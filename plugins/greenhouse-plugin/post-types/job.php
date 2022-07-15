<?php

namespace SGP\PostTypes;

use SGP\Classes\CPT;

class Job {
	function __construct() {
		$instance = new CPT( array(
			'post_type_name' => 'job',
			'singular' => 'Job',
			'plural' => 'Jobs',
			'slug' => 'job'
		), array(
			'supports' => array( 'title', 'editor', 'thumbnail' )
		) );
		
		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-category',
			'singular' => 'Job Category',
			'plural' => 'Job Categories',
			'slug' => 'job-category'
		), array( 'hierarchical' => false ) );

		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-hub',
			'singular' => 'Job Hub',
			'plural' => 'Job Hubs',
			'slug' => 'job-hub'
		), array( 'hierarchical' => false ) );

		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-location',
			'singular' => 'Job Location',
			'plural' => 'Job Locations',
			'slug' => 'job-location'
		), array( 'hierarchical' => false ) );

		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-engagement',
			'singular' => 'Job Engagement',
			'plural' => 'Job Engagements',
			'slug' => 'job-engagement'
		), array( 'hierarchical' => false ) );

		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-recruiter',
			'singular' => 'Job Recruiter',
			'plural' => 'Job Recruiters',
			'slug' => 'job-recruiter'
		), array( 'hierarchical' => false ) );

		$instance->register_taxonomy( array(
			'taxonomy_name' => 'job-department',
			'singular' => 'Job Department',
			'plural' => 'Job Departments',
			'slug' => 'job-department'			
		), array( 'hierarchical' => false, 'show_admin_column' => false ) );
	}
}