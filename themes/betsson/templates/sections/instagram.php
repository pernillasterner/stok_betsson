<?php

    $hashtags = get_sub_field( 'filter_by_category' );
    $count = get_sub_field( 'count' ) ? get_sub_field( 'count' ) : -1;
    $tax_query = '';
    $fill = array();

    $args = array(
        'post_type'           => 'instagram',
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post_status'         => 'publish',
        'posts_per_page'      => $count
        // 'tax_query'           => $tax_query
    );

    if( $hashtags ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'hashtag',
                'field'    => 'term_id',
                'terms'    => $hashtags,
            )
        );
    }

    $result = new WP_Query( $args );
    $instagramIDs = wp_list_pluck( $result->posts, 'ID' );

    $instaImages = array_map(
        function( $id ) {
            return wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'medium' )[0];
        },
        $instagramIDs
    );

    $instaImagesCount = count( $instaImages );
    $imagesToDisplay = $instaImagesCount - ( $instaImagesCount % 6 );

    if( $title || $text || $cta || $instaImages ) :
		?>

		<section class="section section-instagram <?php echo $sectionClass; ?>">

            <?php
            $sectionParts = array( 'title', 'text', 'button-before' );
            foreach( $sectionParts as $part ) {
                include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
            }
            ?>

			<?php if( $instaImages ) : ?>
			<div class="instagram clearfix">
				<?php
				foreach( $instaImages as $key=>$image ) :
					if( $instaImagesCount > 6 && ( $key+1 ) > $imagesToDisplay ) { break; }
					?>
					<figure class="center-image"><img src="<?php echo $image; ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" /></figure>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

            <?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

		</section>

	<?php endif;
