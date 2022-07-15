<?php
/**
 * Handles form confirmation actions
 *
 * @since      3.0
 */
class VFB_Pro_Confirmation {

	/**
	 * form
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
	public function __construct( $form_id ) {
		$this->form_id = $form_id;
	}

	/**
	 * Prepend the text message to the form
	 *
	 * Instead of replacing the form with the message,
	 * this will prepend the message to a fresh form
	 *
	 * @access public
	 * @return void
	 */
	public function prepend_text() {
		$data = $this->get_settings();

		$type     = isset( $data['confirmation-type'] ) ? $data['confirmation-type'] : 'text';
		$prepend  = isset( $data['text-prepend'] ) ? $data['text-prepend'] : false;

		if ( 'text' !== $type )
			return;

		return $prepend;
	}

	/**
	 * Text message confirmation
	 *
	 * @access public
	 * @param mixed $message
	 * @return void
	 */
	public function text() {
		$data = $this->get_settings();

		$type     = isset( $data['confirmation-type'] ) ? $data['confirmation-type'] : 'text';
		$message  = isset( $data['text-message'] ) ? $data['text-message'] : '';

		if ( 'text' !== $type )
			return;

		return $message;
	}

	/**
	 * WordPress Page redirect
	 *
	 * @access public
	 * @param mixed $page
	 * @return void
	 */
	public function wp_page() {
		$data = $this->get_settings();

		$type       = isset( $data['confirmation-type'] ) ? $data['confirmation-type'] : 'text';
		$page       = isset( $data['wp-page'] ) ? $data['wp-page'] : '';
		$query_vars = isset( $data['wp-page-query-vars'] ) ? $data['wp-page-query-vars'] : '';

		if ( 'wp-page' !== $type )
			return;

		$data      = $this->form_data();
		$permalink = !empty( $query_vars ) ? add_query_arg( $data, $permalink ) : get_permalink( $page );
		wp_redirect( esc_url_raw( $permalink ) );

		exit();
	}

	/**
	 * Custom URL redirect
	 *
	 * @access public
	 * @param mixed $url
	 * @return void
	 */
	public function redirect() {
		$data = $this->get_settings();

		$type       = isset( $data['confirmation-type'] ) ? $data['confirmation-type'] : 'text';
		$redirect   = isset( $data['redirect'] ) ? $data['redirect'] : '';
		$query_vars = isset( $data['redirect-query-vars'] ) ? $data['redirect-query-vars'] : '';

		if ( 'redirect' !== $type )
			return;

		$data = $this->form_data();
		$url  = !empty( $query_vars ) ? add_query_arg( $data, $redirect ) : $redirect;
		wp_redirect( esc_url_raw( $url ) );

		exit();
	}

	/**
	 * Build an array of the submitted $_POST form data
	 *
	 * @return $data
	 */
	public function form_data() {
		// Build array from $_POST
		$data = array();
		foreach ( $_POST as $key => $val ) {
			// Remove special form fields that begin with an underscore
			if ( substr( $key, 0, 1 ) != '_' )
				$data[ $key ] = $val;

			// Remove the honeypot dummy field
			if ( 'vfbp-EMAIL-AN8fuQyoDLXem' == $key )
				unset( $data[ $key ] );

			// Special case to handle Radio "Other" option
			if ( strpos( $key, '-other' ) !== false ) {
				// If "Other" text input is not empty
				if ( !empty( $val ) ) {
					// Get the "non-Other" name attr
					$temp_key = preg_replace( '/(.*?)-other/', '$1', $key );

					// Replace the vfb-field-{xx} value with the "Other" input
					$data[ $temp_key ] = $val;
				}

				// Remove "Other" radio value from entry
				unset( $data[ $key ] );
			}
		}

		return $data;
	}

	/**
	 * Get confirmaton settings
	 *
	 * @access public
	 * @return void
	 */
	public function get_settings() {
		$form_id = $this->get_form_id();
		if ( !$form_id )
			return;

		$vfbdb  = new VFB_Pro_Data();
		$form   = $vfbdb->get_confirmation_settings( $form_id );

		return $form;
	}

	/**
	 * Get just created Entry ID.
	 *
	 * @access public
	 * @return void
	 */
	public function get_entry_id() {
		$form_id = $this->get_form_id();
		if ( !$form_id )
			return;

		$vfbdb    = new VFB_Pro_Data();
		$settings = $vfbdb->get_form_settings( $form_id );

		if ( !isset( $settings['data']['last-entry'] ) )
			return 0;

		return $settings['data']['last-entry'];
	}

	/**
	 * Get form ID
	 *
	 * @access private
	 * @return void
	 */
	public function get_form_id() {
		if ( !isset( $this->form_id ) )
			return false;

		return (int) $this->form_id;
	}

	/**
	 * Basic check to exit if the form hasn't been submitted
	 *
	 * @access public
	 * @return void
	 */
	public function submit_check() {
		// If class form ID hasn't been set, exit
		if ( !$this->get_form_id() )
			return;

		// If form ID hasn't been submitted by $_POST, exit
		if ( !isset( $_POST['_vfb-form-id'] ) )
			return;

		// If class form ID doesn't match $_POST form ID, exit
		if ( $this->get_form_id() !== absint( $_POST['_vfb-form-id'] ) )
			return;

		return true;
	}
}
