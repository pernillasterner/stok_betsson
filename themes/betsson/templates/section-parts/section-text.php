<?php if( $text ) : ?>

	<div class="content is-medium text-center lead <?php echo !empty( $isAllCaps ) ? 'text-uppercase' : null; ?>">
		<?php echo $text; ?>
	</div>
	
<?php endif;