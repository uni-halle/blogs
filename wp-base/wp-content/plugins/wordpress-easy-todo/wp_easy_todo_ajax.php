<?php
require_once('../../../wp-load.php' );
require_once('../../../wp-content/plugins/wordpress-easy-todo/wp_easy_todo.php');

if(!empty($_GET['delete']) && ctype_digit($_GET['delete'])){
	wpet_update('deleted',$_GET['delete']);				
}elseif(!empty($_GET['wpet_ready']) && ctype_digit($_GET['wpet_ready'])){
	wpet_update('done',$_GET['wpet_ready']);
	header('Location: '.$_SERVER['HTTP_REFERER']);
}

?>