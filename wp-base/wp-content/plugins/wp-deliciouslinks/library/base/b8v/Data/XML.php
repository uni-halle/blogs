<?php
class b8v_Data_XML extends b8v_Data_Abstract {
	public function load() {
		$this->array = $this->staticLoad ( $this->filename );
		return $this->array;
	}
	public function staticLoad($file) {
		$data = b8v_Zend_Loader::getFile ( $this->findfile ( $file ) );
		$xml_parser = xml_parser_create ();
		xml_parse_into_struct ( $xml_parser, $data, $vals, $index );
		xml_parser_free ( $xml_parser );
		return $this->decode ( $vals );
	}
	private function decode($xml) {
		$array = array ();
		$sub = array ();
		$complete = array ();
		$tag = null;
		$level = null;
		$id = null;
		foreach ( $xml as $index => $xml_elem ) {
			if ($xml_elem ['type'] == 'open' && is_null ( $level ) && is_null ( $tag )) {
				$tag = $xml_elem ['tag'];
				$level = $xml_elem ['level'];
				$id = $index;
			} elseif ($xml_elem ['type'] == 'close' && $xml_elem ['level'] == $level && $xml_elem ['tag'] = $tag) {
				$data = self::decode ( $sub );
				foreach ( $complete as $key => $value ) {
					$data [$key] = $value;
				}
				$array [$tag . ':' . $id] = $data;
				$tag = null;
				$level = null;
				$sub = array ();
				$complete = array ();
			} elseif ($xml_elem ['type'] == 'complete' && $xml_elem ['level'] == $level + 1) {
				if (array_key_exists ( 'value', $xml_elem )) {
					$complete [$xml_elem ['tag'] . ':' . $index] = $xml_elem ['value'];
				}
				if (array_key_exists ( 'attributes', $xml_elem )) {
					foreach ( $xml_elem ['attributes'] as $key => $value ) {
						$complete [$xml_elem ['tag'] . ':' . $index] [$key . ':' . $index] = $value;
					}
				}
			} else {
				$sub [$index] = $xml_elem;
			}
		}
		return $array;
	}
}