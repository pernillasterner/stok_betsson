<?php

	$mobileLayout = get_sub_field( 'mobile_layout' );
	$locParentPage = get_field( 'all_locations_page', 'locations' );
	$locations = null;

	if( $locParentPage ) {
		$locations = get_pages( array(
		    'parent' => url_to_postid( $locParentPage ),
		    'sort_column' => 'menu_order',
		) );
	}

	if( $mobileLayout === 'full-50-50' ) {
		$sectionClass = 'is-full-5050';
		$isSlider = false;
	} elseif( $mobileLayout === 'slider' ) {
		$sectionClass = '';
		$isSlider = true;
	} else {
		$sectionClass = '';
		$isSlider = false;
	}

	$locationCount = count($locations);
	$counterGridLayout = false;

	if( $locationCount === 1 ) {
		$gridItemClass = 'grid-item--full';
	} elseif( $locationCount === 2 ) {
		$gridItemClass = 'grid-item--xlarge';
	} elseif( $locationCount === 4 || $locationCount % 4 === 0 ) {
		$gridItemClass = '';
	} elseif( $locationCount === 3 || $locationCount % 3 === 0 ) {
		$gridItemClass = 'grid-item--large';
	} else {
		$counterGridLayout = true;
	}

	if( $isSlider ){
		$sectionClass .= " is-slider-mobile";
	}

	if( $locations ) :
		?>

		<section class="section location-grids <?php echo $sectionClass; ?>">

			<div class="grid clearfix <?php echo $isSlider ? 'hidden-xs' : null; ?>">
				<?php
				$firstRowCount = null;
				$secondRowCount = null;
				$nextRowClass = '';

				if( $counterGridLayout ){
					switch( $locationCount % 4 ) {
						case 0:
							$gridItemClass = '';
							$firstRowCount = 4;
							$nextRowClass = '';
							$secondRowCount = 4;
							break;
						case 1:
							$gridItemClass = 'grid-item--xlarge';
							$firstRowCount = 2;
							$nextRowClass = 'grid-item--large';
							$secondRowCount = 3;
							break;
						case 2:
							$gridItemClass = 'grid-item--large';
							$firstRowCount = 3;
							$nextRowClass = 'grid-item--large';
							$secondRowCount = 3;
							break;
						case 3:
							$gridItemClass = 'grid-item--large';
							$firstRowCount = 3;
							$nextRowClass = '';
							$secondRowCount = 4;
							break;
					}
				}

				foreach( $locations as $key=>$item ) {
					$index = $key+1;

					if( $counterGridLayout && $index > ( $firstRowCount + $secondRowCount ) ) {
						$gridItemClass = '';
					} elseif( $counterGridLayout && $index > $firstRowCount ) {
						$gridItemClass = $nextRowClass;
					}

					$isLocationPage = true;
					$hasOverride = false;
					$gridItemClass .= ' grid-item';
					include( locate_template( 'templates/includes/location-item.php' ) );
				}
				?>
			</div>

			<?php if( $isSlider ) : ?>
			<div class="slider-carousel visible-xs">
				<?php
				foreach( $locations as $item ) {
					$gridItemClass = 'item';
					include( locate_template( 'templates/includes/location-item.php' ) );
				}
				?>
			</div>
			<?php endif; ?>

		</section>

	<?php endif;
