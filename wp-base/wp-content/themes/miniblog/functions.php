<?php
/*For Sidebar Widget*/
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => 'sidebar',
'before_widget' => '<div class="sidebar-box">',
'after_widget' => '</div>',
'before_title' => '<h2>',
'after_title' => '</h2>',
));
/*End of For Sidebar Widget*/

/*For Popular Posts*/
function most_popular_posts($no_posts = 5, $before = '<li>', $after = '</li>', $show_pass_post = false, $duration='') {
	global $wpdb;
	$request = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'comment_count' FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish'";
	if(!$show_pass_post) $request .= " AND post_password =''";
	if($duration !="") { $request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts = $wpdb->get_results($request);
	$output = '';
	if ($posts) {
		foreach ($posts as $post) {
		$post_title = stripslashes($post->post_title);
		$comment_count = $post->comment_count;
		$permalink = get_permalink($post->ID);
		$output .= $before . '<a href="' . $permalink . '" title="' . $post_title.'">' . $post_title . '</a>' . $after;
	}
	} else {
		$output .= $before . "None found" . $after;
	}
	echo $output;
}
/*End of Popular Posts*/

/*Fix Excerpts*/
function wp_improved_trim_excerpt($text) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = 80;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words,'...');
			$text = implode(' ',$words);
		}
	}
	return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_improved_trim_excerpt');
/*End of Fix Excerpts*/

/*Custom Field Images*/
function image_attachment($key, $width, $height) {
	global $post;
	$custom_field = get_post_meta($post->ID, $key, true);

	if($custom_field) {
		echo '<img src="'.$custom_field.'" alt="" width="'.$width.'" height="'.$height.'" />';
	}
	else {
		return;
	}
}
/*End of Custom Field Images*/

/*Start of Theme Options ======================================*/

