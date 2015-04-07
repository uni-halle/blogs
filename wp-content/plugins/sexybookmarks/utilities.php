<?php
/**
 * Holds the ShareaholicUtilities class.
 *
 * @package shareaholic
 */

require_once(SHAREAHOLIC_DIR . '/curl.php');
require_once(SHAREAHOLIC_DIR . '/six_to_seven.php');
require_once(SHAREAHOLIC_DIR . '/lib/social-share-counts/seq_share_count.php');

/**
 * This class is just a holder for general functions that have
 * no better place to be.
 *
 * @package shareaholic
 */
class ShareaholicUtilities {
  /**
   * Logs to the PHP error log if plugin's url is set to
   * spreadaholic or the SHAREAHOLIC_DEBUG constant is true.
   *
   * @param mixed $thing anything to be logged, it will be passed to `print_r`
   */
  public static function log($thing) {
    if (preg_match('/spreadaholic/', Shareaholic::URL) || SHAREAHOLIC_DEBUG) {
      error_log(print_r($thing, true));
    }
  }

  /**
   * Locate and require a template, and extract some variables
   * to be used in that template.
   *
   * @param string $template  the name of the template
   * @param array  $vars      any variables to be extracted into the template
   */
  public static function load_template($template, $vars = array()){
    // you cannot let locate_template to load your template
    // because WP devs made sure you can't pass
    // variables to your template :(

    $template_path = 'templates/' . $template . '.php';

    // load it
    extract($vars);
    require $template_path;
  }

  /**
   * Just a wrapper around get_option to
   * get the shareaholic settings. If the settings
   * have not been set it will return an array of defaults.
   *
   * @return array
   */
  public static function get_settings() {
    return get_option('shareaholic_settings', self::defaults());
  }

  /**
   * Destroys all settings except the acceptance
   * of the terms of service.
   *
   * @return bool
   */
  public static function destroy_settings() {
    delete_option('shareaholic_get_or_create_api_key');
    return delete_option('shareaholic_settings');
  }

  /**
   * Returns the defaults we want because PHP does not allow
   * arrays in class constants.
   *
   * @return array
   */
  private static function defaults() {
    return array(
      'disable_tracking' => 'off',
      'disable_admin_bar_menu' => 'off',
      'disable_debug_info' => 'off',
      'disable_internal_share_counts_api' => 'off',
      'api_key' => '',
      'verification_key' => '',
    );
  }

  /**
   * Returns links to add to the plugin options admin page
   *
   * @param  array $links
   * @return array
   */
  public static function admin_plugin_action_links($links) {
  	$links[] = '<a href="admin.php?page=shareaholic-settings">'.__('Settings', 'shareaholic').'</a>';
  	return $links;
  }

  /**
   * Extend the admin bar
   *
   */

   public static function admin_bar_extended() {
   	global $wp_admin_bar;

   	if(!current_user_can('update_plugins') || !is_admin_bar_showing() || self::get_option('disable_admin_bar_menu') == "on")
   		return;

   	$wp_admin_bar->add_menu(array(
   		'id' => 'wp_shareaholic_adminbar_menu',
   		'title' => __('Shareaholic', 'shareaholic'),
   		'href' => admin_url('admin.php?page=shareaholic-settings'),
   	));

   	/*
   	$wp_admin_bar->add_menu(array(
   		'parent' => 'wp_shareaholic_adminbar_menu',
   		'id' => 'wp_shareaholic_adminbar_submenu-analytics',
   		'title' => __('Social Analytics', 'shareaholic'),
   		'href' => 'https://shareaholic.com/publishers/analytics/'. ShareaholicUtilities::get_host(),
   		'meta' => Array( 'target' => '_blank' )
   	));
   	*/

   	$wp_admin_bar->add_menu(array(
   		'parent' => 'wp_shareaholic_adminbar_menu',
   		'id' => 'wp_shareaholic_adminbar_submenu-settings',
   		'title' => __('App Manager', 'shareaholic'),
   		'href' => admin_url('admin.php?page=shareaholic-settings'),
   	));

   	$wp_admin_bar->add_menu(array(
   		'parent' => 'wp_shareaholic_adminbar_menu',
   		'id' => 'wp_shareaholic_adminbar_submenu-general',
   		'title' => __('Website Settings', 'shareaholic'),
   		'href' => 'https://shareaholic.com/publisher_tools/'.self::get_option('api_key').'/verify?verification_key='.self::get_option('verification_key').'&redirect_to='.'https://shareaholic.com/publisher_tools/'.self::get_option('api_key').'/websites/edit?verification_key='.self::get_option('verification_key'),
   		'meta' => Array( 'target' => '_blank' )
   	));
   	$wp_admin_bar->add_menu(array(
   		'parent' => 'wp_shareaholic_adminbar_menu',
   		'id' => 'wp_shareaholic_adminbar_submenu-help',
   		'title' => __('FAQ & Support', 'shareaholic'),
   		'href' => 'http://support.shareaholic.com/',
   		'meta' => Array( 'target' => '_blank' )
   	));
   }

