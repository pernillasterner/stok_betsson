var e = {};
var etype = e.type;

function keydownClick() {
	if ( etype === 'keydown' && ( e.keyCode !== 13 || e.keyCode !== 32 ) ) {
		return true;
	}
}


var globalFnc = {};

( function( $ ) {

	globalFnc.imageIE =  function() {
		if ( window.ActiveXObject || "ActiveXObject" in window ) {
			$( '.center-image' ).each( function () {
				var $container = $( this ),
					imgUrl = $container.find( 'img' ).prop( 'src' );
				if ( imgUrl ) {
					$container
						.css( 'backgroundImage', 'url(' + imgUrl + ')' )
						.addClass( 'compat-cover' );
				}
			} );
		}
	};

	globalFnc.sectionTitle = function(){
		if ($(window).width() < 768) {
			$('.section-title').each(function() {
				var $height = $( this ).find('.h2').innerHeight();
				if($height > 50){
					$( this ).removeClass('is-one-liner');
					$( this ).addClass('is-two-liner');

				}else{
					$( this ).removeClass('is-two-liner');
					$( this ).addClass('is-one-liner');
				}
			});
		}
	}

	globalFnc.checkTitleHeight = function(){

		globalFnc.sectionTitle();

		$(window).resize(function() {
			clearTimeout(window.resizedFinished);
			window.resizedFinished = setTimeout(function(){
				globalFnc.sectionTitle();
			}, 250);
		});

	}

	globalFnc.imageIE();
	globalFnc.checkTitleHeight();



} )( jQuery );
