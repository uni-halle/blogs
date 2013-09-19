<?php
/*
Plugin Name: Google+ Add to Circles
Plugin URI: http://wordpress.org/extend/plugins/google-add-to-circles/
Description: This plugin adds the Google+ Badge and Google+ Direct Connect to your WordPress website. Note, the Google+ badge only works for Google+ pages - not profiles.
Version: 1.0
Author: Brian Purkiss
Author URI: http://wplifeguard.com/
License: GPL2
	Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// create custom plugin settings menu
add_action('admin_menu', 'gatc_create_menu');

function gatc_create_menu() {
	//create new top-level menu
	add_options_page( 'Add to Circles Settings', 'Add to Circles Settings', 'install_plugins', 'gatc-settings', 'gatc_plugin_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'gatc_register_mysettings' );
}

function gatc_register_mysettings() {
	//register our settings
	register_setting( 'gatc-settings-group', 'gatc_google_plus_id' );
}

function gatc_plugin_settings_page() {
?>
<div class="wrap">
	<h2>Google+ Add to Circles Settings</h2>
	<p>This plugin allows you to add a <a href="https://developers.google.com/+/plugins/badge/">Google+ add to circles badge</a> as well as Google+ Direct Connect to your WordPress site through a WordPress widget.</p>
	
	<form method="post" action="options.php">
	<?php settings_fields( 'gatc-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="gatc_google_plus_id">Google+ ID</label></th>
				<td><input type="text" name="gatc_google_plus_id" value="<?php echo get_option('gatc_google_plus_id'); ?>" /> Example: <u>102084107306680338668</u></td>
			</tr>
		</table>
		<p>Your Google+ ID can be found in the URL of the Google+ Page</p>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	</form>
	
	<div class="metabox-holder" style="width: 322px;">
		<div class="postbox">
			<h3 class="hndle">Widget in Action</h3>
			<div class="inside">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<?php if ( get_option('gatc_google_plus_id') == '' ) {
					echo '<p>This is an example of what the widget will look like.</p><g:plus href="https://plus.google.com/102084107306680338668"></g:plus>';
				} else {
					echo '<p>This is an example of what the widget looks like.</p><g:plus href="https://plus.google.com/'; echo get_option('gatc_google_plus_id'); echo '"></g:plus><p>If the space is blank, that means you entered in an invalid Google+ ID or the ID of a profile, not a page.</p>';
				} ?>
			</div><!--.inside-->
		</div><!--.postbox-->
	</div><!--.metabox-holder-->
	
	<div><p>This plugin is brought to you by <a href="http://wplifeguard.com/">wpLifeGuard's WordPress video tutorials</a>.</p></div>
</div>
<?php }


// add the necessary elements to the <head> if a Google+ ID has been added
function gatc_add_to_head() {
	if ( get_option('gatc_google_plus_id') == '' ) {} else {
		echo '<link href="https://plus.google.com/'; 
		echo get_option('gatc_google_plus_id'); 
		echo '" rel="publisher" />	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
	}
}
add_action('wp_head', 'gatc_add_to_head');


/**
 * gatc_google_circles_widget Class
 */
class gatc_google_circles_widget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'gatc_google_circles_widget', /* Name */'Google+ Add to Circles', array( 'description' => 'Adds the Google+ "Add to Circles" badge to your site.' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
		<g:plus href="https://plus.google.com/<?php echo get_option('gatc_google_plus_id'); ?>"></g:plus>
		<?php echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( '', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php 
	}

} // class gatc_google_circles_widget
add_action( 'widgets_init', create_function( '', 'register_widget("gatc_google_circles_widget");' ) );

?>