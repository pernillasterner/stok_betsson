<?php

/**
 * Class that controls the Help page view
 *
 * @since      3.0
 */
class VFB_Pro_Page_Help {
	/**
	 * The update API
	 *
	 *
	 * @var string
	 * @access protected
	 */
	protected $docs_url = 'http://api.vfbpro.com/docs-search';

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

		// Get the current selected tab
		$current_tab = $this->get_current_tab();

		$help_url        = esc_url( add_query_arg( array( 'vfb-tab' => 'help' ), admin_url( 'admin.php?page=vfbp-help' ) ) );
		$diagnostics_url = esc_url( add_query_arg( array( 'vfb-tab' => 'diagnostics' ), admin_url( 'admin.php?page=vfbp-help' ) ) );
		$addons_url      = esc_url( add_query_arg( array( 'vfb-tab' => 'addons-list' ), admin_url( 'admin.php?page=vfbp-help' ) ) );
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo $help_url; ?>" class="nav-tab<?php echo 'help' == $current_tab ? ' nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Documentation', 'vfb-pro' ); ?>
			</a>
			<a href="<?php echo $diagnostics_url; ?>" class="nav-tab<?php echo 'diagnostics' == $current_tab ? ' nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Diagnostics', 'vfb-pro' ); ?>
			</a>
			<a href="<?php echo $addons_url; ?>" class="nav-tab<?php echo 'addons-list' == $current_tab ? ' nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Available Add-Ons', 'vfb-pro' ); ?>
			</a>
		</h2>

		<?php
			// Display current tab content
			switch( $current_tab ) :
				case 'help' :
					$this->docs();
					break;
				case 'diagnostics' :
					$this->diagnostics();
					break;
				case 'addons-list' :
					$this->addons_list();
					break;
			endswitch;
		?>
	</div> <!-- .wrap -->
	<?php
	}

	/**
	 * Display the Documentation tab
	 * @return [type] [description]
	 */
	public function docs() {
		$keyword = isset( $_POST['vfbp-docs-search-keyword'] ) ? sanitize_text_field( $_POST['vfbp-docs-search-keyword'] ) : '';
	?>
	<div class="wrap">
		<form method="post" id="vfbp-search-docs" action="" autocomplete="off">
			<input name="_vfbp_action" type="hidden" value="search-docs" />
			<?php
				wp_nonce_field( 'vfbp_search_docs' );
			?>
			<div class="vfb-edit-section">
				<div class="vfb-edit-section-inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<td>
									<p class="description"><?php _e( 'Thank you for using VFB Pro! Use the form below to search through our <a href="https://docs.vfbpro.com" target="_blank">knowledge base</a> for answers to common questions, tutorials, videos, and more.', 'vfb-pro'); ?></p>
								</td>
							</tr>
							<tr valign="top">
								<td>
									<input type="text" class="regular-text" id="vfbp-docs-search-keyword" name="vfbp-docs-search-keyword" value="<?php esc_html_e( $keyword ); ?>" required />

									<?php
										submit_button(
											__( 'Search', 'vfb-pro' ),
											'primary',
											'vfbp-search-docs',
											false
										);
									?>
								</td>
							</tr>
							<?php if ( !empty( $keyword ) ) : ?>
							<tr valign="top">
								<td>
									<h2 class="vfb-docs-search-header"><?php printf( __( 'Search Results for %s', 'vfb-pro' ), "<strong>$keyword</strong>" ); ?></h2>
									<?php
										$this->docs_search( $keyword );
									?>
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div> <!-- .vfb-edit-section-inside -->
			</div> <!-- .vfb-edit-section -->
		</form>
	</div> <!-- .wrap -->
	<?php
	}

	/**
	 * Search Docs API and return a result
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function docs_search( $data ) {
		if ( !isset( $_POST['_vfbp_action'] ) || !isset( $_GET['page'] ) )
			return;

		if ( 'search-docs' !== $_POST['_vfbp_action'] )
			return;

		check_admin_referer( 'vfbp_search_docs' );

		$api_params = array(
			'vfb-action' => 'search_docs',
			'keyword' 	 => $data,
		);

		$request = wp_remote_post(
			$this->docs_url,
			array(
				'timeout'   => 60,
				'body'      => $api_params
			)
		);

		if ( ! is_wp_error( $request ) ) {
			$request = wp_remote_retrieve_body( $request );

			if ( $request ) {
				$response = json_decode( $request );

				if ( is_array( $response ) ) {
					$output  = sprintf( '<p class="description"><strong>%d</strong> articles found</p>', count( $response ) );
					$output .= '<ul class="vfb-docs-search-list">';

					foreach ( $response as $item ) {
						$output .= '<li>';
							$output .= sprintf( '<a href="%2$s" target="_blank">%1$s</a>', $item->name, esc_url( $item->url ) );
							$output .= sprintf( '<p>%s</p>', $item->description );
						$output .= '</li>';
					}

					$output .= '</ul>';

					echo $output;
				}
			}
		}
	}

	/**
	 * Display the Diagnostics tab
	 *
	 * @access public
	 * @return void
	 */
	public function diagnostics() {
		$diagnostics = new VFB_Pro_Admin_Diagnostics();
		$diagnostics->display();
	}

	/**
	 * Displays the Add-ons tab
	 *
	 * @access public
	 * @return void
	 */
	public function addons_list() {
	?>
	<div class="vfb-addons-list-container">
		<p><?php _e( 'The following add-ons are available for purchase from our <a href="https://vfbpro.com" target="_blank">store</a>.', 'vfb-pro' ); ?></p>
		<div class="vfb-row">
			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-create-user">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Create User', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'Wish you could go ahead and create a WordPress user when someone submits a form? Create a form and watch as user sign-ups are now a piece of cake.', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-create-user/vfbp-create-user.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/visual-form-builder-pro-create-user" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->

			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-create-post">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Create Post', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'Easily create a WordPress post with your forms. Gain access to six new field items: Title, Content, Excerpt, Category, Tag, and Custom Field.', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-create-post/vfbp-create-post.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/visual-form-builder-pro-create-post" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->

			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-display-entries">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Display Entries', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'With the Display Entries add-on you can now show off your entries with the ease of checking a few boxes. Build tables that are fully sortable, filterable, and searchable.', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-display-entries/vfbp-display-entries.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/visual-form-builder-pro-display-entries" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->
		</div> <!-- .vfb-row -->

		<div class="vfb-row">
			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-payments">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Payments', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'Looking to sell more than one item? The Payments add-on allows you to sell multiple items, subscriptions, and even display a running total of your cart. PayPal Standard only (for now).', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-payments/vfbp-payments.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/visual-form-builder-pro-payments" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->

			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-form-designer">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Form Designer', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'Quickly and easily customize the design of your form without writing any CSS. Create a custom design based on your settings.', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-form-designer/vfbp-form-designer.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/visual-form-builder-pro-form-designer" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->

			<div class="vfb-col-4">
				<div class="vfb-addons-widget" id="vfb-addon-notifications">
					<div class="vfb-addons-widget-header">
						<h3><?php _e( 'Notifications', 'vfb-pro' ); ?></h3>
					</div> <!-- .vfb-addons-widget-header -->

					<div class="vfb-addons-widget-content">
						<p><?php _e( 'Connect your forms with a number of third-party services such as MailChimp, Campaign Monitor, Highrise, and FreshBooks after new form submissions. You can even send SMS to your phone!', 'vfb-pro' ); ?></p>
					</div> <!-- .vfb-addons-widget-content -->

					<div class="vfb-addons-widget-footer">
						<?php if ( is_plugin_active( 'vfbp-notifications/vfbp-notifications.php' ) ) : ?>
							<a class="button button-disabled"><span class="dashicons dashicons-yes"></span><?php _e( 'Installed', 'vfb-pro' ); ?></a>
						<?php else : ?>
							<a href="http://vfbpro.com/collections/add-ons/products/notifications" class="button button-primary"><?php _e( 'Buy Now', 'vfb-pro' ); ?></a>
						<?php endif; ?>
					</div> <!-- .vfb-addons-widget-footer -->
				</div> <!-- .vfb-addons-widget -->
			</div> <!-- .vfb-col-4 -->
		</div> <!-- .vfb-row -->
	</div> <!-- .vfb-addons-list-container -->
	<?php
	}

	/**
	 * Returns the current tab
	 *
	 * @access private
	 * @return void
	 */
	private function get_current_tab() {
		$tab = '';

		if ( !isset( $_GET['vfb-tab'] ) || isset( $_GET['vfb-tab'] ) && 'help' == $_GET['vfb-tab'] )
			$tab = 'help';
		elseif ( isset( $_GET['vfb-tab'] ) )
			$tab = esc_html( $_GET['vfb-tab'] );

		return $tab;
	}
}
