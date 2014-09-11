<?php
/**
 * Handles options access.
 */
class Wdgpo_Options {
	/**
	 * Gets a single option from options storage.
	 */
	function get_option ($key=false) {
		$opts = get_option('wdgpo');
		return $key ? @$opts[$key] : $opts;
	}

	/**
	 * Sets all stored options.
	 */
	function set_options ($opts) {
		return WP_NETWORK_ADMIN ? update_site_option('wdgpo', $opts) : update_option('wdgpo', $opts);
	}

	function set_token ($token) {
		return WP_NETWORK_ADMIN ? update_site_option('wdgpo_token', $token) : update_option('wdgpo_token', $token);
	}

	function get_token () {
		$blog_token = get_option('wdgpo_token');
		if (!is_multisite()) return $blog_token;
		if ($blog_token) return $blog_token;
		return get_site_option('wdgpo_token');
	}

	/**
	 * Populates options key for storage.
	 *
	 * @static
	 */
	function populate () {
		$site_opts = get_site_option('wdgpo');
		$site_opts = is_array($site_opts) ? $site_opts : array();

		$opts = get_option('wdgpo');
		$opts = is_array($opts) ? $opts : array();

		$res = array_merge($site_opts, $opts);
		update_option('wdgpo', $res);
	}

}