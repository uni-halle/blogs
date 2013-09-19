<?php /*
Plugin Name: WP Social Share Privacy
Plugin URI: http://fkblog.de/wp/plugins/wp-social-share-privacy/
Description: <a href="http://www.heise.de/extras/socialshareprivacy/" title="jQuery Plug-In socialshareprivacy">jQuery Plug-In socialshareprivacy</a> from Heise.de for WordPress.
Version: 1.1.6
Author: Fabian K&uuml;nzel
Author URI: http://fkblog.de/
Text Domain: socialshareprivacy
License: MIT License (http://www.opensource.org/licenses/mit-license.php) 
*/

define(BASE_URL, plugins_url('/wp-social-share-privacy-plugin/'));
load_plugin_textdomain('ssp_lang', false, basename( dirname( __FILE__ ) ) . '/languages' );

// create custom plugin settings menu
function ssp_create_submenu() {

	//create new submenu page to settings-entry
  $subpage = add_submenu_page(
    'options-general.php',
    __("Social Share Privacy"),
    __("Social Share Privacy"),
    'administrator',
     __FILE__, 'socialshareprivacy_settings_page'
  );
	//call register settings function
	add_action( 'admin_init', 'register_ssp_settings' );    
}
add_action('admin_menu', 'ssp_create_submenu');

function register_ssp_settings() {
	//register our settings
	
	// General Setting
	register_setting( 'spp-group', 'ssp_info_link' );
	register_setting( 'spp-group', 'ssp_txt_help' );
	register_setting( 'spp-group', 'ssp_settings_perma' );
	register_setting( 'spp-group', 'ssp_cookie_domain' );
	register_setting( 'spp-group', 'ssp_cookie_path' );
	register_setting( 'spp-group', 'ssp_cookie_expire' );
	
	// Facebook Setting
	register_setting( 'spp-group', 'ssp_fb_status' );
	register_setting( 'spp-group', 'ssp_fb_dummy_img' );
	register_setting( 'spp-group', 'ssp_fb_txt_info' );
	register_setting( 'spp-group', 'ssp_fb_txt_fb_on' );
	register_setting( 'spp-group', 'ssp_fb_txt_fb_on' );
	register_setting( 'spp-group', 'ssp_fb_perma_option' );
	register_setting( 'spp-group', 'ssp_fb_display_name' );
	register_setting( 'spp-group', 'ssp_fb_referrer_track' );
	register_setting( 'spp-group', 'ssp_fb_language' );
	register_setting( 'spp-group', 'ssp_fb_action' );
	
	// Twitter Setting
	register_setting( 'spp-group', 'ssp_tw_status' );
	register_setting( 'spp-group', 'ssp_tw_dummy_img' );
	register_setting( 'spp-group', 'ssp_tw_txt_info' );
	register_setting( 'spp-group', 'ssp_tw_txt_twitter_on' );
	register_setting( 'spp-group', 'ssp_tw_txt_twitter_on' );
	register_setting( 'spp-group', 'ssp_tw_perma_option' );
	register_setting( 'spp-group', 'ssp_tw_display_name' );
	register_setting( 'spp-group', 'ssp_tw_referrer_track' );
	register_setting( 'spp-group', 'ssp_tw_language' );
	
	// Google+ Setting
	register_setting( 'spp-group', 'ssp_gp_status' );
	register_setting( 'spp-group', 'ssp_gp_dummy_img' );
	register_setting( 'spp-group', 'ssp_gp_txt_info' );
	register_setting( 'spp-group', 'ssp_gp_txt_gplus_on' );
	register_setting( 'spp-group', 'ssp_gp_txt_gplus_on' );
	register_setting( 'spp-group', 'ssp_gp_perma_option' );
	register_setting( 'spp-group', 'ssp_gp_display_name' );
	register_setting( 'spp-group', 'ssp_gp_referrer_track' );
	register_setting( 'spp-group', 'ssp_gp_language' );	
}

function admin_general_description() {
 echo _e("<p><strong>Wenn Optionen leergelassen werden, wird der Standardwert aus dem Heise-Plugin verwendet.</strong></p>");
}


