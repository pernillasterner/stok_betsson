<?php

if( $ctaLocation === 'before_content' ) {
    $ctaClass = 'text-center button-top';
    include( locate_template( 'templates/includes/cta.php' ) );
}
