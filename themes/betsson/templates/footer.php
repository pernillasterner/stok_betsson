<?php

$optionId = 'general';
$logo = get_field( 'logo', $optionId );
$footerLinksDesktop = get_field( 'footer_column_links_desktop', $optionId );
$footerLinksMobile = get_field( 'footer_column_links_mobile', $optionId );
$footerInstagram = get_field( 'footer_column_instagram', $optionId );
$footerInfo = get_field( 'footer_column_info', $optionId );
$footerTermAndPolicyLinks = get_field( 'footer_terms_policy_links', $optionId );
$socialMediaLinks = get_field( 'social_media_links', $optionId );
?>

<footer>
    <div class="container">

        <?php if( $logo ) : ?>
            <div class="footer-logo">
                <a href="<?php echo home_url(); ?>">
                    <img src="<?php echo wp_get_attachment_image_url( $logo, 'medium'); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"/>
                </a>
            </div>
        <?php endif; ?>

        <div class="footer-columns">
            <div class="row">

                <?php if( $footerLinksMobile ) : ?>
                    <div class="col menu mobile">
                        <ul class="small">
                            <?php
                            foreach( $footerLinksMobile as $link ) :
                                if( !$link ) { continue; } ?>
                                <li class="strong"><a href="<?php echo get_the_permalink( $link['link'] ); ?>"><?php echo get_the_title( $link['link'] ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php
                if( $footerLinksDesktop ) :
                    foreach( $footerLinksDesktop as $key=>$column ) :
                        $isLastColumn = ($key+1) === count($footerLinksDesktop);
                        $links = $column['column'];
                        ?>
                        <div class="col menu <?php echo  $isLastColumn ? 'last' : null; ?>">
                            <?php if( $links ) : ?>
                                <ul class="small">
                                    <?php
                                    foreach( $links as $link ) :
                                        if( !$link['link'] ) { continue; }
                                        ?>
                                        <li<?php echo $link['highlight_page'] ? ' class="strong"' : null; ?>>
                                            <a href="<?php echo get_the_permalink( $link['link'] ) ?>"><?php echo get_the_title( $link['link'] ); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- <?php
                if( $footerInstagram ) :
                    $instaTitle = $footerInstagram['title'];
                    $instaText = $footerInstagram['text'];
                    $instaLink = $footerInstagram['instagram_link'];
                    $instaRecentPost = wp_get_recent_posts( array( 'post_type' => 'instagram', 'numberposts' => '1' ) );
                    ?>
                    <div class="col instagram">

                        <?php if( $instaTitle ) : ?>
                            <small><?php echo $instaTitle; ?></small>
                        <?php endif; ?>

                        <?php if( $instaRecentPost ) : ?>
                            <a <?php echo $instaLink ? 'href="'. $instaLink .'" target="_blank" rel="noreferrer noopener" class="center-image"' : null; ?>>
                                <img src="<?php echo get_the_post_thumbnail_url( $instaRecentPost[0]['ID'], 'medium' ); ?>" class="img-responsive"/>
                            </a>
                        <?php endif; ?>

                        <?php if( $instaText ) : ?>
                            <small class="hashtag"><?php echo $instaText; ?></small>
                        <?php endif; ?>

                    </div>
                <?php endif; ?> -->

                <?php
                if( $footerInfo ) :
                    $infoCta = $footerInfo['cta'];
                    $infoText = $footerInfo['text'];
                    $infoIsoLogo = $footerInfo['iso_logo'];
                    $infoIsoCta = $footerInfo['iso_cta'];
                    ?>
                    <div class="col parent-brand">

                        <?php if( $infoCta ) : ?>
                            <a href="<?php echo $infoCta['url']; ?>"
                                class="btn btn-default btn-sm"
                                <?php echo $infoCta['target'] ? 'target="_blank" rel="noreferrer noopener"' : null; ?>>
                                <?php echo $infoCta['title']; ?>
                            </a>
                        <?php endif; ?>

                        <?php if( $infoText ) : ?>
                            <small><?php echo $infoText; ?></small>
                        <?php endif; ?>

                        <a class="iso" href="<?php echo $infoIsoCta['url']; ?>"
                            <?php echo $infoIsoCta['target'] ? 'target="_blank" rel="noreferrer noopener"' : null; ?>>
                            <img src="<?php echo wp_get_attachment_image_url( $infoIsoLogo, 'full'); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"/>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <?php
        $smContainerClass = 'footer-social';
        include( locate_template( 'templates/includes/social-media.php' ) );
        ?>

        <?php if( $footerTermAndPolicyLinks ) : ?>
            <div class="footer-policy">
                <ul>
                    <?php foreach( $footerTermAndPolicyLinks as $item ) : ?>
                        <li><a href="<?php echo get_the_permalink( $item['link'] ); ?>"><?php echo get_the_title( $item['link'] ); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="footer-copyright">
            <small>2018 Betssongroup</small>
        </div>
    </div>
    <div class="powered">Created by <a href="https://www.stok.se" target="_blank" rel="noreferrer">STÖK</a></div>
</footer>
<?php

get_template_part( 'templates/cookie-bar' );
echo get_field( 'scripts_footer', 'general' );