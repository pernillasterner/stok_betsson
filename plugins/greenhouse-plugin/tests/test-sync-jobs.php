<?php
/* 
 * Test importing and saving jobs
 */

use SGP\Classes\JobBoard;
use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;
use SGP\Admin\Settings;

require_once( 'load-wp.php' );
require_once( 'admin\Settings.php' );
require_once( 'vendor\autoload.php' );
require_once( 'classes\JobBoard.php' );

$settings = new Settings();

$settingsArray = $settings->getSettings();

$greenhouseService = new GreenhouseService( array(
	'apiKey' => $settingsArray['api_key'],
	'boardToken' => $settingsArray['board_token']
) );

$jobBoard = new JobBoard( $greenhouseService );
$jobBoard->synchronizeJobs();