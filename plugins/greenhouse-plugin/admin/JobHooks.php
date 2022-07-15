<?php

namespace SGP\Admin;

class JobHooks {
	public function __construct() {
		add_filter( 'template_include', array( &$this, 'loadJobTemplate' ), 99 );

		// Add the custom columns to the job post type
		add_filter( 'manage_job_posts_columns', array( &$this, 'setJobsCustomColumns' ) );

		// Add the data to the custom columns for the job post type
		add_action( 'manage_job_posts_custom_column' , array( &$this, 'setJobsCustomColumnsData' ), 10, 2 );
		add_filter( 'manage_edit-job_sortable_columns', array( &$this, 'setJobsSortableColumns' ) );

		// Modify jobs sorting in admin
		add_action( 'pre_get_posts', array( &$this, 'setJobsOrdering' ) );

		add_action( 'wp_ajax_import_jobs', array( &$this, 'runAjax' ) );
		add_action( 'wp_ajax_nopriv_import_jobs', array( &$this, 'runAjax' ) );

		// Recruiter fields
		add_action( 'job-recruiter_edit_form_fields', array( &$this, 'registerRecruiterAddFormFields' ) );
		add_action( 'edit_job-recruiter', array( &$this, 'saveRecruiterFormFields' ) );		
		add_action( 'admin_enqueue_scripts', array( &$this, 'loadRecruiterScripts' ) );
	}

	public function registerRecruiterAddFormFields( $term ) {
		wp_enqueue_media();
		
		$email = get_term_meta( $term->term_id, 'email', true );
		$image = get_term_meta( $term->term_id, 'image', true );
		$imageSource = $image ? wp_get_attachment_image_url( $image, 'full' ) : '';
	?>
		<tr class="form-field">
			<th scope="row"><label for="email">Email</label></th>
			<td>
				<input type="email" name="email" id="email" value="<?php echo esc_attr( $email ); ?>"/>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="image">Image</label></th>
			<td>
				<div class="image-preview-wrapper">
					<img id="image-preview" src="<?= $imageSource ?>" height="150" style="max-height: 150px;">
				</div>
				<input id="upload_image_button" type="button" class="button" value="Upload Image" />
				<input type="hidden" name="image" id="image_attachment_id" value="<?= $image ?>">
			</td>			
		</tr>
	<?php
	}	

	function loadRecruiterScripts() {
		global $pagenow;

		if( $pagenow !== 'term.php' || $_GET['taxonomy'] !== 'job-recruiter' )
			return;

		wp_enqueue_script( 'sgp_image_selector_js', SGP_PLUGIN_DIR_URL . 'admin/assets/js/image-selector.js', array( 'jquery' ), '1.0.0' );
	}

	public function saveRecruiterFormFields( $term_id ) {
		if( !is_email( $_POST[ 'email' ] ) )
			return;

		update_term_meta( $term_id, 'email', $_POST['email'] );

		if( !isset( $_POST[ 'image' ] ) )
			return;

		update_term_meta( $term_id, 'image', $_POST['image'] );
	}

	public function runAjax() {
		global $task;

		ini_set('max_execution_time', PHP_INT_MAX);		

		try {
			$task->run();
			$log = file_get_contents( SGP_PLUGIN_DIR . 'log.txt' );
		} catch ( \Exception $exception ) {
			wp_send_json_error(array(
				'message' => 'Error encountered while importing jobs.',
				'details' => $exception->getMessage()
			));
		}		
		
		wp_send_json_success(array(
			'message' => 'Jobs successfully imported.',
			'log' => $log
		));
	}
	
	function setJobsOrdering( $query ) {
		if( !is_admin() )
			return;
	
		$orderby = $query->get( 'orderby' );
	
		if( 'closing_date' === $orderby ) {
			$query->set( 'meta_key', 'gh_job_closing_date' );
			$query->set( 'orderby', 'meta_value' );
		} elseif( 'status' === $orderby ) {
			$query->set( 'meta_key', 'gh_job_status' );
			$query->set( 'orderby', 'meta_value' );
		} elseif( 'is_prioritized' === $orderby ) {
			$query->set( 'meta_key', 'gh_job_prioritized_ad' );
			$query->set( 'orderby', 'meta_value' );
		}
}

	public function setJobsSortableColumns( $columns ) {
		$columns['closing_date'] = 'closing_date';
		$columns['status'] = 'status';
		$columns['is_prioritized'] = 'is_prioritized';
	
		return $columns;
	}

	public function setJobsCustomColumns( $columns ) {
		$columns['is_prioritized'] = 'Is Prioritized';
		$columns['closing_date'] = 'Closing Date';
		$columns['status'] = 'Status';		

		return $columns;
	}
	public function setJobsCustomColumnsData( $column, $postId ) {
		switch ( $column ) {

			case 'closing_date' :
				$closingDate = get_post_meta( $postId, 'gh_job_closing_date', true );
				echo $closingDate;
				break;

			case 'status' :
				$status = get_post_meta( $postId, 'gh_job_status', true );
				echo ucwords( $status );
				break;

			case 'is_prioritized' :
				$status = get_post_meta( $postId, 'gh_job_prioritized_ad', true );
				echo $status == 1 ? 'Yes' : 'No';
				break;

		}
	}

	/**
	 * Use default template for jobs if single-job.php is not found in theme
	 *
	 * @param String $template
	 * @return void
	 */
	public function loadJobTemplate( $template ) {
		if ( is_singular('job') ) {
			if( !locate_template( 'single-job.php' ) ){
				return SGP_PLUGIN_DIR . 'single-job.php';
			}			
		}

		return $template;
	}
}