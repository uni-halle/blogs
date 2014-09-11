<?php
/*
Plugin Name: Shareaholic | share buttons, analytics, related posts
Plugin URI: https://shareaholic.com/publishers/
Description: Whether you want to get people sharing, grow your fans, make money, or know who's reading your content, Shareaholic will help you get it done. See <a href="admin.php?page=shareaholic.php">configuration panel</a> for more settings.
Version: 6.1.5.1
Author: Shareaholic
Author URI: https://shareaholic.com
Credits & Thanks: https://shareaholic.com/tools/wordpress/credits
*/


/*
*   @desc Define Plugin version
*/

define('SHRSB_vNum','6.1.5.1');

/*
*   @desc Create Text Domain For Translations
*/

load_plugin_textdomain('shrsb', false, basename(dirname(__FILE__)) . '/languages/');


/*
*   @desc Set "upgrade" state
*/

//Get the current Version from the database
$shrsb_version = get_option('SHRSBvNum');

// if the version number is set and is not the latest, then call the upgrade function
if(false !== $shrsb_version &&  $shrsb_version !== SHRSB_vNum ) {
   update_option('SHRSB_DefaultSprite',true);
   add_action('admin_notices', 'shrsb_Upgrade', 12);
   
   shr_sendTrackingEvent('Upgrade', array('prev_plugin_ver' => get_option('SHRSBvNum')) );
   shr_recommendationsStatus();
   shr_upgrade_routine();
   
   // Added global variable to track the updating state
   define('SHRSB_UPGRADING', TRUE);
} else {
   define('SHRSB_UPGRADING', FALSE);
}


/*
*   @note Make sure to include files first as there may be dependencies
*/

require_once 'includes/bookmarks-data.php';     // contains all bookmark templates
require_once 'includes/html-helpers.php';       // helper functions for html output
require_once 'includes/helper-functions.php';   // helper functions for backend
//require_once 'includes/widget.php';   // widget


/*
*  @ For Debugging Purpose
*/
if((isset($_GET['sb_debug']) || isset($_POST['sb_debug']) ) ){
	
    //Global Debugging Variable
    $shrsb_debug = array();
    $method =  isset($_GET)? "get" : "post"; //true for get, false for post
    $shrsb_debug['dump_type'] = shrsb_get_value($method, "sb_dump");
    $shrsb_debug['dump_type'] = shrsb_get_value($method, "dump_type");
    $shrsb_debug['sb_script'] = shrsb_get_value($method, "sb_script", false);
    $shrsb_debug['tb_script'] = shrsb_get_value($method, "tb_script", false);
    $shrsb_debug['rd_script'] = shrsb_get_value($method, "rd_script", false);
    $shrsb_debug['cb_script'] = shrsb_get_value($method, "cb_script", false);
    $shrsb_debug['sb_die'] = shrsb_get_value($method, "sb_die", false);
    $shrsb_debug['sb_log'] = shrsb_get_value($method, "sb_log", false);
    
    shrsb_dump_settings();
}

/*
 * Newer versions of WordPress include this class already
 * However, we've kept this here for people who are using older versions
 * This will mimick JSON support for PHP4 and below
*/

if ( !class_exists('SERVICES_JSON') ) {
	if ( !function_exists('json_decode') ){
		function json_decode($content, $assoc=false){
			require_once 'includes/JSON.php';
			if ( $assoc ){
				$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			} else {
				$json = new Services_JSON;
			}
				return $json->decode($content);
		}
	}
	if ( !function_exists('json_encode') ){
		function json_encode($content){
			require_once 'includes/JSON.php';
			$json = new Services_JSON;
			return $json->encode($content);
		}
	}
}

/*
*   @desc Setting path for Shareaholic WP Plugin directory
*/

// Because we load assets via javascript, we can't rely on the WordPress HTTPS plugin to do our http => https URL conversions.
// Consequently, we check here if this is an https (ssl) page load. If it is, we use the https prefix instead of http.

