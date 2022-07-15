<?php
		
	$columns = get_sub_field( 'columns' );

	if( $title || $text || $cta || $columns ) :
		?>

		<section class="section section-three-col <?php echo $sectionClass; ?>">
			<div class="container">

				<?php
				$sectionParts = array( 'title', 'text', 'button-before' ); 
				foreach( $sectionParts as $part ) { 
					include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
				}
				?>		

				<?php if( $columns ) : ?>
					<div class="row">

						<?php
						if( count($columns) === 3 ) {
							$gridClass = 'col-sm-4';
							$cardImageSize = '360x270';
						} else {
							$gridClass = count($columns) === 1 ? 'col-sm-offset-3 col-sm-6' : 'col-sm-6';
							$cardImageSize = 'medium';
						}

						foreach( $columns as $col ) : 
							$cardImage = $col['image'];
							$cardTitle = $col['title'];
							$isTextFirst = $col['layout'] === 'text-top';
							?>
							<div class="<?php echo $gridClass; ?>">
								<article>
									<?php if( $cardImage ) : ?>
									<figure class="<?php echo $isTextFirst ? 'visible-xs' : null; ?>">
										<?php echo wp_get_attachment_image( $cardImage, $cardImageSize ); ?>
									</figure>
									<?php endif; ?>
									
									<?php if( $cardTitle ) : ?>
									<h3 class="h5 text-center"><?php echo $cardTitle; ?></h3>
									<?php endif; ?>

									<?php echo apply_filters( 'the_content', $col['text'] ); ?>

									<?php if( $isTextFirst && $cardImage ) : ?>
									<figure class="hidden-xs">
										<?php echo wp_get_attachment_image( $cardImage, $cardImageSize ); ?>
									</figure>
									<?php endif; ?>
								</article>
							</div>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

				<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>			

			</div>
		</section>

	<?php endif; 