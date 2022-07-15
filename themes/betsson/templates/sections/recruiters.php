<?php
if( get_sub_field( 'section_displays' ) === 'handpick' ) {
	$recruiters = get_sub_field( 'recruiters' );
} else {
	$args = null;
	$count = get_sub_field( 'count' ) ? get_sub_field( 'count' ) : -1;

	$terms = get_terms( array(
		'taxonomy' => 'job-recruiter',
		'hide_empty' => true
	) );

	shuffle( $terms );

	$recruiters = array_slice( $terms, 0, $count );
}

if( !$recruiters ) {
	return;
}

$layout = get_sub_field( 'layout' );

if( $layout === 'image_name_text' ) {
	$sectionClass .= ' section-three-col';
}

if( $title || $text || $cta || $recruiters ) :
	?>

	<section class="section recruiters <?php echo $sectionClass; ?>">
		<div class="container">

			<?php
			$sectionParts = array( 'title', 'text', 'button-before' );
			foreach( $sectionParts as $part ) {
				include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
			}
			?>

			<?php if( $layout === 'image_name' ) : ?>
				<div class="recruiter-cards hidden-xs">
					<?php
					$itemCtr = 0;
					foreach( $recruiters as $item ) {
						$itemCtr++;
						$cardClass = $itemCtr === 1 ? 'no-margin--left' : null;
						$isDepartment = false;
						$isRecruiter = true;

						include( locate_template( 'templates/includes/recruiter-item.php' ) );
						include( locate_template( 'templates/includes/recruiter-modal.php' ) );
					} ?>
				</div>

				<div class="cards-carousel visible-xs">
					<?php
					foreach( $recruiters as $item ) {
						$cardClass = null;
						$isRecruiter = false;
						include( locate_template( 'templates/includes/recruiter-item.php' ) );
					}
					?>
				</div>
			<?php elseif( $layout === 'image_name_text' ) : ?>
				<div class="row">
					<?php
					if( count( $recruiters ) === 3 ) {
						$gridClass = 'col-sm-4';
						$cardImageSize = '360x270';
					} else {
						$gridClass = count( $recruiters ) === 1 ? 'col-sm-offset-3 col-sm-6' : 'col-sm-6';
						$cardImageSize = 'medium';
					}

					foreach( $recruiters as $item ) : 
						$cardImage = get_term_meta( $item->term_id, 'image', true );
						$cardTitle = $item->name;
					?>
						<div class="<?php echo $gridClass; ?>">
							<article>
								<?php if( $cardImage ) : ?>
									<figure>
										<?php echo wp_get_attachment_image( $cardImage, $cardImageSize ); ?>
									</figure>
								<?php endif; ?>
								
								<?php if( $cardTitle ) : ?>
									<h3 class="h5 text-center"><?php echo $cardTitle; ?></h3>
								<?php endif; ?>

								<?php echo apply_filters( 'the_content', $item->description ); ?>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			$isDepartment = false;
			include( locate_template( 'templates/section-parts/section-button-after.php' ) );
			?>

		</div>
	</section>
<?php endif;
