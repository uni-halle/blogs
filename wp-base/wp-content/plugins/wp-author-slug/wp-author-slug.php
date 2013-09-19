<?php
/** wp-author-slug.php
 *
 * Plugin Name:	WP Author Slug
 * Plugin URI:	http://en.wp.obenland.it/wp-author-slug/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-author-slug
 * Description:	Rewrites the author url to NOT display the username but the display name
 * Version:		1.2.2
 * Author:		Konstantin Obenland
 * Author URI:	http://en.wp.obenland.it/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-author-slug
 * Text Domain: wp-author-slug
 * Domain Path: /lang
 * License:		GPLv2
 */


if ( ! class_exists( 'Obenland_Wp_Plugins_v300' ) ) {
	require_once( 'obenland-wp-plugins.php' );
}


register_activation_hook( __FILE__, array(
	'Obenland_Wp_Author_Slug',
	'activation'
) );


class Obenland_Wp_Author_Slug extends Obenland_Wp_Plugins_v300 {
	
	
	///////////////////////////////////////////////////////////////////////////
	// METHODS, PUBLIC
	///////////////////////////////////////////////////////////////////////////
	
	/**
	 * Constructor
	 *
	 * @author	Konstantin Obenland
	 * @since	1.1 - 03.04.2011
	 * @access	public
	 *
	 * @return	Obenland_Wp_Author_Slug
	 */
	public function __construct() {
		
		parent::__construct( array(
			'textdomain'		=>	'wp-author-slug',
			'plugin_path'		=>	__FILE__,
			'donate_link_id'	=>	'XVPLJZ3VH4GCN'
		));
		
		$this->hook( 'pre_user_nicename' );
	}
	
	
	/**
	 * Overwrites the users' nicenames with the users' display name
	 *
	 * Only runs on activation of plugin
	 *
	 * @author	Konstantin Obenland
	 * @since	1.0 - 19.02.2011
	 * @access	public
	 * @static
	 *
	 * @return	void
	 */
	public static function activation() {
		$users	=	get_users(array(
			'blog_id'	=>	'',
			'fields'	=>	array( 'ID', 'display_name' )
		));
		
		foreach ( $users as $user ) {
			if ( $user->display_name ) {
				@wp_update_user( array(
					'ID'			=>	$user->ID,
					'user_nicename'	=>	sanitize_title( $user->display_name )
				) );
			}
		}
	}
	
	
	/**
	 * Overwrites the user's nicename with the user's display name
	 *
	 * Runs every time a user is created or updated
	 *
	 * @author	Konstantin Obenland
	 * @since	1.0 - 19.02.2011
	 *
	 * @param	string	$name	The default nicename
	 *
	 * @return	string	The sanitized nicename
	 */
	public function pre_user_nicename( $name ) {
		
		if ( $_REQUEST['display_name'] ) {
			return sanitize_title( $_REQUEST['display_name'] );
		}
		return $name;
	}
}  // End of class Obenland_Wp_Author_Slug


new Obenland_Wp_Author_Slug;


/* End of file wp-author-slug.php */
/* Location: ./wp-content/plugins/wp-author-slug/wp-author-slug.php */