  /**
   * Returns whether the user has accepted our terms of service.
   *
   * @return bool
   */
  public static function has_accepted_terms_of_service() {
    return get_option('shareaholic_has_accepted_tos');
  }

  /**
   * Accepts the terms of service.
   */
  public static function accept_terms_of_service() {
    update_option('shareaholic_has_accepted_tos', true);

    ShareaholicUtilities::log_event("AcceptedToS");

    echo "{}";

    die();
  }

  /**
   * Wrapper for wordpress's get_option
   *
   * @param string $option
   *
   * @return mixed
   */
  public static function get_option($option) {
    $settings = self::get_settings();
    return (isset($settings[$option]) ? $settings[$option] : array());
  }

  /**
   * Wrapper for wordpress's update_option
   *
   * @param  array $array an array of options to update
   * @return bool
   */
  public static function update_options($array) {
    $old_settings = self::get_settings();
    $new_settings = self::array_merge_recursive_distinct($old_settings, $array);
    update_option('shareaholic_settings', $new_settings);
  }

  /**
   * Return the current version.
   *
   * @return string that looks like a number
   */
  public static function get_version() {
    return self::get_option('version') ? self::get_option('version') : get_option('SHRSBvNUM');
  }

  /**
   * Return host domain of WordPress install
   *
   * @return string
   */
  public static function get_host() {
    $parse = parse_url(get_bloginfo('url'));
    return $parse['host'];
  }

  /**
   * Set the current version, how simple.
   *
   * @param string $version the version you want to set
   */
  public static function set_version($version) {
    self::update_options(array('version' => $version));
  }

  /**
   * Determines if the first argument version is less than the second
   * argument version. A version can be up four levels, e.g. 1.1.1.1.
   * Any versions not supplied will be zeroed.
   *
   * @param  string $version
   * @param  string $comparer
   * @return bool
   */
  public static function version_less_than($version, $comparer) {
    $version_array = explode('.', $version);
    $comparer_array = explode('.', $comparer);

    for ($i = 0; $i <= 3; $i++) {
      // zero out unset numbers
      if (!isset($version_array[$i])) { $version_array[$i] = 0; }
      if (!isset($comparer_array[$i])) { $comparer_array[$i] = 0; }

      if ($version_array[$i] < $comparer_array[$i]) {
        return true;
      }

    }
    return false;
  }

  /**
   * Determines if the first argument version is less than or equal to the second
   * argument version. A version can be up four levels, e.g. 1.1.1.1.
   * Any versions not supplied will be zeroed.
   *
   * @param  string $version
   * @param  string $comparer
   * @return bool
   */
  public static function version_less_than_or_equal_to($version, $comparer) {
    $version_array = explode('.', $version);
    $comparer_array = explode('.', $comparer);

    if ($version == $comparer || self::version_less_than($version, $comparer)) {
      return true;
    }

    return false;
  }

  /**
   * Determines if the first argument version is greater than the second
   * argument version. A version can be up four levels, e.g. 1.1.1.1.
   * Any versions not supplied will be zeroed.
   *
   * @param  string $version
   * @param  string $comparer
   * @return bool
   */
  public static function version_greater_than($version, $comparer) {
    $version_array = explode('.', $version);
    $comparer_array = explode('.', $comparer);

    for ($i = 0; $i <= 3; $i++) {
      // zero out unset numbers
      if (!isset($version_array[$i])) { $version_array[$i] = 0; }
      if (!isset($comparer_array[$i])) { $comparer_array[$i] = 0; }

      if ($version_array[$i] > $comparer_array[$i]) {
        return true;
      } elseif ($version_array[$i] < $comparer_array[$i]) {
        return false;
      }

    }
    return false;
  }

  /**
   * Determines if the first argument version is greater than or equal to the second
   * argument version. A version can be up four levels, e.g. 1.1.1.1.
   * Any versions not supplied will be zeroed.
   *
   * @param  string $version
   * @param  string $comparer
   * @return bool
   */
  public static function version_greater_than_or_equal_to($version, $comparer) {
    $version_array = explode('.', $version);
    $comparer_array = explode('.', $comparer);

    if ($version == $comparer || self::version_greater_than($version, $comparer)) {
      return true;
    }

    return false;
  }

  /**
   * This is the function that will perform the update.
   */
  public static function perform_update() {
    if (self::get_version() && intval(self::get_version()) <= 6) {
      // an update so big, it gets it's own class!
      ShareaholicSixToSeven::update();
    }
    if (self::get_option('metakey_6to7_upgraded') != 'true') {
      global $wpdb;
      $results = $wpdb->query( "UPDATE $wpdb->postmeta SET `meta_key` = 'shareaholic_disable_open_graph_tags' WHERE `meta_key` = 'Hide OgTags'" );
      $results = $wpdb->query( "UPDATE $wpdb->postmeta SET `meta_key` = 'shareaholic_disable_share_buttons' WHERE `meta_key` = 'Hide SexyBookmarks'" );
      self::update_options(array('disable_tracking' => 'off'));
      self::update_options(array('metakey_6to7_upgraded' => 'true'));
    }
    
    $version = ShareaholicUtilities::get_version();
    if (!empty($version)){
      ShareaholicUtilities::clear_cache();
    }
    // any other things that need to be updated
  }

