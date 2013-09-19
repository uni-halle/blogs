<?php
/*
    Tools - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-tools.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
?>
<div class="wrap">
<?php include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php'); ?>
<?php
global $wpdb;
$lmm_options = get_option( 'leafletmapsmarker_options' );
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$markercount_all = $wpdb->get_var('SELECT count(*) FROM '.$table_name_markers.'');
$layercount_all = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.'') - 1;
$action = isset($_POST['action']) ? $_POST['action'] : '';
if (!empty($action)) {
	$toolnonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '');
	if (! wp_verify_nonce($toolnonce, 'tool-nonce') ) { die('<br/>'.__('Security check failed - please call this function from the according Leaflet Maps Marker admin page!','lmm').''); };
  if ($action == 'mass_assign') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET layer = %d where layer = %d", $_POST['layer_assign_to'], $_POST['layer_assign_from'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('All markers from layer ID %1$s have been successfully assigned to layer ID %2$s','lmm'), htmlspecialchars($_POST['layer_assign_from']), htmlspecialchars($_POST['layer_assign_to'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';

  }
  elseif ($action == 'mass_delete_from_layer') {
		$result = $wpdb->prepare( "DELETE FROM $table_name_markers where layer = %d", $_POST['delete_from_layer']);
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('All markers from layer ID %1$s have been successfully deleted','lmm'), htmlspecialchars($_POST['delete_from_layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'mass_delete_all_markers') {
		$result = "DELETE FROM $table_name_markers";
		$wpdb->query( $result );
  		$delete_confirm_checkbox = isset($_POST['delete_confirm_checkbox']) ? '1' : '0';
	  	if ($delete_confirm_checkbox == 1) {
			echo '<p><div class="updated" style="padding:10px;">' . __('All markers from all layers have been successfully deleted','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		} else {
			echo '<p><div class="error" style="padding:10px;">' . __('Please confirm that you want to delete all markers by checking the checkbox','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
		}
  }
  elseif ($action == 'basemap') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET basemap = %s", $_POST['basemap'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The basemap for all markers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['basemap'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'overlays') {
		$overlays_checkbox = isset($_POST['overlays_custom']) ? '1' : '0';
		$overlays2_checkbox = isset($_POST['overlays_custom2']) ? '1' : '0';
		$overlays3_checkbox = isset($_POST['overlays_custom3']) ? '1' : '0';
		$overlays4_checkbox = isset($_POST['overlays_custom4']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET overlays_custom = %s, overlays_custom2 = %s, overlays_custom3 = %s, overlays_custom4 = %s", $overlays_checkbox, $overlays2_checkbox, $overlays3_checkbox, $overlays4_checkbox );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The overlays status for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'wms') {
		$wms_checkbox = isset($_POST['wms']) ? '1' : '0';
		$wms2_checkbox = isset($_POST['wms2']) ? '1' : '0';
		$wms3_checkbox = isset($_POST['wms3']) ? '1' : '0';
		$wms4_checkbox = isset($_POST['wms4']) ? '1' : '0';
		$wms5_checkbox = isset($_POST['wms5']) ? '1' : '0';
		$wms6_checkbox = isset($_POST['wms6']) ? '1' : '0';
		$wms7_checkbox = isset($_POST['wms7']) ? '1' : '0';
		$wms8_checkbox = isset($_POST['wms8']) ? '1' : '0';
		$wms9_checkbox = isset($_POST['wms9']) ? '1' : '0';
		$wms10_checkbox = isset($_POST['wms10']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET wms = %d, wms2 = %d, wms3 = %d, wms4 = %d, wms5 = %d, wms6 = %d, wms7 = %d, wms8 = %d, wms9 = %d, wms10 = %d", $wms_checkbox, $wms2_checkbox, $wms3_checkbox, $wms4_checkbox, $wms5_checkbox, $wms6_checkbox, $wms7_checkbox, $wms8_checkbox, $wms9_checkbox, $wms10_checkbox );
		$wpdb->query( $result );
		echo '<p><div class="updated" style="padding:10px;">' . __('The WMS status for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'mapsize') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET mapwidth = %d, mapwidthunit = %s, mapheight = %d", $_POST['mapwidth'], $_POST['mapwidthunit'], $_POST['mapheight'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The map size for all markers has been successfully set to width =  %1$s %2$s and height = %3$s px','lmm'), htmlspecialchars($_POST['mapwidth']), htmlspecialchars($_POST['mapwidthunit']), htmlspecialchars($_POST['mapheight'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'zoom') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET zoom = %d", $_POST['zoom'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Zoom level for all markers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['zoom'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'controlbox') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET controlbox = %d", $_POST['controlbox'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Controlbox status for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'panel') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET panel = %d", $_POST['panel'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Panel status for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'icon') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET icon = %s", $_POST['icon'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The icon for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'openpopup') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET openpopup = %d", $_POST['openpopup'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The popup status for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'popuptext') {
		$result = $wpdb->prepare( "UPDATE $table_name_markers SET popuptext = %s", $_POST['popuptext'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_markers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The popup text for all markers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'basemap-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET basemap = %s", $_POST['basemap-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The basemap for all layers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['basemap-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'overlays-layer') {
		$overlays_checkbox = isset($_POST['overlays_custom-layer']) ? '1' : '0';
		$overlays2_checkbox = isset($_POST['overlays_custom2-layer']) ? '1' : '0';
		$overlays3_checkbox = isset($_POST['overlays_custom3-layer']) ? '1' : '0';
		$overlays4_checkbox = isset($_POST['overlays_custom4-layer']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET overlays_custom = %s, overlays_custom2 = %s, overlays_custom3 = %s, overlays_custom4 = %s", $overlays_checkbox, $overlays2_checkbox, $overlays3_checkbox, $overlays4_checkbox );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The overlays status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'wms-layer') {
		$wms_checkbox = isset($_POST['wms-layer']) ? '1' : '0';
		$wms2_checkbox = isset($_POST['wms2-layer']) ? '1' : '0';
		$wms3_checkbox = isset($_POST['wms3-layer']) ? '1' : '0';
		$wms4_checkbox = isset($_POST['wms4-layer']) ? '1' : '0';
		$wms5_checkbox = isset($_POST['wms5-layer']) ? '1' : '0';
		$wms6_checkbox = isset($_POST['wms6-layer']) ? '1' : '0';
		$wms7_checkbox = isset($_POST['wms7-layer']) ? '1' : '0';
		$wms8_checkbox = isset($_POST['wms8-layer']) ? '1' : '0';
		$wms9_checkbox = isset($_POST['wms9-layer']) ? '1' : '0';
		$wms10_checkbox = isset($_POST['wms10-layer']) ? '1' : '0';
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET wms = %d, wms2 = %d, wms3 = %d, wms4 = %d, wms5 = %d, wms6 = %d, wms7 = %d, wms8 = %d, wms9 = %d, wms10 = %d", $wms_checkbox, $wms2_checkbox, $wms3_checkbox, $wms4_checkbox, $wms5_checkbox, $wms6_checkbox, $wms7_checkbox, $wms8_checkbox, $wms9_checkbox, $wms10_checkbox );
		$wpdb->query( $result );
		echo '<p><div class="updated" style="padding:10px;">' . __('The WMS status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'mapsize-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET mapwidth = %d, mapwidthunit = %s, mapheight = %d", $_POST['mapwidth-layer'], $_POST['mapwidthunit-layer'], $_POST['mapheight-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('The map size for all layers has been successfully set to width =  %1$s %2$s and height = %3$s px','lmm'), htmlspecialchars($_POST['mapwidth-layer']), htmlspecialchars($_POST['mapwidthunit-layer']), htmlspecialchars($_POST['mapheight-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'zoom-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET layerzoom = %s", $_POST['zoom-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . sprintf( esc_attr__('Zoom level for all layers has been successfully set to %1$s','lmm'), htmlspecialchars($_POST['zoom-layer'])) . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'controlbox-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET controlbox = %d", $_POST['controlbox-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Controlbox status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'panel-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET panel = %d", $_POST['panel-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('Panel status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
  elseif ($action == 'listmarkers-layer') {
		$result = $wpdb->prepare( "UPDATE $table_name_layers SET listmarkers = %d", $_POST['listmarkers-layer'] );
		$wpdb->query( $result );
		$wpdb->query( "OPTIMIZE TABLE $table_name_layers" );
		echo '<p><div class="updated" style="padding:10px;">' . __('The list marker-status for all layers has been successfully updated','lmm') . '</div><br/><a class="button-secondary" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_tools">' . __('Back to Tools', 'lmm') . '</a></p>';
  }
} else {
$layerlist = $wpdb->get_results('SELECT * FROM ' . $table_name_layers . ' WHERE id>0', ARRAY_A);
?>
<h3 style="font-size:23px;"><?php _e('Tools','lmm'); ?></h3>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<form method="post">
<input type="hidden" name="action" value="update-settings" />
<?php wp_nonce_field('tool-nonce');
$serialized_options = serialize($lmm_options);
?>
<table class="widefat" style="width:100%;height:100px;">
	<tr style="background-color:#efefef;">
		<td colspan="2"><strong><?php _e('Backup/Restore settings','lmm'); ?> <a href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_pro_upgrade" title="<?php esc_attr_e('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') ?>"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></a></strong></td>
	</tr>
	<tr>
		<td style="vertical-align:top;">
		<p><?php _e('Below you find you current settings. Use copy and paste to make a backup or restore.','lmm'); ?></p>
		<?php
		global $wp_version;
		if ( version_compare( $wp_version, '3.3', '>=' ) ) {
				$settings_tinymce = array(
				'wpautop' => false,
				'media_buttons' => false,
				'tinymce' => array(
				 ),
				'quicktags' => false
				);
				wp_editor( $serialized_options, 'settings-array', $settings_tinymce);
		} else {
			if (function_exists( 'wp_tiny_mce' ) ) {
				add_filter( 'teeny_mce_before_init', create_function( '$a', '
				$a["height"] = "110";
				$a["width"] = "640";
				$a["editor_selector"] = "mceEditor";
				$a["force_br_newlines"] = false;
				$a["force_p_newlines"] = false;
				$a["convert_newlines_to_brs"] = false;
				return $a;'));
				wp_tiny_mce(true);
			}
			echo '<textarea id="settings-array" name="settings-array">' . $serialized_options . '</textarea>';
		}
		echo '<div style="margin:10px 0;"><strong><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade">' . __('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '</a></strong></div>';
		?>
		<script type="text/javascript">
			(function($) {
				$("#settings-array").click(function(){
					this.select();
				});
			})(jQuery);
		</script>
		</td>
	</tr>
</table>
</form>
<br/><br/>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<form method="post">
<input type="hidden" name="action" value="mass_assign" />
<?php wp_nonce_field('tool-nonce'); ?>
<table class="widefat fixed" style="width:auto;">
	<tr style="background-color:#efefef;">
		<td colspan="2"><strong><?php _e('Move markers to a layer','lmm') ?></strong></td>
	</tr>
	<tr>
		<td style="vertical-align:middle;">
		<?php _e('Source','lmm') ?>:
		<select id="layer_assign_from" name="layer_assign_from">
		<?php $markercount_layer0 = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.' as l INNER JOIN '.$table_name_markers.' AS m ON l.id=m.layer WHERE l.id=0'); ?>
		<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
		<?php
		foreach ($layerlist as $row) {
			$markercount = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.' as l INNER JOIN '.$table_name_markers.' AS m ON l.id=m.layer WHERE l.id='.$row['id']);
			echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
		}
		?>
		</select>
		<?php _e('Target','lmm') ?>:
		<select id="layer_assign_to" name="layer_assign_to">
		<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
		<?php
		foreach ($layerlist as $row) {
			$markercount = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.' as l INNER JOIN '.$table_name_markers.' AS m ON l.id=m.layer WHERE l.id='.$row['id']);
			echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
		}
		?>
		</select>
		</td>
		<td>
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_asign-submit" value="<?php _e('move markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to move the selected markers?','lmm') ?>')" />
		</td>
	</tr>
</table>
</form>
<br/><br/>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<form method="post">
<input type="hidden" name="action" value="mass_delete_from_layer" />
<?php wp_nonce_field('tool-nonce'); ?>
<table class="widefat fixed" style="width:auto;">
	<tr style="background-color:#efefef;">
		<td colspan="2"><strong><?php _e('Delete all markers from a layer','lmm') ?></strong></td>
	</tr>
	<tr>
		<td style="vertical-align:middle;">
		<?php _e('Layer','lmm') ?>:
		<select id="delete_from_layer" name="delete_from_layer">
		<option value="0">ID 0 - <?php _e('unassigned','lmm') ?> (<?php echo $markercount_layer0; ?> <?php _e('marker','lmm'); ?>)</option>
		<?php
		foreach ($layerlist as $row) {
			$markercount = $wpdb->get_var('SELECT count(*) FROM '.$table_name_layers.' as l INNER JOIN '.$table_name_markers.' AS m ON l.id=m.layer WHERE l.id='.$row['id']);
			echo '<option value="' . $row['id'] . '">ID ' . $row['id'] . ' - ' . stripslashes(htmlspecialchars($row['name'])) . ' (' . $markercount .' ' . __('marker','lmm') . ')</option>';
		}
		?>
		</select>
		</td>
		<td>
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_delete_from_layer-submit" value="<?php _e('delete all markers from selected layer','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to delete all markers from the selected layer? (cannot be undone)','lmm') ?>')" />
		</td>
	</tr>
</table>
</form>
<br/><br/>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<table class="widefat fixed" style="width:auto;">
	<tr style="background-color:#efefef;">
		<?php
		$settings_all_markers = sprintf( esc_attr__('Change settings for all %1$s existing marker maps','lmm'), $markercount_all);
		?>
		<td colspan="3"><strong><?php echo $settings_all_markers ?></strong></td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="basemap" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Basemap','lmm') ?></strong>
		</td>
		<td>
		<input id="markermaps_osm_mapnik" type="radio" name="basemap" value="osm_mapnik" checked /> <label for="markermaps_osm_mapnik"><?php echo $lmm_options['default_basemap_name_osm_mapnik']; ?></label><br />
		<input id="markermaps_mapquest_osm" type="radio" name="basemap" value="mapquest_osm" /> <label for="markermaps_mapquest_osm"><?php echo $lmm_options['default_basemap_name_mapquest_osm']; ?></label><br />
		<input id="markermaps_mapquest_aerial" type="radio" name="basemap" value="mapquest_aerial" /> <label for="markermaps_mapquest_aerial"><?php echo $lmm_options['default_basemap_name_mapquest_aerial']; ?></label><br />
		<input id="markermaps_googleLayer_roadmap" type="radio" name="basemap" value="googleLayer_roadmap" /> <label for="markermaps_googleLayer_roadmap"><?php echo $lmm_options['default_basemap_name_googleLayer_roadmap']; ?></label><br />
		<input id="markermaps_googleLayer_satellite" type="radio" name="basemap" value="googleLayer_satellite" /> <label for="markermaps_googleLayer_satellite"><?php echo $lmm_options['default_basemap_name_googleLayer_satellite']; ?></label><br />
		<input id="markermaps_googleLayer_hybrid" type="radio" name="basemap" value="googleLayer_hybrid" /> <label for="markermaps_googleLayer_hybrid"><?php echo $lmm_options['default_basemap_name_googleLayer_hybrid']; ?></label><br />
		<input id="markermaps_googleLayer_terrain" type="radio" name="basemap" value="googleLayer_terrain" /> <label for="markermaps_googleLayer_terrain"><?php echo $lmm_options['default_basemap_name_googleLayer_terrain']; ?></label><br />
		<input id="markermaps_bingaerial" type="radio" name="basemap" value="bingaerial" /> <label for="markermaps_bingaerial"><?php echo $lmm_options['default_basemap_name_bingaerial']; ?></label><br />
		<input id="markermaps_bingaerialwithlabels" type="radio" name="basemap" value="bingaerialwithlabels" /> <label for="markermaps_bingaerialwithlabels"><?php echo $lmm_options['default_basemap_name_bingaerialwithlabels']; ?></label><br />
		<input id="markermaps_bingroad" type="radio" name="basemap" value="bingroad" /> <label for="markermaps_bingroad"><?php echo $lmm_options['default_basemap_name_bingroad']; ?></label><br />
		<input id="markermaps_ogdwien_basemap" type="radio" name="basemap" value="ogdwien_basemap" /> <label for="markermaps_ogdwien_basemap"><?php echo $lmm_options['default_basemap_name_ogdwien_basemap']; ?></label><br />
		<input id="markermaps_ogdwien_satellite" type="radio" name="basemap" value="ogdwien_satellite" /> <label for="markermaps_ogdwien_satellite"><?php echo $lmm_options['default_basemap_name_ogdwien_satellite']; ?></label><br />
		<input id="markermaps_cloudmade" type="radio" name="basemap" value="cloudmade" /> <label for="markermaps_cloudmade"><?php echo $lmm_options['cloudmade_name']; ?></label><br />
		<input id="markermaps_cloudmade2" type="radio" name="basemap" value="cloudmade2" /> <label for="markermaps_cloudmade2"><?php echo $lmm_options['cloudmade2_name']; ?></label><br />
		<input id="markermaps_cloudmade3" type="radio" name="basemap" value="cloudmade3" /> <label for="markermaps_cloudmade3"><?php echo $lmm_options['cloudmade3_name']; ?></label><br />
		<input id="markermaps_mapbox" type="radio" name="basemap" value="mapbox" /> <label for="markermaps_mapbox"><?php echo $lmm_options['mapbox_name']; ?></label><br />
		<input id="markermaps_mapbox2" type="radio" name="basemap" value="mapbox2" /> <label for="markermaps_mapbox2"><?php echo $lmm_options['mapbox2_name']; ?></label><br />
		<input id="markermaps_mapbox3" type="radio" name="basemap" value="mapbox3" /> <label for="markermaps_mapbox3"><?php echo $lmm_options['mapbox3_name']; ?></label><br />
		<input id="markermaps_custom_basemap" type="radio" name="basemap" value="custom_basemap" /> <label for="markermaps_custom_basemap"><?php echo $lmm_options['custom_basemap_name']; ?></label><br />
		<input id="markermaps_custom_basemap2" type="radio" name="basemap" value="custom_basemap2" /> <label for="markermaps_custom_basemap2"><?php echo $lmm_options['custom_basemap2_name']; ?></label><br />
		<input id="markermaps_custom_basemap3" type="radio" name="basemap" value="custom_basemap3" /> <label for="markermaps_custom_basemap3"><?php echo $lmm_options['custom_basemap3_name']; ?></label>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="basemap-submit" value="<?php _e('change basemap for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the basemap for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="overlays" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Checked overlays in control box','lmm') ?></strong>
		</td>
		<td>
		<input id="markermaps_overlays_custom" type="checkbox" name="overlays_custom" /> <label for="markermaps_overlays_custom"><?php echo $lmm_options['overlays_custom_name']; ?></label><br />
		<input id="markermaps_overlays_custom2" type="checkbox" name="overlays_custom2" /> <label for="markermaps_overlays_custom2"><?php echo $lmm_options['overlays_custom2_name']; ?></label><br />
		<input id="markermaps_overlays_custom3" type="checkbox" name="overlays_custom3" /> <label for="markermaps_overlays_custom3"><?php echo $lmm_options['overlays_custom3_name']; ?></label><br />
		<input id="markermaps_overlays_custom4" type="checkbox" name="overlays_custom4" /> <label for="markermaps_overlays_custom4"><?php echo $lmm_options['overlays_custom4_name']; ?></label>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="overlays-submit" value="<?php _e('change overlay status for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the overlay status for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="wms" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Active WMS layers','lmm') ?></strong>
		</td>
		<td>
		<input type="checkbox" name="wms" /> <?php echo $lmm_options['wms_wms_name']; ?><br />
		<input type="checkbox" name="wms2" /> <?php echo $lmm_options['wms_wms2_name']; ?><br />
		<input type="checkbox" name="wms3" /> <?php echo $lmm_options['wms_wms3_name']; ?><br />
		<input type="checkbox" name="wms4" /> <?php echo $lmm_options['wms_wms4_name']; ?><br />
		<input type="checkbox" name="wms5" /> <?php echo $lmm_options['wms_wms5_name']; ?><br />
		<input type="checkbox" name="wms6" /> <?php echo $lmm_options['wms_wms6_name']; ?><br />
		<input type="checkbox" name="wms7" /> <?php echo $lmm_options['wms_wms7_name']; ?><br />
		<input type="checkbox" name="wms8" /> <?php echo $lmm_options['wms_wms8_name']; ?><br />
		<input type="checkbox" name="wms9" /> <?php echo $lmm_options['wms_wms9_name']; ?><br />
		<input type="checkbox" name="wms10" /> <?php echo $lmm_options['wms_wms10_name']; ?><br />
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="wms-submit" value="<?php _e('change active WMS layers for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change active WMS layers for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="mapsize" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Map size','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<?php _e('Width','lmm') ?>:
		<input size="2" maxlength="4" type="text" id="mapwidth" name="mapwidth" value="<?php echo intval($lmm_options[ 'defaults_marker_mapwidth' ]) ?>" />
		<input id="markermaps_mapwidthunit_px" type="radio" name="mapwidthunit" value="px" checked />
		<label for="markermaps_mapwidthunit_px">px</label>&nbsp;&nbsp;&nbsp;
		<input id="markermaps_mapwidthunit_percent" type="radio" name="mapwidthunit" value="%" /><label for="markermaps_mapwidthunit_percent">%</label><br/>
		<?php _e('Height','lmm') ?>:
		<input size="2" maxlength="4" type="text" id="mapheight" name="mapheight" value="<?php echo intval($lmm_options[ 'defaults_marker_mapheight' ]) ?>" />px
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mapsize-submit" value="<?php _e('change mapsize for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the map size for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:middle;">
		<form method="post">
		<input type="hidden" name="action" value="zoom" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Zoom','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input style="width: 30px;" type="text" name="zoom" value="<?php echo intval($lmm_options[ 'defaults_marker_zoom' ]) ?>" />
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="zoom-submit" value="<?php _e('change zoom for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the zoom level for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="controlbox" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Basemap/overlay controlbox on frontend','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input id="markermaps_controlbox_hidden" type="radio" name="controlbox" value="0" /><label for="markermaps_controlbox_hidden"><?php _e('hidden','lmm') ?></label><br/>
		<input id="markermaps_controlbox_collapsed" type="radio" name="controlbox" value="1" checked /><label for="markermaps_controlbox_collapsed"><?php _e('collapsed (except on mobiles)','lmm') ?></label><br/>
		<input id="markermaps_controlbox_expanded" type="radio" name="controlbox" value="2" /><label for="markermaps_controlbox_expanded"><?php _e('expanded','lmm') ?></label><br/>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="controlbox-submit" value="<?php _e('change controlbox status for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the controlbox status for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="panel" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Panel for displaying marker name and API URLs on top of map','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input id="markermaps_panel_show" type="radio" name="panel" value="1" checked />
		<label for="markermaps_panel_show"><?php _e('show','lmm') ?></label><br/>
		<input id="markermaps_panel_hide" type="radio" name="panel" value="0" />
		<label for="markermaps_panel_hide"><?php _e('hide','lmm') ?></label></p></td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="panel-submit" value="<?php _e('change panel status for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the panel status for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="icon" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Icon','lmm') ?></strong></td>
		<td style="vertical-align:middle;">
		<div style="text-align:center;float:left;line-height:0px;margin-bottom:3px;"><label for="default_icon"><img src="<?php echo LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png' ?>"/></label><br/>
		<input id="default_icon" type="radio" name="icon" value="" checked />
		</div>
		<?php
		  $iconlist = array();
		  $dir = opendir(LEAFLET_PLUGIN_ICONS_DIR);
		  while ($file = readdir($dir)) {
		    if ($file === false)
		      break;
		    if ($file != "." and $file != "..")
		      if (!is_dir($dir.$file) and substr($file, count($file)-5, 4) == '.png')
		        $iconlist[] = $file;
		  }
		  closedir($dir);
		  sort($iconlist);
		foreach ($iconlist as $row)
		  echo '<div style="text-align:center;float:left;line-height:0px;margin-bottom:3px;"><label for="' . $row . '"><img id="iconpreview" src="' . LEAFLET_PLUGIN_ICONS_URL . '/' . $row . '" title="' . $row . '" alt="' . $row . '" width="32" height="37" /></label><br/><input id="' . $row . '" type="radio" name="icon" value="' . $row . '" /></div>';
		?>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="icon-submit" value="<?php _e('update icon for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the icon for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="openpopup" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Popup status','lmm') ?></strong></td>
		<td style="vertical-align:middle;">
		<input id="markermaps_openpopup_closed" type="radio" name="openpopup" value="0" checked />
		<label for="markermaps_openpopup_closed"><?php _e('closed','lmm') ?></label>&nbsp;&nbsp;&nbsp;
		<input id="markermaps_openpopup_open" type="radio" name="openpopup" value="1" />
		<label for="markermaps_openpopup_open"><?php _e('open','lmm') ?></label></td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="openpopup-submit" value="<?php _e('change popup status for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the popup status for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="popuptext" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Popup text','lmm') ?></strong></td>
		<td style="vertical-align:middle;">
		<?php
			global $wp_version;
			if ( version_compare( $wp_version, '3.3', '>=' ) )
			{
				$settings = array(
						'wpautop' => true,
						'tinymce' => array(
						'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,|,fontselect,fontsizeselect,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,blockquote,|,link,unlink,|,ltr,rtl',
						'theme' => 'advanced',
						'height' => '300',
						'content_css' => LEAFLET_PLUGIN_URL . 'inc/css/leafletmapsmarker-admin-tinymce.php',
						'theme_advanced_statusbar_location' => 'bottom',
						'setup' => 'function(ed) {
								ed.onKeyDown.add(function(ed, e) {
									marker._popup.setContent(ed.getContent());
								});
							}'
						 ),
						'quicktags' => array(
							'buttons' => 'strong,em,link,block,del,ins,img,code,close'));
				wp_editor( '', 'popuptext', $settings);
			}
			else //info: for WP 3.0, 3.1. 3.2
			{
				if (function_exists( 'wp_tiny_mce' ) ) {
					add_filter( 'teeny_mce_before_init', create_function( '$a', '
					$a["theme_advanced_buttons1"] = "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,|,outdent,indent,blockquote,|,bullist,numlist,|,link,unlink,image,|,code";
					$a["theme"] = "advanced";
					$a["skin"] = "wp_theme";
					$a["height"] = "250";
					$a["width"] = "640";
					$a["onpageload"] = "";
					$a["mode"] = "exact";
					$a["elements"] = "popuptext";
					$a["editor_selector"] = "mceEditor";
					$a["plugins"] = "inlinepopups";
					$a["forced_root_block"] = "p";
					$a["force_br_newlines"] = true;
					$a["force_p_newlines"] = false;
					$a["convert_newlines_to_brs"] = true;
					$a["theme_advanced_statusbar_location"] = "bottom";
					return $a;'));
					wp_tiny_mce(true);
				}
			echo '<textarea id="popuptext" name="popuptext"></textarea>';
			}
		?>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="popuptext-submit" value="<?php _e('change popup text for all markers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the popup text for all markers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
</table>
<br/><br/>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<table class="widefat fixed" style="width:auto;">
	<tr style="background-color:#efefef;">
		<?php
		$settings_all_layers = sprintf( esc_attr__('Change settings for all %1$s existing layer maps','lmm'), $layercount_all);
		?>
		<td colspan="3"><strong><?php echo $settings_all_layers ?></strong></td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="basemap-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Basemap','lmm') ?></strong>
		</td>
		<td>
		<input id="layermaps_osm_mapnik" type="radio" name="basemap-layer" value="osm_mapnik" checked /> <label for="layermaps_osm_mapnik"><?php echo $lmm_options['default_basemap_name_osm_mapnik']; ?></label><br />
		<input id="layermaps_mapquest_osm" type="radio" name="basemap-layer" value="mapquest_osm" /> <label for="layermaps_mapquest_osm"><?php echo $lmm_options['default_basemap_name_mapquest_osm']; ?></label><br />
		<input id="layermaps_mapquest_aerial" type="radio" name="basemap-layer" value="mapquest_aerial" /> <label for="layermaps_mapquest_aerial"><?php echo $lmm_options['default_basemap_name_mapquest_aerial']; ?></label><br />
		<input id="layermaps_googleLayer_roadmap" type="radio" name="basemap-layer" value="googleLayer_roadmap" /> <label for="layermaps_googleLayer_roadmap"><?php echo $lmm_options['default_basemap_name_googleLayer_roadmap']; ?></label><br />
		<input id="layermaps_googleLayer_satellite" type="radio" name="basemap-layer" value="googleLayer_satellite" /> <label for="layermaps_googleLayer_satellite"><?php echo $lmm_options['default_basemap_name_googleLayer_satellite']; ?></label><br />
		<input id="layermaps_googleLayer_hybrid" type="radio" name="basemap-layer" value="googleLayer_hybrid" /> <label for="layermaps_googleLayer_hybrid"><?php echo $lmm_options['default_basemap_name_googleLayer_hybrid']; ?></label><br />
		<input id="layermaps_googleLayer_terrain" type="radio" name="basemap-layer" value="googleLayer_terrain" /> <label for="layermaps_googleLayer_terrain"><?php echo $lmm_options['default_basemap_name_googleLayer_terrain']; ?></label><br />
		<input id="layermaps_bingaerial" type="radio" name="basemap-layer" value="bingaerial" /> <label for="layermaps_bingaerial"><?php echo $lmm_options['default_basemap_name_bingaerial']; ?></label><br />
		<input id="layermaps_bingaerialwithlabels" type="radio" name="basemap-layer" value="bingaerialwithlabels" /> <label for="layermaps_bingaerialwithlabels"><?php echo $lmm_options['default_basemap_name_bingaerialwithlabels']; ?></label><br />
		<input id="layermaps_bingroad" type="radio" name="basemap-layer" value="bingroad" /> <label for="layermaps_bingroad"><?php echo $lmm_options['default_basemap_name_bingroad']; ?></label><br />
		<input id="layermaps_ogdwien_basemap" type="radio" name="basemap-layer" value="ogdwien_basemap" /> <label for="layermaps_ogdwien_basemap"><?php echo $lmm_options['default_basemap_name_ogdwien_basemap']; ?></label><br />
		<input id="layermaps_ogdwien_satellite" type="radio" name="basemap-layer" value="ogdwien_satellite" /> <label for="layermaps_ogdwien_satellite"><?php echo $lmm_options['default_basemap_name_ogdwien_satellite']; ?></label><br />
		<input id="layermaps_cloudmade" type="radio" name="basemap-layer" value="cloudmade" /> <label for="layermaps_cloudmade"><?php echo $lmm_options['cloudmade_name']; ?></label><br />
		<input id="layermaps_cloudmade2" type="radio" name="basemap-layer" value="cloudmade2" /> <label for="layermaps_cloudmade2"><?php echo $lmm_options['cloudmade2_name']; ?></label><br />
		<input id="layermaps_cloudmade3" type="radio" name="basemap-layer" value="cloudmade3" /> <label for="layermaps_cloudmade3"><?php echo $lmm_options['cloudmade3_name']; ?></label><br />
		<input id="layermaps_mapbox" type="radio" name="basemap-layer" value="mapbox" /> <label for="layermaps_mapbox"><?php echo $lmm_options['mapbox_name']; ?></label><br />
		<input id="layermaps_mapbox2" type="radio" name="basemap-layer" value="mapbox2" /> <label for="layermaps_mapbox2"><?php echo $lmm_options['mapbox2_name']; ?></label><br />
		<input id="layermaps_mapbox3" type="radio" name="basemap-layer" value="mapbox3" /> <label for="layermaps_mapbox3"><?php echo $lmm_options['mapbox3_name']; ?></label><br />
		<input id="layermaps_custom_basemap" type="radio" name="basemap-layer" value="custom_basemap" /> <label for="layermaps_custom_basemap"><?php echo $lmm_options['custom_basemap_name']; ?></label><br />
		<input id="layermaps_custom_basemap2" type="radio" name="basemap-layer" value="custom_basemap2" /> <label for="layermaps_custom_basemap2"><?php echo $lmm_options['custom_basemap2_name']; ?></label><br />
		<input id="layermaps_custom_basemap3" type="radio" name="basemap-layer" value="custom_basemap3" /> <label for="layermaps_custom_basemap3"><?php echo $lmm_options['custom_basemap3_name']; ?></label>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="basemap-layer-submit" value="<?php _e('change basemap for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the basemap for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="overlays-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Checked overlays in control box','lmm') ?></strong>
		</td>
		<td>
		<input id="layermaps_overlays_custom-layer" type="checkbox" name="overlays_custom-layer" /> <label for="layermaps_overlays_custom-layer"><?php echo $lmm_options['overlays_custom_name']; ?></label><br />
		<input id="layermaps_overlays_custom-layer2" type="checkbox" name="overlays_custom2-layer" /> <label for="layermaps_overlays_custom-layer2"><?php echo $lmm_options['overlays_custom2_name']; ?></label><br />
		<input id="layermaps_overlays_custom-layer3" type="checkbox" name="overlays_custom3-layer" /> <label for="layermaps_overlays_custom-layer3"><?php echo $lmm_options['overlays_custom3_name']; ?></label><br />
		<input id="layermaps_overlays_custom-layer4" type="checkbox" name="overlays_custom4-layer" /> <label for="layermaps_overlays_custom-layer4"><?php echo $lmm_options['overlays_custom4_name']; ?></label>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="overlays-layer-submit" value="<?php _e('change overlay status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the overlay status for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="wms-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Active WMS layers','lmm') ?></strong>
		</td>
		<td>
		<input type="checkbox" name="wms-layer" /> <?php echo $lmm_options['wms_wms_name']; ?><br />
		<input type="checkbox" name="wms2-layer" /> <?php echo $lmm_options['wms_wms2_name']; ?><br />
		<input type="checkbox" name="wms3-layer" /> <?php echo $lmm_options['wms_wms3_name']; ?><br />
		<input type="checkbox" name="wms4-layer" /> <?php echo $lmm_options['wms_wms4_name']; ?><br />
		<input type="checkbox" name="wms5-layer" /> <?php echo $lmm_options['wms_wms5_name']; ?><br />
		<input type="checkbox" name="wms6-layer" /> <?php echo $lmm_options['wms_wms6_name']; ?><br />
		<input type="checkbox" name="wms7-layer" /> <?php echo $lmm_options['wms_wms7_name']; ?><br />
		<input type="checkbox" name="wms8-layer" /> <?php echo $lmm_options['wms_wms8_name']; ?><br />
		<input type="checkbox" name="wms9-layer" /> <?php echo $lmm_options['wms_wms9_name']; ?><br />
		<input type="checkbox" name="wms10-layer" /> <?php echo $lmm_options['wms_wms10_name']; ?><br />
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="wms-layer-submit" value="<?php _e('change active WMS layers for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change active WMS layers for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="mapsize-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Map size','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<?php _e('Width','lmm') ?>:
		<input size="2" maxlength="4" type="text" id="mapwidth-layer" name="mapwidth-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_mapwidth' ]) ?>" />
		<input id="layermaps_mapwidthunit_px" type="radio" name="mapwidthunit-layer" value="px" checked />
		<label for="layermaps_mapwidthunit_px">px</label>&nbsp;&nbsp;&nbsp;
		<input id="layermaps_mapwidthunit_percent" type="radio" name="mapwidthunit-layer" value="%" /><label for="layermaps_mapwidthunit_percent">%</label><br/>
		<?php _e('Height','lmm') ?>:
		<input size="2" maxlength="4" type="text" id="mapheight-layer" name="mapheight-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_mapheight' ]) ?>" />px
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mapsize-layer-submit" value="<?php _e('change mapsize for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the map size for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:middle;">
		<form method="post">
		<input type="hidden" name="action" value="zoom-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Zoom','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input style="width: 30px;" type="text" id="zoom-layer" name="zoom-layer" value="<?php echo intval($lmm_options[ 'defaults_layer_zoom' ]) ?>" />
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="zoom-layer-submit" value="<?php _e('change zoom for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the zoom level for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="controlbox-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Basemap/overlay controlbox on frontend','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input id="layermaps_controlbox_hidden" type="radio" name="controlbox-layer" value="0" /><label for="layermaps_controlbox_hidden"><?php _e('hidden','lmm') ?></label><br/>
		<input id="layermaps_controlbox_collapsed" type="radio" name="controlbox-layer" value="1" checked /><label for="layermaps_controlbox_collapsed"><?php _e('collapsed (except on mobiles)','lmm') ?></label><br/>
		<input id="layermaps_controlbox_expanded" type="radio" name="controlbox-layer" value="2" /><label for="layermaps_controlbox_expanded"><?php _e('expanded','lmm') ?></label><br/>
		</td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="controlbox-layer-submit" value="<?php _e('change controlbox status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the controlbox status for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="panel-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Panel for displaying layer name and API URLs on top of map','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input id="layermaps_panel_show" type="radio" name="panel-layer" value="1" checked />
		<label for="layermaps_panel_show"><?php _e('show','lmm') ?></label><br/>
		<input id="layermaps_panel_hide" type="radio" name="panel-layer" value="0" />
		<label for="layermaps_panel_hide"><?php _e('hide','lmm') ?></label></p></td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="panel-layer-submit" value="<?php _e('change panel status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the panel status for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<input type="hidden" name="action" value="listmarkers-layer" />
		<?php wp_nonce_field('tool-nonce'); ?>
		<strong><?php _e('Display a list of markers under the map','lmm') ?></strong>
		</td>
		<td style="vertical-align:middle;">
		<input id="layermaps_listmarkers_yes" type="radio" name="listmarkers-layer" value="1" checked />
		<label for="layermaps_listmarkers_yes"><?php _e('yes','lmm') ?></label><br/>
		<input id="layermaps_listmarkers_no" type="radio" name="listmarkers-layer" value="0" />
		<label for="layermaps_listmarkers_no"><?php _e('no','lmm') ?></label></p></td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="listmarkers-layer-submit" value="<?php _e('change list marker-status for all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to change the list marker-status for all layers? (cannot be undone)','lmm') ?>')" />
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post">
		<strong><?php _e('Marker clustering','lmm') ?></strong><br/><a href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_pro_upgrade" title="<?php esc_attr_e('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') ?>"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></a>
		</td>
		<td style="vertical-align:middle;">
		<input id="layermaps_clustering_enabled" type="radio" name="listmarkers-clustering" value="1" disabled="disabled" />
		<label for="layermaps_clustering_enabled"><?php _e('enabled','lmm') ?></label><br/>
		<input id="layermaps_clustering_disabled" type="radio" name="listmarkers-clustering" value="0" checked disabled="disabled" />
		<label for="layermaps_listmarkers_disabled"><?php _e('disabled','lmm') ?></label></p></td>
		<td style="vertical-align:middle;">
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="listmarkers-clustering-submit" value="<?php _e('change clustering status for all layers','lmm') ?> &raquo;" disabled="disabled" />
		</form>
		</td>
	</tr>
</table>
<br/><br/>
<?php $nonce= wp_create_nonce('tool-nonce'); ?>
<form method="post">
<input type="hidden" name="action" value="mass_delete_all_markers" />
<?php wp_nonce_field('tool-nonce'); ?>
<table class="widefat fixed" style="width:auto;">
	<tr style="background-color:#efefef;">
		<?php
		$delete_all = sprintf( esc_attr__('Delete all %1$s markers from all %2$s layers','lmm'), $markercount_all, $layercount_all);
		?>
		<td colspan="2"><strong><?php echo $delete_all ?></strong></td>
	</tr>
	<tr>
		<td style="vertical-align:middle;">
		<input id="delete_all_markers_from_all_layers" type="checkbox" id="delete_confirm_checkbox" name="delete_confirm_checkbox" /> <label for="delete_all_markers_from_all_layers"><?php _e('Yes','lmm') ?></label>
		</td>
		<td>
		<input style="font-weight:bold;" class="submit button-primary" type="submit" name="mass_delete_all_markers" value="<?php _e('delete all markers from all layers','lmm') ?> &raquo;" onclick="return confirm('<?php _e('Do you really want to delete all markers from all layers? (cannot be undone)','lmm') ?>')" />
		</td>
	</tr>
</table>
</form>

</div>
<!--wrap-->
<?php }
include('inc' . DIRECTORY_SEPARATOR . 'admin-footer.php');
?>