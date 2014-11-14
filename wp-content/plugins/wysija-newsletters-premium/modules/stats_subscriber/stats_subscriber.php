<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_subscriber_model.php');

class WYSIJANLP_module_stats_subscriber extends WYSIJA_module_statisticschart {

	public $name  = 'stats_subscriber';

	public $model = 'WYSIJA_model_stats_subscriber';

	public $view  = 'stats_subscriber_view';

	/**
	 * Map email status to its description
	 * @var type
	 */
	protected $emails_status = array( );

	/**
	 *
	 * @var type define how charts are displayed
	 */
	protected $chart_style = 2; // 1 = open rate and click rate; 2 = all status in a pie; 0: both

	static protected $stats_data;

	public function __construct() {
		$this->extended_plugin = WYSIJANLP;
		parent::__construct();
		// Don't change the order of statuses here. It's helpful for color concept at frontend.
		$this->emails_status = array(
			2  => __('Opened & Clicked', WYSIJA),
			1  => __('Opened only', WYSIJA),
			0  => __('Sent', WYSIJA),
			3  => __('Unsubscribed', WYSIJA),
			-3 => __('In queue', WYSIJA),
			-2 => __('Not sent', WYSIJA),
			-1 => __('Bounced', WYSIJA),
		);
	}

	/**
	 * Implement hook subscriber_left
	 * In this case, draw a chart to show open rate and click rate
	 * @param type $params
	 * @return string
	 */
	public function hook_subscriber_left($params = array( )) {
		if (empty($params['user_id']))
			return;

		$this->data['emails_count'] = $this->model_obj->get_emails_count($params['user_id']);
		$this->data['dataset'] = array( );
		switch ($this->chart_style) {
			case 0:
				$this->data['dataset'][] = $this->get_open_rate_data($params);
				$this->data['dataset'][] = $this->get_click_rate_data($params);
				$this->data['dataset'][] = $this->get_emails_stats($params);
				break;
			case 1:
				$this->data['dataset'][] = $this->get_open_rate_data($params);
				$this->data['dataset'][] = $this->get_click_rate_data($params);
				break;

			case 2:
				$this->data['dataset'][] = $this->get_emails_stats($params);
				break;
		}

		$this->view_show = 'hook_subscriber_left';

//        wp_register_script('wysija-charts', 'https://www.google.com/jsapi', array( 'jquery' ), true);
//        wp_enqueue_script('wysija-charts');
		wp_enqueue_script('amcharts', WYSIJANLP_URL."js/amcharts/amcharts.js", array( ), WYSIJA::get_version());
		return $this->render();
	}

	/**
	 * Implement hook subscriber_right
	 * In this case, draw a chart to show open rate and click rate
	 * @param type $params
	 * @return string
	 */
	public function hook_subscriber_right($params = array( )) {
		return $this->hook_subscriber_left($params);
	}

	/**
	 * Get open rate data, and prepare for Google.visualization.dataTable
	 * @param array $params
	 * @return array
	 */
	protected function get_open_rate_data(Array $params) {
		$open_rate = $this->model_obj->get_open_rate($params['user_id']);
		return array(
			'title'   => __('Open rate (based on received newsletters)', WYSIJA),
			'columns' => array(
				array(
					'type'  => 'string',
					'label' => __('Rate', WYSIJA)
				),
				array(
					'type'  => 'number',
					'label' => __('Rate', WYSIJA)
				),
			),
			'data'  => array(
				array(
					'label' => __('Opened', WYSIJA),
					'stats' => $open_rate
				),
				array(
					'label' => __('Unopened', WYSIJA),
					'stats' => 100 - $open_rate
				)
			// __('Opened',WYSIJA) => $open_rate,
			// __('Unopened',WYSIJA) => 100 - $open_rate,
			)
		);
	}

	/**
	 * Get click rate data, and prepare for Google.visualization.dataTable
	 * @param array $params
	 * @return array
	 */
	protected function get_click_rate_data($params) {
		$click_rate = $this->model_obj->get_click_rate($params['user_id']);
		return array(
			'title'   => __('Click rate (based on opened newsletters)', WYSIJA),
			'columns' => array(
				array(
					'type'  => 'string',
					'label' => __('Rate', WYSIJA)
				),
				array(
					'type'  => 'number',
					'label' => __('Rate', WYSIJA)
				),
			),
			'data'  => array(
				array(
					'label' => __('Clicked', WYSIJA),
					'stats' => $click_rate
				),
				array(
					'label' => __('Un-clicked', WYSIJA),
					'stats' => 100 - $click_rate
				)
			// __('Clicked',WYSIJA) => $click_rate,
			// __('Un-clicked',WYSIJA) => 100 - $click_rate,
			)
		);
	}

	/**
	 * Get emails stats data, and prepare for Google.visualization.dataTable
	 * @param array $params
	 * @return array
	 */
	protected function get_emails_stats($params) {
		$emails_stats = $this->model_obj->get_email_status_by_user($params['user_id']);
		$_data		= array( );
		foreach ($this->emails_status as $status => $desc) {
			//$_data[$desc] = isset($emails_stats[$status]) ? $emails_stats[$status] : 0;
			$_data[] = array(
				'label' => $desc,
				'stats' => isset($emails_stats[$status]) ? $emails_stats[$status] : 0
			); //isset($emails_stats[$status]) ? $emails_stats[$status] : 0;
		}


		return array(
			'title'   => __('Subscriber stats', WYSIJA),
			'columns' => array(
				array(
					'type'  => 'string',
					'label' => __('Rate', WYSIJA)
				),
				array(
					'type'  => 'number',
					'label' => __('Rate', WYSIJA)
				),
			),
			'data'  => $_data
		);
	}

}