<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  'lib/custom.php',
  'lib/ajax.php',
  'lib/security.php', // Security settings
  'lib/acf_options.php',
  'lib/classes/CPT.php',
  'lib/classes/GreenhouseHelper.php',
  'lib/classes/ContentHelper.php',
  'lib/classes/GeneralHelper.php',
  'lib/classes/VideoHelper.php',
  'lib/post-types/faq.php',
  'lib/post-types/instagram.php',
];

if( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
	$short_codes = array( 'lib/shortcodes/video.php', 'lib/shortcodes/button.php' );
	$sage_includes = array_merge( $sage_includes, $short_codes );
}

if (  current_user_can( 'subscriber' ) ) {
  add_filter('show_admin_bar', '__return_false');
 }

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

function pr($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

