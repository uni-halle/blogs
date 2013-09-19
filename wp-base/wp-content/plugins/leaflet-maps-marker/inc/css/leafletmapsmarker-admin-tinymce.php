<?php
//info: construct path to wp-load.php and get $wp_path
while(!is_file('wp-load.php')){
  if(is_dir('../')) chdir('../');
  else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
header('Content-Type: text/css; charset=UTF-8'); //info: to prevent console warning on chrome
$lmm_options = get_option( 'leafletmapsmarker_options' );
echo "
/* TinyMCE specific rules for Leaflet Maps Marker */
html .mcecontentbody {
	font: 12px/1.4 'Helvetica Neue',Arial,Helvetica,sans-serif;
	max-width:" . intval($lmm_options['defaults_marker_popups_maxwidth'] + 1) . "px;
	word-wrap: break-word;
}
.mcecontentbody a {
	text-decoration:none;
}
.mcecontentbody a:hover {
	text-decoration:underline;
}
.mcecontentbody img {
	max-width:" . intval($lmm_options['defaults_marker_popups_image_max_width']) . "px !important;
	height:auto;
}
"; ?>