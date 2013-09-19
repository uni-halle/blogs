<?php
class b8v_Type_String extends b8v_Type_Abstract {
	public static function is($object) {
		return (is_object ( $object ) && __CLASS__ == get_class ( $object ));
	}
	public function value($value = null) {
		if (! is_null ( $value )) {
			$value = ( string ) $value;
		}
		return parent::value ( $value );
	}
	public static function staticEndsWith($haystack, $needle, $caseInsensative = true) {
		if ($caseInsensative) {
			$haystack = strtolower ( $haystack );
			$needle = strtolower ( $needle );
		}
		return substr ( $haystack, - strlen ( $needle ) ) == $needle;
	}
	public static function staticStartsWith($haystack, $needle, $caseInsensative = true) {
		if ($caseInsensative) {
			$haystack = strtolower ( $haystack );
			$needle = strtolower ( $needle );
		}
		return substr ( $haystack, 0, strlen ( $needle ) ) == $needle;
	}
	// checks the last chracters are what is expect and if not ads them
	public static function staticAddEnding($string, $end) {
		if (! self::staticEndsWith ( $string, $end )) {
			$string .= $end;
		}
		return $string;
	}
	public static function staticPlural($number, $singular, $plural) {
		if ($number != 1 && $number != - 1) {
			return $plural;
		}
		return $singular;
	}
	public static function staticStringToHex($string) {
		$return = "";
		$length = strlen ( $string );
		for($cnt = 0; $cnt < $length; $cnt ++) {
			$return .= ' x' . dechex ( ord ( substr ( $string, $cnt, 1 ) ) );
		}
		return $return;
	}
	private static function staticGetPair($string) {
		$stringA = explode ( " = ", $string );
		foreach ( $stringA as $key => $string ) {
			$stringA [$key] = substr ( $string, 1, strlen ( $string ) - 2 );
		}
		return $stringA;
	}
	public static function staticGetFirst($string) {
		$return = "";
		$stringA = self::getPair ( $string );
		if (count ( $stringA ) >= 1) {
			$return = $stringA [0];
		}
		return $return;
	}
	public static function staticGetSecond($string) {
		$return = "";
		$stringA = self::getPair ( $string );
		if (count ( $stringA ) >= 2) {
			$return = $stringA [1];
		}
		return $return;
	}
	public static function staticSafe($string) {
		$string = str_replace ( ' ', '-', $string );
		$string = strtolower ( $string );
		return $string;
	}
}