<?php
$itemImageSize = '260x300';

$itemImage = get_term_meta( $item->term_id, 'image', true );

if( $itemImage ) {
	$itemImage = wp_get_attachment_image( $itemImage, $itemImageSize );
} elseif( $item->ID && has_post_thumbnail( $item ) ) {
	$itemImage = get_the_post_thumbnail( $item, $itemImageSize );
} else {
	$itemImageUrl = get_template_directory_uri() . '/dist/images/placeholders/'. $itemImageSize . ( !empty( $isDepartment ) ? null : '-user' );
	$itemImage = '<img src="'. $itemImageUrl .'.jpg" alt="'. get_bloginfo( 'name' ) .'">';
}

if( !empty( $isDepartment ) ) {
	$cardTitle = get_the_title( $item );
	$cardLink = get_permalink( $item );
} else {
	$cardTitle = str_replace( ' ', '<br>', $item->name );
	$cardLink = null;
}
?>

<div class="card <?php echo $cardClass; ?>">
	<?php if( $cardLink  ) : ?>
	<a href="<?php echo $cardLink ?>">
	<?php endif; ?>

		<?php if( isset( $isRecruiter ) && $isRecruiter ) : ?>
			<div class="modal-item" data-toggle="modal" data-target="#modal-item-<?php echo $itemCtr; ?>">
		<?php endif; ?>

		<figure><?php echo $itemImage; ?></figure>
		<h3 class="text-uppercase"><?php echo $cardTitle; ?></h3>

		<?php if( isset( $isRecruiter ) && $isRecruiter ) : ?>
			</div>
		<?php endif; ?>

	<?php if( $cardLink  ) : ?>
	</a>
	<?php endif; ?>
</div>
