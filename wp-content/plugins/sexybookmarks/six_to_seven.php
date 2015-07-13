<?php
/**
 * This file holds the ShareaholicSixToSeven class.
 *
 * @package shareaholic
 */

/**
 * This class is in charge or extracting the old style of configuration
 * from the wordpress database and turning it into a format that we
 * can POST back to shareaholic.com to create a new publisher configuration.
 *
 * @package shareaholic
 */
class ShareaholicSixToSeven {
  /**
   * The updating function. This should create a whole configuration
   * object including share buttons and recommendations as well as
   * site name and domain to send back to us.
   */
  public static function update() {
    $version = ShareaholicUtilities::get_version();
    $sexybookmarks_configuration = get_option('SexyBookmarks');
    $classicbookmarks_configuration = get_option('ShareaholicClassicBookmarks');
    $recommendations_configuration = get_option('ShareaholicRecommendations');
    $top_bar_configuration = get_option('ShareaholicTopBar');

    $new_share_buttons_configuration = self::transform_sexybookmarks_configuration($sexybookmarks_configuration);
    $new_classicbookmarks_locations = self::transform_classicbookmarks_locations($classicbookmarks_configuration, $sexybookmarks_configuration);
    $new_top_bar_configuration = self::transform_top_bar_configuration($top_bar_configuration);

    $location_names = array_map(array('self', 'grab_location_iterator'), $new_share_buttons_configuration['locations_attributes']);
    // if sexybookmarks are off or not on the bottom
    if ($sexybookmarks_configuration['sexybookmark'] != '1' ||
        !(bool)preg_grep('/below/', $location_names)) {
      // then merge in the classic bookmark locations
      $new_share_buttons_configuration = array_merge(
        $new_share_buttons_configuration,
        $new_classicbookmarks_locations
      );
    } elseif ($sexybookmarks_configuration['sexybookmark'] != '1' ||
              !(bool)preg_grep('/above/', $location_names)) {
      $new_share_buttons_configuration = array_merge(
        $new_share_buttons_configuration,
        $new_top_bar_configuration
      );
    }

    $new_recommendations_configuration = array(
      'locations_attributes' => self::transform_recommendations_configuration($recommendations_configuration)
    );

    $new_recommendations_configuration = isset($new_recommendations_configuration) ?
      $new_recommendations_configuration :
      null;

    $verification_key = md5(mt_rand());

    list($turned_on_share_buttons_location_names, $turned_off_share_buttons_location_names) = self::pad_locations($new_share_buttons_configuration);
    list($turned_on_recommendations_location_names, $turned_off_recommendations_location_names) = self::pad_locations($new_recommendations_configuration);

    $new_configuration = array(
      'configuration_publisher' => array(
        'share_buttons_attributes' => $new_share_buttons_configuration,
        'recommendations_attributes' => $new_recommendations_configuration,
        'site_name' => ShareaholicUtilities::site_name(),
        'domain' => ShareaholicUtilities::site_url(),
        'verification_key' => $verification_key,
        'platform_id' => '12',
        'language_id' => ShareaholicUtilities::site_language()
      ),
    );

    $shortener_configuration = (isset($sexybookmarks_configuration['shorty']) ?
      self::transform_shortener_configuration($sexybookmarks_configuration) : array());
    $new_configuration['configuration_publisher'] = array_merge($new_configuration['configuration_publisher'], $shortener_configuration);

    $response = ShareaholicCurl::post(Shareaholic::API_URL . '/publisher_tools/anonymous', $new_configuration, 'json');

    if ($response && preg_match('/20*/', $response['response']['code'])) {
      ShareaholicUtilities::log_event('6To7ConversionSuccess', array(
        'the_posted_json' => $new_configuration,
        'the_created_api_key' => $response['body']['api_key'],
        'SexyBookmarks' => $sexybookmarks_configuration,
        'ShareaholicClassicBookmarks' => $classicbookmarks_configuration,
        'ShareaholicRecommendations' => $recommendations_configuration
      ));

      ShareaholicUtilities::update_options(array(
        'api_key' => $response['body']['api_key'],
        'version' => Shareaholic::VERSION,
        'verification_key' => $verification_key,
        'location_name_ids' => $response['body']['location_name_ids']
      ));

      ShareaholicUtilities::turn_on_locations(
        array('share_buttons' => array_flip($turned_on_share_buttons_location_names), 'recommendations' => array_flip($turned_on_recommendations_location_names)),
        array('share_buttons' => array_flip($turned_off_share_buttons_location_names), 'recommendations' => array_flip($turned_off_recommendations_location_names))
      );

      self::transform_wordpress_specific_settings();
      self::cleanup_v6_options();
    } else {
      ShareaholicUtilities::log_event('6To7ConversionFailed', array(
        'the_posted_json' => $new_configuration,
        'SexyBookmarks' => $sexybookmarks_configuration,
        'ShareaholicClassicBookmarks' => $classicbookmarks_configuration,
        'ShareaholicRecommendations' => $recommendations_configuration
      ));
    }
  }

