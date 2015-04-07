<?php
/**
 * This file holds the ShareaholicCurl class.
 *
 * @package shareaholic
 */

require_once(SHAREAHOLIC_DIR . '/query_string_builder.php');

/**
 * This class is a library to easily interface with PHP's native
 * cURL library. It exposes two methods `get` and `post`.
 *
 * @package shareaholic
 */
class ShareaholicCurl {
  /**
   *
   * Performs a POST request
   *
   * @param string $url          the url you are POSTing to
   * @param array  $data         an associative array of the data you are posting
   * @param string $data_type    defaults to nothing, you can pass in 'json'
   * @param bool   $ignore_error whether to log a networking error
   *
   * @return array the returned data json decoded
   */
  public static function post($url, $data = array(), $data_type = '', $ignore_error = false) {
    return self::send_request_with_wp($url, $data, $data_type, $ignore_error, 'POST');
  }

  /**
   *
   * Performs a GET request
   *
   * @param string $url          the url you are GETing to
   * @param array  $data         an associative array of the data you are posting
   * @param string $data_type    defaults to nothing, you can pass in 'json'
   * @param bool   $ignore_error whether to log a networking error
   *
   * @return array the returned data json decoded
   */
  public static function get($url, $data = array(), $data_type = '', $ignore_error = false) {
    return self::send_request_with_wp($url, $data, $data_type, $ignore_error, 'GET');
  }

  /**
   *
   * Performs a request using the methods built into WordPress, which account for
   * various PHP eccenctricities.
   *
   * @param string $url
   * @param array  $data         an associative array of the data
   * @param string $data_type    either an empty string or 'json'
   * @param bool   $ignore_error whether to log a networking error
   * @param string $method       the HTTP verb to be used
   *
   * @return mixed the returned data json decoded or false
   */
  private static function send_request_with_wp($url, $data, $data_type, $ignore_error, $method) {
    ShareaholicUtilities::log($url);
    ShareaholicUtilities::log($data);
    ShareaholicUtilities::log($data_type);
    ShareaholicUtilities::log($method);
    ShareaholicUtilities::log('-----------------');
    $timeout = 15;	  
    $useragent = 'WordPress/' . get_bloginfo('version') . '; '. 'PHP/' . phpversion() . '; ' . 'SHR_WP/' . Shareaholic::VERSION . '; ' . get_bloginfo( 'url' );
    if ($method == 'GET') {
      $response = wp_remote_get($url, array('body' => $data, 'sslverify'=>false, 'user-agent'=>$useragent, 'timeout'=>$timeout));
    } elseif ($method == 'POST') {
      $request = array();
      if ($data_type == 'json') {
        $request['headers'] = array(
          'Content-Type' => 'application/json'
        );
        $request['body'] = json_encode($data);
      } else {
        $request['body'] = $data;
      }
      $request['headers']['Accept'] = 'application/json';
      $request['sslverify'] = false;
      $request['timeout'] = $timeout;
      $response = wp_remote_post($url, $request);
    }

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      ShareaholicUtilities::log($error_message);
      if (!$ignore_error) {
        ShareaholicUtilities::log_event('CurlRequestFailure', array('error_message' => $error_message, 'url' => $url));
      }
      return false;
    }
    else {
      if(is_array($response) && array_key_exists('body', $response)) {
        $body = $response['body'];

        // strip out any BOM appended characters
        $body = self::strip_utf8_bom($body);

        $response['body'] = ShareaholicUtilities::object_to_array(json_decode($body)) ?
          ShareaholicUtilities::object_to_array(json_decode($body)) : $body;
        return $response;
      }
    }
  }

  /**
   *
   * Performs a request using cURL
   *
   * @param string $url       the url you are GETing to
   * @param array  $data      an associative array of the data you are posting
   * @param string $data_type either an empty string or 'json'
   * @param string $method    the HTTP verb to be used
   *
   * @return array the returned data json decoded
   */
  private static function send_request($url, $data, $data_type, $method) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false
    ));

    /*
     * Because many shared hosting providers set `open_basedir` in php.ini
     * that means we can't always set CURLOPT_FOLLOWLOCATION.
     * This next block is an attempt around that by sending head requests
     * to determine if there will be a redirect and then following it.
     * Shamelessly stolen from here:
     *   http://us2.php.net/manual/en/function.curl-setopt.php#102121
     */
    $mr = 5;
    if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $mr > 0);
      curl_setopt($curl, CURLOPT_MAXREDIRS, $mr);
    } else {
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
      if ($mr > 0) {
        $newurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

        $rch = curl_copy_handle($curl);
        curl_setopt($rch, CURLOPT_HEADER, true);
        curl_setopt($rch, CURLOPT_NOBODY, true);
        curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
        curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
        do {
          curl_setopt($rch, CURLOPT_URL, $newurl);
          $header = curl_exec($rch);
          if (curl_errno($rch)) {
            $code = 0;
          } else {
            $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
            if ($code == 301 || $code == 302) {
              preg_match('/Location:(.*?)\n/', $header, $matches);
              $newurl = trim(array_pop($matches));
            } else {
              $code = 0;
            }
          }
        } while ($code && --$mr);
        curl_close($rch);
        if (!$mr) {
          if ($maxredirect === null) {
            trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
          } else {
            $maxredirect = 0;
          }
          return false;
        }
        curl_setopt($curl, CURLOPT_URL, $newurl);
      }
    }
    /* end stolen code */

    if ($method == 'POST') {
      curl_setopt_array($curl, array(
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => array("Accept: application/json,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain")
      ));

      if ($data_type == 'json'){
        curl_setopt_array($curl, array(
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
          )
        ));
      } else {
        curl_setopt_array($curl, array(
          CURLOPT_POSTFIELDS => ShareaholicQueryStringBuilder::build_query_string($data),
        ));
      }
    }

    $result = curl_exec($curl);
    $info = curl_getinfo($curl);
    ShareaholicUtilities::log(curl_error($curl));
    ShareaholicUtilities::log(curl_getinfo($curl));
    curl_close($curl);

    if (preg_match('/^20*/', $info['http_code'])) {
      return ShareaholicUtilities::object_to_array(json_decode($result)) ?
        ShareaholicUtilities::object_to_array(json_decode($result)) : $result;
    } else {
      return false;
    }
  }

  /**
   * Sometimes the response from APIs begins with BOM (byte order mark) which
   * makes it difficult to parse JSON responses.
   *
   * If it exists, remove it and return the cleaned up string
   *
   * @param {String} $str the string to strip BOM
   * @return {String} the stripped out string if BOM exists
   */
  private static function strip_utf8_bom($str) {
    // create binary string for BOM
    $bom = pack("CCC", 0xef, 0xbb, 0xbf);

    // binary safe compare the BOM with the string
    if (0 === strncmp($str, $bom, 3)) {
      return substr($str, 3);
    }

    return $str;
  }
}

?>
