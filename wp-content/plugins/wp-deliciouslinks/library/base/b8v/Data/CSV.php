<?php
class b8v_Data_CSV extends b8v_Data_Abstract {
	public function load() {
		$this->value = $this->staticLoad ( $this->filename, $this->firstLineKeys );
		return $this;
	}
	public function staticLoad($file, $firstLineKeys = false) {
		$array = array ();
		$keys = null;
		if (($handle = fopen ( $this->findfile ( $file ), "r" )) !== false) {
			while ( ($data = fgetcsv ( $handle, 1000, "," )) !== false ) {
				if ($this->header_lines > 0) {
					$this->header_lines --;
				} else {
					$subarray = array ();
					if (is_null ( $keys ) && $firstLineKeys) {
						$keys = $data;
					} else {
						$key = 0;
						foreach ( $data as $datum ) {
							if ($firstLineKeys) {
								$subarray [$keys [$key]] = $datum;
							} else {
								$subarray [] = $datum;
							}
							$key ++;
						}
						$array [] = $subarray;
					}
				}
			}
			fclose ( $handle );
		}
		return $array;
	}
	protected $firstLineKeys = null;
	protected $header_lines = null;
	public function __construct($application, $file, $firstLineKeys = false, $header_lines = 0) {
		$this->firstLineKeys = $firstLineKeys;
		$this->header_lines = $header_lines;
		parent::__construct ( $application, $file );
	}
}