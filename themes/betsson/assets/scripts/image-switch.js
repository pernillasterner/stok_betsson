var helperViewport = {};

helperViewport.documentWidth = function(){
	var e = window, a = 'inner';

	if( !( 'innerWidth' in window ) ) {
		a = 'client';
		e = document.documentElement || document.body;
	}

	return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
};


var ImageSwitch = {};

ImageSwitch.updateImage = function( targetEl ) {
	for( var i = 0; i < targetEl.length; i++ ) {
		var background = null;
		var currentEl = targetEl[i];

		if( helperViewport.documentWidth().width < 767 ) {
			background = currentEl.getAttribute( 'data-mobileBackground' );
		} else {
			background = currentEl.getAttribute( 'data-desktopBackground' );
		}

		if( currentEl.getAttribute( 'data-change-type' ) === 'src' ) {
			currentEl.src = background;
		} else {
			currentEl.style.backgroundImage = 'url('+ background +')';
		}
	}
};

ImageSwitch.init = function( wrapperEl ) {
	var target = document.querySelectorAll( wrapperEl );

	if( target.length === 0 ) {
		return;
	}

	ImageSwitch.updateImage( target );

	window.addEventListener( 'resize', function() { 
		ImageSwitch.updateImage( target );
	});	
};

ImageSwitch.init( '.js-image-switch' );