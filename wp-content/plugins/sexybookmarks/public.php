<?php
/**
 * Holds the ShareaholicPublic class.
 *
 * @package shareaholic
 */

// Get the required libraries for the Share Counts API
require_once(SHAREAHOLIC_DIR . '/lib/social-share-counts/wordpress_http.php');
require_once(SHAREAHOLIC_DIR . '/lib/social-share-counts/seq_share_count.php');
require_once(SHAREAHOLIC_DIR . '/lib/social-share-counts/curl_multi_share_count.php');
require_once(SHAREAHOLIC_DIR . '/public_js.php');

/**
 * This class is all about drawing the stuff in publishers'
 * templates that visitors can see.
 *
 * @package shareaholic
 */
class ShareaholicPublic {

  /**
   * Loads before all else
   */
  public static function init() {
    add_filter('wp_headers', array('ShareaholicUtilities', 'add_header_xua'));
  }

  /**
   * Loads before all else
   */
  public static function after_setup_theme() {
    // Ensure thumbnail/featured image support
    if(!current_theme_supports('post-thumbnails')){
      add_theme_support('post-thumbnails');
    }
		
    // Adds support for shortcodes in sidebar text widgets
    if (!has_filter('widget_text', 'do_shortcode')){
      add_filter('widget_text', 'do_shortcode', 11);
    }
		
    add_image_size('shareaholic-thumbnail', 300); // 300 pixels wide (and unlimited height)
  }
	
  /**
   * The function called during the wp_head action. The
   * rest of the plugin doesn't need to know exactly what happens.
  */
  public static function wp_head() {
    // this will only run on pages that would actually call
    // the deprecated functions. For some reason I could not
    // get this function to run using a hook, though that
    // should not discourage anyone in the future. -DG
    ShareaholicDeprecation::destroy_all();
    self::script_tag();
    self::shareaholic_tags();
    self::draw_og_tags();
  }  

  /**
   * Inserts the script code snippet into the head of the page
   */
  public static function script_tag() {
    if (ShareaholicUtilities::has_accepted_terms_of_service() &&
        ShareaholicUtilities::get_or_create_api_key()) {
      ShareaholicUtilities::load_template('script_tag', array(
        'api_key' => ShareaholicUtilities::get_option('api_key'),
        'base_settings' => ShareaholicPublicJS::get_base_settings(),
        'overrides' => ShareaholicPublicJS::get_overrides()
      ));
    }
  }

  /**
   * The function that gets called for shortcodes
   *
   * @param array $attributes this is passed keys: `id`, `app`, `title`, `link`, `summary`
   * @param string $content is the enclosed content (if the shortcode is used in its enclosing form)
   */
  public static function shortcode($attributes, $content = NULL) {
    extract(shortcode_atts(array(
      "id" => NULL,
      "app" => 'share_buttons',
      "title" => NULL,
      "link" => NULL,
      "summary" => NULL
    ), $attributes, 'shareaholic'));
    
    if (isset($attributes['title'])) $title = esc_attr(trim($attributes['title']));  
    if (isset($attributes['link'])) $link = trim($attributes['link']);
    if (isset($attributes['summary'])) $summary = esc_attr(trim($attributes['summary']));  
    
    return self::canvas($attributes['id'], $attributes['app'], $title, $link, $summary);
  }
  
  
  /**
   * Draws the shareaholic meta tags.
   */
  private static function shareaholic_tags() {
    echo "\n<!-- Shareaholic Content Tags -->\n";
    self::draw_site_name_meta_tag();
    self::draw_language_meta_tag();
    self::draw_url_meta_tag();
    self::draw_keywords_meta_tag();
    self::draw_article_meta_tag();
    self::draw_site_id_meta_tag();
    self::draw_plugin_version_meta_tag();
    self::draw_image_meta_tag();
    echo "\n<!-- Shareaholic Content Tags End -->\n";
  }

