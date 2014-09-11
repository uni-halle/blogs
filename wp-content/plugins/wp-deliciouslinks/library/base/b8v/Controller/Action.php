<?php
abstract class b8v_Controller_Action extends b8v_Base {
	protected function marker($tag, $content) {
		$tagc = b8v_Tag::instance ();
		$matches = $tagc->get ( $tag, $content, true );
		foreach ( ( array ) $matches as $match ) {
			$new = call_user_func ( array ($this, $tag . '_Marker' ), $match );
			$content = str_replace ( $match ['match'], w8v_Values::fixPostInsert ( $new ), $content );
		}
		return $content;
	}
	private $_type = null;
	
	public function getType() {
		return $this->_type;
	}
	protected function set_type($type) {
		$this->_type = $type;
	}
	const DIRECT = 1;
	protected $title = "";
	public function __construct($application) {
		parent::__construct ( $application );
		$this->getView ();
		$this->template_folders = array ($this->controllerName (), '' );
	}
	public function updated($message = 'Settings Saved', $type = 'updated') {
		$return = '';
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$this->view->message = $message;
			$this->view->type = $type;
			$return = $this->renderScript ( 'Common/Updated.phtml' );
		}
		return $return;
	}
	protected $_actions = null;
	
	protected function actions() {
		if (null === $this->_actions) {
			$this->_actions = $this->decode_actions ();
			uasort ( $this->_actions, array ($this, 'action_sort' ) );
		}
		return $this->_actions;
	}
	protected $view = null;
	
	protected function getView() {
		$this->setView ();
		return $this->view;
	}
	protected function setView() {
		if (null === $this->view) {
			$this->view = new b8v_View ( $this->application () );
		}
	}
	protected function decode_actions() {
		$return = array ();
		$methods = get_class_methods ( $this );
		$actions = array ();
		foreach ( $methods as $method ) {
			if (strpos ( $method, 'Virtual' ) !== 0) {
				if (strrpos ( $method, 'Meta' ) != strlen ( $method ) - 4) {
					if (strpos ( $method, 'Action' )) {
						$actions [] = $method;
					}
				}
			}
		}
		$actions = $this->VirtualActions ( $actions );
		foreach ( $actions as $method ) {
			$decoded = $this->decode_action ( $method );
			if ($decoded !== false) {
				$return [$method] = $decoded;
			}
		}
		return $return;
	}
	protected function action_sort($a, $b) {
		if ($a ['priority'] == $b ['priority']) {
			if (strtolower ( $a ['title'] ) == strtolower ( $b ['title'] )) {
				return 0;
			}
			return (strtolower ( $a ['title'] ) < strtolower ( $b ['title'] )) ? - 1 : 1;
		}
		return ($a ['priority'] < $b ['priority']) ? - 1 : 1;
	}
	public function donate_button($large = false) {
		$this->view->image = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
		if ($large) {
			$this->view->image = 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif';
		}
		return $this->renderScript ( 'Common/donate.phtml' );
	}
	protected function decode_action($method) {
		$return = array ();
		if (strpos ( $method, 'Action' )) {
			$return ['action'] = $method;
		} else {
			$return ['action'] = 'VirtualAction';
		}
		$return ['level'] = 'administrator';
		$return ['title'] = '';
		$return ['hide'] = false;
		$return ['raw_title'] = '';
		$return ['priority'] = 0;
		$info = explode ( 'Action', $method );
		$return ['raw_title'] = $info [0];
		$info [0] = str_replace ( '_2E', '.', $info [0] );
		$info [0] = urldecode ( str_replace ( '_', '%', $info [0] ) );
		$security = "";
		if (count ( $info ) < 2 || $info [1] == "") {
			$info [1] = 0;
		} else {
			$info2 = explode ( '__', $info [1] );
			$info [1] = str_replace ( '_', '-', $info2 [0] );
			if (count ( $info2 ) > 1 && $info2 [1] != "") {
				$security = $info2 [1];
			}
		}
		$return ['title'] = $info [0];
		$return ['priority'] = $info [1];
		$meta = $return ['action'] . 'Meta';
		if (method_exists ( $this, $meta )) {
			if ($meta == 'VirtualActionMeta') {
				$return = $this->$meta ( $return ['title'], $return );
			} else {
				$return = $this->$meta ( $return );
			}
		}
		if ($return ['title'] === null || $return ['title'] == '') {
			return false;
		}
		return $return;
	}
	protected $baseURL = "";
	protected function renderScript($script) {
		$return = null;
		$orig = $script;
		$exists = file_exists ( $script );
		$path = null;
		if (! $exists) {
			$script = $this->template_path ( $script );
		}
		if ($script !== false) {
			$pi = pathinfo ( $script );
			switch (strtolower ( $pi ['extension'] )) {
				case 'ini' :
					$data = new b8v_Data_INI ( $this->application (), $script );
					$return = $data->load ();
					$return = $return->getArray ();
					break;
				case 'csv' :
					$data = new b8v_Data_CSV ( $this->application (), $script, true );
					$return = $data->getArray ();
					$return = $data->load ();
					break;
				default :
					$return = $this->view->render ( $script );
			}
		}
		return $return;
	}
	protected $template_folders = array ('' );
	private function template_path($filename) {
		$paths = $this->application ()->frontcontroller ()->getViewPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths, true );
		$newdirs = array ();
		foreach ( $this->template_folders as $tp ) {
			foreach ( $dirs as $dir ) {
				$newdirs [] = rtrim ( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $tp;
			}
		}
		$fullpath = $this->application ()->loader ()->find_file ( ltrim ( $filename, DIRECTORY_SEPARATOR ), true, $newdirs );
		return $fullpath;
	}
	public function set_template_folders($folders) {
		$folders [] = $this->controllerName ();
		$folders [] = '';
		$this->template_folders = $folders;
	}
	public function VirtualActions($actions) {
		return $actions;
	}
	public function VirtualAction($action, $content) {
		return $content . $action;
	}
	public function template_files($types = "*") {
		$return = array ();
		$paths = $this->application ()->frontcontroller ()->getViewPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths );
		$newdirs = array ();
		foreach ( $this->template_folders as $tp ) {
			foreach ( $dirs as $dir ) {
				$newdirs [] = rtrim ( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $tp;
			}
		}
		if (! is_array ( $types )) {
			$types = ( array ) $types;
		}
		foreach ( $newdirs as $dir ) {
			$d = dir ( $dir );
			while ( false !== ($entry = $d->read ()) ) {
				if ($entry [0] != '.') {
					$fullpath = $d->path . DIRECTORY_SEPARATOR . $entry;
					$pi = pathinfo ( $fullpath );
					if (in_array ( '*', $types ) || in_array ( strtolower ( $pi ['extension'] ), $types )) {
						$return [] = $fullpath;
					}
				}
			}
			$d->close ();
		}
		return $return;
	}
	protected function preDispatch() {
	}
	protected function Dispatch() {
	}
	protected function postDispatch() {
	}
	public function controller() {
		$this->view->args = array ();
		if (count ( func_get_args () ) > 0) {
			$this->view->args = func_get_args ();
		} else {
			$this->view->args [] = null;
		}
		$return = call_user_func_array ( array ($this, 'preDispatch' ), $this->view->args );
		if (null !== $return) {
			$this->view->args [0] = $return;
		}
		$return = call_user_func_array ( array ($this, 'Dispatch' ), $this->view->args );
		if (null !== $return) {
			$this->view->args [0] = $return;
		}
		$return = call_user_func_array ( array ($this, 'postDispatch' ), $this->view->args );
		if (null !== $return) {
			$this->view->args [0] = $return;
		}
		return $this->view->args [0];
	}
	public function controllerName() {
		$class = get_class ( $this );
		$return = substr ( $class, 0, - 10 );
		if (method_exists ( $this, 'ControllerMeta' )) {
			$return = $this->ControllerMeta ();
		}
		return $return;
	}
	protected function selected_action_page() {
		$split = explode ( '/', $this->application ()->page () );
		if (count ( $split ) < 4 || $split [3] == "") {
			$act = 'indexAction';
		} else {
			$split [3] = urlencode ( $split [3] );
			$split [3] = str_replace ( '%', '_', $split [3] );
			$split [3] = str_replace ( '.', '_2E', $split [3] );
			$act = $split [3] . 'Action';
		}
		$actions = $this->actions ();
		if (array_key_exists ( $act, $actions )) {
			return $actions [$act];
		}
		if (method_exists ( $this, 'catchAllAction' )) {
			return $actions ['catchAllAction'];
		}
		return null;
	}
	protected function selected_action() {
		return $this->selected_action_page ();
	}
	protected function csv_headers($file = null) {
		header ( "Content-type: application/csv" );
		if (null !== $file) {
			header ( "Content-Disposition: attachment; filename=$file.csv" );
		}
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
	}
	protected function txt_headers() {
		header ( 'Content-Type: text/plain' );
	}
}