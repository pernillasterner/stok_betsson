<?php
/**
 * Template Name: Jobs Template
 */

get_template_part( 'templates/includes/departments-icon' );

$sectionClass = '';
$sectionCount = 0;
$ctaColor = 'orange';
include( locate_template( 'templates/sections/featured_jobs.php' ) );

get_template_part( 'templates/jobs' );

get_template_part( 'templates/sections' );

get_template_part( 'templates/includes/modal-subscribe' );