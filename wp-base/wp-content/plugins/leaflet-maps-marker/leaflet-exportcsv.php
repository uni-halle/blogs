<?php
/*
    Export all markers as CVS file - Leaflet Maps Marker Plugin
*/
//info: construct path to wp-load.php
while(!is_file('wp-load.php')) {
	if(is_dir('..' . DIRECTORY_SEPARATOR)) chdir('..' . DIRECTORY_SEPARATOR);
	else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
//info: check if plugin is active (didnt use is_plugin_active() due to problems reported by users)
function lmm_is_plugin_active( $plugin ) {
	$active_plugins = get_option('active_plugins');
	$active_plugins = array_flip($active_plugins);
	if ( isset($active_plugins[$plugin]) || lmm_is_plugin_active_for_network( $plugin ) ) { return true; }
}
function lmm_is_plugin_active_for_network( $plugin ) {
	if ( !is_multisite() )
		return false;
	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset($plugins[$plugin]) )
				return true;
	return false;
}
if (!lmm_is_plugin_active('leaflet-maps-marker/leaflet-maps-marker.php') ) {
	echo sprintf(__('The plugin "Leaflet Maps Marker" is inactive on this site and therefore this API link is not working.<br/><br/>Please contact the site owner (%1s) who can activate this plugin again.','lmm'), antispambot(get_bloginfo('admin_email')) );
} else {
   global $wpdb;
   $noncelink = isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '';
   if (! wp_verify_nonce($noncelink, 'exportcsv-nonce') ) die("".__('Security check failed - please call this function from the according Leaflet Maps Marker admin page!','lmm')."");
   $lmm_options = get_option( 'leafletmapsmarker_options' );
   if (current_user_can($lmm_options[ 'capabilities_edit' ])) {
   $rows = array();
        $table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
	$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
	$objects = $wpdb->get_results('SELECT m.id as mid, l.name as layername,m.lat as lat, m.popuptext as popuptext, m.openpopup as openpopup, m.lon as lon,m.icon as icon, m.zoom as zoom, m.mapwidth as mapwidth, m.mapwidthunit as mapwidthunit, m.mapheight as mapheight, m.markername as markername, m.createdby as mcreatedby, m.createdon as mcreatedon, m.updatedby as mupdatedby, m.updatedon as mupdatedon, l.createdby as lcreatedby, l.createdon as lcreatedon, l.updatedby as lupdatedby, l.updatedon as lupdatedon, m.overlays_custom as moverlays_custom, m.overlays_custom2 as moverlays_custom2, m.overlays_custom3 as moverlays_custom3, m.overlays_custom4 as moverlays_custom4, m.wms as mwms, m.wms2 as mwms2, m.wms3 as mwms3, m.wms4 as mwms4, m.wms5 as mwms5, m.wms6 as mwms6, m.wms7 as mwms7, m.wms8 as mwms8, m.wms9 as mwms9, m.wms10 as mwms10, m.kml_timestamp as mkml_timestamp, m.address as maddress FROM '.$table_name_markers.' as m LEFT OUTER JOIN '.$table_name_layers.' AS l ON m.layer=l.id order by m.id',OBJECT_K);
	foreach ($objects as $row) {
		$columns = array();
		$columns['id'] = $row->mid;
		$columns['markername'] = stripslashes(str_replace(';', ',', $row->markername));
		$columns['layername'] = stripslashes(str_replace(';', ',', $row->layername));
		$columns['popuptext'] = preg_replace('/(\015\012)|(\015)|(\012)/',' ',strip_tags(stripslashes(str_replace(';', ',', $row->popuptext))));
		$columns['openpopup'] = $row->openpopup;
		$columns['lat'] = str_replace('.', ',', $row->lat);
		$columns['lon'] = str_replace('.', ',', $row->lon);
	    if ($row->icon == null) {
	        $columns['icon'] = LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png';
	    } else {
	        $columns['icon'] = LEAFLET_PLUGIN_ICONS_URL . '/' . $row->icon;
    	}

		$columns['zoom'] = $row->zoom;
		$columns['mapwidth'] = $row->mapwidth;
		$columns['mapwidthunit'] = $row->mapwidthunit;
		$columns['mapheight'] = $row->mapheight;
		$columns['mapheightunit'] = 'px';
		$columns['m.createdby'] = $row->mcreatedby;
		$columns['m.createdon'] = $row->mcreatedon;
		$columns['m.updatedby'] = $row->mupdatedby;
		$columns['m.updatedon'] = $row->mupdatedon;
		$columns['l.createdby'] = $row->lcreatedby;
		$columns['l.createdon'] = $row->lcreatedon;
		$columns['l.updatedby'] = $row->lupdatedby;
		$columns['l.updatedon'] = $row->lupdatedon;
		$columns['m.overlays_custom'] = $row->moverlays_custom;
		$columns['m.overlays_custom2'] = $row->moverlays_custom2;
		$columns['m.overlays_custom3'] = $row->moverlays_custom3;
		$columns['m.overlays_custom4'] = $row->moverlays_custom4;
		$columns['m.wms'] = $row->mwms;
		$columns['m.wms2'] = $row->mwms2;
		$columns['m.wms3'] = $row->mwms3;
		$columns['m.wms4'] = $row->mwms4;
		$columns['m.wms5'] = $row->mwms5;
		$columns['m.wms6'] = $row->mwms6;
		$columns['m.wms7'] = $row->mwms7;
		$columns['m.wms8'] = $row->mwms8;
		$columns['m.wms9'] = $row->mwms9;
		$columns['m.wms10'] = $row->mwms10;
		$columns['m.kml_timestamp'] = $row->mkml_timestamp;
		$columns['m.address'] = $row->maddress;
		$rows[] = join(';',$columns);
	}
        $header = "Markerid;Markername;Layername;PopupText;OpenPopup;Latitude;Longitude;Icon;Zoom;Mapwidth;MapwidthUnit;Mapheight;MapheightUnit;MarkerCreatedBy;MarkerCreatedOn;MarkerUpdatedBy;MarkerUpdatedOn;LayerCreatedBy;LayerCreatedOn;LayerUpdatedBy;LayerUpdatedOn;Overlays_Custom;Overlays_Custom2;Overlays_Custom3;Overlays_Custom4;WMS;WMS2;WMS3;WMS4;WMS5;WMS6;WMS7;WMS8;WMS9;WMS10;KML_Timestamp;Address";
	$file = $header."\n".join("\n",$rows);
	header('Content-Type: text/plain; charset=UTF-8');
	echo $file;
exit;

} else {
	_e('Error - CSV export of all markers not allowed.','lmm');
}
} //info: end plugin active check
?>