// Uninstall options
function ssp_uninstall() {
  if ( function_exists('current_user_can') && current_user_can('manage_options') ) {
  	
    // old ssp settings
	delete_option('ssp_info_link' );
	delete_option('ssp_txt_help' );
	delete_option('ssp_settings_perma' );
	delete_option('ssp_cookie_domain' );
	delete_option('ssp_cookie_path' );
	delete_option('ssp_cookie_expire' );
	
	// Facebook Setting
	delete_option('ssp_fb_status' );
	delete_option('ssp_fb_app_id' );
	delete_option('ssp_fb_dummy_img' );
	delete_option('ssp_fb_txt_info' );
	delete_option('ssp_fb_txt_fb_on' );
	delete_option('ssp_fb_txt_fb_on' );
	delete_option('ssp_fb_perma_option' );
	delete_option('ssp_fb_display_name' );
	delete_option('ssp_fb_referrer_track' );
	delete_option('ssp_fb_language' );
	delete_option('ssp_fb_action' );
	
	// Twitter Setting
	delete_option('ssp_tw_status' );
	delete_option('ssp_tw_dummy_img' );
	delete_option('ssp_tw_txt_info' );
	delete_option('ssp_tw_txt_twitter_on' );
	delete_option('ssp_tw_txt_twitter_on' );
	delete_option('ssp_tw_perma_option' );
	delete_option('ssp_tw_display_name' );
	delete_option('ssp_tw_referrer_track' );
	delete_option('ssp_tw_language' );
	
	// Google+ Setting
	delete_option('ssp_gp_status' );
	delete_option('ssp_gp_dummy_img' );
	delete_option('ssp_gp_txt_info' );
	delete_option('ssp_gp_txt_gplus_on' );
	delete_option('ssp_gp_txt_gplus_on' );
	delete_option('ssp_gp_perma_option' );
	delete_option('ssp_gp_display_name' );
	delete_option('ssp_gp_referrer_track' );
	delete_option('ssp_gp_language' );	
  } else {
		wp_die('<p>'.__('Du hast nicht ausreichend Rechte um die Einstellungen zu &auml;ndern.', 'ssp_lang').'</p>');
	}
}

