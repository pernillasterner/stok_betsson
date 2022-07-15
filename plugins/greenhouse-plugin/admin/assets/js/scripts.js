var SGPAdmin = {};

( function ( $ ) {

	SGPAdmin.ajaxURL = '/wp-admin/admin-ajax.php';

	SGPAdmin.init = function () {
		var message = $( '#msg-importing' ),
			loader = $( '#loader' ),
			form = $( 'form#SGPForm' ),
			log = $( '#log' );

		$( '#btn-import' ).click( function () {
			form.hide();
			message.html( 'Importing jobs. Do not close this page.' ).show();
			loader.css( 'display', 'inline-block' );

			$.ajax( {
				url: SGPAdmin.ajaxURL,
				data: {
					action: 'import_jobs'
				},
				dataType: 'json',
				success: function ( result ) {
					if ( result.success ) {
						message.html( result.data.message );
						log.html( result.data.log );
						log.parents( 'tr' ).show();
					} else {
						message.html( result.data.message + ' ' + result.data.details );
					}

					form.show();
					loader.hide();
				}, error: function ( result ) {
					console.debug( result );
				}
			} );
		} );
	};

	$( SGPAdmin.init );
} )( jQuery );