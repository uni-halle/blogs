<?php
/*
Plugin Name: Anything Popup
Description: This is a simple plugin to display the entered content in to unblockable popup window. popup will open by clicking the text or image button.
Author: Gopi Ramasamy
Version: 5.9
Plugin URI: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
Author URI: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
Donate link: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
Text Domain: anything-popup
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("AnythingPopupTable", $wpdb->prefix . "AnythingPopup");
define('AnythingPopup_FAV', 'http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/');

if ( ! defined( 'ANYTHGPOPUP_PLUGIN_BASENAME' ) )
	define( 'ANYTHGPOPUP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'ANYTHGPOPUP_PLUGIN_NAME' ) )
	define( 'ANYTHGPOPUP_PLUGIN_NAME', trim( dirname( ANYTHGPOPUP_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'ANYTHGPOPUP_PLUGIN_DIR' ) )
	define( 'ANYTHGPOPUP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . ANYTHGPOPUP_PLUGIN_NAME );

if ( ! defined( 'ANYTHGPOPUP_PLUGIN_URL' ) )
	define( 'ANYTHGPOPUP_PLUGIN_URL', WP_PLUGIN_URL . '/' . ANYTHGPOPUP_PLUGIN_NAME );
	
if ( ! defined( 'ANYTHGPOPUP_ADMIN_URL' ) )
	define( 'ANYTHGPOPUP_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=anything-popup' );

function AnythingPopup( $pop_id = "1" )
{
	global $wpdb, $wp_version;
	$ArrInput = array();
	$ArrInput["id"] = $pop_id;
	echo AnythingPopup_shortcode( $ArrInput );
}

function AnythingPopup_shortcode( $atts ) 
{
	global $wpdb;
	$scode = "";
	// [AnythingPopup id="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$pop_id = $atts['id'];
	
	$sSql = "select * from ".AnythingPopupTable." where 1=1";
	if($pop_id == "RANDOM" || $pop_id == "")
	{
		$sSql = $sSql . " Order by rand()";
	}
	else
	{
		if(is_numeric($pop_id)) 
		{
			$sSql = $sSql . " and pop_id=$pop_id";
		}
	}
	$sSql = $sSql . " LIMIT 0,1";
	
	$pop = "";
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{

		$data = $data[0];
		$pop_content_id = $data->pop_id;
		$pop_width = stripslashes($data->pop_width);
		$pop_height = stripslashes($data->pop_height);
		$pop_headercolor = stripslashes($data->pop_headercolor);
		$pop_bordercolor = stripslashes($data->pop_bordercolor);
		$pop_header_fontcolor = stripslashes($data->pop_header_fontcolor);
		$pop_title = stripslashes($data->pop_title);
		$pop_Temp = stripslashes($data->pop_content);
		$pop_Temp = do_shortcode($pop_Temp);
		$pop_content = $pop_Temp;	
		$pop_content = str_replace("\n", "<br />", $pop_content);
		$pop_caption = stripslashes($data->pop_caption);
		$pop_content_height = $pop_height - 60;
		
		$pop = $pop . '<style type="text/css">';
		$pop = $pop . '#AnythingPopup_BoxContainer'.$pop_content_id.'	{';
			$pop = $pop . 'width:'.$pop_width.'px;';
			$pop = $pop . 'height:'.$pop_height.'px;';
			$pop = $pop . 'background:#FFFFFF;';
			$pop = $pop . 'border:1px solid '.$pop_bordercolor.';';
			$pop = $pop . 'padding:0;';
			$pop = $pop . 'position:fixed;';
			$pop = $pop . 'z-index:99999;';
			$pop = $pop . 'cursor:default;';   
			$pop = $pop . '-moz-border-radius: 10px;';
			$pop = $pop . '-webkit-border-radius: 10px;';
			$pop = $pop . '-khtml-border-radius: 10px;';
			$pop = $pop . 'border-radius: 10px;   ';
			$pop = $pop . 'display:none;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerHeader'.$pop_content_id.' {';
			$pop = $pop . 'height:30px;';
			$pop = $pop . 'background:'.$pop_headercolor.';';
			$pop = $pop . 'border-top-right-radius:10px;';
			$pop = $pop . '-moz-border-radius-topright:10px;';
			$pop = $pop . '-webkit-border-top-right-radius:10px;';
			$pop = $pop . '-khtml-border-top-right-radius: 10px;';
			$pop = $pop . 'border-top-left-radius:10px;';
			$pop = $pop . '-moz-border-radius-topleft:10px;';
			$pop = $pop . '-webkit-border-top-left-radius:10px;';
			$pop = $pop . '-khtml-border-top-left-radius: 10px;';   
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerHeader'.$pop_content_id.' a {';
		   $pop = $pop . 'color:'.$pop_header_fontcolor.';';
		   $pop = $pop . 'font-family:Verdana,Arial;';
		   $pop = $pop . 'font-size:10pt;';
		   $pop = $pop . 'font-weight:bold;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxTitle'.$pop_content_id.' {';
		   $pop = $pop . 'float:left;';
		   $pop = $pop . ' margin:5px;';
		   $pop = $pop . 'color:'.$pop_header_fontcolor.';';
		   $pop = $pop . 'font-family:Verdana,Arial;';
		   $pop = $pop . 'font-size:12pt;';
		   $pop = $pop . 'font-weight:bold;';   
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxClose'.$pop_content_id.' {';
		   $pop = $pop . 'float:right;';
		   $pop = $pop . 'width:50px;';
		   $pop = $pop . 'margin:5px;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerBody'.$pop_content_id.' {';
		   $pop = $pop . 'margin:15px;';
		   $pop = $pop . 'overflow:auto;';
		   $pop = $pop . 'height:'.$pop_content_height.'px;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerFooter'.$pop_content_id.' {';
		   $pop = $pop . 'position: fixed;'; 
		   $pop = $pop . 'top:0;'; 
		   $pop = $pop . 'left:0;'; 
		   $pop = $pop . 'bottom:0;'; 
		   $pop = $pop . 'right:0;';
		   $pop = $pop . 'background:#000000;';
		   $pop = $pop . 'opacity: .3;';
		   $pop = $pop . '-moz-opacity: .3;';
		   $pop = $pop . 'filter: alpha(opacity=30);';
		   $pop = $pop . 'border:1px solid '.$pop_bordercolor.';';
		   $pop = $pop . 'z-index:999;';
		   $pop = $pop . 'display:none;';
		$pop = $pop . '} ';
		$pop = $pop . '</style>';
		
		$HrefOpen = 'javascript:AnythingPopup_OpenForm("AnythingPopup_BoxContainer'.$pop_content_id.'","AnythingPopup_BoxContainerBody'.$pop_content_id.'","AnythingPopup_BoxContainerFooter'.$pop_content_id.'","'.$pop_width.'","'.$pop_height.'");';
		$HrefClose = "javascript:AnythingPopup_HideForm('AnythingPopup_BoxContainer".$pop_content_id."','AnythingPopup_BoxContainerFooter".$pop_content_id."');";
	
		$pop = $pop . "<a href='".$HrefOpen."'>".$pop_caption."</a>";
		$pop = $pop . '<div style="display: none;" id="AnythingPopup_BoxContainer'.$pop_content_id.'">';
		  $pop = $pop . '<div id="AnythingPopup_BoxContainerHeader'.$pop_content_id.'">';
			$pop = $pop . '<div id="AnythingPopup_BoxTitle'.$pop_content_id.'">'.$pop_title.'</div>';
			$pop = $pop . '<div id="AnythingPopup_BoxClose'.$pop_content_id.'"><a href="'.$HrefClose.'">Close</a></div>';
		  $pop = $pop . '</div>';
		  $pop = $pop . '<div id="AnythingPopup_BoxContainerBody'.$pop_content_id.'">'.$pop_content.'</div>';
		$pop = $pop . '</div>';
		$pop = $pop . '<div style="display: none;" id="AnythingPopup_BoxContainerFooter'.$pop_content_id.'"></div>';
	}
	else
	{
		$pop = _('No record found.', 'anything-popup');
	}
	return $pop;
}

function AnythingPopup_install() 
{
	global $wpdb, $wp_version;
	if($wpdb->get_var("show tables like '". AnythingPopupTable . "'") != AnythingPopupTable) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". AnythingPopupTable . "` (";
		$sSql = $sSql . "`pop_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`pop_width` int(11) NOT NULL default '380' ,";
		$sSql = $sSql . "`pop_height` int(11) NOT NULL default '260' ,";
		$sSql = $sSql . "`pop_headercolor` VARCHAR( 10 ) NOT NULL default '#4D4D4D' ,";
		$sSql = $sSql . "`pop_bordercolor` VARCHAR( 10 ) NOT NULL default '#4D4D4D',";
		$sSql = $sSql . "`pop_header_fontcolor` VARCHAR( 10 ) NOT NULL default '#FFFFFF' ,";
		$sSql = $sSql . "`pop_title` VARCHAR( 1024 ) NOT NULL default 'Anything Popup' ,";
		$sSql = $sSql . "`pop_content`TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`pop_caption` VARCHAR( 2024 ) NOT NULL default 'Click to open popup' ,";
		$sSql = $sSql . "PRIMARY KEY ( `pop_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$sSql = "";
		$con = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,";
		$con = $con . " when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap";
		$con = $con . " into electronic typesetting, remaining essentially unchanged."; 
		
		$IsSql = "INSERT INTO `". AnythingPopupTable . "` (`pop_content`)"; 
		$sSql = $IsSql . " VALUES ('".$con."');";
		$wpdb->query($sSql);
	}
	add_option('pop_id', "RANDOM");
}

function AnythingPopup_widget($args) 
{
	extract($args);
	echo $before_widget;
	$pop_id = get_option('pop_id');
	AnythingPopup($pop_id = $pop_id);
	echo $after_widget;
}
	
function AnythingPopup_control() 
{
	$pop_id = get_option('pop_id');
	if (isset($_POST['pop_submit'])) 
	{
		$pop_id = stripslashes(trim($_POST['pop_id']));
		update_option('pop_id', $pop_id );
	}
	
	echo '<p>'.__('Popup ID', 'anything-popup').'<br>';
	echo '<input  style="width: 200px;" maxlength="100" type="text" value="';
	echo $pop_id . '" name="pop_id" id="pop_id" /></p>';
	echo '<input type="hidden" id="pop_submit" name="pop_submit" value="1" />';
	
	echo '<p>';
	_e('Check official website for more information', 'anything-popup');
	?> <a target="_blank" href="<?php echo AnythingPopup_FAV; ?>"><?php _e('click here', 'anything-popup'); ?></a></p><?php
}

function AnythingPopup_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget(__('Anything Popup', 'anything-popup'), __('Anything Popup', 'anything-popup'), 'AnythingPopup_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control(__('Anything Popup', 'anything-popup'), array(__('Anything Popup', 'anything-popup'), 'widgets'), 'AnythingPopup_control');
	} 
}

function AnythingPopup_deactivation() 
{
	delete_option( 'pop_id' ); 
}

function AnythingPopup_admin()
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'set':
			include('pages/widget-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function AnythingPopup_add_to_menu() 
{
	add_options_page( __('Anything Popup', 'anything-popup'), __('Anything Popup', 'anything-popup'), 'manage_options', 'anything-popup', 'AnythingPopup_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'AnythingPopup_add_to_menu');
}

function AnythingPopup_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'anything-popup-js', get_option('siteurl').'/wp-content/plugins/anything-popup/anything-popup.js');
	}
}   

function AnythingPopup_textdomain() 
{
	  load_plugin_textdomain( 'anything-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'AnythingPopup_textdomain');
add_shortcode( 'AnythingPopup', 'AnythingPopup_shortcode' );
add_action('wp_enqueue_scripts', 'AnythingPopup_add_javascript_files');
add_action("plugins_loaded", "AnythingPopup_widget_init");
register_activation_hook(__FILE__, 'AnythingPopup_install');
register_deactivation_hook(__FILE__, 'AnythingPopup_deactivation');
add_action('init', 'AnythingPopup_widget_init');
?>