function socialshareprivacy_settings_page() {

	if ( isset($_POST['action']) && ('update' == $_POST['action']) ) {
		$message_export = '<br class="clear"><div class="updated"><p>';
		$message_export.= __('Einstellungen wurden aktualisiert!', 'ssp_lang');
		$message_export.= '</p></div>';
	}
	
  if ( (isset($_POST['deinstall_yes'])) && (isset($_POST['deinstall']) && $_POST['deinstall'] == 'uninstall') ) {
		ssp_uninstall();
		$message_export = '<br class="clear"><div class="updated"><p>';
		$message_export.= __('WP Social Share Privacy Einstellungen wurden deinstalliert!', 'ssp_lang');
		$message_export.= '</p></div>';
	}
?>
<div class="wrap">
<div class="icon32" id="icon-options-general"></div>
<h2><?php _e('Social Share Privacy', 'ssp_lang'); ?></h2>
<?php echo $message_export; ?>
		<div id="poststuff" class="ui-sortable">
			<div class="postbox closed" >
				<h3><?php _e('Information zum Plugin', 'ssp_lang') ?></h3>
				<div class="inside">
					<p><?php _e('<strong>2 Klicks für mehr Datenschutz</strong>', 'ssp_lang') ?></p>
					<p><?php _e('Das jQuery Plugin <em>Social Share Privacy</em> stammt von <a href="http://www.heise.de/" title="Heise Online">Heise Online</a> und wurde von <a href="http://fkblog.de/"" title="Fabian K&uuml;nzel">Fabian K&uuml;nzel</a> an WordPress angepasst.', 'ssp_lang') ?></p>
          <p><?php _e('Mit dem <em>WP Social Share Privacy</em> Plugin verhindert Ihr, das schon beim Aufruf eurer Webseite, Daten an die Social Media Plattformen, in die USA gesendet werden.<br />Ihr euren Besuchern selbst die Wahl, ob er damit einverstanden ist und sch&uuml;tzt somit die Privatsp&auml;re derjenigen, die dieses nicht wollen.', 'ssp_lang') ?></p>
          <p><?php _e('Auch k&ouml;nnt Ihr mit Hilfe dieses Plugins ein Schritt in Richtung Datenschutz Sicherheit gehen, heise.de hat hierzu einen Artikel; <em><a href="http://www.heise.de/security/artikel/Das-verraet-Facebooks-Like-Button-1230906.html" title="Das Like-Problem">Das Like-Problem</a> Ver&ouml;ffentlicht, in dem &uuml;ber das Datenschutz Problem mit den Social Media Plugins eingegangen wird.', 'ssp_lang') ?></p>
          <p><?php _e('Das Orginale jQuery Plugin k&ouml;nnt Ihr unter <a href="http://www.heise.de/extras/socialshareprivacy/" title="jQuery Plug-In socialshareprivacy – Dokumentation">http://www.heise.de/extras/socialshareprivacy/</a> Downloaden', 'ssp_lang') ?></p>
				</div>
			</div>
		</div>
		<br class="clear">
    <h3><?php _e('Plugin Einstellungen'); ?></h3>
    <form action="options.php" method="post">
    <?php settings_fields( 'spp-group' ); ?>
    <div id="ssp-tabs" class="inside">
			<ul>
				<li><a href="#ssp-general"><?php _e('Allgemeines', 'spp_lang' ); ?></a></li>
				<li><a href="#ssp-facebook"><?php _e('Facebook', 'spp_lang' ); ?></a></li>
				<li><a href="#ssp-twitter"><?php _e('Twitter', 'spp_lang' ); ?></a></li>
				<li><a href="#ssp-gplus"><?php _e('Google+', 'spp_lang' ); ?></a></li>
			</ul>    
      
      <div id="ssp-general">
        <?php _e('<p>Wenn Optionen leergelassen werden, wird der Standardwert aus dem Heise-Plugin verwendet.</p>', 'spp_lang'); ?>
        <table class="form-table" id="facebook">
    
          <tr valign="top">
            <th scope="row"><strong><?php _e('Info Link', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_info_link" size="100" value="<?php echo get_option('ssp_info_link', 'http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html');?>" />
              <br /><?php _e('Link zu detaillierter Datenschutz-Info. Standard Wert ist der Heise.de Artikel.', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Hilfetext', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_txt_help" rows="3" cols="89" ><?php echo get_option('ssp_txt_help', 'Wenn Sie diese Felder durch einen Klick aktivieren, werden Informationen an Facebook, Twitter oder Google in die USA übertragen und unter Umst&auml;nden auch dort gespeichert. N&auml;heres erfahren Sie durch einen Klick auf das.');?></textarea>
              <br /><?php _e('MouseOver-Text des <em>i</em>-Icons', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Dauerhaft Aktivieren', 'ssp_lang'); ?></strong></th>
            <td>
              <?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_settings_perma" value="on" <?php if ( get_option('ssp_settings_perma') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_settings_perma" value="off" <?php if ( get_option('ssp_settings_perma') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Denn Besuchern erlauben, die Social Media Dienste dauerhaft zu Aktivieren (mittels Cookie)', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Cookie-Domain', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_cookie_domain" size="100" value="<?php echo get_option('ssp_cookie_domain'); ?>" />
              <br /><?php _e('Domain für die das Cookie gültig ist, Standard m&auml;ssig sollte hier die Blog Domain genommen werden.', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Cookie-Path', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_cookie_path" size="100" value="<?php echo get_option('ssp_cookie_path'); ?>" />
              <br /><?php _e('Pfad der Gültigkeit des Cookies', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Cookie-Expire-Time', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_cookie_expire" size="100" value="<?php echo get_option('ssp_cookie_expire'); ?>" />
              <br /><?php _e('Dauer, die das Cookie gültig ist, in Tagen', 'ssp_lang'); ?></p>            
            </td>      
          </tr>
         
          </tr>
     
        </table>
      </div>
      
      <div id="ssp-facebook">

      <table class="form-table" id="facebook">
      
          <tr valign="top">
            <th scope="row"><strong><?php _e('Status', 'ssp_lang'); ?></strong></th>
            <td>
              <p><?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_fb_status" value="on" <?php if ( get_option('ssp_fb_status') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_fb_status" value="off" <?php if ( get_option('ssp_fb_status') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Dürfen Besucher die Inhalte über Facebook teilen?', 'ssp_lang'); ?></p>            
            </td>      
          </tr> 
          
          <tr valign="top">
            <th scope="row"><strong><?php _e('Grafik Pfad', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_fb_dummy_img" size="100" value="<?php echo get_option('ssp_fb_dummy_img', BASE_URL.'images/dummy_facebook.png'); ?>" />
              <br /><?php _e('Hier kannst du einen eigenen Pfad zur Anzeige Grafik eingeben.', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Info Text', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_fb_txt_info" rows="3" cols="89" ><?php echo get_option('ssp_fb_txt_info', '2 Klicks f&uuml;r mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie k&ouml;nnen Ihre Empfehlung an Facebook senden. Schon beim Aktivieren werden Daten an Dritte &uuml;bertragen &ndash; siehe <em>i</em>.'); ?></textarea>
              <br /><?php _e('MouseOver-Text des Empfehlen-Buttons', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>on</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_fb_txt_fb_on" rows="3" cols="89" ><?php echo get_option('ssp_fb_txt_fb_on', 'mit Facebook verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>off</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_fb_txt_fb_off" rows="3" cols="89" ><?php echo get_option('ssp_fb_txt_fb_off', 'nicht mit Facebook verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Dauerhaft Aktivieren', 'ssp_lang'); ?></strong></th>
            <td>
              <?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_fb_perma_option" value="on" <?php if ( get_option('ssp_fb_perma_option') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_fb_perma_option" value="off" <?php if ( get_option('ssp_fb_perma_option') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Denn Besuchern erlauben, Facebook dauerhaft zu Aktivieren (mittels Cookie)', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Anzeigename', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_fb_display_name" size="100" value="<?php echo get_option('ssp_fb_display_name', 'Facebook'); ?>" />
              <br /><?php _e('Schreibweise des Service in den Optionen, Standard Wert ist <tt>Facebook</tt>', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Referrer Track', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_fb_referrer_track" size="100" value="<?php echo get_option('ssp_fb_referrer_track') ?>" />
              <br /><?php _e('Wird ans Ende der URL gehängt, kann zum Tracken des Referrers genutzt werden', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Sprache', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_fb_language" size="100" value="<?php echo get_option('ssp_fb_language') ?>" />
              <br /><?php _e('Spracheinstellung, etwa "<tt>de_DE</tt>"', 'ssp_lang'); ?></p>            
            </td>      
          </tr>
          
          <tr valign="top">
            <th scope="row"><strong><?php _e('Beschriftung des Buttons', 'ssp_lang'); ?></strong></th>
            <td>
              <p>recommend <input type="radio" name="ssp_fb_action" value="recommend" <?php if ( get_option('ssp_fb_action') == recommend ) echo 'checked="checked"'; ?> /> 
              like <input type="radio" name="ssp_fb_action" value="like" <?php if ( get_option('ssp_fb_action') == like ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Beschriftung des Buttons: Empfehlen <code>(recommend)</code> oder Gefällt mir <code>(like)</code>', 'ssp_lang'); ?></p>            
            </td>           
              
      </table>
        
      </div>
      
      <div id="ssp-twitter">
      <table class="form-table" id="twitter">
      
          <tr valign="top">
            <th scope="row"><strong><?php _e('Status', 'ssp_lang'); ?></strong></th>
            <td>
              <p><?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_tw_status" value="on" <?php if ( get_option('ssp_tw_status') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_tw_status" value="off" <?php if ( get_option('ssp_tw_status') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Dürfen Besucher die Inhalte über Twitter teilen?', 'ssp_lang'); ?></p>            
            </td>      
          </tr> 

          <tr valign="top">
            <th scope="row"><strong><?php _e('Grafik Pfad', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_tw_dummy_img" size="100" value="<?php echo get_option('ssp_tw_dummy_img',  BASE_URL.'images/dummy_twitter.png'); ?>" />
              <br /><?php _e('Hier kannst du einen eigenen Pfad zur Anzeige Grafik eingeben.', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Info Text', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_tw_txt_info" rows="3" cols="89" ><?php echo get_option('ssp_tw_txt_info', '2 Klicks f&uuml;r mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie k&ouml;nnen Ihre Empfehlung an Twitter senden. Schon beim Aktivieren werden Daten an Dritte &uuml;bertragen &ndash; siehe <em>i</em>.'); ?></textarea>
              <br /><?php _e('MouseOver-Text des Empfehlen-Buttons', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>on</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_tw_txt_twitter_on" rows="3" cols="89" ><?php echo get_option('ssp_tw_txt_twitter_on', 'mit Twitter verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>off</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_tw_txt_twitter_off" rows="3" cols="89" ><?php echo get_option('ssp_tw_txt_twitter_off', 'nicht mit Twitter verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Dauerhaft Aktivieren', 'ssp_lang'); ?></strong></th>
            <td>
              <?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_tw_perma_option" value="on" <?php if ( get_option('ssp_tw_perma_option') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_tw_perma_option" value="off" <?php if ( get_option('ssp_tw_perma_option') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Denn Besuchern erlauben, Twitter dauerhaft zu Aktivieren (mittels Cookie)', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Anzeigename', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_tw_display_name" size="100" value="<?php echo get_option('ssp_tw_display_name', 'Twitter'); ?>" />
              <br /><?php _e('Schreibweise des Service in den Optionen, Standard Wert ist <tt>Twitter</tt>', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Referrer Track', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_tw_referrer_track" size="100" value="<?php echo get_option('ssp_tw_referrer_track') ?>" />
              <br /><?php _e('Wird ans Ende der URL gehängt, kann zum Tracken des Referrers genutzt werden', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Sprache', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_tw_language" size="100" value="<?php echo get_option('ssp_tw_language') ?>" />
              <br /><?php _e('Spracheinstellung, etwa "<tt>de_DE</tt>"', 'ssp_lang'); ?></p>            
            </td>      
          </tr>
              
      </table>    
      </div>
      
      <div id="ssp-gplus">
      <table class="form-table" id="gplus">
      
          <tr valign="top">
            <th scope="row"><strong><?php _e('Status', 'ssp_lang'); ?></strong></th>
            <td>
              <p><?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_gp_status" value="on" <?php if ( get_option('ssp_gp_status') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_gp_status" value="off" <?php if ( get_option('ssp_gp_status') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Dürfen Besucher die Inhalte über Google+ teilen?', 'ssp_lang'); ?></p>            
            </td>      
          </tr> 

          <tr valign="top">
            <th scope="row"><strong><?php _e('Grafik Pfad', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_gp_dummy_img" size="100" value="<?php echo get_option('ssp_gp_dummy_img', BASE_URL.'images/dummy_gplus.png'); ?>" />
              <br /><?php _e('Hier kannst du einen eigenen Pfad zur Anzeige Grafik eingeben.', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Info Text', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_gp_txt_info" rows="3" cols="89" ><?php echo get_option('ssp_tw_txt_info', '2 Klicks f&uuml;r mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie k&ouml;nnen Ihre Empfehlung an Google+ senden. Schon beim Aktivieren werden Daten an Dritte &uuml;bertragen &ndash; siehe <em>i</em>.'); ?></textarea>
              <br /><?php _e('MouseOver-Text des Empfehlen-Buttons', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>on</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_gp_txt_gplus_on" rows="3" cols="89" ><?php echo get_option('ssp_gp_txt_gplus_on', 'mit Google+ verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Statusmeldung <code>off</code>', 'ssp_lang'); ?></strong></th>
            <td>
              <p><textarea name="ssp_gp_txt_gplus_off" rows="3" cols="89" ><?php echo get_option('ssp_gp_txt_gplus_off', 'nicht mit Google+ verbunden'); ?></textarea>
              <br /><?php _e('Text-Entsprechung der Schalter-Grafik im ausgeschalteten Zustand, in der Regel nicht sichtbar für den User', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Dauerhaft Aktivieren', 'ssp_lang'); ?></strong></th>
            <td>
              <?php _e('Ja', 'ssp_lang'); ?> <input type="radio" name="ssp_gp_perma_option" value="on" <?php if ( get_option('ssp_gp_perma_option') == on ) echo 'checked="checked"'; ?> /> 
              <?php _e('Nein', 'ssp_lang'); ?> <input type="radio" name="ssp_gp_perma_option" value="off" <?php if ( get_option('ssp_gp_perma_option') == off ) echo 'checked="checked"'; ?> />
              <br /><?php _e('Denn Besuchern erlauben, Google+ dauerhaft zu Aktivieren (mittels Cookie)', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Anzeigename', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_gp_display_name" size="100" value="<?php echo get_option('ssp_gp_display_name', 'Google+');  ?>" />
              <br /><?php _e('Schreibweise des Service in den Optionen, Standard Wert ist <tt>Google+</tt>', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Referrer Track', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_gp_referrer_track" size="100" value="<?php echo get_option('ssp_gp_referrer_track') ?>" />
              <br /><?php _e('Wird ans Ende der URL gehängt, kann zum Tracken des Referrers genutzt werden', 'ssp_lang'); ?></p>            
            </td>      
          </tr>

          <tr valign="top">
            <th scope="row"><strong><?php _e('Sprache', 'ssp_lang'); ?></strong></th>
            <td>
              <p><input type="text" name="ssp_gp_language" size="100" value="<?php echo get_option('ssp_gp_language') ?>" />
              <br /><?php _e('Spracheinstellung, etwa "<tt>de</tt>"', 'ssp_lang'); ?></p>            
            </td>      
          </tr>
              
      </table>      
      </div>      
      
          
    </div>

      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>

    </form>

    <div id="poststuff" class="ui-sortable">
			<div class="postbox closed" >
				<h3><?php _e('Einstellungen deinstallieren', 'ssp_lang') ?></h3>
				<div class="inside">
					<form name="form2" method="post" action="options.php">
						<p><?php _e('Der Button inkl. aktive Checkbox l&ouml;scht alle Einstellungen des Plugins <em>WP Social Share Privacy</em>. Bitte nutze ihn, <strong>bevor</strong> das Plugin deaktiviert wird.<br /><strong>Achtung: </strong>Du kannst dies nicht r&uuml;ckg&auml;ngig machen!', 'ssp_lang' ); ?></p>
						<p id="submitbutton">
							<input type="submit" name="Submit_uninstall" value="<?php _e('Uninstall Options &raquo;', 'ssp_lang') ?>" class="button-secondary" />
							<input type="checkbox" name="deinstall_yes" value="  " />
							<input type="hidden" name="deinstall" value="uninstall" />
						</p>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		<!--
		<?php if ( version_compare( substr($wp_version, 0, 3), '2.7', '<' ) ) { ?>
		jQuery('.postbox h3').prepend('<a class="togbox">+</a> ');
		<?php } ?>
		jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
		jQuery('.postbox.close-me').each(function(){
			jQuery(this).addClass("closed");
		
		});	
		//-->
		</script>
</div>
<?php } 

// Output for Template


 
function socialshareprivacy_scripttag() { ?>
  <script type="text/javascript">
jQuery(document).ready(function($){
	if(jQuery('#social_bookmarks').length > 0){
		jQuery('#social_bookmarks').socialSharePrivacy({
		    'services' : {
          <?php if ( get_option('ssp_fb_status') == on ) { ?>
          'facebook' : {
            'status'            : 'on',
            'dummy_img'         : '<?php echo get_option('ssp_fb_dummy_img'); ?>',
            'txt_info'          : '<?php echo get_option('ssp_fb_txt_info'); ?>',
            'txt_fb_off'        : '<?php echo get_option('ssp_fb_txt_fb_off'); ?>',
            'txt_fb_on'         : '<?php echo get_option('ssp_fb_txt_fb_on'); ?>',
            'perma_option'      : '<?php echo get_option('ssp_fb_perma_option'); ?>',
            'display_name'      : '<?php echo get_option('ssp_fb_display_name'); ?>',
            'referrer_track'    : '<?php echo get_option('ssp_fb_referrer_track'); ?>',
            'language'          : '<?php echo get_option('ssp_fb_language'); ?>',
            'action'            : '<?php echo get_option('ssp_fb_action'); ?>'
          },
          <?php } else { ?>
          'facebook' : {
            'status'            : 'off',
          },
          <?php } ?>
          <?php if ( get_option('ssp_tw_status') == on ) { ?>
          'twitter' : {
            'status'            : 'on',
            'dummy_img'         : '<?php echo get_option('ssp_tw_dummy_img'); ?>',
            'txt_info'          : '<?php echo get_option('ssp_tw_txt_info'); ?>',
            'txt_twitter_off'   : '<?php echo get_option('ssp_tw_txt_twitter_off'); ?>',
            'txt_twitter_on'    : '<?php echo get_option('ssp_tw_txt_twitter_on'); ?>',
            'perma_option'      : '<?php echo get_option('ssp_tw_perma_option'); ?>',
            'display_name'      : '<?php echo get_option('ssp_tw_display_name'); ?>',
            'referrer_track'    : '<?php echo get_option('ssp_tw_referrer_track'); ?>',
            'language'          : '<?php echo get_option('ssp_tw_language'); ?>'
          },
          <?php } else { ?>
          'twitter' : {
            'status'            : 'off',
          },
          <?php } ?>
          <?php if ( get_option('ssp_gp_status') == on ) { ?>
          'gplus' : {
            'status'            : 'on',
            'dummy_img'         : '<?php echo get_option('ssp_gp_dummy_img'); ?>',
            'txt_info'          : '<?php echo get_option('ssp_gp_txt_info'); ?>',
            'txt_gplus_off'     : '<?php echo get_option('ssp_gp_txt_gplus_off'); ?>',
            'txt_gplus_on'      : '<?php echo get_option('ssp_gp_txt_gplus_on'); ?>',
            'perma_option'      : '<?php echo get_option('ssp_gp_perma_option'); ?>',
            'display_name'      : '<?php echo get_option('ssp_gp_display_name'); ?>',
            'referrer_track'    : '<?php echo get_option('ssp_gp_referrer_track'); ?>',
            'language'          : '<?php echo get_option('ssp_gp_language'); ?>'
          }
          <?php } else { ?>
          'gplus' : {
            'status'            : 'off',
          }
          <?php } ?>
          },
            'info_link'         : '<?php echo get_option('ssp_info_link'); ?>',
            'txt_help'          : '<?php echo get_option('ssp_txt_help'); ?>',
            'settings_perma'    : '<?php echo get_option('ssp_settings_perma'); ?>',
            'cookie_path'       : '<?php echo get_option('ssp_cookie_domain'); ?>',
            'cookie_domain'     : '<?php echo get_option('ssp_cookie_path'); ?>',
            'cookie_expires'    : '<?php echo get_option('ssp_cookie_expire'); ?>'
		}); 
	}
});
  </script>
<?php } add_action('wp_head', 'socialshareprivacy_scripttag'); 

/**
 * Print Stylesheet and jQuery Plugins
 */ 
function spp_styles() {
	wp_enqueue_style('wp-social-share-privacy-style',  BASE_URL.'wp-social-share-privacy.css');
}
 
function spp_scripts() {
	wp_enqueue_script('wp-social-share-privacy', BASE_URL.'js/jquery.socialshareprivacy.js', array('jquery'), '1.4.2');
}
function spp_admin_scripts() {
   wp_enqueue_script('jquery-ui-tabs');
}

function ssp_admin_header() {
  echo '
    <script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			//jQuery UI 1.5.2 doesn\'t expect tab Id\'s at DIV, so we have to apply a hotfix instead
			var needs_jquery_hotfix = (($.ui.version === undefined) || !$.ui.version.match(/^(1\.[7-9]|[2-9]\.)/));
			$("#ssp-tabs"+(needs_jquery_hotfix ? ">ul" : "")).tabs({
				selected: 0
			}); 			
		});
		//]]>
		</script>';
} 
add_action( 'admin_head', 'ssp_admin_header' );

function spp_admin_style() {
	echo '<link rel="stylesheet" href="'. BASE_URL.'ui.all.css" type="text/css" />';
}
if ( !is_admin() ) {
  add_action( 'wp_print_scripts', 'spp_scripts' );
  add_action( 'wp_print_styles', 'spp_styles' );
} else { 
  add_action( 'wp_print_scripts', 'spp_admin_scripts' );
  add_action( 'admin_head', 'spp_admin_style' );
}
  
function socialshareprivacy() {
    echo '<div id="social_bookmarks"></div>';
}
?>
