<?php

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

function wptouch_is_controlled_network() {
	$bncid_info = wptouch_get_settings( 'bncid' );

	return ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) && $bncid_info->multisite_control );
}

function wptouch_is_multisite_enabled() {
	return is_multisite();
}

function wptouch_is_multisite_primary() {
	global $blog_id;
	return ( $blog_id == 1 );
}

function wptouch_is_multisite_secondary() {
	if ( wptouch_is_multisite_enabled() ) {
		global $blog_id;

		return ( $blog_id > 1 );
	} else {
		return false;
	}
}

function wptouch_get_multsite_aware_install_path( $suffix ) {
	if ( wptouch_is_controlled_network() ) {
		return WPTOUCH_BASE_CONTENT_MS_DIR . '/' . $suffix;
	} else {
		return WPTOUCH_BASE_CONTENT_DIR . '/' . $suffix;
	}
}

function wptouch_is_network_available( $type, $check_product = false ) {
	if ( $type == 'theme' && !$check_product ) {
		global $wptouch_cur_theme;
		$check_product = $wptouch_cur_theme;
	} elseif ( !$check_product ) {
		global $wptouch_cur_addon;
		$check_product = $wptouch_cur_addon;
	}

	if ( $check_product ) {
		if ( !is_multisite() ) {
			return true;
		} else {
			if ( $check_product->location != 'cloud' ) {
				return true;
			} elseif ( !wptouch_is_controlled_network() || ( wptouch_is_controlled_network() && is_network_admin() ) ) {
				return true;
			}

			return false;
		}
	}

	return false;
}
