<?php
if( function_exists( 'acf_add_options_page' ) ) {

	$menuSlug = 'betsson-general-settings';

	acf_add_options_page( array(
		'page_title' => 'Sitewide',
		'menu_title' => 'Sitewide',
		'menu_slug' => $menuSlug,
		'redirect' => true
	) );

	$menus = array( 'Jobs', 'Default Texts', 'Locations', 'Departments', 'Brands', 'Contact', 'Offices', 'Default Images', 'General', 'Default Pages' );

	foreach( $menus as $item ) {
		$slug = str_replace( ' ', '-', strtolower( $item ) );

		acf_add_options_sub_page( array(
			'page_title' => $item,
			'menu_title' => $item,
			'menu_slug' => $menuSlug . '-'  . $slug,
			'post_id' => $slug,
			'parent_slug' => $menuSlug,
		) );
	}	
}