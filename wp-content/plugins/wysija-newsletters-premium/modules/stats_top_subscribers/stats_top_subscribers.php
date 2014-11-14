<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_top_subscribers_model.php');

class WYSIJANLP_module_stats_top_subscribers extends WYSIJA_module_statistics {

	public $name  = 'stats_top_subscribers';

	public $model = 'WYSIJA_model_stats_top_subscribers';

	public $view  = 'stats_top_subscribers_view';

	static protected $stats_data;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
	}

	/**
	 * Get stats data
	 * @return type
	 */
	protected function get_stats(&$params) {
		if (!empty(self::$stats_data))
			return self::$stats_data;
		$top	   = $params['top'];
		$from_date = !empty($params['from']) ? $params['from'] : null;
		$to_date   = !empty($params['to']) ? $params['to'] : null;

		if (empty($params['order_by'])) {
			$params['order_by'] = WYSIJA_module_statistics::ORDER_BY_CLICK;
		}
		if (empty($params['order_direction'])) {
			$params['order_direction'] = WYSIJA_module_statistics::ORDER_DIRECTION_DESC;
		}
		self::$stats_data = $this->model_obj->get_top_subscribers($top, $from_date, $to_date, $params['order_by'], $params['order_direction']);
		return self::$stats_data;
	}

	protected function get_order_direction($params) {
		$order_direction = (!empty($params['order_direction']) && $params['order_direction'] == WYSIJA_module_statistics::ORDER_DIRECTION_ASC ? 'asc' : 'desc');
		return array(
			'sent'   => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_SENT ? 'sorted '.$order_direction : '',
			'opens'  => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_OPEN ? 'sorted '.$order_direction : '',
			'clicks' => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_CLICK ? 'sorted '.$order_direction : ''
		);
	}

	/**
	 * hook_stats - main view of statistics page
	 * @param type $params = array( // @todo: to be defined
	 *
	 * )
	 * @return type
	 */
	public function hook_stats($params) {
		if (!$this->is_premium)
			return;
		$this->data['top_subscribers'] = $this->get_stats($params);
		$this->data['order_direction'] = $this->get_order_direction($params);
		$this->view_show = 'hook_stats';
		return $this->render();
	}

	/**
	 * hook_subscriber - page Wysija >> Subscribers >> view detail
	 * @param array $params
	 * @return string
	 */
	public function hook_subscriber($params) {
		return $this->hook_stats($params);
	}

	public function hook_subscriber_left($params = array( )) {
		return '<h3>This is left block</h3>';
	}

	public function hook_subscriber_bottom($params = array( )) {
		if (empty($params['user_id']))
			return;
		$this->data['order_direction'] = $this->get_order_direction($params);
		$this->data['opened_newsletters'] = $this->get_opened_newsletters($params);
		$this->view_show = 'hook_subscriber_bottom';
		return $this->render();
	}

	protected function get_opened_newsletters($params) {
		// get emails by user
		$emails = $this->model_obj->get_emails_by_user_id($params['user_id']);
		if (empty($emails))
			return;
		// get urls by email
		$urls   = $this->model_obj->get_urls_by_email_ids(array_keys($emails), $params['user_id']);
		// combine
		foreach ($emails as $email_id => $email) {
			$emails[$email_id]['urls'] = !empty($urls[$email_id]) ? $urls[$email_id] : array( );
		}
		return $emails;
	}

	/**
	 * hook_newsletter - page Wysija >> Newsletters >> view detail
	 * @param array $params
	 * @return string
	 */
	public function hook_newsletter($params) {
		return $this->hook_stats($params);
	}

}