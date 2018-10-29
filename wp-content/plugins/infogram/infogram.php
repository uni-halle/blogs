<?php
/*
  Plugin Name: Infogr.am
  Plugin URI: https://infogram.com/blog/new-infogram-wordpress-plugin/
  Description: It allows you to insert graphics from the site infogram.com
  Version: 1.5.7
  Text Domain: infogram
  Tags: infogram, shortcode, iframe, insert, rest api, json
*/

// Add setings page and register settings
add_action('admin_menu', 'infogr_add_pages');
add_action('wp_ajax_infogram_dialog', 'infogr_ajax_dialog');

function infogr_ajax_dialog() {
  global $infogram;
  ($infogram->check_is_valid()) ? infogr_add_media_popup() : infogr_message_popup();

  wp_die();
}

function infogr_add_pages() {
  //create new top-level menu
  add_options_page('Infogram.com v1.5.7', 'Infogram settings', 'level_0', 'infogram', 'infogr_page');

  //call register settings function
  add_action('admin_init', 'register_infogr_settings');
}

function register_infogr_settings() {
  //register our settings
  register_setting('my-infogr-settings', 'infogr_api_key');
  register_setting('my-infogr-settings', 'infogr_api_secret');
  register_setting('my-infogr-settings', 'infogr_username');
}

function infogr_page() {
  global $infogram;
?>
  <div class="wrap">
    <h2>Infogram</h2>
    <?php $infogram->plugin_status(); ?>
    <form method="post" action="options.php">
      <?php settings_fields('my-infogr-settings'); ?>
      <?php do_settings_sections('my-infogr-settings'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php _e('Your Api key:', 'infogram'); ?></th>
          <td><input type="text" name="infogr_api_key" size="40" value="<?php echo esc_attr( get_option('infogr_api_key') ); ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Your Api secret:', 'infogram'); ?></th>
          <td><input type="text" name="infogr_api_secret" size="40" value="<?php echo esc_attr( get_option('infogr_api_secret') ); ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('User name:', 'infogram'); ?></th>
          <td><input type="text" name="infogr_username" value="<?php echo esc_attr( get_option('infogr_username') ); ?>" /></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
<?php
}

function infogr_create_object() {
  // Load Api config file
  require_once('core/autoload.php');
  // Load main Infogram class
  require_once('class/class-infogram.php');
  // Load media button function
  require_once('button/add_button.php');

  global $infogram;

  $options = array(
    'api_key' => get_option('infogr_api_key'),
    'api_secret' => get_option('infogr_api_secret'),
    'username' => get_option('infogr_username')
  );

  if ( !$infogram ) {
    $infogram = new Infogram($options);
  }
}

// out infographic
function infogr_add_infographics($atts) {
  $atts = shortcode_atts(array(
    'id' => '',
    'prefix' => '',
    'format' => ''
  ), $atts, 'id');

  $format = 'interactive';

  if($atts['id']) {
    if($atts['format'] && $atts['format'] == 'image') {
      $format = 'image';
    }

    return '<div class="infogram-embed" data-id="'.$atts['id'].'" data-type="'.$format.'"></div><script>!function(e,t,s,i){var n="InfogramEmbeds",o=e.getElementsByTagName("script"),d=o[0],r=/^http:/.test(e.location)?"http:":"https:";if(/^\/{2}/.test(i)&&(i=r+i),window[n]&&window[n].initialized)window[n].process&&window[n].process();else if(!e.getElementById(s)){var a=e.createElement("script");a.async=1,a.id=s,a.src=i,d.parentNode.insertBefore(a,d)}}(document,0,"infogram-async","//e.infogram.com/js/dist/embed-loader-min.js");</script>';
  } else {
    return 'This code is broken or not exists!';
  }
}

add_shortcode('infogram', 'infogr_add_infographics');

// Global Infogram activation hook
function infogr_handle_activation() {}

// Global Infogram deactivation hook
function infogr_handle_deactivation() {}

// Activation hooks for some basic initialization
register_activation_hook(__FILE__, 'infogr_handle_activation');
register_deactivation_hook(__FILE__, 'infogr_handle_deactivation');

// Main Infogram activation hook
if ( is_admin() ) {
  add_action('plugins_loaded', 'infogr_create_object');
};
