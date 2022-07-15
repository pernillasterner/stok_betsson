<?php

	if( is_tax() ) {
		$term = get_queried_object()->term_id;
		$taxonomy = get_queried_object()->taxonomy;
	}

	// Post query filters
    $args = array(
        'post_type' => $postType,
        'post_status' => 'publish',
	    'orderby' => 'menu_order',
    );

    // Category
    if( !empty( $term ) && $term !== 'false' ) {
	    $args['tax_query'] = array( array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'terms' => $term,
	    ));
    }

	// No. of post
	if( !empty( $count ) ) {
		$postPerPage = $count;
	} elseif( !empty( $postPerPage ) ) {
		$postPerPage = $postPerPage;
	} else {
		$postPerPage = -1;
	}

	// If called by an ajax request, Show all remaining posts
	if ( !empty( $isAjax ) ) {
		$args['post__not_in'] = ( isset( $excluded ) ) ? $excluded : null;
		$args['posts_per_page'] = -1;
	} else {
		$args['posts_per_page'] = $postPerPage;
	}

    $wpQuery = new \WP_Query( $args );
    $posts = $wpQuery->posts;

	$results = array();
	$excluded = array();
	$hasMore = $wpQuery->max_num_pages > 1;

	if( $posts ) {
		foreach( $posts as $item ) {
			$excluded[] = $item->ID;

	        if( !empty( $isAjax ) ) {
		        ob_start();
		        include( locate_template( 'templates/includes/faq.php' ) );
		    	$html = ob_get_clean();
		    	@ob_end_clean();

		    	array_push( $results, $html );
	        } else {
	        	include( locate_template( 'templates/includes/faq.php' ) );
	        }
		}

	} else {
        if( !empty( $isAjax ) ) {
	        ob_start();
    		echo '<p>'. $noPostsAvailable .'</p>';
	    	$html = ob_get_clean();
	    	@ob_end_clean();

	    	array_push( $results, $html );
        } else {
        	echo '<p>'. $noPostsAvailable .'</p>';
        }		
	}


