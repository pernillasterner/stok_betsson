<?php
$optionsId = 'jobs';
$title = get_field( 'title', $optionsId );
$applyText = get_field( 'apply_now_text', $optionsId );
$seeJobsText = get_field( 'see_all_jobs_text', $optionsId );

$jobsPageId = get_field( 'jobs_page', 'default-pages' );

if( !$jobsPageId ) {
	return;
}

$seeJobsLink = get_permalink( $jobsPageId );

$hasArrow = get_sub_field( 'show_arrow?' );

$featuredJobs = get_posts( array(
	'post_type' => 'job',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'orderby' => 'ID',
	'meta_query' => array(
		'relation' => 'AND',
		'prioritized_clause' => array(
			'key' => 'gh_job_prioritized_ad',
			'value' => '1'
		),
		'status_clause' => array(
			'key' => 'gh_job_status',
			'value' => 'active'
		)
	)
) );

if( $hasArrow && empty($isLastSection) ) {
	$sectionClass .= ' has-arrow';
}

if( $featuredJobs || $title || $applyText || ( $seeJobsText && $seeJobsLink ) ) :
	?>

	<section class="section featured-jobs <?php echo $sectionClass; ?>">
		<div class="container text-center">

			<?php if( $title ) : ?>
			<h4><?php echo $title; ?></h4>
			<?php endif; ?>

			<?php if( $featuredJobs ) : ?>
				<div class="typed-container">
					<div id="typed-strings-<?php echo $sectionCount; ?>" class="typed-strings">
						<?php
						foreach( $featuredJobs as $job ) {
							echo '<p data-url="'. get_the_permalink( $job ) .'">'. get_the_title( $job ) .'</p>';
						}
						?>
					</div>
					<span id="typed-<?php echo $sectionCount; ?>" class="typed"></span>
				</div>
			<?php endif; ?>

			<?php if( $applyText ) : ?>
			<p><a href="#" class="btn btn-underlined js-apply-btn""><?php echo $applyText; ?></a></p>
			<?php endif; ?>

			<?php
			if( $seeJobsText && $seeJobsLink ) {
				$ctaClass = 'text-center button-bottom';
				$ctaStyle = 'button';
				$cta = [
					'title' => $seeJobsText,
					'url' => $seeJobsLink,
					'target' => false
				];
				include( locate_template( 'templates/includes/cta.php' ) );
			}
			?>

		</div>
	</section>

<?php endif;
