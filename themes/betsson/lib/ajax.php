<?php

namespace AJAX;

function get_jobs() {
	if( !isset( $_GET['page'] ) ) {
		wp_send_json_error(array(
			'message' => 'Page parameter is required.'
		));
		exit;
	}

	// Initialize query args
	$args = array(
		'post_type' => 'job',
		'meta_query' => array( 
            array(
                'key' => 'gh_job_status',
                'value' => 'active'
            )
        )
	);

	// Get filters
	if( isset( $_GET['keyword'] ) && $_GET['keyword'] ) {
		$args['s'] = sanitize_text_field( $_GET['keyword'] );
	}

	if( isset( $_GET['locations'] ) && $_GET['locations'] ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'job-location',
			'terms' => array_map( function( $item ){
				return intval( $item );
			}, $_GET['locations'] )
		);
	}

	if( isset( $_GET['departments'] ) && $_GET['departments'] ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'job-category',
			'terms' => array_map( function( $item ){
				return intval( $item );
			}, $_GET['departments'] )
		);
	}

	// Page 1 shows initial count of jobs, page 2 shows the reset
	if( $_GET['page'] === '1' ) {

		// Get initial count of jobs from jobs page
		$jobsPageId = get_field( 'jobs_page', 'default-pages' );

		if( !$jobsPageId ) {
			wp_send_json_success(array(
				'message' => 'See all jobs page was not set up.'
			));
			exit;
		}

		$initialCount = get_field( 'jobs_initial_count', $jobsPageId );

		if( !$initialCount ) {
			wp_send_json_success(array(
				'message' => 'Initial jobs count was not set up.'
			));
			exit;
		}

		$args['posts_per_page'] = $initialCount;		
	} elseif( $_GET['page'] === '2' ){
		if( !isset( $_GET['excluded_ids'] ) || !$_GET['excluded_ids'] ) {
			wp_send_json_error(array(
				'message' => 'Excluded IDs parameter is required.'
			));
			exit;
		}

		$args['post__not_in'] = $_GET['excluded_ids'];
		$args['posts_per_page'] = -1;
	} else {
		wp_send_json_success(array(
			'message' => 'Invalid page.'			
		));
		exit;
	}

	// Do the query!
	$query = new \WP_Query( $args );
	
	ob_start();

	// IDs to exclude on page 2
	$excludedIds = array();

	foreach( $query->posts as $job ) {
		include( locate_template( 'templates/includes/job-listing-item.php' ) );

		$excludedIds[] = $job->ID;
	}

	$numberFormat = '%03d';

	if( get_field( 'override_job_match_count_format', $jobsPageId ) ) {
		$numberFormat = '%0'. get_field( 'no_of_digits_to_display', $jobsPageId ) .'d';
	}

	wp_send_json_success(array(
		'message' => 'Success.',
		'html' => ob_get_clean(),
		'has_more' => $query->max_num_pages > 1,
		'excluded_ids' => $excludedIds,
		'found_posts' => sprintf( $numberFormat, intval( $query->found_posts ) )
	));

	exit;
}

add_action( 'wp_ajax_get_jobs', __NAMESPACE__ . '\\get_jobs' );
add_action( 'wp_ajax_nopriv_get_jobs', __NAMESPACE__ . '\\get_jobs' );

/**
 * Get posts listing
 *
 * @return json
 */
function get_posts() {
	
	$postType = isset( $_GET['postType'] ) ? $_GET['postType'] : 'post';
	$term = isset( $_GET['term'] ) ? $_GET['term'] : null;
	$taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : null;
	$excluded = isset( $_GET['excluded'] ) ? $_GET['excluded'] : null;
	$isAjax = true;

	include( locate_template( 'templates/includes/faq-list.php' ) );

	header( 'Content-Type: text/json' );

	echo json_encode( array(
		'hasMore' => $hasMore,
		'html' => $results,
	) );

	wp_die();

}

add_action( 'wp_ajax_get_posts', __NAMESPACE__ . '\\get_posts' );
add_action( 'wp_ajax_nopriv_get_posts', __NAMESPACE__ . '\\get_posts' );

/**
 * Get the rest of news items
 *
 * @return json
 */
function get_more_news() {
	if( !isset( $_GET['excluded_ids'] ) ) {
		wp_send_json_error(array(
			'message' => 'Excluded IDs parameter is required.'
		));
		exit;
	}

	$args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
		'posts_per_page' => -1,
		'post__not_in' => $_GET['excluded_ids'],
        'orderby' => 'ID',      
        'order' => 'DESC'
	);

	if( isset( $_GET['tax_query'] ) ) {		
        $args['tax_query'] = $_GET['tax_query'];
	}
	
	$query = new \WP_Query( $args );
	$posts = $query->posts;

	ob_start();
	include( locate_template( 'templates/includes/latest_news_items.php' ) );

	wp_send_json_success(array(
		'message' => 'Success.',
		'html' => ob_get_clean()		
	));

	exit;
}

add_action( 'wp_ajax_get_more_news', __NAMESPACE__ . '\\get_more_news' );
add_action( 'wp_ajax_nopriv_get_more_news', __NAMESPACE__ . '\\get_more_news' );