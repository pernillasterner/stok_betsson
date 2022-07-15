<?php

namespace Lib\PostTypes;

use Lib\Classes\CPT;

class Instagram {
	function __construct() {
		$instance = new CPT( array(
			'post_type_name' => 'instagram',
			'singular' => 'Instagram',
			'plural' => 'Instagram',
			'slug' => 'instagram'
		), array(
			'supports' => array( 'title', 'thumbnail', 'editor' ),
			'menu_icon' => 'dashicons-camera',
			'capability_type' => 'instagram',
    		'map_meta_cap'    => true
		) );
		
		$instance->register_taxonomy( array(
			'taxonomy_name' => 'hashtag',
			'singular' => 'Hashtag',
			'plural' => 'Hashtags',
			'slug' => 'hashtag'
		) );
	}
}

new Instagram();