<?php
abstract class w8v_Controller_Action_Abstract extends b8v_Controller_Action {
	protected function basic_auth() {
		$credentials = array ();
		if (array_key_exists ( 'PHP_AUTH_USER', $_SERVER ) && array_key_exists ( 'PHP_AUTH_PW', $_SERVER )) {
			$credentials ['user_login'] = $_SERVER ['PHP_AUTH_USER'];
			$credentials ['user_password'] = $_SERVER ['PHP_AUTH_PW'];
		}
		$user = wp_signon ( $credentials );
		if (is_wp_error ( $user )) {
			header ( 'WWW-Authenticate: Basic realm="' . $_SERVER ['SERVER_NAME'] . '"' );
			header ( 'HTTP/1.0 401 Unauthorized' );
			die ();
		} // if
	}
	const WP_FILTER = 2;
	const WP_ACTION = 4;
	const WP_CONTROL = 8;
	const WP_DASHBOARD = 16;
	protected function setView() {
		$this->view = new w8v_View ( $this->application () );
	}
	public function controller() {
		$this->view->title = $this->title;
		$this->view->options = $this->actions ();
		$this->view->selected = $this->selected_action ();
		$args = func_get_args ();
		return call_user_func_array ( array ('parent', 'controller' ), $args );
	}
	protected function selected_action_wp() {
		$filter = explode ( '_', current_filter () );
		if (count ( $filter ) > 1 && $filter [1] == 'page') {
			$pages = $this->subpages ();
			if (empty ( $pages ['page2'] )) {
				foreach ( $this->actions () as $r ) {
					return $r;
				}
			} else {
				foreach ( $this->actions () as $r ) {
					if ($pages ['page2'] == b8v_Type_String::staticSafe ( $r ['title'] )) {
						return $r;
					}
				}
			}
		} else {
			foreach ( ( array ) $this->actions () as $action ) {
				if (strpos ( $action ['raw_title'], current_filter () ) === 0) {
					return $action;
				}
			}
		}
		return null;
	}
	public function control_url($page) {
		$return = rtrim ( get_option ( 'siteurl' ), '/' );
		$page = ltrim ( $page, '/' );
		if (get_option ( 'permalink_structure' ) == '') {
			$return .= '/index.php?view=';
		} else {
			$return .= '/';
		}
		$return .= $page;
		return $return;
	}
	protected function selected_action() {
		return $this->selected_action_wp ();
	}
	
	protected function Dispatch() {
		$this->view->args = array ();
		if (count ( func_get_args () ) > 0) {
			$this->view->args = func_get_args ();
		} else {
			$this->view->args [] = null;
		}
		if (is_array ( $this->view->selected )) {
			$args = $this->view->args;
			if ($this->view->selected ['action'] == 'VirtualAction') {
				array_unshift ( $args, $this->view->selected ['raw_title'] );
			}
			$return = call_user_func_array ( array ($this, $this->view->selected ['action'] ), $args );
			if (null !== $return) {
				$this->view->args [0] = $return;
			}
		}
		$return = $this->renderScript ( $this->view->selected ['raw_title'] . '.phtml' );
		if (null !== $return) {
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}
	protected function subpages() {
		$pages = array ();
		foreach ( ( array ) $_GET as $key => $value ) {
			if (b8v_Type_String::staticStartsWith ( $key, 'page' )) {
				$pages [$key] = $value;
			}
		}
		ksort ( $pages );
		return $pages;
	}
}