<?php


$themename = "Jenny";
$shortname = "p2h";
$version = "1.5";

//Header Customizations
function p2h_wp_head() { 
		
	// Custom CSS block in Theme Options Page
	$custom_css = get_option('p2h_custom_css');
	if($custom_css != '')
	{
	$output = '<style type="text/css">'."\n";
	$output .= $custom_css . "\n";
	$output .= '</style>'."\n";
	echo $output;
	}

	
	//If Set in Theme Options, Add Feed URL in Head
	if(get_option('p2h_feedurl') != '') {
	echo '<link rel="alternate" type="application/rss+xml" href="'. get_option('p2h_feedurl') .'" title="'. get_bloginfo('name') .' RSS Feed"/>'."\n";
	}

}
add_action('wp_head','p2h_wp_head');

	
//Header Customization -- Remove Auto Feed URL
if(get_option('p2h_feedurl') != '') {
	// Remove the links to feed
	remove_action( 'wp_head', 'feed_links', 2);
}

// Remove the links to the extra feeds such as category feeds
if(get_option('p2h_cleanfeedurls') !='' ) {
remove_action( 'wp_head', 'feed_links_extra', 3 ); 
}

//Automatically List StyleSheets in Folder
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if((stristr($alt_stylesheet_file, ".css") !== false) && (stristr($alt_stylesheet_file, "default") == false)){
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}
array_unshift($alt_stylesheets, "default.css"); 


