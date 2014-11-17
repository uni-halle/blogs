<?php
require_once('wp-load.php');

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



