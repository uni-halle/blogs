<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_new_subscribers extends WYSIJA_module_statistics_model {

	/**
	 * Get new subscribers, based on lists
	 * @param int $from_date
	 * @param int $to_date
	 * @return array associated array of
	 *  'count' => int,
	 *  'subscribers'=>array(array(
	 *  - id
	  - email
	  - firstname
	  - lastname
	  - open
	  - click
	  - lists => array(list_id => list_name)
	  - sub_date
	 * ))
	 */
	public function get_new_subscribers($from_date = NULL, $to_date = NULL, $group_by = WYSIJA_module_statistics::GROUP_BY_DATE) {
		// init
		$table_name	 = $this->get_table_name(func_get_args());
		$table_name_2 = $table_name.'_2';
		$helper_toolbox = WYSIJA::get('toolbox', 'helper');
		$sql_where	  = array( 'sub_date > 0' );
		$sql_where_total	  = array( 'created_at > 0' );
		if (!empty($from_date)) {
			$_from_date = $helper_toolbox->localtime_to_servertime(strtotime($from_date));
			$sql_where[] = 'sub_date >= '.$_from_date;
			$sql_where_total[] = 'created_at >= '.$_from_date;
		}
		if (!empty($to_date)) {
			$_to_date = $helper_toolbox->localtime_to_servertime(strtotime($to_date));
			$sql_where[] = 'sub_date < '.$_to_date;
			$sql_where_total[] = 'created_at < '.$_to_date;
		}



		// prepare queries
		$query_create_table = '
            CREATE TABLE `'.$table_name.'` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `list_id` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `time` varchar(10) NOT NULL,
                `count` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `time` (`time`)
            ) DEFAULT CHARSET=utf8;
            ';
		$query_create_table_2 = '
            CREATE TABLE `'.$table_name_2.'` (
                `new_subscribers` int(11) NOT NULL
            );
            ';
		$query_insert_data_2 = '
			INSERT INTO `'.$table_name_2.'` (`new_subscribers`)
				SELECT
					COUNT(*) as `new_subscribers`
				FROM `[wysija]user` WHERE '.implode(' AND ', $sql_where_total);

		$query_insert_data = '
            INSERT INTO `'.$table_name.'` (`list_id`, `name`, `time`, `count`)
            SELECT
               `list_id`,
               `name`,
               `time`,
               COUNT(*) AS `count`
            FROM
                (SELECT
                    l.`list_id`,
                    l.`name`,
                    DATE_FORMAT(FROM_UNIXTIME(sub_date), "'.$this->get_date_time_format($group_by).'") AS `time`
                FROM
                    [wysija]user_list ul
                JOIN
                    [wysija]list l ON ul.`list_id` = l.`list_id`
                WHERE '.implode(' AND ', $sql_where).'
                ORDER BY
                    `time` ASC
                ) AS `temp_table`

            GROUP BY `time`, `list_id`
            ORDER BY `time`
            ';

		// execute queries
		$this->generate_table($table_name, array( $query_create_table ), array( $query_insert_data ));
		$query  = 'SELECT * FROM `'.$table_name.'` ORDER BY `time`, `name`';
		$result = $this->get_results($query);

		$output = array(
			'total' => 0,
			'time' => $result
		);
		if (!empty($result)) {
			$output['time'] = $this->group_data_by_time($result);

			$this->generate_table($table_name_2, array( $query_create_table_2 ), array( $query_insert_data_2 ));
			$query_total_of_new_subscribers = 'SELECT `new_subscribers` FROM `'.$table_name_2.'`';
			$result_total = $this->get_results($query_total_of_new_subscribers);
			if (!empty($result_total))
				$output['total'] = $result_total[0]['new_subscribers'];
		}
		return $output;
	}

	/**
	 * format data, group lists by time
	 * @param type $records stats records
	 */
	protected function group_data_by_time($records) {
		$output = array( ); // output
		$lists = array( ); // array of list_id => list_name
		$temp_time	 = null;
		$temp_item	 = null;
		$temp_list_ids = array( );

		foreach ($records as $key => $value) {
			if (empty($lists[$value['list_id']]))
				$lists[$value['list_id']] = $value['name'];
		}
		$list_ids				 = array_keys($lists);

		// group data by time
		foreach ($records as $key => $value) {
			if ($value['time'] != $temp_time) {
				if ($temp_time !== null) {
					// at a certain time, if a list does not have data, add a default = 0
					$diff_list_ids			   = array_diff($list_ids, $temp_list_ids);
					if (!empty($diff_list_ids))
						foreach ($diff_list_ids as $list_id)
							$temp_item[$lists[$list_id]] = 0;
					$output[]					= $temp_item;
				}

				$temp_item = array( 'time'		 => $value['time'] );
				$temp_time	 = $value['time'];
				$temp_list_ids = array( );
			}
			$temp_item[$value['name']]   = $value['count'];
			$temp_list_ids[]			 = $value['list_id'];
		}
		// add the last temp_item
		$diff_list_ids			   = array_diff($list_ids, $temp_list_ids);
		if (!empty($diff_list_ids))
			foreach ($diff_list_ids as $list_id)
				$temp_item[$lists[$list_id]] = 0;
		$output[]					= $temp_item;
		return $output;
	}

	/**
	 * Get date time format, base on a type of group by (date, month, year, etc)
	 * @param type $group_by a const of class class Stats_group_by
	 * @return string date_time format
	 * @link http://www.w3schools.com/sql/func_date_format.asp
	 */
	protected function get_date_time_format($group_by) {
		// we are using %m (instead of %c), %d (instead of %e) to make sure:sorting by date works correctly
		switch ($group_by) {
			case WYSIJA_module_statistics::GROUP_BY_YEAR:
				$date_time_format = '%Y,01,01'; // year, month, day, hours, minutes, seconds, milliseconds
				break;
			case WYSIJA_module_statistics::GROUP_BY_MONTH:
				$date_time_format = '%Y,%m,01'; // year, month, day, hours, minutes, seconds, milliseconds
				break;
			case WYSIJA_module_statistics::GROUP_BY_DATE:
			default:
				$date_time_format = '%Y,%m,%d'; // year, month, day, hours, minutes, seconds, milliseconds
				break;
		}
		return $date_time_format;
	}

}