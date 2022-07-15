jQuery(document).ready(function($) {
	if ( $.fn.jSignature ) {
		if ( $( '.vfb-signature' ).length > 0 ) {
			$( '.vfb-signature' ).each( function() {
				var sig         = $( this ).jSignature(),
					sigInput    = $( this ).prev( '.vfb-signature-input' ),
					sigButtons  = sig.next( '.vfb-signature-buttons' ),
					sigRequired = sigButtons.next( '.vfb-signature-error' ),
					sigData;

				// Automatically hide the Signature error message if enabled
				if ( sigRequired.length > 0 ) {
					sigRequired.hide();
				}

				// If signature has been used
				sig.on( 'change', function(e) {
					var data     = $( e.target ).jSignature( 'getData', 'native' );

					// Display reset button
					sigButtons.show();

					// Check if there are more than 2 strokes in the signature
					// Or, if there is just one stroke that it has more than 20 points
					if ( data.length > 2 || ( data.length === 1 && data[0].x.length > 20 ) ) {
						sigData = sig.jSignature( 'getData' );
					}
				});

				// Action for Reset button
				sig.next( '.vfb-signature-buttons' ).click( function(e){
					e.preventDefault();
					sig.jSignature( 'reset' );
					sigData = '';
				});

				// Load base64 data in the hidden input value on submit
				$( '.vfbp-form' ).submit( function() {
					// Check if Signature has been used and display error message/prevent form submission
					if ( typeof sigData === 'undefined' || sigData == '' ) {
						if ( sigRequired.length > 0 ) {
							sigRequired.show();

							return false;
						}
					}

					if ( $( '.vfbp-form' ).parsley( 'isValid' ) ) {
						sig.prev( sigInput ).val( sigData );
					}
				});
			});
		}
	}
});
