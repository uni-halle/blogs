<?php

class Wdgpo_Logger {

	const LOG_KEY = 'wdgpo_log';
	const LOG_COUNT = 25;

	const LEVEL_ERROR = 100;
	const LEVEL_INFO = 200;
	const LEVEL_DEBUG = 300;

	private $_level;

	public function __construct ($level=false) {
		$this->_level = (int)$level ? (int)$level : self::LEVEL_ERROR;
	}

	public function log ($msg, $lvl) {
		$lvl = (int)$lvl ? (int)$lvl : self::LEVEL_INFO;
		if ($lvl > $this->_level) return false;

		$this->_do_log($msg, $lvl);
	}

	public function clear () {
		$this->_update_log(array());
	}

	public function get_log_string () {
		$log = $this->_get_log();
		if (!$log) return __('No logged actions', 'wdgpo');
		$ret = '';
		$log = array_reverse($log);
		foreach ($log as $item) {
			$ret .= '' .
				'<dt>' . date('Y-m-d H:i:s', $item['timestamp']) . '</dt>' .
				"<dd>{$item['message']}</dd>" .
			'';
		}
		return "<dl>{$ret}</dl>";
	}

	private function _get_log () {
		$log = get_option(self::LOG_KEY);
		return $log ? $log : array();
	}

	private function _update_log ($log) {
		if (!is_array($log)) return false;
		$diff = count($log) - self::LOG_COUNT;
		if ($diff > 0) for ($i=0; $i<$diff; $i++) array_shift($log);
		update_option(self::LOG_KEY, $log);
	}

	private function _do_log ($msg, $lvl) {
		$log = $this->_get_log();
		$log[] = array(
			"timestamp" => time(),
			"message" => $msg,
			"level" => $lvl,
		);
		$this->_update_log($log);
	}
}