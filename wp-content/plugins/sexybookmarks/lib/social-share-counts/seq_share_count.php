<?php
/**
 * Shareaholic Sequential Share Count
 *
 * @package shareaholic
 * @version 1.0.0.0
 */

require_once('share_count.php');

/**
 * A class that implements ShareaholicShareCounts
 * This class will get the share counts by calling
 * the social services sequentially
 *
 * @package shareaholic
 */

class ShareaholicSeqShareCount extends ShareaholicShareCount {

  /**
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
  public function get_counts() {
    $services_length = count($this->services);
    $config = self::get_services_config();
    $response = array();
    $response['status'] = 200;

    for($i = 0; $i < $services_length; $i++) {
      $service = $this->services[$i];

      if(!isset($config[$service])) {
        continue;
      }

      if(isset($config[$service]['prepare'])) {
        $this->{$config[$service]['prepare']}($this->url, $config);
      }

      $timeout = isset($config[$service]['timeout']) ? $config[$service]['timeout'] : 1;
      $timeout = isset($this->options['timeout']) ? $this->options['timeout'] : $timeout;

      $options = array(
        'method' => $config[$service]['method'],
        'timeout' => $timeout,
        'headers' => isset($config[$service]['headers']) ? $config[$service]['headers'] : array(),
        'body' => isset($config[$service]['body']) ? $config[$service]['body'] : NULL,
      );

      $result = ShareaholicHttp::send(str_replace('%s', $this->url, $config[$service]['url']), $options);
      if(!$result) {
        $response['status'] = 500;
      }
      $callback = $config[$service]['callback'];
      $counts = $this->$callback($result);
      if(is_numeric($counts)) {
        $response['data'][$service] = $counts;
      }
      $this->raw_response[$service] = $result;
    }
    return $response;
  }
}