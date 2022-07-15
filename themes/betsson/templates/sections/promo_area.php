<?php

	$promos = get_sub_field( 'items' );
	$promoCount = count($promos);
	$counterGridLayout = false;

	if( $promoCount === 1 ) {
		$gridItemClass = 'grid-item--full';
	} elseif( $promoCount === 2 ) {
		$gridItemClass = 'grid-item--xlarge';
	} else {
		$gridItemClass = 'grid-item--large';
	}

	if( $cta && $ctaLocation === 'before_content' ) {
		$sectionClass .= ' has-button-before';
	} elseif( $cta && $ctaLocation === 'after_content' ){
		$sectionClass .= ' has-button-after';
	}

	$hasHeader = ( $title || $text || ( $cta && $ctaLocation === 'before_content' ) );
	if( $hasHeader && $sectionCount === 1 ) {
		$sectionClass .= ' is-first';
	}

	if( $title || $text || $cta || $promos ) :
		?>

		<section class="section location-grids <?php echo $sectionClass; ?>">

			<?php if( $hasHeader ) : ?>
				<div class="container">
					<?php
					$sectionParts = array( 'title', 'text', 'button-before' ); 
					foreach( $sectionParts as $part ) { 
						include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
					}
					?>				
				</div>
			<?php endif; ?>

			<?php if( $promos ) : ?>
				<div class="grid clearfix">
					<?php
					foreach( $promos as $promo ) {
						$item = $promo['post'];
						$gridItemClass .= ' grid-item';
						$hasOverride = true;
						$isLocationPage = get_page_template_slug( $item ) === 'template-location.php';

						include( locate_template( 'templates/includes/location-item.php' ) );
					}
					?>
				</div>
			<?php endif; ?>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

		</section>

	<?php endif;
