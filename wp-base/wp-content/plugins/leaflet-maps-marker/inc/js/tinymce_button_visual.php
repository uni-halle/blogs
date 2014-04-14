<?php
header('Content-Type: text/javascript; charset=UTF-8'); //info: to prevent console warning on chrome
$leaflet_plugin_url = isset($_GET['leafletpluginurl']) ? htmlspecialchars(base64_decode($_GET['leafletpluginurl'])) : '';
$admin_url = isset($_GET['adminurl']) ? htmlspecialchars(base64_decode($_GET['adminurl'])) : '';
$text_add = isset($_GET['textadd']) ? htmlspecialchars(base64_decode($_GET['textadd'])) : '';

echo "jQuery(function($) {
	$(document).ready(function(){
		$('#map_button_visual').remove();
		$(document).on('click', '#map_button_text', function(){
			tb_show('" . $text_add . "', '" . $admin_url . "admin-ajax.php?action=get_mm_list&TB_iframe');
			return false;
		});
		$('#wp-content-media-buttons').append('<a style=\'margin-left:5px;\' class=\'button\' title=\'" . $text_add . "\' id=\'map_button_text\' href=\'#\'><div style=\'float:left;\'><img src=\'" . $leaflet_plugin_url . "inc/img/icon-tinymce.png#\' style=\'padding:0 5px 3px 0;\'></div><div style=\'float:right;padding-top:0px;\'>" . $text_add . "</div></a>');
	});
});";
?>