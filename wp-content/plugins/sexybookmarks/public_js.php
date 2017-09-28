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
   * Return a base set of settings for the Shareaholic JS or Publisher SDK
   */
  public static function get_base_settings() {
    $base_settings = array(
      'endpoints' => array(
        'local_recs_url' => admin_url('admin-ajax.php') . '?action=shareaholic_permalink_related'
      )
    );
    
    $disable_share_counts_api = ShareaholicUtilities::get_option('disable_internal_share_counts_api');
    $share_counts_connect_check = ShareaholicUtilities::get_option('share_counts_connect_check');

    if (isset($disable_share_counts_api)) {
      if (isset($share_counts_connect_check) && $share_counts_connect_check == 'SUCCESS' && $disable_share_counts_api != 'on') {
        $base_settings['endpoints']['share_counts_url'] = admin_url('admin-ajax.php') . '?action=shareaholic_share_counts_api';
      }
    }
    
    // Used by Share Count Recovery feature
    if (is_singular()) {
      global $post;
      $author_id = $post->post_author;
      
      $base_settings['url_components']['year'] = date('Y', strtotime($post->post_date));
      $base_settings['url_components']['monthnum'] = date('m', strtotime($post->post_date));
      $base_settings['url_components']['day'] = date('d', strtotime($post->post_date));
      $base_settings['url_components']['post_id'] = "$post->ID";
      $base_settings['url_components']['postname'] = $post->post_name;
      $base_settings['url_components']['author'] = get_the_author_meta('user_nicename', $author_id);
    }
    
    return $base_settings;
  }

  public static function get_overrides() {
    $output = '';

    if (ShareaholicUtilities::get_env() === 'staging') {
      $output = "data-shr-environment='stage' data-shr-assetbase='//cdn-staging-shareaholic.s3.amazonaws.com/v2/'";
    }

    return $output;
   }

}
