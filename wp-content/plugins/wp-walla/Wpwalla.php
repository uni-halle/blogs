<?php
    class Wpwalla
	{
		private $_wpwallaname;
		private $_wpwallaurl;
		private $_cacheFile = 'wpwallacache.txt';
		private $_cacheMintues;
		public $wpwalladata;
		
		public function __construct($wpwallaoptions)
		{
			$gowallaName = $wpwallaoptions['wpwallausername'];
			$cacheTime = $wpwallaoptions['wpwallacache'];
			if($cacheTime < 20) {
				$cacheTime = 20;
			}

			$this->_wpwallaname = $gowallaName;
			$this->_wpwallaurl = 'http://gowalla.com/users/'.$gowallaName.'/visits.atom';
			$this->_cacheMintues = $cacheTime;
		}
		
		public function getWpwallaData()
		{
			$fileName = dirname(__FILE__)."/".$this->_cacheFile;
			if(!file_exists($fileName)) {
				$this->createCacheFile();
			} 
			$fileTime = filemtime($fileName);
			$currentTime = time();
			$cacheTime = mktime(date('H', $currentTime), date('i', $currentTime) - $this->_cacheMintues, date('s', $currentTime), date("m",$currentTime)  , date("d",$currentTime), date("Y",$currentTime));

			if(filesize($fileName) < 200)  { //If the cacheFile is empty
				$cacheTime = time();
			}
			if($cacheTime >= $fileTime) {
				if(function_exists('curl_init')) { // If CURL exists, use it, otherwise use file_get_contents
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $this->_wpwallaurl);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_USERAGENT, 'WP-Walla - Gowalla wordpress plugin http://www.baronen.org/wpwalla/');
					$rawData = curl_exec($ch);
					if(curl_exec($ch) === false) {
						echo curl_error($ch);
					}
					curl_close($ch);
				} else {
					$rawData = @file_get_contents($this->_wpwallaurl); //Get new Data from Gowalla-url
				}
				
				$this->createCacheFile($rawData); //Create the cacheFile
			} else {
				$rawData = file_get_contents($fileName); //Get old Data from Filecache
			}
			$rawData = $this->convertData($rawData);
			$WpwallaXml = simplexml_load_string($rawData);
			return $WpwallaXml;
		}
		
		
		private function createCacheFile($data = '')
		{
			file_put_contents(dirname(__FILE__)."/".$this->_cacheFile, $data); //Create the Cachefile
		}
		
		private function convertData($data)
		{
			$data = str_replace('&', '', $data);
			$data = iconv("UTF-8","UTF-8//IGNORE",$data);
			return $data;
		}
	}
?>