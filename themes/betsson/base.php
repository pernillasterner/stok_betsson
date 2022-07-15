<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
use Lib\Classes\GeneralHelper;

$hasHeroBanner = !is_single() ? true : false;
$bodyClass = $hasHeroBanner ? 'has-hero' : null;
$general = new GeneralHelper();

if( !$hasHeroBanner && $general->get_sub_navigation() ) {
	$bodyClass .= ' has-subnav';
};
?>

<!doctype html>
<html <?php language_attributes(); ?>>
	<?php get_template_part('templates/head'); ?>
	<body <?php body_class( $bodyClass ); ?>>
		<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TKRBBC"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<!--[if IE]>
		<div class="alert alert-warning ie-alert">
			<?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
		</div>
		<![endif]-->
		<?php if(!is_404()) :?>
		<div class="nav-mobile-toggle js-menu-toggle">
			<button class="nav-close js-toggle-menu">x</button>
			<ul></ul>
		</div>
		<div class="main-body">
			<?php
			do_action('get_header');
			get_template_part('templates/header');
			?>
			<div class="wrap" role="document">
				<main>

					<?php

					if( $hasHeroBanner && !is_singular( 'job' ) ) {
						get_template_part('templates/sections/banner');
					}

					include Wrapper\template_path();
					?>

				</main>
			</div>

			<?php
			do_action('get_footer');
			get_template_part('templates/footer');
			?>

		</div>
		<?php else: ?>
			<?php
				include Wrapper\template_path();
			?>
		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>
</html>
