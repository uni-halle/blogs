<?php

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once( plugin_dir_path( __FILE__ ).'opinionstage-client-session.php' );

	// Sidebar widget class for embeding the Opinion Stage sidebar placement
	class OpinionStageWidget extends WP_Widget {
		function __construct() {
			// register new widget
			$widget_ops = array(
				'classname' => 'opinionstage_widget',
				'description' => __('Adds a highly engaging polls to your widget section.', OPINIONSTAGE_TEXT_DOMAIN)
			);
			parent::__construct(
				'opinionstage_widget',
				__( 'Opinion Stage Sidebar Widget', OPINIONSTAGE_TEXT_DOMAIN ),
				$widget_ops
			);
		}

		/*
		 * Returns the widget content - including the title and the sidebar placement content (once enabled)
		 */
		function widget($args, $instance) {
			extract($args);
			echo $before_widget;
			$title = @$instance['title'];
			$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);

			// Show the title once widget is enabled
			if (!empty($title) && $os_options['sidebar_placement_active'] == 'true') echo $before_title . apply_filters('widget_title', $title) . $after_title;

			// Add the placement shortcode once widget is enabled
			if (!empty($os_options["sidebar_placement_id"]) && $os_options['sidebar_placement_active'] == 'true') {
				echo opinionstage_widget_placement( opinionstage_placement_embed_code_url($os_options["sidebar_placement_id"]) );
			}

			echo $after_widget;
		}

		/*
		 * Updates the widget settings (title and enabled flag)
		 */
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['enabled'] = strip_tags($new_instance['enabled']);
			$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
			$os_options['sidebar_placement_active'] = ('1' == $instance['enabled']);
			update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
			return $instance;
		}

		/*
		 * Generates the admin form for the widget.
		 */
		function form($instance) {
			opinionstage_register_css_asset( 'icon-font', 'icon-font.css' );
			opinionstage_register_css_asset( 'sidebar-widget', 'sidebar-widget.css' );

			opinionstage_enqueue_css_asset('icon-font');
			opinionstage_enqueue_css_asset('sidebar-widget');

			$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
			$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
			$enabled = $os_options['sidebar_placement_active'] == 'true' ? '1' : '';
			$os_client_logged_in = opinionstage_user_logged_in();

			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						var callbackURL = '<?php echo opinionstage_callback_url() ?>';

						$('.opinionstage-sidebar-widget').on('click', '.start-login', function(){
							var emailInput = $('#os-email');
							var email = $(emailInput).val();
							var new_location = "<?php echo OPINIONSTAGE_LOGIN_PATH.'?callback=' ?>" + encodeURIComponent(callbackURL) + "&email=" + email;
							window.location = new_location;
						});

						$('.opinionstage-sidebar-widget').on('click', '.switch-email', function(){
							var new_location = "<?php echo OPINIONSTAGE_LOGIN_PATH.'?callback=' ?>" + encodeURIComponent(callbackURL);
							window.location = new_location;
						});

						$('#os-email').keypress(function(e){
							if (e.keyCode == 13) {
								$('#os-start-login').click();
							}
						});
					});
				</script>

				<div class="opinionstage-sidebar-widget">
					<?php if ( $os_client_logged_in ) {?>
						<div class="opinionstage-sidebar-connected">
							<div class="os-icon icon-os-form-success"></div>
							<div class="opinionstage-connected-info">
								<div class="opinionstage-connected-title"><b>You are connected</b> to Opinion Stage with:</div>
								<input id="os-email" type="email" disabled="disabled" value="<?php echo($os_options["email"]) ?>">
								<a href="javascript:void(0)" class="switch-email" id="os-switch-email" >Switch Account</a>
							</div>
						</div>
						<p>
							<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', OPINIONSTAGE_TEXT_DOMAIN); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" placeholder="Enter the title here" value="<?php echo $title; ?>" >
						</p>
						<div class="opinionstage-sidebar-actions">
							<div class="opinionstage-sidebar-enabled">
								<input type="checkbox" id="<?php echo $this->get_field_id('enabled'); ?>" name="<?php echo $this->get_field_name('enabled'); ?>" value="1" <?php echo($enabled == '1' ? "checked" : "") ?> />
								<label for="<?php echo $this->get_field_id('enabled'); ?>">Enabled</label>
							</div>
							<div class="opinionstage-sidebar-config">
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('content'); ?>" target="_blank" class='opinionstage-blue-bordered-btn'>EDIT CONTENT</a>
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">
									<div class="os-icon icon-os-common-settings"></div>
								</a>
							</div>
						</div>
					<?php } else { ?>
						<p>Connect WordPress with Opinion Stage to enable the widget</p>
						<div class="os-icon icon-os-poll-client"></div>
						<input id="os-email" type="email" class="os-email" placeholder="Enter Your Email">
						<a href="javascript:void(0)" class="os-button start-login" id="os-start-login">Connect</a>
					<?php } ?>
				</div>
			<?php
		}
	}

	/*
	 * Register Sidebar Placement Widget
	 */
	function opinionstage_init_widget() {
		register_widget('OpinionStageWidget');
	}
?>
