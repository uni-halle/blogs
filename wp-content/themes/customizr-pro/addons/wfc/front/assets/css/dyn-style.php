<?php
//Disable error reporting
error_reporting(0);
$s 					= $_SERVER;

//loads WP
$default_path 		= '../../../../../..';
require_once($default_path.'/wp-load.php');

//checks if is customizing
$is_customizing     = isset($_GET['is_customizing']) && 1 == $_GET['is_customizing'] ? true : false;

//gets the last-modified-date in GMT format
$now 				= new DateTime(null, new DateTimeZone('UTC'));
$now             	= $now -> format('D, d M Y H:i:s \G\M\T');
$last_modified 		= get_option( 'tc_font_customizer_last_modified', $now );
//gets a unique hash of this file (etag)
$etag_file 			= md5($last_modified);
//gets the HTTP_IF_MODIFIED_SINCE header if set
$if_modified_since 	= (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false );
//gets the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
$etag_header 		= (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim( $_SERVER['HTTP_IF_NONE_MATCH']) : false );

//sets last-modified header
header( "Last-Modified: {$last_modified}" );
//sets etag-header
header( "Etag: {$etag_file}" );
//sets the content type
header( "Content-type: text/css");

//checks if stylesheet has changed. If not, send 304 and exit.
if ( ! $is_customizing ) {
	//makes sure caching is turned on
	header( "Cache-Control: public" );

	if ( @strtotime($if_modified_since) >= @strtotime($last_modified) || $etag_header == $etag_file ) {
       header("HTTP/1.1 304 Not Modified");
       exit;
    }
}
//loads the dynamic stylesheet
do_action('__dyn_style');
?>
