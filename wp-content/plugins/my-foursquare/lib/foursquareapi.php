<?php
function get_foursquare_data($user,$pass,$url) {
	$cURL = curl_init();
	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HEADER, 0);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($cURL, CURLOPT_USERPWD, "$user:$pass");
	$response = curl_exec($cURL);
	curl_close($cURL);
	$xmlObj   	= new XmlToArray($response);
	$arrayData 	= $xmlObj->createArray();
	return $arrayData;
}
?>
