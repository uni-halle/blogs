<?php
/**
 * This will hold all of the global namespaced functions.
 *
 * @package shareaholic
 */

/**
 * The old 'shortcode' function, which wasn't a real
 * WordPress shortcode. This is currently deprecated so it
 * logs that fact.
 *
 * @deprecated beginning with the release of 7.0.0.0
 */
 
 if (!function_exists('selfserv_shareaholic')) {
   function selfserv_shareaholic() {
     $trace = debug_backtrace();
     $deprecation = new ShareaholicDeprecation('selfserv_shareaholic');
     $deprecation->push($trace[0]['file'], $trace[0]['line']);
     echo ShareaholicPublic::canvas(NULL, 'share_buttons');
   }
}

/**
 * Another old 'shortcode' function. Because this accepts a position
 * (either 'Top' or 'Bottom') it requres a little more finessing in
 * its implementation.
 *
 * @param string $position either 'Top' or 'Bottom'
 */

 if (!function_exists('get_shr_like_buttonset')) { 
    function get_shr_like_buttonset($position) {
      $trace = debug_backtrace();
      $deprecation = new ShareaholicDeprecation('get_shr_like_buttonset');
      $deprecation->push($trace[0]['file'], $trace[0]['line']);

      $settings = ShareaholicUtilities::get_settings();
      $page_type = ShareaholicUtilities::page_type();

      switch ($position) {
        case 'Top':
          $id = isset($settings['location_name_ids']["{$page_type}_above_content"])
            ? $settings['location_name_ids']["{$page_type}_above_content"] : NULL;
          break;
        case 'Bottom':
          $id = isset($settings['location_name_ids']["{$page_type}_below_content"])
            ? $settings['location_name_ids']["{$page_type}_below_content"] : NULL;
          break;
      }

      echo ShareaholicPublic::canvas($id, 'share_buttons');
    }
  }

?>