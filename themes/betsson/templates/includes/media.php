<?php

use Lib\Classes\VideoHelper;
use Lib\Classes\GeneralHelper;

$general = new GeneralHelper();

$newMediaType = !empty( $mediaType ) ? $mediaType : null;
$newImage = !empty( $image ) ? $image : null;
$newHasShadow = !empty( $hasShadow ) ? $hasShadow : null;
$newVideoURL = !empty( $videoURL ) ? $videoURL : null;
$newPreviewImage = !empty( $previewImage ) ? $previewImage : null;
$newVideoSource = !empty( $videoSource ) ? $videoSource : null;

if( $newMediaType === 'image' && $newImage ) :
	$contentClass .= $newHasShadow ? ' has-shadow' : null;
	$imageSrc = wp_get_attachment_image_url( $newImage, $cardImageSize );
	$imageAlt = $general->get_image_alt( $newImage );
	?>
	<div class="image-holder center-image <?php echo $contentClass; ?>">
		<?php if( $cta['url'] ) : ?><a href="<?php echo $cta['url']; ?>"><?php endif; ?>
			<img src="<?php echo $imageSrc; ?>" alt="<?php echo $imageAlt; ?>" />
		<?php if( $cta['url'] ) : ?></a><?php endif; ?>
	</div>

<?php
elseif( $newMediaType === 'video' && $newVideoURL ) :
	$previewImageSrc = $newPreviewImage ? wp_get_attachment_image_url( $newPreviewImage, $cardImageSize ) : null;
	$previewImageAlt = $general->get_image_alt( $newPreviewImage );

	$tag = VideoHelper::getVideoTag( $newVideoURL, false, false, $isAutoplay );
	?>
	<div class="video-holder center-image <?php echo $contentClass; ?> js-background-image js-play-video">
		<?php if( !$isAutoplay ): ?>
			<img class="video-image" src="<?php echo $previewImageSrc; ?>" alt="<?php echo $previewImageAlt; ?>" />
			<span class="icon icon-play"></span>
		<?php endif; ?>

		<div class="video-background"><?= $tag ?></div>
	</div>

<?php

// Reset values
$newMediaType = null;
$newImage = null;
$newHasShadow = null;
$newVideoURL = null;
$newPreviewImage = null;
$newVideoSource = null;

endif;
