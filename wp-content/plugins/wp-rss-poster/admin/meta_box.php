<?php
function wrp_campagins_template_meta_box(){
   global $wrp;
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
     $template = $data->template;
   }else{
	 $template = '{content}<br />{title}<br />{permalink}<br />{feedurl}<br />{feedtitle}<br />{feeddescription}<br />{feedlogo}';   
   }
?>
  <table class="form-table">
    <tr>
	    <th>
        	<label for="enable_template">Enable Template:</label> 
	   </th>	 
	   <td>
		    <input type="checkbox" value="true" name="enable_template" <?php if($data->enable_template == 'true'){echo 'checked="checked"'; }?> />
	   </td> 
	 </tr> 
     <tr>
	   <th>
        	<label for="template"><?php _e( 'Post Template:', 'wrp' ); ?></label>
	   </th>
	   <td>
	        <textarea rows="4" cols="65" name="template"><?php echo $template; ?></textarea>
            <br />
            <span>You can use the following tag on template:</span><br /><small>{content}{title}{permalink}{feedurl}{feedtitle}{feeddescription}{feedlogo}</small>   
	   </td>
	  </tr>
   </table>	   
   
<?php   	
}
function wrp_cron_meta_box(){
   $wrp_opts = get_option(WRP_OPTIONS);
   $unix_cron = $wrp_opts['unix_cron']; 
?>	
   <p><input type="checkbox" value="true" name="unix_cron" <?php if($unix_cron == 'true'){echo 'checked="checked"'; }?> />
   <label>Enable Unix Cron</label></p>
   
   <p>command: <i> php -q <?PHP echo ABSPATH.'wp-content/plugins/wp-rss-poster/cron.php'; ?></i></p>   
<?php      
}

function wrp_twitter_meta_box(){
     $wrp_opts = get_option(WRP_OPTIONS);
          
     if(!isset($wrp_opts['tw_username'])){
	   $url = admin_url( 'admin.php?page=wrp-twitter&connect=tw' );	 
?>
       <a href=<?php echo $url; ?>>Sign In</a>       	 
<?php
     }else{
		$twitter_name = $wrp_opts['tw_username'];
		$profile = $wrp_opts['tw_profile'];
		$dis_url = admin_url( 'admin.php?page=wrp-twitter&disconnect=tw' );	
?>
        <table class="form-table">
		<tr>
			<th>
            	<label for="action"><?php _e( 'Action:', 'wrp' ); ?></label> 
            </th>
            <td>
            	<a href="<?php echo $dis_url?>">Disconnect</a>
            </td>
		</tr>
	    <tr>
			<th>
            	<label for="userinfo"><?php _e( 'User Info:', 'wrp' ); ?></label> 
            </th>
            <td>
            	<img src="<?php echo $profile; ?>" />
                <p><?php echo $twitter_name; ?></p>
            </td>
		</tr>	
	    </table><!-- .form-table -->
        
        
<?php		 	 
     }
}

function wrp_facebook_meta_box(){
 $wrp_opts = get_option(WRP_OPTIONS);
 if(isset($wrp_opts['access_token'])){
	 $fb = new Facebook(array(
	'appId'  => FB_APP_ID,
	'secret' => FB_APP_SECRET	
    ));
    $me = $fb->api('/me', array('access_token' => $wrp_opts['access_token']));
    
	 $fb_name = $me['name'];
	 $uid = $wrp_opts['uid'];
	 $dis_url = admin_url( 'admin.php?page=wrp-facebook&disconnect=fb' );
?>
    <table class="form-table">
		<tr>
			<th>
            	<label for="action"><?php _e( 'Action:', 'wrp' ); ?></label> 
            </th>
            <td>
            	<a href="<?php echo $dis_url; ?>">Disconnect</a>
            </td>
		</tr>
		<tr>
			<th>
            	<label for="userinfo"><?php _e( 'User Info:', 'wrp' ); ?></label> 
            </th>
            <td>
                <img src="https://graph.facebook.com/<?php echo $uid; ?>/picture">
                <p><?php echo $fb_name; ?></p>         	
            </td>
		</tr>	
	</table><!-- .form-table -->   
<?php      
  }else{
	 $return = urlencode(admin_url( 'admin.php?page=wrp-facebook&connect=fb&resp=1' ));
	 $url = "http://wp-coder.net/fb/request_permissions.php?return=".$return;	 
?>
       <a href=<?php echo $url; ?>>Sign In</a>       	  
<?php
  }	
}

function wrp_campagins_schedule_meta_box(){
global $wrp;
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
     $schedule = $data->schedule;
   }
