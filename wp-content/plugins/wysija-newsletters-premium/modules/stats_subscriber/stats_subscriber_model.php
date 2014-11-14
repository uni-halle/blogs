<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_subscriber extends WYSIJA_module_statistics_model {

	/**
	 * Store email status (output of $this->get_email_status_by_user()) by user_id
	 * @var type
	 */
	protected static $emails_status = array( );

	/**
	 * Get and group by status of all emails which were sent to a specific user
	 * @param int $user_id
	 * @return array list of emails, group by status. It contains an empy list, or list of one or more status
	 * array(
	 *  status => emails count, // status: -3: inqueue, -2:notsent, -1: bounced, 0: sent, 1: open, 2: clicked, 3: unsubscribed
	 *  status => emails count,
	 *  ...
	 *  status => emails count
	 * )
	 */
	public function get_email_status_by_user($user_id) {
		if (!isset(self::$emails_status[$user_id])) {
			// get stats email status
			$query = '
                SELECT
                    count(`email_id`) as emails,
                    `status`
                FROM
                    `[wysija]email_user_stat`
                WHERE `user_id` = '.(int)$user_id.'
                GROUP BY `status`'
			;
			self::$emails_status[$user_id] = $this->indexing_dataset_by_field('status', $this->get_results($query), false, 'emails');
		}
		return self::$emails_status[$user_id];
	}

	/**
	 * Get open rate, based on sent emails
	 * @param int $user_id
	 * @return float open rate in percentage
	 */
	public function get_open_rate($user_id) {
		$email_status  = $this->get_email_status_by_user($user_id);
		if (empty($email_status))
			return 0;
		$sent_emails   = 0;
		$opened_emails = 0;
		foreach ($email_status as $status => $emails) {
			if ((int)$status >= 0)
				$sent_emails += (int)$emails;
			if ((int)$status >= 1)
				$opened_emails += (int)$emails;
		}
		return round(($opened_emails / $sent_emails) * 100, 2);
	}

	/**
	 * Get click rate, based on opened emails
	 * @param int $user_id
	 * @return float click rate in percentage
	 */
	public function get_click_rate($user_id) {
		$email_status   = $this->get_email_status_by_user($user_id);
		if (empty($email_status))
			return 0;
		$opened_emails  = 0;
		$clicked_emails = 0;
		foreach ($email_status as $status => $emails) {
			if ((int)$status >= 1)
				$opened_emails += (int)$emails;
			if ((int)$status >= 2 && (int)$status < 3)
				$clicked_emails += (int)$emails;
		}
		return round(($clicked_emails / $opened_emails) * 100, 2);
	}

	/**
	 *
	 * @param int $user_id
	 * @return int a number of received / sent newsletters to a specific user
	 */
	public function get_emails_count($user_id) {
		// get emails group by status
		$count		 = 0;
		$emails_status = $this->get_email_status_by_user($user_id); // we don't need to write a separated sql query here, reduce 1 sql request
		if (empty($emails_status))
			return $count;
		foreach ($emails_status as $emails)
			$count += $emails;
		return $count;
	}

}