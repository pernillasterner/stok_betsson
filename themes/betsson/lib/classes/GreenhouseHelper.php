<?php

namespace Lib\Classes;

class GreenhouseHelper {

	public static function process_submission_redirect() {
		// Check form is submitted
		if( !isset( $_POST['job_application_form'] ) || !isset( $_POST['post_id'] ) || !isset( $_POST['id'] ) ) {
			return;
		}

		// Get form fields
		$questions = get_post_meta( $_POST['post_id'], 'gh_job_questions', true );
		$questions = unserialize( $questions );

		try {
			// Check WP nonce
			if( !isset( $_POST['_wpnonce'] ) || !\wp_verify_nonce( $_POST['_wpnonce'], 'submit-application_' . $_POST['id'] ) ) {
				throw new \RuntimeException( 'Invalid request. Please try again.' );
			}

			// Initialize form data
			$data = array( 'id' => $_POST['id'] );

			// Validate fields
			foreach( $questions as $question ) {
				if( !is_array( $question->fields ) ) {
					continue;
				}

				foreach( $question->fields as $field ) {
					try {
						if( $field->type === 'input_text' ) {
							// Check required text fields
							if( !trim( $_POST[$field->name] ) ) {
								if( $question->required ) {
									throw new \RuntimeException( 'This field is required.' );
								}

								// Check if email
								if( $field->name === 'email' && !is_email( $_POST[$field->name] ) ) {
									throw new \RuntimeException( 'Invalid email.' );
								}

								continue;
							}

							$data[$field->name] = $_POST[$field->name];
						} else if( $field->type === 'input_file' ) {
							// If field is required, check it in $_FILES array
							if ( $question->required && !isset( $_FILES[$field->name] ) ) {
								throw new \RuntimeException( 'This field is required.' );
							}

							if( !isset( $_FILES[$field->name] ) || !$_FILES[$field->name]['tmp_name'] ){
								continue;
							}

							// Check file error
							switch ( $_FILES[$field->name]['error'] ) {
								case UPLOAD_ERR_OK:
									break;
								case UPLOAD_ERR_NO_FILE:
									if( $question->required ) {
										throw new \RuntimeException( 'No file sent.' );
									} else {
										break;
									}
								case UPLOAD_ERR_INI_SIZE:
								case UPLOAD_ERR_FORM_SIZE:
									throw new \RuntimeException( 'Exceeded filesize limit.' );
								default:
									throw new \RuntimeException( 'Unknown errors.' );
							}

							// You should also check filesize here.
							// Limit to 5 MB
							if ( $_FILES[$field->name]['size'] > 5000000 ) {
								throw new \RuntimeException( 'File size must be less than 5 MB.' );
							}

							// Check file extensions
							$acceptedExtensions = array( 'pdf', 'doc', 'docx' );
							$explodedFilename = explode( '.', $_FILES[$field->name]['name'] );
							$ext = strtolower( array_pop( $explodedFilename ) );
							if ( !in_array( $ext, $acceptedExtensions ) ) {
								throw new \RuntimeException( 'Invalid file type. Acceptable types are *.pdf, *.doc, *.docx.' );
							}

							$data[$field->name] = new \CURLFile( $_FILES[$field->name]['tmp_name'], $_FILES[$field->name]['type'], $_FILES[$field->name]['name'] );
						}
					} catch ( \Exception $ex ) {
						$GLOBALS['job_application_errors'][$field->name] = $ex->getMessage();
					}
				}
			}

			if( !empty( $GLOBALS['job_application_errors'] ) ) {
				return;
			}

			$result = $GLOBALS['sgp_job_board']->processSubmission( $data );

			$result = json_decode( $result );

			if( property_exists( $result, 'success' ) ) {
				$thankYouPage = get_field( 'thank_you_page', 'jobs' );

				// Redirect to thank you page
				wp_redirect( $thankYouPage );
				die;
			}
		} catch ( \Exception $ex ) {
			$GLOBALS['job_application_errors']['general'] = $ex->getMessage();
		}
	}

