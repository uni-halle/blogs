<?php
/*
Plugin Name: Soundcloud is Gold
Plugin URI: http://www.mightymess.com/soundcloud-is-gold-wordpress-plugin
Description: <strong><a href="http://www.mightymess.com/soundcloud-is-gold-wordpress-plugin">Soundcloud is gold</a></strong> integrates perfectly into wordpress. Select, set and add track, playlists or favorites to your post using the soundcloud player. Live Preview, easy, smart and straightforward. You can set default settings in the option page, choose your defaut soundcloud player (Standard, Artwork, Visual), its width, extra Css classes for you CSS lovers and your favorite colors. You'll still be able to set players to different settings before adding to your post if you fancy a one off change!
Version: 2.4.3
Author: Thomas Michalak
Author URI: http://www.mightymess.com/thomas-michalak
License: GPL2 or Later
*/

/*
 Default Sizes
 mini: h = 18, w = 100%
 standard: h = 81 (165), w = 100%
 artwork: h = 300, w = 300
 html5: h=166, w=100%
*/

define ('SIG_PLUGIN_DIR', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
//define( 'SIG_PLUGIN_DIR_HTTP', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
//define( 'SIG_PLUGIN_DIR', (is_ssl() ? str_replace('http:', 'https:', SIG_PLUGIN_DIR_HTTP) : SIG_PLUGIN_DIR_HTTP) );
//$httpPrefix = (is_ssl() ? 'https' : 'http');

define ('CLIENT_ID', '9UhNtlbTIh7V6YHJm9wwHgjCwd7t1xOk');

require_once('soundcloud-is-gold-notice.php');
require_once('soundcloud-is-gold-functions.php');

/** Get Plugin Version **/
function get_soundcloud_is_gold_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}

/*** Plugin Init ***/
add_action( 'admin_init', 'soundcloud_is_gold_admin_init' );
function soundcloud_is_gold_admin_init() {
    register_setting( 'soundcloud_is_gold_options', 'soundcloud_is_gold_options' );
    wp_register_script('soundcloud-is-gold-js', SIG_PLUGIN_DIR.'soundcloud-is-gold-js.js', array('jquery', 'farbtastic'));
    wp_register_script('carouFredSel', SIG_PLUGIN_DIR.'includes/jquery.carouFredSel-5.5.0-packed.js', array('jquery'));
    wp_register_style('soundcloud-is-gold-css', SIG_PLUGIN_DIR.'soundcloud-is-gold-css.css');
    //wp_register_style('ChunkFive', SIG_PLUGIN_DIR.'includes/ChunkFive-fontfacekit/stylesheet.css');
    //wp_register_style('Quicksand', SIG_PLUGIN_DIR.'includes/Quicksand-fontfacekit/stylesheet.css');
    wp_register_style('soundcloud-is-gold-editor-css', SIG_PLUGIN_DIR.'tinymce-plugin/soundcloud-is-gold-editor_plugin.css');
}
//Plugin option scripts
function soundcloud_is_gold_option_scripts() {
    wp_enqueue_script('farbtastic');
    wp_enqueue_script('soundcloud-is-gold-js');
    wp_enqueue_script('carouFredSel');
}
//Plugin option style
function soundcloud_is_gold_option_styles() {
  wp_enqueue_style('soundcloud-is-gold-css');
  wp_enqueue_style('farbtastic');
}
//Plugin Options' Fonts
function soundcloud_is_gold_option_fonts() {
  wp_enqueue_style('ChunkFive');
  wp_enqueue_style('Quicksand');
}
/*** Add Admin Menu ***/
add_action('admin_menu', 'soundcloud_is_gold_menu');
function soundcloud_is_gold_menu() {
	//Main
	$soundcloudIsGoldPage = add_menu_page('Soundcloud is Gold', 'Soundcloud is Gold', 'activate_plugins', __FILE__, 'soundcloud_is_gold_options', SIG_PLUGIN_DIR.'images/soundcloud-is-gold-icon.png');
	add_action( "admin_print_scripts-$soundcloudIsGoldPage", 'soundcloud_is_gold_option_scripts' ); // Add script
	add_action( "admin_print_styles-$soundcloudIsGoldPage", 'soundcloud_is_gold_option_styles' ); // Add Style
	//add_action( "admin_print_styles-$soundcloudIsGoldPage", 'soundcloud_is_gold_option_fonts' ); // Add Fonts
	//Development
	/*$soundcloudIsGoldDevPage = add_submenu_page( __FILE__, 'Soundcloud is Gold: Dev', 'Development Options', 'activate_plugins', 'soundcloud_is_gold_dev_options', soundcloud_is_gold_dev_options );
	add_action( "admin_print_scripts-$soundcloudIsGoldDevPage", 'soundcloud_is_gold_option_scripts' ); // Add script
	add_action( "admin_print_styles-$soundcloudIsGoldDevPage", 'soundcloud_is_gold_option_styles' ); // Add Style
	add_action( "admin_print_styles-$soundcloudIsGoldDevPage", 'soundcloud_is_gold_option_fonts' ); // Add Fonts
	*/
}

