<?php
/**
 * ColorWay Customizer Notification Section Class.
 *
 * @package ColorWay
 */

// Include utils functions
require_once( get_template_directory() . '/includes/plugin-notification/cw-notifications/functions/cw-notifications-utils.php' );

/**
 * Inkthemes_Customizer_Notify_Section class
 */
class Inkthemes_Customizer_Notify_Section extends WP_Customize_Section {
	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'cw-customizer-notify-section';
	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $recommended_actions = '';
	/**
	 * Recommended Plugins.
	 *
	 * @var string
	 */
	public $recommended_plugins = '';
	/**
	 * Total number of required actions.
	 *
	 * @var string
	 */
	public $total_actions = '';
	/**
	 * Plugin text.
	 *
	 * @var string
	 */
	public $plugin_text = '';
	/**
	 * Dismiss button.
	 *
	 * @var string
	 */
	public $dismiss_button = '';

	/**
	 * Check if plugin is installed/activated
	 *
	 * @param plugin-slug $slug the plugin slug.
	 *
	 * @return array
	 */
	public function check_active( $slug ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug . '.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$needs = is_plugin_active( $slug . '/' . $slug . '.php' ) ? 'deactivate' : 'activate';

			return array(
				'status' => is_plugin_active( $slug . '/' . $slug . '.php' ),
				'needs'  => $needs,
			);
		}

