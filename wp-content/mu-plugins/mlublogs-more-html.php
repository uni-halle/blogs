<?php



function mlu_allow_simple_scripts() {
	global $allowedposttags;
	$allowedposttags=array_merge($allowedposttags,[
		'script'=>['type'=>true],
		'iframe'=>[]
	]);
}

add_action('init', 'mlu_allow_simple_scripts', 10);
