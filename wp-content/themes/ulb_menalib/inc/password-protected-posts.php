<?php
/**
 * All functions changing password protectet posts 
 *
 * @package ulb_menalib 
 */

/*
2018_05_23 HenryG.
Hide password-protected posts
https://codex.wordpress.org/Using_Password_Protection#Customize_the_Protected_Text
*/

// Filter to hide protected posts
function exclude_protected($where) {
	global $wpdb;
	return $where .= " AND {$wpdb->posts}.post_password = '' ";
}

// Decide where to display them
function exclude_protected_action($query) {
	if( !is_single() && !is_page() && !is_admin() ) {
		add_filter( 'posts_where', 'exclude_protected' );
	}
}

// Action to queue the filter at the right time
add_action('pre_get_posts', 'exclude_protected_action');




// Change  Texts 

function password_protected_filter_gettext( $translated, $original, $domain ) {

	    if ( $translated == 'This content is password protected. To view it please enter your password below:' ) 
	    { 		
        	$translated = 'This content is password protected.';
        	
    	}

  
	    if ( $translated == 'Dieser Inhalt ist passwortgeschützt. Um ihn anzuschauen, gib dein Passwort bitte unten ein:' ) {
	        $translated = 'Dieser Inhalt ist passwortgeschützt.';
	       
	    }

    return $translated;
 
}


add_filter( 'gettext', 'password_protected_filter_gettext', 10, 3 );