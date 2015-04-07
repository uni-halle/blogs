<?php
/**
 * Holds the ShareaholicPublicJS class.
 *
 * @package shareaholic
 */

/**
 * This class gets the necessary components ready
 * for rendering the shareaholic js code for the template
 *
 * @package shareaholic
 */
class ShareaholicPublicJS {

  /**
   * Gets the page configuration to be used by shareaholic.js
   *
   * This function returns a string representation of the page config object
   * which will be consumed by the shareaholic javascript.
   *
   * @return string The stringified version of the page config
   */
  public static function get_page_config() {
    $config = self::get_base_settings();
    $config['apps'] = array();
    $functions_map = self::get_function_definitions();
    $share_buttons = self::get_share_buttons_config();

    // if all the configurations are empty, return an empty JS object
    if(empty($share_buttons)) {
      return '{}';
    }

    $config['apps']['share_buttons'] = $share_buttons;

    // Get the json representation of the page configuration
    $json_string = json_encode($config);

    // Now iterate through the function mapping and do a string replace
    foreach($functions_map as $placeholder => $implementation) {
      $json_string = str_replace('"' . $placeholder . '"', $implementation, $json_string);
    }
    return $json_string;
  }


  /**
   * Return a base set of settings for the Shareaholic JS or Publisher SDK
   */
  public static function get_base_settings() {
    return array(
      'endpoints' => array(
        'local_recs_url' => admin_url('admin-ajax.php') . '?action=shareaholic_permalink_related',
        'share_counts_url' => admin_url('admin-ajax.php') . '?action=shareaholic_share_counts_api'
      )
    );
  }

  /**
   * Get the share_buttons configuration to be used by Shareaholic.js
   *
   * This function returns an object for the share buttons configuration
   * which will be consumed by Shareaholic.js
   *
   * @return array an associative array of configuration for share buttons
   */
  public static function get_share_buttons_config() {
    $share_buttons = array();
    $disable_share_counts_api = ShareaholicUtilities::get_option('disable_internal_share_counts_api');
    $share_counts_connect_check = ShareaholicUtilities::get_option('share_counts_connect_check');
    
    if (isset($disable_share_counts_api)) {
      if (isset($share_counts_connect_check) && $share_counts_connect_check == 'SUCCESS' && $disable_share_counts_api != 'on') {
        $share_buttons['get_share_counts'] = '%get_share_counts%';
      }
    }
    return $share_buttons;
  }

  /**
   * Get the mapping of function placeholder to function implementation
   *
   * This function will return a mapping of a function placeholder to
   * the function implementation. This is necessary so that we can send
   * functions along to the JS side since json_encode cannot encode functions
   *
   * @return array an associative array of function placeholder to function implementation
   */
  public static function get_function_definitions() {
    return array(
      '%get_share_counts%' => self::get_share_counts_function(),
    );
  }

  /**
   * Get the share counts functions as a string
   *
   * @return string the stringified version of get_share_counts function
   */
  public static function get_share_counts_function() {
    $ajax_url = admin_url('admin-ajax.php');
    $share_counts_function = <<<DOC
  function(url, services, cb) {
    Shareaholic.Utils.ajax({
      cache: true,
      cache_ttl: '1 minute',
      url: '$ajax_url',
      data: { action: 'shareaholic_share_counts_api', url: url, services: services },
      success: function(res) {
        if(res && res.data) {
          cb(res.data, true);
        }
      }
    })
  }
DOC;
    return $share_counts_function;
  }
}
