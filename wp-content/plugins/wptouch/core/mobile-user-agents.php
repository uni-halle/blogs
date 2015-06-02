<?php

/* Smartphones */
global $wptouch_smartphone_list;

$wptouch_smartphone_list = array(
	array( 'iPhone' ), 			// iPhone
	array( 'iPod', 'Mobile' ),	// iPod touch
	array( 'Android', 'Mobile' ), 					// Android devices
	array( 'Opera', 'Mini/7' ), 					// Opera Mini 7
	array( 'BB', 'Mobile Safari' ), 				// BB10 devices
	array( 'BlackBerry', 'Mobile Safari' ),			// BB 6, 7 devices
	array( 'Firefox', 'Mobile' ),					// Firefox OS devices
	'IEMobile/7.0',									// Windows Phone OS 7
	'IEMobile/8.0',									// Windows Phone OS 8
	'IEMobile/9.0',									// Windows Phone OS 9
	array( 'IEMobile/10', 'Touch' ),				// Windows IE 10 touch devices
	array( 'IEMobile/11', 'Touch' )					// Windows IE 11 touch devices
);

/* Tablets */
global $wptouch_tablet_list;

$wptouch_tablet_list = array(
	// Nothing excluded yet
);

/* Matching any of these user-agents will cause WPtouch Pro to be shown for the 'default' theme */
global $wptouch_device_classes;

$wptouch_device_classes[ 'default' ] = $wptouch_smartphone_list;

global $wptouch_exclusion_list;

$wptouch_exclusion_list = $wptouch_tablet_list;