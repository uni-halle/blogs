<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_newsletter extends WYSIJA_module_statistics_model {

	/**
	 * Store email status (output of $this->get_email_status())
	 * @var type
	 */
	protected static $emails_status = array( );

	/**
	 * Get and group by status of a specific newsletter which was sent to subscribers
	 * @param int $user_id
	 * @return array list of emails, group by status. It contains an empy list, or list of one or more status
	 * array(
	 *  status => emails count, // status: -3: inqueue, -2:notsent, -1: bounced, 0: sent, 1: open, 2: clicked, 3: unsubscribed
	 *  status => emails count,
	 *  ...
	 *  status => emails count
	 * )
	 */
	public function get_email_status($email_id) {
		if (!isset(self::$emails_status[$email_id])) {
			// get stats email status
			$query = '
                SELECT
                    count(`email_id`) as emails,
                    `status`
                FROM
                    `[wysija]email_user_stat`
                WHERE `email_id` = '.(int)$email_id.'
                GROUP BY `status`'
			;
			self::$emails_status[$email_id] = $this->indexing_dataset_by_field('status', $this->get_results($query), false, 'emails');
		}
		return self::$emails_status[$email_id];
	}

	/**
	 *
	 * @param int $user_id
	 * @return int a number of received / sent newsletters to a specific user
	 */
	public function get_emails_count($email_id) {
		// get emails group by status
		$count		 = 0;
		$emails_status = $this->get_email_status($email_id); // we don't need to write a separated sql query here, reduce 1 sql request
		if (empty($emails_status))
			return $count;
		foreach ($emails_status as $emails)
			$count += $emails;
		return $count;
	}

	/**
	 * Get top links of a newsletters
	 * @param type $email_id
	 * @return type
	 */
	public function get_top_links($email_id, $top = 5, $order_direction = 'DESC') {
		$limit = !empty($top) ? 'LIMIT 0, '.$top : '';
		$query = '
                SELECT
                    euu.`email_id`,
                    u.`url_id`,
                    u.`url`,
                    SUM(`number_clicked`) AS `number_clicked`
                FROM
                    [wysija]email_user_url euu
                JOIN
                    [wysija]url u ON euu.url_id = u.url_id
                WHERE
                    euu.email_id = '.(int)$email_id.'
                GROUP BY
                    u.`url_id`
                ORDER BY `number_clicked` '.$order_direction.'
                '.$limit.'

            ';
		return $this->get_results($query);
	}

}