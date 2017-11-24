<?php
namespace WidgetWrangler;

/**
 * Class AdminPageSettings
 * @package WidgetWrangler
 */
class AdminPageSettings extends AdminPage {

	/**
	 * @see AdminPage::title()
	 */
	function title() {
		return __('Settings', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::menuTitle()
	 */
	function menuTitle() {
		return __('Settings', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::slug()
	 */
	function slug() {
		return 'settings';
	}

	/**
	 * @see AdminPage::description()
	 */
	function description() {
		return array(
			__('Site settings for Widget Wrangler'),
		);
	}

	/**
	 * @see AdminPage::actions()
	 */
	function actions() {
		return array(
			'save' => array( $this, 'actionSave' ),
			'reset' => array( $this, 'actionResetWidgets' ),
			'reset_settings' => array( $this, 'actionResetSettings' ),
			'theme_setup' => array( $this, 'actionThemeSetup' ),
		);
	}

	/**
	 * @see \AdminPage::page()
	 */
	function page() {
		$settings_form = new Form(array(
			'action' => $this->actionPath('save'),
			'field_prefix' => 'settings',
			'style' => 'box',
			'fields' => array(
				'submit' =>array(
					'type' => 'submit',
					'value' => __('Save settings', 'widgetwrangler'),
                    'class' => 'button button-large button-primary',
				),
				'post_types' => array(
					'type' => 'checkboxes',
					'title' => __('Post Types', 'widgetwrangler'),
					'description' => __('Select which post types can control widgets individually.', 'widgetwrangler'),
					'options' => $this->postTypeOptions(),
					'value' => $this->settings['post_types'],
				),
				'taxonomies' => array(
					'type' => 'checkboxes',
					'title' => __('Taxonomies', 'widgetwrangler'),
					'description' => __('Select which taxonomies can control widgets individually.', 'widgetwrangler'),
					'options' => $this->taxonomyOptions(),
					'value' => $this->settings['taxonomies'],
				),
				'theme_compat' => array(
					'type' => 'checkbox',
					'title' => __('Theme Compatibility', 'widgetwrangler'),
					'help' => __('If checked, widgets will include WordPress sidebar settings for the registered sidebar.  ie, $before_widget, $before_title, $after_title, $after_widget. -- Additionally, enabling theme compatibility provides an administration page for managing the current theme\'s registered sidebar html.', 'widgetwrangler'),
					'value' => $this->settings['theme_compat'],
				),
				// Widget Post Type
				'exclude_from_search' => array(
					'type' => 'checkbox',
					'title' => __('Exclude from search', 'widgetwrangler'),
					'help' => __('If checked, widgets will be excluded from search results.', 'widgetwrangler'),
					'value' => $this->settings['exclude_from_search'],
				),
				'advanced_capability' => array(
					'type' => 'text',
					'title' => __('Widget post type capability', 'widgetwrangler'),
					'description' => __('This is primarily for incorporating third party permission systems.', 'widgetwrangler'),
					'help' => __('Leave blank to use default post capabilities.', 'widgetwrangler'),
					'value' => $this->settings['advanced_capability'],
				),
				// Old & Deprecated stuff
				'legacy_template_suggestions' => array(
					'type' => 'checkbox',
					'title' => __('Legacy Template Suggestions', 'widgetwrangler'),
					'help' => __('This version of Widget Wrangler has been upgraded from 1.x. If you have created templates with the previous version, you should leave this checked.', 'widgetwrangler'),
					'value' => $this->settings['legacy_template_suggestions'],
					'access' => get_option('ww_previous_main_version', false),
				),
				'override_elements_enabled' => array(
					'type' => 'checkbox',
					'title' => __('Deprecated: Enable overriding widget HTML from the Post UI.', 'widgetwrangler'),
					'help' => __('This is a deprecated feature that is only here for legacy systems.', 'widgetwrangler'),
					'value' => $this->settings['override_elements_enabled'],
					'access' => ( $this->settings['previously_pro'] ),
				),
				'override_elements' => array(
					'type' => 'textarea',
					'title' => __('Deprecated: HTML Override Elements', 'widgetwrangler'),
					'help' => __('Allowed elements for override a widget\'s html output.  Place one element per line.', 'widgetwrangler'),
					'value' => implode( "\n", $this->settings['override_elements'] ),
					'access' => ( $this->settings['previously_pro'] ),
				),
			)
		));

		$setup_theme_form = new Form(array(
            'style' => 'box',
            'action' => $this->actionPath('theme_setup'),
            'attributes' => array(
                'id' => 'setup-theme-tool',
            ),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'title' => __('Setup Theme', 'widgetwrangler'),
                    'description' => __('If you click this button, Widget Wrangler will create a Corral for each WordPress sidebar you have, and place a Widget Wrangler Corral Widget into each WordPress Sidebar.', 'widgetwrangler'),
                    'value' => __('Setup Theme', 'widgetwrangler'),
                    'class' => 'button disabled',
                    'attributes' => array(
                        'data-confirm' => __('Are you sure you want to replace the WordPress widgets in your sidebars with corrals?', 'widgetwrangler'),
                    ),
                )
            )
        ));
		$reset_widgets_form = new Form(array(
            'style' => 'box',
            'action' => $this->actionPath('reset'),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'title' => __('Mass Reset', 'widgetwrangler'),
                    'description' => __('If you click this button, all pages will lose their assigned widget settings and will fall back on the default preset.', 'widgetwrangler'),
                    'value' => __('Reset All Widgets to Default', 'widgetwrangler'),
                    'class' => 'button disabled',
                    'attributes' => array(
	                    'data-confirm' => __('Are you sure you want to set all post and page widget settings back to the default preset?', 'widgetwrangler'),
                    ),
                )
            )
        ));
		$reset_settings_form = new Form(array(
            'style' => 'box',
            'action' => $this->actionPath('reset_settings'),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'title' => __('Reset Settings', 'widgetwrangler'),
                    'description' => __('If you click this button, Widget Wrangler settings will be reset to their default state.  This will not affect Corral or Widget data.', 'widgetwrangler'),
                    'value' => __('Reset Settings', 'widgetwrangler'),
                    'class' => 'button disabled',
                    'attributes' => array(
	                    'data-confirm' => __('Are you sure you want to reset the plugin settings back to default?', 'widgetwrangler'),
                    ),
                )
            )
        ));
        ?>
        <div class="ww-columns">
            <div class="ww-column col-25">
                <h2><?php _e('General', 'widgetwrangler'); ?></h2>
                <p><?php _e('Setup how Widget Wrangler works with other WordPress content.', 'widgetwrangler'); ?></p>
            </div>
            <div class="ww-column col-75">
				<?php print $settings_form->render(); ?>
            </div>
        </div>
        <div class="ww-columns">
            <div class="ww-column col-25">
                <h2><?php _e('Tools', 'widgetwrangler'); ?></h2>
                <p><?php _e('Actions that will modify Widget Wrangler data.', 'widgetwrangler'); ?></p>
            </div>
            <div class="ww-column col-75">
				<?php
                    print $setup_theme_form->render();
                    print $reset_widgets_form->render();
                    print $reset_settings_form->render();
				?>
            </div>
        </div>
        <?php
    }

	/**
	 * Reset all pages to use the default widget settings
	 */
	function actionResetWidgets() {
		global $wpdb;
		$query = "DELETE FROM `{$wpdb->postmeta}` WHERE `meta_key` = 'ww_post_widgets' OR `meta_key` = 'ww_post_preset_id'";
		$wpdb->query($query);

		return $this->result( __('Widget data on all posts have been deleted.', 'widgetwrangler') );
	}

	/**
	 * Reset Widget Wrangler settings values back to default.
	 */
	function actionResetSettings() {
		$settings = Settings::instance();
		$settings->values = $settings->default_settings;
		$settings->save();
		$this->settings = $settings->values;

		return $this->result( __('Settings have been reset to default.', 'widgetwrangler') );
    }

	/**
	 * Save the Widget Wrangler Settings page
	 */
	function actionSave() {
	    if ( empty( $_POST['settings'] ) ) {
	        return $this->error();
        }

		$settings = Settings::instance();

		// copy submitted fields to the settings object
	    foreach ($settings->values as $key => $value) {
	        if ( isset( $_POST['settings'][ $key ] ) ) {
                $settings->values[ $key ] = $_POST['settings'][ $key ];
            }
        }

		$settings->save();
		$this->settings = $settings->values;

		return $this->result( __('Settings saved.', 'widgetwrangler') );
	}

	/**
	 * Empty wp sidebars,
	 *  - create a corral for each wp sidebar,
	 *  - place corral widget inside of each wp sidebar
	 */
	function actionThemeSetup() {
		global $wp_registered_sidebars;
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		$corrals = Corrals::all();
		$new_corrals = array();

		// new options
		$new_sidebars_widgets = array(
			'wp_inactive_widgets' => $sidebars_widgets['wp_inactive_widgets'],
			'array_version' => $sidebars_widgets['array_version'],
		);
		$new_widget_ww_sidebar = array('_multiwidget' => 1);

		$i = 0;
		foreach ($wp_registered_sidebars as $sidebar_id => $sidebar_details){
			$corral_slug = Utils::makeSlug($sidebar_details['name']);

			// see if corral exists
			if (!isset($corrals[$corral_slug])){
				// make it
				$corrals[$corral_slug] = $sidebar_details['name'];
				$new_corrals[] = $sidebar_details['name'];
			}

			// assign a new corral widget instances
			$new_widget_ww_sidebar[$i] = array(
				'title' => $sidebar_details['name'],
				'sidebar' => $corral_slug,
			);

			// assign new widget instance to sidebar
			$new_sidebars_widgets[$sidebar_id][$i] = 'widget-wrangler-sidebar-'.$i;

			$i++;
		}

		update_option('ww_sidebars', $corrals);
		update_option('sidebars_widgets', $new_sidebars_widgets);
		update_option('widget_widget-wrangler-sidebar', $new_widget_ww_sidebar);

		if ( !empty( $new_corrals ) ) {
			return $this->result( __('New corrals created: ', 'widgetwrangler') . implode(', ', $new_corrals) );
        }

        return $this->result( __('No new corrals created. Appropriate Corrals have been assigned to their sidebars.', 'widgetwrangler') );
	}

	/**
     * Get all post types widgets can be assigned to as an options array.
     *
	 * @return array
	 */
	function postTypeOptions() {
		$post_types = get_post_types(array('public' => true, '_builtin' => false), 'names', 'and');
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';
		unset($post_types['widget']);
		ksort($post_types);

		foreach ($post_types as $id => $name ) {
		    $post_types[$id] = ucfirst( $name );
        }

		return $post_types;
	}

	/**
     * Get all taxonomies widgets can be assigned to as an options array.
     *
	 * @return array
	 */
	function taxonomyOptions() {
		$options = array();
		$taxonomies = get_taxonomies(array(), 'objects');

		foreach( $taxonomies as $slug => $taxonomy ){
			if ($taxonomy->show_ui) {
				$options[ $slug ] = $taxonomy->label;

				if ( !empty( $this->settings['taxonomies'][ $slug ] ) ) {
					// taken from get_edit_term_link
					// https://core.trac.wordpress.org/browser/tags/3.9.1/src/wp-includes/link-template.php#L894
					$edit_link = add_query_arg( array( 'taxonomy' => $slug ), admin_url( 'edit-tags.php' ) );
					$options[ $slug ].= sprintf( ' - <a href="%s">%s</a>', $edit_link, __('edit', 'widgetwrangler') );
                }
			}
		}

		return $options;
	}

}