if ( !defined('WP_CONTENT_URL') ) {  
  define('SHRSB_PLUGPATH', shrsb_correct_protocol(get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/'));
	define('SHRSB_PLUGDIR', ABSPATH.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} 
else {
  define('SHRSB_PLUGPATH', shrsb_correct_protocol(WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/'));
  define('SHRSB_PLUGDIR',WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}

/*
*   @desc Setting path for Shareaholic Upload directory (local WP)
*/

if ( !function_exists('wp_upload_dir') ) {
        define('SHRSB_UPLOADPATH_DEFAULT', shrb_addTrailingChar(get_option('siteurl'),"/").'wp-content/uploads/shareaholic/');
        define('SHRSB_UPLOADDIR_DEFAULT', shrb_addTrailingChar(ABSPATH,"/").'wp-content/uploads/shareaholic/');
    } else {
        $upload_path  = wp_upload_dir();
        if(!$upload_path["error"]){
            define('SHRSB_UPLOADPATH_DEFAULT',shrb_addTrailingChar($upload_path['baseurl'],"/").'shareaholic/');
            define('SHRSB_UPLOADDIR_DEFAULT',shrb_addTrailingChar($upload_path['basedir'],"/").'shareaholic/');
        }else {
            define('SHRSB_UPLOADPATH_DEFAULT',shrb_addTrailingChar(SHRSB_PLUGPATH,"/"));
            define('SHRSB_UPLOADDIR_DEFAULT',shrb_addTrailingChar(SHRSB_PLUGDIR,"/"));
      }
}

//Including the Shareaholic global settings
require_once 'includes/shrsb_sexybookmarks_page.php';  // SexyBookmarks global Settings
require_once 'includes/shrsb_topbar_page.php';  // Topbar global Settings
require_once 'includes/shrsb_analytics_page.php';  // Analytics global Settings
require_once 'includes/shrsb_recommendations_page.php';  // Recommendations global Settings
require_once 'includes/shrsb_classicbookmarks_page.php';  // Classic Bookmarks global Settings

function shr_upgrade_routine() {
  $prev_version = str_replace (".","",get_option('SHRSBvNum')); 
  if($prev_version < '6137' ) {
    global $shrsb_recommendations;
    if($shrsb_recommendations['recommendations']  !== '1') {
      $shrsb_recommendations['recommendations']  = '1';
      $shrsb_recommendations['pageorpost'] ='postpage';
      update_option('ShareaholicRecommendations',$shrsb_recommendations);
    }
  }
}

//Update version (note: location of this update is important)
update_option('SHRSBvNum', SHRSB_vNum);


//populate default analytics values to pass with major events - (un)install, upgrade, (de)activate, etc
//TD: GET THE USER ID IF AVAILABLE

function shr_getTrackingData() {
  	
  global $wpdb;
	$sbtmp = get_option('SexyBookmarks');
	$cbtmp = get_option('ShareaholicClassicBookmarks');
	$rdtmp = get_option('ShareaholicRecommendations');
	$tbtmp = get_option('ShareaholicTopbar');
  	  
	$tracking_metadata = array(
		'plugin_version' => SHRSB_vNum,
		'api_key' => isset($shrsb_plugopts['apikey']) ? $shrsb_plugopts['apikey'] : '',
		'domain' => get_bloginfo('url'),
		'locale' => get_bloginfo('language'),
		'stats' => array (
		  'posts_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'post' AND post_status = 'publish'" ),
		  'pages_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'page' AND post_status = 'publish'" ),
		  'comments_total' => wp_count_comments()->approved,
		  'users_total' => $wpdb->get_var("SELECT count(ID) FROM $wpdb->users"),
      ),
    'diagnostics' => array (
  		'wp_version' => get_bloginfo('version'),
  		'theme' => get_option('template'),
  		'active_plugins' => get_option('active_plugins', array()),
  		'multisite' => is_multisite(),
  		),
    'features' => array (
  		'f_sexy' => ($sbtmp['sexybookmark'] == '1') ? 'true' : 'false',
  		'f_topbar' => ($tbtmp['topbar'] == '1') ? 'true' : 'false',
  		'f_classic' => ($cbtmp['cb'] == '1') ? 'true' : 'false',
  		'f_rec' => ($rdtmp['recommendations'] == '1') ? 'true' : 'false'
  		)
	);
	return $tracking_metadata;
}  

//send a tracking event with the default analytics values and extras passed as a key-value array
function shr_sendTrackingEvent($event_name = 'Default', $extra_params = NULL) {
	global $tracking_metadata;
	$event_data = isset($tracking_metadata) ? $tracking_metadata : shr_getTrackingData();
	if (isset($extra_params)) {
		$event_data = array_merge($event_data, $extra_params);
	}
	$trackingURL = "http://www.shareaholic.com/api/events";
	$trackingParams = array('name' => "WordPress:".$event_name, 'data' => json_encode($event_data) );	
	// Send Ping
	// var_dump($trackingParams);
	$response = wp_remote_post($trackingURL, array('body' => $trackingParams) );
}

// kicks off Recommendations
function shr_recommendationsStatus() {
	$recommendationsStatusURL = "https://www.shareaholic.com/v2/recommendations/status?url=".home_url();
	$response = wp_remote_post($recommendationsStatusURL, array('sslverify'=>false));
	if(is_array($response) && array_key_exists('body', $response)){
	  return $response['body'];
	} else {
	  return false;
	}
}

function shr_recommendationsStatus_code() {
	$recommendationsStatusURL_result = shr_recommendationsStatus();
	$obj = json_decode($recommendationsStatusURL_result, true);
	if (is_array($obj) && $obj['code'] == 200){
	  if ($obj['data'][0]['status_code'] < 3) {
	    return "processing";
	  } else {
	  return "ready";
	  }
  } else {
    return "unknown";
  }
}

$default_spritegen = get_option('SHRSB_DefaultSprite');

if(!is_writable(SHRSB_UPLOADDIR)) {
    //add_action('admin_notices', 'shrsb_SpritegenNotice', 12);
}

//if(false !== $shrsb_version &&  $shrsb_version !== SHRSB_vNum &&  SHRSB_vNum === '3.3.11' ) {
//    // This is specific till 3.3.11 to over ride existing customers to beta mode
//   if($shrsb_plugopts['shareaholic-javascript']  !== '1') {
//           $shrsb_plugopts['shareaholic-javascript']  = '1';
//           update_option('SexyBookmarks', $shrsb_plugopts);
//   }
//}



function shrsb_Upgrade() {
    global $shrsb_plugopts;

    if (current_user_can('activate_plugins')){
         // check if sprite files are not present, ask the user to re-save setting.
         if($shrsb_plugopts['shareaholic-javascript'] == '1' ||
                 (!file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') || !file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css'))) {
             echo '
              <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#FEB1B1;border:1px solid #FE9090;color:#820101;font-size:14px;font-weight:bold;height:auto;margin:30px 15px 15px 0px;overflow:hidden;padding:4px 10px 6px;line-height:30px;">
                  '.sprintf(__('NOTICE: Shareaholic was just updated - Please visit the %sPlugin Options Page%s and re-save your preferences.', 'shrsb'), '<a href="admin.php?page=shareaholic_sexybookmarks.php" style="color:#ca0c01">', '</a>').'
              </div>';
         }
    }
}

function shrsb_SpritegenNotice() {
    if (current_user_can('activate_plugins')){
             echo '
              <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
                <div style="background:url('.SHRSB_UPLOADPATH.'custom-fugue-sprite.png) no-repeat 0 -525px;margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">
                  '.sprintf(__('NOTICE: Your spritegen directory isn\'t writable - Please %sCHMOD%s your spritegen directory to ensure that Shareaholic remains working like a charm...', 'shrsb'), '<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target = "_blank" style="color:#ca0c01">', '</a>').'
                </div>
              </div>';
    }
}

//add activation hook to remove all old and non-existent options from database if necessary
function shrsb_Activate() {
  if(false === get_option('SHRSBvNum') || get_option('SHRSBvNum') == '') {
    delete_option('SexyBookmarks');
    delete_option('ShareaholicTopbar');
    delete_option('ShareaholicAnalytics');
    delete_option('SexyCustomSprite');
    delete_option('SEXY_SPONSORS');
    delete_option('SHRSB_CustomSprite');
    delete_option('ShareaholicRecommendations');
    delete_option('ShareaholicClassicBookmarks');
    
    // Report fresh plugin Installation
    shr_sendTrackingEvent('Install_Fresh');
  }
  if(!file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') || !file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
    delete_option('SHRSB_CustomSprite');
  }
  shr_sendTrackingEvent('Activate');
  shr_recommendationsStatus();
  
}
register_activation_hook( __FILE__, 'shrsb_Activate' );

//add deactivation hook to update the version number option so that options won't be deleted again upon upgrading
function shrsb_deActivate() {
  if(false !== get_option('SHRSBvNum') || get_option('SHRSBvNum') != '') {
    update_option('SHRSBvNum', SHRSB_vNum);
  }
  shr_sendTrackingEvent('Deactivate');
}
register_deactivation_hook( __FILE__, 'shrsb_deActivate' );

//adding uninstall_hook
function shrsb_uninstall() {
	 shr_sendTrackingEvent('Uninstall');
	 
	 //clean up local db
	 delete_option('SexyBookmarks');
   delete_option('ShareaholicTopbar');
   delete_option('ShareaholicAnalytics');
   delete_option('SexyCustomSprite');
   delete_option('SEXY_SPONSORS');
   delete_option('SHRSB_CustomSprite');
   delete_option('ShareaholicRecommendations');
   delete_option('ShareaholicClassicBookmarks');
   delete_option('SHRSB_DefaultSprite');
   delete_option('shr_reportupgrade');
   delete_option('SHRSBvNum');
   delete_option('SHRSB_apikey');
}
register_uninstall_hook( __FILE__, 'shrsb_uninstall');
	
//add update notice to the main dashboard area so it's visible throughout
function showUpdateNotice() {

  if(!shrsb_check_activation()){
      // Don't show the connect notice on the shareaholic settings page.
      if ( (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic.php' )) || (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic_sexybookmarks' )) ||  (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic_analytics' )) || (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic_topbar' )) || (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic_recommendations' )) || (false !== strpos( $_SERVER['QUERY_STRING'], 'page=shareaholic_classicbookmarks' )))
          return;
      echo '
      <div id="activate_shr" onclick="location.href=\'admin.php?page=shareaholic.php\';" style="cursor: pointer;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#6aafcf;border:1px solid #2A8CBA;color:white;font-size:14px;font-weight:bold;height:auto;margin:30px 15px 15px 0px;overflow:hidden;padding:4px 10px 6px;line-height:30px;text-shadow: 0px 1px 1px 
				rgba(0, 0, 0, 0.4);">
          '.sprintf(__('ACTION REQUIRED: Shareaholic for WordPress is almost ready – %sVisit the Plugin Options Page to activate Shareaholic now &rarr;%s', 'shrsb'), '<a href="admin.php?page=shareaholic.php" style="
					-moz-box-shadow:inset 0px 1px 0px 0px #fce2c1;
					-webkit-box-shadow:inset 0px 1px 0px 0px #fce2c1;
					box-shadow:inset 0px 1px 0px 0px #fce2c1;
					background-color:#ffc477;
					-moz-border-radius:6px;
					-webkit-border-radius:6px;
					border-radius:6px;
					border:1px solid #eeb44f;
					display:inline-block;
					color:#ffffff;
					font-family:arial;
					font-size:15px;
					font-weight:bold;
					padding:2px 14px;
					text-decoration:none;">', '</a>').'
      </div>';
    }
    else{
    //If the option doesn't exist yet, it means the old naming scheme was found and scrubbed... Let's alert the user to update their settings
    if(!get_option('SHRSBvNum') || get_option('SHRSBvNum') == '') {
      echo '
        <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#FEB1B1;border:1px solid #FE9090;color:#820101;font-size:14px;font-weight:bold;height:auto;margin:30px 15px 15px 0px;overflow:hidden;padding:4px 10px 6px;line-height:30px;">
            '.sprintf(__('NOTICE: Shareaholic Plugin needs to be configured - please visit the %sPlugin Options Page%s to set your preferences.', 'shrsb'), '<a href="admin.php?page=shareaholic.php" style="color:#ca0c01">', '</a>').'
        </div>';
    }
  }
}

/*
*   @desc Adds "SexyBookmarks" options on each post
*/
function _add_meta_box_options() {
    if (current_user_can('edit_posts')) {
        // Hide options on each post
        add_meta_box( 'hide_options_meta', __( 'Shareaholic', 'shrsb' ), '_hide_options_meta_box_content', 'page', 'advanced', 'high' );
        add_meta_box( 'hide_options_meta', __( 'Shareaholic', 'shrsb' ), '_hide_options_meta_box_content', 'post', 'advanced', 'high' );
    }
}

function _hide_options_meta_box_content() {
    global $post;
    $hide_sexy = get_post_meta( $post->ID, 'Hide SexyBookmarks',true);
    $hide_ogtags = get_post_meta( $post->ID, 'Hide OgTags',true);
	if ( isset( $hide_sexy ) && $hide_sexy == 1 )
		$hide_sexy = ' checked="checked"';
	else
		$hide_sexy = '';
    if ( isset( $hide_ogtags ) && $hide_ogtags == 1 )
		$hide_ogtags = ' checked="checked"';
	else
		$hide_ogtags = '';
    
    //"Hide SexyBookmarks" option on each post
	echo '<p><label for="hide_sexy"><input name="hide_sexy" id="hide_sexy" value="1"' . $hide_sexy . ' type="checkbox"> ' . __( 'Disable SexyBookmarks Share Buttons on this page.', 'shrsb' ) . '</label></p>';
    
    //For Hiding the OG tags for specific posts
    echo '<p><label for="hide_ogtags"><input name="hide_ogtags" id="hide_ogtags" value="1"' . $hide_ogtags . ' type="checkbox"> ' . __( 'Disable Open Graph Tags on this page.', 'shrsb' ) . '(<a href="https://shareaholic.com/tools/wordpress/faq/#open_graph_image" target="_blank">?</a>)</label></p>';
}

function _hide_options_meta_box_save( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

	// Record sharing disable
	if (isset($_POST['post_type']) && !isset($_POST['_inline_edit']) && ('post' == $_POST['post_type'] || 'page' == $_POST['post_type'] )) {
		if ( current_user_can( 'edit_post', $post_id ) ) {
            if ( isset( $_POST['hide_sexy'] ) ) {
                update_post_meta( $post_id, 'Hide SexyBookmarks', 1 );
            }
            else {
                //delete_post_meta( $post_id, 'Hide SexyBookmarks' );
                update_post_meta( $post_id, 'Hide SexyBookmarks', 0 );
            }
            
            if ( isset( $_POST['hide_ogtags'] ) ) {
                update_post_meta( $post_id, 'Hide OgTags', 1 );
            }
            else {
                //delete_post_meta( $post_id, 'Hide OgTags' );
                update_post_meta( $post_id, 'Hide OgTags', 0 );
            }
		}
	}

  return $post_id;
}

add_action('admin_notices', 'showUpdateNotice', 12);

function shrsb_topbar_settings(){
    require_once 'includes/shrsb_settings_page.php';  
    if(!shrsb_check_activation())
      shrsb_first_page();
    else{
      require_once 'includes/shrsb_topbar_settings_page.php';
      shrsb_tb_settings_page();
    }
}

function shrsb_analytics_settings(){
    require_once 'includes/shrsb_settings_page.php';  
    if(!shrsb_check_activation())
      shrsb_first_page();
    else{
      require_once 'includes/shrsb_analytics_settings_page.php';
      shrsb_analytics_settings_page();
    }
}

function shrsb_recommendations_settings(){
    require_once 'includes/shrsb_settings_page.php';
    if(!shrsb_check_activation())
      shrsb_first_page();
    else{
      require_once 'includes/shrsb_recommendations_settings_page.php';
      shrsb_recommendations_settings_page();
    }
}
function shrsb_cb_settings(){
    require_once 'includes/shrsb_settings_page.php';
    if(!shrsb_check_activation())
      shrsb_first_page();
    else{
      require_once 'includes/shrsb_classicbookmarks_settings_page.php';
      shrsb_cb_settings_page();
    }
}

function shrsb_first_page(){
  require_once 'includes/shrsb_activation_page.php';
  shrsb_display_activation();
}
  
function shrsb_check_activation(){
  $activated = get_option('SHR_activate');
  if($activated == 0 || $activated === false){ 
    if(isset($_POST['activate']) && $_POST['activate'] == 1 && check_admin_referer('save-settings','shareaholic_nonce')){
      update_option("SHR_activate",1);
      return true;
    }
    else
      return false;
  }
  else
    return true;
}

function shrsb_landing(){
  require_once 'includes/shrsb_settings_page.php';
    if(!shrsb_check_activation())
      shrsb_first_page();
    else{
      require_once 'includes/shrsb_landing_page.php';
      shrsb_landing_page();
    }
}

function shrsb_sexybookmarks_settings(){
  
  require_once 'includes/shrsb_settings_page.php';  
  if(!shrsb_check_activation())
    shrsb_first_page();
  else{
    require_once 'includes/shrsb_sexybookmarks_settings_page.php';
    shrsb_sb_settings_page();
  }
}


//add sidebar link to settings page
add_action('admin_menu', 'shrsb_menu_link');

/*
*   @desc Show Shareaholic Menu on WP Admin Dashboard
*/
function shrsb_menu_link() {
	if (function_exists('add_menu_page')) {

    $shrsb_landing_page = add_menu_page( __( 'Shareaholic for Publishers', 'shrsb' ), __( 'Shareaholic', 'shrsb' ), 'edit_posts', basename(__FILE__), 'shrsb_landing', SHRSB_PLUGPATH.'images/shareaholic_16x16.png');
    $shrsb_landing_page = add_submenu_page( basename(__FILE__), __( 'Dashboard' ), __( 'Dashboard', 'shrsb' ), 'edit_posts', basename(__FILE__), 'shrsb_landing' );
    
    $shrsb_recommendations_page = add_submenu_page( basename(__FILE__), __( 'Related Content' ), __( 'Related Content', 'shrsb' ), 'administrator', 'shareaholic_recommendations.php', 'shrsb_recommendations_settings' );
    
		$shrsb_sexybookmarks_page = add_submenu_page( basename(__FILE__), __( 'SexyBookmarks' ), __( 'SexyBookmarks', 'shrsb' ), 'administrator', 'shareaholic_sexybookmarks.php', 'shrsb_sexybookmarks_settings' );
    
    $shrsb_cb_page = add_submenu_page( basename(__FILE__), __( 'ClassicBookmarks' ), __( 'ClassicBookmarks', 'shrsb' ), 'administrator', 'shareaholic_classicbookmarks.php', 'shrsb_cb_settings' );
    $shrsb_topbar_page = add_submenu_page( basename(__FILE__), __( 'Top Bar' ), __( 'Top Bar', 'shrsb' ), 'administrator', 'shareaholic_topbar.php', 'shrsb_topbar_settings' );
    
    $shrsb_analytics_page = add_submenu_page( basename(__FILE__), __( 'Social Analytics' ), __( 'Social Analytics', 'shrsb' ), 'edit_posts', 'shareaholic_analytics.php', 'shrsb_analytics_settings' );


		add_action( "admin_print_scripts-$shrsb_landing_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_landing_page", 'shrsb_admin_styles' );
    
    add_action( "admin_print_scripts-$shrsb_sexybookmarks_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_sexybookmarks_page", 'shrsb_admin_styles' );
    
    
    add_action( "admin_print_scripts-$shrsb_topbar_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_topbar_page", 'shrsb_admin_styles' );
    
    add_action( "admin_print_scripts-$shrsb_analytics_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_analytics_page", 'shrsb_admin_styles' );
    
    add_action( "admin_print_scripts-$shrsb_recommendations_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_recommendations_page", 'shrsb_admin_styles' );
    
    add_action( "admin_print_scripts-$shrsb_cb_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_cb_page", 'shrsb_admin_styles' );
  }
}

//styles and scripts for admin area
function shrsb_admin_scripts() {
    global $shrsb_plugopts;
    wp_enqueue_script('shareaholic-admin-js', SHRSB_PLUGPATH.'js/shareaholic-admin.min.js', array('jquery','jquery-ui-sortable'), SHRSB_vNum, true);
    wp_enqueue_script('shareaholic-bootstrap', SHRSB_PLUGPATH.'js/bootstrap/bootstrap.min.js', array('jquery'), SHRSB_vNum, true);
    wp_enqueue_script('shareaholic-reveal', SHRSB_PLUGPATH.'js/reveal/jquery.reveal.min.js', array('jquery'), SHRSB_vNum, true);
    
		//Add promo bar for browser extensions
    if ($shrsb_plugopts['promo'] == "1") {
				if(shrsb_check_activation() === true){
        	// wp_enqueue_script('shareaholic-promo', SHRSB_PLUGPATH.'js/shareaholic-promo.min.js', array('jquery'), SHRSB_vNum, false);
				}
    }
    if(shrsb_check_activation())
      echo get_googleanalytics();
}

function shrsb_first_image() {
  global $post, $posts;
  $og_first_img = '';
  ob_start();
  ob_end_clean();
  if ($post == null)
    return false;
  else {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if(isset($matches) && isset($matches[1]) && isset($matches[1][0]) ){
        $og_first_img = $matches[1][0];
    }
    if(empty($og_first_img)){ // return false if nothing there, makes life easier
      return false;
    }
    return $og_first_img;
  }
}

/*
*   @desc For adding Open Graph tags to each post (http://ogp.me/)
*/

function shrsb_add_ogtags_head() {
  
  echo "\n<!-- Shareaholic Content Tags -->\n\n";
  
	global $post, $shrsb_plugopts;

  $blog_name = get_bloginfo();
  if (!empty($blog_name)) {
    echo "<meta name='shareaholic:site_name' content='" . $blog_name . "' />\n";
  }

  if (is_home()) {

    if (isset($shrsb_plugopts['shrsb_fallback_img']) && $shrsb_plugopts['shrsb_fallback_img'] != '') {
      echo "<meta name='shareaholic:image' content='" . $shrsb_plugopts['shrsb_fallback_img'] . "' />\n";
    } else {
      echo "\t<!-- Shareaholic Notice: There is no featured image set -->\n";
    }
  } else {

    if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
      $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
      echo "<meta name='shareaholic:image' content='" . esc_attr($thumbnail_src[0]) . "' />\n";
    } elseif (is_singular()) {
      $first_image = shrsb_first_image();
      if ($first_image !== false) {
        echo "<meta name='shareaholic:image' content='" . $first_image . "' />\n";
      }
    } else {
      echo "\t" . '<!-- Shareaholic Notice: There is neither a featured nor gallery image set -->' . "\n";
    }
  }
  echo "\n<!-- / Shareaholic Content Tags -->\n\n";

  // check to see if ogtags are enabled or not
  if (!isset($shrsb_plugopts['ogtags']) || empty($shrsb_plugopts['ogtags'])) {
    echo "\n\n" . '<!-- Shareaholic - Facebook Open Graph Tags -->' . "\n\n";
  } else {
        //Check whther OG Tags enabled for this post
        if(($ogtags_meta = get_post_meta($post->ID, 'Hide OgTags',true)) == 1)  {
            echo "\n\n".'<!-- Shareaholic Notice: Open Graph Tags disabled for this post -->'."\n\n";
           return;
        }
        
        echo "\n\n".'<!-- Shareaholic - Open Graph Tags -->'."\n\n";

		if (is_home()) {
			if (isset($shrsb_plugopts['shrsb_fallback_img']) && $shrsb_plugopts['shrsb_fallback_img'] != '') {
				echo "<meta property='og:image' content='".$shrsb_plugopts['shrsb_fallback_img']."' />\n";
			}else{
				echo "\t<!-- Shareaholic Notice: There is no featured image set -->\n"; 
			}
		} else {
			if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
				$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
				echo "<meta property='og:image' content='".esc_attr($thumbnail_src[0])."' />\n";
			}elseif (( shrsb_first_image() !== false ) && (is_singular())) {
				echo "<meta property='og:image' content='".shrsb_first_image()."' />\n";
			}else{
                echo "\t".'<!-- Shareaholic Notice: There is neither a featured nor gallery image set -->'."\n";
			}
		}
		echo "\n<!-- / Shareaholic - Open Graph Tags -->\n\n";
    }
} // end function

if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] == '1') {
    add_action( 'admin_init', '_add_meta_box_options' );    
    add_action('wp_head','shrsb_add_ogtags_head',10);
    add_action( 'save_post', '_hide_options_meta_box_save' );
}

