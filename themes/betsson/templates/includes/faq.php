<div class="faq-item">
	<h6 class="faq-title">
		<?php echo get_the_title( $item ); ?>
		<span class="icon icon-arrow_down"></span>
	</h6>
	<div class="faq-content">
		<?php echo apply_filters( 'the_content', $item->post_content ); ?>
	</div>
</div>