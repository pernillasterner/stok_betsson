/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var FeaturedJobs = {};

( function( $ ) {

	FeaturedJobs.updateApplyBtn = function( typedStrings, pos, applyBtn ) {
		var currentUrl = $( typedStrings + ' p' ).eq( pos ).data( 'url' );
		applyBtn.attr( 'href', currentUrl );
	};

	FeaturedJobs.typedInit = function( typedContainer, typedEl, typedStrings ) {
		var applyBtn = $( typedStrings ).parent().parent().find( '.js-apply-btn' );
		var isLinkUpdated = false;

		var typed = new Typed( typedEl, {
			stringsElement: typedStrings,
			typeSpeed: 100,
			backSpeed: 60,
			loop: !0,
			preStringTyped: function(pos, self) { 
				isLinkUpdated = true;
				FeaturedJobs.updateApplyBtn( typedStrings, pos, applyBtn );
			},
			onStringTyped: function(pos, self) { 
				if( !isLinkUpdated ) {
					FeaturedJobs.updateApplyBtn( typedStrings, pos, applyBtn );
				}

				isLinkUpdated = false;
			},			
		});		
	};

	FeaturedJobs.init = function ( typedContainer ) {
		if( !typedContainer ) {
			return;
		}

		$.each( typedContainer, function() {
			var _this = $( this );
			var typedStrings = '#' + _this.find( '.typed-strings' ).attr( 'id' );
			var typedEl = '#' + _this.find( '.typed' ).attr( 'id' );

			FeaturedJobs.typedInit( typedContainer, typedEl, typedStrings );
		} );
	};

	$( document ).ready(function() {
		FeaturedJobs.init( $( '.typed-container' ) );
	});

} )( jQuery );
