<?php
defined('WYSIJA') or die('Restricted access');

require_once(dirname(__FILE__).DS.'stats_newsletter_model.php');

class WYSIJANLP_module_stats_newsletter extends WYSIJA_module_statistics {

	public $name  = 'stats_newsletter';

	public $model = 'WYSIJA_model_stats_newsletter';

	public $view  = 'stats_newsletter_view';

	/**
	 *
	 * @var type define how charts are displayed
	 */
	protected $chart_stytle = 2; // 1 = open rate and click rate; 2 = all status in a pie; 0: both

	/**
	 * Map email status to its description
	 * @var type
	 */
	protected $emails_status = array( );

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
	 *
	 * @param array $params
	 * array(
	 *  'email_id' => int,
	 *  'email_object' => array(
	 *      'email_id' => int,
	 *      'campaign_id' => int,
	 *      'subject' => string,
	 *      'body' => html,
	 *      'created_at' => int,
	 *      'modified_at' => int,
	 *      'sent_at' => int,
	 *      'from_email' => string,
	 *      'from_name' => string,
	 *      'replyto_email' => string,
	 *      'replyto_name' => string,
	 *      'attachments' => ?,
	 *      'status' => int,
	 *      'type' => int,
	 *      'number_sent' => int,
	 *      'number_opened' => int,
	 *      'number_clicked' => int,
	 *      'number_unsub' => int,
	 *      'number_bounce' => int,
	 *      'number_forward' => int,
	 *      'params' => array(
	 *          'quickselection' => array(),
	 *          'divider' => array(),
	 *          'googletrackingcode' => string,
	 *          'schedule' => array(),
	 *          'theme' => string
	 *      ),
	 *      'wj_data' => string, (base64)
	 *      'wj_styles' => string (base64)
	 *  )
	 * )
	 * @return html
	 */
	public function hook_newsletter_top($params = array( )) {
		if (!$this->is_premium)
			return;

		if (empty($params['email_id']) || empty($params['email_object']))
			return;

		$this->data = array_merge($this->data, $params);
		$this->data['emails_count'] = $this->model_obj->get_emails_count($params['email_id']);
		$helper_toolbox = WYSIJA::get('toolbox', 'helper');
		$sent_at		= !empty($params['email_object']['sent_at']) ? $params['email_object']['sent_at'] : $params['email_object']['created_at'];
		$title_pattern  = $this->data['emails_count'] > 1 ? 'Sent %1$s ago to %2$s subscribers' : 'Sent %1$s ago to %2$s subscriber';
		$chart_title	= sprintf(__($title_pattern, WYSIJA), $helper_toolbox->duration_string($sent_at), $this->data['emails_count']);

		$params['chart_title'] = $chart_title;
		$this->data['dataset'] = array( );
		switch ($this->chart_stytle) {
			case 0:
				// $this->data['dataset'][] = $this->get_open_rate_data($params);
				// $this->data['dataset'][] = $this->get_click_rate_data($params);
				$this->data['dataset'][] = $this->get_emails_stats($params);
				break;
			case 1:
				// $this->data['dataset'][] = $this->get_open_rate_data($params);
				// $this->data['dataset'][] = $this->get_click_rate_data($params);
				break;

			case 2:
				$this->data['dataset'][] = $this->get_emails_stats($params);
				break;
		}

		$this->view_show = 'hook_newsletter_top';

		wp_enqueue_script('amcharts', WYSIJANLP_URL."js/amcharts/amcharts.js", array( ), WYSIJA::get_version());
		return $this->render();
	}

	/**
	 * Get emails stats data, and prepare for Google.visualization.dataTable
	 * @param array $params
	 * @return array
	 */
	protected function get_emails_stats($params) {
		$emails_stats = $this->model_obj->get_email_status($params['email_id']);
		$_data		= array( );
		foreach ($this->emails_status as $status => $desc) {
			// $_data[$desc] = isset($emails_stats[$status]) ? $emails_stats[$status] : 0;
			$_data[] = array(
				'label' => $desc,
				'stats' => isset($emails_stats[$status]) ? $emails_stats[$status] : 0
			);
		}

		return array(
			'title'   => $params['chart_title'],
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