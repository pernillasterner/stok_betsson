/* COOKIES */
var betssonCookie = {};

betssonCookie.set = function ( cname, cvalue, exdays ) {
    var d = new Date();
    d.setTime( d.getTime() + (exdays * 24 * 60 * 60 * 1000) );
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
};

betssonCookie.get = function ( cname ) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent( document.cookie );
    var ca = decodedCookie.split( ';' );

    for ( var i = 0; i < ca.length; i++ ) {
        var c = ca[ i ];
        while ( c.charAt( 0 ) === ' ' ) {
            c = c.substring( 1 );
        }

        if ( c.indexOf( name ) === 0 ) {
            return c.substring( name.length, c.length );
        }
    }
    return "";
};

betssonCookie.addListener = function ( element, eventNames, listener ) {
    var events = eventNames.split( ' ' );
    for ( var i = 0; i < events.length; i++ ) {
        element.addEventListener( events[ i ], listener );
    }
};

betssonCookie.init = function ( wrapperClass, btnClass ) {
    var wrappers = document.querySelectorAll( wrapperClass );

    for ( var i = 0; i < wrappers.length; i++ ) {
        var cookieName = wrappers[ i ].getAttribute( 'data-cookie' );

        // if cookies allowed but not yet saved
        if ( document.cookie.indexOf( cookieName ) < 0 && navigator.cookieEnabled ) {
            //show cookie
            wrappers[ i ].style.display = "block";

            //init approval buttons
            var buttons = wrappers[ i ].querySelectorAll( btnClass );
            var thisWrapper = wrappers[ i ];

            for ( var j = 0; j < buttons.length; j++ ) {

                betssonCookie.addListener( buttons[ j ], 'click keydown', function ( e ) {
                    var etype = e.type;

                    if ( etype === 'keydown' && (e.keyCode !== 13 && e.keyCode !== 32) ) {
                        return true;
                    }

                    e.preventDefault();
                    betssonCookie.set( cookieName, true, 365 );
                    thisWrapper.parentNode.removeChild( thisWrapper );
                } );
            }
        } else {
            wrappers[ i ].parentNode.removeChild( wrappers[ i ] );
        }
    }
};

betssonCookie.init( '.js-cookie', '.js-cookie-approve' );