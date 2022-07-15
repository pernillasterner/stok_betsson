var Scroll = {};

( function ( $ ) {
	Scroll.To = function ( target, adjHeight ) {
		target = $( target );

		if( typeof adjHeight === "undefined" || adjHeight === null ) { 
			adjHeight = 0; 
		}		

		if ( target.length === 0 ) {
			return;
		}
		var scrollTop = target.offset().top;

		// Get nav height and subtract to top offset of target element
		var navBar = $( 'header' );
		if ( navBar.length > 0 ) {
			scrollTop -= parseInt( navBar.css( 'height' ) );
		}

		// Get subnav height and subtract it also
		var subnav = $( '.submenu-nav' );
		if ( subnav.length > 0 ) {
			scrollTop -= parseInt( subnav.css( 'height' ) );
		}		

		// Get wpadminbar height and subtract to top offset of target element
		var wpAdminBar = $( '#wpadminbar' );
		if ( wpAdminBar.length > 0 ) {
			scrollTop -= parseInt( wpAdminBar.css( 'height' ) );
		}

		if( adjHeight > 0 ) {
			scrollTop -= parseInt( adjHeight );
		}

		// Call stop() to avoid shaking behavior in Chrome
		$( 'html, body' ).stop( true ).animate( {
			scrollTop: scrollTop
		}, 1000 );
	};
} )( jQuery );