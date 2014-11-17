<?php
header('x-auth-file: mlu_file_auth.php');
require_once('wp-load.php');
 
is_user_logged_in() || auth_redirect();

require_once "wp-includes/ms-files.php";