  /**
   * Draws Shareaholic keywords meta tag.
   */
  private static function draw_keywords_meta_tag() {
    if (in_array(ShareaholicUtilities::page_type(), array('page', 'post'))) {
      global $post;
      $keywords = '';
      
      if (is_attachment() && $post->post_parent){
        $id = $post->post_parent;
      } else {
        $id = $post->ID;
      }
      
      // Get post tags
      $keywords = implode(', ' , ShareaholicUtilities::permalink_keywords($id));
             
      // Get post categories
      $categories_array = get_the_category($id);
      $categories = '';
      $separator = ', ';
      $output = '';
      
      if($categories_array) {
      	foreach($categories_array as $category) {
      	  if ($category->cat_name != "Uncategorized") {
      		  $output .= $separator.$category->cat_name;
    		  }
      	}
       $categories = trim($output, $separator);
      }      
      
      // Merge post tags and categories
      if ($keywords != ''){
        $keywords .= ', '.$categories;
      } else {
        $keywords .= $categories;
      }
            
      // Encode, lowercase & trim appropriately
      $keywords = ShareaholicUtilities::normalize_keywords($keywords);
      
      // Unique keywords
      $keywords_array = array();
      $keywords_array = explode(', ', $keywords);
      $keywords_array = array_unique($keywords_array);      
      $keywords_unique_list = implode(', ', $keywords_array);
      
      if ($keywords_unique_list != '' && $keywords_unique_list != "array") {
        echo "<meta name='shareaholic:keywords' content='" .  $keywords_unique_list . "' />\n";
      }
    }
  }
  
  /**
   * Draws Shareaholic article meta tags
   */
  private static function draw_article_meta_tag() {
    if (in_array(ShareaholicUtilities::page_type(), array('page', 'post'))) {
      global $post;
    
      // Article Publish and Modified Time
      $article_published_time = strtotime($post->post_date_gmt);
      $article_modified_time = strtotime(get_lastpostmodified('GMT'));
    
      if (!empty($article_published_time)) {
        echo "<meta name='shareaholic:article_published_time' content='" . date('c', $article_published_time) . "' />\n";
      }
      if (!empty($article_modified_time)) {
        echo "<meta name='shareaholic:article_modified_time' content='" . date('c', $article_modified_time) . "' />\n";
      }
      
      // Article Visibility
      $article_visibility = $post->post_status;
      $article_password = $post->post_password;

      if ($article_visibility == 'draft' || $article_visibility == 'auto-draft' || $article_visibility == 'future' || $article_visibility == 'pending'){
        echo "<meta name='shareaholic:shareable_page' content='false' />\n";
        $article_visibility = 'draft';
      } else if ($article_visibility == 'private' || $post->post_password != '') {
        echo "<meta name='shareaholic:shareable_page' content='false' />\n";
        $article_visibility = 'private';
      } else if (is_attachment()) {
        // attachments are shareable but not recommendable
        echo "<meta name='shareaholic:shareable_page' content='true' />\n";
        $article_visibility = 'private';
      } else {
        echo "<meta name='shareaholic:shareable_page' content='true' />\n";
        $article_visibility = NULL;
      }

      // Lookup Metabox value
      if (get_post_meta($post->ID, 'shareaholic_exclude_recommendations', true)) {
        $article_visibility = 'private';
      }

      if (!empty($article_visibility)) {
        echo "<meta name='shareaholic:article_visibility' content='" . $article_visibility . "' />\n";
      }
            
      // Article Author Name      
      if ($post->post_author) {
        $article_author_data = get_userdata($post->post_author);
        if ($article_author_data) {
          $article_author_name = $article_author_data->display_name;
        }
      }
      if (!empty($article_author_name)) {
        echo "<meta name='shareaholic:article_author_name' content='" . $article_author_name . "' />\n";
      }
    }
  }
  
