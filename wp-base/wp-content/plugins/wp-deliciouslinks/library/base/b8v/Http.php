<?php
class b8v_Http extends b8v_Base {
	private $Timeout = 60;
	public function Timeout($value = null) {
		if (! is_null ( $value )) {
			$this->Timeout = $value;
		}
		return $this->Timeout;
	}
	private $Port = null;
	public function Port($value = null) {
		if (! is_null ( $value )) {
			$this->Port = $value;
		}
		return $this->Port;
	}
	private $Headers = array ();
	public function addHeader($key, $value) {
		$this->Headers [strtoupper ( $key )] = $value;
	}
	public function removeHeader($key) {
		unset ( $this->Headers [strtoupper ( $key )] );
	}
	public function Headers($value = null) {
		if (! is_null ( $value )) {
			$this->Headers = $value;
		}
		return $this->Headers;
	}
	public function logonBasic($username, $password) {
		$this->Headers ["Authorization"] = "Basic " . base64_encode ( $username . ":" . $password );
	}
	private $url = null;
	public function url($value = null) {
		if (! is_null ( $value )) {
			$this->url = $value;
		}
		return $this->url;
	}
	private $method = 'GET';
	public function method($value = null) {
		if (! is_null ( $value )) {
			$this->method = $value;
		}
		return $this->method;
	}
	public function display() {
		$return = $this->get ();
		foreach ( $this->returnedHeaders () as $key => $value ) {
			header ( $key . ': ' . $value );
		}
		echo $return;
	}
	private $data = null;
	public function data($data = null) {
		if (! is_null ( $data )) {
			if (is_array ( $data )) {
				foreach ( $data as $key => $value ) {
					$data [$key] = $key . '=' . $value;
				}
				$data = implode ( '&', $data );
			
			}
			$this->data = $data;
		}
		return $this->data;
	}
	private $_user = null;
	private $_password = null;
	public function set_user($value = null) {
		$this->_user = $value;
		return $this->_user;
	}
	public function set_password($value = null) {
		$this->_password = $value;
		return $this->_password;
	}
	public function user() {
		return $this->_user;
	}
	public function password() {
		return $this->_password;
	}
	public function __construct($url = null) {
		parent::__construct ();
		$this->url = $url;
		$this->dummyHeaders ();
	}
	public function get() {
		return $this->request ( $this->url, $this->method, $this->data );
	}
	private function dummyHeaders() {
		// stick in defaults
		$this->Headers ['USER_AGENT'] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3';
		$this->Headers ['ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$this->Headers ['ACCEPT_LANGUAGE'] = 'en-us,en;q=0.5';
		$this->Headers ['ACCEPT_ENCODING'] = 'gzip,deflate';
		$this->Headers ['CONNECTION'] = 'close';
		// if possible stick in values from th users browser
		foreach ( $_SERVER as $key => $value ) {
			if (b8v_Type_String::staticStartsWith ( $key, 'http_' )) {
				switch (strtoupper ( $key )) {
					// don't use these vaules as they cause problems
					case 'HTTP_KEEP_ALIVE' :
					case 'HTTP_CONNECTION' :
					case 'HTTP_COOKIE' :
					case 'HTTP_COOKIE' :
					case 'HTTP_HOST' :
					case 'HTTP_REFERER' :
						break;
					default :
						$this->Headers [substr ( $key, 0, strlen ( 'http_' ) )] = $value;
				}
			}
		}
	}
	private function request($Url, $Method = 'GET', $Data = null) {
		$pURL = parse_url ( $Url );
		if (empty ( $pURL ['host'] )) {
			return false;
		}
		$Host = $pURL ['host'];
		$Path = (isset ( $pURL ['path'] )) ? $pURL ['path'] : '/';
		$Method = strtoupper ( $Method );
		switch ($pURL ['scheme']) {
			case 'https' :
				$scheme = 'ssl://';
				$port = 443;
				break;
			case 'http' :
			default :
				$scheme = '';
				$port = 80;
		}
		if (is_null ( $this->Port )) {
			$this->Port = $port;
		}
		if (! $Stream = fsockopen ( $scheme . $pURL ['host'], $this->Port, $Errno, $Errstr, $this->Timeout )) {
			return false;
		}
		if ($Method == 'GET' && $Data != null) {
			$Path .= '?' . $Data;
		}
		$Request = '';
		$Request .= "$Method $Path HTTP/1.1\r\n";
		$this->addHeader ( 'HOST', $Host );
		if ($Method == 'POST') {
			$this->addHeader ( 'Content-Type', "application/x-www-form-urlencoded" );
			$this->addHeader ( 'Content-Length', strlen ( $Data ) );
		}
		$this->addHeader ( 'CONNECTION', 'close' );
		foreach ( array_keys ( ( array ) $this->Headers ) as $key ) {
			$Request .= $key . ": " . $this->Headers [$key] . "\r\n";
		}
		$Request .= "\r\n";
		$Page = $this->_request ( $Stream, $Request, $Method, $Data );
		$parts = explode ( "\r\n\r\n", $Page, 2 );
		$headers = $parts [0];
		$content = $parts [1];
		unset ( $parts );
		$this->returnedHeaders = $this->_headers ( $headers );
		$content = $this->decode_body ( $this->returnedHeaders, $content );
		$this->returnedPageRaw = $Page;
		$this->returnedPage = $content;
		return $content;
	}
	private $returnedPageRaw = '';
	public function ReturnedPageRaw() {
		return $this->returnedPageRaw;
	}
	private $returnedHeaders = array ();
	public function returnedHeaders() {
		return $this->returnedHeaders;
	}
	private $returnedPage = "";
	public function returnedPage() {
		return $this->returnedPage;
	}
	private function _request($Stream, $Request, $Method = 'GET', $Data = "") {
		fwrite ( $Stream, $Request );
		if ($Method == 'POST') {
			fputs ( $Stream, $Data );
		}
		$Page = '';
		while ( ! feof ( $Stream ) ) {
			$Page .= fread ( $Stream, 128 );
		}
		fclose ( $Stream );
		return $Page;
	}
	//--------------------------------------------------------------------------------------------------------------------
	/*
	* Headers
	* explode headers
	* @param	string	$Headers
	* @return	array
	*/
	private function _headers($Headers) {
		foreach ( ( array ) explode ( "\r\n", $Headers ) as $Header ) {
			$part = explode ( ": ", $Header, 2 );
			if (count ( $part ) == 1) {
				$part = explode ( " ", $Header, 2 );
			}
			$key = $part [0];
			$value = $part [1];
			$RetVal [strtoupper ( $key )] = $value;
		}
		return $RetVal;
	}
	private function decode_body($info, $str, $eol = "\r\n") {
		$tmp = $str;
		$add = strlen ( $eol );
		$str = '';
		if (isset ( $info ['TRANSFER-ENCODING'] ) && $info ['TRANSFER-ENCODING'] == 'chunked') {
			do {
				$tmp = ltrim ( $tmp );
				$pos = strpos ( $tmp, $eol );
				$len = hexdec ( substr ( $tmp, 0, $pos ) );
				if (isset ( $info ['CONTENT-ENCODING'] )) {
					$str .= gzinflate ( substr ( $tmp, ($pos + $add + 10), $len ) );
				} else {
					$str .= substr ( $tmp, ($pos + $add), $len );
				}
				$tmp = substr ( $tmp, ($len + $pos + $add) );
				$check = trim ( $tmp );
			} while ( ! empty ( $check ) );
		} else if (isset ( $info ['CONTENT-ENCODING'] )) {
			$str = gzinflate ( substr ( $tmp, 10 ) );
		} else {
			$str = $tmp;
		}
		return $str;
	}
}