var Video = {};

( function ( $ ) {
	var trigger = $( '.js-play-video' );

	Video.play = function( _this, checkIfUrl ) {
		var iframe = _this.find( 'iframe' );
		var url = iframe.data( 'url' );

		// Remove the play button
		_this.find( '.icon-play' ).remove();

		if( checkIfUrl && !url ) {
			return;
		}

		// Show the iframe
		iframe.prop( 'src', url ).show();
		_this.find( '.video-image' ).fadeOut();
	};

	Video.init = function () {

		if( trigger.find( 'iframe' ).data( 'autoplay' ) && !Mobile.isMobile() ) {
			Video.play( trigger, false );
		}

		trigger.on( 'click keydown', function () {
			var _this = $( this );

			keydownClick();

			var checkIfUrl = ( Mobile.isMobile() ) ? true : false;
			Video.play( _this, checkIfUrl );

		} );

		// On mobile, always show preview image and play button of hero video
		// if ( Mobile.isMobile() ) {
		// 	heroVideo.find( '.video-iframe' ).hide();
		// }
	};

	if ( trigger.length ) {
		$( Video.init() );
	}

} )( jQuery );
