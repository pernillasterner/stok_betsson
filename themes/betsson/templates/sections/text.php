<?php

$sectionAttr = null;
$backgroundDesktopUrl = '';
$backgroundMobileUrl = '';
$isBgImage = get_sub_field( 'is_bg_image' );
$image = get_sub_field( 'image' );
$mobileImage = get_sub_field( 'image_mobile' );
$makeTextWhite = get_sub_field( 'make_text_color_white' );


if( $isBgImage ) {
	// Background Image
	if( $image ) {
	    $backgroundDesktopUrl = wp_get_attachment_image_url( $image, 'banner-desktop' );
		$backgroundMobileUrl = wp_get_attachment_image_url( $image, 'banner-mobile' );
		$sectionClass .= ' has-background-image';
	}

	// Background Image Mobile
	if( $mobileImage ) {
	    $backgroundMobileUrl = wp_get_attachment_image_url( $mobileImage, 'banner-mobile' );
	}

	if( $makeTextWhite ) {
		$sectionClass .= ' has-white-text';
	}

	$sectionClass .= ' js-image-switch';
	$sectionAttr .= 'style="background-image:url('. $backgroundDesktopUrl .')"';
	$sectionAttr .= 'data-desktopBackground="'. $backgroundDesktopUrl .'"';
	$sectionAttr .= 'data-mobileBackground="'. $backgroundMobileUrl .'"';
}

if( $title || $text || $cta || $backgroundDesktopUrl || $backgroundMobileUrl ) : ?>

	<section class="section section-text <?php echo $sectionClass; ?>" <?php echo $sectionAttr; ?>>
		<div class="container">

<?php
			$sectionParts = array( 'title', 'button-before' );
			foreach( $sectionParts as $part ) {
				include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
			}
			?>

			<?php if( $text ) : ?>
			<div class="content is-small text-center medium <?php echo !empty( $isAllCaps ) ? 'text-uppercase' : null; ?>">
				<?php echo apply_filters( 'the_content', $text ); ?>
			</div>
			<?php endif; ?>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

		</div>
	</section>

<?php endif;
