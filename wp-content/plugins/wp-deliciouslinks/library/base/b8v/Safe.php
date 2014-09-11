<?php
class b8v_Safe extends b8v_Base {
	public function name($value)
	{
		$value = urlencode($value);
		return $value;
	}
	public function id($value)
	{
		$value = str_replace('+','_',$value);
		$value = urlencode($value);
		return $value;
	}
}