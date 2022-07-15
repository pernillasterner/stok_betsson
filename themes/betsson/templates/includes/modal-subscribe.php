<?php
$shortcode = get_field( 'subscribe_form_shortcode' );
$message = get_field( 'subscribe_form_success_message' );

if( !$shortcode || !$message ) {
	return;
}
?>
<div id="subscribe-modal" class="subscribe-modal modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?= apply_filters( 'the_content', $shortcode );?>
				<div class="text-center js-modal-subscribe-success" style="display:none"><?= $message ?></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->