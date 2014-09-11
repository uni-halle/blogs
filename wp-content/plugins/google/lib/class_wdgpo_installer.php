<?php
/**
 * Installs the database, if it's not already present.
 */
class Wdgpo_Installer {

	/**
	 * @public
	 * @static
	 */
	function check () {
		$is_installed = get_site_option('wdgpo', false);
		$is_installed = $is_installed ? $is_installed : get_option('wdgpo', false);
		if (!$is_installed) Wdgpo_Installer::install();
	}

	/**
	 * @private
	 * @static
	 */
	function install () {
		$me = new Wdgpo_Installer;
		$me->create_default_options();
	}

	/**
	 * @private
	 */
	function create_default_options () {
		update_site_option('wdgpo', array (
			'show_count' => 1,
			'front_page' => 1,
			'position' => 'top',
			'appearance' => 'standard',
		));
	}
}