// Create theme options
global $options;
$options = array (

array( "name" => __('Theme Styles','shaan'),
	"type" => "section"),

array( "name" => __('Choose a color scheme and add custom CSS styles.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => "Colour Scheme",
	"desc" => "Select a colour scheme for the theme. Future versions will have multiple styles.",
	"id" => $shortname."_alt_stylesheet",
	"type" => "select",
	"options" => $alt_stylesheets,
	"std" => "default.css"),

array( "name" => __('Custom Styles','shaan'),
	"desc" => __('Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets. eg: a.button{color:green}','shaan'),
	"id" => $shortname."_custom_css",
	"type" => "textarea",
	"std" => ""),		

array( "type" => "close"),

array( "name" => __('RSS Feeds/Facebook/Twitter','shaan'),
	"type" => "section"),

array( "name" => __('Set up social links.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => __('Custom Feed URL','shaan'),
	"desc" => __('You can use your own feed URL (<strong>with http://</strong>). Paste your Feedburner URL here to let readers see it in your website.','shaan'),
	"id" => $shortname."_feedurl",
	"type" => "text",
	"std" => get_bloginfo('rss2_url')),
	
array( "name" => __('Delete Extra Feeds','shaan'),
	"desc" => __('WordPress adds feeds for categories, tags, etc., by default. Check this box to remove them and reduce the clutter.','shaan'),
	"id" => $shortname."_cleanfeedurls",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __('Twitter ID','shaan'),
	"desc" => __('Your Twitter user name, please. It will be shown in the navigation bar. Leaving it blank will keep the Twitter icon supressed.','shaan'),
	"id" => $shortname."_twitterid",
	"type" => "text",
	"std" => ""),

array( "name" => __('Facebook Page','shaan'),
	"desc" => __('Link to your Facebook page, <strong>with http://</strong>. It will be shown in the navigation bar. Leaving it blank will keep the Facebook icon supressed.','shaan'),
	"id" => $shortname."_facebookid",
	"type" => "text",
	"std" => ""),
	
array( "type" => "close"),

//FOOTER
array( "name" => "Footer",
	"type" => "section"),

array( "name" => __('Customize footer of your website/blog.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => "Footer Text",
					"desc" => "Add your own custom footer text.",
					"id" => $shortname."_footer_text",
					"std" => "",
					"type" => "textarea"),

array( "name" => "Hide Footer Credits",
					"desc" => "Send a contribution and get the version where you can remove this credit.",
					"id" => "",
					"std" => "",
					"type" => "checkbox"),

array( "type" => "close"),

//ADVERTISEMENTS --- POST ADS 
array( "name" => "Advertisements",
	"type" => "section"),

array( "name" => __('Show ads on your website/blog.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => "Ad Above Posts",
					"desc" => "Enter your Adsense code (or other ad network code) here. This ad will be displayed at the beginning of posts, below title on Post Pages and Pages with ad-supporting template. It is very basic and effective option for putting ads on your blog. If you want more functionality, get a specialized Ad plugin from <a href='http://wordpress.org/extend/plugins/'>WordPress</a>.",
					"id" => $shortname."_posttop_adcode",
					"std" => "",
					"type" => "textarea"),

array( "name" => "Ad Below Posts",
					"desc" => "Enter your Adsense code (or other ad network code) here. This ad will be displayed at the end of post content on Post Pages and Pages with ad-supporting template. Please make sure that you don't activate more ads than what is allowed by your ad network. Adsense allows up to 3 on one page.",
					"id" => $shortname."_postend_adcode",
					"std" => "",
					"type" => "textarea"),

array( "type" => "close"),

//Contact Form
array( "name" => "Contact Form",
	"type" => "section"),

array( "name" => __('Set your contct form email.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => __('Contact Email','shaan'),
	"desc" => __('This email address will be used by <strong>Contact Form</strong> for sending emails. Create a page with Contact Page as the template to use the in-built Contact Form.','shaan'),
	"id" => $shortname."_contact_email",
	"type" => "text",
	"std" => ""),	

array( "type" => "close"),

//Analytics Code
array( "name" => "Tracking/Other Codes",
	"type" => "section"),

array( "name" => __('Insert Web tracking/analytics and other codes here.','shaan'),
	"type" => "section-desc"),
	
array( "type" => "open"),

array( "name" => __('Analytics/Tracking Code','shaan'),
	"desc" => __('You can paste your Google Analytics or other codes in this box. The codes will be automatically added to the footer.','shaan'),
	"id" => $shortname."_analytics_code",
	"type" => "textarea",
	"std" => ""),	

array( "type" => "close"),

);
		
function p2h_add_admin() {

    global $themename, $shortname, $options;

	if ( isset ( $_GET['page'] ) && ( $_GET['page'] == basename(__FILE__) ) ) {

		if ( isset ($_REQUEST['action']) && ( 'save' == $_REQUEST['action'] ) ){

			foreach ( $options as $value ) {
				if ( array_key_exists('id', $value) ) {
					if ( isset( $_REQUEST[ $value['id'] ] ) ) { 
						update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
					} 
					else {
						delete_option( $value['id'] );
					}
				}
			}
			$_REQUEST['saved'] = 1;
		} 
		else if ( isset ($_REQUEST['action']) && ( 'reset' == $_REQUEST['action'] ) ) {
			foreach ($options as $value) {
				if ( array_key_exists('id', $value) ) {
					delete_option( $value['id'] );
				}
			}
			$_REQUEST['reset'] = 1;
		}
	}

add_menu_page($themename, $themename, 'administrator', basename(__FILE__), 'p2h_admin');
add_submenu_page(basename(__FILE__), $themename . ' Theme Options', 'Theme Options', 'administrator',  basename(__FILE__),'p2h_admin'); // Default
}

function p2h_add_init() {

$file_dir=get_bloginfo('template_directory');
wp_enqueue_style("p2hCss", $file_dir."/functions/theme-options.css", false, "1.0", "all");
wp_enqueue_script("p2hScript", $file_dir."/functions/theme-options.js", false, "1.0");

}

function p2h_admin() {

    global $themename, $shortname, $version, $options;
	$i=0;

	if ( isset ($_REQUEST['saved']) && ($_REQUEST['saved'] ) )echo '<div id="message" class="updated"><p>'.$themename.' settings saved.</p></div>';
	if ( isset ($_REQUEST['reset']) && ($_REQUEST['reset'] ) ) echo '<div id="message" class="updated"><p>'.$themename.' settings reset.</p></div>';
    
?>

<div class="wrap ">
<div class="options_wrap">
<h2 class="settings-title"><?php echo $themename; ?> Settings</h2>
<p class="top-notice">Use these options to customize <?php echo $themename; ?> theme.</p>
<form method="post">


<?php foreach ($options as $value) { 
switch ( $value['type'] ) {
case "section":
?>
	<div class="section_wrap">
	<h3 class="section_title"><?php echo $value['name']; ?> 

<?php break; 
case "section-desc":
?>
	<span><?php echo $value['name']; ?></span></h3>
	<div class="section_body">

<?php 
break;
case 'text':
?>

	<div class="options_input options_text">
		<div class="options_desc"><?php echo $value['desc']; ?></div>
		<span class="labels"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
		<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>"/>
	</div>

<?php
break;
case 'textarea':
?>
	<div class="options_input options_textarea">
		<div class="options_desc"><?php echo $value['desc']; ?></div>
		<span class="labels"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
		<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo $value['std']; } ?></textarea>
	</div>

<?php 
break;
case 'select':
?>
	<div class="options_input options_select">
		<div class="options_desc"><?php echo $value['desc']; ?></div>
		<span class="labels"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
		<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		<?php foreach ($value['options'] as $option) { ?>
				<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
		</select>
	</div>

<?php
break;
case "radio":
?>
	<div class="options_input options_select">
		<div class="options_desc"><?php echo $value['desc']; ?></div>
		<span class="labels"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
		  <?php foreach ($value['options'] as $key=>$option) { 
			$radio_setting = get_option($value['id']);
			if($radio_setting != ''){
				if ($key == get_option($value['id']) ) {
					$checked = "checked=\"checked\"";
					} else {
						$checked = "";
					}
			}else{
				if($key == $value['std']){
					$checked = "checked=\"checked\"";
				}else{
					$checked = "";
				}
			}?>
			<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
			<?php } ?>
	</div>

<?php
break;
case "checkbox":
?>
	<div class="options_input options_checkbox">
		<div class="options_desc"><?php echo $value['desc']; ?></div>
		<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
		<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
		<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	 </div>

<?php
break;
case "close":
$i++;
?>
<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save Changes" /></span>
</div><!--#section_body-->
</div><!--#section_wrap-->

<?php break;
}
}
?>

<input type="hidden" name="action" value="save" />
<span class="submit">
<input name="save" type="submit" value="Save All Changes" />
</span>
</form>


<form method="post">
<span class="submit">
<input name="reset" type="submit" value="Delete/Reset All Options" />
<input type="hidden" name="action" value="reset" />
</span>
</form>
<br/>
</div><!--#options-wrap-->

<div class="sidebox">
	<h2>Support <?php echo $themename; ?>!</h2>
	<p>You are using <strong><a href="http://www.speckygeek.com/jenny-free-wordpress-theme/"><?php echo $themename; ?> <?php echo $version; ?></a></strong>, a free theme by <a href="http://www.speckygeek.com">Specky Geek</a>, a technology blog.</p>
	<p>It has taken a lot of effort to make it as good as a premium theme. If you find it useful, why not reward me for my hard work! Be generous and send me as many dollars as you can.</p>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="6WPPE2PW6ERVC">
	<table>
	<tr><td><input type="hidden" name="on0" value="Reward for Jenny WP Theme">Reward for Jenny WP Theme</td></tr><tr><td><select name="os0">
		<option value="Five Dollars">Five Dollars $5.00</option>
		<option value="Ten Dollars">Ten Dollars $10.00</option>
		<option value="Fifteen Dollars">Fifteen Dollars $15.00</option>
		<option value="Twenty Dollars">Twenty Dollars $20.00</option>
		<option value="Twenty Five Dollars">Twenty Five Dollars $25.00</option>
		<option value="Thirty Five Dollars">Thirty Five Dollars $35.00</option>
		<option value="Fifty Dollars">Fifty Dollars $50.00</option>
		<option value="Hundred Dollars">Hundred Dollars $100.00</option>
	</select> </td></tr>
	</table>
	<input type="hidden" name="currency_code" value="USD">
	<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
	<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>

	<hr />
	<ul>
	<li><a href="http://www.speckygeek.com/jenny-free-wordpress-theme/">Help/Updates for Jenny</a></li>
	<li><a href="http://www.speckygeek.com/wordpress-themes/">Free WordPress Themes</a></li>
	<li><a href="http://www.speckygeek.com/contact-us/">Contact Specky Geek</a></li>
	</ul>
</div>

	</div>
<?php
}
add_action('admin_init', 'p2h_add_init');
add_action('admin_menu' , 'p2h_add_admin'); 
?>
