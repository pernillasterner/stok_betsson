<?php
	use Lib\Classes\GeneralHelper;

	$general = new GeneralHelper(); 

	if( strpos( $gridItemClass, 'grid-item--full' ) > -1 ) {
		$placeholderImageSize = '1920x480';
		$cardImageSize = '1920x480';
	} elseif( strpos( $gridItemClass, 'grid-item--xlarge' ) > -1 ) {
		$placeholderImageSize = '960x480';
		$cardImageSize = '960x480';
	} elseif( strpos( $gridItemClass, 'grid-item--large' ) > -1 ) {
		$placeholderImageSize = '640x480';
		$cardImageSize = 'large';
	} else {
		$placeholderImageSize = '480x480';
		$cardImageSize = 'medium';
	}

	$postImage = get_template_directory_uri() . '/dist/images/placeholders/grid/'. $placeholderImageSize .'.jpg';
	$postThumbId = null;
	$cardSubheading = null;
	$cardTitle = null;
	$cardLinkURL = null;
	$cardText = null;
	$cardButtonText = null;
	$postTitle = $postLinkURL = $postSubheading = $postText = $postButtonText = null;
	$jobCount = 0;

	if( $item ) {
		if( has_post_thumbnail( $item ) ) {
			$postImage = get_the_post_thumbnail_url( $item, $cardImageSize );
			$postThumbId = get_post_thumbnail_id( $item );
		}			

		$jobCountry =  get_field( 'location', $item->ID );

		// Get country available jobs count
		if( $jobCountry ) {
		    $args = array(
		        'post_type' => 'job',
		        'post_status' => 'publish',
		        'posts_per_page' => -1,
		        'tax_query' => array( array(
		            'taxonomy' => 'job-location',
		            'field' => 'term_id',
		            'terms' => $jobCountry,
			    )),
		        'meta_query' => array( 
		            array(
		                'key' => 'gh_job_status',
		                'value' => 'active'
		            )
		        )			    
		    );
		    $wpQuery = new WP_Query( $args );
		    $jobCount = count( $wpQuery->posts );
		}

		$postTitle = $item->post_title;
		$postLinkURL = get_permalink( $item );

		if( !empty( $isLocationPage ) ) {
			$postSubheading = get_field( 'subheading', $item->ID );		
			$postText = $jobCount.' '.get_field( 'available_jobs_text', 'locations' );
			$postButtonText = get_field( 'button_text', 'locations' );			
		}

	}

	if( !empty($hasOverride) ) {
		$cardImage = $promo['image'] ? wp_get_attachment_image_url( $promo['image'], $cardImageSize ) : $postImage;
		$cardImageAlt = $general->get_image_alt( $promo['image'] );
		$cardSrcSet = $promo['image'] ? wp_get_attachment_image_srcset( $promo['image'], $cardImageSize  ) : wp_get_attachment_image_srcset( $postThumbId, $cardImageSize );
		$cardImageSizes = $promo['image'] ? wp_get_attachment_image_sizes( $promo['image'], $cardImageSize ) : wp_get_attachment_image_sizes( $postThumbId, $cardImageSize );
		$cardSubheading = $promo['subheading'] ? $promo['subheading'] : $postSubheading;
		$cardTitle = $promo['title'] ? $promo['title'] : $postTitle;
		$cardLinkURL = $promo['link'] ? $promo['link'] : $postLinkURL;
		$cardButtonText = $promo['link_text'] ? $promo['link_text'] : $postButtonText;
		$cardExternalLink = $promo['open_in_new_tab'] ? 'target="_blank" rel="nofollow noreferrer"' : null;

		$cardText = $postText;
		if( $promo['text'] ) {
	        $replaces['job-count']  = ' '.$jobCount.' '; 

	        $cardText = preg_replace_callback( '/\s?\[(.+?)\]\s?/', function ( $matches ) use ( $replaces ) {
	            return $replaces[ $matches[1] ];
	        }, $promo['text'] ); 
	        
	        $cardText = trim( $cardText );			
		}

	} else {
		$cardImage = $postImage;
		$cardImageAlt = $general->get_image_alt( $postThumbId );
		$cardSrcSet = wp_get_attachment_image_srcset( $postThumbId, $cardImageSize );
		$cardImageSizes = wp_get_attachment_image_sizes( $postThumbId, $cardImageSize );
		$cardSubheading = $postSubheading;
		$cardTitle = $postTitle;
		$cardLinkURL = $postLinkURL;
		$cardText = $postText;
		$cardButtonText = $postButtonText;
		$cardExternalLink = null;
	}
	?>

	<a <?php echo $cardLinkURL ? 'href="'.$cardLinkURL.'"' : null; ?> class="<?php echo $gridItemClass; ?>" <?php echo $cardExternalLink; ?>>
		<div class="location-item center-image">
			<img src="<?php echo $cardImage; ?>" alt="<?php echo $cardImageAlt; ?>" srcset="<?php echo $cardSrcSet; ?>" sizes="<?php echo $cardImageSizes; ?>"/>
			<div class="location-content">

				<?php if( $cardSubheading ) : ?>
				<p class="small"><?php echo $cardSubheading; ?></p>
				<?php endif; ?>

				<h3 class="h4"><?php echo $cardTitle; ?></h3>

				<?php if( $cardText ) : ?>
				<p class="job-number"><?php echo $cardText; ?></p>
				<?php endif; ?>

				<?php if( $cardButtonText && $cardLinkURL ) : ?>
				<p><div class="btn btn-primary">
					<?php echo $cardButtonText; ?>
				</div></p>
				<?php endif; ?>

			</div>
		</div>
	</a>