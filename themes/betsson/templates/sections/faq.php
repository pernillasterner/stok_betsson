<?php
	
	$count = get_sub_field( 'count' );
	$term = get_sub_field( 'filter_by_category' );
	$isShowAll = get_sub_field( 'display_show_all_button' );
	$showAllBtn = get_sub_field( 'show_all_button_text' );
	$noPostsAvailable = get_sub_field( 'no_posts_available' );
	$ctaButtonClass = null;
	$ctaButtonAttr = null;
	$postType = 'faq';
	$taxonomy = 'faq-category';
	?>

	<section class="section faq js-listing <?php echo $sectionClass; ?>">
		<div class="container">

            <?php
            $sectionParts = array( 'title', 'text', 'button-before' ); 
            foreach( $sectionParts as $part ) { 
                include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
            }
            ?> 

			<div id="js-ajax-container-<?php echo $sectionCount; ?>" class="accordion">
				<?php include( locate_template( 'templates/includes/faq-list.php' ) ); ?>
			</div>

			<?php

			// Show all button
			if( $isShowAll ) {
				$listingData = array(
					'listingContainer' => '#js-ajax-container-'. $sectionCount,
					'hasMore' => $hasMore,
					'postType' => $postType,
					'term' => $term,
					'taxonomy' => $taxonomy,
					'excluded' => $excluded
				);
				$cta['title'] = $showAllBtn ? $showAllBtn : 'Show All';
				$cta['target'] = false;
				$ctaButtonClass = 'js-show-all';
				$ctaButtonAttr = 'data-listing=' .json_encode( $listingData );

				$ctaClass = 'text-center button-bottom';
				include( locate_template( 'templates/includes/cta.php' ) );
			}

			// CTA after content
			$ctaButtonClass = null;
			$ctaButtonAttr = null;
			$cta = get_sub_field( 'cta' ); 
			include( locate_template( 'templates/section-parts/section-button-after.php' ) ); 
			?>

			<div class="text-center">
				<div class="loader" style="display: none;"></div>
			</div>			

		</div>
	</section>