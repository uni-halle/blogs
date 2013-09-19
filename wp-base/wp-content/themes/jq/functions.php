<?php function mytheme_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class('clearfix') ?> id="comment-<?php comment_ID() ?>">

         <?php echo get_avatar($comment, 48); ?>

      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
      <?php endif; ?>

      <p class="comment-details"><?php if (1==$comment->user_id) echo 'Admin comment by '; else if ($comment->user_id) echo 'Author comment by '; ?><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><em><?php comment_author_link() ?></em></a> &middot; <?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?><?php if ( is_user_logged_in() ) : ?><?php edit_comment_link('Edit', ' &middot; <small>', '</small>'); ?><?php endif; ?></p>

            		<div class="comment-text">
                    		<?php comment_text() ?>
            		</div>

		<?php comment_reply_link(array_merge($args, array(
					'depth' => $depth,
					'before' => '<p class="comment-reply">', 
					'after' => '</p>'
				)));
		?>
<?php } ?>
<?php
// Widget Settings
if ( function_exists('register_sidebar') )
        {
        register_sidebar(array(
        'name' => 'sidebar_right',
        'before_widget' => '<div id="%1$s" class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4><a href="#" title="Toggle" class="hide_widget">',
        'after_title' => '</a></h4>',
        ));

        register_sidebar(array(
        'name' => 'sidebar_bottom_left',
        'before_widget' => '<div id="%1$s" class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4><a href="#" title="Toggle" class="hide_widget">',
        'after_title' => '</a></h4>',
        ));

        register_sidebar(array(
        'name' => 'sidebar_bottom_middle',
        'before_widget' => '<div id="%1$s" class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4><a href="#" title="Toggle" class="hide_widget">',
        'after_title' => '</a></h4>',
        ));

        register_sidebar(array(
        'name' => 'sidebar_bottom_right',
        'before_widget' => '<div id="%1$s" class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4><a href="#" title="Toggle" class="hide_widget">',
        'after_title' => '</a></h4>',
        ));
}

?>
<?php
$themename = "jQ";
$shortname = "jq";

$options = array (
			
	array(	"type" => "open"),

        array(	"name" => "Style Schemes",
			"desc" => "Choose a style scheme.",
			"id" => $shortname."_style_scheme",
			"std" => "Serif",
			"type" => "select",
			"options" => array("Serif", "Sans-Serif", "Dark")),

	array(	"name" => "Font Size",
			"desc" => "Here you can adjust the general font size of your blog. <em>Careful!</em> This will change the CSS value of the top level element of your page (the body element). All other values will cascade down from this <em>reference value</em>. Default is 10, so try 9 or 11, depending on what you want to do. This will also disable the font magnifying. <p><em>Note: You should clear cache and cookies.</em></p>",
			"id" => $shortname."_font_size",
			"std" => "",
			"type" => "text"),
	
	array(	"name" => "Background Colour",
			"desc" => "Enter a value for the background colour (e.g. <em>#ff0000</em>).",
			"id" => $shortname."_bg_color",
			"std" => "",
			"type" => "text"),

	array(	"name" => "Page Colour",
			"desc" => "Enter a value for the page colour (e.g. <em>#ff0000</em>).",
			"id" => $shortname."_page_color",
			"std" => "",
			"type" => "text"),

	array(  "name" => "Sidebar",
			"desc" => "Check this box if you would like to display the sidebar on the left of the page.",
            "id" => $shortname."_sidebar_left",
            "type" => "checkbox",
            "std" => "false"),

	array(  "name" => "Navigation",
			"desc" => "Check this box if you would like to display categories instead of pages in the navigation.",
            "id" => $shortname."_nav_display",
            "type" => "checkbox",
            "std" => "false"),

	array(  "name" => "Index Pages",
			"desc" => "Check this box if you would like to show the excerpt instead of the entire post on index pages.",
            "id" => $shortname."_post_display",
            "type" => "checkbox",
            "std" => "false"),

	array(  "name" => "Content Sliding",
			"desc" => "Check this box if you would like to hide the content sliding box.",
            "id" => $shortname."_slide_display",
            "type" => "checkbox",
            "std" => "false"),

	array(  "name" => "Admin E-mail",
			"desc" => "Check this box if you would like to hide the admin email address.",
            "id" => $shortname."_mail_display",
            "type" => "checkbox",
            "std" => "false"),
	
	array(	"type" => "close")
	
);

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

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p>'.$themename.' settings saved.</p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p>'.$themename.' settings reset.</p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> settings</h2>

<form method="post">



<?php foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		case "open":
		?>
        <table width="100%" border="0" style="color:#333;">		    
        
		<?php break;
		
		case "close":
		?>
		
        </table><br />
        
        
		<?php break;
		
		case "title":
		?>
		<table width="100%" border="0"><tr>
        	<td colspan="2"><?php echo $value['name']; ?></td>
        </tr>

		<?php break;
		
		case 'select':
		?>
        <tr>
            <td width="25%" rowspan="2" valign="middle"><h3 style="font:normal 14px Verdana,sans-serif;color:#21759b;"><?php echo $value['name']; ?></h3></td>
	            <td width="75%"><select style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                <?php foreach ($value['options'] as $option) { ?>
	                <option <?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select></td></tr>
                        <tr>
            <td><p style="width: 200px;"><?php echo $value['desc']; ?></p></td>
        </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
        
		<?php break;

		case 'text':
		?>
        
        <tr>
            <td width="25%" rowspan="2" valign="middle"><h3 style="font:normal 14px Verdana,sans-serif;color:#21759b;"><?php echo $value['name']; ?></h3></td>
            <td width="75%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
        </tr>

        <tr>
            <td><p style="width: 405px;"><?php echo $value['desc']; ?></p></td>
        </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
        break;
            
		case "checkbox":
		?>
            <tr>
            <td width="25%" rowspan="2" valign="middle"><h3 style="font:normal 14px Verdana,sans-serif;color:#21759b;"><?php echo $value['name']; ?></h3></td>
                <td width="75%"><?php if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </td>
            </tr>
                        
            <tr>
                <td><p style="width: 200px;"><?php echo $value['desc']; ?></p></td>
           </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
            
        <?php 		break;	
 
} 
}
?>
<!--</table>-->
<p class="submit" style="margin:0;padding:0;">
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
add_action('admin_menu', 'mytheme_add_admin'); ?>
