<?php
	
	require_once(dirname(__FILE__) . '/../../../wp-config.php');
	require_once( dirname(__FILE__) . '/wp_rss_poster.php' );
	                                     
	nocache_headers();
	
	$wrp_opts = get_option(WRP_OPTIONS);
	if($wrp_opts['unix_cron'] == 'true') {
		wrp_unix_cron();		
	}
?>