  /**
   * Return the type of page we're on as a string
   * to use for the location in the JS
   *
   * @return string
   */
  public static function page_type() {
    if (is_front_page() || is_home()) {
      return 'index';
    } elseif (is_page()) {
      return 'page';
    } elseif (is_single()) {
      return 'post';
    } elseif (is_category() || is_author() || is_tag() || is_date()) {
      return 'category';
    }
  }

  /**
   * Returns the appropriate asset path for environment
   *
   * @param string $asset
   * @return string
   */
  public static function asset_url($asset) {
    if (preg_match('/spreadaholic/', Shareaholic::URL)) {
      return "http://spreadaholic.com:8080/" . $asset;
    } elseif (preg_match('/stageaholic/', Shareaholic::URL)) {
      return '//d2062rwknz205x.cloudfront.net/' . $asset;
    } else {
      return '//dsms0mj1bbhn4.cloudfront.net/' . $asset;
    }
  }
  
  /**
   * Returns the appropriate asset path for environment - admin
   *
   * @param string $asset
   * @return string
   */
  public static function asset_url_admin($asset) {
    if (preg_match('/spreadaholic/', Shareaholic::URL)) {
      return "http://spreadaholic.com:8080/" . $asset;
    } elseif (preg_match('/stageaholic/', Shareaholic::URL)) {
      return 'https://d2062rwknz205x.cloudfront.net/' . $asset;
    } else {
      return 'https://dsms0mj1bbhn4.cloudfront.net/' . $asset;
    }
  }

  /**
   * Checks whether the api key has been verified
   * using the rails endpoint. Once the key has
   * been verified, we store that away so that we
   * don't have to check again.
   *
   * @return bool
   */
  public static function api_key_verified() {
    $settings = self::get_settings();
    if (isset($settings['api_key_verified']) && $settings['api_key_verified']) {
      return true;
    }

    $api_key = $settings['api_key'];
    if (!$api_key) {
      return false;
    }

    $response = ShareaholicCurl::get(Shareaholic::API_URL . '/publisher_tools/' . $api_key . '/verified');
    $result = $response['body'];

    if ($result == 'true') {
      ShareaholicUtilities::update_options(array(
        'api_key_verified' => true
      ));
    }
  }

  /**
   * A wrapper function to specificaly update the location name ids
   * because this is such a common function
   *
   * @todo Determine whether needed anymore
   *
   * @param array $array an array of location names to location ids
   * @return bool
   */
  public static function update_location_name_ids($array) {
    $settings = self::get_settings();
    $location_name_ids = (isset($settings['location_name_ids']) ? $settings['location_name_ids'] : array());
    $merge = array_merge($location_name_ids, $array);
    $settings['location_name_ids'] = $merge;

    update_option('shareaholic_settings', $settings);
  }

  /**
   *
   * Loads the locations names and their respective ids for an api key
   * and sets them in the shareaholic settings.'
   *
   * @param string $api_key
   */
  public static function get_new_location_name_ids($api_key) {
    $response = ShareaholicCurl::get(Shareaholic::API_URL . "/publisher_tools/{$api_key}.json");
    $publisher_configuration = $response['body'];
    $result = array();

    if ($publisher_configuration && is_array($publisher_configuration)) {
      foreach (array('share_buttons', 'recommendations') as $app) {
        foreach ($publisher_configuration['apps'][$app]['locations'] as $id => $location) {
          $result[$app][$location['name']] = $id;
        }
      }

      self::update_location_name_ids($result);
    } else {
      ShareaholicUtilities::load_template('failed_to_create_api_key_modal');
      ShareaholicUtilities::log_bad_response('FailedToCreateApiKey', $response);
    }
  }

  /**
   * A general function to underscore a CamelCased string.
   *
   * @param string $string
   * @return string
   */
  public static function underscore($string) {
    return strtolower(preg_replace('/([a-z])([A-Z])', '$1_$2', $string));
  }

  /**
   * Passed an array of location names mapped to ids per app.
   *
   * @param array $array
   */
  public static function turn_on_locations($array, $turn_off_array = array()) {

   if (is_array($array)) {
      foreach($array as $app => $ids) {
        if (is_array($ids)) {
          foreach($ids as $name => $id) {
            self::update_options(array(
              $app => array($name => 'on')
            ));
          }
        }
      }
    }

    if (is_array($turn_off_array)) {
      foreach($turn_off_array as $app => $ids) {
        if (is_array($ids)) {
          foreach($ids as $name => $id) {
            self::update_options(array(
              $app => array($name => 'off')
            ));
          }
        }
      }
    }
  }

