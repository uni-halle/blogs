<?php
// helper functions
  if ( function_exists('wp_list_bookmarks') ) //used to check WP 2.1 or not
    $numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' and post_status = 'publish'");
	else
    $numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");
  if (0 < $numposts) $numposts = number_format($numposts);
	$numcmnts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
		if (0 < $numcmnts) $numcmnts = number_format($numcmnts);
// ----------------
// For backward Compatiblity to older versions of WordPress
add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/old-comments.php';
	return $file;
}
    if ( function_exists('register_sidebar') )
    register_sidebar(array(
    'before_widget' => '<li class="sidebox">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
if ( function_exists('unregister_sidebar_widget') )
	{
		unregister_sidebar_widget( __('Links') );
	}
	if ( function_exists('register_sidebar_widget') )
	{
		register_sidebar_widget(__('Links'), 'mistylook_ShowLinks');
	}
	if ( function_exists('register_sidebar_widget') )
	{
		register_sidebar_widget(__('About'), 'mistylook_ShowAbout');
	}
function mistylook_ShowAbout() {?>
<li class="sidebox">
	<h3><?php _e('About','ml');?></h3>
	<p>
	<img src="<?php bloginfo('stylesheet_directory');?>/img/profile.jpg" alt="<?php _e('Profile','ml');?>" /><br/>
	<strong><?php bloginfo('name');?></strong><br/><?php bloginfo('description');?><br/>
	<?php _e('There are','ml');?> <?php global $numposts;echo $numposts; ?> <?php _e('Posts and','ml');?> <?php global $numcmnts;echo $numcmnts;?> <?php _e('Comments so far.','ml');?>
	</p>
</li>
<?php }

function mistylook_ShowRecentPosts() {?>
<li class="sidebox">
	<h3><?php _e('Recent Posts','ml');?></h3>
	<ul><?php wp_get_archives('type=postbypost&limit=6');?></ul>
</li>
<?php }

function mistylook_ShowLinks() {?>
<li class="sidebox" id="sidelinks">
	<ul>
		<?php
			if(function_exists('wp_list_bookmarks'))
			{
				wp_list_bookmarks();
			}
			else
			{
				get_links_list('name');
			}
		?>
	</ul>
</li>
<?php  }

function mistylook_add_theme_page() {
	if ( $_GET['page'] == basename(__FILE__) ) {

	    // save settings
		if ( 'save' == $_REQUEST['action'] ) {

			update_option( 'mistylook_asideid', $_REQUEST[ 's_asideid' ] );
			update_option( 'mistylook_sortpages', $_REQUEST[ 's_sortpages' ] );
			if( isset( $_POST[ 'excludepages' ] ) ) { update_option( 'mistylook_excludepages', implode(',', $_POST['excludepages']) ); } else { delete_option( 'mistylook_excludepages' ); }
			// goto theme edit page
			header("Location: themes.php?page=functions.php&saved=true");
			die;

  		// reset settings
		} else if( 'reset' == $_REQUEST['action'] ) {

			delete_option( 'mistylook_asideid' );
			delete_option( 'mistylook_sortpages' );
			delete_option( 'mistylook_excludepages' );


			// goto theme edit page
			header("Location: themes.php?page=functions.php&reset=true");
			die;

		}
	}


    add_theme_page(__("MistyLook Options",'ml'), __("MistyLook Options",'ml'), 'edit_themes', basename(__FILE__), 'mistylook_theme_page');

}

function mistylook_theme_page() {

	// --------------------------
	// MistyLook theme page content
	// --------------------------

	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__('MistyLook Theme: Settings saved.','ml').'</strong></p></div>';
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.__('MistyLook Theme: Settings reset.','ml').'</strong></p></div>';

?>
<style>
	.wrap { border:#ccc 1px dashed;padding:10px;}
	.block { margin:1em;padding:1em;line-height:1.6em;}
	table tr td {border:#ddd 1px solid;padding:10px;font-family:Verdana, Arial, Serif;font-size:0.9em;}
	h4 {font-size:1.3em;color:#265e15;font-weight:bold;margin:0;padding:10px 0;}
</style>
<div class="wrap">

<h2>MistyLook 3.7.2</h2>

<div class="block"><h4><?php _e('Theme Page:','ml');?> <a href="http://wpthemes.info/misty-look/">MistyLook</a> </h4>
					<h4><?php _e('Designed & Coded by:','ml');?><a href="http://wprocks.com/" title="WordPress Rocks!"  target="_blank">Sadish Bala</a></h4>

</div>


<form method="post">


<!-- blog layout options -->
<fieldset class="options">
<legend><?php _e('Theme Settings','ml');?></legend>

<p><?php _e('Change the way your blog looks and acts with the many blog settings below','ml');?></p>

<table width="100%" cellspacing="5" cellpadding="10" class="editform">
<tr>
<td valign="top" colspan="2" style="border:0px;margin:0;padding:0;">
	<input type="hidden" name="action" value="save" />
	<?php ml_input( "save", "submit", "", __("Save Settings",'ml') );?>
</td>
</tr>
<tr valign="top">
<td align="left">
	<?php
	ml_heading(__("List Pages / Navigation",'ml'));
		global $wpdb;
		if (function_exists('wp_list_bookmarks')) //WP 2.1 or greater
			$results = $wpdb->get_results("SELECT ID, post_title from $wpdb->posts WHERE post_type='page' AND post_parent=0 ORDER BY post_title");
		else
			$results = $wpdb->get_results("SELECT ID, post_title from $wpdb->posts WHERE post_status='static' AND post_parent=0 ORDER BY post_title");

		$excludepages = explode(',', get_settings('mistylook_excludepages'));
		if($results) {
			echo "<br/>";_e('Exclude the Following Pages from the Top Navigation','ml');echo "<br/><br/>";
			foreach($results as $page)
      {
			  echo '<input type="checkbox" name="excludepages[]" value="' . $page->ID . '"';
        if(in_array($page->ID, $excludepages)==true) { echo ' checked="checked"'; }
				echo ' /> <a href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a><br />';
			}
		}
		echo '<br/><br/>';
		echo "<br/><strong> ";_e('Sort the List Pages by','ml');echo " </strong><br/>";

		ml_input( "s_sortpages", "radio", __("Page Title ?",'ml'), "post_title", get_settings( 'mistylook_sortpages' ) );
		ml_input( "s_sortpages", "radio", __("Date ?",'ml'), "post_date", get_settings( 'mistylook_sortpages' ) );
		ml_input( "s_sortpages", "radio", __("Page Order ?",'ml'), "menu_order", get_settings( 'mistylook_sortpages' ) );
		_e("(Each Page can be given a page order number, from the wordpress admin, edit page area)",'ml');
		echo "<br/>";
?>
</td>
<td>
<?php
	ml_heading( __("Support for Asides / Side Notes",'ml') );
	_e("Asides are the 'quick bits' of information you want to post. They do not have to look like a regular post.",'ml');
	echo "<br/><br/>"; _e('Learn More at','ml'); echo " <a href='http://photomatt.net/2004/05/19/asides/'>Matt's Asides technique</a>";
?>
	<?php
		global $wpdb;
		$id = get_option('mistylook_asideid');
		$defaults = array(
			'show_option_all' => '', 'show_option_none' => '',
			'orderby' => 'ID', 'order' => 'ASC',
			'show_last_update' => 0, 'show_count' => 0,
			'hide_empty' => 1, 'child_of' => 0,
			'exclude' => '', 'echo' => 1,
			'selected' => 0, 'hierarchical' => 0,
			'name' => 'cat', 'class' => 'postform'
		);
		$r = wp_parse_args( $args, $defaults );
		extract( $r );

		$asides_cats = get_categories($r);
	?>
			<p><?php _e('Select the category here. Any posts under this category will look like an Aside.','ml');?></p>
			<select name="s_asideid" id="s_asideid">
				<option value="0"><?php _e('NOT SELECTED','ml');?></option>
				<?php
					foreach ($asides_cats as $cat) {
					if ($id == $cat->cat_ID)
					{
						$sIsSelected = "selected='true'";
					}
					else
					{
						$sIsSelected = "";
					}
						echo '<option value="' . $cat->cat_ID . '"'. $sIsSelected. '>' . $cat->cat_name . '</option>';
				}?>
			</select>
</td>

</td>
</tr>
<tr>
<td valign="top" colspan="2" style="border:0px;margin:0;padding:0;">
	<input type="hidden" name="action" value="save" />
	<?php ml_input( "save", "submit", "", __("Save Settings",'ml') );?>
</td>
</tr>
</table>
</fieldset>
</form>

<form method="post">

<fieldset class="options">
<legend><?php _e('Reset','ml');?></legend>

<p><?php _e('If for some reason you want to uninstall MistyLook then press the reset button to clean things up in the database.','ml');?></p>
<p><?php _e('You have to make sure to delete the theme folder, if you want to completely remove the theme.','ml');?></p>
<?php

	ml_input( "reset", "submit", "", __("Reset Settings",'ml') );

?>

</div>
<input type="hidden" name="action" value="reset" />
</form>

<?php
}
add_action('admin_menu', 'mistylook_add_theme_page');


function ml_input( $var, $type, $description = "", $value = "", $selected="" ) {

	// ------------------------
	// add a form input control
	// ------------------------

 	echo "\n";

	switch( $type ){

	    case "text":

	 		echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 60%\" class=\"textbox\" value=\"$value\" />";

			break;

		case "submit":

	 		echo "<p class=\"submit\"><input name=\"$var\" type=\"$type\" value=\"$value\" /></p>";

			break;

		case "option":

			if( $selected == $value ) { $extra = "selected=\"true\""; }

			echo "<option value=\"$value\" $extra >$description</option>";

		    break;
  		case "radio":

			if( $selected == $value ) { $extra = "checked=\"true\""; }

  			echo "<label><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";

  			break;

		case "checkbox":

			if( $selected == $value ) { $extra = "checked=\"true\""; }

  			echo "<label for=\"$var\"><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";

  			break;

		case "textarea":

		    echo "<textarea name=\"$var\" id=\"$var\" style=\"width: 80%; height: 10em;\" class=\"code\">$value</textarea>";

		    break;
	}

}

function ml_heading( $title ) {

	// ------------------
	// add a table header
	// ------------------

   echo "<h4>" .$title . "</h4>";

}
?>
<?php

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/img/misty.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 760);
define('HEADER_IMAGE_HEIGHT', 200);
define( 'NO_HEADER_TEXT', true );

function mistylook_admin_header_style() {
?>
<style type="text/css">
#headimg {
	background: url(<?php header_image() ?>) no-repeat;
}
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}
</style>
<?php
}
function mistylook_header_style() {
?>
<style type="text/css">
#headerimage {
	background: url(<?php header_image() ?>) no-repeat;
}
</style>
<?php
}
if ( function_exists('add_custom_image_header') ) {
	add_custom_image_header('mistylook_header_style', 'mistylook_admin_header_style');
}
load_theme_textdomain('ml');
?>
