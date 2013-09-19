<?php
abstract class w8v_Controller_Action_Action extends w8v_Controller_Action_Abstract {
	public function admin_headAction() {
		$this->wp_headAction ();
	}
	public function wp_headAction() {
		$this->view->url = $this->url ( 'public/wp-style.php' );
		$this->view->url .= '?t=' . $this->application ()->Settings ()->slug;
		$this->view->_e ( $this->renderScript ( 'Common/head.phtml' ) );
		$this->view->url = $this->url ( 'public/' . $this->application ()->Settings ()->slug . '.css' );
		if ($this->view->url !== false) {
			$this->view->_e ( $this->renderScript ( 'Common/head.phtml' ) );
		}
	}
	protected function url($file) {
		$return = false;
		$file = $this->application ()->loader ()->find_file ( $file, true );
		if ($file !== false) {
			$return = w8v_Values::urlFromFileame ( $file );
		}
		return $return;
	}
	public function __construct($application) {
		$this->set_type ( self::WP_ACTION );
		parent::__construct ( $application );
	}
	public function setup() {
		foreach ( ( array ) $this->actions () as $action ) {
			add_action ( $action ['raw_title'], array ($this, "controller" ), $action ['priority'] );
		}
	}
}