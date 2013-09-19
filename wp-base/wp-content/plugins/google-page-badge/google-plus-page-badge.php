<?php
/*
Plugin Name: Google+ Page Badge
Plugin URI: http://wordpress.org/extend/plugins/google-page-badge
Description: Add a badge for your Google+ Page to your website in a widget, via shortcode or using template tags. You are welcome to express your gratitude for this plugin by donating via <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SXTEL7YLUSFFC" target="_blank"><strong>PayPal</strong></a>
Author: bkmacdaddy designs
Version: 1.0
Author URI: http://bkmacdaddy.com/

/* License

    Google+ Page Badge
    Copyright (C) 2012 Brian McDaniel (brian at bkmacdaddy dot com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
*/

function gpluspb($userid, $gpluspbSize='badge') {

	// This is the main function of the plugin. It is used by the widget and can also be called from anywhere in your theme. See the readme file for example.
	
	$content = '';
	$content .= '<div class="gppb-wrap">';
		if ($gpluspbSize == 'badge') { 
			$content .= '<g:plus href="https://plus.google.com/' . $userid .'" size="badge">';
			$content .= '</g:plus>';
		} elseif ($gpluspbSize == 'smallbadge') { 
			$content .= '<g:plus href="https://plus.google.com/' . $userid .'" size="smallbadge">';
			$content .= '</g:plus>';
		}
	$content .= '</div>';	
	return $content;
}

function gppb($id, $size) {
	$gppbid = get_option('gpluspb_id');
	if (( !empty( $gppbid ) ) && ( empty( $id ) )) {
		$id = $gppbid;
	}
	echo gpluspb($id, $size);
}

function gpluspb_shortcode( $atts )	{
 	$sc_id = get_option('gpluspb_id');
	extract( shortcode_atts( array(
				'id' => $sc_id,
				'size' => 'badge'
			), $atts 
		) 
	);
	// this will display the Google+ badge
	$gpluspbsc = gpluspb($id, $size);
	return $gpluspbsc;
 
}
add_shortcode('gpluspb', 'gpluspb_shortcode');

class gPlusPageBadge_Widget extends WP_Widget {
  function gPlusPageBadge_Widget() {
    $widget_ops = array('classname' => 'gPlusPageBadge_widget', 'description' => 'A widget to display a Google+ Badge' );
    $this->WP_Widget('gPlusPageBadge_widget', 'Google+ Page Badge', $widget_ops);
  }

  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;

    $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
    $user_id = empty($instance['user_id']) ? '&nbsp;' : $instance['user_id'];
    $gpluspbSize = empty($instance['gpluspbSize']) ? '&nbsp;' : $instance['gpluspbSize'];
 
    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    if ( empty( $gpluspbSize ) ) { $gpluspbSize = 'badge'; };

    if ( !empty( $user_id ) ) {

      echo gpluspb($user_id, $gpluspbSize); ?>

                <?php }

    echo $after_widget;
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['user_id'] = strip_tags($new_instance['user_id']);
    $instance['gpluspbSize'] = strip_tags($new_instance['gpluspbSize']);
 
    return $instance;
  }
 
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'user_id' => '', 'gpluspbSize' => '') );
    $title = strip_tags($instance['title']);
    $user_id = strip_tags($instance['user_id']);
    $gpluspbSize = strip_tags($instance['gpluspbSize']);
?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <br /><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
								    
      <p><label for="<?php echo $this->get_field_id('user_id__title'); ?>">Google+ Page ID<br /><input class="widefat" id="<?php echo $this->get_field_id('user_id'); ?>" name="<?php echo $this->get_field_name('user_id'); ?>" type="text" value="<?php echo attribute_escape($user_id); ?>" /></label><br /><em>Your Page ID is a 21-digit string at the end of the URL. Example: https://plus.google.com/<strong>100677423206997674566</strong>/<br /> The numbers in bold is your Page ID.</em></p>      
      
      <p><label for="<?php echo $this->get_field_id('gpluspbSize'); ?>">Badge size: <br /><select id="<?php echo $this->get_field_id('gpluspbSize'); ?>" name="<?php echo $this->get_field_name('gpluspbSize'); ?>">
        <?php 
			echo '<option ';
				if ( $instance['gpluspbSize'] == 'badge' ) { echo 'selected '; }
					echo 'value="badge">';
					echo 'Standard </option>';
			echo '<option ';
				if ( $instance['gpluspbSize'] == 'smallbadge' ) { echo 'selected '; }
					echo 'value="smallbadge">';
					echo 'Small </option>';
		?>
      </select></label></p>

<?php
																			}
}

// register_widget('gPlusPageBadge_Widget');
add_action( 'widgets_init', create_function('', 'return register_widget("gPlusPageBadge_Widget");') );

// create custom plugin settings menu
add_action('admin_menu', 'gpluspb_create_menu');

// add the admin options page
function gpluspb_admin_add_page() {
	add_options_page('Google+ Page Badge', 'Google+ Page Badge', 'manage_options', 'plugin', 'gpluspb_options_page');
}

add_action('admin_menu', 'gpluspb_admin_add_page');

// display the admin options page
function gpluspb_options_page() {

	if($_POST['gpluspb_hidden'] == 'Y') {
		//Form data sent
		$gppid = $_POST['gpluspb_id'];
		update_option('gpluspb_id', $gppid);
		?>
		<div class="updated"><p><strong>Settings saved</strong></p></div>
		<?php
	} else {
		//Normal page display
		$gppid = get_option('gpluspb_id');
	}
	
	
?>

<div class="wrap">  
    <h2>Google+ Page Badge Settings</h2>  
  
    <form name="gpluspb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="gpluspb_hidden" value="Y">
        <p>Google+ Page ID: <input type="text" name="gpluspb_id" value="<?php echo $gppid; ?>" size="20"></p>
        <p><em>Your Page ID is a 21-digit string at the end of the URL.</em></p>
        <p>Example: https://plus.google.com/<strong>100677423206997674566</strong>/</p>
        <p><em>The numbers in bold is your Page ID.</em></p>  
  
        <p class="submit">  
        <input type="submit" name="Submit" value="Save Settings" />  
        </p>  
    </form> 
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Like this plugin? You can support the developer and express your gratitude by making a donation of any size via PayPal. Just click on the button below!</p>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="SXTEL7YLUSFFC">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form> 
</div>
<?php
}


function insert_gpluspb_in_head() {
	$gpuserid = get_option('gpluspb_id');
	global $post;
		echo '<!-- script for Google+ Page Badge plugin -->';
		echo '<link href="https://plus.google.com/'.$gpuserid.'" rel="publisher" /><script type="text/javascript">';
		echo '(function()';
		echo '{var po = document.createElement("script");';
		echo 'po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";';
		echo 'var s = document.getElementsByTagName("script")[0];';
		echo 's.parentNode.insertBefore(po, s);';
		echo '})();</script>';
}

add_action( 'wp_head', 'insert_gpluspb_in_head', 5 );

?>