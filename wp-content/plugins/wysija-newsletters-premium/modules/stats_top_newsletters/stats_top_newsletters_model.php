<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_top_newsletters extends WYSIJA_module_statistics_model {

	/**
	 * Decide what "sent" means. TRUE = total of sent newsletters. FALSE = total of newsletters whose statuses are "sent"
	 * @var type
	 */
	public $show_sent_as_total_of_sent_newsletters = true;

	protected static $data_view;

	/**
	 * Decide how to collect top newsletters, by total or by percentage
	 * @var type
	 */
	protected $sort_by_percentage = true;

	/**
	 * Filter by sent date + open/click date (TRUE) OR by open/click only (FALSE)
	 * @var type
	 */
	protected $filter_by_sent_date = true;

	/**
	 * the shortcode of the link unsubscribe
	 * @var string
	 */
	protected $unsubscribe_link_shortcode = '[unsubscribe_link]';

	/**
	 * Get top newsletters
	 * @param array $params Input params Array( <br/>
	 *       [top] => int <br/>
	 *       [from] => int <br/>
	 *       [to] => int <br/>
	 *       [order_by] => int <br/>
	 *       [order_direction] => int <br/>
	 *		 [email_type] => int <br /> 0 = built-in email, 1 =
	 * @return Array <br/>
	 *       [emails] => Array <br/>
	 *       ( <br/>
	 * 		[0] => Array <br/>
	 * 		( <br/>
	 * 		    [email_id] => int <br/>
	 * 		    [subject] => string <br/>
	 * 		    [sent] => int <br/>
	 * 		    [opens] => int <br/>
	 * 		    [clicks] => int <br/>
	 * 		    [lists] => Array( <br/>
	 * 			list_id1 => list_name, <br/>
	 * 			list_id2 => list_name2 <br/>
	 * 			) <br/>
	 * 		    [sent_at] => int <br/>
	 * 		) <br/>
	 * 		[1] => Array <br/>
	 * 		( <br/>
	 * 		    [email_id] => int <br/>
	 * 		    [subject] => string <br/>
	 * 		    [sent] => int <br/>
	 * 		    [opens] => int <br/>
	 * 		    [clicks] => int <br/>
	 * 		    [lists] => Array( <br/>
	 * 			list_id1 => list_name, <br/>
	 * 			list_id2 => list_name2 <br/>
	 * 			) <br/>
	 * 		    [sent_at] => int <br/>
	 * 		) <br/>
	 *  <br/>
	 * 		[n] => Array <br/>
	 * 		( <br/>
	 * 		    [email_id] => int <br/> <br/>
	 * 		    [subject] => string <br/>
	 * 		    [sent] => int <br/>
	 * 		    [opens] => int <br/>
	 * 		    [clicks] => int <br/>
	 * 		    [total] => int <br/>
	 * 		    [lists] => Array( <br/>
	 * 			list_id1 => list_name, <br/>
	 * 			list_id2 => list_name2 <br/>
	 * 			) <br/>
	 * 		    [sent_at] => int <br/>
	 * 		) <br/>
	 * 	    ) <br/>
	 *       [count] => int <br/>
	 */
	public function get_top_newsletters($top = 5, $from_date = NULL, $to_date = NULL, $order_by = WYSIJA_module_statistics::ORDER_BY_OPEN, $order_direction = WYSIJA_module_statistics::ORDER_DIRECTION_DESC, $email_type = 1) {
		$helper_toolbox = WYSIJA::get('toolbox', 'helper');
		$from		   = !empty($from_date) ? $helper_toolbox->localtime_to_servertime(strtotime($from_date)) : NULL;
		$to			 = !empty($to_date) ? $helper_toolbox->localtime_to_servertime(strtotime($to_date)) : NULL;
		// generate data view
		$table_name	 = $this->generate_data_view($from, $to);

		// prepare queries
		$direction	= (!empty($order_direction) && $order_direction == WYSIJA_module_statistics::ORDER_DIRECTION_DESC) ? 'DESC' : 'ASC';
		$order_column = NULL;
		switch ($order_by) {
			case WYSIJA_module_statistics::ORDER_BY_OPEN:
				$order_column = $this->sort_by_percentage ? 'opens_percent' : 'opens';
				break;

			case WYSIJA_module_statistics::ORDER_BY_CLICK:
				$order_column = $this->sort_by_percentage ? 'clicks_percent' : 'clicks';
				break;

			case WYSIJA_module_statistics::ORDER_BY_UNSUBSCRIBE:
				$order_column = $this->sort_by_percentage ? 'unsubscribes_percent' : 'unsubscribes';
				break;

			case WYSIJA_module_statistics::ORDER_BY_SENT:
			default:
				$order_column = $this->show_sent_as_total_of_sent_newsletters ? 'total' : ($this->sort_by_percentage ? 'sent_percent' : 'sent');
				break;
		}
		$sql_where	= array( );
		$sql_where[] = '`type` = '.(int)$email_type;
		// if we filter by sent_date, let's get all of them first.
		if ($this->filter_by_sent_date) {
			if (!empty($from)) {
				$sql_where[] = '`sent_at` >= '.$from;
			}
			if (!empty($to)) {
				$sql_where[] = '`sent_at` < '.$to;
			}
		}
		$where	   = !empty($sql_where) ? ' WHERE '.implode(' AND ', $sql_where) : '';
		$order	   = !empty($order_column) ? ' ORDER BY '.$order_column.' '.$direction : '';
		$limit	   = !empty($top) ? ' LIMIT 0, '.(int)$top : '';

		$query = '
			SELECT
				`email_id`,
				`sent`,
				`opens`,
				`clicks`,
				`unsubscribes`,
				ROUND(`sent_percent` / 100,2) AS `sent_percent`,
				ROUND(`opens_percent` / 100,2) AS `opens_percent`,
				ROUND(`clicks_percent` / 100,2) AS `clicks_percent`,
				ROUND(`unsubscribes_percent` / 100,2) AS `unsubscribes_percent`,
				`total`,
				`sent_at`
			FROM `'.$table_name.'`'.$where.$order.$limit;

		// get stats of top newsletters
		$emails = $this->get_results($query);

		// get email title, lists, sent_at
		$email_ids = array( );
		foreach ($emails as $email) {
			$email_ids[] = $email['email_id'];
		}

		// get lists which these emails were sent to + subject of email
		$email_lists = $this->get_lists_by_email($email_ids);
		$email_meta  = $this->get_meta_info_by_email($email_ids);

		// combine lists, subject, sent_at into emails
		foreach ($emails as &$email) {
			if (!empty($email_lists[$email['email_id']])) {
				$email = array_merge($email, $email_lists[$email['email_id']]);
			}
			if (!empty($email_meta[$email['email_id']])) {
				$email = array_merge($email, $email_meta[$email['email_id']]);
			}
		}

		$data = array( );
		$data['emails'] = $emails;
		$data['count']  = count($data['emails']) < (int)$top ? count($data['emails']) : $this->count_emails($table_name, $from, $to);
		return $data;
	}

	/**
	 *
	 * @param array $email_ids list of current emails
	 * @return array
	 * 	    email_id => Array
	 *       (
	 * 	      [subject] => string,
	 * 	      [sent_at] => int
	 *       )
	 */
	protected function get_meta_info_by_email($email_ids) {
		if (empty($email_ids))
			return $email_ids;
		$sql_where = array( );
		$sql_where[] = 'e.`email_id` IN ('.implode(', ', $email_ids).')';

		$query = '
            SELECT
                e.`email_id`,
                e.`subject`,
		e.`sent_at`
            FROM
                `[wysija]email` e
            WHERE
		'.implode(' AND ', $sql_where)
		;


		$result = $this->get_results($query);

		// format data, consider email_id as key, and lists as id_list => list_name
		$tmp = array( );
		if (!empty($result)) {
			$helper_mailer	 = WYSIJA::get('mailer', 'helper');
			$helper_shortcodes = WYSIJA::get('shortcodes', 'helper');
			foreach ($result as $value) {
				// Render shortcodes in email's subject
				$email_object	 = (object)$value;
				$helper_mailer->parseSubjectUserTags($email_object);
				$value['subject'] = $helper_shortcodes->replace_subject($email_object);

				$tmp[$value['email_id']] = $value;
				unset($tmp[$value['email_id']]['email_id']);
			}
		}
		return $tmp;
	}

	/**
	 *
	 * @param array $email_ids list of current emails
	 * @return array
	 * 	    email_id => Array
	 *       (
	 * 	      [lists] => Array
	 * 	      (
	 * 	          list_id => list_name
	 * 	      )
	 *       )
	 */
	protected function get_lists_by_email(Array $email_ids) {
		if (empty($email_ids))
			return $email_ids;
		$sql_where = array( );
		$sql_where[] = 'e.`email_id` IN ('.implode(', ', $email_ids).')';

		$query = '
            SELECT
                e.`email_id`,
                GROUP_CONCAT(l.list_id,"--", l.name SEPARATOR "::") AS `lists`
            FROM
                `[wysija]email` e
            JOIN
                `[wysija]campaign_list` cl ON cl.`campaign_id` = e.`campaign_id`
            JOIN
                `[wysija]list` l ON l.`list_id` = cl.`list_id`
            WHERE
		'.implode(' AND ', $sql_where).'
            GROUP BY
		e.`email_id`
            ';


		$result = $this->get_results($query);

		// format data, consider email_id as key, and lists as id_list => list_name
		$tmp = array( );
		if (!empty($result)) {
			foreach ($result as $value) {
				// Prepare lists
				$tmp_list = array( );
				$lists = explode('::', $value['lists']);
				foreach ($lists as $list) {
					$_list				 = explode('--', $list);
					$tmp_list[$_list['0']] = $_list[1];
				}
				$value['lists']		= $tmp_list;

				$tmp[$value['email_id']] = $value;
				unset($tmp[$value['email_id']]['email_id']);
			}
		}
		return $tmp;
	}

	protected function generate_data_view($from = NULL, $to = NULL) {
		if (!empty(self::$data_view)){
			return self::$data_view;
		}
		$queries_insert_data = array( );
		$queries_create_table = array( );
		$params = func_get_args();
		$params[] = '2.7';// since 2.7, we add a new column into $table_name below. By adding this param, we ignore the existing cached table and create a new one.
		$table_name			 = $this->get_table_name($params); // main, cached table
		$temp_table_name_1	  = '[wysija]stn1_'.time(); // Table 1 - which is similar to [wysija]email_user_stat but group / count (open, sent) by email
		$temp_table_name_2	  = '[wysija]stn2_'.time(); // Table 2 - which is similar to [wysija]email_user_url but group / count (clicks) by email
		// cached table

		$queries_create_table[] = '
                CREATE TABLE `'.$table_name.'` (
                  `email_id` INT(10) unsigned NOT NULL,
				  `type` TINYINT(4) unsigned NOT NULL,
                  `sent` INT(11) NOT NULL,
                  `opens` INT(11) NOT NULL,
                  `clicks` INT(11) NOT NULL,
		  `unsubscribes` INT(11) NOT NULL,
                  `sent_percent` INT(11) NOT NULL,
                  `opens_percent` INT(11) NOT NULL,
                  `clicks_percent` INT(11) NOT NULL,
		  `unsubscribes_percent` INT(11) NOT NULL,
		  `total` INT(11) NOT NULL,
		  `sent_at` INT(11) NOT NULL,
                  PRIMARY KEY (`email_id`)
                )
            ';

		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_1.'` (
              `email_id` INT(10) unsigned NOT NULL,
              `sent` INT(10) unsigned NOT NULL,
              `opens` INT(10) unsigned DEFAULT NULL,
              PRIMARY KEY (`email_id`)
              )';




		$queries_insert_data[] = '
            CREATE TEMPORARY TABLE `'.$temp_table_name_2.'` (
              `email_id` int(10) unsigned NOT NULL,
              `clicks` int(10) unsigned DEFAULT NULL,
	      `unsubscribes` INT(10) unsigned DEFAULT NULL,
              PRIMARY KEY (`email_id`)
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
                `'.$temp_table_name_1.'` (`email_id`, `sent`, `opens`)
                SELECT
                    eus.`email_id`,
                    COUNT( IF('.implode(' AND ', $sql_1_count_if_sent).', eus.`sent_at`, NULL)) AS `sent`,
                    COUNT( IF('.implode(' AND ', $sql_1_count_if_opens).', eus.`opened_at`, NULL)) AS `opens`
                FROM
                    `[wysija]email_user_stat` eus
                GROUP BY
                    eus.`email_id`;
                ;
            ';
		// build table 2, based on [wysija]email_user_url
		$sql_2_count_if_clicks = array( 'eul.`clicked_at` IS NOT NULL' );
		$sql_2_count_if_unsubscribes = array( 'eul.`clicked_at` > 0', 'eul.`url_id` = '.$this->get_unsubscribe_url_id() );


		if (!empty($from)) {
			$sql_2_count_if_clicks[]	   = 'eul.`clicked_at` >= '.$from;
			$sql_2_count_if_unsubscribes[] = '`clicked_at` >= '.$from;
		}
		else {
			$sql_2_count_if_clicks[] = 'eul.`clicked_at` > 0';
		}

		if (!empty($to)) {
			$sql_2_count_if_clicks[]	   = 'eul.`clicked_at` < '.$to;
			$sql_2_count_if_unsubscribes[] = '`clicked_at` < '.$to;
		}

		// Here, we group by email_id, but count each user_id once
		$queries_insert_data[] = '
                INSERT
                    `'.$temp_table_name_2.'`
                    SELECT
                        eul.`email_id`,
			COUNT( DISTINCT IF('.implode(' AND ', $sql_2_count_if_clicks).', eul.`user_id`, NULL)) AS `clicks`,
			COUNT( DISTINCT IF('.implode(' AND ', $sql_2_count_if_unsubscribes).', eul.`user_id`, NULL)) AS `unsubscribes`
                    FROM
                        `[wysija]email_user_url` eul
                    GROUP BY
			eul.`email_id`
                ;
            ';
		// The set of $temp_table_name_1 is always greater than or equal to $temp_table_name_2 logically. A user can not click without openning a newsletter first.
		$queries_insert_data[] = '
            INSERT `'.$table_name.'`
                SELECT
                        t1.`email_id`,
						e.`type`,
                        t1.`sent`,
                        t1.`opens`,
                        t2.`clicks`,
						t2.`unsubscribes`,
						0 AS `sent_percent`,
						0 AS `opens_percent`,
						0 AS `clicks_percent`,
						0 AS `unsubscribes_percent`,
						IF(t1.`sent`, t1.`sent`, 0) + IF(t1.`opens`, t1.`opens`, 0) + IF(t2.`clicks`, t2.`clicks`,0) + IF(t2.`unsubscribes`, t2.`unsubscribes`,0) AS `total`,
						e.`sent_at`
                FROM
                        `'.$temp_table_name_1.'` t1
                LEFT JOIN
                        `'.$temp_table_name_2.'` t2 ON t1.`email_id` = t2.`email_id`
				LEFT JOIN
						`[wysija]email` e ON t1.`email_id` = e.`email_id`
            ';
		$queries_insert_data[] = '
			UPDATE `'.$table_name.'`
			SET
				`sent_percent` = ROUND((`sent` / `total`) * 10000, 0),
				`opens_percent` = ROUND((`opens` / `total`) * 10000, 0),
				`clicks_percent` = ROUND((`clicks` / `total`) * 10000, 0),
				`unsubscribes_percent` = ROUND((`unsubscribes` / `total`) * 10000, 0)
			WHERE `total` > 0';
		$this->generate_table($table_name, $queries_create_table, $queries_insert_data);
		self::$data_view = $table_name;
		return self::$data_view;
	}

	/**
	 * Count available domains in database
	 * @table_name $table name of "data view"
	 * @param int $from From date
	 * @param int $to To date
	 * @return int
	 */
	protected function count_emails($table_name, $from = NULL, $to = NULL) {
		$sql_where = array( );
		// if we filter by sent_date, let's get all of them first.
		if ($this->filter_by_sent_date) {
			if (!empty($from)) {
				$sql_where[] = '`sent_at` >= '.$from;
			}
			if (!empty($to)) {
				$sql_where[] = '`sent_at` < '.$to;
			}
		}
		$where	   = !empty($sql_where) ? ' WHERE '.implode(' AND ', $sql_where) : '';
		$result	  = $this->get_results('SELECT COUNT(*) AS `count_emails` FROM `'.$table_name.'`'.$where);
		return $result[0]['count_emails'];
	}

	/**
	 * Get id of unsubscribe link
	 * @return type
	 */
	protected function get_unsubscribe_url_id() {
		$query  = '
	    SELECT `url_id` FROM `[wysija]url` WHERE `url` = "'.$this->unsubscribe_link_shortcode.'"
	    ';
		$result = $this->get_results($query);
		return ($result && !empty($result[0]['url_id']) ? (int)$result[0]['url_id'] : 0);
	}

}