?>
     Update every: <input type="text" name="schedule" value="<?php if(isset($schedule)){echo $schedule;}else{echo 5;} ?>" /> hours 
<?php	
}


function wrp_campagins_number_meta_box(){
  global $wrp;
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
     $number = $data->number;
   }
?>
  Latest feed number: <input type="text" name="number" value="<?php if(isset($number)){echo $number;}else{echo 5;} ?>" /> 
 
<?php
}

function wrp_campagins_new_category_meta_box(){
  global $wrp;
?> 
  <input type="text" name="new_category" value="" /> 				
<?php   	
}

function wrp_campagins_category_meta_box(){
   global $wrp;
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
   }
?>
  <ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
		<?php 
		 if(isset($data)){
		   $selected_cats = json_decode($data->category);	 
		   wp_category_checklist( 0, 0, $selected_cats);	 
		 }else{
		   wp_category_checklist(); 	 
		 }
		?>
  </ul>	
<?php
}

function wrp_campagins_exclude_meta_box(){
  if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
   }
?>
  <table class="form-table">
	 <tr>
	   <th>
        	<label for="exclude"><?php _e( 'Exclude:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <textarea rows="4" cols="65" name="exclude"><?php if(isset($data)){echo $data->exclude; } ?></textarea>
	        <br />
		      <span>This plugin will not fetch data after this end point.</span>
	   </td> 
	 </tr> 	    
	</table><!-- .form-table -->	
<?php
}

function wrp_campagins_rewrite_meta_box(){
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
   }
?>
   <table class="form-table">
	 <tr>
	   <th>
        	<label for="origin"><?php _e( 'Origin:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <textarea rows="4" cols="65" name="origin"><?php if(isset($data)){echo $data->origin; } ?></textarea>
	        <br />
		      <span>Please separate the words by comma.</span>
	   </td> 
	 </tr> 
	 <tr>
	   <th>
        	<label for="rewrite"><?php _e( 'Rewrite to:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <textarea rows="4" cols="65" name="rewrite"><?php if(isset($data)){echo $data->rewrite; } ?></textarea>
	        <br />
		      <span>Also separate the corresponding words by comma.</span>
	   </td> 
	 </tr>      
	</table><!-- .form-table -->
<?php	 	
}

function wrp_campagins_rss_meta_box(){
   global $wrp;
   $status = array(1 => 'publish', 2 => 'draft', 3 => 'private');
   $publish_date = array('Immediately' => 'immediately', 'RSS Publish Date' => 'rss');
   if(!empty($_GET['id'])){
     global $wpdb;
     $wrp_table = $wpdb->prefix.'wrp';
     $query = "SELECT * FROM $wrp_table WHERE id = ".$_GET['id'];
     $data = $wpdb->get_row($query);                    
   }
?>
   <table class="form-table">
	 <tr>
	   <th>
        	<label for="url"><?php _e( 'Feed Url:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <input id="url" name="url" type="text" value="<?php if(isset($data)){echo $data->rss_url; }?>" />
	   </td> 
	 </tr>
	 <tr>
	   <th>
        	<label for="status"><?php _e( 'Post Status:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <select name="status" >
                <?php foreach($status as $key => $value) { ?>
                  <option value="<?php echo $value; ?>" <?php if(isset($data)){selected($value, $data->status);} ?> > <?php echo $value; ?></option> 
                <?php } ?>
            </select>
	   </td> 
	 </tr> 
	 <tr>
	   <th>
        	<label for="author"><?php _e( 'Author:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <?php wp_dropdown_users(array('name' => 'author','selected' => $data->author)); ?>
	   </td> 
	 </tr>
	 <tr>
	   <th>
        	<label for="date"><?php _e( 'Publish Date:', 'wrp' ); ?></label>
	   </th>	 
	   <td>
		    <select name="date" >
                <?php foreach($publish_date as $key => $value) { ?>
                  <option value="<?php echo $value; ?>" <?php if(isset($data)){selected($value, $data->publish_date);} ?> > <?php echo $key; ?></option> 
                <?php } ?>
            </select>
	   </td> 
	 </tr> 
	 <tr>
	    <th>
        	<label for="cache">Cache Images for this campaign</label> 
	   </th>	 
	   <td>
		    <input type="checkbox" value="true" name="cache" <?php if($data->cache_image == 'true'){echo 'checked="checked"'; }?> />
	   </td> 
	 </tr> 
	 <tr>
	    <th>
        	<label for="source">Show Links from source</label> 
	   </th>	 
	   <td>
		    <input type="checkbox" value="true" name="source" <?php if($data->source_link == 'true'){echo 'checked="checked"'; }?> />
	   </td> 
	 </tr>    
	</table><!-- .form-table -->
<?php	
}
?>
