<?php
	$style = $_GET['style'];
	setcookie("style", $style, time()+604800, '/'); // 604800 = amount of seconds in one week
	if(isset($_GET['js'])) {
		echo $style;
	} else {
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
?>