<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_top_subscribers extends WYSIJA_module_statistics_model {

	/**
	 * Get top subscribers, based on clicks and opens
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
	public function get_top_subscribers($top = 5, $from_date = NULL, $to_date = NULL, $order_by = WYSIJA_module_statistics::ORDER_BY_SENT, $order_direction = WYSIJA_module_statistics::ORDER_DIRECTION_DESC) {
		$table_name = $this->generate_data_view($from_date, $to_date);

		$direction	= (!empty($order_direction) && $order_direction == WYSIJA_module_statistics::ORDER_DIRECTION_DESC) ? 'DESC' : 'ASC';
		$order_column = NULL;
		switch ($order_by) {
			case WYSIJA_module_statistics::ORDER_BY_OPEN:
				$order_column = 'opens';
				break;

			case WYSIJA_module_statistics::ORDER_BY_CLICK:
				$order_column = 'clicks';
				break;

			case WYSIJA_module_statistics::ORDER_BY_SENT:
			default:
				$order_column = 'sent';
				break;
		}
		$order		= !empty($order_column) ? ' ORDER BY '.$order_column.' '.$direction : '';
		$limit		= !empty($top) ? ' LIMIT 0, '.(int)$top : '';
		$query		= 'SELECT * FROM `'.$table_name.'`'.$order.$limit;
		// execute
		$data		 = array( );
		$data['subscribers'] = array( );
		$top_subscribers = $this->get_results($query);
		// get user info
		if (!empty($top_subscribers)) {
			foreach ($top_subscribers as $subscriber_record)
				$data['subscribers'][$subscriber_record['user_id']] = $subscriber_record;
			$user_infos										 = $this->get_user_info_by_users(array_keys($data['subscribers']));
			foreach ($user_infos as $user_id => $user_info)
				$data['subscribers'][$user_id]					  = array_merge($data['subscribers'][$user_id], $user_info);
		}
		$data['count']									  = count($data['subscribers']) < (int)$top ? count($data['subscribers']) : $this->count_subscribers($table_name);

		return $data;
	}

	/**
	 * Count available subscribers
	 * @table_name $table name of "data view"
	 * @return int
	 */
	protected function count_subscribers($table_name) {
		$result = $this->get_results('SELECT COUNT(*) AS `count_top_subscribers` FROM `'.$table_name.'`');
		return $result[0]['count_top_subscribers'];
	}

	/**
	 * Get basic info of a list of user ids
	 * @param array $user_ids
	 * @return array(
	 * user_id => array(
	  [firstname] => First Name
	  [lastname] => Last Name
	  [email] => email@domain.com
	  [created_at] => 1371694974
	  [lists] => Array
	  (
	  list_id_1 => List 1
	  list_id_2 => List 2
	  )
	 * ))
	 */
	protected function get_user_info_by_users(Array $user_ids) {
		if (empty($user_ids))
			return $user_ids;
		$query  = '
            SELECT
                A.`user_id`,
                A.`firstname`,
                A.`lastname`,
                A.`email`,
                A.`created_at`,
                GROUP_CONCAT(C.`list_id`,"--", C.`name` SEPARATOR "::") AS `lists`
            FROM `[wysija]user` A
            JOIN `[wysija]user_list` B ON A.user_id = B.user_id
            JOIN `[wysija]list` C ON B.list_id = C.list_id
            WHERE A.`user_id` IN ('.implode(', ', $user_ids).')
            GROUP BY A.`user_id`
            ';
		$result = $this->get_results($query);

		// format data, consider user_id as key, and lists as id_list => list_name
		$tmp = array( );
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$tmp_list = array( );
				$lists = explode('::', $value['lists']);
				foreach ($lists as $list) {
					$_list				  = explode('--', $list);
					$tmp_list[$_list['0']]  = $_list[1];
				}
				$value['lists']		 = $tmp_list;
				$tmp[$value['user_id']] = $value;
				unset($tmp[$value['user_id']]['user_id']);
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
		$temp_table_name_1 = '[wysija]sts1_'.time();
		$temp_table_name_2 = '[wysija]sts2_'.time();

		$queries_create_table[] = '
            CREATE TABLE `'.$table_name.'` (
              `user_id` int(255) NOT NULL,
              `sent` int(11),
              `opens` int(11),
              `clicks` int(11),
              PRIMARY KEY (`user_id`)
            );
        ';
		$queries_insert_data[]  = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_1.'` (
                `user_id` int(11) NOT NULL,
                `sent` int(11) NOT NULL,
                `opens` int(11) NOT NULL,
                PRIMARY KEY (`user_id`)
            )';
		$queries_insert_data[]  = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_2.'` (
                `user_id` int(11) NOT NULL,
                `clicks` int(11) NOT NULL,
                PRIMARY KEY (`user_id`)
            )';

		$sql_where = array( 1 );
		if (!empty($from)) {
			$sql_where[] = 'eus.sent_at >= '.$from;
			$sql_where[] = 'eus.opened_at >= '.$from;
		}
		if (!empty($to)) {
			$sql_where[] = 'eus.sent_at < '.$to;
			$sql_where[] = 'eus.opened_at < '.$to;
		}

		$queries_insert_data[] = '
            INSERT
                `'.$temp_table_name_1.'`(`user_id`, `sent`, `opens`)
                SELECT
                    u.user_id,
                    COUNT( IF(eus.sent_at is null OR eus.sent_at <=0,null,eus.sent_at)) as sent,
                    COUNT( IF(eus.opened_at is null OR eus.opened_at <=0 OR eus.status <=0 ,null,eus.opened_at)) as opens
                FROM
                    [wysija]user u
                LEFT JOIN
                    [wysija]email_user_stat eus ON u.user_id = eus.user_id
                WHERE '.implode(' AND ', $sql_where).'
                GROUP BY
                    u.user_id
                ;
            ';
		$sql_where			 = array( 1 );
		if (!empty($from)) {
			$sql_where[] = 'eul.clicked_at >= '.$from;
		}
		if (!empty($to)) {
			$sql_where[]		   = 'eul.clicked_at < '.$to;
		}
		$queries_insert_data[] = '
                INSERT
                    `'.$temp_table_name_2.'`(`user_id`, `clicks`)
                    SELECT
                        u.user_id,
                        SUM( IF(eul.number_clicked is null OR eul.number_clicked <=0,0,eul.number_clicked)) as clicks
                    FROM
                        [wysija]user u
                    LEFT JOIN
                        [wysija]email_user_url eul ON u.user_id = eul.user_id
                    WHERE '.implode(' AND ', $sql_where).'
                    GROUP BY u.user_id
                ;
            ';
		$queries_insert_data[] = '
            INSERT `'.$table_name.'`
                SELECT
                        t1.user_id,
                        t1.sent,
                        t1.opens,
                        t2.clicks
                FROM
                        `'.$temp_table_name_1.'` t1
                LEFT JOIN
                        `'.$temp_table_name_2.'` t2 ON t1.user_id = t2.user_id
            ';
		$this->generate_table($table_name, $queries_create_table, $queries_insert_data);
		return $table_name;
	}

	/**
	 *
	 * @param int $user_id
	 * @return array list of emails
	 * array(
	 * 1 => array(
	 *  email_id => 1
	 *  subject => 'lorem ipsum'
	 *  sent_at => 123456789
	 * ),
	 * 3 => array(
	 *  email_id => 3
	 *  subject => 'lorem ipsum'
	 *  sent_at => 123456789
	 * ),
	 * 5 => array(
	 *  email_id => 5
	 *  subject => 'lorem ipsum'
	 *  sent_at => 123456789
	 * )
	 * )
	 */
	public function get_emails_by_user_id($user_id, $opened_only = false) {
		$where = array( 1 );
		if ($opened_only) {
			$where[] = 'eus.`opened_at` > 0';
			$where[] = 'eus.`status` > 0';
		}

		$query   = '
            SELECT
                e.`email_id`,
                e.`subject`,
                e.`sent_at`
            FROM
                `[wysija]email_user_stat` eus
            JOIN
                `[wysija]email` e ON e.`email_id` = eus.`email_id` AND eus.`user_id` = '.(int)$user_id.'
            WHERE '.implode(' AND ', $where).'
            ';
		$dataset = $this->get_results($query);
		return $this->indexing_dataset_by_field('email_id', $dataset);
	}

	/**
	 *
	 * @param array $email_ids
	 * @return array list of urls
	 * array(
	 * email_id => array(
	 *  url_id => array(
	 *      'email_id' => 1,
	 *      'url_id' => 1,
	 *      'url' => 'http://...',
	 *      'number_clicked' => 2
	 *   )
	 *  )
	 * )
	 */
	public function get_urls_by_email_ids(Array $email_ids, $user_id = null) {
		if (empty($email_ids))
			return array( );

		$where = array( 1 );
		$where[] = 'euu.`email_id` IN ('.implode(',', $email_ids).')';
		if (!empty($user_id))
			$where[] = 'euu.`user_id` = '.(int)$user_id;
		$query   = '
            SELECT
                euu.`email_id`,
                u.`url_id`,
                u.`url`,
                euu.`number_clicked`
            FROM
                `[wysija]email_user_url` euu
            JOIN
                `[wysija]url` u ON u.`url_id` = euu.`url_id` AND '.implode(' AND ', $where).'
            ORDER BY euu.`email_id`, u.`url`

            ';

		$dataset = $this->get_results($query);
		if (empty($dataset))
			return array( );

		$tmp = array( );
		foreach ($dataset as $record) {
			$tmp[$record['email_id']][] = $record;
		}
		foreach ($tmp as $email_id => $_dataset) {
			$tmp[$email_id] = $this->indexing_dataset_by_field('url_id', $_dataset);
		}
		return $tmp;
	}

}