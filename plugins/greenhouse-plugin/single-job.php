<?php get_header(); ?>

<div class="wrap">	
	<?php
	/* Start the Loop */
	while ( have_posts() ) : the_post();
		$data = get_post_meta( $post->ID, 'gh_job_data', true );
		$data = unserialize( $data );		
	?>
		<h1><?= the_title() ?></h1>
		<?= the_content() ?>
		<p>
			<strong>Title:</strong>
			<span><?= $data->title ?></span>
		</p>
		<?php
			foreach( $data->metadata as $item ):
				$value = $item->value;

				if( is_array( $value ) ) {
					$value = $value[0];
				} elseif ( is_object( $value ) ) {
					$value = $value->name . ' ' . $value->email;
				}
		?>
			<p>
				<strong><?= str_replace( ':', '', $item->name ) ?>:</strong>
				<span><?= $value ?></span>
			</p>
		<?php endforeach; ?>
	<?php
	endwhile; // End of the loop.
	?>
</div><!-- .wrap -->

<?php get_footer();