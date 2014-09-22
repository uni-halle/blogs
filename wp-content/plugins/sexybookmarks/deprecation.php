<?php
/**
 * Holds the `ShareaholicDeprecation` class
 *
 * @package shareaholic
 */

/**
 * This class keeps track of various deprecations and what files
 * and line numbers they occur on.
 *
 * @package shareaholic
 */
class ShareaholicDeprecation {
  /**
   * Constructor for the `ShareaholicDeprecation` class.
   *
   * @param string $function the name of a function.
   */
  public function __construct($function) {
    $this->function = $function;
    $deprecations = get_option('shareaholic_deprecations');
    $this->deprecations = isset($deprecations[$function]) ? $deprecations[$function] : array();
  }

  /**
   * Store a file and line number of the call site of a deprecated function.
   *
   * If the file already has a deprecated function in it and a differe line number,
   * the line number will be appended (i.e. the user has called it multiple times).
   *
   * @param string $file the name of the file
   * @param string $line the line number of the call site
   */
  public function push($file, $line) {
    $line_numbers = isset($this->deprecations[$file]) ? $this->deprecations[$file] : array();
    array_push($line_numbers, $line);
    $this->deprecations[$file] = array_unique($line_numbers);

    $this->update($this->deprecations);
  }

  /**
   * Returns all of the deprecations and their call sites
   *
   * @return array
   */
  public static function all() {
    return get_option('shareaholic_deprecations') ? get_option('shareaholic_deprecations') : array();
  }

  /**
   * Destroy all deprecation warnings. This is called as early
   * as possible in wordpress loading so that if someone has
   * removed a deprecated function, the warning does not stick
   * around. Because this occurs *before* the theme is set up,
   * those warnings will get relogged.
   */
  public static function destroy_all(){
    delete_option('shareaholic_deprecations');
  }

  /**
   * Updates the wordpress option.
   *
   * @param array $function_calls key of the file name and the value
   *                              is an array of the line numbers
   */
  private function update($function_calls) {
    $deprecations = get_option('shareaholic_deprecations');
    $deprecations[$this->function] = $function_calls;
    update_option('shareaholic_deprecations', $deprecations);
  }

}

?>