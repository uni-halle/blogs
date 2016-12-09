<?php

require_once('wp-load.php');
/*
call_user_func(function($l){
  $check=function() {
    if(!is_user_logged_in()) return false;
    $u = wp_get_current_user();
    return is_super_admin()||count(wp_get_current_user()->allcaps)>0;
  };
  $quit = function() {
    header('location: '.home_url());
    auth_redirect();
    die();
    return true;
  };
  $check()||$quit();
  require_once $l;
  return 1;
},'wp-includes/ms-files.php');
*/
//echo (int)class_exists('ds_more_privacy_options',false);
#$ds_more_privacy_options->ds_authenticator();
#$ds_more_privacy_options->ds_users_authenticator();
#die();

header('x-files-auth: start');
$check=false;
switch ($current_blog->public) {
	case -1:$check='users'; break;
	case -2:$check='members'; break;
	case -3:$check='admins'; break;
}
#header ('content-type: text/plain');
if($check) {
	$m="ds_{$check}_authenticator";
	header("chk_priv: $m");
	$res=call_user_func([new DS_More_Privacy_Options(),$m]);
	//$ds_more_privacy_options->$m();
}

require_once 'wp-includes/ms-files.php';
