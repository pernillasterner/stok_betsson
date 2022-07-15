/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var FAQ = {};

( function( $ ) {

	FAQ.init = function () {
    $(".accordion" ).accordion({
		heightStyle: "content",
		collapsible: true,
		active: false,
		header: '> div.faq-item >h6'
	});
	};

	$( document ).ready(function() {
    FAQ.init();
	});

} )( jQuery );
