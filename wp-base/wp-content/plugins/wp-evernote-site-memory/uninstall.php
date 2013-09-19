<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option( 'evernoteSiteMemoryAdminOptions' );
delete_option( 'wp_evernote_options' );
