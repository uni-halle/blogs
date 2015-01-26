<?php
/**
 * Holds the ShareaholicCron class.
 *
 * @package shareaholic
 */

/**
 * This class will contain all the cron jobs executed by this plugin
 *
 * @package shareaholic
 */
class ShareaholicCron {

  const TRANSIENT_SCHEDULE = 'hourly';

  /**
   * Schedules the cron jobs if it does not exist
   */
  public static function activate() {
    if (!wp_next_scheduled('shareaholic_remove_transients_hourly')) {
      // schedule the first occurrence 1 min from now
      wp_schedule_event(
        time() + 60, self::TRANSIENT_SCHEDULE, 'shareaholic_remove_transients_hourly'
      );
      ShareaholicUtilities::log('Shareaholic is now scheduled');
    } else {
      ShareaholicUtilities::log('Shareaholic is already scheduled');
    }
  }

  /**
   * Remove scheduled cron jobs created by Shareaholic
   */
  public static function deactivate() {
    if (wp_next_scheduled('shareaholic_remove_transients_hourly')) {
      wp_clear_scheduled_hook('shareaholic_remove_transients_hourly');
      ShareaholicUtilities::log('Shareaholic schedule cleared');
    } else {
      ShareaholicUtilities::log('no need to clear nonexistent Shareaholic schedule');
    }
  }

  /**
   * A job that clears up the shareaholic share counts transients
   */
  public static function remove_transients() {
    global $wpdb;
    $older_than = time() - (60 * 60); // older than an hour ago

    ShareaholicUtilities::log('Start of Shareaholic transient cleanup');

    $query = "SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_timeout\_shr\_api\_res-%%' AND option_value < %s LIMIT 5000";
    $transients = $wpdb->get_col($wpdb->prepare($query, $older_than));

    $options_names = array();
    foreach($transients as $transient) {
      $options_names[] = esc_sql('_transient_' . $transient);
      $options_names[] = esc_sql('_transient_timeout_' . $transient);
    }
    if ($options_names) {
      $options_names = "'" . implode("','", $options_names) . "'";
      $result = $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name IN ({$options_names})");

      if (!$result) {
        ShareaholicUtilities::log('Transient Query Error!!!');
      }
    }

    ShareaholicUtilities::log('End of Shareaholic transient cleanup');
  }


}