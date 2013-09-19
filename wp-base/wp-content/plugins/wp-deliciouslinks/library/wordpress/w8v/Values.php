<?php
class w8v_Values extends b8v_Base {
	const scope_blog = 1;
	const scope_site = 2;
	private static $WP_PluginDir = null;
	public static function WP_PluginDir($value = '') {
		if (is_null ( self::$WP_PluginDir )) {
			self::$WP_PluginDir = self::abspath () . PLUGINDIR . DIRECTORY_SEPARATOR;
		}
		return self::$WP_PluginDir . $value;
	}
	private static $WP_ContentDir = null;
	public static function WP_ContentDir($value = '') {
		if (is_null ( self::$WP_ContentDir )) {
			self::$WP_ContentDir = dirname ( self::WP_PluginDir () );
		}
		return self::$WP_ContentDir . $value;
	}
	private static $WP_PluginURL = null;
	public static function WP_PluginURL($value = '') {
		if (is_null ( self::$WP_PluginURL )) {
			self::$WP_PluginURL = self::WP_SiteURL ( dirname ( PLUGINDIR ) . '/' );
		}
		return self::$WP_PluginURL . $value;
	}
	private static $WP_SiteURL = null;
	public static function WP_SiteURL($value = '') {
		if (is_null ( self::$WP_SiteURL )) {
			self::$WP_SiteURL = b8v_Type_String::staticAddEnding ( get_option ( 'siteurl' ), '/' );
		}
		return self::$WP_SiteURL . $value;
	}
	private static $WP_PluginBaseURL = 'http://wordpress.org/extend/plugins/';
	public static function WP_PluginBaseURL($value = '') {
		return self::$WP_PluginBaseURL . $value;
	}
	public static function urlFromFileame($filename) {
		return self::WP_PluginURL ( substr ( $filename, strlen ( self::WP_ContentDir () ) + 1 ) );
	}
	public static function fixPostInsert($string) {
		$string = str_replace ( "\n", '', $string );
		$string = str_replace ( "\r", '<br/>', $string );
		return $string;
	}
	private static $abspath = null;
	public static function abspath() {
		if (is_null ( self::$abspath )) {
			if (defined ( 'ABSPATH' )) {
				self::$abspath = ABSPATH;
			} else {
				//deduce abspath;
				self::$abspath = __FILE__;
				while ( ! file_exists ( self::$abspath . 'wp-load.php' ) ) {
					self::$abspath = dirname ( self::$abspath ) . DIRECTORY_SEPARATOR;
				}
			}
		}
		return self::$abspath;
	}
	private static $wp_config = null;
	public static function wp_config() {
		if (is_null ( self::$wp_config )) {
			self::$wp_config = self::abspath () . 'wp-config.php';
		}
		return self::$wp_config;
	}
	private $db_name = null;
	private $db_user = null;
	private $db_password = null;
	private $db_host = null;
}