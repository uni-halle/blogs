<?php

/******************************************************************************************************************
 
Plugin Name: CETS Simple Dashboard

Plugin URI:  http://wordpress.org/extend/plugins/wpmu-simple-dashboard/

Description: WordPress MultiSite plugin for simplifying the wordpress dashboard. 

Version: 1.6.1

Author: Deanna Schneider, Kevin Graeme, and Jason Lemahieu

Copyright:

    Copyright 2009 - 2011 CETS

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    
	modified 10/31/08
            
*******************************************************************************************************************/

/* **********************************************************************************
Set up the dashboard each time a user comes there 
********************************************************************************* */

function cets_simple_dashboard_setup() {
	global $wp_version, $wp_meta_boxes, $_wp_contextual_help;
	
	// get the site options
	$dashoptions = get_site_option('cets_dashoptions');
	
	// this is the way it was done in 2.6.5
	if ( version_compare( $wp_version, '2.7', '<' ) ) {
		
		
		if ($dashoptions['primaryfeed'] == TRUE) {
			wp_unregister_sidebar_widget("dashboard_primary");		
			wp_unregister_widget_control("dashboard_primary");
		} 
		
		if ($dashoptions['secondaryfeed'] == TRUE){
			 wp_unregister_sidebar_widget("dashboard_secondary");
			 wp_unregister_widget_control("dashboard_secondary");
	 	}
		 
		if ($dashoptions['incominglinks'] == TRUE){
			 wp_unregister_sidebar_widget("dashboard_incoming_links");
			 wp_unregister_widget_control("dashboard_incoming_links");
		}
		if ($dashoptions['recentcomments'] == TRUE){
			 wp_unregister_sidebar_widget("dashboard_recent_comments");
			 wp_unregister_widget_control("dashboard_recent_comments");
		}
		
	 
		 // If the primary feed has been overloaded, it doesn't make sense to let users edit it - it will get overwritten anytime the site admin updates it.
		 
		 if (strlen($dashoptions['feed'])) {
			wp_unregister_widget_control("dashboard_primary");
		 }
	 
	 }
	 // this is the way it's done in 2.7
	 if ( version_compare( $wp_version, '2.7', '>=' ) ) {
	 	
		if (strlen($dashoptions['helptext'])){
			unset($_wp_contextual_help['dashboard']);	
			$_wp_contextual_help['dashboard'] = stripslashes($dashoptions['helptext']);
		}
		
		if (strlen($dashoptions['otherhelptext'])){
			add_filter('default_contextual_help', 'cets_simple_dashboard_other_default_help');
		}
	 	
		
		if ($dashoptions['rightnow'] == TRUE) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		} 
		 if ($dashoptions['primaryfeed'] == TRUE) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		} 
		
		if ($dashoptions['secondaryfeed'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	 	}
		 
		if ($dashoptions['incominglinks'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
			 
		}
		if ($dashoptions['recentcomments'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		}	
		if ($dashoptions['recentdrafts'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		}	
		if ($dashoptions['plugins'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		}
		if ($dashoptions['quickpress'] == TRUE){
			 unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		}
		
		
		 // If the primary feed has been overloaded & user controls disabled, it doesn't make sense to let users edit it - it will get overwritten anytime the site admin updates it.
		 
		 if (strlen($dashoptions['feed'])) {
		 	//re-add the dashboard feed, minus the control
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
			
			if ($dashoptions['disable_primaryfeed_uc'] == true) {
				wp_add_dashboard_widget( 'dashboard_primary', $dashoptions['feedtitle'], 'wp_dashboard_primary');
			}
			else {
				wp_add_dashboard_widget( 'dashboard_primary', $dashoptions['feedtitle'], 'wp_dashboard_primary', 'wp_dashboard_primary_control');
			}
			
		 }
		
		// ditto secondary feed
		 if (strlen($dashoptions['sfeed'])) {
		 	//re-add the dashboard feed
			
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
			
			if ($dashoptions['disable_sfeed_uc'] == true){
				wp_add_dashboard_widget( 'dashboard_secondary', $dashoptions['sfeedtitle'], 'wp_dashboard_secondary');
			}
			else {
				wp_add_dashboard_widget( 'dashboard_secondary', $dashoptions['sfeedtitle'], 'wp_dashboard_secondary', 'wp_dashboard_secondary_control');
			}
			
		 }
		
	}
	 
}


function cets_dashboard_help($text){
	global $current_screen;
	
	if ($current_screen->id == 'dashboard') {
		$dashoptions = get_site_option('cets_dashoptions');
		if (strlen($dashoptions['helptext'])) {
			$text = stripslashes($dashoptions['helptext']);	
		} 
	}
			
	return $text;
	
}

function cets_simple_dashboard_other_default_help($default_help) {
	$dashoptions = get_site_option('cets_dashoptions');
	return stripslashes($dashoptions['otherhelptext']);
	
	
}


/* *************************************************************************
Create the page for the admins to set up stuff
************************************************************************** */
function cets_simple_dashboard_admin() {
	global $wpdb, $wp_version, $wp_meta_boxes;
	$dashoptions = get_site_option('cets_dashoptions');
	$primaryfeed = $dashoptions['primaryfeed'];
	$primaryfeedchecked = ($primaryfeed == 1) ? 'checked= checked' : '';
	$secondaryfeed = $dashoptions['secondaryfeed'];
	$secondaryfeedchecked = ($secondaryfeed == 1) ? 'checked= checked' : '';
	$incominglinks = $dashoptions['incominglinks'];
	$incominglinks = ($incominglinks == 1) ? 'checked= checked' : '';
	$recentcomments = $dashoptions['recentcomments'];
	$recentcommentschecked = ($recentcomments == 1) ? 'checked= checked' : '';
	$feedtitle = esc_html($dashoptions['feedtitle']);
	$feedlink = esc_html($dashoptions['feedlink']);
	$feed = esc_html($dashoptions['feed']);
	// 2.7 options
	$recentdrafts = $dashoptions['recentdrafts'];
	$recentdraftschecked = ($recentdrafts == 1) ? 'checked= checked' : '';
	$plugins = $dashoptions['plugins'];
	$pluginschecked = ($plugins == 1) ? 'checked= checked' : '';
	$quickpress = $dashoptions['quickpress'];
	$quickpresschecked = ($quickpress == 1) ? 'checked= checked' : '';
	$disable_primaryfeed_uc = $dashoptions['disable_primaryfeed_uc'];
	$disable_primaryfeed_uc_checked = ($disable_primaryfeed_uc == 1) ? 'checked= checked' : '';
	$rightnow = $dashoptions['rightnow'];
	$rightnowchecked = ($rightnow == 1) ? 'checked= checked' : '';
	//secondary feed
	$sfeedtitle = esc_html($dashoptions['sfeedtitle']);
	$sfeedlink = esc_html($dashoptions['sfeedlink']);
	$sfeed = esc_html($dashoptions['sfeed']);
	$disable_sfeed_uc = $dashoptions['disable_sfeed_uc'];
	$disable_sfeed_uc_checked = ($disable_sfeed_uc == 1) ? 'checked= checked' : '';
	
	// help
	$helptext = stripslashes($dashoptions['helptext']);
	$otherhelptext = stripslashes($dashoptions['otherhelptext']);
	

	
	

	?><h3><?php _e('Simple Dashboard Options') ?></h3> 
   		
        
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Remove the following widgets:') ?></th> 
			<td>
				<input name="cets_sd_primaryfeed" type="checkbox" id="cets_sd_primaryfeed"  value="1" <?php echo $primaryfeedchecked; ?> /> <?php _e('Primary Feed (Do not remove if you wish to override it below.)'); ?>
				<br />
				<input name="cets_sd_secondaryfeed" type="checkbox" id="cets_sd_secondaryfeed"  value="1" <?php echo $secondaryfeedchecked; ?> /> <?php _e('Secondary Feed (Do not remove if you wish to override it below.)'); ?>
				<br />
				<input name="cets_sd_incominglinks" type="checkbox" id="cets_sd_incominglinks"  value="1" <?php echo $incominglinks; ?> /> <?php _e('Incoming Links'); ?>
				<br />
				<input name="cets_sd_recentcomments" type="checkbox" id="cets_sd_recentcomments"  value="1" <?php echo $recentcommentschecked; ?> /> <?php _e('Recent Comments'); ?>
				<?php // options that are only relevant for 2.7
				if ( version_compare( $wp_version, '2.7', '>=' ) ) { 
				?>
				<br />
				<input name="cets_sd_recentdrafts" type="checkbox" id="cets_sd_recentdrafts"  value="1" <?php echo $recentdraftschecked; ?> /> <?php _e('Recent Drafts'); ?>
				<br />
				<input name="cets_sd_plugins" type="checkbox" id="cets_sd_plugins"  value="1" <?php echo $pluginschecked; ?> /> <?php _e('Plugins'); ?>
				<br />
				<input name="cets_sd_quickpress" type="checkbox" id="cets_sd_quickpress"  value="1" <?php echo $quickpresschecked; ?> /> <?php _e('Quick Press'); ?>
				<br />
				<input name="cets_sd_rightnow" type="checkbox" id="cets_sd_rightnow"  value="1" <?php echo $rightnowchecked; ?> /> <?php _e('Right Now'); ?>
				
				<?php 
				} 
				?>
			
			</td> 
		</tr>
        <tr valign="top">
			<th scope="row"><?php _e('Modify the Primary Feed') ?></th> 
			<td>
				Feed Title: <input type="text" name="cets_sd_feedtitle" id="cets_sd_feedtitle" style="width: 45%;" value="<?php echo $feedtitle; ?>" /> <br /> 
                <span style="font-size:smaller">(Ex: WordPress Development Blog)</span><br/>
				Feed URL: <input type="text" name="cets_sd_feed" id="cets_sd_feed" style="width: 45%;" value="<?php echo $feed; ?>" /> <br />
                <span style="font-size:smaller">(Ex: http://wordpress.org/development/feed/)</span><br/>
				Blog URL: <input type="text" name="cets_sd_feedlink" id="cets_sd_feedlink" style="width: 45%;" value="<?php echo $feedlink; ?>" /> <br />
                <span style="font-size:smaller">(Ex: http://wordpress.org/development/)</span><br/>
				<?php if ( version_compare( $wp_version, '2.7', '>=' ) && version_compare($wp_version, '3.0', '<') ) {  ?>
				Disable User Controls: <input name="cets_sd_disable_primaryfeed_uc" type="checkbox" id="cets_sd_disable_primaryfeed_uc"  value="1" <?php echo $disable_primaryfeed_uc_checked; ?> />
				<?php } ?>
			</td> 
		</tr>
		<?php if ( version_compare( $wp_version, '2.7', '>=' ) ) {  ?>
		<tr valign="top">
			<th scope="row"><?php _e('Modify the Secondary Feed') ?></th> 
			<td>
				Feed Title: <input type="text" name="cets_sd_sfeedtitle" id="cets_sd_sfeedtitle" style="width: 45%;" value="<?php echo $sfeedtitle; ?>" /> <br /> 
                <span style="font-size:smaller">(Ex: WordPress Development Blog)</span><br/>
				Feed URL: <input type="text" name="cets_sd_sfeed" id="cets_sd_sfeed" style="width: 45%;" value="<?php echo $sfeed; ?>" /> <br />
                <span style="font-size:smaller">(Ex: http://wordpress.org/development/feed/)</span><br/>
				Blog URL: <input type="text" name="cets_sd_sfeedlink" id="cets_sd_sfeedlink" style="width: 45%;" value="<?php echo $sfeedlink; ?>" /> <br />
                <span style="font-size:smaller">(Ex: http://wordpress.org/development/)</span><br/>
				<?php if (version_compare($wp_version, '3.0', '<')){?>
				Disable User Controls: <input name="cets_sd_disable_sfeed_uc" type="checkbox" id="cets_sd_disable_sfeed_uc"  value="1" <?php echo $disable_sfeed_uc_checked; ?> />
				<?php } // end 3.0 check
		
				} // end 2.7 check ?>
			</td> 
		</tr>
		
		<tr valign="top">
			<th scope="row"><?php _e('Change Help Text') ?></th>
			<td>
				<textarea name="cets_sd_helptext" id="cets_sd_helptext" style="width: 95%;" rows="5"><?php echo ($helptext);?></textarea>
			</td>
		</tr>
		<?php if (version_compare($wp_version, '3.0', '<')){?>
		<tr valign="top">
			<th scope="row"><?php _e('Change Other Help Text') ?></th>
			<td>
				<textarea name="cets_sd_otherhelptext" id="cets_sd_otherhelptext" style="width: 95%;" rows="5"><?php echo ($otherhelptext);?></textarea>
			</td>
		</tr>
       <?php }?>
	</table>
    
	<?php
	

}
/* *************************************************************************
Handle the posts when admins update options
************************************************************************** */
function cets_simple_dashboard_admin_update() {
	global $wpdb, $wp_version;
	
	// get the list of blogs we may need to update
	$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC", $wpdb->siteid), ARRAY_A );
	
	$options = array('primaryfeed'=>FALSE,
			 'secondaryfeed' => FALSE,
			 'incominglinks'=>FALSE);
	$options['primaryfeed'] = ($_POST['cets_sd_primaryfeed'] == 1) ? TRUE : FALSE;
	$options['secondaryfeed'] = ($_POST['cets_sd_secondaryfeed'] == 1) ? TRUE : FALSE;
	$options['incominglinks'] = ($_POST['cets_sd_incominglinks'] == 1) ? TRUE : FALSE;
	$options['recentcomments'] = ($_POST['cets_sd_recentcomments'] == 1) ? TRUE : FALSE;
	$options['feedtitle'] = stripslashes_deep(htmlentities(strip_tags($_POST['cets_sd_feedtitle'])));
	$options['feedlink'] = htmlentities(strip_tags($_POST['cets_sd_feedlink']));
	$options['feed'] = htmlentities(strip_tags($_POST['cets_sd_feed']));
	// deal with 2.7 options
	if ( version_compare( $wp_version, '2.7', '>=' ) ) {
		$options['recentdrafts'] = ($_POST['cets_sd_recentdrafts'] == 1) ? TRUE : FALSE;
		$options['plugins'] = ($_POST['cets_sd_plugins'] == 1) ? TRUE : FALSE;
		$options['quickpress'] = ($_POST['cets_sd_quickpress'] == 1) ? TRUE : FALSE;
		$options['rightnow'] = ($_POST['cets_sd_rightnow'] == 1) ? TRUE : FALSE;
		$options['disable_primaryfeed_uc'] = ($_POST['cets_sd_disable_primaryfeed_uc'] == 1) ? TRUE : FALSE;
		
		// secondary feed
		$options['sfeedtitle'] = stripslashes_deep(htmlentities(strip_tags($_POST['cets_sd_sfeedtitle'])));
		$options['sfeedlink'] = htmlentities(strip_tags($_POST['cets_sd_sfeedlink']));
		$options['sfeed'] = htmlentities(strip_tags($_POST['cets_sd_sfeed']));
		$options['disable_sfeed_uc'] = ($_POST['cets_sd_disable_sfeed_uc'] == 1) ? TRUE : FALSE;
		}
	
	
	$options['helptext'] = $_POST['cets_sd_helptext'];
	$options['otherhelptext'] = $_POST['cets_sd_otherhelptext'];
	
	if (!is_array(get_site_option('cets_dashoptions'))) {
		add_site_option('cets_dashoptions', $options);
	} 
	else {
		update_site_option('cets_dashoptions', $options);
	}
	// get the options
	$dashoptions = get_site_option('cets_dashoptions');
	
	
	// If someone wants to overwrite the primary or secondary feed, set up the variables and override the options on a blog by blog basis
	if (strlen($dashoptions['feed']) || strlen($dashoptions['sfeed'])) {
	
		// This really only needs to run if something is different from what it used to be, so check that.
		$oldoptions = get_option('dashboard_widget_options');
			
		if ($dashoptions['feed'] != $oldoptions['dashboard_primary']['url'] || 
			$dashoptions['feedlink'] != $oldoptions['dashboard_primary']['link'] ||
			$dashoptions['feedtitle'] != $oldoptions['dashboard_primary']['title'] || 
			$dashoptions['sfeed'] != $oldoptions['dashboard_secondary']['url'] || 
			$dashoptions['sfeedlink'] != $oldoptions['dashboard_secondary']['link'] ||
			$dashoptions['sfeedtitle'] != $oldoptions['dashboard_secondary']['title']){
	
			if (strlen($dashoptions['feed'])) {
				$widget_options['dashboard_primary'] = array(
				'link' => apply_filters( 'dashboard_primary_link',  __( $dashoptions['feedlink'] ) ),
				'url' => apply_filters( 'dashboard_primary_feed',  __( $dashoptions['feed'] ) ),
				'title' => apply_filters( 'dashboard_primary_title', __( $dashoptions['feedtitle'] ) ),
				'items' => 2,
				'show_summary' => 1,
				'show_author' => 0,
				'show_date' => 1);
			}
			
			if (strlen($dashoptions['sfeed'])){
				$widget_options['dashboard_secondary'] = array(
				'link' => apply_filters( 'dashboard_secondary_link',  __( $dashoptions['sfeedlink'] ) ),
				'url' => apply_filters( 'dashboard_secondary_feed',  __( $dashoptions['sfeed'] ) ),
				'title' => apply_filters( 'dashboard_secondary_title', __( $dashoptions['sfeedtitle'] ) ),
				'items' => 2,
				'show_summary' => 1,
				'show_author' => 0,
				'show_date' => 1);
				
			}
				
				
			
			// loop through all the blogs and edit their dashboard widget options 
					
				foreach ($blogs as $key => $details){
				switch_to_blog($details['blog_id']);
				update_option( 'dashboard_widget_options', $widget_options );
				restore_current_blog();
				
				}
				
				
			
		}// end if nothing is new
	}//end if feed has a value
	
	
	
	
	
	
	
}

/* ********************************************************************************************************************
This function applies the customized options when a new blog is created
********************************************************************************************************************** */
function cets_modify_new_blog_dashboard($blog_id) {
	
	// look for the dashboard options
	$dashoptions = get_site_option('cets_dashoptions');
	// If someone wants to overwrite the primary feed, set up the variables and override the options on a blog by blog basis
	if (strlen($dashoptions['feed']) || strlen($dashoptions['sfeed'])) {
		
		if (strlen($dashoptions['feed'])){
		$widget_options['dashboard_primary'] = array(
			'link' => apply_filters( 'dashboard_primary_link',  __( $dashoptions['feedlink'] ) ),
			'url' => apply_filters( 'dashboard_primary_feed',  __( $dashoptions['feed'] ) ),
			'title' => apply_filters( 'dashboard_primary_title', __( $dashoptions['feedtitle'] ) ),
			'items' => 2,
			'show_summary' => 1,
			'show_author' => 0,
			'show_date' => 1);
		}
		
		if (strlen($dashoptions['sfeed'])){
			$widget_options['dashboard_secondary'] = array(
			'link' => apply_filters( 'dashboard_secondary_link',  __( $dashoptions['sfeedlink'] ) ),
			'url' => apply_filters( 'dashboard_secondary_feed',  __( $dashoptions['sfeed'] ) ),
			'title' => apply_filters( 'dashboard_secondary_title', __( $dashoptions['sfeedtitle'] ) ),
			'items' => 2,
			'show_summary' => 1,
			'show_author' => 0,
			'show_date' => 1);
			}
		
		
		switch_to_blog($blog_id);
		//dashboard_widget_options is a built-in convention that WP uses to store modifications to the dashboard widgets.	
		add_option(	'dashboard_widget_options', $widget_options );
		restore_current_blog();
	
	} // end if		
	
	
}



/* ************************************************************************************************************
Add all the actions and filters
************************************************************************************************************** */

add_action( 'wp_dashboard_setup', 'cets_simple_dashboard_setup' );
add_action('wpmu_options', 'cets_simple_dashboard_admin');
add_action( 'update_wpmu_options', 'cets_simple_dashboard_admin_update' );
add_filter('wpmu_new_blog', 'cets_modify_new_blog_dashboard');
add_action('contextual_help', 'cets_dashboard_help');
