<h2 class="h4 hidden-xs text-uppercase">
    <?php if( $cta['url'] ) : ?>
    <a href="<?php echo $cta['url']; ?>"><?php echo $title; ?></a>
    <?php else: ?>
    <?php echo $title; ?>
    <?php endif; ?>
</h2>
<div class="col-content"><?= $text ?></div>			
<?php include( locate_template( 'templates/includes/cta.php' ) ) ?>