  /**
   * Give back only the request keys from an array. The first
   * argument is the array to be sliced, and after that it can
   * either be a variable-length list of keys or one array of keys.
   *
   * @param  array $array
   * @param  Mixed ... can be either one array or many keys
   * @return array
   */
  public static function associative_array_slice($array) {
    $keys = array_slice(func_get_args(), 1);
    if (func_num_args() == 2 && is_array($keys[0])) {
      $keys = $keys[0];
    }

    $result = array();

    foreach($keys as $key) {
      $result[$key] = $array[$key];
    }

    return $result;
  }

  /**
   * Sets a lock (mutex)
   *
   * @param string $name
   */
  public static function set_lock($name) {
    update_option('shareaholic_' . $name, true);
  }

  /**
   * Checks if an action is locked.
   *
   * @param  string $name
   * @return bool
   */
  public static function is_locked($name) {
    return get_option('shareaholic_' . $name, false);
  }

  /**
   * Unlocks a mutex
   *
   * @param string $name
   */
  public static function unlock($name) {
    delete_option('shareaholic_' . $name);
  }

  /**
   * Checks whether a plugin is active
   *
   * @param string $name
   */
  public static function check_for_other_plugin() {
    if (is_plugin_active('shareaholic/shareaholic.php')) {
      deactivate_plugins('sexybookmarks/shareaholic.php');
    }
    if (is_plugin_active('shareaholic/sexy-bookmarks.php')) {
      deactivate_plugins('sexybookmarks/sexy-bookmarks.php');
    }
  }

  /**
   * Returns the api key or creates a new one.
   *
   * It first checks the database. If the key is not
   * found (or is an empty string or empty array or
   * anything that evaluates to false) then we will
   * attempt to make a new one by POSTing to the
   * anonymous configuration endpoint. That action
   * is wrapped in a mutex to keep two requests from
   * trying to create new api keys at the same time.
   *
   * @return string
   */
  public static function get_or_create_api_key() {
    $api_key = self::get_option('api_key');
    if ($api_key) {
      return $api_key;
    }

    if (!self::is_locked('get_or_create_api_key')) {
      self::set_lock('get_or_create_api_key');

      $old_settings = self::get_settings();

      delete_option('shareaholic_settings');

      // restore any old settings that should be preserved between resets
      if (isset($old_settings['share_counts_connect_check'])) {
        self::update_options(array(
          'share_counts_connect_check' => $old_settings['share_counts_connect_check'],
        ));
      }

      $verification_key = md5(mt_rand());

      $turned_on_share_buttons_locations = array(
        array('name' => 'post_below_content', 'counter' => 'badge-counter'),
        array('name' => 'page_below_content', 'counter' => 'badge-counter'),
        array('name' => 'index_below_content', 'counter' => 'badge-counter'),
        array('name' => 'category_below_content', 'counter' => 'badge-counter')
      );
      $turned_off_share_buttons_locations = array(
        array('name' => 'post_above_content', 'counter' => 'badge-counter'),
        array('name' => 'page_above_content', 'counter' => 'badge-counter'),
        array('name' => 'index_above_content', 'counter' => 'badge-counter'),
        array('name' => 'category_above_content', 'counter' => 'badge-counter')
      );

      $turned_on_recommendations_locations = array(
        array('name' => 'post_below_content'),
        array('name' => 'page_below_content'),
      );
      $turned_off_recommendations_locations = array(
        array('name' => 'index_below_content'),
        array('name' => 'category_below_content'),
      );

      $share_buttons_attributes = array_merge($turned_on_share_buttons_locations, $turned_off_share_buttons_locations);
      $recommendations_attributes = array_merge($turned_on_recommendations_locations, $turned_off_recommendations_locations);
      $data = array(
        'configuration_publisher' => array(
          'verification_key' => $verification_key,
          'site_name' => self::site_name(),
          'domain' => self::site_url(),
          'platform_id' => '12',
          'language_id' => self::site_language(),
          'shortener' => 'shrlc',
          'recommendations_attributes' => array(
            'locations_attributes' => $recommendations_attributes
          ),
          'share_buttons_attributes' => array(
            'locations_attributes' => $share_buttons_attributes
          )
        )
      );

      $response = ShareaholicCurl::post(
        Shareaholic::API_URL . '/publisher_tools/anonymous',
        $data,
        'json'
      );

      if ($response && preg_match('/20*/', $response['response']['code'])) {
        self::update_options(array(
          'api_key' => $response['body']['api_key'],
          'verification_key' => $verification_key,
          'location_name_ids' => $response['body']['location_name_ids']
        ));

        if (isset($response['body']['location_name_ids']) && is_array($response['body']['location_name_ids'])) {

          $turned_on_share_buttons_keys = array();
          foreach($turned_on_share_buttons_locations as $loc) {
            $turned_on_share_buttons_keys[] = $loc['name'];
          }

          $turned_on_recommendations_keys = array();
          foreach($turned_on_recommendations_locations as $loc) {
            $turned_on_recommendations_keys[] = $loc['name'];
          }

          $turned_off_share_buttons_keys = array();
          foreach($turned_off_share_buttons_locations as $loc) {
            $turned_off_share_buttons_keys[] = $loc['name'];
          }

          $turned_off_recommendations_keys = array();
          foreach($turned_off_recommendations_locations as $loc) {
            $turned_off_recommendations_keys[] = $loc['name'];
          }

          $turn_on = array(
            'share_buttons' => self::associative_array_slice($response['body']['location_name_ids']['share_buttons'], $turned_on_share_buttons_keys),
            'recommendations' => self::associative_array_slice($response['body']['location_name_ids']['recommendations'], $turned_on_recommendations_keys)
          );

          $turn_off = array(
            'share_buttons' => self::associative_array_slice($response['body']['location_name_ids']['share_buttons'], $turned_off_share_buttons_keys),
            'recommendations' => self::associative_array_slice($response['body']['location_name_ids']['recommendations'], $turned_off_recommendations_keys)
          );

          ShareaholicUtilities::turn_on_locations($turn_on, $turn_off);
          ShareaholicUtilities::clear_cache();
        } else {
          ShareaholicUtilities::log_bad_response('FailedToCreateApiKey', $response);
        }
      } else {
        add_action('admin_notices', array('ShareaholicAdmin', 'failed_to_create_api_key'));
        ShareaholicUtilities::log_bad_response('FailedToCreateApiKey', $response);
      }

      self::unlock('get_or_create_api_key');
    } else {
      usleep(100000);
      self::get_or_create_api_key();
    }
  }

