<?php

	$gridLayout = get_sub_field( 'layout' );
	$mediaType = get_sub_field( 'media_type' );
	$showFirst = get_sub_field( 'show_first_on_mobile' );

	if( $mediaType === 'image' ) {
		$image = get_sub_field( 'image' );
		$hasShadow = get_sub_field( 'add_shadow' );
		$hasContent = ( $image );
	} else {
		// $videoSource = get_sub_field( 'video_source' );
		$videoURL = get_sub_field( 'video_link' );
		$isAutoplay = get_sub_field( 'autoplay' );
		$previewImage = get_sub_field( 'preview_image' );
		$hasContent = ( $videoURL || $previewImage );
	}

	$cardImageSize = $gridLayout === 'outside' ? 'banner-desktop' : '1160x650';

	if( $title || $text || $cta || $hasContent ) : ?>

		<section class="section section-text <?php echo $sectionClass; ?>">
			<div class="container">

				<?php

				include( locate_template( 'templates/section-parts/section-title.php' ) );

				// Media content - shown only on mobile if 'Show first on mobile' is set to true
				if( $showFirst ) {
					$contentClass = $gridLayout === 'outside' ? 'is-full' : null;
					$contentClass .= ' visible-xs';
					include( locate_template( 'templates/includes/media.php' ) );
				}

				// Button before content
				$sectionParts = array( 'text', 'button-before' );
				foreach( $sectionParts as $part ) {
					include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
				}

				// Main content if 'Grid layout' is set to 'Inside the grid'
				if( $gridLayout === 'inside' ) {
					$contentClass = '';
					$contentClass .= ( $showFirst ) ? ' hidden-xs' : null;

					include( locate_template( 'templates/includes/media.php' ) );
					include( locate_template( 'templates/section-parts/section-button-after.php' ) );
				}
				?>

			</div>

			<?php
			// Main content if 'Grid layout' is set to 'Outside the grid'
			if( $gridLayout === 'outside' ) {
				$contentClass = 'is-full';
				$contentClass .= ( $showFirst ) ? ' hidden-xs' : null;

				include( locate_template( 'templates/includes/media.php' ) );
				include( locate_template( 'templates/section-parts/section-button-after.php' ) );
			}
			?>
		</section>

	<?php endif;
