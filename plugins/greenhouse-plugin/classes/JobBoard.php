<?php
namespace SGP\Classes;

use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;

class JobBoard {

	private $greenhouseService;

	public function __construct( GreenhouseService $greenhouseService ) {
		$this->greenhouseService = $greenhouseService;		
	}	

	private function getJobsFromApi() {
		$jobApiService = $this->greenhouseService->getJobApiService();
		$jobs = $jobApiService->getJobs( true );

		return json_decode( $jobs )->jobs;
	}

	private function getJobQuestionsFromApi( $id ) {
		$jobApiService = $this->greenhouseService->getJobApiService();
		$job = $jobApiService->getJob($id, true );

		return json_decode( $job )->questions;
	}

	public function embedIframe() {
		$jobBoardService = $this->greenhouseService->getJobBoardService();
		return $jobBoardService->embedGreenhouseJobBoard();
	}

	public function processSubmission( $data ) {
		$appService = $this->greenhouseService->getApplicationApiService();
		
		return $appService->postApplication( $data );
	}

	public function getApplications( $perPage = 5, $page = 1 ) {
		$harvestService = $this->greenhouseService->getHarvestService();

		$params = array(
			'per_page' => $perPage,
			'page' => $page
		);
		
		return $harvestService->getApplications( $params );
	}

	public function updateJobsStatus() {
		global $wpdb;

		$sql = <<<SQL
UPDATE {$wpdb->prefix}postmeta
SET meta_value = 'closed'
WHERE meta_key = 'gh_job_status'
AND post_id IN
(
	SELECT a.post_id FROM
    (
		SELECT post_id FROM {$wpdb->prefix}postmeta
        WHERE meta_key = 'gh_job_closing_date'
		AND meta_value != ''
		AND STR_TO_DATE(meta_value, '%Y-%m-%d') > CURDATE() = 0
	) a	
)
SQL;

		$results = $wpdb->query( $sql );

		$log = 'Closed jobs: ' . $results;

		file_put_contents( SGP_PLUGIN_DIR . 'log.txt', $log, FILE_APPEND );
	}

	public function synchronizeJobs() {
		global $wpdb;		
		$newJobs = 0;
		$closedJobs = 0;
		$updatedJobs = 0;

		// Get all jobs from API
		$jobs = $this->getJobsFromApi( true );

		// Get existing jobs in database
		$result = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key=\"gh_job_id\" AND meta_value IS NOT NULL" );

		// Collect post IDs to deactive
		$postIdsToDeactive = array();

		// Create lookup table
		$lookup = array();

		foreach( $result as $item ) {
			$lookup[$item->meta_value] = $item->post_id;
			$postIdsToDeactive[] = $item->post_id;
		}

		// Process each job
		foreach( $jobs as $job ) {
			// If job already exists in database, check if it needs to be updated by comparing the content
			if( array_key_exists( $job->id, $lookup ) ) {			
				$jobPost = get_post( $lookup[$job->id] );
				$jobContentFromAPI = html_entity_decode( $job->content );

				if( strcmp( $jobContentFromAPI, $jobPost->post_content ) !== 0 ) {
					$this->updateJob( $lookup[$job->id], $job );
					$updatedJobs++;
				}

				update_post_meta( $lookup[$job->id] , 'gh_job_status', 'active' );

				// Remove ID from posts to be deactivated
				if ( ( $key = array_search( $lookup[$job->id], $postIdsToDeactive ) ) !== false ) {
					unset( $postIdsToDeactive[$key] );
				}
			}
			// Otherwise, save it
			else {
				$this->insertJob( $job );
				$newJobs++;
			}
		}

		// Deactivate job posts
		$postIdsToDeactive = implode( ',', $postIdsToDeactive );		
		$wpdb->query( "UPDATE {$wpdb->prefix}postmeta SET meta_value=\"inactive\" WHERE meta_key=\"gh_job_status\" AND post_id IN ($postIdsToDeactive)" );

		// Count inactive jobs
		$result = $wpdb->get_results( "SELECT COUNT(*) AS count FROM {$wpdb->prefix}postmeta WHERE meta_key=\"gh_job_status\" AND meta_value=\"inactive\"" );
		$inactiveJobs = $result[0]->count;
		
		// Create log content
		$log = 'Import date: ' . date( 'm/d/Y h:i:s A', time() ) . "\r\n";
		$log .= 'Pulled jobs: ' . count( $jobs ) . "\r\n";
		$log .= 'New jobs: ' . $newJobs . "\r\n";
		$log .= 'Updated jobs: ' . $updatedJobs . "\r\n";
		$log .= 'Inactive jobs: ' . $inactiveJobs . "\r\n";

		// Bring back the commas in job category terms
		$terms = get_terms( array( 'taxonomy' => 'job-category', 'hide_empty' => true ) );