/*** Link to Settings from the plugin Page ***/
function soundcloud_is_gold_settings_link($links, $file) {
    if ( $file == plugin_basename( __FILE__ ) ) {
	$settings_link = '<a href="admin.php?page=soundcloud-is-gold/soundcloud-is-gold.php">'.__('Settings').'</a>';
	array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter("plugin_action_links", 'soundcloud_is_gold_settings_link', 10, 2 );

/*** Add tinyMce Soundcloud is Gold Plugin ***/
add_filter("mce_external_plugins", 'soundcloud_is_gold_mce_plugin');
//add_filter( 'mce_buttons', 'soundcloud_is_gold_mce_button' );
add_filter('mce_css', 'soundcloud_is_gold_mce_css');


/*** Options and Utilities***/
register_activation_hook(__FILE__, 'soundcloud_is_gold_add_defaults');
function soundcloud_is_gold_add_defaults() {
    //Get the options to see if plugin was already installed or not
		$tmpOptions = get_option('soundcloud_is_gold_options');
		//First Time install or reactivating
		if(empty($tmpOptions)) {
					//Set default Users and Pick a random user to be active
					$soundcloudIsGoldDefaultUsers = array(
									    'anna-chocola' => array('anna-chocola', 'https://i1.sndcdn.com/avatars-000009470567-spqine-large.jpg?4387aef'),
									    't-m' => array('t-m', 'https://i1.sndcdn.com/avatars-000002680779-fkvvpj-large.jpg?4387aef'),
									    'my-disco-nap' => array('my-disco-nap', 'https://i1.sndcdn.com/avatars-000012680897-foqv41-large.jpg?b9f92e9')
									    );
					$soundcloudIsGoldDefaultUser = $soundcloudIsGoldDefaultUsers[array_rand($soundcloudIsGoldDefaultUsers, 1)][0];
					//Set Default Settings
					$soundcloudIsGoldDefaultSettings = array(
				                                        false,
				                                        true,
																								true,
																								false
					);
					//Set default Width Settings
					$soundcloudIsGoldWitdhDefaultSettings = array(
				                                       "type" => "custom",
				                                       "wp" => "medium",
				                                       "custom" => "100%"
					);
					//Register defaults settings
					$args = array(
					    'soundcloud_is_gold_users' => $soundcloudIsGoldDefaultUsers,
					    'soundcloud_is_gold_active_user' => $soundcloudIsGoldDefaultUser,
					    'soundcloud_is_gold_settings' => $soundcloudIsGoldDefaultSettings,
					    'soundcloud_is_gold_width_settings' => $soundcloudIsGoldWitdhDefaultSettings,
					    'soundcloud_is_gold_classes' => '',
					    'soundcloud_is_gold_color' => 'ff7700'
						  );
					//Update Settings
					update_option('soundcloud_is_gold_options', $args);
		}else{
			//Updating plugin
		}
		//For both New and Updating
		add_action( 'admin_notices', 'soundcloud_is_gold_update_admin_notice__info');
}

//Deactivation
//register_deactivation_hook(__FILE__, 'soundcloud_is_gold_deactivate_plugin');
function soundcloud_is_gold_deactivate_plugin() {
}
// Delete options table entries ONLY when plugin is deactivated AND gets deleted
register_uninstall_hook(__FILE__, 'soundcloud_is_gold_delete_plugin_options');
function soundcloud_is_gold_delete_plugin_options() {
	delete_option("soundcloud_is_gold_options");
}

// display default admin notice
function soundcloud_is_gold_add_settings_errors() {
    settings_errors();
}
add_action('admin_notices', 'soundcloud_is_gold_add_settings_errors');


/**********************************************/
/**                                          **/
/**            THE OPTIONS PAGE              **/
/**                                          **/
/**********************************************/
function soundcloud_is_gold_options(){
    $options = get_option('soundcloud_is_gold_options');
		//Fix bug when updating to 2.4.2 where API requests can only use user id
    $options = soundcloud_is_gold_update_users($options);
		//printl($options);
    $soundcloudIsGoldActiveUser = isset($options['soundcloud_is_gold_active_user']) ? $options['soundcloud_is_gold_active_user'] : '';
    $soundcloudIsGoldUsers = isset($options['soundcloud_is_gold_users']) ? $options['soundcloud_is_gold_users'] : '';
    $soundcloudIsGoldSettings = isset($options['soundcloud_is_gold_settings']) ? $options['soundcloud_is_gold_settings'] : '';
    $soundcloudIsGoldWidthSettings = isset($options['soundcloud_is_gold_width_settings']) ? $options['soundcloud_is_gold_width_settings'] : '';
		$soundcloudIsGoldHeightSettings = isset($options['soundcloud_is_gold_height_settings']) ? $options['soundcloud_is_gold_height_settings'] : '';
	  $soundcloudIsGoldClasses = isset($options['soundcloud_is_gold_classes']) ? $options['soundcloud_is_gold_classes'] : '';
    $soundcloudIsGoldColor = isset($options['soundcloud_is_gold_color']) ? $options['soundcloud_is_gold_color'] : '';

		//weird bug limit as to be set to 2 as some user don't return anything when set to 1
    $soundcloudIsGoldApiCall = 'https://api.soundcloud.com/users/'.$soundcloudIsGoldActiveUser.'/tracks.json?limit=2&client_id='.CLIENT_ID;
		$soundcloudIsGoldApiResponse = get_soundcloud_is_gold_api_response($soundcloudIsGoldApiCall);

		if(isset($soundcloudIsGoldApiResponse['response']) && $soundcloudIsGoldApiResponse['response']){
			foreach($soundcloudIsGoldApiResponse['response'] as $soundcloudMMLatestTrack){
				$soundcouldMMId = (string)$soundcloudMMLatestTrack['id'];
				break;//we just want the first track as we have to loop because of the limit=2 bug
			}
    }
    $soundcouldMMShortcode = '[soundcloud id='.$soundcouldMMId.']';

?>

    <script type="text/javascript">
	//Set default Soundcloud Is Gold Settings
        <?php get_soundcloud_is_gold_default_settings_for_js(); ?>
    </script>


    <!-- XXS test -->
    <!-- <form method="POST" action="
http://localhost/~thomas/Others/dev/wp-admin/admin-ajax.php?action=get_soundcloud_player" />
<input type="text" name="id" value='"></param></object><img src=xonerror=alert(1) />' />
<input type="text" name="format" value="1">
<input type="submit" name="submit" />
</form> -->

  <div class="soundcloudMMWrapper soundcloudMMOptions soundcloudMMMainWrapper">
		<!-- Survey -->
		<a href="https://mightymess.typeform.com/to/Bg82kF" id="soundcloudMMSurvey" class="button-primary" target="_blank" >Help me make a better plugin by taking this super short survey ></a>
		<!-- Header -->
		<div id="soundcloudMMTop">
			<div class="leftPart">
				<img id="soundcloudMMPowered" width="104" height="32" src="https://developers.soundcloud.com/assets/powered_by_black-4339b4c3c9cf88da9bfb15a16c4f6914.png">
				<h1>SoundCloud is gold <small>v<?php echo get_soundcloud_is_gold_version($options) ?></small></h1>
				<p>This is your main options page. You can set a default styling for your site and link to your soundcloud accounts.</p>
			</div>
			<div class="rightPart">
				<ul id="soundcloudMMExtras" class="">
						<li><a href="https://wordpress.org/tags/soundcloud-is-gold?forum_id=10" title="Soundcloud is Gold Forum" class="soundcloudMMBt button-primary">Support Forum</a></li>
						<li>
						<form class="soundcloudMMBtForm" action="https://www.paypal.com/cgi-bin/webscr" method="post">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="9VGA6PYQWETGY">
										<input type="submit" name="submit" value="Please help with a donation" class="soundcloudMMBt button-primary" alt="PayPal - The safer, easier way to pay online.">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
								</form>
						</li>
				</ul>
			</div>
		</div>
		<!-- Main -->
        <div id="soundcloudMMMain" class="">
            <form method="post" action="options.php" id="soundcloudMMMainForm" name="soundcloudMMMainForm" class="">
	    <p class="hidden soundcloudMMId" id="soundcloudMMId-<?php echo $soundcouldMMId ?>"><?php echo $soundcouldMMId ?></p>
            <?php settings_fields('soundcloud_is_gold_options'); ?>
                <ul id="soundcloudMMSettings">
                    <!-- Username -->
		    <li class="soundcloudMMBox"><label class="optionLabel">User Name</label>
			<?php get_soundcloud_is_gold_username_interface($options, $soundcloudIsGoldUsers) ?>
		    </li>
		    <!-- Default Settings -->
                    <li class="soundcloudMMBox">
												<label class="optionLabel">Default Settings</label>
                        <ul class="subSettings checkboxes">
                            <li><input type="checkbox" <?php echo (isset($soundcloudIsGoldSettings[0]) && $soundcloudIsGoldSettings[0]) ? 'checked="checked"' : ''?> name="soundcloud_is_gold_options[soundcloud_is_gold_settings][0]" value="true" class="soundcloudMMAutoPlay" id="soundcloudMMAutoPlay"/><label for="soundcloudMMAutoPlay">Play Automatically</label></li>
                            <li><input type="checkbox" <?php echo (isset($soundcloudIsGoldSettings[1]) && $soundcloudIsGoldSettings[1]) ? 'checked="checked"' : ''?> name="soundcloud_is_gold_options[soundcloud_is_gold_settings][1]" value="true" class="soundcloudMMShowComments" id="soundcloudMMShowComments"/><label for="soundcloudMMShowComments">Show comments</label></li>
			    									<li><input type="checkbox" <?php echo (isset($soundcloudIsGoldSettings[2]) && $soundcloudIsGoldSettings[2]) ? 'checked="checked"' : ''?> name="soundcloud_is_gold_options[soundcloud_is_gold_settings][2]" value="true" class="soundcloudMMShowArtwork" id="soundcloudMMShowArtwork"/><label for="soundcloudMMShowArtwork">Show Artwork</label></li>
														<li><input type="checkbox" <?php echo (isset($soundcloudIsGoldSettings[3]) && $soundcloudIsGoldSettings[3]) ? 'checked="checked"' : ''?> name="soundcloud_is_gold_options[soundcloud_is_gold_settings][3]" value="true" class="soundcloudMMShowVisual" id="soundcloudMMShowVisual"/><label for="soundcloudMMShowVisual">Full Visual <small>(use soundcloud colors)</small></label></li>
                        </ul>
                    </li>
		    <!-- Sizes -->
                    <li class="soundcloudMMBox"><label class="optionLabel">Default Size</label>
                        <ul id="soundcloudMMWidthSetting" class="subSettings texts">
                            <li>
                                <input name="soundcloud_is_gold_options[soundcloud_is_gold_width_settings][type]" <?php echo ($soundcloudIsGoldWidthSettings['type'] == "wp") ? 'checked="checked"' : ''; ?> id="soundcloudMMWpWidth" value="wp" type="radio" class="soundcloudMMWpWidth soundcloudMMWidthType radio"/><label for="soundcloudMMWpWidth">Media Width</label>
                                <select class="soundcloudMMInput soundcloudMMWidth" name="soundcloud_is_gold_options[soundcloud_is_gold_width_settings][wp]">
                                <?php foreach(get_soundcloud_is_gold_wordpress_sizes() as $key => $soundcloudIsGoldMediaSize) : ?>
                                    <?php
                                    //First Time, then Other Times
                                    if($soundcloudIsGoldWidthSettings['wp'] == 'medium') $soundcloudIsGoldMediaSelected = ($key == $soundcloudIsGoldWidthSettings['wp']) ? 'selected="selected"' : '';
                                    else $soundcloudIsGoldMediaSelected = ($soundcloudIsGoldMediaSize[0] == $soundcloudIsGoldWidthSettings['wp']) ? 'selected="selected"' : ''; ?>
                                    <option <?php echo $soundcloudIsGoldMediaSelected ?> value="<?php echo $soundcloudIsGoldMediaSize[0]?>" class="soundcloudMMWpSelectedWidth"><?php echo $key.': '.$soundcloudIsGoldMediaSize[0]?></option>
                                <?php endforeach; ?>
                                </select>
                            </li>
                            <li>
                                <input name="soundcloud_is_gold_options[soundcloud_is_gold_width_settings][type]" <?php echo ($soundcloudIsGoldWidthSettings['type'] == "custom") ? 'checked="checked"' : ''; ?> id="soundcloudMMCustomWidth" value="custom" type="radio" class="soundcloudMMCustomWidth soundcloudMMWidthType radio"/><label for="soundcloudMMCustomWidth">Custom Width</label>
                                <input name="soundcloud_is_gold_options[soundcloud_is_gold_width_settings][custom]" id="soundcloudMMCustomSelectedWidth" class="soundcloudMMInput soundcloudMMWidth soundcloudMMCustomSelectedWidth" type="text" name="soundcloud_is_gold_options[soundcloudMMCustomSelectedWidth]" value="<?php echo $soundcloudIsGoldWidthSettings['custom'] ?>" />
                            </li>
														<li>
															<input type="checkbox" <?php echo (isset($soundcloudIsGoldHeightSettings['square']) && $soundcloudIsGoldHeightSettings['square']) ? 'checked="checked"' : ''?> name="soundcloud_is_gold_options[soundcloud_is_gold_height_settings][square]" value="true" class="soundcloudMMSquareHeight" id="soundcloudMMSquareHeight"/><label for="soundcloudMMSquareHeight">Force Visual Square</label>
														</li>
                        </ul>
                    </li>
		    <!-- Color and Classes -->
                    <li class="soundcloudMMBox"><label class="optionLabel">Extras</label>
                        <ul class="subSettings texts">
                            <li>
                                <label>Color</label>
                                <div class="soundcloudMMColorPickerContainer" id="soundcloudMMColorPickerContainer">
                                    <input type="text" class="soundcloudMMInput soundcloudMMColor" id="soundcloudMMColor" name="soundcloud_is_gold_options[soundcloud_is_gold_color]" value="<?php echo $soundcloudIsGoldColor ?>" style="background-color:<?php echo $soundcloudIsGoldColor ?>"/><a href="#" class="soundcloudMMBt soundcloudMMBtSmall inline blue soundcloudMMRounder soundcloudMMResetColor">reset to default</a>
                                    <div id="soundcloudMMColorPicker" class="shadow soundcloudMMColorPicker"><div id="soundcloudMMColorPickerSelect" class="soundcloudMMColorPickerSelect"></div><a id="soundcloudMMColorPickerClose" class="blue soundcloudMMBt soundcloudMMColorPickerClose">done</a></div>
                                </div>
                            </li>
                            <li class="clear">
                                <label>CSS Classes <small>(no commas)</small></label><input class="soundcloudMMInput soundcloudMMClasses" type="text" name="soundcloud_is_gold_options[soundcloud_is_gold_classes]" value="<?php echo $soundcloudIsGoldClasses ?>" />
                            </li>
                        </ul>
                    </li>
		    <!-- Preview -->
                    <li class="soundcloudMMBox"><label class="optionLabel previewLabel">Live Preview <small>(your latest track)</small></label>
                        <?php if($soundcloudIsGoldApiResponse['response']) :?>
                        <p class="soundcloudMMEmbed soundcloudMMEmbedOptions" style="text-align:center;">
			    							<!-- Soundcloud Preview here -->
												</p>
                        <p class="soundcloudMMLoading soundcloudMMPreviewLoading" style="display:none"></p>
                        <?php else : ?>
                        <!-- Error getting Json -->
                        <div class="soundcloudMMJsonError"><p><?php echo $soundcloudIsGoldApiResponse['error'] ? $soundcloudIsGoldApiResponse['error'] : "Oups! There's been a error while getting the tracks from soundcloud. Please reload the page."?></p></div>
                        <?php endif; ?>
                    </li>
                </ul>
		<!-- Submit -->
                <p id="soundcloudMMSubmit"><input type="submit" name="Submit" value="<?php _e('Save Your SoundCloud Settings') ?>" class="soundcloudMMButton-primary button-primary"/></p>
	    </form>
        </div>
        <p id="disclaimer">SoundCloud and SoundCloud Logo are trademarks of SoundCloud Ltd.</p>
    </div>

    <?php
}


?>
