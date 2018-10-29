<?php

namespace mlu ;

class client_token {
	public $key,$data;
	function __construct ($strKey,$strData) {
		$this->key=trim($strKey);
		$this->data=trim($strData);
	}
}
class client_verification {
	protected $alg;
	protected $trim;
	public function __construct ($algo='sha512',$tokenTrim=2) {
		$this->alg=$algo;
		$this->trim=$tokenTrim;
	}
	protected function hash ($Fields) {
		$data=implode('\n',$Fields);
		return hash($this->alg,$data);
	}
	public static function clientConnection() {
		return array_merge(func_get_args(),array(
			$_SERVER['SERVER_ADDR'],
			$_SERVER['DOCUMENT_ROOT'],
			$_SERVER['HTTP_HOST'],
			$_SERVER['HTTPS'],
			$_SERVER['HTTP_REMOTE_ADDR']
		));
	}
	public static function clientBrowser() {
		return array_merge(func_get_args(),array(
			$_SERVER['HTTP_HOST'],
			$_SERVER['HTTP_USER_AGENT'],
			$_SERVER['HTTP_ACCEPT_LANGUAGE'],
			$_SERVER['HTTP_ACCEPT_CHARSET'],
		));
	}
	protected static function rand ($min,$max) {
		$num=abs((int)hexdec(bin2hex(openssl_random_pseudo_bytes(7))));
		return $min+($num%($max-$min+1));
	}
	protected function make_token($Fields,$length,&$OffsetCapture) {
		$Hash=$this->hash($Fields);
		$shift=static::rand($this->trim,strlen($Hash)-$length-$this->trim);
		$token=substr($Hash,$shift,$length);
		$OffsetCapture=strpos($Hash,$token);
		return $token;
	}
	public function getToken($keyFields,$dataFields,$keyLength=8) {
		$key=$this->make_token($keyFields,$keyLength,$offset);
		$dataFields[]="$key|$offset|$keyLength";
		$data=$this->hash($dataFields);
		return new client_token ($key,$data);
	}
	public function checkToken(client_token $token,$keyFields,$dataFields,$keyLength=8) {
		$hashKey=$this->hash($keyFields);
		$hashLen=strlen($hashKey);
		$key=$token->key;
		$pos=strpos($hashKey,$key);
		$len=strlen($key);
		switch(true) {
			case $keyLength != $len:
			case false===$pos:
			case $pos < $this->trim:
			case $pos >= $hashLen-$keyLength-$this->trim:
				return 1;
		}
		# valid key, make seed and check data
		$dataFields[]="$key|$pos|$keyLength";
		$hashData=$this->hash($dataFields);
		if($hashData===$token->data) return 0;
		return 2;
	}
}

