<?php

$brands = get_field( 'brands', 'brands' );

if( $title || $text || $cta || $brands ) :
    ?>

    <section class="section brands <?php echo $sectionClass; ?>">
        <div class="container">

            <?php
            $sectionParts = array( 'title', 'text', 'button-before' ); 
            foreach( $sectionParts as $part ) { 
                include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
            }
            ?>              

            <?php if( $brands ) : ?>
            <div class="brand-list">
                <ul>
                    <?php foreach( $brands as $brand ) : ?>
                        <?php
                        if( !$brand['logo'] ) { continue; } 

                        $imageSize = strpos( wp_get_attachment_image_url( $brand['logo'] ), '.svg' ) !== false ? null : '160x60';
                        ?>
                        <li>
                            <a <?php echo $brand['url'] ? 'href="'.$brand['url'].'" target="_blank" rel="noreferrer noopener"' : null; ?>>
                                <figure><?php echo wp_get_attachment_image( $brand['logo'], $imageSize ); ?></figure>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>          

        </div>
    </section>

<?php endif; 