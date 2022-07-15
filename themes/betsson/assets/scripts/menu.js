var navmenu = {};

( function( $ ) {

	navmenu.menuMobile = function () {
        var primaryMenu = $('.nav-primary .nav').html();
        var secondaryMenu = $('.nav-secondary .nav').html();
        $('.js-menu-toggle ul').html( [ primaryMenu, secondaryMenu ] );
    };

    navmenu.toggleMenu =  function () {
        $( ".js-toggle-menu" ).click(function() {
            $( ".nav-mobile-toggle" ).toggleClass( "menu-open" );
            $( ".main-body" ).toggleClass( "menu-open" );
        });
    };

    navmenu.menuScroll = function (locationX) {
        var locationWin =  locationX;

        if( $('.js-hero').length ) {

            var bannerHeight = $('.banner').height();   

            if( locationWin >= bannerHeight ){ 
                $('.banner').removeClass('has-hero');
            }
            else {
                $('.banner').addClass('has-hero');
            }
        }

        if( $('.js-submenu-banner').length ) {

            var targetOffset = $('.hero-banner').outerHeight() - $('.js-submenu-banner').children('.submenu-nav').outerHeight();
            var topCss = $('.banner').outerHeight() + $('#wpadminbar').outerHeight() - 1;
            var targetHeight = locationWin + $('.banner').outerHeight();

            if( targetHeight >= targetOffset ) {
               $('.js-submenu-banner').children('.submenu-nav').addClass('submenu-fix');
               $('.js-submenu-banner').children('.submenu-nav').css("top", topCss);
            }
            else {
                $('.js-submenu-banner').children('.submenu-nav').removeClass('submenu-fix');
                $('.js-submenu-banner').children('.submenu-nav').removeAttr("style");
            }
        }
    };


    navmenu.subMenuMobile = function() {
        $('.js-submenu-carousel').flickity({
            cellAlign: 'center',
            contain: true,
            prevNextButtons: false,
            pageDots: false
        });  
    };


    navmenu.toggleMenu();
    navmenu.menuMobile();
    navmenu.menuScroll();
    
    $(window).load(function(){
        navmenu.subMenuMobile();
    });

    $(window).scroll(function () {
        navmenu.menuScroll($(window).scrollTop());
    });

    $( window ).resize(function() {
        navmenu.menuScroll($(window).scrollTop());
    });

} )( jQuery );
