<?php
/* Admin functions to set and save settings of the 
 * @package WRP
*/
require_once('pages.php');
require_once('meta_box.php');
require_once(WRP_INC.'list_table.php');
require_once(WRP_INC.'twitteroauth.php');
require_once(WRP_INC.'facebook.php');
/* Initialize the theme admin functions */
add_action('init', 'wrp_admin_init');

function wrp_admin_init(){
			
    add_action('admin_menu', 'wrp_settings_init');
    add_action('admin_init', 'wrp_actions_handler');
    add_action('admin_init', 'wrp_admin_style');
    add_action('admin_init', 'wrp_admin_script');
    
}

function wrp_settings_init(){
   global $wrp;
   add_menu_page('WRP', 'WRP', 0, 'wrp-campaigns', 'wrp_campaigns_page' ); 
   $wrp->campaings = add_submenu_page('wrp-campaigns', 'Campaigns', 'Campaigns', 0, 'wrp-campaigns', 'wrp_campaigns_page' );
   $wrp->addnew = add_submenu_page('wrp-campaigns', 'Add Campaign', 'Add Campaign', 0, 'wrp-add-new', 'wrp_add_new_page');
   $wrp->twitter = add_submenu_page('wrp-campaigns', 'Twitter', 'Twitter', 0, 'wrp-twitter', 'wrp_twitter_page');
   $wrp->facebook = add_submenu_page('wrp-campaigns', 'Facebook', 'Facebook', 0, 'wrp-facebook', 'wrp_facebook_page');
   $wrp->settings = add_submenu_page('wrp-campaigns', 'Settings', 'Settings', 0, 'wrp-settings', 'wrp_configuration_page' );

   add_action( "load-{$wrp->addnew}", 'wrp_add_new_settings');
   add_action( "load-{$wrp->twitter}", 'wrp_twitter_settings');
   add_action( "load-{$wrp->facebook}", 'wrp_facebook_settings');
   add_action( "load-{$wrp->settings}", 'wrp_configuration_settings');
}

function wrp_admin_style(){
   $plugin_data = get_plugin_data( WRP_DIR . 'wp_rss_poster.php' );	
   wp_enqueue_style( 'wrp-admin', WRP_CSS . 'style.css', false, $plugin_data['Version'], 'screen' );	
}

function wrp_admin_script(){}
function wrp_actions_handler(){
    global $wpdb;
       
    if(isset($_GET['connect'])){
	   wrp_connect($_GET['connect']);	
	}
		
	if(isset($_GET['disconnect'])){
	   wrp_disconnect($_GET['disconnect']);	
	}
    
    if(isset($_GET['action']) && $_GET['action']=='campaign-run'){
	                   
      $return = wrp_do_job($_GET['id']);
      $settings['last_run'] = time();
      $where = array('id' => $_GET['id']); 
      $wpdb->update($wpdb->prefix.'wrp', $settings, $where);
	  //var_dump($return);
	  $redirect = admin_url( 'admin.php?page=wrp-campaigns&success=true' ); 
      wp_redirect($redirect);
   }
    
   if(isset($_GET['action']) && $_GET['action']=='campaign-delete'){
	  $wrp_table = $wpdb->prefix.'wrp';
	  $query = "DELETE FROM $wrp_table WHERE id=".$_GET['id'];
	  $wpdb->query($query);
	  $redirect = admin_url( 'admin.php?page=wrp-campaigns' ); 
      wp_redirect($redirect);
   }
    
   if(isset($_POST['settings'])){
	  $wrp_opts = get_option(WRP_OPTIONS);
	  $wrp_opts['unix_cron'] = $_POST['unix_cron'];
	  update_option(WRP_OPTIONS, $wrp_opts);
	  wp_redirect(admin_url('admin.php?page=wrp-settings&updated=true'));   
   }
   
   if(isset($_POST['add_category'])){
	  wp_create_category($_POST['new_category']); 
	  wp_redirect(admin_url('admin.php?page=wrp-add-new'));   
   }
   
    if(isset($_POST['campaign'])){
	  //put the campaign info to settings array
	  $settings = array();
	  $settings['name'] = $_POST['name'];
	  $settings['rss_url'] = $_POST['url'];   
	  $settings['category'] = json_encode($_POST['post_category']);
	  $settings['number'] = $_POST['number'];  
      $settings['status'] = $_POST['status'];
      $settings['author'] = $_POST['author'];
      $settings['publish_date'] = $_POST['date'];
      $settings['schedule'] = $_POST['schedule'];
      $settings['next_run'] = time() + 60 * 60 * $_POST['schedule'];
      $settings['origin'] = $_POST['origin'];
      $settings['rewrite'] = $_POST['rewrite'];
      $settings['cache_image'] = $_POST['cache'];
      $settings['source_link'] = $_POST['source'];
      $settings['template'] = stripslashes($_POST['template']);
      $settings['enable_template'] = $_POST['enable_template'];
      $settings['exclude'] = $_POST['exclude'];
      //process the settings info according to action value
	  switch ($_POST['action']) { 
		 case 'create':		       
		    wp_create_category($_POST['new_category']); 
	        $wpdb->insert( $wpdb->prefix.'wrp', $settings);
            if($wpdb->insert_id != false){ 
               $redirect = admin_url( 'admin.php?page=wrp-add-new&updated=true&id=' ).$wpdb->insert_id; 
               wp_redirect($redirect);
            }      
		 break;
		 case 'update':
		    wp_create_category($_POST['new_category']);
		    $where = array('id' => $_GET['id']); 
            $wpdb->update($wpdb->prefix.'wrp', $settings, $where);
            $redirect = admin_url( 'admin.php?page=wrp-add-new&updated=true&id=' ).$_GET['id'];
            wp_redirect($redirect);		 
		 break;  
	  }  
   }	
}

function wrp_error_message(){
   echo '<div class="error">
		<p>Error</p>
  </div>';  
}
function wrp_success_message(){
  echo '<div class="updated fade">
		<p>This campaign has done successfully.</p>
  </div>';  
}
function wrp_update_message(){
   echo '<div class="updated fade">
		<p>Settings Updated</p>
  </div>';  	
}
function wrp_create_message(){
   echo '<div class="updated fade">
		<p>Campaign Created</p>
  </div>';	
}
?>