  /**
   * Draws Shareaholic language meta tag.
   */
  private static function draw_language_meta_tag() {
    $blog_language = get_bloginfo('language');
    if (!empty($blog_language)) {
      echo "<meta name='shareaholic:language' content='" . $blog_language . "' />\n";
    }
  }

  /**
   * Draws Shareaholic url meta tag.
   */
  private static function draw_url_meta_tag() {
    if (in_array(ShareaholicUtilities::page_type(), array('page', 'post'))) {
      $url_link = get_permalink();
      echo "<meta name='shareaholic:url' content='" . $url_link . "' />\n";
    }
  }
    
  /**
   * Draws Shareaholic version meta tag.
   */
  private static function draw_plugin_version_meta_tag() {
      echo "<meta name='shareaholic:wp_version' content='" . ShareaholicUtilities::get_version() . "' />\n";
  }  
  
  /**
   * Draws Shareaholic site name meta tag.
   */
  private static function draw_site_name_meta_tag() {
    $blog_name = get_bloginfo();
    if (!empty($blog_name)) {
      echo "<meta name='shareaholic:site_name' content='" . $blog_name . "' />\n";
    }
  }

  /**
   * Draws Shareaholic site_id meta tag.
   */
  private static function draw_site_id_meta_tag() {
    $site_id = ShareaholicUtilities::get_option('api_key');
    if (!empty($site_id)) {
      echo "<meta name='shareaholic:site_id' content='" . $site_id . "' />\n";
    }
  }
  
  /**
   * Draws Shareaholic image meta tag. Will only run on pages or posts.
   */
  private static function draw_image_meta_tag() {
    if (in_array(ShareaholicUtilities::page_type(), array('page', 'post'))) {
      global $post;
      $thumbnail_src = '';
      
      if (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        $thumbnail_src = esc_attr($thumbnail[0]);
      } 
      if ($thumbnail_src == NULL) {
        $thumbnail_src = ShareaholicUtilities::post_first_image();
      }
      if ($thumbnail_src != NULL) {
        echo "<meta name='shareaholic:image' content='" . $thumbnail_src . "' />";
      }
    }
  }

  /**
   * Draws an open graph image meta tag if they are enabled and exist. Will only run on pages or posts.
   */
  private static function draw_og_tags() {
    if (in_array(ShareaholicUtilities::page_type(), array('page', 'post'))) {
      global $post;
      $thumbnail_src = '';
      $settings = ShareaholicUtilities::get_settings();
      if (!get_post_meta($post->ID, 'shareaholic_disable_open_graph_tags', true) && (isset($settings['disable_og_tags']) && $settings['disable_og_tags'] == "off")) {        
        if (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
          $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
          $thumbnail_src = esc_attr($thumbnail[0]);
        } 
        if ($thumbnail_src == NULL) {
          $thumbnail_src = ShareaholicUtilities::post_first_image();
        }
        if ($thumbnail_src != NULL) {
          echo "\n<!-- Shareaholic Open Graph Tags -->\n";
          echo "<meta property='og:image' content='" . $thumbnail_src . "' />";
          echo "\n<!-- Shareaholic Open Graph Tags End -->\n";
        }
      }
    }
  }
	
  /**
   * This static function inserts the shareaholic canvas at the end of the post
   *
   * @param  string $content the wordpress content
   * @return string          the content
   */
  public static function draw_canvases($content) {
    global $post;
    $settings = ShareaholicUtilities::get_settings();
    $page_type = ShareaholicUtilities::page_type();
    foreach (array('share_buttons', 'recommendations') as $app) {
      if (!get_post_meta($post->ID, "shareaholic_disable_{$app}", true)) {
        if (isset($settings[$app]["{$page_type}_above_content"]) &&
            $settings[$app]["{$page_type}_above_content"] == 'on') {
          // share_buttons_post_above_content
          $id = $settings['location_name_ids'][$app]["{$page_type}_above_content"];
          $content = self::canvas($id, $app) . $content;
        }

        if (isset($settings[$app]["{$page_type}_below_content"]) &&
            $settings[$app]["{$page_type}_below_content"] == 'on') {
          // share_buttons_post_below_content
          $id = $settings['location_name_ids'][$app]["{$page_type}_below_content"];
          $content .= self::canvas($id, $app);
        }
      }
    }

    // something that uses the_content hook must return the $content
    return $content;
  }