  private static function pad_locations(&$configuration) {
    $all_names = array();
    foreach (array('post', 'page', 'index', 'category') as $page_type) {
      foreach (array('above', 'below') as $location) {
        $all_names[] = "{$page_type}_{$location}_content";
      }
    }

    $already_set_names = array();
    foreach($configuration['locations_attributes'] as $attrs) {
      $already_set_names[] = $attrs['name'];
    }

    $names_to_pad = array_diff($all_names, $already_set_names);
    foreach($names_to_pad as $name) {
      $configuration['locations_attributes'][] = array('name' => $name);
    }

    return array(
      $already_set_names,
      $names_to_pad
    );
  }

  private static function grab_location_iterator($location) {
    return $location['name'];
  }

  /**
   * Munge the stored configuration for sexybookmarks
   * into the format we expect them on our side.
   *
   * @param  array $share_buttons_configuration the old wordpress configuration
   *                                            to be transformed
   * @return array
   */
  private static function transform_sexybookmarks_configuration($share_buttons_configuration) {
    $result = array();
    $headline_text = self::transform_headline_text_settings($share_buttons_configuration);
    $counter = $share_buttons_configuration['showShareCount'] == '1' ? 'badge-counter' : '';
    $alignment = $share_buttons_configuration['autocenter'] != '0' ? 'center-align' : '';

    if ($share_buttons_configuration['position'] != 'both') {
      $result[$share_buttons_configuration['position']] = array(
        'services' => self::services($share_buttons_configuration['bookmark']),
        'theme' => 'diglett',
        'counter' => $counter,
        'headline_text' => $headline_text,
        'alignment' => $alignment
      );
    } else {
      $result['above'] = array(
        'services' => self::services($share_buttons_configuration['bookmark']),
        'theme' => 'diglett',
        'counter' => $counter,
        'headline_text' => $headline_text,
        'alignment' => $alignment
      );
      $result['below'] = array(
        'services' => self::services($share_buttons_configuration['bookmark']),
        'theme' => 'diglett',
        'counter' => $counter,
        'headline_text' => $headline_text,
        'alignment' => $alignment
      );
    }


    if (!isset($result['above']) && $share_buttons_configuration['likeButtonSetTop']) {
      $result['above'] = array(
        'services' => self::like_button_set_services($share_buttons_configuration),
        'size' => 'rectangle',
        'counter' => 'top-counter',
        'headline_text' => ''
      );
    }

    if (!isset($result['below']) && $share_buttons_configuration['likeButtonSetBottom']) {
      $result['below'] = array(
        'services' => self::like_button_set_services($share_buttons_configuration),
        // theme candybar
        'size' => 'rectangle',
        'counter' => 'top-counter',
        'headline_text' => ''
      );
    }

    return array(
      'message_format' => urldecode($share_buttons_configuration['tweetconfig']),
      'locations_attributes' => self::set_page_types($result, $share_buttons_configuration['pageorpost'])
    );
  }

