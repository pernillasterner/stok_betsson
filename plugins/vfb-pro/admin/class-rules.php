<?php
/**
 * Class that controls the Rules view
 *
 * @since 3.0
 */
class VFB_Pro_Edit_Rules {

	/**
	 * The form ID
	 *
	 * @var mixed
	 * @access private
	 */
	private $id;

	/**
	 * Assign form ID when class is loaded
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function __construct( $id ) {
		$this->id = (int) $id;
	}

	/**
	 * display function.
	 *
	 * @access public
	 * @return void
	 */
	public function display() {
		// Double check permissions before display
		if ( !current_user_can( 'vfb_edit_forms' ) )
			return;

		$vfbdb = new VFB_Pro_Data();
		$data  = $vfbdb->get_rule_settings( $this->id );
		$fields = $vfbdb->get_fields( $this->id );

		$enable       = isset( $data['rules-enable'] ) ? $data['rules-enable'] : '';
		$enable_email = isset( $data['rules-email-enable'] ) ? $data['rules-email-enable'] : '';

		$rules       = isset( $data['rules'] ) ? $data['rules'] : '';
		$rules_email = isset( $data['rules-email'] ) ? $data['rules-email'] : '';

		$x = $y = 0;
	?>
	<form method="post" id="vfbp-rules-settings" action="">
		<input name="_vfbp_action" type="hidden" value="save-rules-settings" />
		<input name="_vfbp_form_id" type="hidden" value="<?php echo $this->id; ?>" />
		<?php
			wp_nonce_field( 'vfbp_rules_settings' );
		?>

		<div class="vfb-edit-section">
			<div class="vfb-edit-section-inside">
				<h3><?php _e( 'Field Rules', 'vfb-pro' ); ?></h3>
				<p><?php _e( 'Show or hide fields based on these rules.', 'vfb-pro' ); ?></p>

				<table class="form-table vfb-rules-container">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="enable"><?php _e( 'Enable Field Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<fieldset>
									<label>
										<input type="hidden" name="settings[rules-enable]" value="0" /> <!-- This sends an unchecked value to the meta table -->
										<input type="checkbox" name="settings[rules-enable]" id="enable" value="1"<?php checked( $enable, 1 ); ?> /> <?php _e( 'Enable Rules', 'vfb-pro' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>

						<?php
							if ( is_array( $rules ) && !empty( $rules ) ) :
								foreach ( $rules as $rule ) :
						?>
						<tr valign="top" class="vfb-rules" id="vfb-rule-<?php echo $x; ?>">
							<th scope="row">
								<label><?php _e( 'Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<table class="vfb-rules-table">
								<?php
									$this->rules( $rule, $fields, $x );

									echo '<tbody class="vfb-cloned-conditions">';

									$conditions = isset( $rule['conditions'] ) ? $rule['conditions'] : '';
									if ( is_array( $conditions ) && !empty( $conditions ) ) {
										foreach ( $conditions as $condition ) {
											$this->conditions( $condition, $fields, $x, $y );
											$y++;
										}
									}

									echo '</tbody> <!-- .vfb-cloned-conditions -->';

									$x++;
									$y = 0;
								?>
								</table> <!-- .vfb-rules-table -->
							</td>
							<td class="vfb-rules-actions">
								<a href="#" class="button vfb-rules-actions-delete">
									<i class="vfb-icon-minus-circle"></i>&nbsp;<?php _e( 'Delete Rule', 'vfb-pro' ); ?>
								</a>
							</td>
						</tr>
						<?php
								endforeach; // $rules
							else :
						?>
						<tr valign="top" class="vfb-rules" id="vfb-rule-<?php echo $x; ?>">
							<th scope="row">
								<label><?php _e( 'Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<table class="vfb-rules-table">
								<?php
								$this->rules_default( $fields );

								echo '<tbody class="vfb-cloned-conditions">';

								$this->conditions_default( $fields );

								echo '</tbody> <!-- .vfb-cloned-conditions -->';
								?>
								</table> <!-- .vfb-rules-table -->
							</td>
							<td class="vfb-rules-actions">
								<a href="#" class="button vfb-rules-actions-delete">
									<i class="vfb-icon-minus-circle"></i>&nbsp;<?php _e( 'Delete Rule', 'vfb-pro' ); ?>
								</a>
							</td>
						</tr>
						<?php
							endif; // if $rules
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" align="center">
								<a href="#" class="button vfb-rules-actions-add">
									<i class="vfb-icon-plus-circle"></i>&nbsp;<?php _e( 'Add Rule', 'vfb-pro' ); ?>
								</a>
							</td>
						</tr>
					</tfoot>
				</table>
			</div> <!-- .vfb-edit-section-inside -->
		</div> <!-- .vfb-edit-section -->

		<div class="vfb-edit-section">
			<div class="vfb-edit-section-inside">
				<h3><?php _e( 'Email Rules', 'vfb-pro' ); ?></h3>
				<p><?php _e( 'After the form is submitted, send additional emails to different addresses based on these rules.', 'vfb-pro' ); ?></p>

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="enable"><?php _e( 'Enable Email Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<fieldset>
									<label>
										<input type="hidden" name="settings[rules-email-enable]" value="0" /> <!-- This sends an unchecked value to the meta table -->
										<input type="checkbox" name="settings[rules-email-enable]" id="enable" value="1"<?php checked( $enable_email, 1 ); ?> /> <?php _e( 'Enable Email Rules', 'vfb-pro' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>

						<?php
							// Reset counters
							$x = $y = 0;

							if ( is_array( $rules_email ) && !empty( $rules_email ) ) :
								foreach ( $rules_email as $rule ) :
						?>
						<tr valign="top" class="vfb-rules" id="vfb-rule-email-<?php echo $x; ?>">
							<th scope="row">
								<label><?php _e( 'Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<table class="vfb-rules-table">
								<?php
									echo '<tbody class="vfb-cloned-conditions">';

									$conditions = isset( $rule['conditions'] ) ? $rule['conditions'] : '';
									if ( is_array( $conditions ) && !empty( $conditions ) ) {
										foreach ( $conditions as $condition ) {
											$this->conditions( $condition, $fields, $x, $y, 'rules-email' );
											$y++;
										}
									}

									echo '</tbody> <!-- .vfb-cloned-conditions -->';

									$x++;
									$y = 0;
								?>
								</table> <!-- .vfb-rules-table -->
							</td>
						</tr>
						<?php
								endforeach; // $rules
							else :
						?>
						<tr valign="top" class="vfb-rules" id="vfb-rule-email-<?php echo $x; ?>">
							<th scope="row">
								<label><?php _e( 'Rules' , 'vfb-pro'); ?></label>
							</th>
							<td>
								<table class="vfb-rules-table">
								<?php

								echo '<tbody class="vfb-cloned-conditions">';

								$this->conditions_default( $fields, 'rules-email' );

								echo '</tbody> <!-- .vfb-cloned-conditions -->';
								?>
								</table> <!-- .vfb-rules-table -->
							</td>
						</tr>
						<?php
							endif; // if $rules
						?>
					</tbody>
				</table>
			</div> <!-- .vfb-edit-section-inside -->
		</div> <!-- .vfb-edit-section -->

		<?php
			submit_button(
				__( 'Save Changes', 'vfb-pro' ),
				'primary',
				'' // leave blank so "name" attribute will not be added
			);
		?>
	</form>
	<?php
	}

	/**
	 * Rules setting display
	 *
	 * @access private
	 * @param mixed $setting	The rules
	 * @param mixed $fields		All form fields
	 * @param mixed $x			The rules array position
	 * @return void
	 */
	private function rules( $setting, $fields, $x ) {
		$type     = isset( $setting['type'] ) ? $setting['type'] : '';
		$field_id = isset( $setting['field-id'] ) ? $setting['field-id'] : '';
		$match    = isset( $setting['match-type'] ) ? $setting['match-type'] : '';
	?>
	<tr>
		<td colspan="4" class="vfb-cloned-rules">
			<select name="settings[rules][<?php echo $x;?>][type]" class="vfb-rules-select vfb-rules-select-type">
				<option value="show"<?php selected( 'show', $type ); ?>><?php _e( 'Show', 'vfb-pro' ); ?></option>
				<option value="hide"<?php selected( 'hide', $type ); ?>><?php _e( 'Hide', 'vfb-pro' ); ?></option>
			</select>
			<?php _e( 'the', 'vfb-pro' ); ?>

			<select name="settings[rules][<?php echo $x;?>][field-id]" class="vfb-rules-select vfb-rules-select-label">
				<?php foreach ( $fields as $field ) : ?>
				<option value="<?php echo $field['id']; ?>"<?php selected( $field['id'], $field_id ); ?>><?php echo $field['data']['label']; ?></option>
				<?php endforeach; ?>
			</select>
			<?php _e( 'field based on', 'vfb-pro' ); ?>

			<select name="settings[rules][<?php echo $x;?>][match-type]" class="vfb-rules-select vfb-rules-select-match">
				<option value="any"<?php selected( 'any', $match ); ?>><?php _e( 'any', 'vfb-pro' ); ?></option>
				<option value="all"<?php selected( 'all', $match ); ?>><?php _e( 'all', 'vfb-pro' ); ?></option>
			</select>
			<?php _e( 'of the following rules.', 'vfb-pro' ); ?>
		</td>
	</tr>
	<?php
	}

	/**
	 * Display rules row when no rules have been saved yet
	 *
	 * @access private
	 * @param mixed $fields		All form fields
	 * @return void
	 */
	private function rules_default( $fields ) {
	?>
	<tr>
		<td colspan="4">
			<select name="settings[rules][0][type]" class="vfb-rules-select vfb-rules-select-type">
				<option value="show"><?php _e( 'Show', 'vfb-pro' ); ?></option>
				<option value="hide"><?php _e( 'Hide', 'vfb-pro' ); ?></option>
			</select>
			<?php _e( 'the', 'vfb-pro' ); ?>

			<select name="settings[rules][0][field-id]" class="vfb-rules-select vfb-rules-select-label">
				<?php foreach ( $fields as $field ) : ?>
				<option value="<?php echo $field['id']; ?>"><?php echo $field['data']['label']; ?></option>
				<?php endforeach; ?>
			</select>
			<?php _e( 'field based on', 'vfb-pro' ); ?>

			<select name="settings[rules][0][match-type]" class="vfb-rules-select vfb-rules-select-match">
				<option value="any"><?php _e( 'any', 'vfb-pro' ); ?></option>
				<option value="all"><?php _e( 'all', 'vfb-pro' ); ?></option>
			</select>
			<?php _e( 'of the following rules.', 'vfb-pro' ); ?>
		</td>
	</tr>
	<?php
	}

	/**
	 * conditions function.
	 *
	 * @access private
	 * @param mixed $setting	The conditions
	 * @param mixed $fields		All form fields
	 * @param mixed $x			The rules array position
	 * @param mixed $y			The rules[conditions] array position
	 * @return void
	 */
	private function conditions( $setting, $fields, $x, $y, $type = 'rules' ) {
		$field_id   = isset( $setting['field-id'] ) ? $setting['field-id'] : '';
		$filter     = isset( $setting['filter'] ) ? $setting['filter'] : '';
		$value      = isset( $setting['value'] ) ? htmlspecialchars( str_replace( '&amp;', '&', $setting['value'] ) ) : '';
		$email      = isset( $setting['email'] ) ? $setting['email'] : '';

		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$test_from_email = 'wordpress@' . $sitename;
	?>
	<tr id="vfb-condition-<?php echo "{$type}-{$x}-{$y}"; ?>" class="vfb-condition">
		<td>
			<?php _e( 'If', 'vfb-pro' ); ?>
			<select name="settings[<?php echo $type; ?>][<?php echo $x;?>][conditions][<?php echo $y;?>][field-id]" class="vfb-rules-select vfb-rules-select-label">
				<?php foreach ( $fields as $field ) : ?>
				<option value="<?php echo $field['id']; ?>"<?php selected( $field_id, $field['id'] ); ?>><?php echo $field['data']['label']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>

		<td>
			<select name="settings[<?php echo $type; ?>][<?php echo $x;?>][conditions][<?php echo $y;?>][filter]" class="vfb-rules-select vfb-rules-select-condition">
				<optgroup label="<?php _e( 'Keyword match', 'vfb-pro' ); ?>">
					<option value="is"<?php selected( $filter, 'is' ); ?>><?php _e( 'is', 'vfb-pro' ); ?></option>
					<option value="is not"<?php selected( $filter, 'is not' ); ?>><?php _e( 'is not', 'vfb-pro' ); ?></option>
					<option value="contains"<?php selected( $filter, 'contains' ); ?>><?php _e( 'contains', 'vfb-pro' ); ?></option>
					<option value="does not contain"<?php selected( $filter, 'does not contain' ); ?>><?php _e( 'does not contain', 'vfb-pro' ); ?></option>
					<option value="begins with"<?php selected( $filter, 'begins with' ); ?>><?php _e( 'begins with', 'vfb-pro' ); ?></option>
					<option value="ends with"<?php selected( $filter, 'ends with' ); ?>><?php _e( 'ends with', 'vfb-pro' ); ?></option>
				</optgroup>
				<optgroup label="<?php _e( 'Number match', 'vfb-pro' ); ?>">
					<option value="is equal to"<?php selected( $filter, 'is equal to' ); ?>><?php _e( 'is equal to', 'vfb-pro' ); ?></option>
					<option value="is not equal to"<?php selected( $filter, 'is not equal to' ); ?>><?php _e( 'is not equal to', 'vfb-pro' ); ?></option>
					<option value="is greater than"<?php selected( $filter, 'is greater than' ); ?>><?php _e( 'is greater than', 'vfb-pro' ); ?></option>
					<option value="is less than"<?php selected( $filter, 'is less than' ); ?>><?php _e( 'is less than', 'vfb-pro' ); ?></option>
				</optgroup>
			</select>
		</td>

		<td>
			<input type="text" name="settings[<?php echo $type; ?>][<?php echo $x;?>][conditions][<?php echo $y;?>][value]" class="regular-text vfb-rule-<?php echo 'rules-email' == $type ? 'email-value' : 'field-value'; ?>" value="<?php esc_html_e( $value );  ?>" />
			<?php if ( 'rules-email' == $type ) : ?>
				<?php _e( 'email', 'vfb-pro' ); ?>
				<input type="email" name="settings[<?php echo $type; ?>][<?php echo $x;?>][conditions][<?php echo $y;?>][email]" class="regular-text vfb-rule-email-email" value="<?php esc_html_e( $email ); ?>" placeholder="<?php esc_attr_e( $test_from_email ); ?>" />
			<?php endif; ?>
		</td>

		<td>
			<span class="vfb-add-condition" title="<?php esc_attr_e( 'Add Condition', 'vfb-pro' ); ?>">
				<i class="vfb-icon-plus-circle"></i>&nbsp;
			</span>

			<span class="vfb-delete-condition" title="<?php esc_attr_e( 'Delete Condition', 'vfb-pro' ); ?>">
				<i class="vfb-icon-minus-circle"></i>
			</span>
		</td>
	</tr>
	<?php
	}

	/**
	 * Display conditions row when no conditions have been saved yet
	 *
	 * @access private
	 * @param mixed $fields		All form fields
	 * @return void
	 */
	private function conditions_default( $fields, $type = 'rules' ) {
	?>
	<tr id="vfb-condition-<?php echo $type; ?>-0-0" class="vfb-condition">
		<td>
			<?php _e( 'If', 'vfb-pro' ); ?>
			<select name="settings[<?php echo $type; ?>][0][conditions][0][field-id]" class="vfb-rules-select vfb-rules-select-label">
				<?php foreach ( $fields as $field ) : ?>
				<option value="<?php echo $field['id']; ?>"><?php echo $field['data']['label']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>

		<td>
			<select name="settings[<?php echo $type; ?>][0][conditions][0][filter]" class="vfb-rules-select vfb-rules-select-condition">
				<optgroup label="<?php _e( 'Keyword match', 'vfb-pro' ); ?>">
					<option value="is"><?php _e( 'is', 'vfb-pro' ); ?></option>
					<option value="is not"><?php _e( 'is not', 'vfb-pro' ); ?></option>
					<option value="contains"><?php _e( 'contains', 'vfb-pro' ); ?></option>
					<option value="does not contain"><?php _e( 'does not contain', 'vfb-pro' ); ?></option>
					<option value="begins with"><?php _e( 'begins with', 'vfb-pro' ); ?></option>
					<option value="ends with"><?php _e( 'ends with', 'vfb-pro' ); ?></option>
				</optgroup>
				<optgroup label="<?php _e( 'Number match', 'vfb-pro' ); ?>">
					<option value="is equal to"><?php _e( 'is equal to', 'vfb-pro' ); ?></option>
					<option value="is not equal to"><?php _e( 'is not equal to', 'vfb-pro' ); ?></option>
					<option value="is greater than"><?php _e( 'is greater than', 'vfb-pro' ); ?></option>
					<option value="is less than"><?php _e( 'is less than', 'vfb-pro' ); ?></option>
				</optgroup>
			</select>
		</td>

		<td>
			<input type="text" name="settings[<?php echo $type; ?>][0][conditions][0][value]" class="regular-text<?php echo 'rules-email' == $type ? ' vfb-rule-email-value' : ''; ?>" value="" />
			<?php if ( 'rules-email' == $type ) : ?>
				<?php _e( 'email', 'vfb-pro' ); ?>
				<input type="text" name="settings[<?php echo $type; ?>][0][conditions][0][email]" class="regular-text<?php echo 'rules-email' == $type ? ' vfb-rule-email-email' : ''; ?>" value="" />
			<?php endif; ?>
		</td>

		<td>
			<span class="vfb-add-condition" title="<?php esc_attr_e( 'Add Condition', 'vfb-pro' ); ?>">
				<i class="vfb-icon-plus-circle"></i>&nbsp;
			</span>

			<span class="vfb-delete-condition" title="<?php esc_attr_e( 'Delete Condition', 'vfb-pro' ); ?>">
				<i class="vfb-icon-minus-circle"></i>
			</span>
		</td>
	</tr>
	<?php
	}
}
