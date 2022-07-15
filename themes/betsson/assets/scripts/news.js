( function ( $ ) {
	$( 'section.latest-news' ).each( function () {
		var newsSection = $( this );

		var newsData = newsSection.find( '#news-data' ),
			showMoreButton = newsSection.find( '#news-show-more' ),
			loader = newsSection.find( '.loader' ),
			newsContainer = newsSection.find( '#news-container' );

		if ( newsContainer.length === 0 ) {
			return;
		}

		/**
		 * Add big cards
		 */
		var updateColumns = function () {
			var bigIndex1 = 3,
				bigIndex2 = 5,
				bigIndex3 = 7,
				nextIndex = 9;

			newsContainer.find( '.col' ).each( function ( index, item ) {
				var isBig = false;
				index++;

				if ( index === bigIndex1 ) {
					isBig = true;
					bigIndex1 += nextIndex;
				} else if ( index === bigIndex2 ) {
					isBig = true;
					bigIndex2 += nextIndex;
				} else if ( index === bigIndex3 ) {
					isBig = true;
					bigIndex3 += nextIndex;
				}

				var img = $( item ).find( 'figure img' );

				if ( isBig ) {
					$( item ).addClass( 'is-landscape' );

					if( img.data( 'src-landscape' ) ) {
						img.attr( 'src', img.data( 'src-landscape' ) );
					}
				} else {
					$( item ).removeClass( 'is-landscape' );
					
					if( img.data( 'src-portrait' ) ) {
						img.attr( 'src', img.data( 'src-portrait' ) );	
					}
					
				}
			} );
		};

		updateColumns();

		if ( newsData.length === 0 || showMoreButton.length === 0 ) {
			return;
		}

		// Get news data
		newsData = JSON.parse( newsData.text() );

		// Show the rest of the news items
		showMoreButton.click( function ( e ) {
			e.preventDefault();

			$.ajax( {
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'get_more_news',
					tax_query: newsData.tax_query,
					excluded_ids: newsData.excluded_ids
				},
				method: 'get',
				dataType: 'json',
				beforeSend: function () {
					showMoreButton.hide();
					loader.show();
				},
				success: function ( result ) {
					newsContainer.append( result.data.html );
					updateColumns();
					loader.hide();
					
					globalFnc.imageIE();
				},
				error: function ( result ) {
					console.log( result );
				}
			} );
		} );
	} );
} )( jQuery );