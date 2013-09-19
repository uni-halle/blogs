<?php
if (! class_exists ( 'b8v_Application' )) :
	require dirname ( __FILE__ ) . '/Base.php';
	class b8v_Application extends b8v_Base {
		private $_page = null;
		public function page() {
			if (null === $this->_page) {
				$this->set_page ();
			}
			return $this->_page;
		}
		public function set_page($page = null) {
			if (null === $page) {
				$this->_page = $this->relative_path ();
			} else {
				$this->_page = '/' . ltrim ( rtrim ( $page, '/' ), '/' );
			}
		}
		public function relative_path($uri = null) {
			if (null === $uri) {
				$uri = $_SERVER ['REQUEST_URI'];
			}
			$uri = explode ( '?', $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri, '/' );
			$project = dirname ( $this->filename () );
			$root_uri = $uri;
			while ( strpos ( $project, $root_uri ) === false ) {
				$root_uri = substr ( $root_uri, 0, strrpos ( $root_uri, '/' ) );
			}
			$uri = '/' . ltrim ( rtrim ( substr ( $uri, strlen ( $root_uri ) ), '/' ), '/' );
			return $uri;
		}
		private $_filename = null;
		public function filename() {
			return $this->_filename;
		}
		private static $_applications = array ();
		public function applications() {
			return self::$_applications;
		}
		private function add_application() {
			self::$_applications [] = $this;
		}
		public function __construct($filename = "") {
			if ($this->MeetsSpec ()) {
				parent::__construct ( $this );
				$this->_filename = $filename;
				$this->preload_classes ();
				$this->set_frontcontroller ();
				$this->add_application ();
			}
		}
		private $settings = null;
		public function settings() {
			if (null == $this->settings) {
				$this->settings = new b8v_Settings ( $this );
			}
			return $this->settings;
		}
		private $_frontController;
		public function frontcontroller() {
			$this->set_frontcontroller ();
			return $this->_frontcontroller;
		}
		protected function set_frontcontroller($controller = null) {
			if (null === $controller) {
				$this->_frontcontroller = b8v_Controller_Front::getInstance ( $this->application () );
			} else {
				$this->_frontcontroller = $controller;
			}
		}
		private $_loader = null;
		public function loader() {
			return $this->_loader;
		}
		protected function set_loader() {
			if (! class_exists ( 'b8v_Loader' )) {
				require_once dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'b8v/Loader.php';
			}
			$this->_loader = new b8v_Loader ( $this );
			$this->_loader->add_subfolder ( 'application' );
			$this->_loader->add_subfolder ( 'application/Models' );
		}
		protected function phpmin() {
			return '5.2.0';
		}
		protected $errors = null;
		protected function MeetsSpec() {
			$this->errors = array ();
			if (version_compare ( phpversion (), $this->phpmin () ) >= 0) {
				$this->set_loader ();
				if (! class_exists ( 'b8v_Loader' )) {
					require_once dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'b8v/Loader.php';
				}
				$db = debug_backtrace ();
				$file = $db ['1'] ['file'];
				b8v_Loader::addRoot ( $file );
				return true;
			}
			$this->errors [] = "%s requires a minimum of PHP " . $this->phpmin ();
			return false;
		}
		protected function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			$loader = $this->loader ();
			array_unshift ( $classes, 'b8v_Info', 'b8v_Controller_Action', 'b8v_Type_Abstract', 'b8v_Type_String', 'b8v_Type_Array', 'b8v_Debug', 'b8v_FS', 'b8v_View', 'b8v_Http', 'b8v_Tag', 'b8v_Data_Abstract', 'b8v_Data_INI', 'b8v_Data_CSV', 'b8v_Data_XML', 'b8v_FS', 'b8v_Http', 'b8v_Controller_Front', 'b8v_Controller_Dispatcher', 'b8v_Table', 'b8v_Controller_Action_Direct', 'b8v_Settings' ,'b8v_Safe');
			foreach ( $classes as $class ) {
				$loader->load_class ( $class );
			}
		}
	}

endif;