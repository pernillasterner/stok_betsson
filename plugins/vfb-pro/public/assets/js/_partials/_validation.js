jQuery(document).ready(function($) {
	var $form            = $( '.vfbp-form' ),
		$submit          = $form.find( ':submit' ),
		$recaptcha_error = $( '#vfb-recaptcha-error' );

	// Make sure potential disabled submit button is not disabled on load
	$( window ).on( 'pageshow', function() {
		$( '.vfbp-form :submit' ).removeAttr( 'disabled' );
	});

	if ( $.fn.parsley ) {
		$form.parsley({
			namespace: 'data-vfb-',
			errorClass: 'vfb-has-error',
			successClass: 'vfb-has-success',
			classHandler: function(ParsleyField) {
		        return ParsleyField.$element.parents( 'div[id*="vfbField"]' );
		    },
		    errorsContainer: function(ParsleyField) {
			    return ParsleyField.$element.closest( 'div[id*="vfbField"]' );
		    },
		    errorsWrapper: '<span class="vfb-help-block">',
			errorTemplate: '<div></div>',
			excluded: 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden'
		});

		// Automatically hide the reCAPTCHA error message if enabled
		if ( $recaptcha_error.length > 0 ) {
			$recaptcha_error.hide();
		}

		// Setup reCAPTCHA v3 hidden token
		if ( $form.find( '.g-recaptcha-v3' ).length > 0 ) {
			var site_key = $form.find( '.g-recaptcha-v3' ).data( 'sitekey' );

			grecaptcha.ready( function() {
				grecaptcha.execute(
					site_key,
					{
						action: 'vfbp_form'
					}
				).then( function( token ) {
					$form.prepend( '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" value="' + token + '" />' );
				});
			} );
		}

		$form.submit( function() {
			// Check if reCAPTCHA v2 has been performed and display error message/prevent form submission
			if ( $form.find( '.g-recaptcha' ).length > 0 ) {
				if ( $form.find( '#g-recaptcha-response' ).val() === '' ) {
					if ( $recaptcha_error.length > 0 ) {
						$recaptcha_error.show();
					}

					return false;
				}
			}

			// Check if reCAPTCHA v3 has been performed and display error message/prevent form submission
			if ( $form.find( '.g-recaptcha-v3' ).length > 0 ) {
				if ( $form.find( '#g-recaptcha-response' ).val() === '' ) {
					if ( $recaptcha_error.length > 0 ) {
						$recaptcha_error.show();
					}

					return false;
				}
			}

			// Actions to perform when the form is valid
			if ( $form.parsley( 'isValid' ) ) {
				// Disable the submit button to prevent double submissions
				$submit.attr( 'disabled', 'disabled' );

				// Make sure reCAPTCHA error message is hidden
				if ( $recaptcha_error.length > 0 ) {
					$recaptcha_error.hide();
				}

				// Clear cookies
				if ( $.fn.phoenix ) {
					$( '.vfbp-form :input:not(".vfb-file-input")' ).phoenix( 'remove' );
				}
			}
		});
	}
});
