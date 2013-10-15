<?php
/*
Plugin Name: Leaflet Maps Marker &reg;
Plugin URI: http://www.mapsmarker.com
Description: Pin, organize & show your favorite places & tracks through OpenStreetMap, Google Maps, Google Earth (KML), Bing Maps, APIs or Augmented-Reality browsers
Tags: map, maps, Leaflet, OpenStreetMap, geoJSON, json, jsonp, OSM, travelblog, opendata, open data, opengov, open government, ogdwien, WMTS, geoRSS, location, geo, geo-mashup, geocoding, geolocation, travel, mapnick, osmarender, cloudmade, mapquest, geotag, geocaching, gpx, OpenLayers, mapping, bikemap, coordinates, geocode, geocoding, geotagging, latitude, longitude, position, route, tracks, google maps, googlemaps, gmaps, google map, google map short code, google map widget, google maps v3, google earth, gmaps, ar, augmented-reality, wikitude, wms, web map service, geocache, geocaching, qr, qr code, fullscreen, marker, marker icons, layer, multiple markers, karte, blogmap, geocms, geographic, routes, tracks, directions, navigation, routing, location plan, YOURS, yournavigation, ORS, openrouteservice, widget, bing, bing maps, microsoft, map short code, map widget, kml, cross-browser, fully documented, traffic, bike lanes, map short code, custom marker text, custom marker icons and text, gpx
Version: 3.6.6
Author: Robert Harm
Author URI: http://www.harm.co.at
Donate link: http://www.mapsmarker.com/donations
Requires at least: 3.0
Tested up to: 3.6
Requires at least PHP 5.2
Copyright 2011-2013 - @RobertHarm - All rights reserved
MapsMarker &reg; - registration pending
Parts of this plugin were originally based on the Leaflet Plugin by Hind (Copyright 2011)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License v2 as published by
the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You have received a copy of the full GNU General Public License
along with this program (see file licence-gpl20.txt)
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-maps-marker.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
global $wp_version;
if (version_compare($wp_version,"3.0","<")){
	exit('[Leaflet Maps Marker Plugin - installation failed!]: WordPress Version 3.0 or higher is needed for this plugin (you are using version '.$wp_version.') - please upgrade your WordPress installation!');
}
if (version_compare(phpversion(),"5.2","<")){
	exit('[Leaflet Maps Marker Plugin - installation failed]: PHP 5.2 is needed for this plugin (you are using PHP '.phpversion().'; note: support for PHP 4 has been officially discontinued since 2007-12-31!) - please upgrade your PHP installation!');
}
//info: die if pro version is active
include_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'plugin.php' );
if (is_plugin_active('leaflet-maps-marker-pro/leaflet-maps-marker.php') ) {
	if (!is_multisite()) {
		exit('Too bad you want to use the free version again :-( Please deactivate "Leaflet Maps Marker Pro" first before downgrading to the free version!<br/>Please tell us what we can do to win you as a happy pro user at <a href="http://www.mapsmarker.com/feedback" target="_blank">www.mapsmarker.com/feedback</a> and receive a discount voucher!');
	} else {
		if (is_network_admin()) {
			echo 'Network wide activation of the plugin "Leaflet Maps Marker" failed as the plugin "Leaflet Maps Marker Pro" is still active on subsites. Please activate "Leaflet Maps Marker" on desired subsites only!<br/>Please tell us what we can do to win you as a happy pro user at <a href="http://www.mapsmarker.com/feedback" target="_blank">www.mapsmarker.com/feedback</a> and receive a discount voucher!<br/><br/>';
		} else {
			echo 'Too bad you want to use the free version again :-( Please deactivate "Leaflet Maps Marker Pro" first before downgrading to the free version!<br/>Please tell us what we can do to win you as a happy pro user at <a href="http://www.mapsmarker.com/feedback" target="_blank">www.mapsmarker.com/feedback</a> and receive a discount voucher!<br/><br/>';
		}
	}
}
//info: define necessary paths and urls
if ( ! defined( 'LEAFLET_WP_ADMIN_URL' ) )
	define( 'LEAFLET_WP_ADMIN_URL', get_admin_url() );
if ( ! defined( 'LEAFLET_PLUGIN_URL' ) )
	define ("LEAFLET_PLUGIN_URL", plugin_dir_url(__FILE__));
if ( ! defined( 'LEAFLET_PLUGIN_DIR' ) )
	define ("LEAFLET_PLUGIN_DIR", plugin_dir_path(__FILE__));
$lmm_upload_dir = wp_upload_dir();
if ( ! defined( 'LEAFLET_PLUGIN_ICONS_URL' ) )
	define ("LEAFLET_PLUGIN_ICONS_URL", $lmm_upload_dir['baseurl'] . "/leaflet-maps-marker-icons");
if ( ! defined( 'LEAFLET_PLUGIN_ICONS_DIR' ) )
	define ("LEAFLET_PLUGIN_ICONS_DIR", $lmm_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons");
class Leafletmapsmarker
{
	function __construct() {
		global $wp_version;
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		add_action('init', array(&$this, 'lmm_load_translation_files'),1);
		add_action('admin_init', array(&$this, 'lmm_load_settings_class'),2);
		add_action('admin_init', array(&$this, 'lmm_install_and_updates'),3); //info: register_action_hook not used as otherwise Wordpress Network installs break
		add_action('wp_enqueue_scripts', array(&$this, 'lmm_frontend_enqueue_scripts') );
		add_action('wp_print_styles', array(&$this, 'lmm_frontend_enqueue_stylesheets'),4);
		add_action('admin_menu', array(&$this, 'lmm_admin_menu'),5);
		add_action('admin_init', array(&$this, 'lmm_plugin_meta_links'),6);
		//info: override max image width in popups
		if ( version_compare( $wp_version, '3.3', '<' ) ) {
			add_action('wp_head', array(&$this, 'lmm_image_css_override'),1000);
		}
		add_action('admin_bar_menu', array(&$this, 'lmm_add_admin_bar_menu'),149);
		if ( !empty($lmm_options) ) { //info: needed to suppress warning when reseting settings
			add_shortcode($lmm_options['shortcode'], array(&$this, 'lmm_showmap'));
		}
		add_filter('widget_text', 'do_shortcode'); //info: needed for widgets
		if ( isset($lmm_options['misc_global_admin_notices']) && ($lmm_options['misc_global_admin_notices'] == 'show') ){
			add_action('admin_notices', array(&$this, 'lmm_compatibility_checks'));
		}
		if ( !empty($lmm_options) ) { //info: needed to suppress warning when reseting settings
			if ($lmm_options['misc_add_georss_to_head'] == 'enabled') {
				add_action( 'wp_head', array( &$this, 'lmm_add_georss_to_head' ) );
			}
		}

		if ( isset($lmm_options['misc_tinymce_button']) && ($lmm_options['misc_tinymce_button'] == 'enabled') ) {
			require_once( plugin_dir_path( __FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'tinymce-plugin.php' );
		}
		if ( isset($lmm_options['misc_plugin_language']) && ($lmm_options['misc_plugin_language'] != 'automatic') ){
			add_filter('plugin_locale', array(&$this,'lmm_set_plugin_locale'), 'lmm');
		}
		add_action('widgets_init', create_function('', 'return register_widget("Class_leaflet_recent_marker_widget");'));
		if ( isset($lmm_options['misc_admin_dashboard_widget']) && ($lmm_options['misc_admin_dashboard_widget'] == 'enabled') ){
			if ( !is_multisite() ) {
				add_action('wp_dashboard_setup', array( &$this,'lmm_register_widgets' ));
			} else {
				add_action('wp_network_dashboard_setup', array( &$this,'lmm_register_widgets' ));
				add_action('wp_dashboard_setup', array( &$this,'lmm_register_widgets' ));
			}
		}
		if ( (isset($lmm_options['misc_pointers'])) && ($lmm_options['misc_pointers'] == 'enabled') ) {
			//info: dont show update pointers on new installs
			$version_before_update = get_option('leafletmapsmarker_version_before_update');
			if ($version_before_update != '0') {
				add_action( 'admin_enqueue_scripts', array( $this, 'lmm_update_pointer_admin_scripts' ),1001);
			}
		}
		//info: add features pointers
		add_action( 'admin_enqueue_scripts', array( $this, 'lmm_feature_pointer_admin_scripts' ),1002);
		//info: multisite only - delete tables+options+files if blog deleted from network admin
		if ( is_multisite() ) {
			add_action('delete_blog', array( &$this,'lmm_delete_multisite_blog' ));
		}
		//info: check template files for do_shortcode()-action
		if ( (isset($lmm_options['misc_conditional_css_loading'])) && ($lmm_options['misc_conditional_css_loading'] == 'enabled') ){
			add_action('template_include', array( &$this,'lmm_template_check_shortcode' ));
		}
		//info: style & add extra links to plugin page
		add_action('plugin_row_meta', array( &$this,'lmm_plugins_page_add_links' ), 10, 2);
		add_action( 'admin_enqueue_scripts', array( $this, 'lmm_style_plugins_page' ));
	}
	function lmm_style_plugins_page() {
		global $pagenow;
		if ($pagenow == "plugins.php") {
			$plugin_version = get_option('leafletmapsmarker_version_pro');
			wp_register_style( 'leafletmapsmarker-plugin-styling', LEAFLET_PLUGIN_URL . 'inc/css/leafletmapsmarker-plugins-styling.css', array(), $plugin_version);
			wp_enqueue_style( 'leafletmapsmarker-plugin-styling' );
		}
	}
	function lmm_plugins_page_add_links($links, $file) {
		$plugin = plugin_basename(__FILE__);
		if ($file == $plugin) {
			$go_pro_link = '<a style="float:left;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('Upgrade to pro version for even more features - click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img style="margin-top:4px;margin-right:5px;" src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-upgrade.png" width="80" height="15" alt="go pro"></a>';
			$affiliate_link = '<a style="text-decoration:none;" title="' . esc_attr__('MapsMarker affiliate program - sign up now and receive commissions up to 50%!','lmm') . '" href="https://www.mapsmarker.com/affiliates" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-affiliates.png" width="16" height="16" alt="affiliates"></a>';
			$rate_link = '<a style="text-decoration:none;" href="http://www.mapsmarker.com/reviews" target="_blank" title="' . esc_attr__('please rate this plugin on wordpress.org','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-star.png" width="16" height="16" alt="ratings"></a>';
			$translation_link = '<a href="http://translate.mapsmarker.com/projects/lmm" target="_blank" title="' . esc_attr__('translations','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-translations.png" width="16" height="16" alt="translations"></a>';
			$fbook_link = '<a href="http://facebook.com/mapsmarker" target="_blank" title="' . esc_attr__('Follow MapsMarker on Facebook','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-facebook.png" width="16" height="16" alt="facebook"></a>';
			$twitter_link = '<a href="http://twitter.com/mapsmarker" target="_blank" title="' . esc_attr__('Follow @MapsMarker on Twitter','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-twitter.png" width="16" height="16" alt="twitter"></a>';
			$googleplus_link = '<a href="http://www.mapsmarker.com/+" target="_blank" title="' . esc_attr__('Follow MapsMarker on Google+','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-google-plus.png" width="16" height="16" alt="google+"></a>';
			$rss_link = '<a href="http://feeds.feedburner.com/MapsMarker" target="_blank" title="RSS"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-rss.png" width="16" height="16" alt="rss"></a>';
			$rss_email_link = '<a href="http://feedburner.google.com/fb/a/mailverify?uri=MapsMarker" target="_blank" title="RSS (via Email)"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-rss-email.png" width="16" height="16" alt="rss-email"></a>';
			$links[] = $go_pro_link . $affiliate_link . '&nbsp;' . $rate_link . '&nbsp;' . $translation_link . '&nbsp;&nbsp;' . $fbook_link . '&nbsp;&nbsp;&nbsp;' . $twitter_link . '&nbsp;&nbsp;&nbsp;' . $googleplus_link . '&nbsp;&nbsp;&nbsp;' . $rss_link . '&nbsp;&nbsp;&nbsp;' . $rss_email_link;
		}
		return $links;
	}
	function lmm_delete_multisite_blog($blog_id) {
		switch_to_blog($blog_id);
		/* Remove tables */
		$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."leafletmapsmarker_layers`");
		$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."leafletmapsmarker_markers`");
		/*remove map icons directory for subsite*/
		$lmm_upload_dir = wp_upload_dir();
		$icons_directory = $lmm_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons" . DIRECTORY_SEPARATOR;
		if (is_dir($icons_directory)) {
			foreach(glob($icons_directory.'*.*') as $v) {
				unlink($v);
			}
			rmdir($icons_directory);
		}
	}
	function lmm_update_pointer_admin_scripts() {
		$dismissed_pointers = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$dismissed_pointers = array_flip($dismissed_pointers);
		$do_add_script = false;
		$lmm_version_new = get_option( 'leafletmapsmarker_version' );
		$version_without_dots = "lmmv" . str_replace('.', '', $lmm_version_new);

		if ( !isset($dismissed_pointers[$version_without_dots]) ) {
			$do_add_script = true;
			add_action( 'admin_print_footer_scripts', array( $this, 'lmm_update_pointer_footer_script' ) );
		}
		if ( $do_add_script ) {
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_style( 'wp-pointer' );
		}
	}
	function lmm_update_pointer_footer_script() {
		$lmm_version_new = get_option( 'leafletmapsmarker_version' );
		$version_without_dots = "lmmv" . str_replace('.', '', $lmm_version_new);
		$pointer_content = '<h3>' . sprintf(esc_attr__('Leaflet Maps Marker plugin update to v%1s was successful','lmm'), $lmm_version_new) . '</h3>';
		$changelog_url = '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_markers' . '" style="text-decoration:none;">' . __('changelog','lmm') . '</a>';
		$blogpost_url = '<a href="http://www.mapsmarker.com/v' . $lmm_version_new . '" target="_blank" style="text-decoration:none;">mapsmarker.com</a>';
		$pointer_content .= '<p>' . sprintf(esc_attr__('Please see the %1s for new features or the blog post on %2s for more details','lmm'), $changelog_url, $blogpost_url) . '</p>';
		$pointer_content .= '<hr noshade size="1"><p><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-upgrade.png"></a></p>';
		$pointer_content .= '<p><a style="background:#f99755;display:block;padding:5px;text-decoration:none;color:#2702c6;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('Upgrade to pro version for even more features - click here to find out how you can start a free 30-day-trial easily','lmm') . '</a></p>';
	  ?>
		<script type="text/javascript">// <![CDATA[
		jQuery(document).ready(function($) {
			if(typeof(jQuery().pointer) != 'undefined') {
				$('#toplevel_page_leafletmapsmarker_markers').pointer({
					content: '<?php echo $pointer_content; ?>',
					position: {
						edge: 'left',
						align: 'center'
					},
					close: function() {
						$.post( ajaxurl, {
							pointer: '<?php echo $version_without_dots; ?>',
							action: 'dismiss-wp-pointer'
						});
					}
				}).pointer('open');
			}
		});
		// ]]></script>
		<?php
	}
	function lmm_feature_pointer_admin_scripts() {
		$dismissed_pointers = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$dismissed_pointers = array_flip($dismissed_pointers);
		$do_add_script = false;
		//info: add new feature pointer IDs below
		if ( !isset($dismissed_pointers["lmmesw"]) ) {
			$do_add_script = true;
			add_action( 'admin_print_footer_scripts', array( $this, 'lmm_feature_pointer_footer_script' ) );
		}
		if ( $do_add_script ) {
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_style( 'wp-pointer' );
		}
	}
	function lmm_feature_pointer_footer_script() {
    	include('inc' . DIRECTORY_SEPARATOR . 'feature-pointers.php');
	}
	function lmm_register_widgets(){
		wp_add_dashboard_widget( 'lmm-admin-dashboard-widget', __('Leaflet Maps Marker - recent markers','lmm'), array( &$this,'lmm_dashboard_widget'), array( &$this,'lmm_dashboard_widget_control'));
	}
	function lmm_dashboard_widget(){
		global $wpdb;
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
		$widgets = get_option( 'dashboard_widget_options' );
		$widget_id = 'lmm-admin-dashboard-widget';
		$number_of_markers =  isset( $widgets[$widget_id] ) && isset( $widgets[$widget_id]['items'] ) ? absint( $widgets[$widget_id]['items'] ) : 4;
		$result = $wpdb->get_results($wpdb->prepare("SELECT ID,markername,icon,createdon,createdby FROM $table_name_markers ORDER BY createdon desc LIMIT %d", $number_of_markers), ARRAY_A);
		echo '<p><a style="background:#f99755;display:block;padding:5px;text-decoration:none;color:#2702c6;text-align:center;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('Upgrade to pro version for even more features - click here to find out how you can start a free 30-day-trial easily','lmm') . '</a><hr style="border:0;height:1px;background-color:#d8d8d8;"/></p>';
		if ($result != NULL) {
			echo '<table style="margin-bottom:5px;"><tr>';
			foreach ($result as $row ) {
				$icon = ($row['icon'] == NULL) ? LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png' : LEAFLET_PLUGIN_ICONS_URL . '/' . $row['icon'];
				echo '<td><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker&id=' . $row['ID'] . '" title="' . esc_attr__('edit marker','lmm') . '"><img src="' . $icon . '" style="width:80%;"></a>';
				echo '<td style="vertical-align:top;line-height:1.2em;">';
				echo '<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker&id=' . $row['ID'] . '" title="' . esc_attr__('edit marker','lmm') . '">'.htmlspecialchars(stripslashes($row['markername'])).'</a><br/>' . __('created on','lmm') . ' ' . date("Y-m-d - h:m", strtotime($row['createdon'])) . ', ' . __('created by','lmm') . ' ' . $row['createdby'];
				echo '</td></tr>';
			}
			echo '</table>';
		} else {
			echo '<p style="margin-bottom:5px;">' . __('No marker created yet','lmm') . '</p>';
		}
		if  ( !isset($widgets[$widget_id]['blogposts']) ) {
			$show_rss = 1;
		} else if ( isset($widgets[$widget_id]['blogposts']) && ($widgets[$widget_id]['blogposts'] == 1) ) {
			$show_rss = 0;
		} else {
			$show_rss = 1;
		}
		if ($show_rss == 1)	{
				require_once(ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'class-simplepie.php');
				$feed = new SimplePie();
				if ( file_exists(LEAFLET_PLUGIN_ICONS_DIR . DIRECTORY_SEPARATOR . 'readme-icons.txt') ) {
					$feed->enable_cache(true);
					$feed->set_cache_location($location = LEAFLET_PLUGIN_ICONS_DIR);
					$feed->set_cache_duration(86400);
				} else {
					$feed->enable_cache(false);
				}
				$feed->set_feed_url('http://feeds.feedburner.com/MapsMarker');
				$feed->set_stupidly_fast(true);
				$feed->enable_order_by_date(true);
				$feed->init();
				$feed->handle_content_type();
				echo '<hr style="border:0;height:1px;background-color:#d8d8d8;"/><strong><p>' . __('Latest blog posts from www.mapsmarker.com','lmm') . '</p></strong>';
				if ($feed->get_items() == NULL) {
					$blogpost_url = '<a href="http://www.mapsmarker.com/news" target="_blank">http://www.mapsmarker.com/news</a>';
					echo sprintf(__('Feed could not be retrieved, please try again later or read the latest blog posts at %s','lmm'),$blogpost_url);
				}
				foreach ($feed->get_items(0,3) as $item) {
					echo '<p>' . $item->get_date('j F Y') . ': <strong><a href="' . $item->get_permalink() . '?ref=dashboard">' . $item->get_title() . '</a></strong><br/>' . $item->get_description() . '</p>'.PHP_EOL;
				}
				echo '<p><a style="text-decoration:none;" href="http://www.mapsmarker.com" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-website-home.png" width="16" height="16" alt="mapsmarker.com"> MapsMarker.com</a>&nbsp;<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade' . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-up16.png" width="16" height="16" alt="upgrade to pro"> ' . __('Upgrade to Pro','lmm') . '</a>&nbsp;<a style="text-decoration:none;" title="' . esc_attr__('MapsMarker affiliate program - sign up now and receive commissions up to 50%!','lmm') . '" href="https://www.mapsmarker.com/affiliates" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-affiliates.png" width="16" height="16" alt="affiliates"> ' . __('Affiliates','lmm') . '</a>&nbsp;<a style="text-decoration:none;" href="http://www.mapsmarker.com/reviews" target="_blank" title="' . esc_attr__('please rate this plugin on wordpress.org','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-star.png" width="16" height="16" alt="ratings"> ' . __('rate plugin','lmm') . '</a>&nbsp;<a href="http://translate.mapsmarker.com/projects/lmm" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-translations.png" width="16" height="16" alt="translations"> ' . __('translations','lmm') . '</a> <a href="http://twitter.com/mapsmarker" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-twitter.png" width="16" height="16" alt="twitter">&nbsp;Twitter</a>&nbsp;<a href="http://facebook.com/mapsmarker" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-facebook.png" width="16" height="16" alt="facebook"> Facebook</a>&nbsp;<a href="http://www.mapsmarker.com/+" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-google-plus.png" width="16" height="16" alt="google+"> Google+</a>&nbsp;<a style="text-decoration:none;" href="http://www.mapsmarker.com/changelog" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-changelog-header.png" width="16" height="16" alt="changelog"> ' . __('Changelog','lmm') . '</a>&nbsp;<a href="http://feeds.feedburner.com/MapsMarker" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-rss.png" width="16" height="16" alt="rss"> RSS</a>&nbsp;<a href="http://feedburner.google.com/fb/a/mailverify?uri=MapsMarker" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-rss-email.png" width="16" height="16" alt="rss-email"> ' . __('E-Mail','lmm') . '</a></p>';
		}
	}
	function lmm_dashboard_widget_control(){
		$widget_id = 'lmm-admin-dashboard-widget';
		$form_id = 'lmm-admin-dashboard-widget-control';
		$update = false;
		if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
		  $widget_options = array();
		if ( !isset($widget_options[$widget_id]) ) {
		//info: set default value
		  $widget_options[$widget_id] = array(
				'blogposts' => 0,
				'items' => 5
		  );
		  $update = true;
		}
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST[$form_id]) ) {
		  $number = ($_POST[$form_id]['items'] == NULL) ? '3' : absint( $_POST[$form_id]['items'] );
		  //$number = absint( $_POST[$form_id]['items'] );
		  $blogposts = isset($_POST[$form_id]['blogposts']) ? '1' : '0';
		  $widget_options[$widget_id]['items'] = $number;
		  $widget_options[$widget_id]['blogposts'] = $blogposts;
		  $update = true;
		}
		if($update) update_option( 'dashboard_widget_options', $widget_options );
		$number = isset( $widget_options[$widget_id]['items'] ) ? (int) $widget_options[$widget_id]['items'] : '';
		echo '<p><label for="lmm-admin-dashboard-widget-number">' . __('Number of markers to show:') . ' </label>';
		echo '<input id="lmm-admin-dashboard-widget-number" name="'.$form_id.'[items]" type="text" value="' . $number . '" size="2" /></p>';
		echo '<p><label for="lmm-admin-dashboard-widget-blogposts">' . __('Hide blog posts and link section:') . ' </label>';
		echo '<input id="lmm-admin-dashboard-widget-blogposts" name="'.$form_id.'[blogposts]" type="checkbox" ' . checked($widget_options[$widget_id]['blogposts'],1,false) . '/></p>';
	}
	function lmm_load_translation_files() {
		load_plugin_textdomain('lmm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}
	function lmm_set_plugin_locale( $lang ) {
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		global $locale;
		if ($lmm_options['misc_plugin_language_area'] == 'backend') {
			return is_admin() ? $lmm_options['misc_plugin_language'] : $locale;
		} else if ($lmm_options['misc_plugin_language_area'] == 'frontend') {
			return is_admin() ? $locale : $lmm_options['misc_plugin_language'];
		} else if ($lmm_options['misc_plugin_language_area'] == 'both') {
			return $lmm_options['misc_plugin_language'];
		} else {
			return $locale;
		}
	}
	function lmm_compatibility_checks() {
		include('inc' . DIRECTORY_SEPARATOR . 'compatibility-checks.php');
	}
	function lmm_help() {
		include('leaflet-help-credits.php');
	}
	function lmm_settings() {
		global $lmm_options_class;
		$lmm_options_class->display_page();
	}
	function lmm_list_layers() {
		include('leaflet-list-layers.php');
	}
	function lmm_list_markers() {
		include('leaflet-list-markers.php');
	}
	function lmm_layer() {
		include('leaflet-layer.php');
	}
	function lmm_marker() {
		include('leaflet-marker.php');
	}
	function lmm_tools() {
		include('leaflet-tools.php');
	}
	function lmm_add_georss_to_head() {
		$georss_to_head = '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' GeoRSS-Feed" href="' . LEAFLET_PLUGIN_URL . 'leaflet-georss.php?layer=all" />'.PHP_EOL;
		echo $georss_to_head;
	}
	function lmm_showmap($atts) {
		require('inc' . DIRECTORY_SEPARATOR . 'showmap.php');
		return $lmm_out;
	}
	function lmm_load_settings_class() {
		if ( is_admin() ) {
			require_once( plugin_dir_path( __FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'class-leaflet-options.php' );
			global $lmm_options_class;
			$lmm_options_class = new Class_leaflet_options();
		}
	}
	function lmm_admin_menu() {
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		if ( !empty($lmm_options) ) { //info: needed to suppress warning when reseting settings
			$capabilities = $lmm_options[ 'capabilities_edit' ];
		} else {
			$capabilities = 'edit_posts';
		}
		$page = add_object_page('Maps Marker', 'Maps Marker', $capabilities, 'leafletmapsmarker_markers', array(&$this, 'lmm_list_markers'), LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-page.png' );
		if ( !empty($lmm_options) ) { //info: needed to suppress warning when reseting settings
			$page2 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('List all markers', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-list.png"> ' . __('List all markers', 'lmm'), $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_markers', array(&$this, 'lmm_list_markers') );
			$page3 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('add/edit marker', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-add.png"> ' . __('Add new marker', 'lmm'), $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_marker', array(&$this, 'lmm_marker') );
			$page4 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('List all layers', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-list.png"> ' . __('List all layers', 'lmm'), $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_layers', array(&$this, 'lmm_list_layers') );
			$page5 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('add/edit layer', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-add.png"> ' . __('Add new layer', 'lmm'), $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_layer', array(&$this, 'lmm_layer') );
		} else {
			$page = '';
			$page2 = '';
			$page3 = '';
			$page4 = '';
			$page5 = '';
		}
		$page6 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('Tools', 'lmm'), '<hr noshade size="1"/>' . '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-tools.png"> ' . __('Tools', 'lmm'), 'activate_plugins','leafletmapsmarker_tools', array(&$this, 'lmm_tools') );
		$page7 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('Settings', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-settings.png"> ' . __('Settings', 'lmm'), 'activate_plugins','leafletmapsmarker_settings', array(&$this, 'lmm_settings') );
		if ( !empty($lmm_options) ) { //info: needed to suppress warning when reseting settings
			$page8 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('Support', 'lmm'), '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-help.png"> ' . __('Support', 'lmm'), $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_help', array(&$this, 'lmm_help') );
			$page9 = add_submenu_page('leafletmapsmarker_markers', 'www.mapsmarker.com', '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-external.png"> ' . 'mapsmarker.com', $lmm_options[ 'capabilities_edit' ], 'www_mapsmarker_com', array(&$this, 'lmm_mapsmarker_com') );
			$page10 = add_submenu_page('leafletmapsmarker_markers', 'Maps Marker - ' . __('Upgrade to Pro', 'lmm'), '<span style="background:#F99755;color:#000;padding:3px;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-up.png"> ' . __('Upgrade to Pro', 'lmm') . '&nbsp;&nbsp;&nbsp;</span>', $lmm_options[ 'capabilities_edit' ], 'leafletmapsmarker_pro_upgrade', array(&$this, 'lmm_pro_upgrade') );
		} else {
			$page8 = '';
			$page9 = '';
			$page10 = '';
		}
		//info: add javascript - leaflet.js - for admin area
		add_action('admin_print_scripts-'.$page3, array(&$this, 'lmm_admin_enqueue_scripts'),7);
		add_action('admin_print_scripts-'.$page5, array(&$this, 'lmm_admin_enqueue_scripts'),8);
		add_action('admin_print_scripts-'.$page7, array(&$this, 'lmm_admin_jquery_ui'),9);
		add_action('admin_print_scripts-'.$page10, array(&$this, 'lmm_admin_jquery_ui_accordion'),9);
		//info: add css styles for admin area
		add_action('admin_print_styles-'.$page, array(&$this, 'lmm_admin_enqueue_stylesheets'),17);
		add_action('admin_print_styles-'.$page2, array(&$this, 'lmm_admin_enqueue_stylesheets'),18);
		add_action('admin_print_styles-'.$page3, array(&$this, 'lmm_admin_enqueue_stylesheets'),19);
		add_action('admin_print_styles-'.$page4, array(&$this, 'lmm_admin_enqueue_stylesheets'),20);
		add_action('admin_print_styles-'.$page5, array(&$this, 'lmm_admin_enqueue_stylesheets'),21);
		add_action('admin_print_styles-'.$page6, array(&$this, 'lmm_admin_enqueue_stylesheets'),22);
		add_action('admin_print_styles-'.$page7, array(&$this, 'lmm_admin_enqueue_stylesheets'),23);
		add_action('admin_print_styles-'.$page8, array(&$this, 'lmm_admin_enqueue_stylesheets'),23);
		add_action('admin_print_styles-'.$page10, array(&$this, 'lmm_admin_enqueue_stylesheets'),23);
		add_action('admin_print_styles-'.$page10, array(&$this, 'lmm_admin_enqueue_stylesheets_jqueryui'),23);
		//info: add css styles for datepicker
		add_action('admin_print_styles-'.$page3, array(&$this, 'lmm_admin_enqueue_stylesheets_datepicker'),24);
		//info: add contextual help on all pages
		add_action('admin_print_scripts-'.$page, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page2, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page3, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page4, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page5, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page6, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page7, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page8, array(&$this, 'lmm_add_contextual_help'));
		add_action('admin_print_scripts-'.$page10, array(&$this, 'lmm_add_contextual_help'));
		//info: add jquery datepicker on marker page
		add_action('admin_print_scripts-'.$page3, array(&$this, 'lmm_admin_enqueue_scripts_jquerydatepicker'));
		//info: add image css override for marker+layer edit page
		add_action( 'admin_head-'. $page3, array(&$this, 'lmm_image_css_override'),1000);
		add_action( 'admin_head-'. $page5, array(&$this, 'lmm_image_css_override'),1000);
	}
	function lmm_mapsmarker_com() {
		echo '<script type="text/javascript">window.location.href = "http://www.mapsmarker.com";</script>  ';
	}
	function lmm_pro_upgrade() {
		include('leaflet-pro-upgrade.php');
	}
	function lmm_add_admin_bar_menu() {
		global $wp_version;
		if ( version_compare( $wp_version, '3.1', '>=' ) )
		{
			$lmm_options = get_option( 'leafletmapsmarker_options' );
			if ( $lmm_options[ 'admin_bar_integration' ] == 'enabled' && current_user_can($lmm_options[ 'capabilities_edit' ]) )
			{
			global $wp_admin_bar;
				$menu_items = array(
					array(
						'id' => 'lmm',
						'title' => '<img style="float:left;margin:3px 5px 0 0;" src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-tinymce.png"/></span> Maps Marker',
						'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_markers',
						'meta' => array( 'title' => 'Wordpress-Plugin ' . __('by','lmm') . ' www.mapsmarker.com' )
					),
					array(
						'id' => 'lmm-markers',
						'parent' => 'lmm',
						'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-list.png"> ' . __('List all markers','lmm'),
						'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_markers'
					),
					array(
						'id' => 'lmm-add-marker',
						'parent' => 'lmm',
						'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-add.png"> ' . __('Add new marker','lmm'),
						'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker'
					),
					array(
						'id' => 'lmm-layers',
						'parent' => 'lmm',
						'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-list.png"> ' . __('List all layers','lmm'),
						'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layers'
					),
					array(
						'id' => 'lmm-add-layers',
						'parent' => 'lmm',
						'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-add.png"> ' . __('Add new layer','lmm'),
						'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer'
					)
				);
				if ( current_user_can( 'activate_plugins' ) ) {
					$menu_items = array_merge($menu_items, array(
						array(
							'id' => 'lmm-tools',
							'parent' => 'lmm',
							'title' => '<hr style="margin:3px 0;" noshade size="1"/><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-tools.png"> ' . __('Tools','lmm'),
							'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools'
						),
						array(
							'id' => 'lmm-settings',
							'parent' => 'lmm',
							'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-settings.png"> ' . __('Settings','lmm'),
							'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings'
						)
					));
				}
				$menu_items = array_merge($menu_items, array(
						array(
							'id' => 'lmm-help-credits',
							'parent' => 'lmm',
							'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-help.png"> ' . __('Support','lmm'),
							'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_help'
						),
						array(
							'id' => 'lmm-plugin-website',
							'parent' => 'lmm',
							'title' => '<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-menu-external.png"> ' . 'mapsmarker.com',
							'href' => 'http://www.mapsmarker.com',
							'meta' => array( 'target' => '_blank', 'title' => __('Open plugin website','lmm') )
						),
						array(
							'id' => 'lmm-upgrade',
							'parent' => 'lmm',
							'title' => '<span style="background:#F99755;color:#000;padding:3px;text-shadow:none;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-up.png"> ' . __('Upgrade to Pro','lmm') . '&nbsp;&nbsp;</span>',
							'href' => LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade'
						)
					));

				foreach ($menu_items as $menu_item) {
					$wp_admin_bar->add_menu($menu_item);
				}
			}
		}
	}
	function lmm_add_contextual_help() {
		global $wp_version;
		$helptext = '<p>' . __('Do you have questions or issues with Leaflet Maps Marker? Please use the following support channels appropriately.','lmm') . '<br/>';
		$helptext .= '<strong>' . __('One personal request: before you post a new support ticket in the <a href="http://wordpress.org/support/plugin/leaflet-maps-marker" target="_blank">Wordpress Support Forum</a>, please follow the instructions from <a href="http://www.mapsmarker.com/readme-first" target="_blank">http://www.mapsmarker.com/readme-first</a> which give you a guideline on how to deal with the most common issues.','lmm') . '</strong></p>';
		$helptext .= '<ul>';
		$helptext .= '<li><a href="http://www.mapsmarker.com/faq/" target="_blank">' . __('FAQ','lmm') . '</a> (' . __('frequently asked questions','lmm') . ')</li>';
		$helptext .= '<li><a href="http://www.mapsmarker.com/docs/" target="_blank">' . __('Documentation','lmm') . '</a></li>';
		$helptext .= '<li><a href="http://wordpress.org/support/plugin/leaflet-maps-marker" target="_blank">WordPress Support Forum</a> (' . __('free community support','lmm') . ')</li>';
		$helptext .= '</ul>';
		$helptext .= '<a style="background:#f99755;display:block;padding:5px 5px 5px 10px;text-decoration:none;color:#2702c6;margin:10px 0;" href="' . LEAFLET_WP_ADMIN_URL .
	'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('If you want to get dedicated 1:1 support from the plugin author, please upgrade to the pro version. Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a>';
		if ( version_compare( $wp_version, '3.3', '<' ) )
		{
			global $current_screen;
			add_contextual_help( $current_screen, $helptext );
		}
		else if ( version_compare( $wp_version, '3.3', '>=' ) )
		{
			$screen = get_current_screen();
			$screen->add_help_tab( array( 'id' => 'lmm_help_tab', 'title' => __('Help & Support','lmm'), 'content' => $helptext ));
		}
	}
	function lmm_admin_jquery_ui() {
		$plugin_version = get_option('leafletmapsmarker_version');
		wp_enqueue_script( array ( 'jquery' ) );
		wp_enqueue_script( 'leafletmapsmarker-bootstrap-tabs', LEAFLET_PLUGIN_URL . 'inc/js/bootstrap-tabs.min.js', array('jquery'), $plugin_version);
		wp_enqueue_script( 'leafletmapsmarker-select2', LEAFLET_PLUGIN_URL . 'inc/js/select2/select2.min.js', array('jquery'), $plugin_version);
		wp_enqueue_style( 'leafletmapsmarker-select2', LEAFLET_PLUGIN_URL . 'inc/js/select2/select2.css', array(), $plugin_version);
		wp_localize_script('leafletmapsmarker-select2', 'mapsmarkerjs', array(
				'settings_search_placeholder' => __( 'start full-text search', 'lmm'),
				'settings_search_no_results' => __( 'No matches found', 'lmm')
		) );
	}
	function lmm_admin_jquery_ui_accordion() {
		global $wp_version;
		$plugin_version = get_option('leafletmapsmarker_version');
		if ( version_compare( $wp_version, '3.3', '<' ) )
		{
			wp_enqueue_script( 'leafletmapsmarker-jquery-ui-core', LEAFLET_PLUGIN_URL . 'inc/js/jquery.ui.core.min.js', array('jquery'), $plugin_version);
			wp_enqueue_script( 'leafletmapsmarker-jquery-ui-widget', LEAFLET_PLUGIN_URL . 'inc/js/jquery.ui.widget.min.js', array('leafletmapsmarker-jquery-ui-core'), $plugin_version);
			wp_enqueue_script( 'leafletmapsmarker-jquery-accordion', LEAFLET_PLUGIN_URL . 'inc/js/jquery.ui.accordion.min.js', array('leafletmapsmarker-jquery-ui-widget'), $plugin_version);
		}
		else if ( version_compare( $wp_version, '3.3', '>=' ) )
		{
			wp_enqueue_script( array ( 'jquery-ui-accordion' ) );
		}
	}
	function lmm_frontend_enqueue_scripts() {
		global $wp_version;
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		$plugin_version = get_option('leafletmapsmarker_version');
		if ( is_admin() ) { $gmaps_libraries = '&libraries=places'; } else { $gmaps_libraries =  ''; }
		//info: Google language localization (JSON API)
		if ($lmm_options['google_maps_language_localization'] == 'browser_setting') {
			$google_language = '';
		} else if ($lmm_options['google_maps_language_localization'] == 'wordpress_setting') {
			if ( defined('WPLANG') ) { $google_language = "&language=" . substr(WPLANG, 0, 2); } else { $google_language =  '&language=en'; }
		} else {
			$google_language = "&language=" . $lmm_options['google_maps_language_localization'];
		}
		if ($lmm_options['google_maps_base_domain_custom'] == '') {
			$gmaps_base_domain = "&base_domain=" . $lmm_options['google_maps_base_domain'];
		} else {
			$gmaps_base_domain = "&base_domain=" . $lmm_options['google_maps_base_domain_custom'];
		}
		//info: Google API key
		if ( isset($lmm_options['google_maps_api_key']) && ($lmm_options['google_maps_api_key'] != NULL) ) { $google_maps_api_key = $lmm_options['google_maps_api_key']; } else { $google_maps_api_key = ''; }
		//info: fallback for adding js to footer 1
		if ( (version_compare( $wp_version, '3.3', '>=' )) && ($lmm_options['misc_javascript_header_footer'] == 'footer') ) {
			wp_register_script( 'leafletmapsmarker-googlemaps-loader', 'https://www.google.com/jsapi?key='.$google_maps_api_key, array(), 3.7, true);
		} else if ( (version_compare( $wp_version, '3.3', '<' )) || ((version_compare( $wp_version, '3.3', '>=' )) && ($lmm_options['misc_javascript_header_footer'] == 'header')) ) {
			wp_enqueue_script( array ( 'jquery' ) );
			wp_enqueue_script( 'leafletmapsmarker-googlemaps-loader', 'https://www.google.com/jsapi?key='.$google_maps_api_key, array(), NULL);
		}
		//info: Bing culture code
		if ($lmm_options['bingmaps_culture'] == 'automatic') {
			if ( defined('WPLANG') ) { $bing_culture = WPLANG; } else { $bing_culture =  'en_us'; }
		} else {
			$bing_culture = $lmm_options['bingmaps_culture'];
		}
		//info: load leaflet.js + plugins
		//info: fallback for adding js to footer 2
		if ( (version_compare( $wp_version, '3.3', '>=' )) && ($lmm_options['misc_javascript_header_footer'] == 'footer')) {
			wp_register_script( 'leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.js', array('leafletmapsmarker-googlemaps-loader', 'jquery' ), $plugin_version, true);
			wp_register_script( 'show_map', LEAFLET_PLUGIN_URL . 'inc/js/show_map.js', array('leafletmapsmarker' ), $plugin_version, true);
		} else if ( (version_compare( $wp_version, '3.3', '<' )) || ((version_compare( $wp_version, '3.3', '>=' )) && ($lmm_options['misc_javascript_header_footer'] == 'header')) ) {
			wp_enqueue_script( 'leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.js', array('leafletmapsmarker-googlemaps-loader'), $plugin_version);
		}
		wp_localize_script('leafletmapsmarker', 'mapsmarkerjs', array(
			'zoom_in' => __( 'Zoom in', 'lmm' ),
			'zoom_out' => __( 'Zoom out', 'lmm' ),
			'googlemaps_language' => $google_language,
			'googlemaps_libraries' => $gmaps_libraries,
			'googlemaps_base_domain' => $gmaps_base_domain,
			'bing_culture' => $bing_culture
			) );
	}
	function lmm_admin_enqueue_scripts() {
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		$plugin_version = get_option('leafletmapsmarker_version');
		if ( is_admin() ) { $gmaps_libraries = '&libraries=places'; } else { $gmaps_libraries =  ''; }
		if ( defined('WPLANG') ) { $lang = substr(WPLANG, 0, 2); } else { $lang =  'en'; }
		//info: Google language localization (JSON API)
		if ($lmm_options['google_maps_language_localization'] == 'browser_setting') {
			$google_language = '';
		} else if ($lmm_options['google_maps_language_localization'] == 'wordpress_setting') {
			if ( defined('WPLANG') ) { $google_language = "&language=" . substr(WPLANG, 0, 2); } else { $google_language =  '&language=en'; }
		} else {
			$google_language = "&language=" . $lmm_options['google_maps_language_localization'];
		}
		if ($lmm_options['google_maps_base_domain_custom'] != '') {
			$gmaps_base_domain = "&base_domain=" . $lmm_options['google_maps_base_domain'];
		} else {
			$gmaps_base_domain = "&base_domain=" . $lmm_options['google_maps_base_domain_custom'];
		}
		wp_enqueue_script( array ( 'jquery' ) );
		//info: Google API key
		if ( isset($lmm_options['google_maps_api_key']) && ($lmm_options['google_maps_api_key'] != NULL) ) { $google_maps_api_key = $lmm_options['google_maps_api_key']; } else { $google_maps_api_key = ''; }
		wp_enqueue_script( 'leafletmapsmarker-googlemaps-loader', 'https://www.google.com/jsapi?key='.$google_maps_api_key, array(), NULL);
		//info: Bing culture code
		if ($lmm_options['bingmaps_culture'] == 'automatic') {
			if ( defined('WPLANG') ) { $bing_culture = WPLANG; } else { $bing_culture =  'en_us'; }
		} else {
			$bing_culture = $lmm_options['bingmaps_culture'];
		}
		//info: load leaflet.js + plugins
		wp_enqueue_script( 'leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.js', array('leafletmapsmarker-googlemaps-loader'), $plugin_version);
		wp_localize_script('leafletmapsmarker', 'mapsmarkerjs', array(
			'zoom_in' => __( 'Zoom in', 'lmm' ),
			'zoom_out' => __( 'Zoom out', 'lmm' ),
			'googlemaps_language' => $google_language,
			'googlemaps_libraries' => $gmaps_libraries,
			'googlemaps_base_domain' => $gmaps_base_domain,
			'bing_culture' => $bing_culture
			) );
	}
	function lmm_image_css_override() {
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		echo '<style type="text/css" id="leafletmapsmarker-image-css-override">.leaflet-popup-content img { max-width:' . intval($lmm_options['defaults_marker_popups_image_max_width']) . 'px !important; height:auto; margin: 0px !important; padding: 0px !important; box-shadow:none !important; width:auto !important; }</style>';
	}
	function lmm_admin_enqueue_scripts_jquerydatepicker() {
		$plugin_version = get_option('leafletmapsmarker_version');
		wp_enqueue_script( array ( 'jquery', 'jquery-ui-tabs','jquery-ui-datepicker','jquery-ui-slider' ) );
		wp_enqueue_script( 'jquery-ui-timepicker-addon', LEAFLET_PLUGIN_URL . 'inc/js/jquery-ui-timepicker-addon.js', array('jquery', 'jquery-ui-tabs','jquery-ui-datepicker'), $plugin_version);
	}
	function lmm_frontend_enqueue_stylesheets() {
		//info: conditional loading of css files
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		if ( (isset($lmm_options['misc_conditional_css_loading'])) && ($lmm_options['misc_conditional_css_loading'] == 'enabled') ){
				global $wp_query, $wp_version;
				$posts = $wp_query->posts;
				$pattern = get_shortcode_regex();

				$plugin_version = get_option('leafletmapsmarker_version');
				global $wp_styles;
				wp_register_style('leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.css', array(), $plugin_version);
				wp_register_style('leafletmapsmarker-ie-only', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.ie.css', array(), $plugin_version);

				if (is_array($posts)) {
					foreach ($posts as $post) {
						if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( $lmm_options['shortcode'], $matches[2] ) ) {
							wp_enqueue_style('leafletmapsmarker');
							wp_enqueue_style('leafletmapsmarker-ie-only');
							$wp_styles->add_data('leafletmapsmarker-ie-only', 'conditional', 'lt IE 9');
							break;
						}
					}
					//info: override max image width in popups
					if ( version_compare( $wp_version, '3.3', '>=' ) ) {
						$lmm_custom_css = ".leaflet-popup-content img { max-width:" . intval($lmm_options['defaults_marker_popups_image_max_width']) . "px !important; height:auto; margin: 0px !important; padding: 0px !important; box-shadow:none !important; width:auto !important; }";
						wp_add_inline_style('leafletmapsmarker',$lmm_custom_css);
					}
				}
		} else {
				global $wp_styles, $wp_version;
				$plugin_version = get_option('leafletmapsmarker_version');
				wp_register_style('leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.css', array(), $plugin_version);
				wp_enqueue_style('leafletmapsmarker');
				wp_register_style('leafletmapsmarker-ie-only', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.ie.css', array(), $plugin_version);
				wp_enqueue_style('leafletmapsmarker-ie-only');
				$wp_styles->add_data('leafletmapsmarker-ie-only', 'conditional', 'lt IE 9');
				//info: override max image width in popups
				if ( version_compare( $wp_version, '3.3', '>=' ) ) {
					$lmm_custom_css = ".leaflet-popup-content img { max-width:" . intval($lmm_options['defaults_marker_popups_image_max_width']) . "px !important; height:auto; margin: 0px !important; padding: 0px !important; box-shadow:none !important; width:auto !important; }";
					wp_add_inline_style('leafletmapsmarker',$lmm_custom_css);
				}
		}
	}
	function lmm_template_check_shortcode( $template ) {
		$lmm_options = get_option( 'leafletmapsmarker_options' );
		$searchterm = '[' . $lmm_options['shortcode'];
		$files = array( $template, get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'header.php', get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'footer.php' );
		foreach( $files as $file ) {
			if( file_exists($file) ) {
				$contents = file_get_contents($file);
				if( strpos( $contents, $searchterm )  ) {
					global $wp_styles;
					$plugin_version = get_option('leafletmapsmarker_version');
					wp_register_style('leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.css', array(), $plugin_version);
					wp_enqueue_style('leafletmapsmarker');
					wp_register_style('leafletmapsmarker-ie-only', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.ie.css', array(), $plugin_version);
					wp_enqueue_style('leafletmapsmarker-ie-only');
					$wp_styles->add_data('leafletmapsmarker-ie-only', 'conditional', 'lt IE 9');
					break;
				}
			}
		}
		return $template;
	}
	function lmm_admin_enqueue_stylesheets() {
		global $wp_styles;
		$plugin_version = get_option('leafletmapsmarker_version');
		wp_register_style( 'leafletmapsmarker', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.css', array(), $plugin_version);
		wp_enqueue_style( 'leafletmapsmarker' );
		wp_register_style( 'leafletmapsmarker-admin', LEAFLET_PLUGIN_URL . 'inc/css/leafletmapsmarker-admin.css', array(), $plugin_version);
		wp_enqueue_style('leafletmapsmarker-admin' );
		wp_register_style('leafletmapsmarker-ie-only', LEAFLET_PLUGIN_URL . 'leaflet-dist/leaflet.ie.css', array(), $plugin_version);
		wp_enqueue_style('leafletmapsmarker-ie-only');
		$wp_styles->add_data('leafletmapsmarker-ie-only', 'conditional', 'lt IE 9');
		//info: compatibility fix for flickr gallery plugin which is breaking the settings page
		if (is_plugin_active('flickr-gallery/flickr-gallery.php') ) {
			wp_dequeue_style('fg-jquery-ui');
		}
	}
	function lmm_admin_enqueue_stylesheets_jqueryui() {
		$plugin_version = get_option('leafletmapsmarker_version');
		wp_register_style( 'leafletmapsmarker-admin-upgrade', LEAFLET_PLUGIN_URL . 'inc/css/jquery_ui.css', array(), $plugin_version);
		wp_enqueue_style('leafletmapsmarker-admin-upgrade' );
	}
	function lmm_admin_enqueue_stylesheets_datepicker() {
		$plugin_version = get_option('leafletmapsmarker_version');
		wp_register_style( 'jquery-ui-all', LEAFLET_PLUGIN_URL . 'inc/css/jquery-datepicker-theme/jquery-ui-1.9.2.custom.css', array(), $plugin_version);
		wp_enqueue_style( 'jquery-ui-all' );
		wp_register_style( 'jquery-ui-timepicker-addon', LEAFLET_PLUGIN_URL . 'inc/css/jquery-datepicker-theme/jquery-ui-timepicker-addon.css', array('jquery-ui-all'), NULL );
		wp_enqueue_style( 'jquery-ui-timepicker-addon' );
	}
	function lmm_install_and_updates() {
		//info: set transient to execute install & update-routine only once a day
		$current_version = "v366"; //2do - mandatory: change on each update to new version!
		$schedule_transient = 'leafletmapsmarker_install_update_cache_' . $current_version;
		$install_update_schedule = get_transient( $schedule_transient );
		if ( $install_update_schedule === FALSE ) {
			$schedule_transient = 'leafletmapsmarker_install_update_cache_' . $current_version;
			set_transient( $schedule_transient, 'execute install and update-routine only once a day', 60*60*24 );
			include('inc' . DIRECTORY_SEPARATOR . 'install-and-updates.php');
		}
	}
	function lmm_plugin_meta_links() {
		define( 'FB_BASENAME', plugin_basename( __FILE__ ) );
		define( 'FB_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
		define( 'FB_FILENAME', str_replace( FB_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );
		function leafletmapsmarker_filter_plugin_meta($links, $file) {
			if ( $file == FB_BASENAME ) {
				array_unshift(
					$links,
					'<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_markers">'.__('Markers','lmm').'</a>',
					'<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layers">'.__('Layers','lmm').'</a>' ,
					'<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_settings">'.__('Settings','lmm').'</a>'
				);
			}
			return $links;
		}
		add_filter( 'plugin_action_links', 'leafletmapsmarker_filter_plugin_meta', 10, 2 );
	  } //info: end plugin_meta_links()
} //info: end class
$run_leafletmapsmarker = new Leafletmapsmarker();
//info: include widget class
require_once( plugin_dir_path( __FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'class-leaflet-recent-marker-widget.php' );
unset($run_leafletmapsmarker);
?>