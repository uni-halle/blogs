<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_new_subscribers_model.php');

class WYSIJANLP_module_stats_new_subscribers extends WYSIJA_module_statisticschart {

	public $name							= 'stats_new_subscribers';

	public $model						   = 'WYSIJA_model_stats_new_subscribers';

	public $view							= 'stats_new_subscribers_view';

	static protected $stats_data;

	/**
	 * Percent number to be added to maxium value of subscribers. <br />
	 * If the maximum of subscribers per list is 100%, $percent_to_add_to_maximum_value is 10%, the maximum should be 110 which will be shown in graph chart
	 * @var float
	 */
	protected $percent_to_add_to_maximum_value = 0.0;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
	}

	/**
	 * Get stats data
	 * @param array $params Input params
	  [top] => 5
	  [from] =>
	  [to] =>
	  [order_by] =>
	  [order_direction] => 2
	 * @return type
	  [0] => Array
	  (
	  [time] => 2002,1,1
	  [Commentaires] => 4
	  [List 1's name] => 2
	  [List 2's name] => 7
	  ...
	  [List n's name] => 2
	  )
	  [1] => Array
	  (
	  [time] => 2002,1,1
	  [Commentaires] => 4
	  [List 1's name] => 2
	  [List 2's name] => 7
	  ...
	  [List n's name] => 2
	  )
	  ....
	  [n] => Array
	  (
	  [time] => 2002,1,1
	  [Commentaires] => 4
	  [List 1's name] => 2
	  [List 2's name] => 7
	  ...
	  [List n's name] => 2
	  )
	 */
	protected function get_stats($params) {
		if (!empty(self::$stats_data))
			return self::$stats_data;
		$from_date = !empty($params['from']) ? $params['from'] : null;
		$to_date   = !empty($params['to']) ? $params['to'] : null;
		$group_by  = !empty($params['group_by']) ? $params['group_by'] : null;
		self::$stats_data = $this->model_obj->get_new_subscribers($from_date, $to_date, $group_by);
		return self::$stats_data;
	}

	protected function get_list_names_from_dataset($new_subscribers) {
		$first_record = $new_subscribers[0];
		unset($first_record['time']);
		return array_keys($first_record);
	}

	/**
	 * Get max value of new subscribers of a form
	 * @param array $new_subscribers
	 * @return int
	 */
	protected function get_max_value_from_dataset($new_subscribers) {
		$values = array( 0 );// making sure, there is at least 1 item in the array, it's helpful for using max() later
		foreach ($new_subscribers as $subscribers) {
			foreach ($subscribers as $key => $value) {
				if ($key != 'time') {
					$values[] = $value;
				}
			}
		}
		$maximum = 0;
		if (count($values)) {
			$maximum = $this->percent_to_add_to_maximum_value ? (max($values) * ((100 + $this->percent_to_add_to_maximum_value) / 100)) : max($values);
		}

		return round($maximum, 0);
	}

	/**
	 * hook_stats - main view of statistics page
	 * @param type $params = array(
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
		$this->data['new_subscribers'] = $this->get_stats($params);
		$this->data['list_names'] = !empty($this->data['new_subscribers']['time']) ? $this->get_list_names_from_dataset($this->data['new_subscribers']['time']) : array( );
		$this->data['max_value'] = $this->get_max_value_from_dataset($this->data['new_subscribers']['time']);
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