  /**
   * Draws an individual canvas given a specific location
   * id and app. The app isn't strictly necessary, but is
   * being kept for now for backwards compatability.
   * This method was private, but was made public to be accessed
   * by the shortcode static function in global_functions.php.
   *
   * @param string $id  the location id for configuration
   * @param string $app the type of app
   * @param string $title the title of URL
   * @param string $link url
   * @param string $summary summary text for URL
   */
  public static function canvas($id, $app, $title = NULL, $link = NULL, $summary = NULL) {
    global $post, $wp_query;
    $page_type = ShareaholicUtilities::page_type();
    $is_list_page = $page_type == 'index' || $page_type == 'category';
    $loop_start = did_action('loop_start');
    $loop_end = did_action('loop_end');
    $in_loop = $loop_start > $loop_end ? TRUE : FALSE;

    $link = trim($link);

    // Use the $post object to get the title, link, and summary only if the
    // title, link or summary is not provided AND one of the following is true:
    // - we are on a non list page
    // - we are in the wordpress loop
    if (trim($title) == NULL && (!$is_list_page || $in_loop)) {
      $title = htmlspecialchars($post->post_title, ENT_QUOTES);
    }
    if (trim($link) == NULL && (!$is_list_page || $in_loop)) {
      $link = get_permalink($post->ID);
    }
    if (trim($summary) == NULL && (!$is_list_page || $in_loop)) {
      $summary = htmlspecialchars(strip_tags(strip_shortcodes($post->post_excerpt)), ENT_QUOTES);
      $summary = ShareaholicUtilities::truncate_text($summary, 500);
    }
    
    $canvas = "<div class='shareaholic-canvas'
      data-app-id='$id'
      data-app='$app'
      data-title='$title'
      data-link='$link'
      data-summary='$summary'></div>";

    return trim(preg_replace('/\s+/', ' ', $canvas));
  }


  /**
   * Function to handle the share count API requests
   *
   */
  public static function share_counts_api() {
    $debug_mode = isset($_GET['debug']) && $_GET['debug'] === '1';
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    $services = isset($_GET['services']) ? $_GET['services'] : array();
    $services = self::parse_services($services);
    $cache_key = 'shr_api_res-' . md5( $url );

    if (empty($url) || empty($services)) {
      $result = array();
    } else {
      $result = get_transient($cache_key);
    }

    $has_curl_multi = self::has_curl();

    if (!$result || $debug_mode || !self::has_services_in_result($result, $services)) {
      if (isset($result['services']) && !$debug_mode) {
        $services = array_keys(array_flip(array_merge($result['services'], $services)));
      }

      $result = array();
      $options = array();

      if ($debug_mode && isset($_GET['timeout'])) {
        $options['timeout'] = intval($_GET['timeout']);
      }

      if(is_array($services) && count($services) > 0 && !empty($url)) {
        if ($debug_mode && isset($_GET['client'])) {
          if ($has_curl_multi && $_GET['client'] !== 'seq') {
            $shares = new ShareaholicCurlMultiShareCount($url, $services, $options);
          } else {
            $shares = new ShareaholicSeqShareCount($url, $services, $options);
          }
        } else if($has_curl_multi) {
          $shares = new ShareaholicCurlMultiShareCount($url, $services, $options);
        } else {
          $shares = new ShareaholicSeqShareCount($url, $services, $options);
        }
        $result = $shares->get_counts();

        if ($debug_mode) {
          $result['has_curl_multi'] = $has_curl_multi;
          $result['curl_type'] = get_class($shares);
          $result['raw'] = $shares->raw_response;
        }

        if (isset($result['data']) && !$debug_mode) {
          $result['services'] = $services;
          set_transient( $cache_key, $result, SHARE_COUNTS_CHECK_CACHE_LENGTH );
        }
      }
    }

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Cache-Control: max-age=180'); // 3 minutes
    echo json_encode($result);
    exit;
  }