  /**
   * Translates settings for the 'top bar.' If it was not set to be on,
   * will return an empty array. Just because something is returned,
   * that does not mean it will be used. If the user already has something
   * for above content (from either sexy bookmarks or the like button
   * sets), this will not be used.
   *
   * @param  array $top_bar_configuration
   * @return array either empty or in the style of our new configurations
   */
  private static function transform_top_bar_configuration($top_bar_configuration) {
    $result = array(
      'services' => array()
    );

    if (!$top_bar_configuration['topbar']) {
      return array();
    }

    if ($top_bar_configuration['fbLikeButtonTop'] == '1' ||
        $top_bar_configuration['fbSendButtonTop'] == '1') {
      array_push($result['services'], 'facebook');
    }
    if ($top_bar_configuration['tweetButtonTop'] == '1') {
      array_push($result['services'], 'twitter');
    }
    if ($top_bar_configuration['googlePlusOneButtonTop'] == '1') {
      array_push($result['services'], 'google_plus');
    }

    // do some stuff for the themes!
    // there seems to be this thing `likeButtonSizeSetTop`
    // which can be one of [0,1,2], and that determines
    // the theme. the counters showing up is determined
    // by `likeButtonSetCountTop`
    switch ($top_bar_configuration['likeButtonSetSizeTop']) {
      case 0:
        $result['theme'] = '';
        $result['size'] = 'rectangle';
        $result['orientation'] = '';
        $result['counter'] = 'side-counter';
        break;
      case 1:
        $result['theme'] = '';
        $result['size'] = 'rectangle';
        $result['orientation'] = '';
        $result['counter'] = 'side-counter';
        break;
      case 2:
        $result['theme'] = '';
        $result['size'] = 'rectangle';
        $result['orientation'] = '';
        $result['counter'] = 'top-counter';
        break;
    }

    if (!$top_bar_configuration['likeButtonSetCountTop']) {
      $result['counter'] = '';
    }

    return self::set_page_types(array('above' => $result), $top_bar_configuration['pageorpost']);
  }

  /**
   * The old plugin could have a number of different shorteners enabled,
   * for which we just want to use shr.lc. Also some use different
   * names now.
   *
   * @param  array $share_buttons_configuration
   * @return array our new version of the shortener configurations, which
   *               will contain at least a key of 'shortener', but may also
   *               include 'shortener_api_key' and 'shortener_login'
   */
  private static function transform_shortener_configuration($share_buttons_configuration) {
    $shortener = isset($share_buttons_configuration['shorty']) ? $share_buttons_configuration['shorty'] : NULL;

    switch ($shortener) {
      case 'goog':
      case 'google':
        return array('shortener' => 'googl');
        break;
      case 'tiny':
        return array('shortener' => 'tinyurl');
        break;
      case 'shrlc':
      case 'shr.lc':
      case 'yourls':
      case 'tflp':
      case 'slly':
      case 'snip':
      case 'cligs':
      case 'tinyarrow':
      case 'b2l':
      case 'trim':
      case 'e7t':
      case 'awesm':
      case 'supr':
        return array('shortener' => 'shrlc');
        break;
      case '':
        return array('shortener' => 'shrlc');
        break;
      default:
        return array(
          'shortener' => $shortener ? $shortener : 'shrlc',
          'shortener_api_key' => (isset($share_buttons_configuration['shortyapi'][$shortener]['key'])
            ? $share_buttons_configuration['shortyapi'][$shortener]['key'] : ''),
          'shortener_login' => (isset($share_buttons_configuration['shortyapi'][$shortener]['user'])
            ? $share_buttons_configuration['shortyapi'][$shortener]['user'] : ''),
        );
    }
  }

