<?php
include_once(ABSPATH . WPINC . '/rss.php');

if ( function_exists('register_sidebar') )
	register_sidebars(2);

/*Start of Theme Options*/
 
$themename = "Feusional";
$shortname = "feusional";
$options = array (
 
array( "name" => "Welcome message",
	"type" => "title"),
 
array( "type" => "open"),
 
array( "name" => "Welcome heading",
	"desc" => "The heading of the Welcome message",
	"id" => $shortname."_welcome_title",
	"type" => "text",
	"std" => "Hello and welcome to my website!"),
 
array( "name" => "Welcome message",
	"desc" => "A short description about the website, or extended version of your welcome message",
	"id" => $shortname."_welcome_desc",
	"type" => "textarea",
	"std" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip."),	
 
 
array( "name" => "Twitter account",
	"type" => "title"),
 
array( "type" => "open"),

array( "name" => "Disable Twitter status",
	"desc" => "Check if you don't want to use this feature",
	"id" => $shortname."_notwitter",
	"type" => "checkbox",
	"std" => "false"),	
 
array( "name" => "Twitter username",
	"desc" => "your Twitter account username",
	"id" => $shortname."_twitter",
	"type" => "text",
	"std" => "username"),
 
array( "name" => "Introduction box (sidebar)",
	"type" => "title"),
 
array( "type" => "open"),
 
array( "name" => "Photo URL",
	"desc" => "The URL of the photo (max width 170px)",
	"id" => $shortname."_intro_pic",
	"type" => "text",
	"std" => ""),
 
array( "name" => "Text",
	"desc" => "A short introduction.",
	"id" => $shortname."_intro_desc",
	"type" => "textarea",
	"std" => "Short introduction..."),	
 
array( "type" => "close")
 
);


function feusional_add_admin() {
 
	global $themename, $shortname, $options;
 
	if ( $_GET['page'] == basename(__FILE__) ) {
 
		if ( 'save' == $_REQUEST['action'] ) {
 
			foreach ($options as $value) {
			update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
 
			foreach ($options as $value) {
			if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else 					{ delete_option( $value['id'] ); } }
 
			header("Location: themes.php?page=functions.php&saved=true");
			die;
 
		} else if( 'reset' == $_REQUEST['action'] ) {
 
			foreach ($options as $value) {
			delete_option( $value['id'] ); }
			 
			header("Location: themes.php?page=functions.php&reset=true");
			die;
			 
		}
	}
add_menu_page($themename." Options", "".$themename." Options",'edit_themes', basename(__FILE__), 'feusional_admin'); 

}


function feusional_admin() {
 
	global $themename, $shortname, $options;
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>'; 
	?>	
	<div class="wrap">		
		<h2><?php echo $themename; ?> Theme Settings</h2>
		<p><?php echo $themename; ?> is a free theme by <a href="http://www.flisterz.com">flisterz</a>.</p> 
		<form method="post">
 		<?php foreach ($options as $value) {		
			switch ( $value['type'] ) { 
				case "open": ?>
					<table width="90%" border="0" style="background-color:#eeeeee; padding:10px;margin: 0 0 20px 0;">
					<?php break;
				case "close": ?>
					</table> 
					<?php break;				
				case "title": ?>
					<table width="90%" border="0" style="background-color:#cccccc; padding:5px 5px;"><tr>
					<td colspan="2"><h3 style="font-family:Georgia,'Times New Roman',Times,serif; margin:0;">
					<?php echo $value['name']; ?></h3></td>
					</tr>
					<?php break;				
				case 'text': ?>
					<tr>
						<td style="padding:0 15px 15px 0;" width="25%" rowspan="2" valign="top"><strong><?php echo $value['name']; ?></strong>
						<br /><small><?php echo $value['desc']; ?></small></td>
					
						<td style="padding:0 0px 15px 0;" width="70%"><input style="width:500px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
					</tr> 		
					<tr> </tr>
					<?php
					break;
				case 'textarea': ?>
					<tr>
					<td style="padding:0 15px 15px 0;" width="25%" rowspan="2" valign="top"><strong><?php echo $value['name']; ?></strong><br />
					<small><?php echo $value['desc']; ?></small></td>
					<td style="padding:0 0px 15px 0;" width="70%"><textarea name="<?php echo $value['id']; ?>" style="width:500px; height:100px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea></td>
					</tr>				 
					<tr> </tr>
					<?php
					break;					 
				case 'select':	?>
					<tr>
					<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
					<td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
					</tr>					 
					<tr>
					<td><small><?php echo $value['desc']; ?></small></td>
					</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>					 
					<?php
					break;
				case "checkbox": ?>
					<tr>
					<td style="padding:0 15px 15px 0;" width="25%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong><br />
					<small><?php echo $value['desc']; ?></small></td>
					<td style="padding:0 0px 15px 0;" width="70%"><?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
					<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> /> Disable
					</td>
					</tr>					 
					<tr> </tr>
					<?php break;				 
				}
				}
?> 
	<p class="submit">
		<input name="save" type="submit" value="Save changes" />
		<input type="hidden" name="action" value="save" />
	</p> 
</form>

<?php
}

add_action('admin_menu', 'feusional_add_admin');

function feusional_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      
      <div class="comment-author vcard">
         <p class="fleft"><?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?></p>

        <?php comment_author_link(); ?>
        <p class="c_info"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?> 
        </p> 
        <div class="clear"></div>
	
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <?php comment_text() ?>

      <div class="reply fright">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <div class="clear"></div>
	
     </div>
<?php
}
 

function fpings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
<li id="comment-<?php comment_ID(); ?>" class="pings"><?php comment_author_link(); ?>
<?php
}
?>