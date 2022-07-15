var ListingPagination = {};

(function ( $ ) {

    var scope = $( '.js-listing' );
    var currentPage = 1;
    var ajaxRequestEnable = true;
    var container = null;
    var hasMore = false;
    var btnDelay = 0;
    var showAllButton = $( '.js-show-all' );
    var listingLoader = '.loader';

    ListingPagination.load = function ( data, buttonEl ) {

        // To avoid double send of ajax request
        if( !ajaxRequestEnable ) {
            return;
        }

        $.ajax( {
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            data: {
                action: 'get_posts',
                postType: data.postType,
                term: data.term,
                taxonomy: data.taxonomy,
                excluded: data.excluded
            },
            beforeSend: function () {
                ajaxRequestEnable = false;
                buttonEl.hide();
                container.parent().find( listingLoader ).show();
            },
            success: function ( result ) {
                ListingPagination.outputResult( result.html );
				hasMore = result.hasMore;
				
				globalFnc.imageIE();
            },
            complete: function() {
                container.parent().find( listingLoader ).hide();
                ajaxRequestEnable = true;           

                if( !hasMore ) {
                    buttonEl.hide();                  
                }
            },
            error: function( xhr, textStatus, error ) {
                console.log( error );
            }
        } );
    };

    ListingPagination.outputResult = function( items ) {
        var item = null;

        $.each( items, function( i, val ) {
            btnDelay = 100*i;

            setTimeout( function(){
                item = $( val ).hide();
                container.append( item );
                item.fadeIn( 'slow' );

                // re-initialized accordion
                $( '.accordion' ).accordion('destroy');
                FAQ.init();               
            }, btnDelay );
        } );
    };

    showAllButton.on('click', function ( e ) {
        e.preventDefault();

        var data = $( this ).data( 'listing' );
        container = $( data.listingContainer );

        if ( data.hasMore ) {
            ListingPagination.load( data, $( this ) );
        }
    } );

    ListingPagination.init = function () {
        if( showAllButton.length === 0 ) { return; }

        $.each( showAllButton, function( i, val ) {
            if( !$( this ).data( 'listing' ).hasMore ) {
                $( this ).hide();
            }
        });
    };

    if( scope.length > 0 ) {
        ListingPagination.init();
    }

})( jQuery );