$themename = "miniBlog";
$shortname = "mb";
$options = array (

array(
"name" => "Welcome to the Theme Options page for your 'miniBlog' theme.<br/>Here you can configure things such as the color of certain elements to what appears in the sidebar.<br/>Hope you enjoy your new theme.",
"type" => "misc"),

array(
"name" => "Select Your Colors",
"type" => "title"),

array(
"type" => "open"),

array(
"name" => "Page Background Color",
"desc" => "Choose a background color for the site.  This will also be used in other areas of the design.<br/>Input a 6-digit HEX value, excluding the '#' sign.",
"id" => $shortname."_color_page_background",
"std" => "eeeadd",
"type" => "text",
"image" => "to-color-page-background.gif"),

array(
"name" => "Secondary Color",
"desc" => "This color will be used for the title of the blog as well as in other miscellaneous areas.<br/>Input a 6-digit HEX value, excluding the '#' sign.",
"id" => $shortname."_color_secondary",
"std" => "77afcf",
"type" => "text",
"image" => "to-color-secondary.gif"),

array(
"name" => "Tab Color",
"desc" => "Used for the background of the 'selected' tab as well as other miscellaneous areas.<br/>Input a 6-digit HEX value, excluding the '#' sign.",
"id" => $shortname."_color_tab",
"std" => "d8e7f0",
"type" => "text",
"image" => "to-color-tab.gif"),

array(
"type" => "close"),

array(
"type" => "submit"),

array(
"name" => "Setup Your Title and Slogan",
"type" => "title"),

array(
"type" => "open"),

array(
"name" => "First Part of Title",
"desc" => "This will be the first part of the title of your site.",
"id" => $shortname."_title_first",
"type" => "text",
"image" => "to-title-settings.gif",
"std" => "mini"),

array(
"name" => "Second Part of Title",
"desc" => "This will be the second part of the title of your site.",
"id" => $shortname."_title_second",
"type" => "text",
"image" => "",
"std" => "Blog"),
		
array(
"name" => "Your Slogan",
"desc" => "This will be the slogan of your site.",
"id" => $shortname."_slogan",
"type" => "text",
"image" => "",
"std" => "A very simple Wordpress theme"),
		
array(
"name" => "Fix Speech Bubble Width",
"desc" => "This is only for if you are having problems fitting in the title and slogan of your site into the speech bubble.<br/>Simply increase or decrease the value (in pixels) in the text field until all is well.",
"id" => $shortname."_bubble_size",
"type" => "text",
"image" => "to-header-fix.gif",
"std" => "230"),
		
array(
"type" => "close"),

array(
"type" => "submit"),

array(
"name" => "Add and Remove Elements From the Sidebar",
"type" => "title"),

array(
"type" => "open"),

array(
"name" => "Display the Tab Area?",
"desc" => "Check this box to enable the display of the tab area in the sidebar.",
"id" => $shortname."_sidebar_tabs",
"type" => "checkbox",
"image" => "to-sidebar-tabs.gif",
"std" => "false"),

array(
"name" => "Display the Twitter Widget?",
"desc" => "Check this box to enable the display of the Twitter widget in the sidebar.<br/>Note, if this is enabled, you will have to configure this widget lower down on this page.",
"id" => $shortname."_sidebar_twitter",
"type" => "checkbox",
"image" => "to-sidebar-twitter.gif",
"std" => "false"),

array(
"name" => "Display the Popular Posts Widget?",
"desc" => "Check this box to enable the display of the Popular Posts widget in the sidebar.",
"id" => $shortname."_sidebar_popular_posts",
"type" => "checkbox",
"image" => "to-sidebar-popular-posts.gif",
"std" => "false"),

array(
"name" => "Display the Recent Posts Widget?",
"desc" => "Check this box to enable the display of the Recent Posts widget in the sidebar.",
"id" => $shortname."_sidebar_recent_posts",
"type" => "checkbox",
"image" => "to-sidebar-recent-posts.gif",
"std" => "false"),

array(
"name" => "Display the Recent Comments Widget?",
"desc" => "Check this box to enable the display of the Recent Comments widget in the sidebar.",
"id" => $shortname."_sidebar_recent_comments",
"type" => "checkbox",
"image" => "to-sidebar-recent-comments.gif",
"std" => "false"),

array(
"name" => "Display the Categories Widget?",
"desc" => "Check this box to enable the display of the Categories widget in the sidebar.",
"id" => $shortname."_sidebar_categories",
"type" => "checkbox",
"image" => "to-sidebar-categories.gif",
"std" => "false"),

array(
"name" => "Display the Search Box?",
"desc" => "Check this box to enable the display of the Search Box in the sidebar.",
"id" => $shortname."_sidebar_search",
"type" => "checkbox",
"image" => "to-sidebar-search.gif",
"std" => "false"),

array(
"type" => "close"),

array(
"type" => "submit"),

array(
"name" => "Twitter Configuration",
"type" => "title"),

array(
"type" => "open"),

array(
"name" => "What is Your Twitter Username?",
"desc" => "I need to know this so I can fetch the Twitter feed from the correct person.",
"id" => $shortname."_twitter_name",
"type" => "text",
"image" => "",
"std" => "FTLRalph"),

array(
"name" => "How Many Tweets Do You Want Displayed?",
"desc" => "This is how many tweets are shown at one time.  The recommended is 3.",
"id" => $shortname."_twitter_count",
"type" => "text",
"image" => "",
"std" => "3"),

array(
"type" => "close"),

array(
"type" => "submit"),

array(
"name" => "Miscellaneous Settings",
"type" => "title"),

array(
"type" => "open"),

array(
"name" => "Post 'Custom Field' Image Key",
"desc" => "This is the key you will use when specifying images to go along with your posts in the 'Custom Fields.'<br/>The default work is 'image', but if you were using a previous theme that used a different key, enter that word here and miniBlog will attempt to retrieve your old images.<br/>Note, if the images are not square, they may be distorted by miniBlog, as this theme uses images that are 98px by 98px.",
"id" => $shortname."_image_key",
"type" => "text",
"image" => "to-image-key.gif",
"std" => "image"),

array(
"name" => "Display the RSS Feed Link in the Navigation?",
"desc" => "Do you want to display the 'RSS Feed' link in the navigation at the top of the site?",
"id" => $shortname."_show_rss",
"type" => "checkbox",
"image" => "",
"std" => "false"),

array(
"type" => "close")
);

