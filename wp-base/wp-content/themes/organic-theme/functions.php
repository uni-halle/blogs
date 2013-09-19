<?php
/**
 * @package WordPress
 * @subpackage Organic Theme
 */
automatic_feed_links();

if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
echo "<div class='updated fade'><p>You can set up your theme by <a href=".get_bloginfo('url')."/wp-admin/themes.php?page=functions.php>clicking here</a></p></div>";
}

/* ENQUEUE JAVASCRIPT */
// Using one enqueue and in admin only in order to pinpoint a possible Javascript bug.

function organic_enqueue_js () {

	$dir = get_stylesheet_directory_uri() . '/icon.php';
	$sprinkle_path = get_bloginfo('template_directory') . '/js/sprinkle.js';
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('organic-sprinkle', $sprinkle_path, array('jquery', 'jquery-ui-tabs'), '1.1', false);
	wp_enqueue_script('organic-iconchanger', $dir, array('jquery'), '1.1', false);
	
} // End organic_enqueue_js()

add_action('init', organic_enqueue_js(),1);

if ( function_exists('register_sidebar') )
register_sidebar(array(
'before_widget' => '<li id="%1$s" class="widget %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
	
/* VARIABLES */	

include("includes/reset.php");	
$themename = "Organic";
$shortname = "organic";
$diff_stylesheets = array("Blue","Green","Brown");
$diff_logos = array("Bullseye","Butterfly","Crown","Diamonds","Default","Female","Fern","Flower","Flower2","Foot","Grass","Hand","Heart","Leaf","Leaves1","Male","Moon","Music","Paws","Plant","Puzzle","Snowflake","Star","Star2","Star3","Sun","Tree","Water","World","Yinyang");
$organic_pages_ob = get_pages('sort_column=menu_order');
$organic_pages = array();
foreach ($organic_pages_ob as $organic_page) { $organic_pages[$organic_page->ID] = str_replace("-"," ",$organic_page->post_title); }

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
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], stripslashes($_REQUEST[ $value['id'] ] ) ); } else { delete_option( $value['id'] ); } }

                ?><meta http-equiv="refresh" content="0;url=themes.php?page=functions.php&saved=true"><?php
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

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
<table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">

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

<table width="100%" border="0" style="background-color:#dceefc; padding:5px 10px;"><tr>
    <td colspan="2"><h3 style="font-family:Georgia,'Times New Roman',Times,serif;"><?php echo $value['name']; ?></h3></td>
</tr>


<?php break;

case 'text':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'info':
?>

<tr>
    <td width="100%" colspan="2"><?php echo $value['name']; ?></strong></td>
</tr>

<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'info2':
?>

<tr>
    <td width="100%" colspan="2"><?php echo $value['name']; ?></strong></td>
</tr>

<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case 'texter':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td></tr>

<?php break;

case 'texterend':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><input size="37" maxlength="30" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px; border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'textarea':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:100px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea></td>

</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'select':
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top:20px; ">
<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="25%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td><td><img src="" alt=" " id="thumb"></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

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
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr>

<?php
break;

case "checkbox":
?>
    <tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
        <td width="80%"><?php if(get_settings($value['id']) == "true"){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                </td>
    </tr>
	
	<tr>
        <td><small><?php echo $value['desc']; ?></small></td>
   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

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