		foreach( $terms as $term ) {
			if( strpos( $term->name, SGP_COMMA_REPLACEMENT ) !== FALSE ) {
				wp_update_term( $term->term_id, 'job-category', array( 'name' => str_replace( SGP_COMMA_REPLACEMENT, ',', $term->name ) ) );
			}
		}

		file_put_contents( SGP_PLUGIN_DIR . 'log.txt', $log );
	}

	private function insertJob( $job ) {
		// Create post object
		$jobPost = array(
			'post_title' => wp_strip_all_tags( $job->title ),
			'post_content' => html_entity_decode( $job->content ),
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'job'
		);

		// Insert the post into the database
		$id = wp_insert_post( $jobPost );

		$this->saveJobsPostMetas( $id, $job );
		$this->saveTaxonomies( $id, $job );
	}

	private function updateJob( $id, $job ){
		// Update post object
		$jobPost = array(
			'ID' => $id,
			'post_title' => wp_strip_all_tags( $job->title ),
			'post_content' => html_entity_decode( $job->content )		
		);
		
		// Update the post into the database
		wp_update_post( $jobPost );

		$this->saveJobsPostMetas( $id, $job );
		$this->saveTaxonomies( $id, $job );
	}

	private function saveJobsPostMetas( $id, $job ) {
		// Get metadata values
		$metaValues = $this->getMetadataValues( $job->metadata );

		// Update post meta data
		update_post_meta( $id, 'gh_job_status', 'active' );
		update_post_meta( $id, 'gh_job_id', $job->id );
		update_post_meta( $id, 'gh_job_data', serialize( $job ) );
		update_post_meta( $id, 'gh_job_closing_date', $metaValues['closing'] );
		update_post_meta( $id, 'gh_job_internal_job_id', $job->internal_job_id );
		update_post_meta( $id, 'gh_job_responsibilities', $metaValues['responsibilities'] );
		update_post_meta( $id, 'gh_job_candidate_wishlist', $metaValues['wish list'] );
		update_post_meta( $id, 'gh_job_prioritized_ad', $metaValues['prioritised'] );

		// Get and update questions
		$questions = $this->getJobQuestionsFromApi( $job->id );
		update_post_meta( $id, 'gh_job_questions', serialize( $questions ) );
	}

	private function replaceCommas( $value ) {
		return str_replace( ',', SGP_COMMA_REPLACEMENT, $value );
	}

	private function getMetadataValues( $jobMetadata ) {
		$fieldsToGet = array( 'closing', 'responsibilities', 'wish list', 'prioritised', 'country', 'category', 'engagement', 'recruiter' );
		$categoriesToRename = array(
			'Fraud' => 'Fraud, Risk & Payment',
			'Risk & Payments' => 'Fraud, Risk & Payment',
			'Risk & Payment' => 'Fraud, Risk & Payment'
		);
		$output = array();

		foreach( $fieldsToGet as $field ) {
			$output[$field] = null;

			foreach( $jobMetadata as $metadata ) {
				if( strpos( strtolower( $metadata->name ), strtolower( $field ) ) === FALSE ) {
					continue;
				}

				if( $field === 'country' ) {
					$output[$field] = $metadata->value[0];
				} elseif ( $field === 'recruiter' ) {
					if( $metadata->value && $metadata->value->name && $metadata->value->email ) {
						$output[$field]['name'] = $metadata->value->name;
						$output[$field]['email'] = $metadata->value->email;
					}
				} elseif ( $field === 'category' ) {	
					// Replace the commas in job category terms
					if( array_key_exists( $metadata->value, $categoriesToRename ) ) {
						$output[$field] = $this->replaceCommas( $categoriesToRename[$metadata->value] );
					} else {
						$output[$field] = $this->replaceCommas( $metadata->value );
					}
				} else {
					$output[$field] = $metadata->value;
				}

				break;
			}
		}

		return $output;
	}

	private function saveTaxonomies( $id, $job ) {
		// Get metadata values
		$metaValues = $this->getMetadataValues( $job->metadata );

		wp_set_post_terms( $id, $metaValues['country'], 'job-location', false );
		wp_set_post_terms( $id, $metaValues['category'], 'job-category', false );
		wp_set_post_terms( $id, $job->location->name, 'job-hub', false );
		wp_set_post_terms( $id, $metaValues['engagement'], 'job-engagement', false );

		$recruiter = wp_set_post_terms( $id, $metaValues['recruiter']['name'], 'job-recruiter', false );

		// Add recruiter metas
		if( $recruiter ) {
			$recruiter = is_array( $recruiter ) ? $recruiter[0] : $recruiter;
			update_term_meta( $recruiter, 'email', $metaValues['recruiter']['email'] );
		}

		if( $job->departments ) {
			foreach( $job->departments as $item ) {
				wp_set_post_terms( $id, $item->name, 'job-department', false );				
			}			
		}		
	}
}