<?php
$jobTitle = get_field( 'job', 'default-texts' );
$deptTitle = get_field( 'department', 'default-texts' );
$locTitle = get_field( 'location', 'default-texts' );

$count = get_sub_field( 'job_count' );
$category = get_sub_field( 'filter_by_category' );
$location = get_sub_field( 'filter_by_location' );
$taxonomies = array( 'category', 'location' );
$taxQuery = array( 'relation' => 'AND' );

// Taxonomy filter
foreach( $taxonomies as $tax ) {
	if( $tax === 'category' && $category ) {
		$termId = $category;
	} elseif( $tax === 'location' && $location ) {
		$termId = $location;
	} else {
		continue;
	}

	$taxQuery[] = array(
		'taxonomy' => 'job-'. $tax,
		'field' => 'term_id',
		'terms' => $termId,
	);
}

$args = array(
	'post_type' => 'job',
	'post_status' => 'publish',
	'posts_per_page' => ( $count ? $count : -1 ),
	'tax_query' => $taxQuery,
	'meta_query' => array( 
		array(
			'key' => 'gh_job_status',
			'value' => 'active'
		)
	)
);

$wpQuery = new WP_Query( $args );
$jobs = $wpQuery->posts;

if( $title || $text || $cta || $jobs ) :
	?>

	<section class="section job-listing <?php echo $sectionClass; ?>">
		<div class="container">
			<?php
			$sectionParts = array( 'title', 'text', 'button-before' ); 
			foreach( $sectionParts as $part ) { 
				include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
			}
			?>

			<?php if( $jobs ) : ?> 
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
							foreach( $jobs as $job ) :
								$jobCategories = wp_get_post_terms( $job->ID, 'job-category' );
								$jobLocations = wp_get_post_terms( $job->ID, 'job-location' );

								if( $jobCategories ) {
									$departments = implode( ', ', array_map( function( $category ){
										return $category->name;
									}, $jobCategories ) );
								} else {
									$departments = '';
								}

								if( $jobLocations ) {
									$locations = implode( ', ', array_map( function( $location ){
										return $location->name;
									}, $jobLocations ) );
								} else {
									$locations = '';
								}                                    
								?>
								<div class="tr">
									<a href="<?php echo get_permalink( $job ); ?>">
										<div class="td job">
											<?php echo get_the_title( $job ); ?>
											<span class="department mobile"><?php echo $departments; ?></span>
										</div>
										<div class="td department"><?php echo $departments; ?></div>
										<div class="td location"><?php echo $locations; ?></div>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>
		</div>
		
	</section>

<?php endif;