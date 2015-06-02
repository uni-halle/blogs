<?php
//info: construct path to wp-load.php
while(!is_file('wp-load.php')) {
	if(is_dir('..' . DIRECTORY_SEPARATOR)) chdir('..' . DIRECTORY_SEPARATOR);
	else die('Error: Could not construct path to wp-load.php - please check <a href="https://www.mapsmarker.com/path-error">https://www.mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );

$transient_tinymce_custom_css_get = (isset($_GET['transient']) ? $_GET['transient'] : '');
$transient_tinymce_custom_css = get_transient( 'leafletmapsmarker_tinymce_custom_css' );
	 
if ( ( $transient_tinymce_custom_css !== FALSE) && ($transient_tinymce_custom_css_get == $transient_tinymce_custom_css ) ) { 

	header('Content-Type: text/css; charset=UTF-8'); //info: to prevent console warning on chrome
	$wordpress_version = isset($_GET['wordpress_version']) ? $_GET['wordpress_version'] : '';
	$defaults_marker_popups_maxwidth = isset($_GET['defaults_marker_popups_maxwidth']) ? intval($_GET['defaults_marker_popups_maxwidth']) : '';
	$defaults_marker_popups_image_css = isset($_GET['defaults_marker_popups_image_css']) ? urldecode($_GET['defaults_marker_popups_image_css']) : '';
	
	if ( version_compare( $wordpress_version , '3.9-alpha', '>=' ) ) {
		echo "
		a { text-decoration:none; }
		a:hover { text-decoration:underline; }
		img { " . $defaults_marker_popups_image_css . " }
		"; 
	} else {
		echo "
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
		"; 
	}
	
} else {
	die("".__('Security check failed - please call this function from the according admin page!','lmm').""); 
}
?>