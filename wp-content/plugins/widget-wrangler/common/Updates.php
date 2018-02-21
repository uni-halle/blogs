<?php

namespace WidgetWrangler;

/**
 * Class Update
 * @package WidgetWrangler
 */
class Updates {

	/**
	 * Simple wrapper to retrieve global $wpdb object with local modifications
	 *
	 * @return \wpdb
	 */
	public static function db() {
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Check for updates and perform as necessary
	 */
	public static function update() {
		$old_version = self::getDbVersion();

		// upgrade, if an old version exists
		if ( version_compare( $old_version, WW_DB_VERSION, '<') ){

			// these are 2 legacy updates that will be deleted eventually.
			self::update2000();
			self::update2001();

			// automatically detect updates and execute them
			$updates = self::getUpdates();

			foreach ($updates as $update) {
				call_user_func( $update );
			}

			update_option('ww_version', WW_VERSION);
			update_option('ww_db_version', WW_DB_VERSION);
		}
	}

	/**
	 * Get Widget Wrangler's database version
	 *
	 * @return string
	 */
	public static function getDbVersion() {
		// add db version if missing
		$db_version = get_option( 'ww_db_version', FALSE );

		if ( !$db_version ) {
			update_option('ww_db_version', WW_DB_VERSION );
			$db_version = WW_DB_VERSION;
		}

		return $db_version;
	}

	/**
	 * Get an array of update methods that need to be executed.
	 *
	 * @return array
	 */
	public static function getUpdates() {
		$old_version = self::getDbVersion();
		$updates = array();

		if ( version_compare( $old_version, WW_DB_VERSION, '<') ) {
			$difference = (int) WW_DB_VERSION - (int) $old_version;

			for ( $i = 1; $i <= $difference; $i++ ) {
				$next_version = ( (int) $old_version + $i );
				$next_update = '\WidgetWrangler\Updates::update'. $next_version;

				if ( is_callable( $next_update ) ) {
					$updates[ $next_version ] = $next_update;
				}
			}
		}

		return $updates;
	}

	/**
	 * Installation.
	 */
	public static function install() {
		include_once  WW_PLUGIN_DIR.'/admin/Admin.php';

		$settings = Settings::instance();
		add_option('ww_settings', $settings->default_settings);
		add_option('ww_version', WW_VERSION);
		add_option('ww_db_version', WW_DB_VERSION);
		Extras::ensureTable();
		Presets::installCore();
	}

	/**
	 * Simple check whether or not WW was previously_pro
	 *
	 * @return bool
	 */
	public static function previouslyPro() {
		$status = get_option('ww_pro_license_status', FALSE );
		if ( ! $status ) {
			return FALSE;
		}

		return (isset($status->license) && $status->license === "valid") ? TRUE : FALSE;
	}
	
	/**
	 * Big upgrade with a lot of 1-time changes
	 */
	public static function update2000() {
		$old_version = get_option( 'ww_version', WW_VERSION );

		if ( version_compare( $old_version, 2, '>=' ) ) {
			return;
		}

		// check to make sure array options aren't over serialized
		$options = array('ww_default_widgets', 'ww_postspage_widgets', 'ww_settings', 'ww_sidebars');
		foreach ($options as $option){
			if ($v = get_option($option)){
				$v = maybe_unserialize($v);
				update_option($option, $v);
			}
		}

		$settings = Settings::instance();

		// add new default settings
		foreach ( $settings->default_settings as $key => $value ) {
			if  ($key != "theme_compat" && !isset( $settings->{$key} ) ) {
				$settings->{$key} = $value;
			}
		}

		if ( isset( $settings->advanced ) ) {
			$settings->advanced_capability = $settings['advanced'];
			unset( $settings->advanced );
		}

		// enable legacy template suggestions
		$settings->legacy_template_suggestions = 1;
		$settings->save();

		// save the previous main version number for later use
		if ( !get_option( 'ww_previous_main_version', '' ) ) {
			add_option('ww_previous_main_version', 1, '', 'no' );
		}
		else {
			update_option('ww_previous_main_version', 1 );
		}
	}

	/**
	 * Migration after abandonment of "Pro" version
	 */
	public static function update2001() {
		// only modifications are for versions that used to be PRO
		if ( get_option('ww_pro_license_status', FALSE ) && self::previouslyPro() ){
			$settings = Settings::instance();

			// this site used to be a paid-for WW Pro version
			$settings->previously_pro = 1;

			// enable the override html legacy feature
			$settings->override_elements_enabled = 1;

			$settings->save();
		}

		delete_option('ww_pro_license_status');
	}

	/**
	 * Fix corrupted installs with presets missing the admin class.
	 */
	public static function update2002() {
		$where = array(
			'type' => 'preset',
			'variety' => 'core',
			'extra_key' => 'default',
		);
		$default = Extras::get($where);
		$default->data = maybe_unserialize($default->data);
		Extras::update($default, $where);

		$where['extra_key'] = 'postspage';
		$postspage = Extras::get($where);
		$postspage->data = maybe_unserialize($postspage->data);
		Extras::update($postspage, $where);
	}

}
