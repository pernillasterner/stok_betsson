<?php
global $post;

$source = $post->ID;

if( is_singular( 'job' ) ) {
	$source = 'jobs';
}

$sectionCount = 0;
$excludedSection = array(
	'featured_jobs',
	'locations_grid',
	'black_frame',
	'fifty_fifty',
	'blockquote'
);

$hasBanner = true;

if( have_rows( 'sections', $source ) ) {
	while ( have_rows( 'sections', $source ) ) {
		the_row();

		$sectionCount++;
		$sectionClass = null;
		$backgroundColor = null;
		$cta = null;
		$ctaColor = null;
		$ctaStyle = null;
		$ctaLocation = null;
		$hasArrow = null;
		$sections = get_field( 'sections', $source );
		$nextSection = isset( $sections[$sectionCount] ) ? $sections[$sectionCount] : null;
		$isLastSection = $sectionCount === count( $sections );

		if( !in_array( get_row_layout(), $excludedSection ) ) {
			$title = get_sub_field( 'title' );
			$disableTitleShadow = get_sub_field( 'disable_title_shadow' );			
			$titleShadow = get_sub_field( 'title_text_shadow' );
			$text = get_sub_field( 'text' );
			$cta = get_sub_field( 'cta' );
			$ctaStyle = get_sub_field( 'cta_style' );
			$ctaColor = get_sub_field( 'cta_color' );
			$ctaLocation = get_sub_field( 'cta_location' );
			$backgroundColor = get_sub_field( 'background_color' );
			$hasArrow = get_sub_field( 'show_arrow?' );
			$isAllCaps = get_sub_field( 'all_caps?' );

			// Arrow
			if( $hasArrow && !$isLastSection ) {
				if( get_row_layout() === 'promo_area' && $ctaLocation === 'after_content' && !$cta ) {
					$sectionClass .= '';
				} else {
					$sectionClass .= 'has-arrow';
				}
			}

			// Background-color
			if( $backgroundColor === 'blue' ) {
				$sectionClass .= ' is-primary';
			} elseif( $backgroundColor === 'orange' ) {
				$sectionClass .= ' is-secondary';
			}

			if($cta && $ctaLocation === 'before_content' ) {
				$sectionClass .= ' has-button-before';
			}elseif($cta && $ctaLocation === 'after_content' ){
				$sectionClass .= ' has-button-after';
			}

			if( in_array( get_row_layout(), [ 'cards', 'latest_news' ] ) && 
				( isset( $nextSection ) && get_row_layout() === $nextSection['acf_fc_layout'] ) &&
				!( $nextSection['title'] || $nextSection['text'] || ( $nextSection['cta'] && $nextSection['cta_location'] === 'before_content' ) )
			) {
				$sectionClass .= ' is-adjacent';
			}

		}

		if( get_row_layout() === 'instagram' &&
			( $backgroundColor === 'white' || empty( $backgroundColor ) ) &&
			( $title || $text || ( $ctaLocation === 'before_content' && $cta ) )
		) {
			$sectionClass .= ' is-first';

		} elseif( !in_array( get_row_layout(), array( 'locations_grid', 'instagram', 'promo_area' ) ) &&
			$sectionCount === 1 &&
			!$hasBanner &&
			!in_array( $backgroundColor, array( 'blue', 'orange' ) ) &&
			!is_single()

		) {
			$sectionClass .= ' is-first';
		}

		include( locate_template( 'templates/sections/' . get_row_layout() . '.php' ) );
	}
}
