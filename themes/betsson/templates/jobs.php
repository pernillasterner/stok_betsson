<?php
// Get initial count of jobs from jobs page
$jobsPageId = get_field( 'jobs_page', 'default-pages' );

// Table header translations
$jobTitle = get_field( 'job', 'default-texts' );
$deptTitle = get_field( 'department', 'default-texts' );
$locTitle = get_field( 'location', 'default-texts' );

if( !$jobsPageId ) {
	return;
}

$moreButtonText = get_field( 'see_all_jobs_text', $jobsPageId );
$subscribeButtonText = get_field( 'subscribe_button_text', $jobsPageId );
$matchText = get_field( 'job_match_text', $jobsPageId );
?>

<section class="section job-subscribe has-arrow hidden-xs">
	<div class="container">
		<div class="text-center">
			<span class="js-jobs-count"></span> <span><?= $matchText ?></span>
			<button type="button" class="js-subscribe-button btn btn-secondary btn-xs"><?= $subscribeButtonText ?></button>
		</div>
	</div>
</section>
<section class="section job-listing is-primary" style="display:none">
	<div class="container">

		<div class="job-subscribe text-center visible-xs">
			<span class="js-jobs-count"></span> <span><?= $matchText ?></span>
			<button type="button" class="js-subscribe-button btn btn-secondary btn-xs"><?= $subscribeButtonText ?></button>
		</div>

		<div class="table-container">
			<div class="table">
				<div class="thead">
					<div class="tr">
                        <div class="th col-job"><?php echo $jobTitle; ?></div>
                        <div class="th col-department"><?php echo $deptTitle; ?></div>
                        <div class="th col-location"><?php echo $locTitle; ?></div>						
					</div>
				</div>
				<div class="tbody" id="jobs-container"></div>
			</div>
		</div>

		<div class="text-center">
			<button id="jobs-show-more" type="button" class="btn btn-secondary"><?= $moreButtonText ?></button>
		</div>
		<div class="text-center">
			<div class="loader">Loading...</div>
		</div>
	</div>
</section>

