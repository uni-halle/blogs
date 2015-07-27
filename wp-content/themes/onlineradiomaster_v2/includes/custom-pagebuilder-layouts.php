<?php
/**
 * Created by PhpStorm.
 * User: Tobi
 * Date: 12.06.15
 * Time: 17:11
 */

function orma_responsive_prebuilt_page_layouts($layouts){
	$layouts['sidebar-right'] = array ();

	return $layouts;
}
add_filter('siteorigin_panels_prebuilt_layouts', 'orma_responsive_prebuilt_page_layouts');
