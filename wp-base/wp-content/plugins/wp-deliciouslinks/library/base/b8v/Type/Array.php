<?php
class b8v_Type_Array extends b8v_Type_Abstract implements Iterator, ArrayAccess, Serializable, Countable {
	public static function is($object) {
		return (is_object ( $object ) && __CLASS__ == get_class ( $object ));
	}
	private $convertAllTypes = null;
	public function __construct($array = null) {
		parent::__construct ();
		if (is_null ( $array )) {
			$array = array ();
		}
		$this->value ( $array );
	}
	public function value($value = null) {
		if (! is_null ( $value )) {
			foreach ( $value as $key => $avalue ) {
				if (is_array ( $avalue )) {
					$value [$key] = new self ( $avalue );
				}
			}
			parent::value ( $value );
		}
		return $this;
	}
	private function convert() {
		foreach ( $this->value as $key => $value ) {
			if (is_array ( $value )) {
				$this->value [$key] = new b8v_Type_Array ( $this->application (), $value );
			}
		}
	}
	// iterator functions
	function rewind() {
		return reset ( $this->value );
	}
	function current() {
		return current ( $this->value );
	}
	function key() {
		return $this->xml_key ( key ( $this->value ) );
	}
	function next() {
		return next ( $this->value );
	}
	function valid() {
		return key ( $this->value ) !== null;
	}
	// (end)iterator functions
	// arrayaccess functions
	public function offsetSet($offset, $value) {
		$this->value [$offset] = $value;
	}
	public function offsetExists($offset) {
		return isset ( $this->value [$offset] );
	}
	public function offsetUnset($offset) {
		unset ( $this->value [$offset] );
	}
	public function offsetGet($offset) {
		return isset ( $this->value [$offset] ) ? $this->value [$offset] : null;
	}
	// (end)arrayaccess functions
	// Serializable functions
	public function serialize() {
		return serialize ( $this->value );
	}
	public function unserialize($data) {
		$this->data = unserialize ( $this->value );
	}
	public function getData() {
		return $this->data;
	}
	// (end)Serializable functions
	// Countable funcitons
	public function count() {
		return count ( $this->value );
	}
	// (end) Countable functions
	public static function xml_key($key, $array = null, $start = 0) {
		if (is_null ( $array )) {
			$colon = strrpos ( $key, ':' );
			if ($colon !== false) {
				$key = substr ( $key, 0, $colon );
			}
			return $key;
		} else {
			$return = null;
			foreach ( $array as $xml_key => $value ) {
				if (self::xml_key ( $xml_key ) == $key) {
					if ($start == 0) {
						return $xml_key;
					}
					$start --;
				}
			}
		}
		return false;
	}
	public static function xml_key_find($key, $array, $start = 0) {
		$return = null;
		$key = self::xml_key ( $key, $array, $start );
		if ($key !== FALSE) {
			$return = $array [$key];
		}
		return $return;
	}
	public function merge($array) {
		foreach ( $array as $key => $item ) {
			$this->array [$key] = $item;
		}
	}
}