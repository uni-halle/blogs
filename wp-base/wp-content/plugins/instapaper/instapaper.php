<?php
/*
Plugin Name: Instapaper Read Later Links
Description: Automatically display Instapaper 'Read Later' links next to your blog posts.
Plugin URI:  http://lud.icro.us/wordpress-plugin-embed-instapaper/
Version:     1.2
Author:      John Blackbourn
Author URI:  http://johnblackbourn.com/

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/

class InstapaperReadLater {

	var $plugin;

	function InstapaperReadLater() {
		add_action( 'read_later',         array( $this, 'read_later' ) );
		add_action( 'admin_menu',         array( $this, 'admin_menu' ) );
		add_action( 'init',               array( $this, 'script' ) );
		add_action( 'wp_head',            array( $this, 'style' ) );
		add_action( 'admin_init',         array( $this, 'register_setting' ) );
		add_filter( 'the_excerpt',        array( $this, 'content' ) );
		add_filter( 'the_content',        array( $this, 'content' ) );
		add_filter( 'ozh_adminmenu_icon', array( $this, 'icon' ) );

		$this->plugin = array(
			'url' => WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ),
			'ver' => '1.2',
			'opt' => get_option( 'read_later' )
		);

		// Upgrade options from 1.0.x:
		if ( empty( $this->plugin['opt'] ) ) {
			$this->plugin['opt'] = array(
				'css'    => get_option( 'read_later_css', "float: right;\nmargin: 0px 0px 10px 15px;" ),
				'filter' => get_option( 'read_later_filter', 'all' )
			);
			update_option( 'read_later', $this->plugin['opt'] );
			delete_option( 'read_later_css' );
			delete_option( 'read_later_filter' );
		}

	}

	function register_setting() {
		register_setting( 'read_later', 'read_later' );
	}

	function admin_menu() {
		add_options_page(
			__( 'Instapaper Read Later Links Settings', 'instapaper_read_later' ),
			__( 'Read Later Links', 'instapaper_read_later' ),
			'manage_options',
			'instapaper_read_later',
			array( $this, 'settings' )
		);
	}

	function style() {
		if ( empty( $this->plugin['opt']['css'] ) )
			return;
		?>
		<style type="text/css">

		.read_later {
		<?php echo $this->plugin['opt']['css']; ?>
		}

		</style>
		<?php
	}

	function script() {
		wp_enqueue_script(
			'read_later',
			'http://www.instapaper.com/javascript/embed2.js',
			null,
			$this->plugin['ver']
		);
	}

	function code( $post_id = 0 ) {
		$post = get_post( $post_id );
		return '<span class="read_later"><script type="text/javascript"><!--
			instapaper_embed( "' . get_permalink( $post->ID ). '", "' . get_the_title( $post->ID ). '", "" );
		//--></script></span>';
	}

	function read_later( $post_id = 0 ) {
		echo $this->code( $post_id );
	}

	function content( $content ) {
		if ( 'all' == $this->plugin['opt']['filter'] )
			$content = $this->code() . $content;
		if ( ( is_single() or is_page() ) and ( 'single' == $this->plugin['opt']['filter'] ) )
			$content = $this->code() . $content;
		else if ( !is_single() and !is_page() and ( 'nonsingle' == $this->plugin['opt']['filter'] ) )
			$content = $this->code() . $content;
		return $content;
	}

	function settings() {
		?>

	<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Instapaper Read Later Links Settings', 'instapaper_read_later' ); ?></h2>

	<form method="post" action="options.php">
	<?php settings_fields( 'read_later' ); ?>

	<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'Automatic &#8216;Read Later&#8217; Link Placement', 'instapaper_read_later' ); ?></th>
		<td>
			<p><label><input type="radio" name="read_later[filter]" <?php checked( 'all', $this->plugin['opt']['filter'] ); ?> value="all" /> <?php _e( 'I&#8217;d like them everywhere!', 'instapaper_read_later' ); ?></label></p>
			<p><label><input type="radio" name="read_later[filter]" <?php checked( 'single', $this->plugin['opt']['filter'] ); ?> value="single" /> <?php _e( 'Just on single posts and pages', 'instapaper_read_later' ); ?></label></p>
			<p><label><input type="radio" name="read_later[filter]" <?php checked( 'nonsingle', $this->plugin['opt']['filter'] ); ?> value="nonsingle" /> <?php _e( 'Just on my home page and archives', 'instapaper_read_later' ); ?></label></p>
			<p><label><input type="radio" name="read_later[filter]" <?php checked( 'false', $this->plugin['opt']['filter'] ); ?> value="false" /> <?php _e( 'Don&#8217;t automatically display them at all', 'instapaper_read_later' ); ?></label><br />
			<span class="description"><?php printf( __( 'You&#8217;ll need to add the %s template tag inside the WordPress loop in this case.', 'instapaper_read_later' ), "<code>&lt;?php do_action('read_later'); ?&gt;</code>" ); ?></span></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Button Style', 'instapaper_read_later' ); ?></th>
		<td>
			<p><?php _e( 'There is currently only one button style available (shown below). The original black version has been deprecated by Instapaper and no longer works.', 'instapaper_read_later' ); ?></p>
			<p><img src="<?php echo $this->plugin['url']; ?>/button2.png" style="vertical-align:middle" alt="" /></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Custom CSS', 'instapaper_read_later' ); ?></th>
		<td>
			<p><textarea name="read_later[css]" class="code" rows="4" cols="50"><?php echo esc_attr( $this->plugin['opt']['css'] ); ?></textarea>
			<p class="description"><?php _e( 'You can specify your own CSS for the &#8216;Read Later&#8217; links here. Note that the only CSS rules that will have real effect are margins and positions as the actual link is inside an iframe and therefore unstylable. If present, this CSS is applied regardless of the automatic placement setting.', 'instapaper_read_later' ); ?></p>
			</p>
		</td>
	</tr>
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	</form>

	</div>

	<?php
	}

	function icon( $hook ) {
		if ( $hook == 'instapaper_read_later' )
			return $this->plugin['url'] . '/icon.png';
		return $hook;
	}

}

load_plugin_textdomain( 'instapaper_read_later', false, dirname( plugin_basename( __FILE__ ) ) );

$read_later = new InstapaperReadLater;

?>