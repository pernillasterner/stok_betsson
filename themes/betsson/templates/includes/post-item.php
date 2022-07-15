<?php
	use Lib\Classes\VideoHelper;
    use Lib\Classes\GeneralHelper;

    $general = new GeneralHelper();

	$cardImageSize = '560x480';
	$postItem = $item['post'];
	$postImageUrl = get_template_directory_uri() . '/dist/images/placeholders/'. $cardImageSize .'.jpg';
	$postImageId = null;
	$postTitle = null;
	$postText = null;
	$postLinkText = get_field( 'read_more', 'default-texts' );
	$postLinkURL = null;

	if( $postItem ) {
		if( has_post_thumbnail( $postItem ) ) {
			$postImageUrl = get_the_post_thumbnail_url( $postItem, $cardImageSize );
			$postImageId = get_post_thumbnail_id( $postItem );
		}

		$postTitle = $postItem->post_title;
		$postText = $postItem->post_content;
		$postLinkURL = get_permalink( $postItem );
	}

	// Apply overrides
	$cardTitle = $item['title'] ? $item['title'] : $postTitle;
	$cardText = $item['text'] ? $item['text'] : $postText;
	$cardMediaType = $item['media_type'];

	// CTA
	$cta['url'] = $item['link'] ? $item['link'] : $postLinkURL;
	$cta['title'] = $item['link_text'] ? $item['link_text'] : $postLinkText;
	$cta['target'] = $item['open_in_new_tab'];
	$ctaStyle = $item['link_type'];
	?>

	<div class="<?php echo $gridClass; ?>">
		<article class="card">

			<?php
			if( $item['media_type'] === 'image' ) :
				$cardImageURL = !empty( $item['image'] ) ? wp_get_attachment_image_url( $item['image'], $cardImageSize ) : $postImageUrl;
				$cardImageAlt = !empty( $item['image'] ) ? $general->get_image_alt( $item['image'] ) : $general->get_image_alt( $postImageId );
				?>
				<figure class="card-image center-image <?php echo $imgClass; ?>">
					<?php if( $cta['url'] ) : ?><a href="<?php echo $cta['url']; ?>"><?php endif; ?>
						<img src="<?php echo $cardImageURL; ?>" alt="<?php echo $cardImageAlt; ?>" />
					<?php if( $cta['url'] ) : ?></a><?php endif; ?>
				</figure>

			<?php
			elseif( $item['media_type'] === 'video' ) :
				$videoURL = $item['video_link'];
				$cardImageURL = !empty( $item['preview_image'] ) ? wp_get_attachment_image_url( $item['preview_image'], $cardImageSize ) : $postImageUrl;
				$cardImageAlt = !empty( $item['preview_image'] ) ? $general->get_image_alt( $item['preview_image'] ) : $general->get_image_alt( $postImageId );
				$tag = VideoHelper::getVideoTag( $videoURL, false, false, false );
				?>
				<div class="video-holder center-image js-background-image js-play-video">
					<?php if( $cardImageURL ) : ?>
						<img src="<?php echo $cardImageURL; ?>" class="video-image" alt="<?php echo $cardImageAlt; ?>">
					<?php endif;?>
					<span class="icon icon-play"></span>
					<div class="video-background"><?php echo $tag; ?></div>
				</div>
			<?php endif; ?>

			<?php if( $cardTitle ) : ?>
				<h3 class="<?php echo $titleClass; ?>"><a href="<?php echo $cta['url']; ?>"><?php echo $cardTitle; ?></a></h3>
			<?php endif; ?>

			<?php if( $cardText ) : ?>
				<div class="excerpt">
					<p><?php echo wp_trim_words( $cardText, 60, '' ); ?></p>
				</div>
			<?php endif; ?>

			<?php
			$ctaClass = '';
			include( locate_template( 'templates/includes/cta.php' ) );
			?>

		</article>
	</div>
