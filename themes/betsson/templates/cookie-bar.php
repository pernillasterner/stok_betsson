<?php
$content = get_field( 'cookie_content', 'general' );
$button = get_field( 'cookie_button', 'general' );
$cookieName = 'betsson_ga_cookie';

if ( ( $content || $button ) ) :
?>
    <div class="cookies-bar js-cookie" data-cookie="<?php echo $cookieName; ?>" style="display:none">
        <div class="container">
            <div class="cookies-wrapper">
                <div class="cookies-content">
                    <?php echo apply_filters( 'the_content', $content ); ?>
                </div>

                <?php if ( $button ) : ?>
                    <div class="cookies-content">
                        <button class="btn btn-primary js-cookie-approve"><?php echo $button; ?></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif;