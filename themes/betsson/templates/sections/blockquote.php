<?php
$subheadline = get_sub_field( 'subheadline' );
$quote = get_sub_field( 'quote' );
$image = wp_get_attachment_image( get_sub_field( 'image' ), '250x190' );
$cta = get_sub_field( 'cta' );
$hasArrow = get_sub_field( 'show_arrow?' );
$ctaLocation = 'after_content';
$hasSubheading = trim( implode( $subheadline, '' ) );

if( $subheadline['firstname'] || $subheadline['lastname'] || $subheadline['position'] || $subheadline['country'] || $quote || $image ) :
	?>

	<section class="section blockquote <?php echo $hasArrow && empty($isLastSection) ? 'has-arrow' : null; ?><?php echo ($image) ? null : 'no-image'; ?>">
		<div class="blockquote-container">
			<div class="container">
				<div class="image">
					<?php if( $image ) : ?>
						<figure class="center-image"><?= $image ?></figure>
					<?php endif; ?>				
				</div>

				<?php if( $hasSubheading || $quote ) : ?>
					<div class="quote">
						<div class="content">
							<?php if( $hasSubheading ) : ?>
								<div class="text"><strong><?= $subheadline['firstname'] ?> <?= $subheadline['lastname'] ?></strong> - <?= $subheadline['position'] ?>, <?= $subheadline['country'] ?></div>
							<?php endif; ?>

							<?php echo $quote ? '<p>'. $quote .'</p>' : null; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>
		</div>
	</section>

<?php endif;