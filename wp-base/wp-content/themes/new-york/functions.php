<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
?>
<?php
function widget_newyork_search() {
?>
<h2>Search</h2>
<form id="searchform" method="get" action="<?php bloginfo('home'); ?>/"> <input type="text" value="type, hit enter" onfocus="if (this.value == 'type, hit enter') {this.value = '';}" onblur="if (this.value == '') {this.value = 'type, hit enter';}" size="18" maxlength="50" name="s" id="s" /> </form> 
<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_newyork_search');
?>
<?php
$themename = "New York";
$shortname = "newyork";
$options = array (
	array(	"name" => "Sidebar 125 x 125 Ads",
			"type" => "heading"),
    array(	"name" => "Show sidebar ads?",
			"desc" => "Check this box if you want to show sidebar ads.<br /><br />",
			"id" => $shortname."_ad_check",
			"std" => "false",
            "type" => "checkbox"),
	array(	"name" => "Ad 1 Image",
			"id" => $shortname."_ad1",
			"std" => "",
            "desc" => "Insert the image path for the banner (125x125 pixels)",
			"type" => "text"),
	array(	"name" => "Ad 1 Link",
			"id" => $shortname."_ad1_link",
			"std" => "",
            "desc" => "Insert link for the banner.<br /><br />",
			"type" => "text"),
	array(	"name" => "Ad 2 Image",
			"id" => $shortname."_ad2",
			"std" => "",
            "desc" => "Insert the image path for the banner (125x125 pixels)",
			"type" => "text"),
	array(	"name" => "Ad 2 Link",
			"id" => $shortname."_ad2_link",
			"std" => "",
            "desc" => "Insert link for the banner.<br /><br />",
			"type" => "text"),
	array(	"name" => "Ad 3 Image",
			"id" => $shortname."_ad3",
			"std" => "",
            "desc" => "Insert the image path for the banner (125x125 pixels)",
			"type" => "text"),
	array(	"name" => "Ad 3 Link",
			"id" => $shortname."_ad3_link",
			"std" => "",
            "desc" => "Insert link for the banner.<br /><br />",
			"type" => "text"),
	array(	"name" => "Ad 4 Image",
			"id" => $shortname."_ad4",
			"std" => "",
            "desc" => "Insert the image path for the banner (125x125 pixels)",
			"type" => "text"),
	array(	"name" => "Ad 4 Link",
			"id" => $shortname."_ad4_link",
			"std" => "",
            "desc" => "Insert link for the banner.<br /><br />",
			"type" => "text"),
	array(	"name" => "Email subscription settings",
			"type" => "heading"),
	array(  "name" => "Feedburner Title",
            "id" => $shortname."_feed_name",
            "std" => "",
			"desc" => "Enter your feedburner feed title (needed for subscription form).<br /><br />",
            "type" => "text"),
	array(	"name" => "Social buttons settings",
			"type" => "heading"),
	array(  "name" => "Feedburner URL",
            "id" => $shortname."_feed_url",
            "std" => "?feed=rss2",
			"desc" => "Enter your feedburner feed url or leave it for default feed address.<br /><br />",
            "type" => "text"),
	array(  "name" => "Your twitter profile link",
            "id" => $shortname."_twitter_link",
            "std" => "",
			"desc" => "Enter your Twitter profile link.<br /><br />",
            "type" => "text"),
	array(  "name" => "Your Facebook link",
            "id" => $shortname."_facebook_link",
            "std" => "",
			"desc" => "Enter your Facebook profile or fan page link.<br /><br />",
            "type" => "text"),
	array(	"name" => "About Me Box",
			"type" => "heading"),
	array(  "name" => "About Me text",
            "id" => $shortname."_aboutme_text",
            "std" => "",
			"desc" => "Write something about yourself.<br /><br />",
            "type" => "textarea"),
	array(  "name" => "About Page Link",
            "id" => $shortname."_aboutme_link",
            "std" => "",
			"desc" => "Enter your About page link.<br /><br />",
            "type" => "text"),
		array(  "name" => "About Me Photo",
            "id" => $shortname."_aboutme_image",
            "std" => "",
			"desc" => "Enter image link for About Me photo. Dimensions must be 70px by 70px.<br /><br />",
            "type" => "text"),
);

// ADMIN PANEL

function newyork_add_admin() {

	 global $themename, $options;
	
	if ( $_GET['page'] == basename(__FILE__) ) {	
        if ( 'save' == $_REQUEST['action'] ) {
	
                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
						}
					}
				}
						
				header("Location: admin.php?page=functions.php&saved=true");								
			
			die;

		} else if ( 'reset' == $_REQUEST['action'] ) {
			delete_option('sandbox_logo');
			
			header("Location: admin.php?page=functions.php&reset=true");
			die;
		}

	}

add_menu_page($themename." Options", $themename." Options", 'edit_themes', basename(__FILE__), 'newyork_page');

}

function newyork_page (){

		global $options, $themename, $manualurl;
		
		?>


<div class="wrap">
<h2><?php echo $themename; ?> settings</h2>
<form method="post">
<table class="optiontable">
<?php foreach ($options as $value) {
if ($value['type'] == "text") {  ?>
<tr valign="top">
	<th scope="row"><?php echo $value['name']; ?>:</th>
	<td>
		<input style="width:500px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
        <br /><?php echo $value['desc']; ?>
    </td>
</tr>
<?php } elseif ($value['type'] == "textarea") {  ?>
<tr valign="top">
	<th scope="row"><?php echo $value['name']; ?>:</th>
	<td>
				<textarea style="width:500px;height:100px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" ><?php
				if( get_settings($value['id']) != "") {
						echo stripslashes(get_settings($value['id']));
					}else{
						echo $value['std'];
				}?></textarea>
        <br /><?php echo $value['desc']; ?>
	</td>
</tr>
<?php } elseif ($value['type'] == "checkbox") {  ?>
<tr valign="top">
	<th scope="row"><?php echo $value['name']; ?></th>
	<td>
	    <?php if(get_settings($value['id'])){
		    $checked = "checked=\"checked\"";
			    }else{
			$checked = "";
				}
		?>
		    <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
        <?php echo $value['desc']; ?>
	</td>
</tr>
<?php } elseif ($value['type'] == "heading") {  ?>
<tr valign="top">
	<th scope="row"></th>
	<td>
        <h3><?php echo $value['name']; ?></h3>
	</td>
</tr>
<?php
}
}
?>
</table>
<br />
<strong>New York was brought to you by <a href="http://www.wpskinner.com/" title="WP Skinner - Free WP themes, tips and tutorials">Wp Skinner</a></strong>
<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<?php
}
add_action('admin_menu', 'newyork_add_admin');
?>