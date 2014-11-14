<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_top_domains_model.php');

class WYSIJANLP_module_stats_top_domains extends WYSIJA_module_statistics {

	public $name  = 'stats_top_domains';

	public $model = 'WYSIJA_model_stats_top_domains';

	public $view  = 'stats_top_domains_view';

	static protected $stats_data;

	/**
	 * Show or not show the chart or rates (unopened, opened, clicked)
	 * Work closely with WYSIJA_model_stats_top_domains::$unique_click
	 * If this is set to TRUE, WYSIJA_model_stats_top_domains::$unique_click should be TRUE as well
	 * @var boolean
	 */
	protected $show_rates = true;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
		$this->data['show_rates'] = $this->show_rates;
		$this->data['show_sent_as_total_of_sent_newsletters'] = $this->model_obj->show_sent_as_total_of_sent_newsletters;
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
	  [domains] => Array
	  (
	  [0] => Array
	  (
	  [domain] => orange.fr
	  [sent] => 33
	  [opens] => 33
	  [clicks] => 9
	  )

	  [1] => Array
	  (
	  [domain] => gmail.com
	  [sent] => 20
	  [opens] => 19
	  [clicks] => 2
	  )

	  [n] => Array
	  (
	  [domain] => wanadoo.fr
	  [sent] => 17
	  [opens] => 17
	  [clicks] => 10
	  )

	  )

	  [count] => 418
	 */
	protected function get_stats(&$params) {
		if (!empty(self::$stats_data))
			return self::$stats_data;
		$top	   = $params['top'];
		$from_date = !empty($params['from']) ? $params['from'] : null;
		$to_date   = !empty($params['to']) ? $params['to'] : null;
		if (empty($params['order_by'])) {
			$params['order_by'] = WYSIJA_module_statistics::ORDER_BY_OPEN;
		}
		if (empty($params['order_direction'])) {
			$params['order_direction'] = WYSIJA_module_statistics::ORDER_DIRECTION_DESC;
		}
		self::$stats_data = $this->model_obj->get_top_domains($top, $from_date, $to_date, $params['order_by'], $params['order_direction']);
		return self::$stats_data;
	}

	/**
	 * hookStats - main view of statistics page
	 * @param array $params Input params
	  [top] => 5
	  [from] =>
	  [to] =>
	  [order_by] =>
	  [order_direction] => 2
	 *
	 * )
	 * @return longtext rendered view
	 */
	public function hook_stats($params) {
		if (!$this->is_premium)
			return;
		$this->data['top_domains'] = $this->get_stats($params);
		$order_direction = (!empty($params['order_direction']) && $params['order_direction'] == WYSIJA_module_statistics::ORDER_DIRECTION_ASC ? 'asc' : 'desc');
		$this->data['order_direction'] = array(
			'sent'   => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_SENT ? 'sorted '.$order_direction : '',
			'opens'  => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_OPEN ? 'sorted '.$order_direction : '',
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