  /**
   * Log reasons for a failure of a response.
   *
   * Checks if the code is not a 20*, the response body
   * is not an array, and whether the response object
   * was false. Sends the appropriate logging message.
   *
   * @param string $name     the name of the event to log
   * @param mixed  $response the response object
   */
  public static function log_bad_response($name, $response) {
    if ($response && is_array($response) && !preg_match('/20*/',$response['response']['code'])) {
      ShareaholicUtilities::log_event($name, array('reason' => 'the response was a ' . $response['response']['code']));
    } elseif ($response && !is_array($response)) {
      $thing = preg_replace('/\n/', '', var_export($response, true));
      ShareaholicUtilities::log_event($name, array('reason' => 'the publisher configuration was not an array, it was this ' . $thing));
    }
  }

  /**
   * Returns the site's url stripped of protocol.
   *
   * @return string
   */
  public static function site_url() {
    return preg_replace('/https?:\/\//', '', site_url());
  }

  /**
   * Returns the site's name
   *
   * @return string
   */
  public static function site_name() {
    return get_bloginfo('name') ? get_bloginfo('name') : site_url();
  }

  /**
   * Returns the site's primary locale / language
   *
   * @return string
   */
  public static function site_language() {
    $site_language = strtolower(get_bloginfo('language'));

    if (strpos($site_language, 'en-') !== false) {
      $language_id = 9; // English
    } elseif (strpos($site_language, 'da-') !== false) {
      $language_id = 7; // Danish
    } elseif (strpos($site_language, 'de-') !== false) {
      $language_id = 13; // German
    } elseif (strpos($site_language, 'es-') !== false) {
      $language_id = 31; // Spanish
    } elseif (strpos($site_language, 'fr-') !== false) {
      $language_id = 12; // French
    } elseif (strpos($site_language, 'pt-') !== false) {
      $language_id = 25; // Portuguese
    } elseif (strpos($site_language, 'it-') !== false) {
      $language_id = 18; // Italian
    } elseif (strpos($site_language, 'zh-cn') !== false) {
      $language_id = 3; // Chinese (Simplified)
    } elseif (strpos($site_language, 'zh-tw') !== false) {
      $language_id = 4; // Chinese (Traditional)
    } elseif (strpos($site_language, 'ja-') !== false) {
        $language_id = 19; // Japanese
    } elseif (strpos($site_language, 'ar-') !== false) {
        $language_id = 1; // Arabic
    } elseif (strpos($site_language, 'sv-') !== false) {
        $language_id = 32; // Swedish
    } elseif (strpos($site_language, 'tr-') !== false) {
        $language_id = 34; // Turkish
    } elseif (strpos($site_language, 'el-') !== false) {
      $language_id = 14; // Greek
    } elseif (strpos($site_language, 'nl-') !== false) {
      $language_id = 8; // Dutch
    } elseif (strpos($site_language, 'pl-') !== false) {
      $language_id = 24; // Polish
    } elseif (strpos($site_language, 'ru-') !== false) {
      $language_id = 27; // Russian
    } elseif (strpos($site_language, 'cs-') !== false) {
      $language_id = 6; // Czech
    } else {
      $language_id = NULL;
    }
    return $language_id;
  }

  /**
   * Shockingly the built in PHP array_merge_recursive function is stupid.
   * this is stolen from the PHP docs and will overwrite existing keys instead
   * of appending the values.
   *
   * http://www.php.net/manual/en/function.array-merge-recursive.php#92195
   *
   * @param  array $array1
   * @param  array $array2
   * @return array
   */
  public static function array_merge_recursive_distinct ( array &$array1, array &$array2 )
  {
    $merged = $array1;

    foreach ( $array2 as $key => &$value )
    {
      if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
      {
        if (empty($value)) {
          $merged[$key] = array();
        } else {
          $merged [$key] = self::array_merge_recursive_distinct ( $merged [$key], $value );
        }
      }
      else
      {
        $merged [$key] = $value;
      }
    }

    return $merged;
  }