function shrsb_admin_styles() {
	global $shrsb_plugopts;

    if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false)) {
        wp_enqueue_style('ie-old-sexy-bookmarks', SHRSB_PLUGPATH.'css/ie7-admin-style.css', false, SHRSB_vNum);
    }
    //Add promo bar for browser extensions
    if ($shrsb_plugopts['promo'] == "1") {
			if(shrsb_check_activation() === true){
        // wp_enqueue_style('shareaholic-promo', SHRSB_PLUGPATH.'css/shareaholic-promo.css', false, SHRSB_vNum);
			}
    }
    wp_enqueue_style('sexy-bookmarks', SHRSB_PLUGPATH.'css/admin-style.css', false, SHRSB_vNum);
    wp_enqueue_style('shrsb-bootstrap', SHRSB_PLUGPATH.'css/bootstrap/bootstrap.min.css', false, SHRSB_vNum);
    wp_enqueue_style('shrsb-reveal', SHRSB_PLUGPATH.'css/reveal/reveal.css', false, SHRSB_vNum);
}

// Add the 'Settings' link to the plugin page, taken from yourls plugin by ozh
function shrsb_admin_plugin_actions($links) {
	$links[] = '<a href="admin.php?page=shareaholic.php">'.__('Settings', 'shrsb').'</a>';
	return $links;
}

add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'shrsb_admin_plugin_actions', -10);
require_once "includes/public.php";

?>