( function ( $ ) {
	var scrollBtn = $( '.js-scrollTo-next-section' );
	var excludedClass = [ 'is-primary', 'is-secondary', 'is-first', 'location-grids' ];

	var scrollToSection = function( targetSection, adjHeight ) {
		Scroll.To( targetSection, adjHeight );
	};

	if( scrollBtn.length > 0 ) {
		scrollBtn.on( 'click', function(e) {
			e.preventDefault();

			var targetElClass = null;
			var mainContainer = $( this ).parents().eq(2);
			var adjHeight = 100;

			if( mainContainer.children().length > 1 ) {
				var nextElClassAttr = $.trim( mainContainer.children().eq(1).attr('class') );
				var nextElClasses = nextElClassAttr.split(/\s+/);
				var intersect = $( nextElClasses ).not( $( nextElClasses ).not( excludedClass ) );

				if( intersect.length > 0 ) {
					adjHeight = 0;
				}

				targetElClass = '.'+ nextElClasses.join('.');
			} else {
				targetElClass = 'footer';
			}

			if( targetElClass ) {
				scrollToSection( targetElClass, adjHeight );
			}
		} );
	}
} )( jQuery );