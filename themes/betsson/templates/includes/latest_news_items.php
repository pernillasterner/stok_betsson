<?php
use Lib\Classes\GeneralHelper;

$general = new GeneralHelper(); 

if( !isset( $excludedIds ) ) {
	$excludedIds = array();
}

$columnsPerRow = isset( $columnsPerRow ) ? $columnsPerRow : 3;

// Get the article text from news page if not set
if( !isset( $readArticle ) || !$readArticle ) {
	$newsPageId = get_field( 'news_page', 'default-pages' );
	$newsPageSections = get_field( 'sections', $newsPageId );
	if( $newsPageSections ) {
		foreach( $newsPageSections as $section ) {
			if( $section['acf_fc_layout'] === 'latest_news' ) {
				$readArticle = $section['read_article_link_text'];
			}
		}

	}
}

foreach( $posts as $key => $item ) { 
	$cardImageSize = '300x400';
	$cardImageSizeBig = '600x400';
	$postThumbnail = get_the_post_thumbnail_url( $item->ID, $cardImageSize );
	$postThumbnailBig = get_the_post_thumbnail_url( $item->ID, $cardImageSizeBig );
	$postCategory = get_the_category( $item->ID );
	$postDate = get_the_date( 'y/m/d', $item->ID );
	$cardDetails = array();

	if( $postCategory ) {
		$cardDetails = wp_list_pluck( $postCategory, 'name' );
	}

	if( get_field( 'show_published_date', $item->ID ) ) {
		$cardDetails[] = $postDate;
	}
	
	$cardImage = $postThumbnail ? $postThumbnail : get_template_directory_uri() . '/dist/images/placeholders/'. $cardImageSizeBig .'.jpg';
	$cardImageBig = $postThumbnailBig ? $postThumbnailBig : get_template_directory_uri() . '/dist/images/placeholders/'. $cardImageSizeBig .'.jpg';
    $cardImageAlt = $general->get_image_alt( get_post_thumbnail_id( $item->ID ) );
	$cardTitle = get_the_title( $item );
	$cardText = wp_trim_words( $item->post_content, 60, '' );
	$cardLinkText = $readArticle;
	$cardLink = get_the_permalink( $item ); 
	$isExternalLink = false;

	if( ( $key + 1 ) % $columnsPerRow === 1 ) {
		echo '<div class="row">';
	}

	include( locate_template( 'templates/includes/card.php' ) );

	if( ( $key + 1 ) % $columnsPerRow === 0 || ( $key + 1 ) === count( $posts ) ) {
		echo '</div>';
	}

	$excludedIds[] = $item->ID;
}
?>