<?php

if( $ctaLocation === 'after_content' ) {
	$ctaClass = 'text-center button-bottom';
	include( locate_template( 'templates/includes/cta.php' ) );
}