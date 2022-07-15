<?php

    $subheadline = get_sub_field( 'subheadline' );
    $title = get_sub_field( 'title' );
    $text = get_sub_field( 'text' );
    $cta = get_sub_field( 'cta' );
    $ctaStyle = get_sub_field( 'cta_style' );
    $ctaColor = get_sub_field( 'cta_color' );
    $hasArrow = get_sub_field( 'show_arrow?' );
    $image = get_sub_field( 'image' );
    $layoutClass = get_sub_field( 'layout' ) === 'image-right' ? 'is-right' : 'is-left';

    if( $hasArrow && empty($isLastSection) ) {
        $sectionClass .= ' has-arrow';
    }

    if( $subheadline || $title || $text || $cta || $image ) :
        ?>
        <section class="section black-frame <?php echo $sectionClass; ?>">
            <div class="container">

                <div class="black-frame-container <?php echo $layoutClass; ?>">
                    <div class="box">
                        <?php if( $subheadline ) : ?>
                        <div class="text small"><?php echo $subheadline; ?></div>
                        <?php endif; ?>

                        <?php if( $title ) : ?>
                        <h3>
							<?php if( $cta['url'] ) : ?>
							<a href="<?php echo $cta['url']; ?>"><?php echo $title; ?></a>
							<?php else: ?>
							<?php echo $title; ?>
							<?php endif; ?>
						</h3>
                        <?php endif; ?>

                        <div class="lead">
                            <?php echo apply_filters( 'the_content', $text ); ?>
                            <?php
                            $ctaClass = 'button-bottom hidden-sm hidden-xs';
                            include( locate_template( 'templates/includes/cta.php' ) );
                            ?>
                        </div>
                    </div>

                    <?php if( $image ) : ?>
						<?php if( $cta['url'] ) : ?>
                        <a href="<?php echo $cta['url']; ?>">
                            <div class="image">
                                <figure class="center-image">
                                    <?php echo wp_get_attachment_image( $image, '600x400' ); ?>
                                </figure>
                            </div>
                        </a>
                        <?php else: ?>
                        <div class="image">
                            <figure class="center-image">
                                <?php echo wp_get_attachment_image( $image, '600x400' ); ?>
                            </figure>
                        </div>
						<?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php
                $ctaClass = 'text-center button-bottom visible-sm visible-xs';
                include( locate_template( 'templates/includes/cta.php' ) );
                ?>

            </div>
        </section>

    <?php endif;