<?php

/**
 * @package WordPress
 * @subpackage Default_Theme
 */

if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
///////////////////////////////////////////////////////////////////////////////////////////////////

//inserts new image url css in header
function color_style() {
  ?>
  <style type="text/css">
  <?php if (get_theme_mod('aero_background_image') == "Default") { ?>
    body { background: url(<?php echo bloginfo('template_directory'); ?>/images/wide.jpg)repeat-x center top; background-color:#000;}
  <?php } elseif (get_theme_mod('aero_background_image') == "Colorlines") { ?>
    body { background: url(<?php echo bloginfo('template_directory'); ?>/images/colorlines.jpg) repeat-x scroll center top; background-color:#000;}
  <?php } elseif (get_theme_mod('aero_background_image') == "Pulsetwo") { ?>
    body { background: url(<?php echo bloginfo('template_directory'); ?>/images/pulse2.jpg) repeat-x scroll center top; background-color:#000; }
  <?php } elseif (get_theme_mod('aero_background_image') == "Wood") { ?>
    body { background: url(<?php echo bloginfo('template_directory'); ?>/images/wood.jpg) repeat-x scroll center top; background-color:#000;}
  <?php } elseif (get_theme_mod('aero_background_image') == "Pulse") { ?>
    body { background: url(<?php echo bloginfo('template_directory'); ?>/images/pulse.jpg) repeat-x scroll center top; background-color:#000;}
 	<?php } ?>
  
  </style>
<?php
}
if (get_theme_mod('aero_background_image')) //add css hook if option is set
  add_action('wp_head', 'color_style');



//admin menu process options
function color_add_admin() {		
	if ( 'save' == $_POST['color_action'] ) {

		// Update Options
		$color_background_image = preg_replace( '|[^a-z]|i', '', $_POST['aero_background_image'] );
		set_theme_mod('aero_background_image', $color_background_image );

		// Go back to the options
		header("Location: themes.php?page=aero-options&saved=true");
		die;
	}
	add_submenu_page('themes.php', __('Change Background Image'), __('Change Background Image'), 5, 'aero-options', 'aero_admin');
}

//actual menu to select background image
function aero_admin() {

	if ( $_GET['saved'] ) echo '<div id="message" class="updated fade"><p>Image option saved. <a href="'. get_bloginfo('url') .'">View Site &raquo;</a></strong></p></div>';
	
?>

<div class="wrap">
<h2>Select a Colorfull background image:</h2>
	<form id='aero_options' method="post">
		<p>
	<label><input type="radio" name="aero_background_image" value="Default" <?php if (get_theme_mod('aero_background_image') == "Default" || !get_theme_mod('aero_background_image')) { echo "checked='checked'"; } ?> /> Default<img src="<?php bloginfo('template_directory'); ?>/images/wide_small.jpg" alt="Default" /></label>
    <label><input type="radio" name="aero_background_image" value="Colorlines" <?php if (get_theme_mod('aero_background_image') == "Colorlines") { echo "checked='checked'"; } ?> /> Color lines<img src="<?php bloginfo('template_directory'); ?>/images/colorlines_small.jpg" alt="Colorlines" /></label>
    <label><input type="radio" name="aero_background_image" value="Pulsetwo" <?php if (get_theme_mod('aero_background_image') == "Pulsetwo") { echo "checked='checked'"; } ?> /> Pulsetwo<img src="<?php bloginfo('template_directory'); ?>/images/pulse2_small.jpg" alt="Pulsetwo" /></label>
    <label><input type="radio" name="aero_background_image" value="Wood" <?php if (get_theme_mod('aero_background_image') == "Wood") { echo "checked='checked'"; } ?> /> Wood<img src="<?php bloginfo('template_directory'); ?>/images/wood_small.jpg" alt="Wood" /></label>
	<label><input type="radio" name="aero_background_image" value="Pulse" <?php if (get_theme_mod('aero_background_image') == "Pulse") { echo "checked='checked'"; } ?> /> Pulse<img src="<?php bloginfo('template_directory'); ?>/images/pulse_small.jpg" alt="Pulse" /></label>
	  </p>
		<input type="hidden" name="color_action" value="save" />
	<p class="submit" style="clear: both"><input name="save" id="save" type="submit" value="Save Options &raquo;" /></p>
	</form>

</div>
<?php
}

//css for option list
function color_admin_header() { ?>
<style media="screen" type="text/css">
  form#aero_options label { 
    width:150px;
    margin-left:10px;
    margin-bottom:20px;
  	display: block;
  	float: left;
  }
</style>
<?php }

add_action('admin_head', 'color_admin_header');
add_action('admin_menu', 'color_add_admin');
?>