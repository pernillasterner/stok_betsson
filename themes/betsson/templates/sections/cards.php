<?php
    
    use Lib\Classes\GeneralHelper;

    $general = new GeneralHelper();
	$items = get_sub_field( 'items' );

    if( $title || $text || $cta || $items ) :
        ?>

        <section class="section latest-news <?php echo $sectionClass; ?>">
            <div class="container">

                <?php
                $sectionParts = array( 'title', 'text', 'button-before' ); 
                foreach( $sectionParts as $part ) { 
                    include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
                }
                ?> 

                <?php if( $items ) : ?>
                    <div class="row">
                        <?php 
                    	$hasBig = false;
                        foreach( $items as $key=>$item ) {
							$cardImageSize = '300x400';
                            $cardImageSizeBig = '600x400';
							$postItem = $item['post'];
							$postImageUrl = get_template_directory_uri() . '/dist/images/placeholders/'. $cardImageSizeBig .'.jpg';
                            $postImageUrlBig = get_template_directory_uri() . '/dist/images/placeholders/'. $cardImageSizeBig .'.jpg';
                            $postImageId = null;
							$postTitle = null;
							$postText = null;
							$postLinkURL = null;                            

							if( $postItem ) {
								if( has_post_thumbnail( $postItem ) ) {
									$postImageUrl = get_the_post_thumbnail_url( $postItem, $cardImageSize );
                                    $postImageUrlBig = get_the_post_thumbnail_url( $postItem, $cardImageSizeBig );
                                    $postImageId = get_post_thumbnail_id( $postItem );
								}

								$postTitle = $postItem->post_title;
								$postText = wp_trim_words( $postItem->post_content, 60, '' );
								$postLinkURL = get_permalink( $postItem );
							}                            

                            $cardImage = $item['image'] ? wp_get_attachment_image_url( $item['image'], $cardImageSize ) : $postImageUrl;
                            $cardImageBig = $item['image'] ? wp_get_attachment_image_url( $item['image'], $cardImageSizeBig ) : $postImageUrlBig;
                            $cardImageAlt = $item['image'] ? $general->get_image_alt( $item['image'] ) : $general->get_image_alt( $postImageId );
                            $cardTitle = $item['title'] ? $item['title'] : $postTitle;
                            $cardText = $item['text'] ? $item['text'] : $postText;
                            $cardLinkText = $item['link_text'];
                            $cardLink = $item['link_url'] ? $item['link_url'] : $postLinkURL;
                            $isExternalLink = $item['open_in_new_tab'];

                            if( count( $items ) < 3 ) {
                            	$isBig = $item['is_big'];
                            } elseif( $hasBig ) {
                            	$isBig = false;
                            } elseif( !$hasBig && ($key+1) === count( $items ) ) {
                            	$isBig = true;
                            } else {
                            	$isBig = $item['is_big'];
                            }

                            if( !$hasBig && $item['is_big'] ) {
	                        	$hasBig = true;
                            }

                            if( $isBig ) {
                                $cardImage = $cardImageBig;
                            }

                            include( locate_template( 'templates/includes/card.php' ) );
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

            </div>
        </section>

    <?php endif;