  /**
   * The settings mungers only make an 'above' and a 'below' part,
   * not broken up by what page it should or not show up on.
   * This function checks $page_or_post for whether there should be
   * a location configuration for this page type and position.
   *
   * @param  array  $hash         the configuration
   * @param  string $page_or_post what types of pages are enabled
   * @return array
   */
  private static function set_page_types($hash, $page_or_post) {
    $result = array();
    foreach (array('post', 'page', 'index', 'category') as $place) {
      foreach (array('above', 'below') as $location) {
        if (isset($hash[$location]) && strpos($page_or_post, $place) !== false) {
          array_push($result, array_merge($hash[$location], array('name' => "{$place}_{$location}_content")));
        }
      }
    }

    return $result;
  }

  /**
   * Returns a configuration if the user was using classicbookmarks.
   *
   * @param  array $classicbookmarks_configuration
   * @param  array $share_buttons_configuration
   * @return array
   */
  private static function transform_classicbookmarks_locations($classicbookmarks_configuration, $share_buttons_configuration) {
    $result = array(
      'services' => array('facebook', 'twitter', 'email_this', 'pinterest', 'tumblr', 'google_plus', 'linkedin'),
    );

    if ($classicbookmarks_configuration['size'] == '16') {
      $result = array_merge($result, array(
        'theme' => '',
        'size' => 'mini',
        'orientation' => '',
        'counter' => '',
      ));
    } elseif ($classicbookmarks_configuration['size'] == '32') {
      $result = array_merge($result, array(
        'theme' => '',
        'size' => '',
        'orientation' => '',
        'counter' => ''
      ));
    }

    return array(
      'message_format' => urldecode($share_buttons_configuration['tweetconfig']),
      'locations_attributes' => self::set_page_types(array('below' => $result), $classicbookmarks_configuration['pageorpost'])
    );
  }

  /**
   * Munge the stored configuration for recommendations
   * into the format we expect them on our side.
   *
   * @param  array $recommendations_configuration
   * @return array
   */
  private static function transform_recommendations_configuration($recommendations_configuration) {
    $settings = array(
      'below' => array(
        'headline_text' => 'You may also like:',
        // if they requested text, honor that,
        // otherwise give them the default style
        'theme' => $recommendations_configuration['style'] == 'text' ? 'text-only' : ''
      )
    );

    $page_types = 'postpagecategory';

    if ($recommendations_configuration['recommendations'] == '1') {
      $page_types = $recommendations_configuration['pageorpost'] . 'postpagecategory';
    }

    return self::set_page_types($settings, $page_types);
  }

  /**
   * Iterates through the bookmark list of services, which is
   * a list of class names in the form of `shr-<service>`
   *
   * @param  array $services
   * @return array
   */
  private static function services($services) {
    return array_map(array('self', 'services_iterator'), $services);
  }

  /**
   * Because PHP < 5.3 doesn't support anonymous functions, this serves
   * as the mapping function for the above method.
   *
   * @param  string $value
   * @return string
   */
  private static function services_iterator($value) {
    if (preg_match('/googleplus/', $value)) {
      // it's stored as googleplus in wordpress, but
      // now we use google_plus
      return 'google_plus';
    }
    if (preg_match('/googlebookmarks/', $value)) {
      // it's stored as googlebookmarks in wordpress, but
      // now we use google_bookmarks
      return 'google_bookmarks';
    }
    if (preg_match('/scriptstyle/', $value)) {
      return 'script_and_style';
    }
    if (preg_match('/designfloat/', $value)) {
      return 'design_float';
    }
    if (preg_match('/misterwong/', $value)) {
      return 'mister_wong';
    }
    if (preg_match('/hackernews/', $value)) {
      return 'yc_hacker_news';
    }
    if (preg_match('/designbump/', $value)) {
      return 'design_bump';
    }
    if (preg_match('/globalgrind/', $value)) {
      return 'global_grind';
    }
    if (preg_match('/pingfm/', $value)) {
      return 'ping_fm';
    }
    if (preg_match('/webblend/', $value)) {
      return 'web_blend';
    }
    if (preg_match('/shr-box/', $value)) {
      return 'box_net';
    }
    if (preg_match('/plaxo/', $value)) {
      return 'plaxo_pulse';
    }
    if (preg_match('/gmail/', $value)) {
      return 'google_mail';
    }
    if (preg_match('/yahoomail/', $value)) {
      return 'yahoo_mail';
    }
    if (preg_match('/fastmail/', $value)) {
      return 'mail';
    }
    if (preg_match('/mail/', $value)) {
      // it's stored as mail in wordpress, but
      // now we use email_this
      return 'email_this';
    }
    // return without the 'shr-'
    return str_replace('shr-', '', $value);
  }

