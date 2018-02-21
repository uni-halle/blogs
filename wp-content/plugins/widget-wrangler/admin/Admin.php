<?php

namespace WidgetWrangler;

/**
 * Class Admin
 * @package WidgetWrangler
 */
class Admin {
	/**
	 * @var array
	 */
	public $settings = array();

	/**
	 * Admin constructor.
	 *
	 * @param $settings
	 */
	function __construct($settings){

		include_once WW_PLUGIN_DIR.'/admin/AdminMessages.php';
		include_once WW_PLUGIN_DIR.'/admin/Form.php';

		include_once WW_PLUGIN_DIR.'/admin/AdminPage.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPageClones.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPageCorrals.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPageDocumentation.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPagePresets.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPageSettings.php';
		include_once WW_PLUGIN_DIR.'/admin/AdminPageSidebars.php';

		include_once WW_PLUGIN_DIR.'/admin/SortableWidgetsUi.php';
		include_once WW_PLUGIN_DIR.'/admin/TaxonomyUi.php';
		include_once WW_PLUGIN_DIR.'/admin/TaxonomyTermUi.php';
		include_once WW_PLUGIN_DIR.'/admin/WidgetPostType.php';

		$this->settings = $settings;
	}

	/**
	 * @param $settings
	 *
	 * @return \WidgetWrangler\Admin
	 */
	public static function register($settings) {
		$plugin = new self($settings);

		add_action( 'wp_loaded', array( $plugin, 'loaded' ) );
		add_action( 'admin_init', array( $plugin, 'admin_init' ) );
		add_action( 'wp_ajax_ww_form_ajax', array( $plugin, 'ww_form_ajax' ) );

		AdminPageClones::register($settings);
		AdminPageCorrals::register($settings);
		AdminPagePresets::register($settings);
		AdminPageSettings::register($settings);
		AdminPageSidebars::register($settings);
		AdminPageDocumentation::register($settings);
		TaxonomyUi::register($settings);
		TaxonomyTermUi::register($settings);

		return $plugin;
	}

	/**
	 * WP hook wp_loaded
	 */
	function loaded() {
		WidgetPostType::register($this->settings);
    }

	/**
	 * WP hook admin_init
	 */
    function admin_init() {
        wp_register_style('ww-admin', plugins_url('css/admin.css', __FILE__), array(), WW_SCRIPT_VERSION );

	    wp_register_script('ww-admin',
		    plugins_url('js/admin.js', __FILE__),
		    array('jquery', 'jquery-ui-sortable', 'wp-util'),
		    WW_SCRIPT_VERSION,
		    true
	    );
        wp_register_script('ww-sortable-widgets',
            plugins_url('js/sortable-widgets.js', __FILE__),
            array('jquery-ui-core', 'jquery-ui-sortable', 'wp-util'),
            WW_SCRIPT_VERSION,
            true );
        wp_register_script('ww-widget-posttype',
            plugins_url('js/widget-posttype.js', __FILE__),
            array('jquery'),
            WW_SCRIPT_VERSION,
            true
        );

        // Add metabox to enabled post_types
        if (!empty($this->settings['post_types'])){
            foreach($this->settings['post_types'] as $enabled_post_type){
                add_meta_box('ww_admin_meta_box',
                    '<img src="'.WW_PLUGIN_URL.'/admin/images/lasso-small-black.png" />' . __('Widget Wrangler', 'widgetwrangler'),
                    '\WidgetWrangler\SortableWidgetsUi::postMetaBox',
                    $enabled_post_type,
                    'normal',
                    'high');

                // Add some CSS to the admin header on the widget wrangler pages, and edit pages
                if (Utils::editingEnabledPostType()){
                    SortableWidgetsUi::js();
                }

                if (isset($_POST['post_type']) && $_POST['post_type'] == $enabled_post_type){
                    add_action( 'save_post', array( $this, 'savePostWidgets' ) );
                }
            }
        }
    }

	/**
	 * Ajax response for changing presets on the wrangler.
	 */
    function ww_form_ajax() {
		if ( empty( $_POST['context'] ) ) {
			exit;
		}

		$context = $_POST['context'];
		$widgets = array();

	    // if we changed to a preset, load those widgets
	    if ( ! empty( $_POST['preset_id'] ) ) {
		    $preset = Presets::get( $_POST['preset_id']);
		    $widgets = $preset->widgets;
	    }
    	// default
	    else if ( !empty( $context['post'] ) ) {
		    $widgets = Utils::getPostWidgets( $context['id'] );
	    }
	    // term
	    else if ( !empty( $context['term'] ) ) {
		    $widgets = Presets::getCore('default')->widgets;

		    $where = array(
			    'type' => 'taxonomy',
			    'variety' => 'term',
			    'extra_key' => $context['id'],
		    );

		    if ( $term_data = Extras::get( $where ) ) {
			    $widgets = $term_data->widgets;
		    }
	    }
	    // taxonomy
	    else if ( !empty( $context['taxonomy'] ) && empty( $context['term'] ) ) {
		    $widgets = Presets::getCore('default')->widgets;

		    $where = array(
			    'type' => 'taxonomy',
			    'variety' => 'taxonomy',
			    'extra_key' => $context['id'],
		    );

		    if ( $term_data = Extras::get( $where ) ) {
			    $widgets = $term_data->widgets;
		    }
	    }

	    SortableWidgetsUi::metaBox( $widgets );
	    exit;
    }

