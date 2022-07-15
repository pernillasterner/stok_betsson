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
<div class="modal fade is-primary" id="modal-item-<?php echo $itemCtr; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center" role="document">
		<div class="modal-content">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<figure><?php echo $itemImage; ?></figure>
			<div class="modal-inner-wrapper">
				<h3 class="text-uppercase"><?php echo $cardTitle; ?></h3>
				<div class="modal-text">
					<?php echo apply_filters( 'the_content', $item->description ); ?>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>

