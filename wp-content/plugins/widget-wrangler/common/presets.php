<?php

namespace WidgetWrangler;

/**
 * Presets are a mechanism for using pre-configured groups of widgets throughout
 *  a WordPress site.
 *
 * Preset varieties are different types of presets.
 *
 * New WordPress filters
 *  - widget_wrangler_preset_varieties
 */
class Presets {

	/**
	 * Register hooks
	 *
	 * @return Presets
	 */
	public static function register() {
		$plugin = new self();
		
		// wp hooks
		add_action( 'admin_init', array( $plugin, 'wp_admin_init' ) );

		return $plugin;
	}

	/**
	 * WordPress hook admin_init
	 *  Ensure default presets are installed
	 */
	function wp_admin_init(){
		// make sure our core presets are installed
		if (!self::getCore('default')){
			Extras::ensureTable();
			self::installCore();
		}
	}

	/**
	 * Look for and insert legacy default and postpage widgets as presets
	 */
	public static function installCore(){
		$data = array(
			'type' => 'preset',
			'variety' => 'core',
			'extra_key' => '',
			'data' => '',
			'widgets' => serialize(array()),
		);

		$where = array(
			'type' => 'preset',
			'variety' => 'core',
			'extra_key' => 'default',
		);

		// default widgets
		$row = Extras::get($where);

		if (!$row) {
			$data['extra_key'] = $where['extra_key'];
			$data['data'] = array('name' => 'Default');

			$existing_widgets = Admin::unserializeWidgets( get_option( 'ww_default_widgets', array() ) );
			$data['widgets'] = serialize($existing_widgets);

			Extras::insert($data);
		}

		// postspage widgets
		$where['extra_key'] = 'postspage';
		$row = Extras::get($where);
		if (!$row) {
			$data['extra_key'] = $where['extra_key'];
			$data['data'] = array('name' => 'Posts Page');

			$existing_widgets = Admin::unserializeWidgets( get_option( 'ww_postspage_widgets', array() ) );
			$data['widgets'] = serialize($existing_widgets);

			Extras::insert($data);
		}
	}

	/**
	 * Get all Presets
	 *
	 * @return array All widget presets found in the DB
	 */
	public static function all(){
		$where = array(
			'type' => 'preset',
		);
		return Extras::get($where, 'all');
	}

	/**
	 * Get a single Preset
	 *
	 * @param int $preset_id the id for the Preset
	 *
	 * @return array the Preset details
	 */
	public static function get($preset_id) {
		$where = array(
			'id' => $preset_id,
			'type' => 'preset',
		);

		return Extras::get($where);
	}

	/**
	 * Get a preset of the 'core' variety using its key
	 *
	 * @param string - key for the core preset
	 *
	 * @return mixed
	 */
	public static function getCore($preset_key){
		$where = array(
			'type' => 'preset',
			'variety' => 'core',
			'extra_key' => $preset_key,
		);

		return Extras::get($where);
	}

	/**
	 * All Preset types provided by default
	 *
	 * @param array - existing preset varieties
	 *
	 * @return array
	 */
	public static function varieties() {
		// default varieties
		$varieties = array(
			'core' => array(
				'title' => 'Core',
				'description' => 'Widget Wrangler provided presets',
			),
			'standard' => array(
				'title' => 'Standard',
				'description' => 'Custom arbitrary widget groupings',
			)
		);

		$varieties = apply_filters('widget_wrangler_preset_varieties', $varieties );

		// Process them a little for extra data
		foreach($varieties as $slug => $preset_variety){
			// set filter's type as a value if not provided by filter
			if(!isset($preset_variety['slug'])){
				$varieties[$slug]['slug'] = $slug;
			}
			// maintain the hook's key
			$varieties[$slug]['hook_key'] = $slug;
		}

		return $varieties;
	}

	/**
	 * All presets as a simple options array.
	 *
	 * @return array
	 */
	public static function asOptions() {
		$options = array();

		foreach( self::all() as $preset ) {
			$options[ $preset->id ] = $preset->data['name'];
		}

		return $options;
	}

}