	/**
	 * Standardized detection of submitted wrangler data.
	 *
	 * @return array
	 */
	public static function getSubmittedWranglerData() {
		$widgets = NULL;
		$preset_id = 0;
		$submitted_widgets = ( ! empty( $_POST['ww-data'] ) && ! empty( $_POST['ww-data']['widgets'] ) ) ? $_POST['ww-data']['widgets'] : array();
		$submitted_preset_id = isset( $_POST['ww-preset-id-new'] ) ? intval( $_POST['ww-preset-id-new'] ) : 0;
		$active_widgets = self::prepareSubmittedWidgetData( $submitted_widgets );

		if ( $submitted_preset_id ) {
			$preset = Presets::get( $submitted_preset_id );

			// if the submitted widgets match the submitted preset widgets,
			// then the user chose a preset, do not record the submitted widgets.
			if ( $preset->widgets === $active_widgets ) {
				$preset_id = $submitted_preset_id;
			}
		}

		// if the preset_id is still 0, record the submitted widgets.
		if ( ! $preset_id ) {
			$widgets = $submitted_widgets;
		}

		return array(
			'preset_id' => $preset_id,
			'widgets' => $widgets,
		);
	}

	/**
	 * Build an array of widgets in corrals from submitted Wrangler interface.
	 *
	 * @param $submitted_widget_data
	 *
	 * @return array
	 */
	public static function prepareSubmittedWidgetData( $submitted_widget_data ) {
		$all_widgets = Widgets::all( array( 'publish', 'draft' ) );
		$active_widgets = array();

		if ( ! empty( $submitted_widget_data ) ) {
			foreach ( $submitted_widget_data as $key => $details ) {
				// get rid of any hashes
				if ( isset( $all_widgets[ $details['id'] ] ) && isset( $details['weight'] ) && isset( $details['sidebar'] ) ) {
					// if something was submitted without a weight, make it neutral
					if ( $details['weight'] < 1 ) {
						$details['weight'] = $key;
					}

					$active_widgets[ $details['sidebar'] ][] = array(
						'id' => $details['id'],
						'weight' => $details['weight'],
					);
				}
			}
		}

		return $active_widgets;
	}

	/**
	 * Take data from $_POST submit and convert in to serialized array as string
	 *
	 * @param $submitted_widget_data
	 *
	 * @return string
	 */
	public static function serializeWidgets( $submitted_widget_data ) {
		$active_widgets = self::prepareSubmittedWidgetData( $submitted_widget_data );
		return serialize( $active_widgets );
	}

	/**
	 * Cleanup stored widget data in case of over serialization
	 *
	 * @param $corrals
	 *
	 * @return mixed
	 */
	public static function unserializeWidgets( $corrals ) {
		// problem with over serialized options
		$corrals = maybe_unserialize( $corrals );
		$corrals = maybe_unserialize( $corrals );

		if ( isset( $corrals['disabled'] ) ) {
			unset( $corrals['disabled'] );
		}

		foreach ( $corrals as $corral_slug => $corral_widgets ) {
			foreach ( $corral_widgets as $i => $widget ) {
				if ( isset( $widget['name'] ) ) {
					unset( $corrals[ $corral_slug ][ $i ]['name'] );
				}
			}
		}

		return $corrals;
	}

	/**
	 * Hook into saving a page
	 * Save the post meta for this post
	 *
	 * @param $post_id
	 *
	 * @return int
	 */
	function savePostWidgets($post_id) {
		// skip quick edit
		if (isset($_REQUEST['_inline_edit'])) { return $post_id; }

		// don't know what is being saved if not a post_type, so we do nothing
		if (!isset($_POST['post_type'])){
			return $post_id;
		}

		// Ensure this is an enabled post_type and user can edit it
		$settings = $this->settings;
		if (!in_array($_POST['post_type'], $settings['post_types']) || !current_user_can('edit_post', $post_id)){
			return $post_id;
		}

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( ! isset( $_POST['ww-sortable-list-box'] ) || ! wp_verify_nonce( $_POST['ww-sortable-list-box'], 'widget-wrangler-sortable-list-box-save' ) ) {
			return $post_id;
		}

		// If this is an auto save routine, our form has not been submitted, so we dont want to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// OK, we're authenticated:
		// we need to find and save the data
		$submitted = self::getSubmittedWranglerData();

		if ( isset( $submitted['widgets'] ) ) {
			$widgets = self::serializeWidgets( $submitted['widgets'] );
			update_post_meta( $post_id, 'ww_post_widgets', $widgets);
		}

		update_post_meta( $post_id, 'ww_post_preset_id', (int) $submitted['preset_id'] );

		return $post_id;
	}

	/**
	 * Save widgets from a wrangler form
	 *
	 * @param $variety
	 * @param $extra_key
	 * @param array $additional_data
	 */
	public static function saveTaxonomyWidgets( $variety, $extra_key, $additional_data = array() ) {
		$submitted = Admin::getSubmittedWranglerData();

		$where = array(
			'type' => 'taxonomy',
			'variety' => $variety,
			'extra_key' => $extra_key,
		);

		$values = array(
			'type' => 'taxonomy',
			'variety' => $variety,
			'extra_key' => $extra_key,
			'data' => array( 'preset_id' => $submitted['preset_id'] ),
			'widgets' => $submitted['widgets'],
		);

		if ( ! empty( $additional_data ) ) {
			$values['data'] += $additional_data;
		}

		if ( $submitted['preset_id'] ) {
			// don't save widgets because they are preset widgets
			unset( $values['widgets'] );
		}

		// doesn't exist, create it before update
		if ( ! Extras::get( $where ) ) {
			Extras::insert( $values );
		}

		Extras::update( $values, $where );
	}

}
