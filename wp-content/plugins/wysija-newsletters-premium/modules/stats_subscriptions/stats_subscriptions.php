<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_subscriptions_model.php');

class WYSIJANLP_module_stats_subscriptions extends WYSIJA_module_statisticschart {

	public $name  = 'stats_subscriptions';

	public $model = 'WYSIJA_model_stats_subscriptions';

	public $view  = 'stats_subscriptions_view';

	static protected $stats_data;

	protected $charts;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		$this->charts = array(
			array(
				'id'	 => 'subscription_by_form',
				'active' => true,
				'name'   => __('Subscriptions by form', WYSIJA)
			),
			array(
				'id'	 => 'subscription_by_list',
				'active' => false,
				'name'   => __('Subscriptions by list', WYSIJA)
			)
		);
		parent::__construct();
	}

	public function hook_stats($params) {
		if (!$this->is_premium)
			return;
		$this->data['charts'] = $this->get_charts($params);
		$this->view_show = __FUNCTION__;
		return $this->render();
	}

	protected function get_charts($params) {
		$params = $params;
		$charts = array( );
		foreach ($this->charts as $chart) {
			if ($chart['active']) {
				$charts[$chart['id']]['data'] = $this->model_obj->get_forms(); // @todo, support 2 charts please
				$charts[$chart['id']]['name'] = $chart['name'];
			}
		}
		return $charts;
	}

}