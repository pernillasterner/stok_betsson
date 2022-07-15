<?php
/**
 * Class that displays all Admin Notices after saving
 *
 * @since 3.0
 */
class VFB_Pro_Admin_Notices {

	/**
	 * form_id
	 *
	 * @var mixed
	 * @access public
	 */
	public $form_id;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->form_id = $this->get_form_id();

		add_action( 'admin_notices', array( $this, 'edit_fields' ) );
		add_action( 'admin_notices', array( $this, 'form_settings' ) );
		add_action( 'admin_notices', array( $this, 'email_settings' ) );
		add_action( 'admin_notices', array( $this, 'confirmation_settings' ) );
		add_action( 'admin_notices', array( $this, 'email_design_settings' ) );
		add_action( 'admin_notices', array( $this, 'rules_settings' ) );
		add_action( 'admin_notices', array( $this, 'addon_settings' ) );
		add_action( 'admin_notices', array( $this, 'vfb_settings' ) );
		add_action( 'admin_notices', array( $this, 'trash_form' ) );
		add_action( 'admin_notices', array( $this, 'duplicate_form' ) );
		add_action( 'admin_notices', array( $this, 'restore_form' ) );
		add_action( 'admin_notices', array( $this, 'delete_form' ) );
		add_action( 'admin_notices', array( $this, 'entries' ) );
	}

	/**
	 * Edit Fields tab
	 *
	 * @access public
	 * @return void
	 */
	public function edit_fields() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-fields' !== $_POST['_vfbp_action'] )
			return;

		// Get max post vars, if available. Otherwise set to 1000
		$max_post_vars = ini_get( 'max_input_vars' ) ? intval( ini_get( 'max_input_vars' ) ) : 1000;

		// Set a message to be displayed if we've reached a limit to max_input_vars
		if ( count( $_POST, COUNT_RECURSIVE ) > $max_post_vars ) {
			$max_input_error = sprintf( __( 'Error saving form. The maximum amount of data allowed by your server has been reached. Please update <a href="%s" target="_blank">max_input_vars</a> in your php.ini file to allow more data to be saved. The current limit is <strong>%d</strong>', 'vfb-pro' ), 'http://www.php.net/manual/en/info.configuration.php#ini.max-input-vars', $max_post_vars );
			echo sprintf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', $max_input_error );
		}
		else {
			echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Field settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
		}
	}

	/**
	 * Form Settings tab
	 *
	 * @access public
	 * @return void
	 */
	public function form_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-form-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Form settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Email Settings tab
	 *
	 * @access public
	 * @return void
	 */
	public function email_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-email-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Email settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Confirmation Settings tab
	 *
	 * @access public
	 * @return void
	 */
	public function confirmation_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-confirmation-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Confirmation settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Email Design tab
	 *
	 * @access public
	 * @return void
	 */
	public function email_design_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-design-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Email Design settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Rules tab
	 *
	 * @access public
	 * @return void
	 */
	public function rules_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-rules-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Rules settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Add-on tab
	 *
	 * @access public
	 * @return void
	 */
	public function addon_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-addon-settings' !== $_POST['_vfbp_action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s%s</p></div>', __( 'Add-on settings saved.' , 'vfb-pro' ), $this->form_preview_link() );
	}

	/**
	 * Settings page
	 *
	 * @access public
	 * @return void
	 */
	public function vfb_settings() {
		if ( !$this->submit_check() )
			return;

		if ( 'save-settings' !== $_POST['_vfbp_action'] )
			return;

		// Special case to handle the Uninstall Plugin button
		if ( isset( $_POST['vfbp-uninstall'] ) ) {
			echo sprintf( '<div id="message" class="updated"><p>%s</p></div>', __( 'VFB Pro has been successfully uninstalled.' , 'vfb-pro' ) );
			return;
		}

		echo sprintf( '<div id="message" class="updated"><p>%s</p></div>', __( 'Settings saved.' , 'vfb-pro' ) );
	}

	/**
	 * Trash form link was clicked
	 *
	 * @access public
	 * @return void
	 */
	public function trash_form() {
		if ( !isset( $_GET['vfb-action'] ) )
			return;

		if ( 'trash-form' !== $_GET['vfb-action'] )
			return;

		$form_id = isset( $_GET['form'] ) ? absint( $_GET['form'] ) : '';

		$undo_url = add_query_arg(
			array(
				'page'       => 'vfb-pro',
				'vfb-action' => 'restore',
				'form'       => $form_id,
			),
			wp_nonce_url( admin_url( 'admin.php' ), 'vfbp_undo_trash' )
		);

		$undo = !empty( $form_id ) ? sprintf( '<a href="%s">%s</a>', esc_url( $undo_url ), __( 'Undo', 'vfb-pro' ) ) : '';

		echo sprintf( '<div id="message" class="updated"><p>%s %s</p></div>', __( 'Item moved to the trash.' , 'vfb-pro' ), $undo );
	}

	/**
	 * Duplicate form link was clicked
	 *
	 * @access public
	 * @return void
	 */
	public function duplicate_form() {
		if ( !isset( $_GET['vfb-action'] ) )
			return;

		if ( 'duplicate-form' !== $_GET['vfb-action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s</p></div>', __( 'Item successfully duplicated.' , 'vfb-pro' ) );
	}

	/**
	 * Undo trash link was clicked
	 *
	 * @access public
	 * @return void
	 */
	public function restore_form() {
		if ( !isset( $_GET['vfb-action'] ) )
			return;

		if ( 'restore' !== $_GET['vfb-action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s</p></div>', __( 'Item restored from the Trash.' , 'vfb-pro' ) );
	}

	/**
	 * Undo trash link was clicked
	 *
	 * @access public
	 * @return void
	 */
	public function delete_form() {
		if ( !isset( $_GET['vfb-action'] ) )
			return;

		if ( 'delete' !== $_GET['vfb-action'] )
			return;

		echo sprintf( '<div id="message" class="updated"><p>%s</p></div>', __( 'Item permanently deleted.' , 'vfb-pro' ) );
	}

	/**
	 * Entries - if Akismet not installed, display an error message if Spam link clicked
	 * @return [type] [description]
	 */
	public function entries() {
		if ( !isset( $_GET['post_type'] ) )
			return;

		if ( 'vfb_entry' !== $_GET['post_type'] )
			return;

		if ( !isset( $_GET['vfb-action'] ) )
			return;

		if ( 'spam' !== $_GET['vfb-action'] )
			return;

		if ( !method_exists( 'Akismet', 'http_post' ) || !function_exists( 'akismet_http_post' ) )
			echo sprintf( '<div id="message" class="notice notice-error"><p>%s</p></div>', __( 'You have attempted to mark an entry as spam, but Akismet is not detected. Please install and activate <a href="https://wordpress.org/plugins/akismet/" target="_blank">Akismet</a>.' , 'vfb-pro' ) );
	}

	/**
	 * form_preview_link function.
	 *
	 * @access private
	 * @return void
	 */
	private function form_preview_link() {
		$preview_url = add_query_arg(
			array(
				'preview'     => true,
				'vfb-form-id' => $this->form_id,
			),
			get_permalink( get_page_by_title( 'VFB Pro - Form Preview' ) )
		);

		if ( current_user_can( 'vfb_edit_forms' ) ) {
			return sprintf( ' <a href="%1$s" target="_blank">%2$s<a>', $preview_url, __( 'View form preview', 'vfb-pro' ) );
		}

		return '';
	}

	/**
	 * Returns the Form ID
	 *
	 * @access private
	 * @return void
	 */
	private function get_form_id() {
		// Exit if not on vfb-pro page
		if ( isset( $_GET['page'] ) && 'vfb-pro' !== $_GET['page'] )
			return;

		// Exit if form var isn't passed
		if ( !isset( $_GET['form'] ) )
			return;

		return absint( $_GET['form'] );
	}

	/**
	 * Basic check to exit if the form hasn't been submitted
	 *
	 * @access public
	 * @return void
	 */
	public function submit_check() {
		if ( !isset( $_POST['_vfbp_action'] ) )
			return;

		$pages = array(
			'vfb-pro',
			'vfb-add-new',
			'vfbp-settings',
		);

		if ( !in_array( $_GET['page'], $pages ) )
			return;

		return true;
	}
}
