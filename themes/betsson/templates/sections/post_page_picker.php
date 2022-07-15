<?php

$items = get_sub_field( 'items' );

if( $title || $text || $cta || $items ) : ?>

	<section class="section post-page-picker <?php echo $sectionClass; ?>">
		<div class="container">

            <?php
            $sectionParts = array( 'title', 'text', 'button-before' );
            foreach( $sectionParts as $part ) {
                include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
            }
            ?>

		</div>
		<?php

		$isSlider = get_sub_field( 'slider_on_mobile' );

		if( count($items) === 3 ) {
			$containerClass = 'is-wide';
			$gridClass = 'col-sm-4';
			$imgClass = 'has-shadow-big';
			$titleClass = 'h5';
		} else {
			$containerClass = '';
			$gridClass = count($items) === 1 ? 'col-sm-offset-3 col-sm-6' : 'col-sm-6';
			$imgClass = 'has-shadow-small';
			$titleClass = 'h4';
		}
		?>

		<div class="container cards <?php echo $containerClass; ?>">

			<?php if( $items ) : ?>

				<div class="row <?php echo $isSlider ? 'hidden-xs' : null; ?>">
					<?php
					foreach( $items as $item ) {
						include( locate_template( 'templates/includes/post-item.php' ) );
					}
					?>
				</div>

				<?php if( $isSlider ) : ?>
					<div class="mobile-carousel cards-carousel visible-xs">
						<?php
						foreach( $items as $item ) {
							$gridClass = 'item';
							$imgClass = 'has-shadow-big';
							$titleClass = 'h5';
							include( locate_template( 'templates/includes/post-item.php' ) );
						}
						?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			$cta = get_sub_field( 'cta' );
			$ctaStyle = get_sub_field( 'cta_style' );
            $ctaClass = 'text-center button-bottom';
			include( locate_template( 'templates/section-parts/section-button-after.php' ) );
			?>

		</div>
	</section>

<?php endif;