		return array(
			'status' => false,
			'needs'  => 'install',
		);
	}

	/**
	 * Call plugin API to get plugins info
	 *
	 * @param plugin-slug $slug The plugin slug.
	 *
	 * @return mixed
	 */
	public function call_plugin_api( $slug ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$call_api = get_transient( 'cw_cust_notify_plugin_info_' . $slug );
		if ( false === $call_api ) {
			$call_api = plugins_api(
				'plugin_information', array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => false,
						'homepage'          => false,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => false,
					),
				)
			);
			set_transient( 'cw_cust_notify_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function json() {
		$json = parent::json();
		global $cw_customizer_notify_recommended_actions;
		global $cw_customizer_notify_recommended_plugins;

		global $install_button_label;
		global $activate_button_label;
		global $deactivate_button_label;

		$formatted_array                               = array();
		$cw_customizer_notify_show_recommended_actions = get_option( 'inkthemes_customizer_notify_show_recommended_actions' );
		foreach ( $cw_customizer_notify_recommended_actions as $key => $cw_customizer_notify_recommended_action ) {
			if ( $cw_customizer_notify_show_recommended_actions[ $cw_customizer_notify_recommended_action['id'] ] === false ) {
				continue;
			}
			if ( $cw_customizer_notify_recommended_action['check'] ) {
				continue;
			}

			$cw_customizer_notify_recommended_action['index'] = $key + 1;

			if ( isset( $cw_customizer_notify_recommended_action['plugin_slug'] ) ) {
				$active = $this->check_active( $cw_customizer_notify_recommended_action['plugin_slug'] );
				$cw_customizer_notify_recommended_action['url'] = create_action_link( $active['needs'], $cw_customizer_notify_recommended_action['plugin_slug'] );
				if ( $active['needs'] !== 'install' && $active['status'] ) {
					$cw_customizer_notify_recommended_action['class'] = 'active';
				} else {
					$cw_customizer_notify_recommended_action['class'] = '';
				}

				switch ( $active['needs'] ) {
					case 'install':
						$cw_customizer_notify_recommended_action['button_class'] = 'install-now button';
						$cw_customizer_notify_recommended_action['button_label'] = $install_button_label;
						break;
					case 'activate':
						$cw_customizer_notify_recommended_action['button_class'] = 'activate-now button button-primary';
						$cw_customizer_notify_recommended_action['button_label'] = $activate_button_label;
						break;
					case 'deactivate':
						$cw_customizer_notify_recommended_action['button_class'] = 'deactivate-now button';
						$cw_customizer_notify_recommended_action['button_label'] = $deactivate_button_label;
						break;
				}
			}
			$formatted_array[] = $cw_customizer_notify_recommended_action;
		}// End foreach().

		$customize_plugins = array();

		$cw_customizer_notify_show_recommended_plugins = get_option( 'inkthemes_customizer_notify_show_recommended_plugins' );

		foreach ( $cw_customizer_notify_recommended_plugins as $slug => $plugin_opt ) {

			if ( ! $plugin_opt['recommended'] ) {
				continue;
			}

			if ( isset( $cw_customizer_notify_show_recommended_plugins[ $slug ] ) && $cw_customizer_notify_show_recommended_plugins[ $slug ] ) {
				continue;
			}

			$active = $this->check_active( $slug );

			if ( ! empty( $active['needs'] ) && ( $active['needs'] == 'deactivate' ) ) {
				continue;
			}

			$cw_customizer_notify_recommended_plugin['url'] = create_action_link( $active['needs'], $slug );
			if ( $active['needs'] !== 'install' && $active['status'] ) {
				$cw_customizer_notify_recommended_plugin['class'] = 'active';
			} else {
				$cw_customizer_notify_recommended_plugin['class'] = '';
			}

			switch ( $active['needs'] ) {
				case 'install':
					$cw_customizer_notify_recommended_plugin['button_class'] = 'install-now button button-primary';
					$cw_customizer_notify_recommended_plugin['button_label'] = $install_button_label;
					break;
				case 'activate':
					$cw_customizer_notify_recommended_plugin['button_class'] = 'activate-now button button-primary';
					$cw_customizer_notify_recommended_plugin['button_label'] = $activate_button_label;
					break;
				case 'deactivate':
					$cw_customizer_notify_recommended_plugin['button_class'] = 'deactivate-now button button-primary';
					$cw_customizer_notify_recommended_plugin['button_label'] = $deactivate_button_label;
					break;
			}
			$info = $this->call_plugin_api( $slug );
			$cw_customizer_notify_recommended_plugin['id']          = $slug;
			$cw_customizer_notify_recommended_plugin['plugin_slug'] = $slug;

			if ( ! empty( $plugin_opt['description'] ) ) {
				$cw_customizer_notify_recommended_plugin['description'] = $plugin_opt['description'];
			} else {
				$cw_customizer_notify_recommended_plugin['description'] = $info->short_description;
			}

			$cw_customizer_notify_recommended_plugin['title'] = '';

			if ( isset( $info->name ) ) {
				$cw_customizer_notify_recommended_plugin['title'] = $info->name;
			}

			$customize_plugins[] = $cw_customizer_notify_recommended_plugin;

		}// End foreach().

		$json['recommended_actions'] = $formatted_array;
		$json['recommended_plugins'] = $customize_plugins;
		$json['total_actions']       = count( $cw_customizer_notify_recommended_actions );
		$json['plugin_text']         = $this->plugin_text;
		$json['dismiss_button']      = $this->dismiss_button;
		return $json;

	}
	/**
	 * Outputs the structure for the customizer control
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() {
		?>
		<# if( data.recommended_actions.length > 0 || data.recommended_plugins.length > 0 ){ #>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

				<h3 class="accordion-section-title">
					<span class="section-title" data-plugin_text="{{ data.plugin_text }}">
						<# if( data.recommended_actions.length > 0 ){ #>
							{{ data.title }}
						<# }else{ #>
							<# if( data.recommended_plugins.length > 0 ){ #>
								{{ data.plugin_text }}
							<# }#>
						<# } #>
					</span>
					<# if( data.recommended_actions.length > 0 ){ #>
						<span class="cw-customizer-notify-actions-count">
							<span class="current-index">{{ data.recommended_actions[0].index }}</span>
							{{ data.total_actions }}
						</span>
					<# } #>
				</h3>
				<div class="recomended-actions_container" id="plugin-filter">					
					<# if( data.recommended_plugins.length > 0 ){ #>
						<# for (action in data.recommended_plugins) { #>
							<div class="epsilon-recommeded-actions-container epsilon-recommended-plugins" data-index="{{ data.recommended_plugins[action].index }}">
								<# if( !data.recommended_plugins[action].check ){ #>
									<div class="epsilon-recommeded-actions">
										<p class="title">{{ data.recommended_plugins[action].title }}</p>
										<span data-action="dismiss" class="dashicons dashicons-no cw-customizer-notify-dismiss-button-recommended-plugin" id="{{ data.recommended_plugins[action].id }}"></span>
										<div class="description">{{{ data.recommended_plugins[action].description }}}</div>
										<# if( data.recommended_plugins[action].plugin_slug ){ #>
											<div class="custom-action">
												<p class="plugin-card-{{ data.recommended_plugins[action].plugin_slug }} action_button {{ data.recommended_plugins[action].class }}">
													<a data-slug="{{ data.recommended_plugins[action].plugin_slug }}" class="{{ data.recommended_plugins[action].button_class }}" href="{{ data.recommended_plugins[action].url }}">{{ data.recommended_plugins[action].button_label }}</a>
												</p>
											</div>
										<# } #>
										<# if( data.recommended_plugins[action].help ){ #>
											<div class="custom-action">{{{ data.recommended_plugins[action].help }}}</div>
										<# } #>
									</div>
								<# } #>
							</div>
						<# } #>
					<# } #>
				</div>
			</li>
		<# } #>
		<?php
	}
}
