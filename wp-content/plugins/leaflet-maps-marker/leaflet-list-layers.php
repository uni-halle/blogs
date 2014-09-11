<?php
/*
    List all layers - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-list-layers.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
?>
<div class="wrap">
<?php include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php'); ?>
<h3 style="font-size:23px;"><?php _e('List all layers','lmm') ?></h3>
<?php
global $wpdb;
$lmm_options = get_option( 'leafletmapsmarker_options' );
//info: security check if input variable is valid
$columnsort_values = array('id','multi_layer_map','name','m.panel','zoom','basemap','createdon','createdby','updatedon','updatedby','controlbox');
$columnsort_input = isset($_GET['orderby']) ? esc_sql($_GET['orderby']) : $lmm_options[ 'misc_layer_listing_sort_order_by' ];
$columnsort = (in_array($columnsort_input, $columnsort_values)) ? $columnsort_input : $lmm_options[ 'misc_layer_listing_sort_order_by' ];
//info: security check if input variable is valid
$columnsortorder_values = array('asc','desc','ASC','DESC');
$columnsortorder_input = isset($_GET['order']) ? esc_sql($_GET['order']) : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
$columnsortorder = (in_array($columnsortorder_input, $columnsortorder_values)) ? $columnsortorder_input : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
$layerlist = $wpdb->get_results( "SELECT * FROM `$table_name_layers` WHERE `id` > 0 order by `$columnsort` $columnsortorder", ARRAY_A );
$lcount = intval($wpdb->get_var('SELECT COUNT(*)-1 FROM '.$table_name_layers));
?>

<div style="display:inline;">
<p>
<span id="exportlinkstext"><a style="text-decoration:none;cursor:pointer;"><?php _e('Show API links for all markers','lmm'); ?></a></span></div>
</p>
<div id="exportlinks" style="display:none;">
<p>
<?php echo "<a href=\"" . LEAFLET_PLUGIN_URL . "leaflet-kml.php?layer=all&name=" . $lmm_options[ 'misc_kml' ] . "\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-kml.png\" /></a> <a href=\"" . LEAFLET_PLUGIN_URL . "leaflet-kml.php?layer=all&name=" . $lmm_options[ 'misc_kml' ] . "\" style=\"text-decoration:none;\">" . __('Export all markers as KML','lmm') . "</a> <a href=\"https://www.mapsmarker.com/kml\" target=\"_blank\" title=\"" . esc_attr__('Click here for more information on how to use as KML in Google Earth or Google Maps','lmm') . "\"> <img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-question-mark.png\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-geojson.php?layer=all&full=yes&full_icon_url=yes\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-json.png\" /></a> <a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-geojson.php?layer=all&full=yes&full_icon_url=yes\" style=\"text-decoration:none;\">" . __('Export all markers as GeoJSON','lmm') . "</a> <a href=\"https://www.mapsmarker.com/geojson\" target=\"_blank\" title=\"" . esc_attr__('Click here for more information on how to integrate GeoJSON into external websites or apps','lmm') . "\"> <img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-question-mark.png\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-georss.php?layer=all\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-georss.png\" /></a> <a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-georss.php?layer=all\" style=\"text-decoration:none;\">" . __('Subscribe to markers via GeoRSS','lmm') . "</a> <a href=\"https://www.mapsmarker.com/georss\" target=\"_blank\" title=\"" . esc_attr__('Click here for more information on how to subscribe to new markers via GeoRSS','lmm') . "\"> <img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-question-mark.png\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-wikitude.php?layer=all\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-wikitude.png\" /></a> <a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-wikitude.php?layer=all\" style=\"text-decoration:none;\">" . __('Export all markers as ARML for Wikitude','lmm') . "</a> <a href=\"https://www.mapsmarker.com/wikitude\" target=\"_blank\" title=\"" . esc_attr__('Click here for more information on how to display in Wikitude Augmented-Reality browser','lmm') . "\"> <img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-question-mark.png\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"" . LEAFLET_PLUGIN_URL . "leaflet-geositemap.php\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-sitemap.png\" /></a> <a target=\"_blank\" href=\"" . LEAFLET_PLUGIN_URL . "leaflet-geositemap.php\" style=\"text-decoration:none;\">" . __('Geo Sitemap','lmm') . "</a>&nbsp;<a href=\"https://www.mapsmarker.com/geo-sitemap\" target=\"_blank\" title=\"" . esc_attr__('Click here for more information on how to submit your Geo Sitemap to Google','lmm') . "\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-question-mark.png\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"https://www.mapsmarker.com/mapsmarker-api\" style=\"text-decoration:none;\"><img src=\"" . LEAFLET_PLUGIN_URL . "inc/img/icon-menu-page.png\" /></a> <a href=\"" . LEAFLET_WP_ADMIN_URL . "admin.php?page=leafletmapsmarker_settings#lmm-misc-section9\" title=\"" . esc_attr__('Click here for more information on how to use the MapsMarker API','lmm') . "\" style=\"text-decoration:none;\">Maps Marker API</a>"; ?>
</p>
</div>
<p>
<?php _e('Total','lmm') ?>: <?php echo $lcount; ?> <?php  echo $lcount_singular_plural = ($lcount == 1) ? __('layer','lmm') : __('layers','lmm'); ?>
</p>
<?php
$getorder = isset($_GET['order']) ? $_GET['order'] : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
if ($getorder == 'asc') { $sortorder = 'desc'; } else { $sortorder= 'asc'; };
if ($getorder == 'asc') { $sortordericon = 'asc'; } else { $sortordericon = 'desc'; };
?>
<table cellspacing="0" class="wp-list-table widefat fixed">
  <thead>
  <tr>
    <th class="manage-column column-id sortable <?php echo $sortordericon; ?>" id="id" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=id&order=<?php echo $sortorder; ?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-type sortable <?php echo $sortordericon; ?>" id="type" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=multi_layer_map&order=<?php echo $sortorder; ?>"><span><?php _e('Type', 'lmm') ?></span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-layername sortable <?php echo $sortordericon; ?>" id="name" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=name&order=<?php echo $sortorder; ?>"><span><?php _e('Name', 'lmm') ?></span><span class="sorting-indicator"></span></a></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) { ?>
	<th class="manage-column column-address" scope="col"><?php _e('Location', 'lmm') ?></th><?php } ?>
    <th class="manage-column column-count" scope="col">#&nbsp;<?php _e('Markers', 'lmm') ?></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) { ?>
	<th class="manage-column column-coords" scope="col"><?php _e('Layer center', 'lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) { ?>
	<th class="manage-column column-mapsize" scope="col"><?php _e('Map size','lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) { ?>
	<th class="manage-column column-openpopup"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=m.panel&order=<?php echo $sortorder; ?>"><span><?php _e('Panel status', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) { ?>
	<th class="manage-column column-zoom" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=zoom&order=<?php echo $sortorder; ?>"><span><?php _e('Zoom', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) { ?>
	<th class="manage-column column-basemap" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=basemap&order=<?php echo $sortorder; ?>"><span><?php _e('Basemap', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) { ?>
	<th class="manage-column column-createdby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdby&order=<?php echo $sortorder; ?>"><span><?php _e('Created by', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) { ?>
	<th class="manage-column column-createdon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdon&order=<?php echo $sortorder; ?>"><span><?php _e('Created on', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) { ?>
	<th class="manage-column column-updatedby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedby&order=<?php echo $sortorder; ?>"><span><?php _e('Updated by', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) { ?>
	<th class="manage-column column-updatedon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedon&order=<?php echo $sortorder; ?>"><span><?php _e('Updated on', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=controlbox&order=<?php echo $sortorder; ?>"><span><?php _e('Controlbox status', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><?php _e('Shortcode', 'lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_kml' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_kml' ] == 1 )) { ?>
	<th class="manage-column column-kml" scope="col">KML<a href="https://www.mapsmarker.com/kml" target="_blank" title="<?php esc_attr_e('Click here for more information on how to use as KML in Google Earth or Google Maps','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_fullscreen' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_fullscreen' ] == 1 )) { ?>
	<th class="manage-column column-fullscreen" scope="col"><?php _e('Fullscreen', 'lmm') ?><span title="<?php esc_attr_e('Open standalone map in fullscreen mode','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></span></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_qr_code' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_qr_code' ] == 1 )) { ?>
	<th class="manage-column column-qr-code" scope="col"><?php _e('QR code', 'lmm') ?><span title="<?php esc_attr_e('Create QR code image for standalone map in fullscreen mode','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></span></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_geojson' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_geojson' ] == 1 )) { ?>
	<th class="manage-column column-geojson" scope="col">GeoJSON<a href="https://www.mapsmarker.com/geojson" target="_blank" title="<?php esc_attr_e('Click here for more information on how to integrate GeoJSON into external websites or apps','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_georss' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_georss' ] == 1 )) { ?>
	<th class="manage-column column-georss" scope="col">GeoRSS<a href="https://www.mapsmarker.com/georss" target="_blank" title="<?php esc_attr_e('Click here for more information on how to subscribe to new markers via GeoRSS','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_wikitude' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_wikitude' ] == 1 )) { ?>
	<th class="manage-column column-wikitude" scope="col">Wikitude<a href="https://www.mapsmarker.com/wikitude" target="_blank" title="<?php esc_attr_e('Click here for more information on how to display in Wikitude Augmented-Reality browser','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
</tr>
  </thead>
  <tfoot>
  <tr>
     <th class="manage-column column-id sortable <?php echo $sortordericon; ?>" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=id&order=<?php echo $sortorder; ?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-type sortable <?php echo $sortordericon; ?>" id="type" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=multi_layer_map&order=<?php echo $sortorder; ?>"><span><?php _e('Type', 'lmm') ?></span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-layername sortable <?php echo $sortordericon; ?>" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=name&order=<?php echo $sortorder; ?>"><span><?php _e('Name', 'lmm') ?></span><span class="sorting-indicator"></span></a></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) { ?>
	<th class="manage-column column-address" scope="col"><?php _e('Location', 'lmm') ?></th><?php } ?>
    <th class="manage-column column-count" scope="col">#&nbsp;<?php _e('Markers', 'lmm') ?></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) { ?>
	<th class="manage-column column-coords" scope="col"><?php _e('Layer center', 'lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) { ?>
	<th class="manage-column column-mapsize" scope="col"><?php _e('Map size','lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) { ?>
	<th class="manage-column column-openpopup"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=m.panel&order=<?php echo $sortorder; ?>"><span><?php _e('Panel status', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) { ?>
	<th class="manage-column column-zoom" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=zoom&order=<?php echo $sortorder; ?>"><span><?php _e('Zoom', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) { ?>
	<th class="manage-column column-basemap" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=basemap&order=<?php echo $sortorder; ?>"><span><?php _e('Basemap', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) { ?>
	<th class="manage-column column-createdby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdby&order=<?php echo $sortorder; ?>"><span><?php _e('Created by', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) { ?>
	<th class="manage-column column-createdon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdon&order=<?php echo $sortorder; ?>"><span><?php _e('Created on', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) { ?>
	<th class="manage-column column-updatedby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedby&order=<?php echo $sortorder; ?>"><span><?php _e('Updated by', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) { ?>
	<th class="manage-column column-updatedon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedon&order=<?php echo $sortorder; ?>"><span><?php _e('Updated on', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=controlbox&order=<?php echo $sortorder; ?>"><span><?php _e('Controlbox status', 'lmm') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><?php _e('Shortcode', 'lmm') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_kml' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_kml' ] == 1 )) { ?>
	<th class="manage-column column-kml" scope="col">KML<a href="https://www.mapsmarker.com/kml" target="_blank" title="<?php esc_attr_e('Click here for more information on how to use as KML in Google Earth or Google Maps','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_fullscreen' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_fullscreen' ] == 1 )) { ?>
	<th class="manage-column column-fullscreen" scope="col"><?php _e('Fullscreen', 'lmm') ?><span title="<?php esc_attr_e('Open standalone map in fullscreen mode','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></span></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_qr_code' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_qr_code' ] == 1 )) { ?>
	<th class="manage-column column-qr-code" scope="col"><?php _e('QR code', 'lmm') ?><span title="<?php esc_attr_e('Create QR code image for standalone map in fullscreen mode','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></span></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_geojson' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_geojson' ] == 1 )) { ?>
	<th class="manage-column column-geojson" scope="col">GeoJSON<a href="https://www.mapsmarker.com/geojson" target="_blank" title="<?php esc_attr_e('Click here for more information on how to integrate GeoJSON into external websites or apps','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_georss' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_georss' ] == 1 )) { ?>
	<th class="manage-column column-georss" scope="col">GeoRSS<a href="https://www.mapsmarker.com/georss" target="_blank" title="<?php esc_attr_e('Click here for more information on how to subscribe to new markers via GeoRSS','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_wikitude' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_wikitude' ] == 1 )) { ?>
	<th class="manage-column column-wikitude" scope="col">Wikitude<a href="https://www.mapsmarker.com/wikitude" target="_blank" title="<?php esc_attr_e('Click here for more information on how to display in Wikitude Augmented-Reality browser','lmm') ?>">&nbsp;<img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a></th><?php } ?>
</tr>
  </tfoot>
  <tbody id="the-list">
<?php
  $layernonce = wp_create_nonce('layer-nonce'); //for delete-links
  if (count($layerlist) < 1)
    echo '<tr><td colspan="7">' . __('No layer created yet', 'lmm') . '</td></tr>';
  else
    foreach ($layerlist as $row)
		{
		$markercount = 0; //info: needed for multi-layer-map count-bug
		if (current_user_can( $lmm_options[ 'capabilities_delete' ])) {
			$delete_link_layer = '<div style="float:right;"><a onclick="if ( confirm( \'' . esc_attr__('Do you really want to delete this layer?','lmm') . ' (ID ' . $row['id'] . ')\' ) ) { return true;}return false;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&action=delete&id=' . $row['id'] . '&_wpnonce=' . $layernonce . '" class="submit delete">' . __('delete layer','lmm') . '</a></div>';
		} else {
			$delete_link_layer = '';
		}
		$column_address = ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) ? '<td class="lmm-border">' . $row['address'] . '</td>' : '';
		if ($row['multi_layer_map'] == 0) {
			$markercount = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id='.$row['id']);
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] == 'all' ) ) {
			$markercount = intval($wpdb->get_var('SELECT COUNT(*) FROM '.$table_name_markers));
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] != NULL ) && ($row['multi_layer_map_list'] != 'all') ) {
			$multi_layer_map_list_exploded = explode(",", $wpdb->get_var('SELECT l.multi_layer_map_list FROM `'.$table_name_layers.'` as l WHERE l.id='.$row['id']));
			foreach ($multi_layer_map_list_exploded as $mlmrowcount){
				$mlm_count_temp{$mlmrowcount} = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE m.layer='.$mlmrowcount);
				$markercount = $markercount + $mlm_count_temp{$mlmrowcount};
			}
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] == NULL ) ) {
			$markercount = 0;
		}
		$multi_layer_map_type = ($row['multi_layer_map'] == 0) ? '&nbsp;&nbsp;<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-layer.png" width="16" height="16" title="' . esc_attr__('single layer map','lmm') . '" />' : '&nbsp;&nbsp;<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-multi_layer_map.png" width="16" height="16" title="' . esc_attr__('multi layer map','lmm') . '" />';
	    $openpanelstatus = ($row['panel'] == 1) ? __('visible','lmm') : __('hidden','lmm');
	 	if ($row['controlbox'] == 0) { $controlboxstatus = __('hidden','lmm'); } else if ($row['controlbox'] == 1) { $controlboxstatus = __('collapsed (except on mobiles)','lmm'); } else if ($row['controlbox'] == 2) { $controlboxstatus = __('expanded','lmm'); };

		 //info: set column display variables - need for for-each
		 $column_layercenter = ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) ? '<td class="lmm-border">Lat: ' . $row['layerviewlat'] . '<br/>Lon: ' . $row['layerviewlon'] . '</td>' : '';
		 $column_mapsize = ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) ? '<td class="lmm-border">' . __('Width','lmm') . ': '.$row['mapwidth'].$row['mapwidthunit'].'<br/>' . __('Height','lmm') . ': '.$row['mapheight'].'px</td>' : '';
	     $column_panelstatus = ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) ?
'<td class="lmm-border">' . $openpanelstatus . '</td>' : '';
		 $column_zoom = ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border">' . $row['layerzoom'] . '</td>' : '';
		 $column_controlbox = ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border">' . $controlboxstatus . '</td>' : '';
		 //info: workaround - select shortcode on input focus doesnt work on iOS
		 global $wp_version;
		 if ( version_compare( $wp_version, '3.4', '>=' ) ) {
			 $is_ios = wp_is_mobile() && preg_match( '/iPad|iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] );
			 $shortcode_select = ( $is_ios ) ? '' : 'onfocus="this.select();" readonly="readonly"';
		 } else {
			 $shortcode_select = '';
		 }
 		 $column_shortcode = ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) ? '<td class="lmm-border"><input ' . $shortcode_select . ' style="width:206px;background:#f3efef;" type="text" value="[' . htmlspecialchars($lmm_options[ 'shortcode' ]) . ' layer=&quot;' . $row['id'] . '&quot;]"></td>' : '';
		 $column_kml = ((isset($lmm_options[ 'misc_layer_listing_columns_kml' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_kml' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-kml.php?layer=' . $row['id'] . '&name=' . $lmm_options[ 'misc_kml' ] . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-kml.png" width="14" height="14" alt="KML-Logo" /><br/>KML</a></td>' : '';
		 $column_fullscreen = ((isset($lmm_options[ 'misc_layer_listing_columns_fullscreen' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_fullscreen' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-fullscreen.php?layer=' . $row['id'] . '" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-fullscreen.png" width="14" height="14" alt="Fullscreen-Logo"><br/>' . __('Fullscreen','lmm') . '</a></td>' : '';
	     $column_qr_code = ((isset($lmm_options[ 'misc_layer_listing_columns_qr_code' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_qr_code' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-qr.php?layer=' . $row['id'] . '&callback=jsonp&full=yes&full_icon_url=yes" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-qr-code.png" width="14" height="14" alt="QR-code-logo"><br/>' . __('QR code','lmm') . '</a></td>' : '';
		if ($row['multi_layer_map'] == 0) {
			 $column_geojson = ((isset($lmm_options[ 'misc_layer_listing_columns_geojson' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_geojson' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-geojson.php?layer=' . $row['id'] . '&callback=jsonp&full=yes&full_icon_url=yes" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-json.png" width="14" height="14" alt="GeoJSON-logo"><br/>GeoJSON</a></td>' : '';
		} else if ($row['multi_layer_map'] == 1) {
			$column_geojson = ((isset($lmm_options[ 'misc_layer_listing_columns_geojson' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_geojson' ] == 1 )) ? '<td class="lmm-border"></td>' : '';
		}
		 $column_georss = ((isset($lmm_options[ 'misc_layer_listing_columns_georss' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_georss' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-georss.php?layer=' . $row['id'] . '" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" width="14" height="14" alt="GeoRSS-logo"><br/>GeoRSS</a></td>' : '';
		 $column_wikitude = ((isset($lmm_options[ 'misc_layer_listing_columns_wikitude' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_wikitude' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-wikitude.php?layer=' . $row['id'] . '" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-wikitude.png" width="14" height="14" alt="Wikitude-logo"><br/>Wikitude</a></td>' : '';
		 $column_basemap = ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) ? '<td class="lmm-border">' . $row['basemap'] . '</td>' : '';
		 $column_createdby = ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) ? '<td class="lmm-border">' . $row['createdby'] . '</td>' : '';
		 $column_createdon = ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) ? '<td class="lmm-border">' . $row['createdon'] . '</td>' : '';
		 $column_updatedby = ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) ? '<td class="lmm-border">' . $row['updatedby'] . '</td>' : '';
		 $column_updatedon = ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) ? '<td class="lmm-border">' . $row['updatedon'] . '</td>' : '';
		 $add_new_marker_to_layer = ( $row['multi_layer_map'] == 0 ) ? '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker&addtoLayer=' . $row['id'] . '&Layername=' . urlencode(stripslashes($row['name'])) . '" style="text-decoration:none;">' . __('add new marker to this layer','lmm') . '</a>' : '';
		echo '<tr valign="middle" class="alternate" id="link-' . $row['id'] . '">
		<td class="lmm-border">'.$row['id'].'</td>
		<td class="lmm-border">'.$multi_layer_map_type.'</td>
		<td class="lmm-border"><strong><a title="' . esc_attr__('Edit', 'lmm') . ' &laquo;' . htmlspecialchars($row['name']) . '&raquo;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '" class="row-title">' . stripslashes(htmlspecialchars($row['name'])) . '</a></strong><br><div class="row-actions"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '">' . __('edit layer','lmm') . '</a></span>'. $add_new_marker_to_layer . $delete_link_layer . '</div></td>
		  ' . $column_address . '
		<td style="text-align:center;" class="lmm-border"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '#assigned_markers" title="' . esc_attr__('show markers assigned to this layer','lmm') . '">'.$markercount.'</a></td>
		  ' . $column_layercenter . '
		  ' . $column_mapsize . '
		  ' . $column_panelstatus . '
		  ' . $column_zoom . '
		  ' . $column_basemap . '
		  ' . $column_createdby . '
		  ' . $column_createdon . '
		  ' . $column_updatedby . '
		  ' . $column_updatedon . '
		  ' . $column_controlbox . '
		  ' . $column_shortcode . '
		  ' . $column_kml . '
		  ' . $column_fullscreen . '
		  ' . $column_qr_code . '
		  ' . $column_geojson . '
		  ' . $column_georss . '
		  ' . $column_wikitude . '
		</tr>';
		}
?>
  </tbody>
</table>
<script type="text/javascript">
//info: show all API links on click on simplified editor
(function($) {
	$('#exportlinkstext').click(function(e) {
			$('#exportlinkstext').hide();
			$('#exportlinks').show();
	});
})(jQuery)
</script>
<?php include('inc' . DIRECTORY_SEPARATOR . 'admin-footer.php'); ?>