<?php
// create custom plugin settings menu
add_action('admin_menu', 'aggiornare_settings');

function aggiornare_settings() {

	//create new sub-level menu
	add_submenu_page('themes.php', 'Aggiornare Custom Settings', 'Aggiornare Settings', 'administrator', 'aggiornare_menu.php', 'aggiornare_settings_page', 'aggiornareIcon.png');

	//call register settings function
	add_action( 'admin_init', 'register_settings' );
}

function register_settings() {
	//register our settings
	register_setting( 'aggiornare_settings_page', 'aggiornare_logo' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_logo_hover' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_navigation' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_image_border' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_homepage_image' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_homepage_headline' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_headline_color' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_homepage_intro' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_homepage_linkText' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_homepage_linkPage' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_footer_title' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_footer_text' );
	register_setting( 'aggiornare_settings_page', 'aggiornare_tagline' );
}


function aggiornare_settings_page() {
?>
<style type="text/css">
	.homePageStuff { border: 1px solid #DFDFDF; background: #fbfafa; -moz-border-radius: 15px; -webkit-border-radius: 15px; padding: 0 0 15px 0; width: 550px; margin: 0 0 15px 0; }
	input, select { width: 300px; }
	input.short { width: 100px; }
	input.checkbox { width: 15px; }
</style>
<div class="wrap">

<h2>Aggiornare Custom Settings</h2>
<p><em>Aggiornare</em> is Italian for <strong>&lsquo;to update&rsquo;</strong>. May you update many times over!</p>
<?php
    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>settings saved.</strong></p></div>';
    if ( $_REQUEST['updated'] ) echo '<div id="message" class="updated fade"><p><strong>Settings have been updated.  Thank you!</strong></p></div>';
?>
<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/options.php">
	<?php wp_nonce_field('update-options');
	settings_fields( 'aggiornare_settings_page' );
 ?>
 <p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th><h2>Initial Setup</h2></th>
   		</tr>
		<tr valign="top">
			<td>
				Aggiornare will work out of the box but, if you want it to look like it does here, please create 2 new pages, a "Home" page (name the page Home) and a blog page (you can name that page anything. When you create your "Home" page, choose the Home option underneath Template in the Attributes box (in the right sidebar). Then, click under Settings&raquo;Reading and choose "Front page displays a static page" and choose "Home" for the Front Page and the blog page for the Posts page.
			</td>
		<tr>
			<td><h2>Support</h2>Support for this theme is offered at the <a href="http://support.geekdesigngirl.com/">GeekDesignGirl Support Forums</a>.  Please check there to see if your question has already been answered or to submit an issues/feature request.</td>
		</tr>
		</tr>
	</table>
</div> 
 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th colspan="2"><h2>Logo Settings</h2></th>
   		</tr>
		<tr valign="top">
			<th scope="row" colspan="2"><input class="checkbox" type="checkbox" name="aggiornare_logo"<?php if(get_option('aggiornare_logo')=='on') { echo ' checked="checked"'; } ?> /> Enable custom logo?</th>
		</tr>
		<tr>
			<td colspan="2">If you enable this option, you MUST upload, via FTP, an image file named <code>logo.jpg</code> to the 'images' directory inside the 'aggiornare' directory. Image must be no larger 278 pixels wide by 160 pixels tall. <strong>The image must have permissions set to 777!</strong></td>
		</tr>
		<tr valign="top">
			<th scope="row" colspan="2"><input class="checkbox" type="checkbox" name="aggiornare_logo_hover"<?php if(get_option('aggiornare_logo_hover')=='on') { echo ' checked="checked"'; } ?> /> Enable hover over logo?</th>
		</tr>
		<tr>
			<td colspan="2">This option allows your logo to 'change' when a visitor hovers their mouse over the image. It must be the same dimensions as your <code>logo.jpg</code> image. If you enable this option, you MUST upload, via FTP, an image file named <code>logoHover.jpg</code> to the 'images' directory inside the 'aggiornare' directory. Image must be no larger 278 pixels wide by 160 pixels tall. <strong>The image must have permissions set to 777!</strong></td>
		</tr>
		<tr valign="top">
			<th scope="row" colspan="2"><input class="checkbox" type="checkbox" name="aggiornare_tagline"<?php if(get_option('aggiornare_tagline')=='on') { echo ' checked="checked"'; } ?> /> Display tagline?</th>
		</tr>
	</table>
</div>

 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th colspan="2"><h2>Navigation Settings</h2></th>
   		</tr>
		<tr valign="top">
			<th scope="row">Pages to display in top navigation:</th>
				<td><input type="text" name="aggiornare_navigation" value="<?php echo get_option('aggiornare_navigation'); ?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><em>Please put a comma separated list of the page ID's you would like to show in the top navigation. If you leave this blank, the default value is all pages. ONLY pages that are in this list will be displayed (that means you have to explicitly set child pages to display if you want them to).</em></td>
		</tr>
	</table>
</div>

 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th colspan="2"><h2>Image Settings</h2></th>
   		</tr>
		<tr valign="top">
			<th scope="row" colspan="2"><input class="checkbox" type="checkbox" name="aggiornare_image_border"<?php if(get_option('aggiornare_image_border')=='on') { echo ' checked="checked"'; } ?> /> Remove borders around all images?</th>
		</tr>
		<tr>
			<td colspan="2"><em>By default, Aggiornare draws a 1 pixel border around images that are placed in posts &amp; pages. Checking this box will remove the border.</em></td>
		</tr>
	</table>
</div>

 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th colspan="2"><h2>Homepage Settings</h2></th>
   		</tr>
		<tr valign="top">
			<th scope="row" colspan="2"><input class="checkbox" type="checkbox" name="aggiornare_homepage_image"<?php if(get_option('aggiornare_homepage_image')=='on') { echo ' checked="checked"'; } ?> /> Enable homepage banner image?</th>
		</tr>
		<tr>
			<td colspan="2">If you enable this option, you MUST upload, via FTP, an image file named <code>banner.jpg</code> to the 'images' directory inside the 'aggiornare' directory. Image should be 600 pixels wide by 227 pixels tall but if it's larger, the image will be centered in the "frame" and trust me, it'll look nice :) <strong>The image must have permissions set to 777!</strong></td>
		</tr>
		<tr valign="top">
			<th scope="row">Banner headline:</th>
				<td><input type="text" name="aggiornare_homepage_headline" value="<?php echo get_option('aggiornare_homepage_headline'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Banner headline color:</th>
				<td>#<input class="short" type="text" name="aggiornare_headline_color" value="<?php echo get_option('aggiornare_headline_color'); ?>" /> Hexidecimal value please (<a href="http://html-color-codes.com/">help?</a>)</td>
		</tr>
		<tr valign="top">
			<th scope="row">Introduction:</th>
				<td><textarea name="aggiornare_homepage_intro" style="width: 300px; height: 200px;"><?php echo get_option('aggiornare_homepage_intro'); ?></textarea></td>
		</tr>
		<tr valign="top">
			<th scope="row">Link text:</th>
				<td><input type="text" name="aggiornare_homepage_linkText" value="<?php echo get_option('aggiornare_homepage_linkText'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Link to page:</th>
				<td><select name="aggiornare_homepage_linkPage">
					<option value="">Please Select</option>
<?php

	$pages = get_pages(); 
  	foreach ($pages as $pagg) {
  	$option = '<option value="'.get_page_link($pagg->ID).'"';
  	if(get_option('aggiornare_homepage_linkPage') == get_page_link($pagg->ID)) {
  		$option .= ' selected="selected"';
  	}
  	$option .= '>';
	$option .= $pagg->post_title;
	$option .= '</option>';
	echo $option;
  }


?>
</select>
</td>
</tr>
</table>
</div>
 <div class=" homePageStuff">
    <table class="form-table">
   		<tr>
    		<th colspan="2"><h2>Footer Settings</h2></th>
   		</tr>
		<tr valign="top">
			<th scope="row">Footer header:</th>
				<td><input type="text" name="aggiornare_footer_title" value="<?php echo get_option('aggiornare_footer_title'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row" style="height: 50px;">Footer text:</th>
				<td rowspan="2"><textarea name="aggiornare_footer_text" style="width: 300px; height: 200px;"><?php echo get_option('aggiornare_footer_text'); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top">You may use <code>HTML</code> code in this box.</td>
		</tr>
	</table>
</div>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="aggiornare_homepage_image, aggiornare_homepage_headline, aggiornare_homepage_intro, aggiornare_homepage_linkText, aggiornare_homepage_linkPage, aggiornare_logo, aggiornare_logo_width, aggiornare_logo_height, aggiornare_footer_title, aggiornare_footer_text, aggiornare_tagline, aggiornare_headline_color" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>
<?php } ?>