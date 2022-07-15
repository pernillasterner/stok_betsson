<?php
namespace SGP\Classes;

use OOCD\ConsentApi;
use SGP\Classes\JobBoard;
use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;
use SGP\Admin\Settings;

require_once( SGP_PLUGIN_DIR . 'admin/Settings.php' );
require_once( SGP_PLUGIN_DIR . 'vendor/autoload.php' );
require_once( SGP_PLUGIN_DIR . 'classes/JobBoard.php' );

/**
 * Description of Task
 *
 * @package package
 * @version $id: Task.php $
 */
class Task {
	public function __construct() {
		$this->name = SGP_PLUGIN_NAME . '_job';
		
		add_filter( 'cron_schedules', array( &$this, 'addScheduledInterval' ) );
		
		add_action( 'init', array( &$this, 'activate' ) );
		add_action( $this->name, array( &$this, 'run' ) );		
		
		register_deactivation_hook( SGP_PLUGIN_FILE, array( &$this, 'deactivate' ) );
	}
	
	public function activate() {
		if( !wp_next_scheduled( $this->name ) ) {
			wp_schedule_event( time(), 'minutes_5', $this->name );
		}
	}
	
	public function deactivate() {
		$timestamp = wp_next_scheduled( $this->name );
		
		wp_unschedule_event( $timestamp, $this->name );
		
		wp_clear_scheduled_hook( $this->name );
	}
	
	public function run() {		
		$settings = new Settings();

		$settingsArray = $settings->getSettings();

		if( !$settingsArray['api_key'] || !$settingsArray['board_token'] ) {
			return;
		}

		$greenhouseService = new GreenhouseService( array(
			'apiKey' => $settingsArray['api_key'],
			'boardToken' => $settingsArray['board_token']
		) );

		$jobBoard = new JobBoard( $greenhouseService );
		$jobBoard->synchronizeJobs();
		$jobBoard->updateJobsStatus();
	}
	
	public function addScheduledInterval( $schedules ) {
		$schedules[ 'minutes_5' ] = array( 'interval' => 300, 'display' => 'Once Every 5 Minutes' );

		return $schedules;
	}
}