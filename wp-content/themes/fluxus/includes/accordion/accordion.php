<?php
function fluxus_accordion_enqueue() {
	wp_enqueue_script('fluxus_accordion', get_template_directory_uri() . '/includes/accordion/accordion.js', array('jquery'), 'r3', true);
}
add_action( 'wp_enqueue_scripts', 'fluxus_accordion_enqueue' );


add_shortcode('acc-start', 'acc_start');
function acc_start() {
	return "<section class='accordionsection'>";
}
add_shortcode('acc-end', 'acc_end');
function acc_end() {
	return "</section>";
}


?>