  /**
   * Array casting an object is not recursive, this makes it recursive
   *
   * @param object $d
   *
   * http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
   */
  public static function object_to_array($d) {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }

    if (is_array($d)) {
      /*
      * Return array converted to object
      */
      return array_map(array('self', 'object_to_array'), $d);
    }
    else {
      // Return array
     return $d;
    }
  }

  /**
   * Answers whether we should ping CM
   *
   * @return bool
   */
  public static function should_notify_cm() {
    $settings = ShareaholicUtilities::get_settings();
    $recommendations_settings = isset($settings['recommendations']) ?
      $settings["recommendations"] :
      null;

    if (is_array($recommendations_settings)) {
      if (in_array("on", $recommendations_settings)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Wrapper for the Shareaholic Content Manager Single Page worker API
   *
   * @param string $post_id
   */
   public static function notify_content_manager_singlepage($post = NULL) {
     if ($post == NULL) {
       return false;
     }
     
     if (in_array($post->post_status, array('draft', 'pending', 'auto-draft'))) {
       // Get the correct permalink for a draft
       $my_post = clone $post;
       $my_post->post_status = 'published';
       $my_post->post_name = sanitize_title($my_post->post_name ? $my_post->post_name : $my_post->post_title, $my_post->ID);
       $post_permalink = get_permalink($my_post);
     } else {
       $post_permalink = get_permalink($post->ID);
     }
     
     if ($post_permalink != NULL) {
       $cm_single_page_job_url = Shareaholic::CM_API_URL . '/jobs/uber_single_page';
       $payload = array (
         'args' => array (
           $post_permalink,
           array ('force' => true)
          )
        );
      $response = ShareaholicCurl::post($cm_single_page_job_url, $payload, 'json');
     }
   }

   /**
    * Wrapper for the Shareaholic Content Manager Single Domain worker API
    *
    * @param string $domain
    */
    public static function notify_content_manager_sitemap() {      
      $text_sitemap_url = admin_url('admin-ajax.php') . '?action=shareaholic_permalink_list&n=500&format=text';
      
      $cm_sitemap_job_url = Shareaholic::CM_API_URL . '/jobs/sitemap';
      $payload = array (
        'args' => array (
          $text_sitemap_url,
          array ('force' => true)
        )
      );
      $response = ShareaholicCurl::post($cm_sitemap_job_url, $payload, 'json');
    }
    
   /**
    * Wrapper for the Shareaholic Content Manager Single Domain worker API
    *
    * @param string $domain
    */
    public static function notify_content_manager_singledomain($domain = NULL) {
      if ($domain == NULL) {
        $domain = get_bloginfo('url');
      }

      if ($domain != NULL) {
        $cm_single_domain_job_url = Shareaholic::CM_API_URL . '/jobs/single_domain';
        $payload = array (
          'args' => array (
            $domain,
            array ('force' => true)
           )
         );
       $response = ShareaholicCurl::post($cm_single_domain_job_url, $payload, 'json');
      }
    }

  /**
   * This is a wrapper for the Event API
   *
   * @param string $event_name    the name of the event
   * @param array  $extra_params  any extra data points to be included
   */
   public static function log_event($event_name = 'Default', $extra_params = false) {

     global $wpdb;

     $event_metadata = array(
  		'plugin_version' => Shareaholic::VERSION,
  		'api_key' => self::get_option('api_key'),
  		'domain' => get_bloginfo('url'),
  		'language' => get_bloginfo('language'),
  		'stats' => array (
  		  'posts_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'post' AND post_status = 'publish'" ),
  		  'pages_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'page' AND post_status = 'publish'" ),
  		  'comments_total' => wp_count_comments()->approved,
  		  'users_total' => $wpdb->get_var("SELECT count(ID) FROM $wpdb->users"),
	      ),
  		'diagnostics' => array (
  		  'php_version' => phpversion(),
  		  'wp_version' => get_bloginfo('version'),
  		  'theme' => get_option('template'),
  		  'active_plugins' => get_option('active_plugins', array()),
  		  'multisite' => is_multisite(),
  		  ),
  		 'features' => array (
  		    'share_buttons' => self::get_option('share_buttons'),
  		    'recommendations' => self::get_option('recommendations'),
  		  )
  	  );

  	 if ($extra_params) {
  	   $event_metadata = array_merge($event_metadata, $extra_params);
  	 }

  	$event_api_url = Shareaholic::API_URL . '/api/events';
  	$event_params = array('name' => "WordPress:".$event_name, 'data' => json_encode($event_metadata) );

    $response = ShareaholicCurl::post($event_api_url, $event_params, '', true);
  }

  /**
   * This loads the locales
   *
   */
  public static function localize() {
    load_plugin_textdomain('shareaholic', false, basename(dirname(__FILE__)) . '/languages/');
  }

  /*
   * Adds a xua response header
   *
   * @return array Where header => header value
   */
  public static function add_header_xua($headers) {
      if(!isset($headers['X-UA-Compatible'])) {
        $headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
      }
      return $headers;
  }

  /*
   * Draws xua meta tag
   *
   */
  public static function draw_meta_xua() {
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
  }
  
  /**
   * Function to return a list of permalink keywords
   *
   * @return list of keywords for the given permalink in an array
   */
  public static function permalink_keywords($post_id = NULL){
    global $post;
    $keywords = '';
        
    if ($post_id != NULL) {
      $id = $post_id;
    } else {
      $id = $post->ID;
    }
    
    // Get post tags
    $keywords = implode(', ', wp_get_post_tags( $id, array('fields' => 'names') ) );
    
    // Support for "All in One SEO Pack" plugin keywords
     if (get_post_meta($id, '_aioseop_keywords') != NULL){
       $keywords .= ', '.stripslashes(get_post_meta($id, '_aioseop_keywords', true));
     }
     
     // Support for "WordPress SEO by Yoast" plugin keywords
     if (get_post_meta($id, '_yoast_wpseo_focuskw') != NULL){
       $keywords .= ', '.stripslashes(get_post_meta($id, '_yoast_wpseo_focuskw', true));
     }
     
     if (get_post_meta($id, '_yoast_wpseo_metakeywords') != NULL){
       $keywords .= ', '.stripslashes(get_post_meta($id, '_yoast_wpseo_metakeywords', true));
     }
     
     // Support for "Add Meta Tags" plugin keywords
     if (get_post_meta($id, '_amt_keywords') != NULL){
       $keywords .= ', '.stripslashes(get_post_meta($id, '_amt_keywords', true));
     }

     if (get_post_meta($id, '_amt_news_keywords') != NULL){
       $keywords .= ', '.stripslashes(get_post_meta($id, '_amt_news_keywords', true));
     }
     
     // Encode, lowercase & trim appropriately
     $keywords = ShareaholicUtilities::normalize_keywords($keywords);
     
     // Unique keywords
     $keywords_array = array();
     $keywords_array = explode(', ', $keywords);
     $keywords_array = array_unique($keywords_array);

     if (empty($keywords_array[0])){
       return array();
     } else {
       return $keywords_array;
     }
  }
  
  /**
   * Normalizes and cleans up a list of comma separated keywords ie. encode, lowercase & trim appropriately
   *
   * @param string $keywords
   * @return string
   */
  public static function normalize_keywords($keywords) {
    return trim(trim(strtolower(trim(htmlspecialchars(htmlspecialchars_decode($keywords), ENT_QUOTES))), ","));
  }
  
  /**
   * Function to return a thumbnail for a given permalink
   *
   * @return thumbnail URL
   */
   public static function permalink_thumbnail($post_id = NULL, $size = "large"){
     $thumbnail_src = '';
     // Get Featured Image
     if (function_exists('has_post_thumbnail') && has_post_thumbnail($post_id)) {
       $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
       $thumbnail_src = esc_attr($thumbnail[0]);
     }
     // Get first image included in the post
     if ($thumbnail_src == NULL) {
       $thumbnail_src = ShareaholicUtilities::post_first_image();
     }
     if ($thumbnail_src == NULL){
       return NULL;
     } else {
       return $thumbnail_src;
     }
   }
  
   /**
    * This will grab the URL of the first image in a given post
    *
    * @return returns `false` or a string of the image src
    */
   public static function post_first_image() {
     global $post;
     $first_img = '';
     if ($post == NULL)
       return false;
     else {      
       $output = preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
       if(isset($matches[1][0]) ){
           $first_img = $matches[1][0];
       } else {
         return false;
       }
       return $first_img;
     }
   }
   
  /*
   * Clears cache created by caching plugins like W3 Total Cache
   *
   */
  public static function clear_cache() {
    // W3 Total Cache plugin
  	if (function_exists('w3tc_pgcache_flush')) {
  		w3tc_pgcache_flush(); 
  	}
  	// WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
      wp_cache_clear_cache();
    }
	  // Hyper Cache
	  if (function_exists('hyper_cache_invalidate')) {
	    hyper_cache_invalidate();
	  }
	  // Quick Cache
	  if (function_exists('auto_clear_cache')) {
  	  auto_clear_cache();
	  }
  }
  
  /**
   * A post just transitioned state. Do something.
   *
   */
  public static function post_transitioned($new_status, $old_status, $post) {
    $post_type = get_post_type($post);
    if ($new_status == 'publish' && $post_type != 'nav_menu_item' && $post_type != 'attachment') {
      // Post was just published
     ShareaholicUtilities::clear_fb_opengraph(get_permalink($post->ID));
     ShareaholicUtilities::notify_content_manager_singlepage($post);
    }
    if ($old_status == 'publish' && $new_status != 'publish') {
      // Notify CM that the post is no longer public
      ShareaholicUtilities::notify_content_manager_singlepage($post);
    }
  }
  
  /**
   * Clears Facebook Open Graph cache for provided URL
   *
   * @param string $url
   */
  public static function clear_fb_opengraph($url) {
    $fb_graph_url = "https://graph.facebook.com/?id=". urlencode($url) ."&scrape=true";
    $result = wp_remote_post ($fb_graph_url);
  }

  /**
   * Server Connectivity check
   *
   */
   public static function connectivity_check() {
  	$health_check_url = Shareaholic::API_URL . "/haproxy_health_check";
    $response = ShareaholicCurl::get($health_check_url);
    $body = $response['body'];
    if(is_array($response) && array_key_exists('body', $response)) {
      if ($body == "OK"){
        return "SUCCESS";
      } else {
        return "FAIL";
      }
    } else {
      return "FAIL";
    }
   }

  /**
   * Share Counts API Connectivity check
   *
   */
   public static function share_counts_api_connectivity_check() {

    // if we already checked and it is successful, then do not call the API again
    $share_counts_connect_check = self::get_option('share_counts_connect_check');
    if (isset($share_counts_connect_check) && $share_counts_connect_check == 'SUCCESS') {
      return $share_counts_connect_check;
    }
    
    $services_config = ShareaholicSeqShareCount::get_services_config();
    $services = array_keys($services_config);
    $param_string = implode('&services[]=', $services);
    $share_counts_api_url = admin_url('admin-ajax.php') . '?action=shareaholic_share_counts_api&url=https%3A%2F%2Fwww.google.com%2F&services[]=' . $param_string;
    $cache_key = 'share_counts_api_connectivity_check';
    
    $response = get_transient($cache_key);
    if (!$response) {
      $response = ShareaholicCurl::get($share_counts_api_url, array(), '', true);
    }

    $response_status = self::get_share_counts_api_status($response);
    // if this was the first time we are doing this and it failed, disable
    // the share counts API
    if (empty($share_counts_connect_check) && $response_status == 'FAIL') {
      self::update_options(array('disable_internal_share_counts_api' => 'on'));
    }

    if ($response_status == 'SUCCESS') {
      set_transient( $cache_key, $response, SHARE_COUNTS_CHECK_CACHE_LENGTH );
    }

    self::update_options(array('share_counts_connect_check' => $response_status));
    return $response_status;
   }

  /**
   * Check the share counts API for empty response or missing services
   */
  public static function get_share_counts_api_status($response) {
    if (!$response || !isset($response['body']) || !is_array($response['body']) || !isset($response['body']['data'])) {
      return 'FAIL';
    }

    // Did it return at least 8 services?
    $has_majority_services = count(array_keys($response['body']['data'])) >= 8 ? true : false;
    $has_important_services = true;
    // Does it have counts for twtr, linkedin, pinterest, and delicious?
    foreach (array('twitter', 'linkedin', 'pinterest', 'delicious') as $service) {
      if (!isset($response['body']['data'][$service]) || !is_numeric($response['body']['data'][$service])) {
        $has_important_services = false;
      }
    }

    if (!$has_majority_services || !$has_important_services) {
      return 'FAIL';
    }

    return 'SUCCESS';
  }

  /**
   * This is a wrapper for the Recommendations API
   *
   */
   public static function recommendations_status_check() {
    if (self::get_option('api_key') != NULL){
    	$recommendations_url = Shareaholic::REC_API_URL . "/v4/recommend?url=" . urlencode(get_bloginfo('url')) . "&internal=6&sponsored=0&api_key=" . self::get_option('api_key');
      $cache_key = 'recommendations_status_check-' . md5( $recommendations_url );
      
      $response = get_transient($cache_key);
      if (!$response){
        $response = ShareaholicCurl::get($recommendations_url);
        if( !is_wp_error( $response ) ) {
            set_transient( $cache_key, $response, RECOMMENDATIONS_STATUS_CHECK_CACHE_LENGTH );
        }
      }
      
      if(is_array($response) && array_key_exists('response', $response)) {
        $body = $response['body'];
        if (is_array($body) && array_key_exists('internal', $body) && !empty($body['internal'])) {
          return "ready";
        } else {
          return "processing";
        }
      } else {
        return "unknown";
      }
    }
   }

  public static function user_info() {
    $current_user = wp_get_current_user();

    if ( !($current_user instanceof WP_User) || !is_user_logged_in()) {
      return array();
    }

    $user_caps = $current_user->get_role_caps();

    $caps = array('switch_themes', 'edit_themes', 'activate_plugins',
      'edit_plugins', 'manage_options', 'unfiltered_html', 'edit_dashboard',
      'update_plugins', 'delete_plugins', 'install_plugins', 'update_themes',
      'install_themes', 'update_core', 'edit_theme_options', 'delete_themes',
      'administrator'
    );

    $user_info = array(
      'roles' => $current_user->roles,
      'capabilities' => array(),
      'is_super_admin' => is_super_admin()
    );

    foreach($caps as $cap) {
      $user_info['capabilities'][$cap] = isset($user_caps[$cap]) ? $user_caps[$cap] : '';
    }

    return $user_info;
  }
}
