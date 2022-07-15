<?php

use Lib\Classes\GreenhouseHelper;
use Lib\Classes\ContentHelper;

global $post;

$locations = wp_get_post_terms( get_the_ID(), 'job-location' );
$location = $locations ? $locations[0]->name : null;

$categories = wp_get_post_terms( get_the_ID(), 'job-category' );
$category = $categories ? $categories[0]->name : null;

$recruiters = wp_get_post_terms( get_the_ID(), 'job-recruiter' );
$recruiter = $recruiters ? $recruiters[0] : null;

$applyByText = get_field( 'apply_by', 'default-texts' );
$publishedText = get_field( 'published_date', 'default-texts' );

$recruiterContent = null;

if( $recruiter ) {
	$recruiterImage = get_term_meta( $recruiter->term_id, 'image', true );
	if( $recruiterImage ) {
		$recruiterImage = wp_get_attachment_image( $recruiterImage, '260x300' );
	} else {
        $recruiterImageUrl = get_template_directory_uri() . '/dist/images/placeholders/260x300-user.jpg';
		$recruiterImage = '<img src="'. $recruiterImageUrl .'">';
	}

	$recruiterContent =
	'<div class="recruiter-info">
		<a href="">
			<figure>'.$recruiterImage.'</figure>
			<p>RECRUITER <br />'. $recruiter->name .'</p>
		</a>
	</div>';
}

// Append apply button
$jobContent = apply_filters( 'the_content', $post->post_content );
$jobContent .= '<div class="text-center button-below hidden-xs"><a href="#" class="js-scroll-to-desktop-form btn btn-secondary">Apply Now!</a></div>';

// Append mobile apply button
$contentBetween = GreenhouseHelper::get_mobile_application_form();
?>

<section class="section is-first selected-page">

    <?php get_template_part( 'templates/includes/share-the-page' ); ?>

    <div class="selected-page-wrap">
        <div class="bg-block is-primary"></div>
        <div class="container">
            <div class="row has-pad-mobile">
                <div class="col-md-12">
                    <div class="selected-page-header">

                        <ul class="selected-page-category small">
                        	<?php
                        	echo $location ? '<li class="is-active">'. $location .'</li>' : null;
                        	echo $category ? '<li>'. $category .'</li>' : null;
                        	?>
                        </ul>

                        <h1 class="h2"><?= the_title() ?></h1>
                    </div>
                </div>
			</div>

			<?= ContentHelper::modify_first_paragraph( $jobContent, $recruiterContent, null, 'is-medium', 'text', $contentBetween ); ?>
		</div>
	</div>
</section>

<!-- APPLY BUTTON MOBILE -->
<section class="section arrow-holder has-arrow visible-xs">
	<div class="text-center"><a href="#" class="js-scroll-show-mobile-form btn btn-secondary">Apply Now!</a></div>
</section>


<div id="job-application-form-container" class="hidden-xs">
	<?= GreenhouseHelper::get_application_form( $post ); ?>
</div>

<?php include( locate_template( 'templates/sections.php' ) ); ?>
