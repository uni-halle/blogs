<?php
//Don't uninstall unless you absolutely want to!
if ( ! defined( 'WP_UNINSTALL_PLUGIN'  ) ) {
	wp_die( 'WP_UNINSTALL_PLUGIN undefined.' );
}

$users	=	get_users( array(
	'blog_id'	=>	'',
	'fields'	=>	array( 'ID', 'user_login' )
) );

foreach ( $users as $user ) {
	@wp_update_user( array(
		'ID'			=>	$user->ID,
		'user_nicename'	=>	sanitize_title( $user->user_login )
	) );
}


/* Goodbye! Thank you for having me! */


/* End of file uninstall.php */
/* Location: ./wp-content/plugins/wp-author-slug/uninstall.php */