/*Add a Theme Options Page*/
function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {

        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';

?>
<div class="wrap" style="margin:0 auto; padding:20px 0px 0px;">

<form method="post">

<?php foreach ($options as $value) {
switch ( $value['type'] ) {

case "open":
?>
<div style="width:808px; background:#eee; border:1px solid #ddd; padding:20px; overflow:hidden; display: block; margin: 0px 0px 30px;">

<?php break;

case "close":
?>

</div>

<?php break;

case "misc":
?>
<div style="width:808px; background:#fffde2; border:1px solid #ddd; padding:20px; overflow:hidden; display: block; margin: 0px 0px 30px;">
	<?php echo $value['name']; ?>
</div>
<?php break;

case "title":
?>

<div style="width:810px; height:22px; background:#555; padding:9px 20px; overflow:hidden; margin:0px; font-family:Verdana, sans-serif; font-size:18px; font-weight:normal; color:#EEE;">
	<?php echo $value['name']; ?>
</div>

<?php break;

case 'text':
?>

<div style="width:808px; padding:0px 0px 10px; margin:0px 0px 10px; border-bottom:1px solid #ddd; overflow:hidden;">
	<span style="font-family:Arial, sans-serif; font-size:16px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['name']; ?>
	</span>
	<?php if ($value['image'] != "") {?>
		<div style="width:808px; padding:10px 0px; overflow:hidden;">
			<img style="padding:5px; background:#FFF; border:1px solid #ddd;" src="<?php bloginfo('template_url');?>/images/<?php echo $value['image'];?>" alt="image" />
		</div>
	<?php } ?>
	<input style="width:200px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" />
	<br/>
	<span style="font-family:Arial, sans-serif; font-size:11px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['desc']; ?>
	</span>
</div>

<?php
break;

case 'textarea':
?>

<div style="width:808px; padding:0px 0px 10px; margin:0px 0px 10px; border-bottom:1px solid #ddd; overflow:hidden;">
	<span style="font-family:Arial, sans-serif; font-size:16px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['name']; ?>
	</span>
	<?php if ($value['image'] != "") {?>
		<div style="width:808px; padding:10px 0px; overflow:hidden;">
			<img style="padding:5px; background:#FFF; border:1px solid #ddd;" src="<?php bloginfo('template_url');?>/images/<?php echo $value['image'];?>" alt="image" />
		</div>
	<?php } ?>
	<textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'] )); } else { echo stripslashes($value['std']); } ?></textarea>
	<br/>
	<span style="font-family:Arial, sans-serif; font-size:11px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['desc']; ?>
	</span>
</div>

<?php
break;
/*Ralph Damiano*/
case 'select':
?>

<div style="width:808px; padding:0px 0px 10px; margin:0px 0px 10px; border-bottom:1px solid #ddd; overflow:hidden;">
	<span style="font-family:Arial, sans-serif; font-size:16px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['name']; ?>
	</span>
	<?php if ($value['image'] != "") {?>
		<div style="width:808px; padding:10px 0px; overflow:hidden;">
			<img style="padding:5px; background:#FFF; border:1px solid #ddd;" src="<?php bloginfo('template_url');?>/images/<?php echo $value['image'];?>" alt="image" />
		</div>
	<?php } ?>
	<select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select>
	<br/>
	<span style="font-family:Arial, sans-serif; font-size:11px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['desc']; ?>
	</span>
</div>

<?php
break;

case "checkbox":
?>

<div style="width:808px; padding:0px 0px 10px; margin:0px 0px 10px; border-bottom:1px solid #ddd; overflow:hidden;">
	<span style="font-family:Arial, sans-serif; font-size:16px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['name']; ?>
	</span>
	<?php if ($value['image'] != "") {?>
		<div style="width:808px; padding:10px 0px; overflow:hidden;">
			<img style="padding:5px; background:#FFF; border:1px solid #ddd;" src="<?php bloginfo('template_url');?>/images/<?php echo $value['image'];?>" alt="image" />
		</div>
	<?php } ?>
	<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
	<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
	<br/>
	<span style="font-family:Arial, sans-serif; font-size:11px; font-weight:bold; color:#444; display:block; padding:5px 0px;">
		<?php echo $value['desc']; ?>
	</span>
</div>


<?php
break;

case "submit":
?>

<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>

<?php break;
}
}
?>

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

add_action('admin_menu', 'mytheme_add_admin');
/*End of Add a Theme Options Page*/

/*End of Theme Options =======================================*/
?>