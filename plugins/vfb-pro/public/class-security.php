<?php
/**
 * Handles all security checks
 *
 * reCAPTCHA, CSRF, etc
 *
 * @since      3.0
 */
class VFB_Pro_Security {
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * csrf_check function.
	 *
	 * @access public
	 * @return void
	 */
	public function csrf_check() {
		$form_id = isset( $_POST['_vfb-form-id'] ) ? absint( $_POST['_vfb-form-id'] ) : 0;

		if ( !isset( $_POST['_vfb-token-' . $form_id] ) )
			return true;

		try {
			// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
			VFB_Pro_NoCSRF::check( '_vfb-token-' . $form_id, $_POST, true, 60*10, false );
			return true;
		}
		catch ( Exception $e ) {
			// CSRF attack detected
			return $e->getMessage();
		}
	}

	/**
	 * honeypot_check function.
	 *
	 * @access public
	 * @return void
	 */
	public function honeypot_check() {
		if ( !isset( $_POST['vfbp-EMAIL-AN8fuQyoDLXem'] ) )
			return true;

		if ( isset( $_POST['vfbp-EMAIL-AN8fuQyoDLXem'] ) && !empty( $_POST['vfbp-EMAIL-AN8fuQyoDLXem'] ) )
			return __( 'Security check: you filled out a form field that was created to stop spam bots and should be left blank. If you think this is an error, please email the site owner.', 'vfb-pro' );

		return true;
	}

	/**
	 * recaptcha_check function.
	 *
	 * @access public
	 * @return void
	 */
	public function recaptcha_check() {
		$vfb_settings  = get_option( 'vfbp_settings' );
		$private_key   = $vfb_settings['recaptcha-private-key'];

		/**
		 * Filter the Google reCAPTCHA Private Key
		 *
		 * Changing this value will alter the private key used by
		 * Google reCAPTCHA.
		 *
		 * @since 3.4
		 *
		 */
		$private_key   = apply_filters( 'vfbp_recaptcha_private_key', $private_key );

		if ( !isset( $_POST['_vfb_recaptcha_enabled'] ) )
			return true;

		if ( 1 !== absint( $_POST['_vfb_recaptcha_enabled'] ) )
			return __( 'Security check: reCaptcha verification has been tampered with. If you think this is an error, please email the site owner.', 'vfb-pro' );

		if ( !isset( $_POST['g-recaptcha-response'] ) )
			return __( 'Security check: reCaptcha verification expects a response and did not see one. Please submit the form again. If you think this is an error, please email the site owner.', 'vfb-pro' );

		$url = add_query_arg(
			array(
				'remoteip' => $_SERVER['REMOTE_ADDR'],
				'response' => esc_html( $_POST['g-recaptcha-response'] ),
				'secret'   => $private_key
			),
			'https://www.google.com/recaptcha/api/siteverify'
		);

		$errors = array(
			'missing-input-secret'   => __( 'The private key for reCAPTCHA is missing.', 'vfb-pro' ),
			'invalid-input-secret'   => __( 'The private key for reCAPTCHA is invalid or malformed.', 'vfb-pro' ),
			'missing-input-response' => __( 'The reCAPTCHA response is missing.', 'vfb-pro' ),
			'invalid-input-response' => __( 'The reCAPTCHA response is invalid or malformed.', 'vfb-pro' ),
		);

		$response = wp_remote_get( esc_url_raw( $url ) );
		if ( !is_wp_error( $response ) ) {
			$resp = json_decode( $response['body'], true );

			if ( $resp['success'] ) {
				return true;
			}
			elseif ( $resp['error-codes'] ) {
				$messages = array();

				foreach ( $resp['error-codes'] as $error ) {
					if ( isset( $errors[ $error ] ) )
						$messages[] = $errors[ $error ];
				}

				return implode( "\n", $messages );
			}
		}
	}

	/**
	 * Simple two digit captcha check
	 * @return [type] [description]
	 */
	public function simple_captcha_check() {
		$form_id = isset( $_POST['_vfb-form-id'] ) ? absint( $_POST['_vfb-form-id'] ) : 0;

		if ( !isset( $_POST['_vfb_captcha_simple_enabled'] ) )
			return true;

		if ( 1 !== absint( $_POST['_vfb_captcha_simple_enabled'] ) )
			return __( 'Security check: Captcha verification has been tampered with. If you think this is an error, please email the site owner.', 'vfb-pro' );

		if ( !isset( $_POST['_vfb_captcha_simple-' . $form_id] ) )
			return true;

		$captcha_value = $_POST['_vfb_captcha_simple-' . $form_id];

		if ( !is_numeric( $captcha_value ) || strlen( $captcha_value ) !== 2 )
			return __( 'Security check: It appears you have failed to properly answer the security Captcha question. Please go back and try again.', 'vfb-pro' );

		return true;
	}

	/**
	 * Make sure the User Agent string is not a SPAM bot.
	 *
	 * Returns true if NOT a SPAM bot
	 *
	 * @access public
	 * @return void
	 */
	public function bot_check() {
		$bots = array(
			'<', '>', '&lt;', '%0A', '%0D', '%27', '%3C', '%3E', '%00', 'href',
			'binlar', 'casper', 'cmsworldmap', 'comodo', 'diavol',
			'dotbot', 'feedfinder', 'flicky', 'ia_archiver', 'jakarta',
			'kmccrew', 'nutch', 'planetwork', 'purebot', 'pycurl',
			'skygrid', 'sucker', 'turnit', 'vikspider', 'zmeu',
		);

		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_kses_data( $_SERVER['HTTP_USER_AGENT'] ) : '';

		foreach ( $bots as $bot ) {
			if ( stripos( $user_agent, $bot ) !== false )
				return __( 'Security check: looks like you are a SPAM bot. If you think this is an error, please email the site owner.' , 'vfb-pro' );
		}

		return true;
	}

	 /**
 	 * Checks the form was not submitted too quickly or too slowly.
 	 *
 	 * @access public
 	 * @return void
 	 */
	public function timestamp_check() {
		$form_id = isset( $_POST['_vfb-form-id'] ) ? absint( $_POST['_vfb-form-id'] ) : 0;

		if ( !isset( $_POST['_vfb-timestamp-' . $form_id] ) )
			return true;

		$min_time  = apply_filters( 'vfbp_timestamp_min', 3 ); 		// 3 seconds
		$max_time  = apply_filters( 'vfbp_timestamp_max', 43200 );	// 12 hours
		$now       = current_time( 'timestamp' );
		$timestamp = wp_unslash( $_POST['_vfb-timestamp-' . $form_id] );
		$time      = $now - $timestamp;

		if ( $time < $min_time ) {
			return __( 'Security check: the form was submitted too fast and looks like a SPAM bot. Please wait a few seconds before sending. If you think this is an error, please email the site owner.' , 'vfb-pro' );
		}

		// if ( $time > $max_time ) {
		// 	return __( 'Security check: the form was submitted too slow and looks like a SPAM bot. Please wait a few seconds before sending. If you think this is an error, please email the site owner.' , 'vfb-pro' );
		// }

		return true;
	}
}