	public static function get_application_form( $job ) {
		$id = get_post_meta( $job->ID, 'gh_job_id', true );
		$questions = get_post_meta( $job->ID, 'gh_job_questions', true );
		$questions = unserialize( $questions );

		if( !$questions ) {
			return;
		}

		ob_start();

		echo '<section class="section form"><div class="container"><div class="box">';

		$textBeforeForm = get_field( 'text_before_application_form', 'jobs' );
		if( $textBeforeForm ) {
			echo '<div class="box-header"><p>' . $textBeforeForm . '</p></div>';
		}

		echo '<form method="post" enctype="multipart/form-data" class="js-job-application-form">';

		// Show general error
		if( isset( $GLOBALS['job_application_errors']['general'] ) ) {
			echo '<label class="error">' . $GLOBALS['job_application_errors']['general'] . '</label>';
		}

		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<input type=\"hidden\" name=\"post_id\" value=\"$job->ID\">";
		wp_nonce_field( 'submit-application_' . $id );

		foreach( $questions as $question ) {
			$requiredLabel = $question->required ? '*' : '';
			$requiredField = $question->required ? 'required' : '';

			echo '<div class="form-group">';
			echo "<label>$question->label $requiredLabel</label>";

			foreach( $question->fields as $field ) {
				switch( $field->type ) {
					case 'input_text':
						$value = '';

						if( isset( $_POST[$field->name] ) ) {
							$value = $_POST[$field->name];
						}

						if( $field->name === 'email' ) {
							echo "<input class=\"form-control\" type=\"email\" name=\"$field->name\" $requiredField value=\"$value\">";
							break;
						}

						echo "<input class=\"form-control\" type=\"text\" name=\"$field->name\" $requiredField value=\"$value\">";
						break;
					case 'input_file':
						echo '<span class="btn btn-secondary btn-file">Select File ';
						echo "<input type=\"file\" name=\"$field->name\" $requiredField accept=\".pdf,.doc,.docx\" title=\"Accepted files formats are PDF, DOC and DOCX. Maximum file size is 5 MB.\">";
						echo '</span><div class="selected-file"><!-- Container for selected file (File selected displayed via jQuery) form.js --></div>';
						break;
				}

				// Show field error if any
				if( isset( $GLOBALS['job_application_errors'][$field->name] ) ) {
					echo '<label class="error">' . $GLOBALS['job_application_errors'][$field->name] . '</label>';
				}
			}

			echo '</div>';
		}

		echo '<p class="button-bottom text-center"><input type="submit" class="btn btn-secondary" value="Submit Application" name="job_application_form"/></p>';
		echo '</form>';

		echo '</div></div></section>';

		return ob_get_clean();
	}

	/**
	 * Redirect not found, inactive and closed jobs to job listing page
	 */
	public static function process_invalid_job_redirect() {
		// Check if query is for job CPT
		if( !isset( $GLOBALS['wp_query']->query['post_type'] ) || $GLOBALS['wp_query']->query['post_type'] !== 'job' ) {
			return;
		}

		// Check if 404
		if( $GLOBALS['post'] ) {
			// Check job status
			$id = $GLOBALS['post']->ID;
			$status = get_post_meta( $id, 'gh_job_status', true );

			if( !in_array( $status, array( 'closed', 'inactive' ) ) ) {
				return;
			}
		}

		// Check all jobs page
		$jobsPageId = get_field( 'jobs_page', 'default-pages' );

		if( !$jobsPageId ) {
			return;
		}

		// Redirect
		wp_redirect( get_permalink( $jobsPageId ) );
        die;
	}

	public static function get_mobile_application_form(){
		$post = $GLOBALS['post'];

		ob_start();

		// Desktop apply button
		echo '<div class="text-center button-top hidden-xs"><a href="#" class="js-scroll-to-desktop-form btn btn-secondary">Apply Now!</a></div>';

		// Mobile apply button (accordion switcher)
		echo '<p class="text-center button-top button-full visible-xs toggler"><a href="#" class="btn-full js-toggle-mobile-form btn btn-secondary">Apply Now! <span class="icon-arrow_down"></span></a></p>';

		// Form accordion
		echo '<div id="job-application-form-mobile-container" class="visible-xs">';
		echo '<div style="display:none">';
		echo self::get_application_form( $post );
		echo '</div>';
		echo '</div>';

		return ob_get_clean();
	}
}

add_action( 'template_redirect', 'Lib\Classes\GreenhouseHelper::process_invalid_job_redirect', 11 );
add_action( 'template_redirect', 'Lib\Classes\GreenhouseHelper::process_submission_redirect', 12 );
