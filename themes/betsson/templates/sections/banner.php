<?php

use Lib\Classes\VideoHelper;

$heroBanner = get_field( 'hero_banner' );

if( $heroBanner ) :
    $bannerSize = $heroBanner['banner_size'];
    $title = $heroBanner['title'] ? $heroBanner['title'] : get_the_title();
    $text = $heroBanner['text'];
    $allCaps = $heroBanner['all_caps?'];
    $videoURL = $heroBanner['video_url'];    
    $image = $heroBanner['image'];
    $mobileImage = $heroBanner['mobile_image'];
    $hasSearchForm = $heroBanner['show_search_form?'];
    $scrollToContent = $heroBanner['show_scroll_to_content?'];

    $backgroundDesktopUrl = '';
    $backgroundMobileUrl = '';
    $sectionClass = 'is-'. $bannerSize;

    // Background Image
    if( $image ) {
        $backgroundDesktopUrl = wp_get_attachment_image_url( $image, 'banner-desktop' );
        $backgroundMobileUrl = wp_get_attachment_image_url( $image, 'banner-mobile' );
    }

    // Background Image Mobile
    if( $mobileImage ) {
        $backgroundMobileUrl = wp_get_attachment_image_url( $mobileImage, 'banner-mobile' );
    }

    // Search form
    if( $hasSearchForm ) {
        $sectionClass .= ' has-search';
    }

    // Search form
    if( $text ) {
        $sectionClass .= ' has-text';
    }     
    ?>

    <section
        class="section hero-banner has-shadow-big js-image-switch has-submenu <?php echo $sectionClass; ?>"
        style="background-image:url( <?php echo $backgroundDesktopUrl; ?> );"
        data-desktopBackground="<?php echo $backgroundDesktopUrl; ?>"
        data-mobileBackground="<?php echo $backgroundMobileUrl; ?>">

        <?php 
        if( $videoURL ) {
            $tag = VideoHelper::getVideoTag( $videoURL, true, false, true, true );
            echo '<div class="video-body">'.$tag.'</div>';
        }
        ?>

        <div class="hero-body <?php echo ($text) ? 'has-text' : null; ?>">

            <?php if( $title ) : ?>
            <h1 class="h1"><?php echo $title; ?></h1>
            <?php endif; ?>

            <div class="container">
                <?php if( $text ) : ?>
                <div class="text medium <?php echo $allCaps ? 'text-uppercase' : null; ?>"><?php echo $text; ?></div>
                <?php endif; ?>

                <?php
                if( $hasSearchForm ) {
                    include( locate_template( 'templates/includes/job-search-form.php' ) );
				}
				
				unset( $hasSearchForm );
                ?>
    		</div>
    	</div>

        <?php
        $smContainerClass = 'social-media';
        $iconColor = '';
        include( locate_template( 'templates/includes/share-the-page.php' ) );
        ?>

        <?php if( $scrollToContent ) : ?>
    	<div class="next-section">
    		<button class="icon-arrow_scroll has-circle is-primary js-scrollTo-next-section"></button>
        </div>
        <?php endif; ?>

        <div class="sub-menu js-submenu-banner">
            <?php
            $subnavClass = 'submenu-nav';
            include( locate_template( 'templates/includes/sub-nav.php' ) );
            ?>
        </div>
    </section>

<?php endif;
