<?php
class b8v_Controller_Front extends b8v_Base {
	protected $controllerPaths = array ();
	protected function setControllerPaths() {
		$this->controllerPaths [] = 'Controllers/';
	}
	protected $viewPaths = array ();
	protected function setViewPaths() {
		$this->viewPaths [] = 'Views/';
	}
	public function getControllerPaths() {
		return $this->controllerPaths;
	}
	public function getViewPaths() {
		return $this->viewPaths;
	}
	protected $_dispatcher = null;
	public function getDispatcher() {
		$this->setDispatcher ();
		return $this->_dispatcher;
	}
	protected static $_instance = array ();
	public static function getInstance($application) {
		$filename = $application->filename ();
		if (! array_key_exists ( $filename, self::$_instance )) {
			self::$_instance [$filename] = new self ( $application );
			self::$_instance [$filename]->setup ();
		}
		return self::$_instance [$filename];
	}
	protected function setDispatcher($dispatcher = null) {
		if (null === $this->_dispatcher) {
			if (null === $dispatcher) {
				$this->_dispatcher = new b8v_Controller_Dispatcher ( $this->application () );
			} else {
				$this->_dispatcher = $dispatcher;
			}
		}
	}
	public function setup() {
		$this->setControllerPaths ();
		$this->setViewPaths ();
		$this->setDispatcher ();
	}
	public function __construct($application) {
		parent::__construct ( $application );
	}
}