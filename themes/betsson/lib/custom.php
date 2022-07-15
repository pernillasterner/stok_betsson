<?php

/**
 * Setting for saving ACF Pro
 */
function acf_json_save_point( $path ) {
	$path = get_stylesheet_directory() . '/lib/json_acf';
	return $path;
}

add_filter( 'acf/settings/save_json', 'acf_json_save_point' );

/**
 * Setting for loading ACF Pro
 */
function my_acf_json_load_point( $paths ) {
	unset( $paths[ 0 ] );
	
	$paths[] = get_stylesheet_directory() . '/lib/json_acf';
	return $paths;
}

add_filter( 'acf/settings/load_json', 'my_acf_json_load_point' );

/**
 * Add image sizes
 */
update_option( 'medium_size_w', 480 );
update_option( 'medium_size_h', 480 );
update_option( 'medium_crop', 1 );
update_option( 'large_size_w', 640 );
update_option( 'large_size_h', 480 );
update_option( 'large_crop', 1 );
add_image_size( '600x400', 600, 400, true );
add_image_size( '560x480', 560, 480, true );
add_image_size( '300x400', 300, 400, true );
add_image_size( '250x190', 250, 190, true );
add_image_size( '260x300', 260, 300, true );
add_image_size( '1160x650', 1160, 650, true );
add_image_size( '1920x480', 1920, 480, true );
add_image_size( '960x480', 960, 480, true );
add_image_size( '160x60', 160, 60, false );
add_image_size( '182x182', 182, 182, false );
add_image_size( '360x270', 360, 270, true );
add_image_size( 'banner-mobile', 375, 580, true );
add_image_size( 'banner-desktop', 1920, 800, true );

// Removed custom sizes
// add_image_size( '560x400', 560, 400, true );
// add_image_size( '560x400-resize', 560, 400, false );
// add_image_size( '334x248', 334, 248, true );
// add_image_size( '145x180', 145, 180, true );
// add_image_size( '760x550', 760, 550, true );
// add_image_size( '1920x130', 1920, 130, true );

/**
 * Modify active menu item
 */
function modify_active_menu_item( $classes, $item ) {
	// Select ancestors of defaults pages

	if( is_singular( 'job' ) ) {
		$jobsPageId = get_field( 'jobs_page', 'default-pages' );
		$parentOfJobsPage = get_post_ancestors( $jobsPageId );

		if( !$parentOfJobsPage )
			return $classes;

		if( $parentOfJobsPage[0] !== intval( $item->object_id ) )
			return $classes;		

		$classes[] = 'current-page-ancestor';
	} elseif( is_singular( 'post' ) ) {
		$categories = get_the_category();
		$pageId = null;
	
		if( !$categories )
			return;
	
		$firstCategory = $categories[0];
	
		if( $firstCategory->slug === 'people' ) {
			$pageId = get_field( 'people_page', 'default-pages' );
		} elseif( $firstCategory->slug === 'benefits' ) {
			$pageId = get_field( 'benefits_page', 'default-pages' );
		} elseif( $firstCategory->slug === 'news' ) {
			$pageId = get_field( 'news_page', 'default-pages' );
		}

		if( $pageId == $item->object_id ) {
			$classes[] = 'current-page-ancestor';
			return $classes;	
		}
	
		$parentOfPage = get_post_ancestors( $pageId );

		if( !$parentOfPage )
			return $classes;

		if( $parentOfPage[0] != $item->object_id )
			return $classes;

		$classes[] = 'current-page-ancestor';
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'modify_active_menu_item', 10, 2 );

/**
 * Remove content editor on pages
 */
function hideEditor() {
	remove_post_type_support( 'page', 'editor' );
}

add_action( 'init', 'hideEditor' );


/**
 *  Removes the h1 tag from the WordPress editor.
 *
 *  @param   array  $settings  The array of editor settings
 *  @return  array             The modified edit settings
 */
add_filter( 'tiny_mce_before_init', 'removeHeadingsFromEditor' );
function removeHeadingsFromEditor( $settings ) {
    $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;';
    return $settings;
}

/**
 * Register new user capabilities on theme activation 
 */
function mytheme_setup_options () {	
	$caps = [
		//* Meta capabilities
		'read',
		'edit_%s',
		'read_%s',
		'delete_%s',
	
		//* Primitive capabilities used outside of map_meta_cap()
		'edit_%ss',
		'edit_others_%ss',
		'publish_%ss',
		'read_private_%ss',
	
		//* Primitive capabilities used within of map_meta_cap()
		'delete_%ss',
		'delete_private_%ss',
		'delete_published_%ss',
		'delete_others_%ss',
		'edit_private_%ss',
		'edit_published_%ss'
	];

	// Add instagram capability to administrator
	$administrator = get_role( 'administrator' );

	foreach( $caps as $cap ) {
		$administrator->add_cap( sprintf( $cap, 'instagram' ) );
	}

	// Remove comment moderation for editor
	$editor = get_role( 'editor' );
	$editor->remove_cap( 'moderate_comments' );
}

add_action('after_switch_theme', 'mytheme_setup_options');

function get_current_user_role() {
	$user = wp_get_current_user();
	$role = ( array ) $user->roles;
	return $role[0];
}

/**
 * Remove admin menu items from editor
 *
 * @return void
 */
function custom_menu_page_removing() {
	if( is_user_logged_in() && is_admin() ) {
		if( get_current_user_role() === 'editor' ) {
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'tools.php' );
		}
	}    
}

add_action( 'admin_menu', 'custom_menu_page_removing' );

/**
 * Remove access to page
 *
 * @return void
 */
function custom_restrict_pages_from_admin() {
    global $pagenow;

	if( get_current_user_role() === 'editor' ) {
		if( in_array( $pagenow, array( 'edit-comments.php', 'tools.php' ) ) ) {
			wp_redirect( admin_url() );
			exit;
		}
	}
}

if( is_admin() ) {
    add_filter( 'init', 'custom_restrict_pages_from_admin' );
}

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/dist/images/betsson_logo.svg);
		height:65px;
		width:320px;
		background-size: 320px 65px;
		background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
    </style>
<?php }

add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}

add_filter( 'login_headerurl', 'my_login_logo_url' );

/**
 * Allow html in category and taxonomy descriptions
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );