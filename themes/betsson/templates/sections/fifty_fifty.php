<?php
$title = get_sub_field( 'title' );
$text = apply_filters( 'the_content', get_sub_field( 'text' ) );
$cta = get_sub_field( 'cta' );
$ctaStyle = get_sub_field( 'cta_style' );
$ctaColor = get_sub_field( 'cta_color' );
$ctaClass = '';
$mediaType = get_sub_field( 'media_type' );
$layout = get_sub_field( 'layout' );

$backgroundClass = '';
switch( get_sub_field( 'background_color' ) ) {
	case 'blue': $backgroundClass = 'is-primary'; break;
	case 'orange': $backgroundClass = 'is-secondary'; break;
}
?>

<section class="section fifty-fifty <?= $backgroundClass.' '.$sectionClass ?>">
	<div class="container">
		<header class="section-title text-uppercase visible-xs">
			<div class="h2"><?= $title ?></div>
			<h2 class="h4 dark-title"><?= $title ?></h2>
		</header>
		<div class="row">
			<?php if( $layout === 'image-right' ): ?>
				<aside class="col-xs-12 col-sm-6 col-sm-push-6">
					<?php include( locate_template( 'templates/includes/fifty-fifty-media.php' ) ); ?>
				</aside>
				<aside class="col-xs-12 col-sm-6 col-sm-pull-6 text-sm-centered">
					<?php include( locate_template( 'templates/includes/fifty-fifty-text.php' ) ); ?>
				</aside>
			<?php else: ?>
				<aside class="col-xs-12 col-sm-6 col-sm-push-6 text-sm-centered">
					<?php include( locate_template( 'templates/includes/fifty-fifty-text.php' ) ); ?>
				</aside>
				<aside class="col-xs-12 col-sm-6 col-sm-pull-6">
					<?php include( locate_template( 'templates/includes/fifty-fifty-media.php' ) ); ?>
				</aside>				
			<?php endif; ?>
		</div>
	</div>
</section>