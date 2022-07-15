<?php

namespace SGP\Admin;

/**
 * Description of Settings
 *
 * @package package
 * @version $id: Settings.php $
 */
class Settings {

	private $settings;
	
	public function __construct() {		
		$this->settings = get_option( 'sgp_settings' );

		add_action( 'admin_menu', array( &$this, 'registerSubmenu' ) );
		add_action( 'admin_init', array( &$this, 'registerSettings' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'loadAdminScripts' ) );
	}

	public function loadAdminScripts( $hook ) {
		if ( $hook !== 'settings_page_sgp_settings_page' )
			return;

		wp_enqueue_script( 'sgp_js', SGP_PLUGIN_DIR_URL . 'admin/assets/js/scripts.js', array( 'jquery' ), '1.0.0' );
	}

	public function getSettings() {
		return $this->settings;
	}

	public function registerSubmenu() {		
		add_submenu_page( 'options-general.php', SGP_MENU_TITLE, SGP_MENU_TITLE, 'manage_options', 'sgp_settings_page', array( &$this, 'submenuPageCallback' ) );
	}
	
	public function registerSettings() {
		register_setting( 'sgp_settings_group', 'sgp_settings' );
	}

	private function getFields() {
		return array(
			array(
				'name' => 'api_key',
				'type' => 'text',
				'label' => 'API Key'
			),
			array(
				'name' => 'board_token',
				'type' => 'text',
				'label' => 'Board Token'
			)
		);
	}
	
	public function submenuPageCallback() {
		$settings = $this->settings;		
		$fields = $this->getFields();

		if( file_exists( SGP_PLUGIN_DIR . 'log.txt' ) ) {
			$log = file_get_contents( SGP_PLUGIN_DIR . 'log.txt' );
		}
		
		include( SGP_PLUGIN_DIR . 'admin/includes/form.php' );
    }	
}