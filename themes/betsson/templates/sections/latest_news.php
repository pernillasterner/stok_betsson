<?php

    $readArticle = get_sub_field( 'read_article_link_text' );
    $category = get_sub_field( 'filter_by_category' );
    $displayType = get_sub_field( 'section_displays' );
	$postCount = get_sub_field( 'post_count' );
	$displayShowMore = get_sub_field( 'display_show_more_button' );
	$showMoreText = get_sub_field( 'show_more_button_text' );
	$excludedIds = array();

    if( $displayType === 'handpick' ) {
        $posts = get_sub_field( 'handpick_news' );

    } else {
        $taxQuery = array();       

        if( $displayType === 'category' && $category  ) {
            $taxQuery[] = array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $category,
            );            
        }
        
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $postCount,
            'orderby' => 'ID',
            'order' => 'DESC',
            'tax_query' => $taxQuery
        );

        $wpQuery = new WP_Query( $args );
		$posts = $wpQuery->posts;
		
		if( $wpQuery->max_num_pages == 1 ) {
			$displayShowMore = false;
		}
	}	

    if( $title || $text || $cta || $posts ) :
        ?>

        <section class="section latest-news <?php echo $sectionClass; ?>">
            <div class="container">

                <?php
                $sectionParts = array( 'title', 'text', 'button-before' ); 
                foreach( $sectionParts as $part ) { 
                    include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
                }
                ?>

                <?php if( $posts ) : ?>
                    <div id="news-container"><?php include( locate_template( 'templates/includes/latest_news_items.php' ) ); ?></div>
                <?php endif; ?>

				<div class="text-center">
					<div class="loader" style="display:none">Loading...</div>
				</div>

				<?php
				if( $displayShowMore ):
					$ajaxData = json_encode( array(
						'tax_query' => $taxQuery,
						'excluded_ids' => $excludedIds
					) );
				?>
					<script id="news-data" type="application/json"><?= $ajaxData ?></script>
                    <div class="text-center button-bottom">
						<button id="news-show-more" type="button" class="btn btn-secondary"><?= $showMoreText ?></button>
					</div>
				<?php endif; ?>

                <?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

            </div>
        </section>

    <?php endif;
