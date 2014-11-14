<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_top_links extends WYSIJA_module_statistics_model {

	/**
	 * Get top links
	 * @param int $from_date (not implemented yet)
	 * @param int $to_date (not implemented yet)
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
	public function get_top_links($top = 100, $from_date = NULL, $to_date = NULL, $order_by = WYSIJA_module_statistics::ORDER_BY_CLICK, $order_direction = WYSIJA_module_statistics::ORDER_DIRECTION_DESC) {
		$data = array( ); // returned data
		// get top clicks
		$top_links = $this->get_top_links_by_click($top, $from_date, $to_date, $order_direction);

		// get email title, lists
		$email_ids = array( );
		foreach ($top_links as $key => $link) {
			if (!empty($link['email_ids'])) {
				$tmp						  = explode(',', $link['email_ids']);
				$top_links[$key]['email_ids'] = $tmp;
				$email_ids					= array_merge($email_ids, $tmp);
			}
		}
		$unique_email_ids			 = array_values(array_unique($email_ids));
		$email_lists				  = $this->get_lists_by_email($unique_email_ids);

		// combine emails + lists into links
		foreach ($top_links as $key => $link) {
			$top_links[$key]['emails'] = array( ); // list of emails which the current link belongs to
			$top_links[$key]['lists'] = array( ); // list of subscriber lists which all emails of the current link were sent to
			if (empty($link['email_ids']))
				continue;
			foreach ($link['email_ids'] as $email_id) {
				if (!empty($email_lists[$email_id])) {
					$top_links[$key]['emails'][$email_id] = $email_lists[$email_id];
					$top_links[$key]['lists'] += $email_lists[$email_id]['lists'];
				}
			}
			unset($top_links[$key]['email_ids']);
		}


		// combine data
		$data['count'] = count($top_links) < (int)$top ? count($top_links) : $this->count_top_links_by_click($from_date, $to_date);
		$data['links'] = $top_links;
		return $data;
	}

	/**
	 * Get top links, based on their clicks' number
	 * @param type $from_date (not implemented)
	 * @param type $to_date (not implemented)
	 * @param type $top
	 * @return array
	 *  [3] => Array
	  (
	  [clicks] => 3
	  [url_id] => 3
	  )
	 */
	protected function get_top_links_by_click($top = 5, $from_date = NULL, $to_date = NULL, $order_direction = WYSIJA_module_statistics::ORDER_DIRECTION_DESC) {
		// prepare
		$table_name = $this->generate_data_view($from_date, $to_date);
		$direction  = (!empty($order_direction) && $order_direction == WYSIJA_module_statistics::ORDER_DIRECTION_DESC) ? 'DESC' : 'ASC';
		$order	  = ' ORDER BY `clicks` '.$direction;
		$limit	  = !empty($top) ? ' LIMIT 0, '.(int)$top : '';
		$query	  = 'SELECT * FROM `'.$table_name.'`'.$order.$limit;

		// query
		$result = $this->get_results($query);

		// format data, consider user_id as key
		$tmp = array( );
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$tmp[$value['url_id']] = $value;
			}
		}
		return $tmp;
	}

	/**
	 * preview "data view". Data View is a concept of MS SQL in which  we generate a "table" based on other existing tables
	 * @todo: create stats_top_domains table; implement cache
	 */
	protected function generate_data_view($from_date = NULL, $to_date = NULL) {
		$helper_toolbox = WYSIJA::get('toolbox', 'helper');
		$from		   = !empty($from_date) ? $helper_toolbox->localtime_to_servertime(strtotime($from_date)) : NULL;
		$to			 = !empty($to_date) ? $helper_toolbox->localtime_to_servertime(strtotime($to_date)) : NULL;

		$queries_insert_data = array( );
		$queries_create_table = array( );
		$table_name		= $this->get_table_name(func_get_args()); // main, cached table
		$temp_table_name_1 = '[wysija]stl1_'.time();

		$queries_create_table[] = '
            CREATE TABLE `'.$table_name.'` (
                    `url_id` int(11) NOT NULL,
                    `url` TEXT NULL,
                    `clicks` int(11) NOT NULL,
                    `email_ids` VARCHAR(255) NULL,
                    PRIMARY KEY (`url_id`)
            )';


		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_1.'` (
                    `url_id` int(11) NOT NULL,
                    `clicks` int(11) NOT NULL,
                    `email_ids` VARCHAR(255) NULL,
                    PRIMARY KEY (`url_id`)
            )';

		$sql_where = array( 'clicked_at > 0' );
		if (!empty($from)) {
			$sql_where[] = '`clicked_at` >= '.$from;
		}
		if (!empty($to)) {
			$sql_where[] = '`clicked_at` < '.$to;
		}
		// Remove special links (View in browser, Subscription, Unsubscribe)
		$sql_where[] = '`url_id` NOT IN (SELECT `url_id` FROM [wysija]url WHERE `url` LIKE "[%]")';

		$queries_insert_data[] = '
            INSERT
                `'.$temp_table_name_1.'`(`url_id`, `clicks`, `email_ids`)
                SELECT
                        `url_id`,
                        SUM(number_clicked) AS clicks,
                        GROUP_CONCAT(DISTINCT email_id) AS`email_ids`
                FROM
                        `[wysija]email_user_url`
                WHERE '.implode(' AND ', $sql_where).'
                GROUP BY `url_id`;
            ';

		$queries_insert_data[] = '
            INSERT INTO `'.$table_name.'` (`url_id`, `url`, `clicks`, `email_ids`)
                SELECT
                        t1.`url_id`,
                        u.`url`,
                        t1.`clicks`,
                        t1.`email_ids`
                FROM `'.$temp_table_name_1.'` t1
                JOIN `[wysija]url` u ON u.`url_id` = t1.`url_id`;
                ';
		$this->generate_table($table_name, $queries_create_table, $queries_insert_data);
		return $table_name;
	}

	/**
	 *
	 * @param array $email_ids
	 * @return array
	  url_id => Array
	  (
	  [subject] => email subject
	  [lists] => Array
	  (
	  list_id => list_name
	  )

	  )
	 */
	protected function get_lists_by_email(Array $email_ids) {
		if (empty($email_ids))
			return $email_ids;
		$query = '
            SELECT
                e.`email_id`,
                e.`subject`,
                GROUP_CONCAT(l.`list_id`,"--", l.`name` SEPARATOR "::") AS `lists`
            FROM
                `[wysija]email` e
            JOIN
                `[wysija]campaign_list` cl ON cl.`campaign_id` = e.`campaign_id`
            JOIN
                `[wysija]list` l ON l.`list_id` = cl.`list_id`
            WHERE e.`email_id` IN ('.implode(', ', $email_ids).')
            GROUP BY e.`email_id`
            ';

		$result = $this->get_results($query);

		// format data, consider user_id as key, and lists as id_list => list_name
		$tmp = array( );
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$tmp_list = array( );
				$lists = explode('::', $value['lists']);
				foreach ($lists as $list) {
					$_list				   = explode('--', $list);
					$tmp_list[$_list['0']]   = $_list[1];
				}
				$value['lists']		  = $tmp_list;
				$tmp[$value['email_id']] = $value;
				unset($tmp[$value['email_id']]['email_id']);
			}
		}
		return $tmp;
	}

	/**
	 * Count top subscribers, based on their clicks' number
	 * @param type $from_date (not implemented)
	 * @param type $to_date (not implemented)
	 * @return int
	 */
	protected function count_top_links_by_click($from_date = NULL, $to_date = NULL) {
		$helper_toolbox = WYSIJA::get('toolbox', 'helper');
		$sql_where	  = array( 'clicked_at > 0' );
		if (!empty($from_date))
			$sql_where[] = 'clicked_at >= '.$helper_toolbox->localtime_to_servertime(strtotime($from_date));
		if (!empty($to_date))
			$sql_where[] = 'clicked_at < '.$helper_toolbox->localtime_to_servertime(strtotime($to_date));
		$query	   = '
            SELECT
                COUNT(DISTINCT url_id) as `count_top_links`
            FROM [wysija]email_user_url
            WHERE '.implode(' AND ', $sql_where)
		;

		$result = $this->get_results($query);
		return $result[0]['count_top_links'];
	}

}