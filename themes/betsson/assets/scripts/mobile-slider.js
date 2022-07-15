/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var mobiSlider = {};

mobiSlider.init = function () {
	jQuery('.mobile-carousel').flickity({
		wrapAround: true,
		initialIndex: 2,
		adaptiveHeight: true,
		prevNextButtons: false,
		pageDots: false,
		lazyLoad: true
	});

	jQuery('.slider-carousel').flickity({
		wrapAround: true,
		initialIndex: 1,
		adaptiveHeight: true,
		prevNextButtons: false,
		pageDots: true,
		lazyLoad: true
	});

	jQuery('.cards-carousel').flickity({
		//wrapAround: true,
		// initialIndex: 2,
		adaptiveHeight: true,
		prevNextButtons: false,
		pageDots: false,
		lazyLoad: true,
		contain: true
	});
};

( function( $ ) {
	mobiSlider.init();
} )( jQuery );
