<?php
/**
 * Shareaholic's Social Share Counts Library
 *
 * https://github.com/shareaholic/social-share-counts
 *
 * @package shareaholic
 * @version 1.0.0.0
 */

/**
 * An abstract class Share Counts to be extended
 *
 * @package shareaholic
 */
abstract class ShareaholicShareCount {

  protected $url;
  protected $services;
  protected $options;
  public $raw_response;

  public function __construct($url, $services, $options) {
    // encode the url if needed
    if (!$this->is_url_encoded($url)) {
      $url = urlencode($url);
    }
    $this->url = $url;
    $this->services = $services;
    $this->options = $options;
    $this->raw_response = array();
  }

  public static function get_services_config() {
    return array(
      'facebook' => array(
        'url' => 'https://graph.facebook.com/?id=%s',
        'method' => 'GET',
        'timeout' => 3,  // in number of seconds
        'callback' => 'facebook_count_callback',
      ),
      'linkedin' => array(
        'url' => 'https://www.linkedin.com/countserv/count/share?format=json&url=%s',
        'method' => 'GET',
        'timeout' => 3,
        'callback' => 'linkedin_count_callback',
      ),
      'google_plus' => array(
        'url' => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ',
        'method' => 'POST',
        'timeout' => 2,
        'headers' => array('Content-Type' => 'application/json'),
        'body' => NULL,
        'prepare' => 'google_plus_prepare_request',
        'callback' => 'google_plus_count_callback',
      ),
      'pinterest' => array(
        'url' => 'https://api.pinterest.com/v1/urls/count.json?url=%s&callback=f',
        'method' => 'GET',
        'timeout' => 3,
        'callback' => 'pinterest_count_callback',
      ),
      'buffer' => array(
        'url' => 'https://api.bufferapp.com/1/links/shares.json?url=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'buffer_count_callback',
      ),
      'stumbleupon' => array(
        'url' => 'https://www.stumbleupon.com/services/1.01/badge.getinfo?url=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'stumbleupon_count_callback',
      ),
      'reddit' => array(
        'url' => 'https://buttons.reddit.com/button_info.json?url=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'reddit_count_callback',
      ),
      'vk' => array(
        'url' => 'http://vk.com/share.php?act=count&url=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'vk_count_callback',
      ),
      'odnoklassniki' => array(
        'url' => 'https://connect.ok.ru/dk?st.cmd=extLike&uid=odklcnt0&ref=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'odnoklassniki_count_callback',
      ),

      'fancy' => array(
        'url' => 'http://fancy.com/fancyit/count?ItemURL=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'fancy_count_callback',
      ),
      'yummly' => array(
        'url' => 'http://www.yummly.com/services/yum-count?url=%s',
        'method' => 'GET',
        'timeout' => 1,
        'callback' => 'yummly_count_callback',
      ),
    );
  }

  /**
   * Check if the url is encoded
   *
   * The check is very simple and will fail if the url is encoded
   * more than once because the check only decodes once
   *
   * @param string $url the url to check if it is encoded
   * @return boolean true if the url is encoded and false otherwise
   */
  public function is_url_encoded($url) {
    $decoded = urldecode($url);
    if (strcmp($url, $decoded) === 0) {
      return false;
    }
    return true;
  }

  /**
   * Check if calling the service returned any type of error
   *
   * @param object $response A response object
   * @return bool true if it has an error or false if it does not
   */
  public function has_http_error($response) {
    if(!$response || !isset($response['response']['code']) || !preg_match('/20*/', $response['response']['code']) || !isset($response['body'])) {
      return true;
    }
    return false;
  }

  /**
   * Get the client's ip address
   *
   * NOTE: this function does not care if the IP is spoofed. This is used
   * only by the google plus count API to separate server side calls in order
   * to prevent usage limits. Under normal conditions, a request from a user's
   * browser to this API should not involve any spoofing.
   *
   * @return {Mixed} An IP address as string or false otherwise
   */
  public function get_client_ip() {
    $ip = NULL;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
     //check for ip from share internet
     $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
     // Check for the Proxy User
     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
     $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }


