<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cleanup Transient
delete_site_transient( 'theme_stats_data' );

// Delete legacy options
delete_site_option( 'cets_theme_info_allow' );
delete_site_option( 'cets_theme_info_data' );
delete_site_option( 'cets_theme_info_data_freshness' );