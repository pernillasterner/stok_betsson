<?php
/**
 * Plugin Name:	Greenhouse Plugin
 * Description: Imports Greenhouse jobs to custom post type.
 * Author: STOK
 * Version: 1.0.5
 */

use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;
use SGP\Admin\Settings;
use SGP\Admin\JobHooks;
use SGP\Classes\Task;
use SGP\PostTypes\Job;
use SGP\Classes\JobBoard;

if( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Constants
define( 'SGP_PLUGIN_NAME', 'sgp' );
define( 'SGP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SGP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'SGP_MENU_TITLE', 'Greenhouse Plugin' );
define( 'SGP_PLUGIN_FILE', __FILE__ );
define( 'SGP_COMMA_REPLACEMENT', '|' );

$includes = [
	SGP_PLUGIN_DIR . 'admin/Settings.php',
	SGP_PLUGIN_DIR . 'admin/JobHooks.php',	
	SGP_PLUGIN_DIR . 'vendor/autoload.php',
	SGP_PLUGIN_DIR . 'classes/CPT.php',
	SGP_PLUGIN_DIR . 'post-types/job.php',
	SGP_PLUGIN_DIR . 'classes/Task.php',
	SGP_PLUGIN_DIR . 'classes/JobBoard.php',
];

foreach( $includes as $file ) {
	require_once $file;
}

// Initialize settings
$settings = new Settings();

// Register CPTs
new Job();

// Create cron task
$task = new Task();

// Register job hooks
new JobHooks();

// Create global jobBoard instance
$settingsArray = $settings->getSettings();

if( !$settingsArray['api_key'] || !$settingsArray['board_token'] ) {
	return;
}

$greenhouseService = new GreenhouseService( array(
	'apiKey' => $settingsArray['api_key'],
	'boardToken' => $settingsArray['board_token']
) );

$GLOBALS['sgp_job_board'] = new JobBoard( $greenhouseService );