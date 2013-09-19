<?php 
/**
 * 
 *
 * @author Alexandre Hermann
 * @version 1.4
 * @copyright AHWEBDEV, 6 June, 2012
 * @package Facebook AWD
 **/
require_once(dirname(__FILE__).'/facebook.php');

class AWD_facebook_api extends Facebook
{
	
	public function __construct($options)
	{
		self::$CURL_OPTS = $options['curl_options'];
		parent::__construct(array(
			'appId'  => $options['app_id'],
			'secret' => $options['app_secret_key'],
			'timeOut' => $options['timeout'],
		));
		if($options['use_extended_access_token'] == true){
			$this->setExtendedAccessToken();
		}
	}
	
	public function getApplicationAccessToken() {
    	return $this->appId.'|'.$this->appSecret;
  	}
}
?>