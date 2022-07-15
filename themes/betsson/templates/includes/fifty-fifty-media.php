<?php

use Lib\Classes\VideoHelper;

if( $mediaType === 'image' ):
	$image = get_sub_field( 'image' );

	if( !$image ) {
		return;
	}

	$image = wp_get_attachment_image( $image, '560x480' );
?>
	<?php if( $cta['url'] ) : ?>
		<a href="<?php echo $cta['url']; ?>">
		<div class="image-holder has-shadow center-image"><?= $image ?></div>
		</a>
    <?php else: ?>
		<div class="image-holder has-shadow center-image"><?= $image ?></div>
    <?php endif; ?>
<?php
else:
	$videoURL = get_sub_field( 'video_link' );
	$image = get_sub_field( 'preview_image' );

	if( !$videoURL || !$image ) {
		return;
	}

	$tag = VideoHelper::getVideoTag( $videoURL, false, false, false );
	$image = wp_get_attachment_image( $image, '560x480', false, array( 'class' => 'video-image' ) );
?>
	<div class="video-holder center-image js-background-image js-play-video">
		<?= $image ?>
		<span class="icon icon-play"></span>
		<div class="video-background"><?= $tag ?></div>
	</div>
<?php endif; ?>
