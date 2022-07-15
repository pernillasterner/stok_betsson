<?php    
use Lib\Classes\GeneralHelper;

$general = new GeneralHelper();    
$categories = get_sub_field( 'categories' );
$ctaLocation = 'after_content';

if( $title || $text || $cta || $categories ) :
?>

	<section class="section find-jobs <?php echo $sectionClass; ?>">
		<div class="container">
			
			<?php include( locate_template( 'templates/section-parts/section-title.php' ) ); ?> 

			<?php if( $categories ) : ?>
				<div class="departments">
					<ul>
						<?php
						$jobsPage = get_field( 'jobs_page', 'default-pages' );
						$jobsPageLink = get_permalink( $jobsPage );
						foreach( $categories as $category ) : 
							$categoryTaxonomy = 'job-category';
							$categoryId = $category['category'];
							if( !term_exists( $categoryId, $categoryTaxonomy ) ) { continue; }

							$category = get_term( $categoryId, $categoryTaxonomy );
							$categoryIconURL = get_template_directory_uri() . '/dist/images/placeholders/department-placeholder.svg';
							$categoryIcon = null;

							if( $categoryIcon = get_field( 'icon', $category->taxonomy . '_' . $category->term_id ) ) {
								$categoryIconURL = wp_get_attachment_image_url( $categoryIcon, '182x182' );
							}
							?>
							<li>
								<a href="#" class="js-department-filter" data-id="<?= $category->term_id ?>" data-base-url="<?= $jobsPageLink ?>">
									<figure>
										<img src="<?php echo $categoryIconURL; ?>" class="img-responsive" alt="<?php echo $general->get_image_alt( $categoryIcon ); ?>"/>
									</figure>
									<div class="text"><?php echo $category->name; ?></div>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?> 

		</div>
	</section>

<?php endif;