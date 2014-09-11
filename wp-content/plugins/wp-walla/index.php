<?php
/*
Plugin Name: WP-Walla
Plugin URI: http://www.baronen.org/wpwalla
Description: Gets your latest checkin's from Gowalla. Available as a sidebarwidget or just put a code snippet in your template.
Version: 0.5.3.5
Author: Andreas Eriksson
Author URI: http://www.baronen.org
*/
	
	define('WPWALLA_VERSION', '0.5.3.1');
	require "Wpwalla.php";
	
	$wpTimezone = get_option('timezone_string');
	date_default_timezone_set($wpTimezone);
	
	function wpwalla_admin()
	{
		include('wpwalla_admin.php'); // Include an extra file for the admin functions
	}
	
	function wpwalla_admin_menu() 
	{
		add_options_page("WP-Walla", "WP-Walla", 1, "WP-Walla", "wpwalla_admin");
	}
	
	class WP_Walla extends WP_Widget
	{
		public function WP_Walla()
		{
			$widget_ops = array('classname' => 'wpwalla', 'description' => 'Shows your latest checkin\'s from Gowalla', 'id_base' => 'wpwalla' );
			$this->WP_Widget('wp_walla', 'WP-Walla', $widget_ops);
			
			if ( is_active_widget(false, false, $this->id_base) )
				add_action('wp_head', array(&$this, 'wpwalla_widget_style') );
		}
		
		public function widget($args = null, $instance = null)
		{
			if($args  === null) {
				$args = $this->argsWithoutWidget();
			}
				
			$wpwallaoptions = get_option('widget_wpwalla');
			$wpwallaObj = new Wpwalla($wpwallaoptions);
			extract($args);
			$xmlData = $wpwallaObj->getWpwallaData();
			echo $before_widget;
			echo $before_title.$wpwallaoptions['wpwallatitle'].$after_title;
			if(count($xmlData->entry) > 0) {
			foreach ($xmlData->entry as $entry) {
				echo "<div class='wpwalla-item-list'>";
				if($wpwallaoptions['wpwallaicons'] == 'on')
					echo "<img src='".$entry->link[1][@href]."' class='wpwalla-item-icon'/>";
				
				$dispMode = $wpwallaoptions['wpwalladisplaymode'];
				if($dispMode == 'on') {
					preg_match('/spots\/([0-9]+)quot/', $entry->content, $matches);
					$openlink = $wpwallaoptions['wpwallaopenlink'] == 'on' ? 'target="_blank"' : '';
					echo "<a href='http://gowalla.com/spots/".$matches[1]."' ".$openlink.">".$entry->title."</a>";
				} else {
					echo $entry->title;
				}
				echo "<br />";
				$date = strtotime($entry->updated);
				$format = date(get_option('time_format')." ".get_option('date_format'), $date);
				echo $format;
				echo "</div>" . PHP_EOL;
				if(++$i == $wpwallaoptions['numbercheckins']) {
					break;
				}
			}
			} else {
				echo "Couldn't fetch Gowalla";
			}
			if($wpwallaoptions['wpwallasponsorlink'] == 'on') {
				echo "<div class='wpwalla-item-list'>";
				echo "Powered by <a href='http://www.baronen.org/wpwalla'>WP-Walla</a>";
				echo "</div>";
			}
			echo $after_widget;
			
		}
		
		public function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['wpwallatitle'] = strip_tags($new_instance['wpwallatitle']);
			$instance['numbercheckins'] = strip_tags($new_instance['numbercheckins']);
	 
			return $instance;
		}
		
		public function form($instance)
		{
			$wpwallaoptions = get_option('widget_wpwalla');
			if(strlen($wpwallaoptions['wpwallausername']) < 3) {
				echo '<p class="no-options-widget"><strong>Not Ready!</strong></p>';
				echo '<p class="no-options-widget">' . __('You must setup WP-Walla plugin under <a href="options-general.php?page=WP-Walla">WP-Walla settings</a> (settings menu).') . '</p>';
			} else {
				echo '<p class="no-options-widget">'.__('Gowalla username: ').$wpwallaoptions['wpwallausername'] . "</p>";
				echo '<p class="no-options-widget">' . __('<a href="options-general.php?page=WP-Walla">Change WP-Walla settings</a> under the WP-Walla option under settings menu.') . '</p>';
			}
			
			return 'noform';
		}
		
		public function wpwalla_widget_style() 
		{
?>
			<style type="text/css">
			.wpwalla-item-list { padding-bottom:10px;}
			.wpwalla-item-icon {width: 25px; height: 25px; float:left; padding-right: 7px}
			</style>
<?php
		}
			
		private function argsWithoutWidget()
		{
			//This part is used if you are not using the plugin as a widget
			return array(
				'name' 			=> 'Sidebar 1',
				'id' 			=> 'sidebar-1',
				'description'	=> '',
				'before_widget'	=> '',
				'after_widget' 	=> '',
				'before_title'	=> '<h3>',
				'after_title' 	=> '</h3>',
				'widget_id'		=> 'wpwalla',
				'widget_name'	=> 'WP-Walla'
			);

		}
	}
	
	function load_widgets() {
		register_widget( 'WP_Walla' );
	}
	add_action( 'widgets_init', 'load_widgets' );
	
	add_action('admin_menu', 'wpwalla_admin_menu'); // Add the Dynamic-menu to the admin-menu
	
?>
