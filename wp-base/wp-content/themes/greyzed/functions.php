<?php
/**
 * @package WordPress
 * @subpackage Greyzed Theme
 */
automatic_feed_links();


if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
echo "<div class='updated fade'><p>You can set up your theme by <a href=".get_bloginfo('url')."/wp-admin/themes.php?page=functions.php>clicking here</a></p></div>";
}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar 1',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar 2',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Footer Middle',
		'before_widget' => '',
		'after_widget' => '</li>',
		'before_title' => '<h4 class="footerwidget">',
		'after_title' => '</h4>',
	));

}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Footer Right',
		'before_widget' => '',
		'after_widget' => '</li>',
		'before_title' => '<h4 class="footerwidget">',
		'after_title' => '</h4>',
	));

}

/* VARIABLES */	

include("includes/reset.php");	
$themename = "Greyzed";
$shortname = "greyzed";
include("includes/options.php");

/* END VARIABLES */

/* OPTIONS PAGE */

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {

        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], stripslashes($_REQUEST[ $value['id'] ])  ); } else { delete_option( $value['id'] ); } }

                //wp_redirect("themes.php?page=functions.php&saved=true");
                ?><meta http-equiv="refresh" content="0;url=themes.php?page=functions.php&saved=true"><?php
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            //wp_redirect("themes.php?page=functions.php&reset=true");
            ?><meta http-equiv="refresh" content="0;url=themes.php?page=functions.php&reset=true"><?php
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
<div class="wrap">

<h2><?php echo $themename; ?> settings</h2>

<form method="post">

<?php foreach ($options as $value) {

switch ( $value['type'] ) {

case "open":
?>
<table width="800px" border="0" style="background-color:#eef5fb; padding:10px; border: 1px solid #ddd; padding:8px;-moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">

<?php break;

case "close":
?>

</table><br />

<?php break;

case 'logos':
?>

<?php
break;

case "title":
?>

<table width="800px" border="0" style="padding:5px 10px;"><tr>
    <td colspan="2"><h3 style="font-family:Georgia,'Times New Roman',Times,serif;"><?php echo $value['name']; ?></h3></td>
</tr>


<?php break;

case 'text':
?>

<tr>
    <td width="40%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'info':
?>

<tr>
    <td width="800px" colspan="2"><?php echo $value['name']; ?></strong></td>
</tr>

<tr><td colspan="2" style="">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'info2':
?>

<tr>
    <td width="800px" colspan="2"><?php echo $value['name']; ?></strong></td>
</tr>

<tr><td colspan="2" >&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'texter':
?>

<tr>
    <td width="40%" rowspan="2"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><input style="width:400px; border: 1px solid #ddd; padding:8px;-moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px;
	border-radius: 3px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td></tr>

<?php break;

case 'texter2':
?>

<tr>
    <td width="40%" rowspan="2"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><input style="width:392px; margin-top:20px; border: 1px solid #ddd; padding:8px;-moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px;
	border-radius: 3px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td></tr>

<?php break;

case 'texterend':
?>

<tr>
    <td width="40%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><input size="37" maxlength="30" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'textarea':
?>

<tr>
    <td width="40%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><textarea name="<?php echo $value['id']; ?>" style="width:410px; height:100px; margin-top:30px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea></td>

</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'select':
?>
<table border="0" width="800px" cellspacing="0" cellpadding="0" style="padding-top:20px; ">
<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="25%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td><td><img src="" alt=" " id="thumb"></td>
</tr>
</table>
<table border="0" width="800px" cellspacing="0" cellpadding="0">
<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'radio':

foreach ($value['options'] as $key=>$option) {
$radio_setting = get_settings($value['id']);
if ($radio_setting != '') {
if ($key == get_settings($value['id']) ) {
$checked = "checked=\"checked\"";
} else {
$checked = "";
}
} else {
if($key == $value['std']){
$checked = "checked=\"checked\"";
} else {
$checked = "";
}
}?>
<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
<?php
}
break;

case 'selecter':
?>
<tr>
    <td width="40%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="60%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr>

<?php
break;

case "checkbox":
?>
    <tr>
    <td width="40%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
        <td width="60%"><?php if(get_settings($value['id']) == "true"){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                </td>
    </tr>
	
	<tr>
        <td><small><?php echo $value['desc']; ?></small></td>
   </tr><tr><td colspan="2" style="">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php         break;

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
/* END OPTIONS PAGE */

/* ADD MENU */
add_action('admin_menu', 'mytheme_add_admin'); 
/* END ADD MENU */
?>
