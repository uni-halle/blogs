<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_top_domains extends WYSIJA_module_statistics_model {

	/**
	 * Decide what "sent" means. TRUE = total of sent newsletters. FALSE = total of newsletters whose statuses are "sent"
	 * @var type
	 */
	public $show_sent_as_total_of_sent_newsletters = true;

	protected static $data_view;

	/**
	 * Decide if we count clicks based on email+user OR email+user+link+number_clicks
	 * @var boolean
	 */
	protected $unique_click = true; // True: 1 email - 1 user => 1 click; False: 1 email - 1 user => 1 or more than 1 clicks; based on number of clicks on every links in a same email

	/**
	 * Get top domains
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

	public function get_top_domains($top = 5, $from_date = NULL, $to_date = NULL, $order_by = WYSIJA_module_statistics::ORDER_BY_SENT, $order_direction = WYSIJA_module_statistics::ORDER_DIRECTION_DESC) {
		// generate data view
		$table_name = $this->generate_data_view($from_date, $to_date);

		// prepare queries
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
				$order_column = $this->show_sent_as_total_of_sent_newsletters ? 'total' : 'sent';
				break;
		}
		$order		= !empty($order_column) ? ' ORDER BY '.$order_column.' '.$direction : '';
		$limit		= !empty($top) ? ' LIMIT 0, '.(int)$top : '';
		$query		= 'SELECT * FROM `'.$table_name.'`'.$order.$limit;

		// execute
		$data = array( );
		$data['domains'] = $this->get_results($query);
		$data['count']   = count($data['domains']) < (int)$top ? count($data['domains']) : $this->count_domains($table_name);
		return $data;
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
		$table_name = $this->get_table_name(array_merge(func_get_args(), array( $this->unique_click ))); // main, cached table
		$temp_table_name_1 = '[wysija]std0_'.time(); // Table 1 - which is similar to [wysija]email_user_stat but group / count (open, sent) by user
		$temp_table_name_2 = '[wysija]std1_'.time(); // Table 2 - combine from user count (opens, sent) + user.domain
		$temp_table_name_3 = '[wysija]std2_'.time(); // Table 3 - which is similar to [wysija]email_user_url but group / count (clicks) by user
		$temp_table_name_4 = '[wysija]std3_'.time(); // Table 4 - combine from user count (clicks) + user.domain
		// cached table
		$queries_create_table[] = '
                CREATE TABLE `'.$table_name.'` (
		  `domain_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `domain` varchar(255) NOT NULL,
                  `sent` int(11) NOT NULL,
                  `opens` int(11) NOT NULL,
                  `clicks` int(11) NOT NULL,
		  `total` int(11) NOT NULL,
                  PRIMARY KEY (`domain_id`)
                )
            ';

		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_1.'` (
              `user_id` int(10) unsigned NOT NULL,
              `sent` int(10) unsigned NOT NULL,
              `opens` int(10) unsigned DEFAULT NULL,
              PRIMARY KEY (`user_id`)
              )';

		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_2.'` (
		`domain_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `domain` varchar(255) NOT NULL,
                `sent` int(11) NOT NULL,
                `opens` int(11) NOT NULL,
                PRIMARY KEY (`domain_id`)
            )';

		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_3.'` (
              `user_id` int(10) unsigned NOT NULL,
              `clicks` int(10) unsigned DEFAULT NULL,
              PRIMARY KEY (`user_id`)
              )';

		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_4.'` (
		`domain_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `domain` varchar(255) NOT NULL,
                `clicks` int(11) NOT NULL,
                PRIMARY KEY (`domain_id`)
            )';


		// build table 1, based on [wysija]email_user_stat
		$sql_1_count_if_opens = array( 'eus.`status` = 1', 'eus.`opened_at` IS NOT NULL' );
		$sql_1_count_if_sent = array( 'eus.`status` = 0', 'eus.`sent_at` IS NOT NULL' );

		if (!empty($from)) {
			$sql_1_count_if_opens[] = 'eus.`opened_at` >= '.$from;
			$sql_1_count_if_sent[]  = 'eus.`sent_at` >= '.$from;
		}
		else {
			$sql_1_count_if_opens[] = 'eus.`opened_at` > 0';
			$sql_1_count_if_sent[]  = 'eus.`sent_at` > 0';
		}

		if (!empty($to)) {
			$sql_1_count_if_opens[] = 'eus.`opened_at` < '.$to;
			$sql_1_count_if_sent[]  = 'eus.`sent_at` < '.$to;
		}

		$queries_insert_data[] = '
            INSERT
                `'.$temp_table_name_1.'` (`user_id`, `sent`, `opens`)
                SELECT
                    user_id,
                    COUNT( IF('.implode(' AND ', $sql_1_count_if_sent).', eus.`sent_at`, NULL)) AS `sent`,
                    COUNT( IF('.implode(' AND ', $sql_1_count_if_opens).', eus.`opened_at`, NULL)) AS `opens`
                FROM
                    [wysija]email_user_stat eus
                GROUP BY
                    eus.user_id;
                ;
            ';

		// build table 2, based on table 1 + [wysija].user
		$queries_insert_data[] = '
            INSERT
                `'.$temp_table_name_2.'` (`domain`, `sent`, `opens`)
                SELECT
                    u.`domain`,
                    SUM(temp.`sent`) AS `sent`,
                    SUM(temp.`opens`) AS `opens`
                FROM
                    `[wysija]user` u
                LEFT JOIN
                    `'.$temp_table_name_1.'` temp ON u.`user_id` = temp.`user_id`
                GROUP BY
                    u.`domain`
                ;
            ';

		// build table 3, based on [wysija]email_user_url
		if ($this->unique_click) {// One email - One user => One click
			$sql_3_count_if_clicks = array( 'eul.`clicked_at` IS NOT NULL' );
			if (!empty($from)) {
				$sql_3_count_if_clicks[] = 'eul.`clicked_at` >= '.$from;
			}
			else {
				$sql_3_count_if_clicks[] = 'eul.`clicked_at` > 0';
			}

			if (!empty($to)) {
				$sql_3_count_if_clicks[] = 'eul.`clicked_at` < '.$to;
			}
			// Here, we group by user_id, but count each email_id once
			$queries_insert_data[]   = '
		    INSERT
			`'.$temp_table_name_3.'`(`user_id`, `clicks`)
			SELECT
			    eul.`user_id`,
			    COUNT( DISTINCT IF('.implode(' AND ', $sql_3_count_if_clicks).', eul.`email_id`, NULL)) AS `clicks`
			FROM
			    `[wysija]email_user_url` eul
			GROUP BY eul.`user_id`
		    ;
		';
		}
		else {// Count all clicks (number clicks on all links in a single email)
			$sql_where_3 = array( 1 );
			if (!empty($from)) {
				$sql_where_3[] = 'eul.clicked_at >= '.$from;
			}
			if (!empty($to)) {
				$sql_where_3[]		 = 'eul.clicked_at < '.$to;
			}
			$queries_insert_data[] = '
		    INSERT
			`'.$temp_table_name_3.'`(`user_id`, `clicks`)
			SELECT
			    eul.user_id,
			    SUM( IF(eul.number_clicked is null OR eul.number_clicked <=0,0,eul.number_clicked)) as clicks
			FROM
			    [wysija]email_user_url eul
			WHERE '.implode(' AND ', $sql_where_3).'
			GROUP BY eul.user_id
		    ;
		';
		}
		// Build table 4, based on table 3 + [wysija].user
		$queries_insert_data[] = '
                INSERT
                    `'.$temp_table_name_4.'`(`domain`, `clicks`)
                    SELECT
                        u.`domain`,
                        COUNT(temp.`clicks`) AS `clicks`
                    FROM
                        `[wysija]user` u
                    LEFT JOIN
                        `'.$temp_table_name_3.'` temp ON u.`user_id` = temp.`user_id`
                    GROUP BY u.domain
                ;
            ';

		$queries_insert_data[] = '
            INSERT `'.$table_name.'` (`domain`, `sent`, `opens`, `clicks`,`total`)
                SELECt
                        t1.`domain`,
                        t1.`sent`,
                        t1.`opens`,
                        t2.`clicks`,
			IF(t1.`sent`, t1.`sent`, 0) + IF(t1.`opens`, t1.`opens`, 0) + IF(t2.`clicks`, t2.`clicks`,0) as `total`
                FROM
                        `'.$temp_table_name_2.'` t1
                JOIN
                        `'.$temp_table_name_4.'` t2 ON t1.domain = t2.domain
            ';

		$this->generate_table($table_name, $queries_create_table, $queries_insert_data);
		return $table_name;
	}

	/**
	 * Count available domains in database
	 * @table_name $table name of "data view"
	 * @return int
	 */
	protected function count_domains($table_name) {
		$result = $this->get_results('SELECT COUNT(*) AS `count_domain` FROM `'.$table_name.'`');
		return $result[0]['count_domain'];
	}

}