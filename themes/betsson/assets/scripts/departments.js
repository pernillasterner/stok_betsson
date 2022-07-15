( function ( $ ) {
	$( 'a.js-department-filter' ).each( function () {
		var element = $( this );

		// Get departent id and prepare data
		var departmentId = element.data( 'id' );
		var data = { departments: [departmentId] };

		// Generate hash
		hash = CryptoJS.AES.encrypt( JSON.stringify( data ), 'betsson' ).toString();

		// Update link
		var baseURL = element.data( 'baseUrl' );
		element.prop( 'href', baseURL + '#' + hash );
	} );
} )( jQuery );