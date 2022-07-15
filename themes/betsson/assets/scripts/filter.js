/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var filter = {};

( function ( $ ) {
	filter.init = function () {
		$( '.job-search' ).each( function () {
			var searchContainer = $( this );

			searchContainer.find( '.department-multiple' ).select2( {
				placeholder: 'Choose Department'
			} );

			searchContainer.find( '.location-multiple' ).select2( {
				placeholder: 'Choose Location'
			} );

			searchContainer.css( 'visibility', 'visible' );
		} );
	};

	$( filter.init );
} )( jQuery );