<?php
/**
 * 
 * @wordpress-plugin
 * 
 * Plugin Name: * mluBlogs Kommentar-Filter [BETA2]
 * Author: Blogs@MLU Team
 * Author URI: mailto:blogs@uni-halle.de
 * Version: 0.2
 * Description: Erweitert den Wordpress-Spamfilter um eine Funktion, die das interagieren des Browsers/Nutzers erfordert.
 *
 */

namespace mlu ;

require_once 'client_ver.php';

### WORDPRESS 

header('x-mlu-as: loaded');

add_filter('comment_id_fields',function($fields){
	if(\is_user_logged_in()) return $fields;
	if(!($post_id=get_the_ID())) {
		trigger_error('ClientVerification: Could not find Post-ID',E_USER_ERROR);
	}
	$ver = new client_verification();
	$token= $ver->getToken(client_verification::clientConnection($post_id),client_verification::clientBrowser());
	$fields .= str_replace(
		array('#1#','#2#','#3#'),
		array($token->data,$token->key,"cver"),
		<<<HTML
<p id="_cp" style="float:none;clear:both;padding:.5em 0;margin:0;">
	<label for="_ci" style="display:block; padding:0 0 0 0.5em; line-height:2.5em;border: 1px solid #f90;">
	<input type="checkbox" name="#3##2#" value="#1#" id="_ci"> Ich bin keine Maschine.</label>
</p>
<script type="text/javascript" id="_cs">(function(){try{var x=jQuery||$;x('#_ci').attr({Type:'hidden'}).removeAttr('id').insertAfter('#_cs');x('#_cs,#_cp').remove();}catch{}})();</script>
HTML
);
	return $fields;
});

add_filter('pre_comment_approved', function($status,$meta=null) {
	switch(true) {
		case is_user_logged_in() : return $status;
		case $meta : $pid=(int)$meta['comment_post_ID']; break;
		case $_POST : $pid=(int)$_POST['comment_post_ID']; break;
		case !$pid : return $status; # ERROR with pid
	}

	$expr=sprintf('/^(%s([0-9a-f]{8}))$/','cver');
	
	if(!((
		$fields=array_values(preg_grep($expr,array_keys($_POST)))
	)&&(
		$items=preg_replace($expr,'\2',$fields)
	)&&(
		$data=array_map(function($field){return $_POST[$field];},$fields)
	))) {
		header('x-verify: missing field');
		return 'spam';
	}
	$token=new client_token($items[0],$data[0]);
	$ver = new client_verification();
	$result = $ver->checkToken($token,client_verification::clientConnection($pid),client_verification::clientBrowser());
	switch($result) {
		case 0: return 1; # allow comment
		case 1: return 'spam'; # trash it
		case 2: return $status; # let users decide
	}
});



