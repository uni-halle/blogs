<?php
if (! class_exists ( 'b8v_Application' )) :
	require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'base/b8v/Application.php';
	class w8v_Application extends b8v_Application {
		protected function set_frontcontroller() {
			parent::set_frontcontroller ( w8v_Controller_Front::getInstance ( $this->application () ) );
		}
		protected $passed_classes = null;
		public function __construct($filename = "", $classes = array()) {
			$this->passed_classes = $classes;
			if (! function_exists ( "wp" )) {
				throw new Exception ( "WordPress has not loaded." );
			}
			add_action ( "plugins_loaded", array ($this, "setup" ) );
			parent::__construct ( $filename );
			$this->info = new w8v_info ( $this );
		}
		public function relative_path($uri = null) {
			global $current_blog;
			if (null === $uri) {
				$uri = $_SERVER ['REQUEST_URI'];
			}
			//$uri = substr ( $uri , strlen ( $current_blog->path ) );
			$uri = substr ( $uri, strlen ( get_option ( 'site_url' ) ) );
			
			$uri = explode ( '?', $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri, '/' );
			$uri = '/' . rtrim ( $uri, '/' );
			return $uri;
		}
		private static $templateDirBase = null;
		private static function templateDirBase() {
			if (is_null ( self::$templateDirBase )) {
				self::$templateDirBase = dirname ( dirname ( dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) );
			}
			return self::$templateDirBase;
		}
		private static $templateDir = null;
		public static function templateDir($subfolder = null) {
			if (! is_null ( $subfolder ) || is_null ( self::$templateDir )) {
				self::$templateDir = self::templateDirBase () . DIRECTORY_SEPARATOR . $subfolder;
			}
			return self::$templateDir;
		}
		public function setup() {
			load_plugin_textdomain ( get_class ( $this ), false, dirname ( plugin_basename ( $this->application ()->filename () ) ) . "/languages/" );
		}
		public function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			array_unshift ( $classes, 'w8v_Info', 'w8v_Values', 'w8v_Table','w8v_Table_SiteMeta', 'w8v_Table_Sites', 'w8v_Table_Site', 'w8v_Table_Posts', 'w8v_Table_Blogs', 'w8v_Table_Blog', 'w8v_Table_Options', 'w8v_Table_Users', 'w8v_Table_UserMeta', 'w8v_View', 'w8v_Controller_Action_Abstract', 'w8v_Controller_Action_Action', 'w8v_Controller_Action_AdminMenu', 'w8v_Controller_Action_Control', 'w8v_Controller_Action_Filter', 'w8v_Controller_Front', 'w8v_Controller_Dispatcher', 'w8v_Table_Comments' );
			foreach ( $this->passed_classes as $class ) {
				$classes [] = $class;
			}
			parent::preload_classes ( $classes );
		}
		private $info = null;
		public function info() {
			return $this->info;
		}
	}

endif;