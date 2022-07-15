<?php
if( !is_singular( 'job' ) || !isset( $categories ) || !$categories ) {
	return;
}

$jobTitle = get_field( 'job', 'default-texts' );
$deptTitle = get_field( 'department', 'default-texts' );
$locTitle = get_field( 'location', 'default-texts' );
$hasHeader = ( $title || $text || ( $cta && $ctaLocation === 'before_content' ) );

$relatedJobs = get_posts( array(
	'post_type' => 'job',
	'posts_per_page' => get_sub_field( 'count' ),
	'post__not_in' => array( $post->ID ),
	'tax_query' => array(
		array(
			'taxonomy' => 'job-category',
			'terms' => $categories[0]->term_id
		)
	),
	'meta_query' => array(
		array(
			'key' => 'gh_job_status',
			'value' => 'active'
		)
	 )
) );

if( !$relatedJobs ) {
	return;
}
?>

<?php if( $hasHeader ) : ?>
	<section class="section has-arrow hidden-xs">
		<div class="container">

			<?php
			$sectionParts = array( 'title', 'text', 'button-before' ); 
			foreach( $sectionParts as $part ) { 
				include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
			}
			?>

		</div>
	</section>
<?php endif; ?>

<section class="section related-positions <?php echo $sectionClass; ?>">
	<div class="container">

		<?php if( $hasHeader ) : ?>
			<div class="visible-xs">
				<?php
				$sectionParts = array( 'title', 'text', 'button-before' ); 
				foreach( $sectionParts as $part ) { 
					include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
				}
				?>
			</div>
		<?php endif; ?>

		<div class="table-container">
			<div class="table">
				<div class="thead">
					<div class="tr">
                        <div class="th col-job"><?php echo $jobTitle; ?></div>
                        <div class="th col-department"><?php echo $deptTitle; ?></div>
                        <div class="th col-location"><?php echo $locTitle; ?></div>						
					</div>
				</div>
				<div class="tbody">
					<?php
						foreach( $relatedJobs as $job ):
							$locations = wp_get_post_terms( $job->ID, 'job-location' );
							$location = $locations ? $locations[0] : false;

							$categories = wp_get_post_terms( $job->ID, 'job-category' );
							$categories = array_map( function( $item ){
								return $item->name;
							}, $categories );
							$categoriesList = implode( ',', $categories );
						?>
						<div class="tr">
							<a href="<?= get_permalink( $job ) ?>">
								<div class="td job">
								<?= $job->post_title ?>
									<span class="department mobile"><?= $categoriesList ?></span>
								</div>
								<div class="td department"><?= $categoriesList ?></div>
								<div class="td location"><?= $location->name ?></div>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

	</div>
</section>
