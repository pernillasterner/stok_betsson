<?php

use Lib\Classes\VideoHelper;
use Lib\Classes\GeneralHelper;

// Register shortcode
add_shortcode( 'video', function( $attr, $content = '' ) {

	$attr = wp_parse_args( $attr, array(
		'preview_image' => 0,
		'video_url' => '',
		'full_width' => false
	) );

	if( !$attr['video_url'] || !$attr['preview_image'] ) {
		return;
	}

	$tag = VideoHelper::getVideoTag( $attr['video_url'], false, false, false );
	$image = '';

	if( $attr['preview_image'] ) {
		$image = wp_get_attachment_image( $attr['preview_image'], '1160x650' );
	}	

	$general = new GeneralHelper();
	$categories = $general->get_post_categories();
	$contentGridClass = 'wide';

	// Get category settings for post grid layout
	if( $categories ) {
		$category = $categories[0];
		$contentGrid = get_field( 'post_content_grid', $category->taxonomy . '_' . $category->term_id );
		if( $contentGrid === 'narrow' ) {
			$contentGridClass = 'is-medium';
		}		
	}

	ob_start();

	?>
	<?php
		if($attr['full_width']) :
	?>
		</div><!--// end .content -->
		</div><!--// end .container -->

		<div class="content-wide">
			<div class="video-holder center-image js-background-image js-play-video is-full">
				<?= $image ?>
				<span class="icon icon-play"></span>
				<div class="video-background"><?= $tag ?></div>
			</div>
		</div>


		<div class="container">
		<div class="content <?= $contentGridClass ?>">
	<?php else: ?>
		</div>
		<div class="video-holder center-image js-background-image js-play-video">
			<?= $image ?>
			<span class="icon icon-play"></span>
			<div class="video-background"><?= $tag ?></div>
		</div>
		<div class="content <?= $contentGridClass ?>">
	<?php endif; ?>





	<?php

	return ob_get_clean();
} );

// Register Shortcake UI
shortcode_ui_register_for_shortcode(
	'video',
	array(

		// Display label. String. Required.
		'label' => 'Video',

		// Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
		// 'listItemImage' => 'dashicons-editor-quote',

		// Available shortcode attributes and default values. Required. Array.
		// Attribute model expects 'attr', 'type' and 'label'
		// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
		'attrs' => array(
			array(
				'label'       => 'Video URL',
				'attr'        => 'video_url',
				'description' => 'Youtube or Vimeo video URL.',
				'type'        => 'url'
			),
			array(
				'label'       => 'Full width?',
				'attr'        => 'full_width',
				'type'        => 'checkbox'
			),
			array(
				'label'       => 'Preview Image',
				'attr'        => 'preview_image',
				'type'        => 'attachment',
				/*
				 * These arguments are passed to the instantiation of the media library:
				 * 'libraryType' - Type of media to make available.
				 * 'addButton'   - Text for the button to open media library.
				 * 'frameTitle'  - Title for the modal UI once the library is open.
				 */
				'libraryType' => array( 'image' ),
				'addButton'   => 'Select Image',
				'frameTitle'  => 'Select Image',
			)
		)
	)
);
