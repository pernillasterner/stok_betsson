<?php

	$showSliderInMobile = get_sub_field( 'slider_on_mobile' );

	if( get_sub_field( 'content_type' ) === 'contact' ) {
		$offices = get_field( 'items', 'contact' );
		$sectionClass .= ' section-contact';
	} else {
		$offices = get_field( 'items', 'offices' );
	}


	if( $title || $text || $cta || $offices ) :
		?>

		<section class="section section-offices <?php echo $sectionClass; ?>">

			<?php
			$sectionParts = array( 'title', 'text', 'button-before' );
			foreach( $sectionParts as $part ) {
				include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
			}
			?>

			<?php
			if( $offices ) :

				$officesCount = count($offices);
				$counterGridLayout = false;
				$rowItemCount = 4;

				if( $officesCount === 1 ) {
					$gridItemClass = 'col-sm-12';
					$rowItemCount = 1;
				} elseif( $officesCount === 2 ) {
					$gridItemClass = 'col-sm-6';
					$rowItemCount = 2;
				} elseif( $officesCount === 4 || $officesCount % 4 === 0 ) {
					$gridItemClass = 'col-sm-3';
					$rowItemCount = 4;
				} elseif( $officesCount <= 3 || $officesCount % 3 === 0 ) {
					$gridItemClass = 'col-sm-4';
					$rowItemCount = $officesCount <= 3 ? $officesCount : 3;
				} else {
					$counterGridLayout = true;
				}

				$firstRowCount = null;
				$secondRowCount = null;
				$nextRowClass = '';

				if( $counterGridLayout ){
					switch( $officesCount % 4 ) {
						case 0:
							$gridItemClass = 'col-sm-6 col-md-3';
							$firstRowCount = 4;
							$nextRowClass = 'col-sm-6 col-md-3';
							$secondRowCount = 4;
							break;
						case 1:
							$gridItemClass = 'col-sm-6';
							$firstRowCount = 2;
							$nextRowClass = 'col-sm-4';
							$secondRowCount = 3;
							break;
						case 2:
							$gridItemClass = 'col-sm-4';
							$firstRowCount = 3;
							$nextRowClass = 'col-sm-4';
							$secondRowCount = 3;
							break;
						case 3:
							$gridItemClass = 'col-sm-4';
							$firstRowCount = 3;
							$nextRowClass = 'col-sm-6 col-md-3';
							$secondRowCount = 4;
							break;
					}
				}

				$rowCount = 0;
				$itemCount = 0;
				foreach( $offices as $key=>$info ) :
					$isSlider = false;
					$index = $key+1;
					$counterGridStartIndexes = array( 1, $firstRowCount+1, $firstRowCount+$secondRowCount+1 );
					$isCounterGridNewRow = $counterGridLayout && ( in_array( $index, $counterGridStartIndexes ) );

					if( ( !$counterGridLayout && ( $index === 1 || $index % $rowItemCount === 1 ) ) || $isCounterGridNewRow ) {
						$rowCount++;
						$itemCount = 0;
					}

					$itemCount++;

					if( $counterGridLayout && $index > ( $firstRowCount + $secondRowCount ) ) {
						$gridItemClass = 'col-sm-6 col-md-3';
					} elseif( $counterGridLayout && $index > $firstRowCount ) {
						$gridItemClass = $nextRowClass;
					}
					?>

					<?php
					$containerClass = null;
					if( $itemCount === 1 ) :
						$containerClass = ( $gridItemClass === 'col-sm-6 col-md-3' || $gridItemClass === 'col-sm-3' ) ? 'is-wide' : null;
						$containerClass .= ( $showSliderInMobile ) ? ' hidden-xs' : null;
						?>
						<div class="container <?php echo $containerClass; ?>">
							<div class="row">
					<?php endif;  ?>

						<?php include( locate_template( 'templates/includes/contact-info.php' ) ); ?>

					<?php
					$counterGridEndIndexes = array( $firstRowCount, $firstRowCount+$secondRowCount );
					$isCounterGridRowEnd = $counterGridLayout && ( in_array( $index, $counterGridEndIndexes ) );

					if( ( !$counterGridLayout && ( $index === $rowItemCount || $index % $rowItemCount === 0 ) ) || $isCounterGridRowEnd || $index === $officesCount ) : ?>
						</div>
					</div>
					<?php endif;  ?>

				<?php endforeach; ?>

				<?php if( $showSliderInMobile ) : ?>
					<div class="cards-carousel visible-xs">
						<?php
						foreach( $offices as $info ) {
							$isSlider = true;
							include( locate_template( 'templates/includes/contact-info.php' ) );
						}
						?>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

		</section>

	<?php endif;
