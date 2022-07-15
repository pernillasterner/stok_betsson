<?php

    $cardLinkAttr = null;
    if( $cardLink ) {
        $cardLinkAttr .= 'href="'.$cardLink.'"';
    }

    if( $isExternalLink ) {
        $cardLinkAttr .= ' target="_blank" rel="nofollow noreferrer"';
    }
    ?>

    <div class="col <?php echo isset( $isBig ) && $isBig ? 'is-landscape' : null; ?>">
        <a class="article" <?php echo $cardLinkAttr; ?>>
            <figure class="center-image">
                <img src="<?php echo $cardImage; ?>"
                    data-src-portrait="<?php echo !empty( $cardImage ) ? $cardImage : null; ?>"
                    data-src-landscape="<?php echo !empty( $cardImageBig ) ? $cardImageBig : null; ?>" 
                    alt="<?php echo $cardImageAlt; ?>"/>
            </figure>       
            
            <?php if( $cardTitle ) : ?>
            <h3 class="h6"><?php echo $cardTitle; ?></h3>
            <?php endif; ?>

            <?php 
            if( !empty( $cardDetails ) ) {
                echo '<ul>';
                foreach( $cardDetails as $list ) {
                    echo '<li>'. $list .'</li>';
                }
                echo '</ul>';
            }
            ?> 

            <?php if( $cardText ) : ?>
            <div class="ellipsis small">
                <p><?php echo $cardText; ?></p>
            </div>
            <?php endif; ?>

            <?php if( $cardLinkText ) : ?>
            <div class="btn-underlined">
                <?php echo $cardLinkText; ?>
            </div>
            <?php endif; ?>
        </a>
    </div>