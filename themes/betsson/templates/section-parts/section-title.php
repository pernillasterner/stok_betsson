<?php 

if( $title ) : 
	$titleClass = $disableTitleShadow ? 'no-title-shadow' : null;
	?>

	<header class="section-title text-uppercase">
		<h2 class="h4 dark-title <?php echo $titleClass ?>"><?php echo $title; ?></h2>

		<?php if( !$disableTitleShadow ) : ?>
		<div class="h2"><?php echo isset( $titleShadow ) && $titleShadow ? $titleShadow : $title; ?></div>
		<?php endif;?>
	</header>
	
<?php endif;