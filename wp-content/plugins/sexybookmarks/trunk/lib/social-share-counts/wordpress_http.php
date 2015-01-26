<?php
/**
 * This file holds the ShareaholicHttp class.
 *
 * @package shareaholic
 */

/**
 * The purpose of this class is to provide an interface around any native
 * http function (wp_remote_get, drupal_http_request, curl) so that one
 * use this consistent API for making http request with well defined input
 * and output.
 *
 * @package shareaholic
 */
class ShareaholicHttp {

  /**
   * Performs a HTTP request with a url, array of options, and ignore_error flag
   *
   *
   * The options object is an associative array that takes the following options:
   * - method: The http method for the request as a string. Defaults is 'GET'.
   *
   * - headers: The headers to send with the request as an associative array of name/value pairs. Default is empty array.
   *
   * - body: The body to send with the request as an associative array of name/value pairs. Default is NULL.
   * If the body is meant to be parsed as json, specify the content type in the headers option to be 'application/json'.
   *
   * - redirection: The number of redirects to follow for this request as an integer, Default is 5.
   *
   * - timeout: The number of seconds the request should take as an integer. Default is 15 (seconds).
   *
   * - user-agent: The useragent for the request. Default is mozilla browser useragent.
   *
   *
   * This function returns an object on success or false if there were errors.
   * The object is an associative array with the following keys:
   * - headers: the response headers as an array of key/value pairs
   * - body: the response body as a string
   * - response: an array with the following keys:
   *    - code: the response code
   *    - message: the status message
   *
   *
   * @param string $url The url you are sending the request to
   * @param array $options An array of supported options to pass to the request
   * @param bool $ignore_error A flag indicating to log error or not. Default is false.
   *
   * @return mixed It returns an associative array of name value pairs or false if there was an error.
   */
  public static function send($url, $options = array(), $ignore_error = false) {
    return self::send_with_wp($url, $options, $ignore_error);
  }

  private static function send_with_wp($url, $options, $ignore_error) {
    $request = array();
    $result = array();
    $request['method'] = isset($options['method']) ? $options['method'] : 'GET';
    $request['headers'] = isset($options['headers']) ? $options['headers'] : array();
    $request['redirection'] = isset($options['redirection']) ? $options['redirection'] : 5;
    $request['timeout'] = isset($options['timeout']) ? $options['timeout'] : 15;
    $request['user-agent'] = isset($options['user-agent']) ? $options['user-agent'] : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0';

    if(isset($options['body'])) {
      if(isset($request['headers']['Content-Type']) && $request['headers']['Content-Type'] === 'application/json') {
        $request['body'] = json_encode($options['body']);
      } else {
        $request['body'] = $options['body'];
      }
    } else {
      $request['body'] = NULL;
    }
    $request['sslverify'] = false;

    $response = wp_remote_request($url, $request);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      ShareaholicUtilities::log($error_message);
      if (!$ignore_error) {
        ShareaholicUtilities::log_event('HttpRequestFailure', array('error_message' => $error_message, 'url' => $url));
      }
      return false;
    }

    return $response;
  }
}