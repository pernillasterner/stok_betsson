<?php
// Register shortcode
add_shortcode( 'button', function( $attr, $content = '' ) {

	$attr = wp_parse_args( $attr, array(
		'text' => '',
		'link' => ''
	) );

	if( !$attr['text'] || !$attr['link'] ){
		return;
	}

	ob_start();
	?>		

	<p class="text-center">
		<a href="<?= $attr['link']; ?>" class="btn btn-secondary" rel="nofollow noreferrer">
			<?= $attr['text'] ?>
		</a>
	</p>

	<?php

	return ob_get_clean();
} );

// Register Shortcake UI
shortcode_ui_register_for_shortcode(
	'button',
	array(

		// Display label. String. Required.
		'label' => 'Button',

		// Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
		// 'listItemImage' => 'dashicons-editor-quote',

		// Available shortcode attributes and default values. Required. Array.
		// Attribute model expects 'attr', 'type' and 'label'
		// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
		'attrs' => array(			
			array(
				'label'       => 'Text',
				'attr'        => 'text',
				'type'        => 'text'
			),
			array(
				'label'       => 'Link',
				'attr'        => 'link',
				'type'        => 'url'
			)
		)
	)
);
