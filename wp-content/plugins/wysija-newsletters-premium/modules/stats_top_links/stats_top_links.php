<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_top_links_model.php');

class WYSIJANLP_module_stats_top_links extends WYSIJA_module_statistics {

	public $name  = 'stats_top_links';

	public $model = 'WYSIJA_model_stats_top_links';

	public $view  = 'stats_top_links_view';

	static protected $stats_data;

	/**
	 * maximum number of links which will be shown by default;
	 * @var int
	 */
	protected $max_links_to_be_shown = 5;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
		$this->data['max_links_to_be_shown'] = $this->max_links_to_be_shown;
	}

	/**
	 * Get stats data
	 * @param array $params Input params
	  [top] => 5
	  [from] =>
	  [to] =>
	  [order_by] =>
	  [order_direction] => 2
	 * @return Array
	  [count] => 10
	  [links] => Array
	  (
	  [5] => Array
	  (
	  [clicks] => 17
	  [url_id] => 5
	  [url] => http://domain.com/path/to/page
	  [email_id] => 3
	  [email_subject] => Email subject 3
	  [lists] => Array
	  (
	  [2] => Utilisateurs WordPress
	  [1] => Liste Abonnés
	  )

	  )

	  [1] => Array
	  (
	  [clicks] => 12
	  [url_id] => 1
	  [url] => http://domain.com/path/to/page
	  [email_id] => 5
	  [email_subject] => Email subject 1
	  [lists] => Array
	  (
	  [3] => Prospects
	  )

	  )
	  )
	 */
	protected function get_stats(&$params) {
		if (!empty(self::$stats_data))
			return self::$stats_data;

		$top	   = $params['top'];
		$from_date = !empty($params['from']) ? $params['from'] : null;
		$to_date   = !empty($params['to']) ? $params['to'] : null;
		if (empty($params['order_by'])) {
			$params['order_by'] = WYSIJA_module_statistics::ORDER_BY_SENT;
		}
		if (empty($params['order_direction'])) {
			$params['order_direction'] = WYSIJA_module_statistics::ORDER_DIRECTION_DESC;
		}

		self::$stats_data = $this->model_obj->get_top_links($top, $from_date, $to_date, $params['order_by'], $params['order_direction']);

		return self::$stats_data;
	}

	/**
	 * hook_stats - main view of statistics page
	 * @param array $params Input params
	  [top] => 5
	  [from] =>
	  [to] =>
	  [order_by] =>
	  [order_direction] => 2
	 * @return longtext rendered view
	 */
	public function hook_stats($params) {
		if (!$this->is_premium)
			return;
		$this->data['top_links'] = $this->get_stats($params);
		$order_direction = (!empty($params['order_direction']) && $params['order_direction'] == WYSIJA_module_statistics::ORDER_DIRECTION_ASC ? 'asc' : 'desc');
		$this->data['order_direction'] = array(
			//'sent' => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_SENT ? 'sorted '.$order_direction : '', // not in use
			//'opens' => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_OPEN ? 'sorted '.$order_direction : '', // not in use
			'clicks' => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_CLICK ? 'sorted '.$order_direction : ''
		);
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

	/**
	 * hook_newsletter - page Wysija >> Newsletters >> view detail
	 * @param array $params
	 * @return string
	 */
	public function hook_newsletter($params) {
		return $this->hook_stats($params);
	}

}