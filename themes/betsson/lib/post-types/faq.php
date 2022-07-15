<?php

namespace Lib\PostTypes;

use Lib\Classes\CPT;

class FAQ {
	function __construct() {
		$instance = new CPT( array(
			'post_type_name' => 'faq',
			'singular' => 'FAQ',
			'plural' => 'FAQs',
			'slug' => 'faq'
		), array(
			'supports' => array( 'title', 'editor' ),
			'menu_icon' => 'dashicons-format-status'
		) );
		
		$instance->register_taxonomy( array(
			'taxonomy_name' => 'faq-category',
			'singular' => 'FAQ Category',
			'plural' => 'FAQ Categories',
			'slug' => 'faq-category'
		) );
	}
}

new FAQ();