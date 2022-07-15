<?php
use Roots\Sage\Titles; 

$acfId = 'general'; 
$logo = get_field( 'logo', $acfId );
$title = get_field( '404_title', $acfId );
$text = get_field( '404_text', $acfId );
$homeBtnText = get_field( '404_home_button_text', $acfId );
$cta = get_field( '404_contact_page_cta', $acfId );
$image = get_field( '404_image', $acfId );
?>
<div class="not-found">
	<div class="not-found-info">
		<section class="section black-frame ">
			<div class="container">
				<?php if( $logo ) : ?>
				<a class="brand" href="<?php echo esc_url( home_url() ); ?>">
					<img class="img-responsive" src="<?php echo wp_get_attachment_image_url( $logo, 'medium'); ?>" alt=" <?php bloginfo('name'); ?>">
				</a>
				<?php endif; ?>

				<div class="black-frame-container is-right">
					<div class="box">
					<h1 class="h3"><?php echo $title ? $title : Titles\title(); ?></h3>
					<div class="lead">
						<?php echo apply_filters( 'the_content', $text ); ?>
						<div class="button-bottom hidden-sm hidden-xs">
							<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-secondary"><?php echo $homeBtnText ? $homeBtnText : 'Back to home.'; ?></a>

							<?php if( $cta ) : ?>
								<a href="<?php echo $cta['url']; ?>" 
									class="btn btn-primary"
									<?php echo $cta['target'] ? 'target="_blank" rel="nofollow noreferrer"' : null; ?>>
									<?php echo $cta['title']; ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if( $image ) : ?>
				<div class="image">
					<figure class="center-image"> <?php echo wp_get_attachment_image( $image, 'large' ); ?></figure>
				</div>
				<?php endif; ?>

				<div class="text-center button-bottom visible-sm visible-xs">
					<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-secondary"><?php echo $homeBtnText ? $homeBtnText : 'Back to home.'; ?></a>

					<?php if( $cta ) : ?>
						<a href="<?php echo $cta['url']; ?>" 
							class="btn btn-primary"
							<?php echo $cta['target'] ? 'target="_blank" rel="nofollow noreferrer"' : null; ?>>
							<?php echo $cta['title']; ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</div>
</div>
