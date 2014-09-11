<?php
class b8v_View extends b8v_Base {
	public function selected($value, $check) {
		$this->_e ( $this->checksel ( $value, $check, 'selected' ) );
	}
	public function checked($value, $check) {
		$this->_e ( $this->checksel ( $value, $check, 'checked' ) );
	}
	private function checksel($value, $check, $output) {
		$selected = '';
		if (strtolower ( $value ) == strtolower ( $check )) {
			$selected = " $output ";
		
		}
		return $selected;
	}
	private $_alternate = false;
	public function alternate($reset = false) {
		if ($reset) {
			$this->_alternate = false;
		}
		$this->_alternate = ! $this->_alternate;
		if ($this->_alternate) {
			return ' alternate ';
		}
		return '';
	}
	function __($value) {
		return $value;
	}
	function _e($value) {
		echo $this->__ ( $value );
	}
	public function render($filename) {
		$page = "";
		if (! file_exists ( $filename )) {
			$paths = $this->application ()->frontcontroller ()->getViewPaths ();
			// reverse the directory order, in the case of view files allow files to be overriden
			$dirs = $this->application ()->loader ()->includepath ( $paths, true );
			$filename = $this->application ()->loader ()->find_file ( $filename, true, $dirs );
		}
		if ($filename !== false) {
			ob_start ();
			require $filename;
			$page = ob_get_contents ();
			ob_end_clean ();
			return $page;
		}
		return null;
	}
	private $safe=null;
	public function safe()
	{
		return $this->safe;
	}
	public function __construct($application)
	{
		parent::__construct($application);
		$this->safe = new b8v_Safe();
	}
}