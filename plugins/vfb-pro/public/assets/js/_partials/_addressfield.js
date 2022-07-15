jQuery(document).ready(function($) {
	if ( $.fn.addressfield ) {
		var configuredFields = [
			'vfb-addresspart-address-1',
			'vfb-addresspart-city',
			'vfb-addresspart-province',
			'vfb-addresspart-zip'
		];

		var vfbCountryConfig;
		if ( window.vfbp_address_config ) {
			vfbCountryConfig = vfbp_address_config.vfbp_addresses;
		}

		// On page load, localize the address block based on the country
		if ( $( '.vfb-address-block' ).length > 0 ) {
			$( '.vfb-address-block' ).each( function() {
				var addrID 		= $( this ).attr( 'id' ),
					addrCountry = $( this ).find( '.vfb-addresspart-country' ).val();

				$( '#' + addrID ).addressfield( vfbCountryConfig[addrCountry], configuredFields );
			});
		}

		// On country change
		$( '.vfb-addresspart-country' ).change( function() {
			var addressBlock = $( this ).closest( '.vfb-address-block' );

			// Trigger the addressfield plugin with the country's data.
			addressBlock.addressfield( vfbCountryConfig[this.value], configuredFields );
		});
	}
});
