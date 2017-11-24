<?php

namespace WidgetWrangler;

/**
 * Class WidgetWranglerExtras
 * @package WidgetWrangler
 */
class Extras {

	/**
	 * Simple wrapper to retrieve global $wpdb object with local modifications
	 *
	 * @return \wpdb
	 */
	public static function db() {
		global $wpdb;
		$wpdb->ww_extras_table = $wpdb->prefix."ww_extras";

		return $wpdb;
	}

	/**
	 * Make sure the ww_extras table exists
	 *
	 * @return array
	 */
	public static function ensureTable() {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$db = self::db();
		$sql = "CREATE TABLE {$db->ww_extras_table} (
		  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(32) NOT NULL,
		  `variety` varchar(32) DEFAULT NULL,
		  `extra_key` varchar(32) DEFAULT NULL,
		  `data` text NOT NULL,
		  `widgets` text NOT NULL,
		  UNIQUE KEY id (id)
		);";
		return dbDelta($sql);
	}

	/**
	 * Get data from the ww_extras table
	 *
	 * @param array $where
	 *   - key value pairs for extras database table
	 *
	 * @param int|string $limit
	 *   - number of items to return
	 *   - can be 'all' to set no limit
	 *
	 * @return object|array|false
	 *   - if $limit === 1, return a single object
	 *   - else, return an array of objects
	 *   - false on no results
	 */
	public static function get($where, $limit = 1){
		$db = self::db();

		// where `type` = '%s' AND `variety` => '%s' AND `key` = '%s'
		$where_string = '';
		$limit_string = '';

		$i = 1;
		foreach ($where as $k => $v){
			$where_string.= "`$k` = '%s'";
			if ($i < count($where)){
				$where_string.= " AND ";
			}
			$i++;
		}

		if ($limit != 'all'){
			$limit_string = "LIMIT ".$limit;
		}

		$sql = "SELECT * FROM {$db->ww_extras_table} WHERE {$where_string} ORDER BY `id` ASC {$limit_string}";

		if ($extras = $db->get_results($db->prepare($sql, array_values($where)))){

			// unserialize
			foreach($extras as $i => $extra){
				$extras[$i]->data = unserialize($extra->data);
				$extras[$i]->widgets = unserialize($extra->widgets);
			}

			if ((int) $limit === 1){
				// return single row of data as object
				return array_pop($extras);
			}
			// return all data as array of objects
			return $extras;
		}

		// failure
		return false;
	}

	/**
	 * Wrapper for wpdb->insert
	 *  - ensure data is serialized
	 *
	 * @param (array) - ww_extra data that needs to be inserted into the db.
	 *
	 * @return int|false
	 *   - as $wpdb->insert.  false on failure
	 */
	public static function insert($data) {
		$db = self::db();

		// make sure this is not an object
		$data = (array) $data;

		// handle data types
		if ( !empty( $data['data'] ) ) {
			$data['data'] = maybe_serialize($data['data']);
		}
		if ( isset( $data['widgets'] ) && is_array( $data['widgets'] ) ) {
			$data['widgets'] = Admin::serializeWidgets( $data['widgets'] );
		}

		$db->insert( $db->ww_extras_table, $data );
		return $db->insert_id;
	}

	/**
	 * Wrapper for wpdb->update
	 *  - ensure data is serialized
	 *
	 * @param (array) - $data according to table structure
	 * @param (array) - $where according to table structure
	 *
	 * @return int|false
	 *   - as $wpdb->update, number of rows if successful, false on failure
	 */
	public static function update($data, $where) {
		$db = self::db();

		// make sure this is not an object
		$data = (array) $data;

		// handle data types
		if ( !empty( $data['data'] ) ) {
			$data['data'] = maybe_serialize($data['data']);
		}
		if ( isset( $data['widgets'] ) && is_array( $data['widgets'] ) ) {
			$data['widgets'] = Admin::serializeWidgets( $data['widgets'] );
		}

		return $db->update($db->ww_extras_table, $data, $where);
	}

	/**
	 * Wrapper for wpdb->delete
	 *
	 * @param (array) - $where according to table structure
	 *
	 * @return int|false
	 *   - as $wpdb->delete, number of rows if successful, false on failure
	 */
	public static function delete($where) {
		$db = self::db();
		return $db->delete($db->ww_extras_table, $where);
	}
}
