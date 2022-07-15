<style>
.loader {
    border: 2px solid #f3f3f3; /* Light grey */
    border-top: 2px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 10px;
    height: 10px;
    animation: spin 1s linear infinite;
}

#loader {
	display: inline-block;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<div class="wrap">
	<h1><?php echo SGP_MENU_TITLE; ?></h1>
	
	<form id="SGPForm" name="SGPForm" method="post" action="options.php">
		<?php settings_fields( 'sgp_settings_group' ); ?>
		
		<table class="form-table">
			<tbody>
				<?php foreach( $fields as $field ): ?>
					<tr valign="top">
						<th scope="row">
							<label for="sgp_settings[<?php echo $field['name']; ?>]"><strong><?php _e( $field['label'], SGP_PLUGIN_NAME ); ?></strong></label>
						</th>
						
						<td>
							<input type="<?php echo $field['type']; ?>" id="sgp_settings[<?php echo $field['name']; ?>]" name="sgp_settings[<?php echo $field['name']; ?>]" value="<?php echo ( isset( $settings[ $field['name'] ] ) ) ? $settings[ $field['name'] ] : ''; ?>" class="regular-text">
						</td>
					</tr>
				<?php endforeach; ?>
				<tr valign="top" <?= isset( $log ) ? '' : 'style="display:none"' ?>>
					<th scope="row">
						<label><strong><?php _e( 'Latest Log', SGP_PLUGIN_NAME ); ?></strong></label>
					</th>
					
					<td>
						<pre id="log"><?= $log ?></pre>
					</td>
				</tr>
			</tbody>
		</table>

		<p>
			<input class="button-primary" type="submit" name="sgp_settings_submit" value="<?php _e( 'Save Changes', SGP_PLUGIN_NAME ); ?>" />

			<?php if( $settings['api_key'] && $settings['board_token'] ): ?>
				<button id="btn-import" class="button-secondary" type="button">Import Jobs</button>				
			<?php endif; ?>
		</p>
	</form>
	
	<div id="loader" style="display:none">
		<div class="loader"></div>
	</div>	
	<span id="msg-importing" style="display:none"></span>
</div>