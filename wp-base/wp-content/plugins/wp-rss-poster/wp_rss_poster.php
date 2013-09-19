<?php
/**
 * Plugin Name: WP Rss Poster
 * Plugin URI: http://wp-coder.net
 * Description: Easy to create posts from multiple rss sources.
 * Version: 1.0.0
 * Author: Darell Sun
 * Author URI:  http://wp-coder.net
 *
 * @package WRP
 */

require_once('include/wrp_config.php');
require_once('include/tools.php');
/* Set up the plugin. */
add_action('plugins_loaded', 'wrp_setup');  
//add cron intervals
add_filter('cron_schedules', 'wrp_intervals');
//Actions for Cron job
add_action('wrp_cron', 'wrp_cron_hook');
/* Create table when admin active this plugin*/
register_activation_hook(__FILE__,'wrp_activation');
register_deactivation_hook(__FILE__, 'wrp_deactivation');

function wrp_activation()
{
	$wrp_opts = get_option(WRP_OPTIONS);
	if(!empty($wrp_opts)){
	   $wrp_opts['version'] = WRP_VERSION;
	   update_option(WRP_OPTIONS, $wrp_opts); 	
	}else{
	   $opts = array(
		'version' => WRP_VERSION		
	  );
	  // add the configuration options
	  add_option(WRP_OPTIONS, $opts);   	
	}	
	
	
	//test if cron active
	if (!(wp_next_scheduled('wrp_cron')))
	wp_schedule_event(time(), 'wrp_intervals', 'wrp_cron');
	
	wrp_create_table();
}

function wrp_deactivation(){
    wp_clear_scheduled_hook('wrp_cron');	
}

function wrp_cron_hook(){
    global $wpdb;
    $wrp_opts = get_option(WRP_OPTIONS);
    $wrp_table = $wpdb->prefix.'wrp';
	$query = "SELECT * FROM $wrp_table";
    $entrys = $wpdb->get_results($query);
    
         
      foreach($entrys as $entry){
	     $next_run = $entry->next_run;
	     $current_time = time();
	     if (!empty($next_run)&&($next_run<=$current_time)) {
		   //do this campaing
		   wrp_do_job($entry->id);		
		   //update next run time
		   $interval = 60 * 60 * $entry->schedule;
		   $next_run = time() + $interval;
		   $settings['next_run'] = $next_run;
		   $settings['last_run'] = time();
		   $where = array('id' => $entry->id); 
           $wpdb->update($wpdb->prefix.'wrp', $settings, $where);	 
     	  }	
	  }
	
}

function wrp_unix_cron(){
    global $wpdb;
    $wrp_opts = get_option(WRP_OPTIONS);
    $wrp_table = $wpdb->prefix.'wrp';
	$query = "SELECT * FROM $wrp_table";
    $entrys = $wpdb->get_results($query);
    
         
      foreach($entrys as $entry){
	       //do this campaing
		   wrp_do_job($entry->id);		
		   //update next run time
		   //$interval = 60 * 60 * $entry->schedule;
		   //$next_run = time() + $interval;
		   //$settings['next_run'] = $next_run;
		   $settings['last_run'] = time();
		   $where = array('id' => $entry->id); 
           $wpdb->update($wpdb->prefix.'wrp', $settings, $where);    	 	
	  }
	
}

function wrp_intervals($schedules){
   $intervals['wrp_intervals']=array('interval' => '300', 'display' => 'wrp');
   $schedules=array_merge($intervals,$schedules);
   return $schedules;	
}

/*
 *Create database table for this plugin
*/
function wrp_create_table(){
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  global $wpdb;
  $wrp_table = $wpdb->prefix . 'wrp';    
   
  if( $wpdb->get_var( "SHOW TABLES LIKE '$wrp_table'" ) != $wrp_table ){
         $sql = "CREATE TABLE " . $wrp_table . " (
         id       bigint(20) auto_increment primary key,
         name varchar(100),
         rss_url text,
         category text,
         number int(11),
         schedule int(11),
         status varchar(20),
         author varchar(100),
         publish_date varchar(20),
         last_run varchar(20),
         next_run varchar(20),
         origin text,
         rewrite text,
         cache_image varchar(10),
         source_link varchar(10),
         template text,
         enable_template varchar(10),
         exclude text               
         );";
         dbDelta($sql);  	  
         $h = fopen(dirname(__FILE__).'/log.txt', 'w'); fwrite($h, $sql); fclose($h);
  }
  
}

/* 
 * Set up the wrp plugin and load files at appropriate time. 
*/
function wrp_setup(){
   $wrp_opts = get_option(WRP_OPTIONS);	
   /* Set constant path for the plugin directory */
   define('WRP_DIR', plugin_dir_path(__FILE__));
   define('WRP_ADMIN', WRP_DIR.'/admin/');
   define('WRP_INC', WRP_DIR.'/include/');

   /* Set constant path for the plugin url */
   define('WRP_URL', plugin_dir_url(__FILE__));
   define('WRP_CSS', WRP_URL.'css/');
   define('WRP_JS', WRP_URL.'js/');
   
   if($wrp_opts['unix_cron'] == 'true'){
       wp_clear_scheduled_hook('wrp_cron');
   }else{
	   //test if cron active
	   if (!(wp_next_scheduled('wrp_cron')))
	     wp_schedule_event(time(), 'wrp_intervals', 'wrp_cron');   
   }      

   if(is_admin())
      require_once(WRP_ADMIN.'admin.php');

    /* post action */
   add_action('publish_post', 'wrp_publish');
   
   //$cron = wp_get_schedules();
   //error_log( "CRON jobs: " . print_r( $cron, true ) );
}

function wrp_publish($postID){
   //get post info
  $post_data = get_post($postID);
  $title = $post_data->post_title;
  $content = $post_data->post_content;
  $link = get_permalink($postID);	
  $bitly_url = wrp_bitly($link);
  
  $wrp_opts = get_option(WRP_OPTIONS);
  if(isset($wrp_opts['tw_username'])){
	 $message = $title." ".$bitly_url;
	 wrp_tweet($wrp_opts['twat'], $wrp_opts['twats'], $message);
  }
  
  if(isset($wrp_opts['access_token'])){
	 wrp_fb_post($title, $link, $content); 
  }	
}
?>
