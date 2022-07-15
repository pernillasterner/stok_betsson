( function ( $ ) {
	var showMobileFormButton = $( '.js-toggle-mobile-form' );

	// Add filesize validator
	$.validator.addMethod( 'filesize', function ( value, element, param ) {
		return this.optional( element ) || ( ( ( element.files[0].size / 1024 ) / 1024 ).toFixed( 4 ) <= param );
	}, 'File size must be less than {0} MB' );

	// Desktop scroll to form
	$( '.js-scroll-to-desktop-form' ).click( function ( e ) {
		e.preventDefault();

		Scroll.To( '#job-application-form-container' );
	} );

	// Mobile scroll to form
	showMobileFormButton.click( function ( e ) {
		e.preventDefault();

		var target = $( '#job-application-form-mobile-container > div' );
		target.toggle();
	} );

	// Show mobile form
	$( '.js-scroll-show-mobile-form' ).click( function ( e ) {
		e.preventDefault();

		if ( !showMobileFormButton.parent().hasClass( 'active' ) ) {
			showMobileFormButton.click();
		}

		Scroll.To( '#job-application-form-mobile-container' );
	} );

	// Application form submission
	$( '.js-job-application-form' ).each( function () {
		$( this ).validate( {
			rules: {
				resume: {
					required: true,
					extension: 'pdf|doc|docx',
					accept: '',
					filesize: 5
				},
				cover_letter: {
					extension: 'pdf|doc|docx',
					accept: '',
					filesize: 5
				}
			},
			messages: {
				resume: {
					extension: 'Invalid file type. Acceptable types are *.pdf, *.doc, *.docx.'
				},
				cover_letter: {
					extension: 'Invalid file type. Acceptable types are *.pdf, *.doc, *.docx.'
				}
			}
		} );
	} );

	// Application form submit tracking
	$( '.js-job-application-form' ).submit( function () {
		if ( !$( this ).valid() ) {
			return;
		}

		if ( typeof fbq === 'undefined' ) {
			return;
		}

		fbq( 'track', 'SubmitApplication' );
	} );
	
	$( '.job-template-default .selected-page-header h1' ).fitText( 1.6 );
} )( jQuery );