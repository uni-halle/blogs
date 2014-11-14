<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_top_newsletters_model.php');

class WYSIJANLP_module_stats_top_newsletters extends WYSIJA_module_statistics {

	public $name  = 'stats_top_newsletters';

	public $model = 'WYSIJA_model_stats_top_newsletters';

	public $view  = 'stats_top_newsletters_view';

	static protected $stats_data;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
		$this->data['show_sent_as_total_of_sent_newsletters'] = $this->model_obj->show_sent_as_total_of_sent_newsletters;
	}

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

		$blocks = array();
		if (empty($params['additional_param'])) {
			$blocks['standard'] = 1;// email type
			$blocks['auto'] = 2;// email type
		} elseif (strtolower($params['additional_param']) === 'standard') {
			$blocks['standard'] = 1;// email type
		} elseif (strtolower($params['additional_param']) === 'auto') {
			$blocks['auto'] = 2;// email type
		}
		self::$stats_data = array();
		foreach ($blocks as $block_name => $email_type) {
			self::$stats_data[$block_name] = $this->model_obj->get_top_newsletters($top, $from_date, $to_date, $params['order_by'], $params['order_direction'], $email_type);
		}

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
		$this->data['top_newsletters'] = $this->get_stats($params);
		$order_direction = (!empty($params['order_direction']) && $params['order_direction'] == WYSIJA_module_statistics::ORDER_DIRECTION_ASC ? 'asc' : 'desc');
		$this->data['order_direction'] = array(
			'sent'		 => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_SENT ? 'sorted '.$order_direction : '',
			'opens'		=> !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_OPEN ? 'sorted '.$order_direction : '',
			'clicks'	   => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_CLICK ? 'sorted '.$order_direction : '',
			'unsubscribes' => !empty($params['order_by']) && $params['order_by'] == WYSIJA_module_statistics::ORDER_BY_UNSUBSCRIBE ? 'sorted '.$order_direction : ''
		);
		$this->view_show = 'hook_stats';
		return $this->render();
	}

}