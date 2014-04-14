<?php
header('Content-Type: text/css; charset=UTF-8'); //info: to prevent console warning on chrome

$defaults_marker_popups_maxwidth = isset($_GET['defaults_marker_popups_maxwidth']) ? intval($_GET['defaults_marker_popups_maxwidth']) : '';
$defaults_marker_popups_image_css = isset($_GET['defaults_marker_popups_image_css']) ? urldecode($_GET['defaults_marker_popups_image_css']) : '';

echo "
/* TinyMCE specific rules for Leaflet Maps Marker */
html .mcecontentbody {
	font: 12px/1.4 'Helvetica Neue',Arial,Helvetica,sans-serif;
	max-width:" . $defaults_marker_popups_maxwidth . "px;
	word-wrap: break-word;
}
.mcecontentbody a {
	text-decoration:none;
}
.mcecontentbody a:hover {
	text-decoration:underline;
}
.mcecontentbody img {
	" . $defaults_marker_popups_image_css . "
}
"; ?>