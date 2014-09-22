<?php
/**
 * File for the ShareaholicNotifier class.
 *
 * @package shareaholic
 */

/**
 * An interface to the publisher notification API
 *
 * @package shareaholic
 */
class ShareaholicNotifier {
  /**
   * The url of the publisher API
   */

  const URL = 'http://api.shareaholic.com/publisher/1.0';

  /**
   * Handles publishing or updating a post
   *
   * @param  string $post_id the post id
   * @return bool   whether the request worked
   */
  public static function post_notify($post_id) {
    global $wpdb;
    $post = get_post($post_id);
    $url = get_permalink($post_id);
    $tags = wp_get_post_tags($post_id, array('fields' => 'names'));

    $categories = array_map(array('ShareaholicNotifier', 'post_notify_iterator'), get_the_category($post_id));

    if (function_exists('has_post_thumbnail') && has_post_thumbnail($post_id)) {
      $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
    } else {
      $featured_image = ShareaholicUtilities::post_first_image();
      if (!$featured_image) {
        $featured_image = '';
      }
    }

    if ($post->post_author) {
      $author_data = get_userdata($post->post_author);
      $author_name = $author_data->display_name;
    }

    $notification = array(
      'url' => $url,
      'api_key' => ShareaholicUtilities::get_option('api_key'),
      'content' => array(
        'title' => $post->post_title,
        'excerpt' => $post->post_excerpt,
        'body' => $post->post_content,
        'featured-image-url' => $featured_image,
      ),
      'metadata' => array(
        'author-id' => $post->post_author,
        'author-name' => $author_name,
        'post-type' => $post->post_type,
        'post-id' => $post_id,
        'post-tags' => $tags,
        'post-categories' => $categories,
        'post-language' => get_bloginfo('language'),
        'published' => $post->post_date_gmt,
        'updated' => get_lastpostmodified('GMT'),
        'visibility' => $post->post_status,
      ),
      'diagnostics' => array(
        'platform' => 'wordpress',
        'platform-version' => get_bloginfo('version'),
        'shareaholic-version' => Shareaholic::VERSION,
        'wp-multisite' => is_multisite(),
        'wp-theme' => get_option('template'),
        'wp-posts-total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'post' AND post_status = 'publish'" ),
        'wp-pages-total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'page' AND post_status = 'publish'" ),
        'wp-comments-total' => wp_count_comments()->approved,
        'wp-users-total' => $wpdb->get_var("SELECT count(ID) FROM $wpdb->users"),
      ));
      
    return self::send_notification($notification);
  }

  /**
   * Because PHP < 5.3 doesn't allow anonymous functions, this
   * is the mapping function used in the method above.
   *
   * @param Category $category
   * @return string
   */
  private static function post_notify_iterator($category) {
    return $category->name;
  }

  /**
   * Actually sends the request to the notification API
   *
   * @param  array $notification an associative array of data
   *                             to send to the API
   * @return bool
   */
  private static function send_notification($notification) {
    $url = self::URL . '/notify';
    $response = ShareaholicCurl::post($url, $notification, 'json');

    if ($response && preg_match('/20*/', $response['response']['code'])) {
      return true;
    } else {
      return false;
    }
  }
}

?>