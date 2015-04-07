<?php


class C_Admin_Notification_Manager
{
	var $_notifications = array();
	var $_displayed_notice = FALSE;
	var $_dismiss_url = NULL;

	static $_instance = NULL;
	static function get_instance()
	{
		if (!isset(self::$_instance)) {
			$klass = get_class();
			self::$_instance = new $klass;
		}
		return self::$_instance;
	}

	function __construct()
	{
		$this->_dismiss_url = site_url('/?ngg_dismiss_notice=1');
	}

	function has_displayed_notice()
	{
		return $this->_displayed_notice;
	}

	function add($name, $handler)
	{
		$this->_notifications[$name] = $handler;
	}

	function remove($name)
	{
		unset($this->_notifications[$name]);
	}

	function render()
	{
		$output= array();

		foreach (array_keys($this->_notifications) as $notice) {
			if (($html = $this->render_notice($notice))) {
				$output[] = $html;
			}
		}

		echo implode("\n", $output);
	}

	function is_dismissed($name)
	{
		$retval = FALSE;

		$settings = C_NextGen_Settings::get_instance();
		$dismissed= $settings->get('dismissed_notifications', array());

		if (isset($dismissed[$name])) {
			if (($id = get_current_user_id())) {
				if (in_array($id, $dismissed[$name])) $retval = TRUE;
				else if (in_array('unknown', $dismissed[$name])) $retval = TRUE;
			}
		}

		return $retval;
	}

	function dismiss($name)
	{
		$retval = FALSE;

		if (($handler = $this->get_handler_instance($name))) {
			$has_method = method_exists($handler, 'is_dismissable');
			if (($has_method && $handler->is_dismissable()) || !$has_method) {
				$settings = C_NextGen_Settings::get_instance();
				$dismissed= $settings->get('dismissed_notifications', array());
				if (!isset($dismissed[$name])) $dismissed[$name] = array();
				$user_id = get_current_user_id();
				$dismissed[$name][] = ($user_id ? $user_id : 'unknown');
				$settings->set('dismissed_notifications', $dismissed);
				$settings->save();
				$retval = TRUE;
			}
		}

		return $retval;
	}

	function get_handler_instance($name)
	{
		$retval = NULL;

		if (isset($this->_notifications[$name]) && (($handler = $this->_notifications[$name]))) {
			if (class_exists($handler)) $retval = call_user_func(array($handler, 'get_instance'), $name);
		}

		return $retval;
	}

	function enqueue_scripts()
	{
		if ($this->has_displayed_notice()) {
			$router = C_Router::get_instance();
			wp_enqueue_script('ngg_admin_notices', $router->get_static_url('photocrati-nextgen_admin#admin_notices.js'), array(), FALSE, TRUE);
			wp_localize_script('ngg_admin_notices', 'ngg_dismiss_url', $this->_dismiss_url);
		}
	}

	function serve_ajax_request()
	{
		$retval = array('failure' => TRUE);

		if (isset($_REQUEST['ngg_dismiss_notice'])) {
			header('Content-Type: application/json');
//			ob_start();
			if (isset($_REQUEST['name']) && $this->dismiss($_REQUEST['name'])) {
				$retval = array('success' => TRUE);
			}
			else $retval['msg'] = __('Not a valid notice name', 'nggallery');
//			ob_end_clean();

			echo json_encode($retval);

			throw new E_Clean_Exit;
		}
	}

	function render_notice($name)
	{
		$retval = '';

		if (($handler = $this->get_handler_instance($name)) && !$this->is_dismissed($name)) {

			// Does the handler want to render?
			$has_method = method_exists($handler, 'is_renderable');
			if (($has_method && $handler->is_renderable()) || !$has_method) {

				$view = new C_MVC_View('photocrati-nextgen_admin#admin_notice', array(
					'css_class'         => (method_exists($handler, 'get_css_class') ? $handler->get_css_class() : 'updated'),
					'is_dismissable'    => (method_exists($handler, 'is_dismissable') ? $handler->is_dismissable() : FALSE),
					'html'              => (method_exists($handler, 'render') ? $handler->render() : ''),
					'notice_name'       =>  $name
				));

				$retval = $view->render(TRUE);
				$this->_displayed_notice = TRUE;
			}
		}

		return $retval;
	}
}