  /**
   * For users who had enabled the 'like button set' thing,
   * we will turn them into the candy bar style buttons.
   *
   * @param  array $share_buttons_configuration
   * @return array
   */
  private static function like_button_set_services($share_buttons_configuration) {
    if ($share_buttons_configuration['position'] == 'above') {
      $position = 'Bottom';
    } else {
      $position = 'Top';
    }

    $result = array();
    if ($share_buttons_configuration['fbLikeButton' . $position] == '1' ||
        $share_buttons_configuration['fbSendButton' . $position] == '1') {
      array_push($result, 'facebook');
    }
    if ($share_buttons_configuration['tweetButton' . $position] == '1') {
      array_push($result, 'twitter');
    }
    if ($share_buttons_configuration['googlePlusOneButton' . $position] == '1') {
      array_push($result, 'google_plus');
    }

    return $result;
  }
  
  /**
   * This function is for all settings that are specific to wordpress
   * and are not stored in a publisher configuration object. So far
   * this only inclues disabling the tracking.
   */
  private static function transform_wordpress_specific_settings() {
    $new_shareaholic_settings = array();
    $analytics_settings = get_option('ShareaholicAnalytics');
    $sexybookmarks_settings = get_option('SexyBookmarks');

    $new_shareaholic_settings['disable_og_tags'] = $sexybookmarks_settings['ogtags'] == '0' ? 'on' : 'off';

    ShareaholicUtilities::update_options($new_shareaholic_settings);
  }
  
  /**
   * This function cleans up options created by <= v6 of the plugin 
   *
   */
  private static function cleanup_v6_options() {
    delete_option('SexyBookmarks');
    delete_option('ShareaholicTopbar');
    delete_option('ShareaholicAnalytics');
    delete_option('ShareaholicRecommendations');
    delete_option('ShareaholicClassicBookmarks');
    delete_option('shr_reportupgrade');
    delete_option('SHRSBvNum');
    delete_option('SHRSB_apikey');
    delete_option('SEXY_SPONSORS');
    delete_option('SHRSB_DefaultSprite');
    delete_option('SHRSB_CustomSprite');
    delete_option('SexyCustomSprite');
  }

  /**
   * Transform headline text based on background image
   *
   */
  private static function transform_headline_text_settings($sexybookmarks_configuration) {
    $headline_text = '';

    if ($sexybookmarks_configuration['bgimg-yes'] != 'yes') {
      return $headline_text;
    }

    switch ($sexybookmarks_configuration['bgimg']) {
      case 'shr':
        $headline_text = 'Sharing is sexy!';
        break;
      case 'caring':
        $headline_text = 'Sharing is caring.';
        break;
      case 'love':
        $headline_text = 'Share the love!';
        break;
      case 'wealth':
        $headline_text = 'Share the wealth!';
        break;
      case 'enjoy':
        $headline_text = 'Share and enjoy!';
        break;
      case 'knowledge':
        $headline_text = 'Share the knowledge!';
        break;
      case 'german':
      case 'care-old':
      default:
        $headline_text = 'Sharing is caring!';
    }
    return $headline_text;
  }
}

?>