  /**
   * Helper method to parse the list of social services to get share counts
   */
  public static function parse_services($services) {
    $result = array();

    if (empty($services) || !is_array($services)) {
      return $result;
    }

    // make the set of services unique
    $services = array_unique($services);

    // only get the services we can get share counts for
    $social_services = array_keys(ShareaholicSeqShareCount::get_services_config());

    foreach($services as $service) {
      if (in_array($service, $social_services)) {
        array_push($result, $service);
      }
    }

    return $result;
  }

  /**
   * Helper method to check if the result has the requested services
   */
  public static function has_services_in_result($result, $services) {
    if (!isset($result['services'])) {
      return false;
    }

    $requested_services = $result['services'];
    foreach($services as $service) {
      if (!in_array($service, $requested_services)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Function to return relevant plugin debug info
   *
   * @return debug info in JSON
   */
  public static function debug_info() {
    global $wpdb;
    
    if (ShareaholicUtilities::get_option('disable_debug_info') == "on"){
      exit;
    }
    
    if (ShareaholicUtilities::get_option('disable_internal_share_counts_api') == NULL || ShareaholicUtilities::get_option('disable_internal_share_counts_api') == "off"){
      $server_side_share_count_status =  "on";
    } else {
      $server_side_share_count_status =  "off";
    }
    
    if (ShareaholicUtilities::has_accepted_terms_of_service() == 1){
      $tos_status = "accepted";
    } else {
      $tos_status = "pending";
    }
    
    if (function_exists('curl_version')){
      $curl_version = curl_version();
    }
    
    $info = array(
  	'plugin_version' => Shareaholic::VERSION,
  	'site_id' => ShareaholicUtilities::get_option('api_key'),
  	'domain' => get_bloginfo('url'),
  	'language' => get_bloginfo('language'),
  	'tos_status' => $tos_status,
  	'stats' => array (
  	  'posts_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'post' AND post_status = 'publish'" ),
  	  'pages_total' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_type = 'page' AND post_status = 'publish'" ),
  	  'comments_total' => wp_count_comments()->approved,
  	  'users_total' => $wpdb->get_var("SELECT count(ID) FROM $wpdb->users"),
      ),
  	'diagnostics' => array (
  	  'theme' => get_option('template'),
    	'multisite' => is_multisite(),
    	'shareaholic_server_reachable' => ShareaholicUtilities::connectivity_check(),
    	'server_side_share_count_api_reachable' => ShareaholicUtilities::share_counts_api_connectivity_check(),
  	  'php_version' => phpversion(),
  	  'wp_version' => get_bloginfo('version'),
  	  'curl' => array (
  	    'status' => ShareaholicPublic::has_curl(),
  	    'version' => $curl_version,
  	  ),
  	'plugins' => array (
  	    'active' => get_option('active_plugins', array()),
  	    'sitewide'  => get_site_option('active_sitewide_plugins', array()),
	      ),
  	  ),
  	'app_locations' => array (
  	  'share_buttons' => ShareaholicUtilities::get_option('share_buttons'),
  	  'recommendations' => ShareaholicUtilities::get_option('recommendations'),
	    ),
  	'advanced_settings' => array (
  	  'server_side_share_count_api' => $server_side_share_count_status,
  	  )
    );
    
    header('Content-Type: application/json');
    echo json_encode($info);
    exit;
  }
  
  
  /**
   * Function to return list of permalinks
   *
   * @return list of permalinks in JSON or plain text
   */
  public static function permalink_list(){
    
    // Input Params
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : "any";
    $n = isset($_GET['n']) ? intval($_GET['n']) : -1;
    $format = isset($_GET['format']) ? $_GET['format'] : "json";
    
    $permalink_list = array();
    $permalink_query = "post_type=$post_type&post_status=publish&posts_per_page=$n";
    $posts = new WP_Query ($permalink_query);
    $posts = $posts->posts;
    foreach($posts as $post){
      switch ($post->post_type){
        case 'revision':
        case 'nav_menu_item':
          break;
        case 'page':
          $permalink = get_page_link($post->ID);
          array_push($permalink_list, $permalink);
          break;
        case 'post':
          $permalink = get_permalink($post->ID);
          array_push($permalink_list, $permalink);
          break;
        case 'attachment':
          break;
        default:
          $permalink = get_post_permalink($post->ID);
          array_push($permalink_list, $permalink);
          break;
        }
      }
      
      if ($format === "text"){
        header('Content-Type: text/plain; charset=utf-8');
        foreach($permalink_list as $link) {
          echo $link. "\r\n";
        }
      } elseif ($format === "json"){
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($permalink_list);
      }
      exit;
  }
  
  /**
   * Function to return relevant info for a given permalink for the Related Content index
   *
   * @return page info in JSON
   */
  public static function permalink_info() {   
    global $wpdb, $post;
    
    // Input Params
    $permalink = isset($_GET['permalink']) ? $_GET['permalink'] : NULL;
    $body_text = isset($_GET['body_text']) ? $_GET['body_text'] : "raw";
    
    if ($permalink == NULL){
      return;
    }
    
    // Get post ID
    $post_id = url_to_postid($permalink);
    
    // for non-default paths - handle both https and http versions of the permalink
    if ($post_id == 0){
      $parse = parse_url($permalink);
      if ($parse['scheme'] == "https"){
        $permalink = str_replace("https", "http", $permalink);
        $post_id = url_to_postid($permalink);
      } else if ($parse['scheme'] == "http"){
        $permalink = str_replace("http", "https", $permalink);
        $post_id = url_to_postid($permalink);
      }
    }
    
    if ($post_id == 0){
      return;
    }
    
    // Get post for given ID
    $post = get_post($post_id);
    
    if ($post->post_status != 'publish' || $post->post_password != ''){
      return;
    }
    
    // Post tags
    $tags = ShareaholicUtilities::permalink_keywords($post_id);

    // Post categories
    $categories = array();
    $categories_array = get_the_category($post_id);
    
    if($categories_array) {
    	foreach($categories_array as $category) {
    	  if ($category->cat_name != "Uncategorized") {
    	    $category_name = ShareaholicUtilities::normalize_keywords($category->cat_name);
          array_push($categories, $category_name);
  		  }
    	}
    }
    
    // Post body
    $order   = array("&nbsp;", "\r\n", "\n", "\r", "  ");
    $post_body = str_replace($order, ' ', $post->post_content);
    
    if ($body_text == "clean"){
      $post_body = strip_tags($post_body);
    } elseif ($body_text == "raw" || $body_text == NULL) {
      $post_body = $post_body;
    }

    // Get post author name
    if ($post->post_author) {
      $author_data = get_userdata($post->post_author);
      $author_name = $author_data->display_name;
    }
    
    // Term frequencies
    // $term_frequency_title = array_count_values(str_word_count(strtolower(strip_tags($post->post_title)), 1));
    $term_frequency_body = array_count_values(str_word_count(strtolower(strip_tags($post_body)), 1));
    
    $term_frequency = $term_frequency_body;
    arsort($term_frequency);
    
    // Construct array
    $info = array(
      'permalink' => $permalink,
      'domain' => get_bloginfo('url'),
      'site_id' => ShareaholicUtilities::get_option('api_key'),
      'content' => array(
        'title' => $post->post_title,
        'excerpt' => $post->post_excerpt,
        'body' => $post_body,
        'thumbnail' => ShareaholicUtilities::permalink_thumbnail($post->ID, "large"),
      ),
      'post_metadata' => array(
        'author_id' => $post->post_author,
        'author_name' => $author_name,
        'post_type' => $post->post_type,
        'post_id' => $post_id,
        'post_tags' => $tags,
        'post_categories' => $categories,
        'post_language' => get_bloginfo('language'),
        'post_published' => date('c', strtotime($post->post_date_gmt)),
        'post_updated' => date('c', strtotime(get_lastpostmodified('GMT'))),
        'post_visibility' => $post->post_status,
      ),
      'post_stats' => array(
        'post_comments_count' => get_comments_number($post_id),
        'post_content_title_character_count' => strlen(trim(html_entity_decode($post->post_title))),
        'post_content_title_word_count' => str_word_count(strip_tags($post->post_title)),
        'post_content_body_character_count' => strlen(trim(html_entity_decode($post_body))),
        'post_content_body_word_count' => str_word_count(strip_tags($post_body)),
        'term_frequency' => $term_frequency,
      ),
      'diagnostics' => array(
        'platform' => 'wp',
        'platform_version' => get_bloginfo('version'),
        'plugin_version' => Shareaholic::VERSION,
      ),
    );
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($info);
    exit;
  }
  
  /**
   * Function to return related permalinks for a given permalink to bootstrap the Related Content app until the off-line processing routines complete
   *
   * @return list of related permalinks in JSON
   */
  public static function permalink_related() {
    global $post;
    
    // Input Params
    $permalink = isset($_GET['permalink']) ? $_GET['permalink'] : NULL;
    $match = isset($_GET['match']) ? $_GET['match'] : "random"; // match method
    $n = isset($_GET['n']) ? intval($_GET['n']) : 10; // number of related permalinks to return
    
    $related_permalink_list = array();
    
    // Get post ID
    if ($permalink == NULL){
      // default to random match if no permalink is available
      $match = "random";
    } else {
      $post_id = url_to_postid($permalink);
      
      // for non-default paths - handle both https and http versions of the permalink
      if ($post_id == 0){
        $parse = parse_url($permalink);
        if ($parse['scheme'] == "https"){
          $permalink = str_replace("https", "http", $permalink);
          $post_id = url_to_postid($permalink);
        } else if ($parse['scheme'] == "http"){
          $permalink = str_replace("http", "https", $permalink);
          $post_id = url_to_postid($permalink);
        }
      }
    }
    
    if ($match == "random"){
      $args = array( 'posts_per_page' => $n, 'orderby' => 'rand' );
      $rand_posts = get_posts( $args );
      foreach ( $rand_posts as $post ){
        $related_link = array(
          'page_id' => $post->ID,
          'url' => get_permalink($post->ID),
          'title' => $post->post_title,
          'description' => $post->post_excerpt,
          'image_url' => ShareaholicUtilities::permalink_thumbnail($post->ID, "medium"),
          'score' => 1
        );
        array_push($related_permalink_list, $related_link);
      }
      wp_reset_postdata();
    } else {
      // other methods coming soon
    }
    
    // Construct results array
    $result = array(
      'request' => array(
        'api_key' => ShareaholicUtilities::get_option('api_key'),
        'url' => $permalink,
      ),
      'internal' => $related_permalink_list
    );
    
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: max-age=180'); // 3 minutes
    echo json_encode($result);
    exit;
  }
  
  /**
   * Checks to see if curl is installed
   *
   * @return bool true or false that curl is installed
   */
  public static function has_curl(){
    return function_exists('curl_version') && function_exists('curl_multi_init') && function_exists('curl_multi_add_handle') && function_exists('curl_multi_exec');
  }
}

?>