<?php if( empty( $isSlider ) ) : ?>
<div class="<?php echo $gridItemClass; ?>">
<?php endif; ?>

	<div class="office">

		<?php if( !empty( $info['image'] ) ) : ?>
			<figure><?php echo wp_get_attachment_image( $info['image'], 'medium' ); ?></figure>
		<?php endif; ?>

		<h3 class="h4 text-uppercase"><?php echo $info['title']; ?></h3>
		<div class="info">
			<p>
				<a class="info-address" href="https://maps.google.co.uk/maps?q=<?php echo $info['address']; ?>" target="_blank" rel="noreferrer noopener"><?php echo $info['address']; ?></a><br />
				<a href="tel:<?= str_replace( ' ', '', $info['telephone'] ) ?>"><?= $info['telephone'] ?></a>
				<a href="mailto:<?php echo $info['email']; ?>"><?php echo $info['email']; ?></a>
			</p>
		</div>
	</div>

<?php if( empty( $isSlider ) ) : ?>
</div>
<?php endif; ?>
