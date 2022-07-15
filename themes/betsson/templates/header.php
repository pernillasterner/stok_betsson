<?php

use Lib\Classes\GeneralHelper;

$helper = new GeneralHelper();
$hasSubNav = $helper->get_sub_navigation();

$hasHeroBanner = !is_single() ? true : false;
$logo = get_field( 'logo', 'general' );
$navBgImage = get_field( 'default_nav_background_image', 'default-images' );
$headerClass = '';

if( $hasHeroBanner ) {
	$headerClass .= 'js-hero has-hero';
}

if( $hasSubNav ) {
	$headerClass .= ' has-sub-nav';
}
?>

<header class="banner <?php echo $headerClass; ?>">
	<div class="banner-wrap">
		<div class="bg" style="background-image: url('<?php echo $navBgImage ? wp_get_attachment_image_url( $navBgImage, '1920x480' ) : null; ?>');"></div>
		<div class="overlay"></div>
		<div class="container">

			<nav class="nav-primary hidden-sm hidden-xs">
				<?php
				if (has_nav_menu('primary_navigation')) {
					wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
				}
				?>
			</nav>

			<a class="brand" href="<?php echo esc_url( home_url() ); ?>">
				<?php if( $logo ) : ?>
				<img class="img-responsive" src="<?php echo wp_get_attachment_image_url( $logo, 'medium'); ?>" alt=" <?php bloginfo('name'); ?>">
				<?php endif; ?>
			</a>

			<nav class="nav-secondary hidden-sm hidden-xs">
				<?php
				if (has_nav_menu('secondary_navigation')) {
					wp_nav_menu(['theme_location' => 'secondary_navigation', 'menu_class' => 'nav']);
				}
				?>
			</nav>
			<div class="mobile-nav visible-xs visible-sm">
				<button type="button" class="navbar-toggle js-toggle-menu" aria-expanded="false">
					<div class="icon-menuicon_fill"></div>
				</button>
			</div>
			<div class="nav-mobile-toggle js-menu-toggle"></div>
		</div>
	</div>
	
	<?php 
	$subnavClass = $hasHeroBanner ? 'submenu-nav hidden' : 'submenu-nav';
	include( locate_template( 'templates/includes/sub-nav.php' ) );
	?>

</header>