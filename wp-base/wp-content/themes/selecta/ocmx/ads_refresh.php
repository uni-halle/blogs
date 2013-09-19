<?php
	global $post_details, $my_id;
	require_once("../../../../wp-blog-header.php");
	require_once("ocmx-create-options.php");
	if($_GET['small_ads']) :
		update_option("ocmx_small_ads", $_GET["small_ads"]);
		for($i = 1; $i <= get_option("ocmx_small_ads"); $i++)
			{small_ad_form($i);}
	endif;
?>