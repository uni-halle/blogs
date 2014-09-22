<?php
/**
 * Holds the ShareaholicQueryStringBuilder class.
 *
 * @package shareaholic
 */

/**
 * This class builds query strings, because PHP's built in
 * version doesn't deal with non-associative arrays correctly.
 *
 * @package shareaholic
 */
class ShareaholicQueryStringBuilder {

  /**
   * Builds the query string
   *
   * @param  array  $hash the data to be query stringified
   * @param  string $key  to scop everything under if desired
   * @return string
   */
  public static function build_query_string($hash, $key = '') {
    $result = array();
    foreach ($hash as $key => $value) {
      array_push($result, self::to_param($value, $key));
    }
    return implode('&', $result);
  }

  /**
   * An object independent function to parameratize an object
   *
   * @param  string $value the object to be parameratized
   * @param  string $key   the key to scope under
   * @return string
   */
  public static function to_param($value, $key = '') {
    if (is_array($value)) {
      if (self::is_assoc($value)) {
        return self::assoc_array_to_param($value, $key);
      } else {
        return self::array_to_param($value, $key);
      }
    } elseif (is_bool($value)) {
      return self::bool_to_param($value);
    } else {
      return ($key ? "$key=" . (string)$value : (string)$value);
    }
  }

  /**
   * Determines whether an array is associative or not
   * by checking for the presenve of string keys.
   *
   * @param  array
   * @return bool
   */
  private static function is_assoc($array) {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
  }

  /**
   * Returns a hash like array to a query string under a namespace
   *
   * <code>
   * array('foo' => 'bar') // namspace of baz
   * baz[foo]=bar
   * </code>
   *
   * @param  array  $hash
   * @param  string $namespace
   * @return string
   */
  private static function assoc_array_to_param($hash, $namespace = '') {
    $namespace_array = array_fill(0, count($hash), $namespace);
    return implode('&', array_map(
      array('self', 'assoc_array_to_param_iterator'),
      $hash,
      array_keys($hash), $namespace_array
    ));
  }

  /**
   * Because PHP < 5.3 doesn't support anonymous functions, this serves
   * as the mapping function for the above method.
   *
   * @param  mixed $value
   * @param  string $key
   * @param  string $namespace
   * @return string
   */
  private static function assoc_array_to_param_iterator($value, $key, $namespace) {
    $new_key = ($namespace ? "{$namespace}[$key]" : $key);
    return ShareaholicQueryStringBuilder::to_param($value, $new_key);
  }

  /**
   * Returns an array paramerterized with a given key
   *
   * <code>
   * array('foo', 'bar', 'baz') // key of hello
   * hello[]=foo&hello[]=bar&hello[]=baz
   * </code>
   *
   * This is what is broken in PHP's native `http_build_query`.
   * Instead of using empty brackets, it will insert the indices
   * of each value instead, which makes it parse as a hash instead
   * of an array.
   *
   * @param  array  $array
   * @param  string $key
   * @return string
   */
  private static function array_to_param($array, $key) {
    $prefix = "{$key}[]";

    $prefix_array = array_fill(0, count($array), $prefix);
    return implode("&", array_map(array('self', 'array_to_param_iterator'), $array, $prefix_array));
  }

  /**
   * Because PHP < 5.3 doesn't support anonymous functions, this serves
   * as the mapping function for the above method.
   *
   * @param  mixed $value
   * @param  string $prefix
   * @return string
   */
  private static function array_to_param_iterator($value, $prefix) {
    return ShareaholicQueryStringBuilder::to_param($value, $prefix);
  }

  /**
   * There's no official pattern for booleans in URLs,
   * I though this made the most sense.
   *
   * @param  bool $bool
   * @return string
   */
  private static function bool_to_param($bool) {
    return ($bool ? 'true' : 'false');
  }
}

?>