  /**
   * Callback function for facebook count API
   * Gets the facebook counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function facebook_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    return isset($body['share']['share_count']) ? intval($body['share']['share_count']) : false;
  }


  /**
   * Callback function for linkedin count API
   * Gets the linkedin counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function linkedin_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    return isset($body['count']) ? intval($body['count']) : false;
  }


  /**
   * A preprocess function to be called necessary to prepare
   * the request to the service.
   *
   * One may customize the headers or body to their liking
   * before the request is sent. The customization should
   * update the services config where it will be read by
   * the get_counts() function
   *
   * @param $url The url needed by google_plus to be passed in to the body
   * @param $config The services configuration object to be updated
   */
  public function google_plus_prepare_request($url, &$config) {
    if ($this->is_url_encoded($url)) {
      $url = urldecode($url);
    }
    $post_fields = array(
      array(
        'method' => 'pos.plusones.get',
        'id' => 'p',
        'params' => array(
          'nolog' => true,
          'id' => $url,
          'source' => 'widget',
          'userId' => '@viewer',
          'groupId' => '@self',
        ),
        'jsonrpc' => '2.0',
        'key' => 'p',
        'apiVersion' => 'v1',
      )
    );

    $ip = $this->get_client_ip();
    if ($ip && !empty($ip)) {
      $post_fields[0]['params']['userIp'] = $ip;
    }

    $config['google_plus']['body'] = $post_fields;
  }


  /**
   * Callback function for google plus count API
   * Gets the google plus counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function google_plus_count_callback($response) {
    if($this->has_http_error($response)) {
       return false;
    }
    $body = json_decode($response['body'], true);
    // special case: do not return false if the count is not set because the api can return without counts
    return isset($body[0]['result']['metadata']['globalCounts']['count']) ? intval($body[0]['result']['metadata']['globalCounts']['count']) : 0;
  }


  /**
   * Callback function for pinterest count API
   * Gets the pinterest counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function pinterest_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $response['body'] = substr($response['body'], 2, strlen($response['body']) - 3);
    $body = json_decode($response['body'], true);
    return isset($body['count']) ? intval($body['count']) : false;
  }


  /**
   * Callback function for buffer count API
   * Gets the buffer share counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function buffer_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    return isset($body['shares']) ? intval($body['shares']) : false;
  }


  /**
   * Callback function for stumbleupon count API
   * Gets the stumbleupon counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function stumbleupon_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    // special case: do not return false if views is not set because the api can return it not set
    return isset($body['result']['views']) ? intval($body['result']['views']) : 0;
  }


  /**
   * Callback function for reddit count API
   * Gets the reddit counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function reddit_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    // special case: do not return false if the ups is not set because the api can return it not set
    return isset($body['data']['children'][0]['data']['ups']) ? intval($body['data']['children'][0]['data']['ups']) : 0;
  }


  /**
   * Callback function for vk count API
   * Gets the vk counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function vk_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }

    // This API does not return JSON. Just plain text JS. Example:
    // 'VK.Share.count(0, 3779);'
    // From documentation, need to just grab the 2nd param: http://vk.com/developers.php?oid=-17680044&p=Share
    $matches = array();
    preg_match('/^VK\.Share\.count\(\d, (\d+)\);$/i', $response['body'], $matches);
    return isset($matches[1]) ? intval($matches[1]) : false;
  }


  /**
   * Callback function for odnoklassniki count API
   * Gets the odnoklassniki counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function odnoklassniki_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }

    // Another weird API. Similar to vk, extract the 2nd param from the response:
    // 'ODKL.updateCount('odklcnt0','14198');'
    $matches = array();
    preg_match('/^ODKL\.updateCount\(\'odklcnt0\',\'(\d+)\'\);$/i', $response['body'], $matches);
    return isset($matches[1]) ? intval($matches[1]) : false;
  }

  /**
   * Callback function for Fancy count API
   * Gets the Fancy counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function fancy_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }

    // Fancy always provides a JS callback like this in the response:
    // '__FIB.collectCount({"url": "http://www.google.com", "count": 26, "thing_url": "http://fancy.com/things/263001623/Google%27s-Jim-Henson-75th-Anniversary-logo", "showcount": 1});'
    // strip out the callback and parse the JSON from there
    $response['body'] = str_replace('__FIB.collectCount(', '', $response['body']);
    $response['body'] = substr($response['body'], 0, strlen($response['body']) - 2);

    $body = json_decode($response['body'], true);
    return isset($body['count']) ? intval($body['count']) : false;
  }

  /**
   * Callback function for Yummly count API
   * Gets the Yummly counts from response
   *
   * @param Array $response The response from calling the API
   * @return mixed The counts from the API or false if error
   */
  public function yummly_count_callback($response) {
    if($this->has_http_error($response)) {
      return false;
    }
    $body = json_decode($response['body'], true);
    return isset($body['count']) ? intval($body['count']) : false;
  }
  
  /**
   * The abstract function to be implemented by its children
   * This function should get all the counts for the
   * supported services
   *
   * It should return an associative array with the services as
   * the keys and the counts as the value.
   *
   * Example:
   * array('facebook' => 12, 'google_plus' => 0, 'twitter' => 14, ...);
   *
   * @return Array an associative array of service => counts
   */
  public abstract function get_counts();

}