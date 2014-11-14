<?php
defined('WYSIJA') or die('Restricted access');

class WYSIJA_model_stats_subscriptions extends WYSIJA_model {

	/**
	 * Get form data
	 * @return type
	 */
	public function get_forms() {
		$query = '
            SELECT
                `form_id`,
                `name`,
                `subscribed`
            FROM [wysija]form
            ';
		return $this->get_results($query);
	}

}