<?php
/**
 * Leaflet Maps Marker Plugin - widget class
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'class-leaflet-recent-marker-widget.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
class Class_leaflet_recent_marker_widget extends WP_Widget {
	public function __construct() {
		$widget_options = array(
			'classname' => 'Class_leaflet_recent_marker_widget',
			'description' => __('Widget to show the most recent markers', 'lmm'));
		$control_options = array();
		parent::__construct( __CLASS__, '<span>' . __('Leaflet Maps Marker - recent markers', 'lmm') . '</span>', $widget_options, $control_options);
	}
	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'lmm-widget-title' => __('Recent markers','lmm'),
			'lmm-widget-howmany' => '5',
			'lmm-widget-showicons' => 'on',
			'lmm-widget-showpopuptext' => 'off',
			'lmm-widget-linktarget' => 'fullscreen',
			'lmm-widget-iconsize' => '90',
			'lmm-widget-createdon' => 'off',
			'lmm-widget-createdonformat' => 'Y-m-d H:i:s',
			'lmm-widget-separatorline' => 'off',
			'lmm-widget-separatorlinecolor' => 'CCCCCC',
			'lmm-widget-orderby' => 'createdon',
			'lmm-widget-orderby-sortorder' => 'desc',
			'lmm-widget-georss' => 'on',
			'lmm-widget-attributionlink' => 'on',
			'lmm-widget-textbeforelist' => '',
			'lmm-widget-textafterlist' => ''
		));
		echo '<p><label for="lmm-widget-title">' . __('Title', 'lmm') . ':</label>';
		echo '<input type="text" value="' . $instance['lmm-widget-title'] . '" name="' . $this->get_field_name('lmm-widget-title') . '" id="' . $this->get_field_id('lmm-widget-title') . '" class="widefat" /></p>';
		echo '<p><label for="lmm-widget-textbeforelist">' . __('Text before list of markers', 'lmm') . ':</label>';
		echo '<input type="text" value="' . $instance['lmm-widget-textbeforelist'] . '" name="' . $this->get_field_name('lmm-widget-textbeforelist') . '" id="' . $this->get_field_id('lmm-widget-textbeforelist') . '" class="widefat" /></p>';
		echo '<p><label for="lmm-widget-markerstoshow">' . __('Number of markers to display', 'lmm') . ':&nbsp;</label>';
		echo '<input style="width:40px;" type="text" value="' . $instance['lmm-widget-howmany'] . '" name="' . $this->get_field_name('lmm-widget-howmany') . '" id="' . $this->get_field_id('lmm-widget-howmany') . '" class="widefat" /></p>';
		echo '<p><div style="float:right"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-banner-small.png" width="68" height="9" border="0"></a></div><label for="lmm-widget-included-layers">' . __('Included layers', 'lmm') . '  <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0" title="' . esc_attr__('If empty, markers from all layers are selected.','lmm') . ' ' . esc_attr__('To select only markers from a layer, please enter the layer ID. Use commas to separate multiple layers. Use 0 for markers not assigned to a layer.','lmm') . '" />:</label>';
		echo '<input type="text" value="' . esc_attr__('Feature available in pro version only','lmm') . '" name="" id="included-layers" class="widefat" disabled="disabled" readonly="readonly" /></p>';
		echo '<p><div style="float:right"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-banner-small.png" width="68" height="9" border="0"></a></div><label for="lmm-widget-exclude-markers">' . __('Exclude markers', 'lmm') . ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0" title="' . esc_attr__('Please enter marker ID. Use commas to separate multiple markers','lmm') . '" />:</label>';
		echo '<input type="text" value="' . esc_attr__('Feature available in pro version only','lmm') . '" name="" id="exclude-markers" class="widefat" disabled="disabled" readonly="readonly" /></p>';
		echo '<p><div style="float:right"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-banner-small.png" width="68" height="9" border="0"></a></div><label for="lmm-widget-exclude-layers">' . __('Exclude layers', 'lmm') . ' <img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0" title="' . esc_attr__('Please enter layer ID. Use commas to separate multiple layers. Use 0 for markers not assigned to a layer.','lmm') . '" />:</label>';
		echo '<input type="text" value="' . esc_attr__('Feature available in pro version only','lmm') . '" name="" id="exclude-layers" class="widefat" disabled="disabled" readonly="readonly" /></p>';
		$showicons = $instance['lmm-widget-showicons'];
		$iconsize = $instance['lmm-widget-iconsize'];
		echo '<p><label for="lmm-widget-showicons">' . __('Show icons', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-showicons') . '" ' . checked($showicons, 'on', false) . ' />';
		echo '&nbsp;&nbsp;&nbsp;' . __('Icon width','lmm') . ': <input style="width:31px;" type="text" value="' . $instance['lmm-widget-iconsize'] . '" name="' . $this->get_field_name('lmm-widget-iconsize') . '" id="' . $this->get_field_id('lmm-widget-iconsize') . '" class="widefat" />%</p>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		$showpopuptext = $instance['lmm-widget-showpopuptext'];
		echo '<p><label for="lmm-widget-showpopuptext">' . __('Show popuptext (no HTML)', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-showpopuptext') . '" ' . checked($showpopuptext, 'on', false) . ' /></p>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		$linktarget = $instance['lmm-widget-linktarget'];
		echo '<p><label for="lmm-widget-linktarget">' . __('Link target', 'lmm') . ':&nbsp;</label>';
		echo '<select name="' . $this->get_field_name('lmm-widget-linktarget') . '">';
		echo '<option value="fullscreen" ' . selected($linktarget, 'fullscreen', false) . '>' . __('fullscreen','lmm') . '</option>';
		echo '<option value="kml" ' . selected($linktarget, 'kml', false) . '>KML</option>';
		echo '<option value="geojson" ' . selected($linktarget, 'geojson', false) . '>GeoJSON</option>';
		echo '<option value="georss" ' . selected($linktarget, 'georss', false) . '>GeoRSS</option>';
		echo '<option value="none" ' . selected($linktarget, 'none', false) . '>' . __('no link','lmm') . '</option>';
		echo '</select>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		$createdon= $instance['lmm-widget-createdon'];
		$createdonformat = $instance['lmm-widget-createdonformat'];
		echo '<p><label for="lmm-widget-separatorline">' . __('Show marker creation time', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-createdon') . '" ' . checked($createdon, 'on', false) . ' /><br/>';
		echo __('Date format','lmm') . ' <a href="http://www.php.net/manual/function.date.php" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-question-mark.png" width="12" height="12" border="0"/></a>: <input style="width:80px;" type="text" value="' . $instance['lmm-widget-createdonformat'] . '" name="' . $this->get_field_name('lmm-widget-createdonformat') . '" id="' . $this->get_field_id('lmm-widget-format') . '" class="widefat" /></p>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		$separatorline= $instance['lmm-widget-separatorline'];
		$separatorlinecolor = $instance['lmm-widget-separatorlinecolor'];
		echo '<p><label for="lmm-widget-separatorline">' . __('Separator lines', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-separatorline') . '" ' . checked($separatorline, 'on', false) . ' />';
		echo '&nbsp;&nbsp;&nbsp;' . __('Color','lmm') . ': #<input style="width:63px;" maxlength="6" type="text" value="' . $instance['lmm-widget-separatorlinecolor'] . '" name="' . $this->get_field_name('lmm-widget-separatorlinecolor') . '" id="' . $this->get_field_id('lmm-widget-separatorlinecolor') . '" class="widefat" /></p>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		$orderby = $instance['lmm-widget-orderby'];
		$orderbysortorder = $instance['lmm-widget-orderby-sortorder'];
		echo '<p><label for="lmm-widget-orderby">' . __('Order by', 'lmm') . ':&nbsp;</label>';
		echo '<select name="' . $this->get_field_name('lmm-widget-orderby') . '">';
		echo '<option value="createdon" ' . selected($orderby, 'createdon', false) . '>' . __('Created on','lmm') . '</option>';
		echo '<option value="updatedon" ' . selected($orderby, 'updatedon', false) . '>' . __('Updated on','lmm') . '</option>';
		echo '</select>';
		echo '<select name="' . $this->get_field_name('lmm-widget-orderby-sortorder') . '">';
		echo '<option value="desc" ' . selected($orderbysortorder, 'desc', false) . '>' . __('desc','lmm') . '</option>';
		echo '<option value="asc" ' . selected($orderbysortorder, 'asc', false) . '>' . __('asc','lmm') . '</option></select></p>';
		echo '<hr style="border:0;height:1px;background-color:#d8d8d8;">';
		echo '<p><label for="lmm-widget-textafterlist">' . __('Text after list of markers', 'lmm') . ':</label>';
		echo '<input type="text" value="' . $instance['lmm-widget-textafterlist'] . '" name="' . $this->get_field_name('lmm-widget-textafterlist') . '" id="' . $this->get_field_id('lmm-widget-textafterlist') . '" class="widefat" /></p>';
		$georss = $instance['lmm-widget-georss'];
		echo '<p><label for="lmm-widget-georss"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" /> ' . __('Show GeoRSS subscribe link', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-georss') . '" ' . checked($georss, 'on', false) . ' /></p>';
		$attributionlink = $instance['lmm-widget-attributionlink'];
		echo '<p><label for="lmm-widget-attributionlink">' . __('Show attribution link', 'lmm') . ':&nbsp;</label>';
		echo '<input type="checkbox" name="' . $this->get_field_name('lmm-widget-attributionlink') . '" ' . checked($attributionlink, 'on', false) . ' disabled="disabled" readonly="readonly" style="display:inline;" />&nbsp;&nbsp;&nbsp;<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-banner-small.png" width="68" height="9" border="0"></a></p>';
	}//info: END function form($instance)
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		if ( ($instance['lmm-widget-attributionlink'] == 'off') ) {
			$new_instance = wp_parse_args((array) $new_instance, array(
				'lmm-widget-title' => __('Recent markers','lmm'),
				'lmm-widget-howmany' => '5',
				'lmm-widget-attributionlink' => 'off'
			));
		} else {
			$new_instance = wp_parse_args((array) $new_instance, array(
				'lmm-widget-title' => __('Recent markers','lmm'),
				'lmm-widget-howmany' => '5',
				'lmm-widget-attributionlink' => 'on'
			));
		}
		$instance['lmm-widget-title'] = (string) strip_tags($new_instance['lmm-widget-title']);
		$instance['lmm-widget-textbeforelist'] = (string) strip_tags($new_instance['lmm-widget-textbeforelist']);
		$instance['lmm-widget-howmany'] = (int) strip_tags($new_instance['lmm-widget-howmany']);
		$instance['lmm-widget-showicons'] = (string) strip_tags($new_instance['lmm-widget-showicons']);
		$instance['lmm-widget-showpopuptext'] = (string) strip_tags($new_instance['lmm-widget-showpopuptext']);
		$instance['lmm-widget-linktarget'] = (string) strip_tags($new_instance['lmm-widget-linktarget']);
		$instance['lmm-widget-iconsize'] = (int) strip_tags($new_instance['lmm-widget-iconsize']);
		$instance['lmm-widget-createdon'] = (string) strip_tags($new_instance['lmm-widget-createdon']);
		$instance['lmm-widget-createdonformat'] = (string) strip_tags($new_instance['lmm-widget-createdonformat']);
		$instance['lmm-widget-separatorline'] = (string) strip_tags($new_instance['lmm-widget-separatorline']);
		$instance['lmm-widget-separatorlinecolor'] = (string) strip_tags($new_instance['lmm-widget-separatorlinecolor']);
		$instance['lmm-widget-orderby'] = (string) strip_tags($new_instance['lmm-widget-orderby']);
		$instance['lmm-widget-orderby-sortorder'] = (string) strip_tags($new_instance['lmm-widget-orderby-sortorder']);
		$instance['lmm-widget-textafterlist'] = (string) strip_tags($new_instance['lmm-widget-textafterlist']);
		$instance['lmm-widget-georss'] = (string) strip_tags($new_instance['lmm-widget-georss']);
		$instance['lmm-widget-attributionlink'] = (string) strip_tags($new_instance['lmm-widget-attributionlink']);
		return $instance;
	}//info: END function update($new_instance, $old_instance)
	public function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		global $wpdb;
		$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
		if ($instance['lmm-widget-howmany']){
			$limiter = (int)$instance['lmm-widget-howmany'];
		} else {
			$limiter = 5;
		}
		$orderby = ($instance['lmm-widget-orderby'] == 'createdon') ? 'createdon' : 'updatedon';
		$orderbysortorder = ($instance['lmm-widget-orderby-sortorder'] == 'desc') ? 'desc' : 'asc';
		$result = $wpdb->get_results("SELECT ID,markername,icon,popuptext,createdon FROM $table_name_markers ORDER BY $orderby $orderbysortorder LIMIT $limiter", ARRAY_A);
		$title = (empty($instance['lmm-widget-title'])) ? '' : $instance['lmm-widget-title'];
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		if (!empty($instance['lmm-widget-textbeforelist'])) {
			echo '<p style="margin-bottom:5px;">' . $instance['lmm-widget-textbeforelist'] . '</p>';
		}
		if ($result != NULL) {
			echo '<table style="border:none;">';
			foreach ($result as $row ) {
				echo '<tr>';
				if (!empty($instance['lmm-widget-showicons'])) {
					$icon = ($row['icon'] == NULL) ? LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png' : LEAFLET_PLUGIN_ICONS_URL . '/'.$row['icon'];
						if ($instance['lmm-widget-linktarget'] != 'none') {
							echo '<td style="vertical-align:top;line-height:1.2em;padding-top:1px;min-width:30px;border:none;"><a href="' . LEAFLET_PLUGIN_URL . 'leaflet-' . $instance['lmm-widget-linktarget'] . '.php?marker='.$row['ID'].'" title="' . esc_attr__('show map','lmm') . ' (' . $instance['lmm-widget-linktarget'] . ')" target="_blank"><img alt="' . esc_attr__('show map','lmm') . '" src="'.$icon.'" style="width:' . $instance['lmm-widget-iconsize'] . '%;box-shadow:none;border-radius:0;display:inline;"></a></td>';
							} else {
							echo '<td style="vertical-align:top;line-height:1.2em;padding-top:1px;border:none;"><img alt="' . esc_attr__('show map','lmm') . '" src="'.$icon.'" style="width:' . $instance['lmm-widget-iconsize'] . '%;border:none;box-shadow:none;border-radius:0;display:inline;"></td>';
						}
				}
				echo '<td style="vertical-align:top;line-height:1.2em;padding-top:1px;width:100%;border:none;">';
				if ($instance['lmm-widget-linktarget'] != 'none') {
					echo '<a href="' . LEAFLET_PLUGIN_URL . 'leaflet-' . $instance['lmm-widget-linktarget'] . '.php?marker='.$row['ID'].'" title="' . esc_attr__('show map','lmm') . ' (' . $instance['lmm-widget-linktarget'] . ')" target="_blank">'.htmlspecialchars(stripslashes($row['markername'])).'</a>';
					} else {
					echo htmlspecialchars(stripslashes($row['markername']));
				}
				if (!empty($instance['lmm-widget-showpopuptext'])) {
					$popuptext = (!empty($row['popuptext'])) ? '<br/>' . stripslashes(strip_tags($row['popuptext'])) : '';
					echo $popuptext;
				}
				if (!empty($instance['lmm-widget-createdon'])) {
					$createdon =  date(htmlspecialchars(stripslashes($instance['lmm-widget-createdonformat'])), strtotime($row['createdon']));
					echo '<br/><span title="' . esc_attr__('created on','lmm') . '">' . $createdon . '</span>';
				}
				echo '</td></tr>';
				if (!empty($instance['lmm-widget-separatorline'])) {
					echo '<tr><td colspan="2"><hr style="border:0;background-color:#' . htmlspecialchars(stripslashes($instance['lmm-widget-separatorline'])) . ';height:1px;padding:0;margin:0.5em 0 1em 0;"></td></tr>';
				}
			}
			echo '</table>';
		} else {
			echo '<p style="margin-bottom:5px;">' . __('No marker created yet','lmm') . '</p>';
		}
		if (!empty($instance['lmm-widget-textafterlist'])) {
			echo '<p style="margin:0;">' . $instance['lmm-widget-textafterlist'] . '</p>';
		}
		if (!empty($instance['lmm-widget-georss'])) {
			echo '<p style="margin:0;"><a target="_blank" href="' . LEAFLET_PLUGIN_URL . 'leaflet-georss.php?layer=all" title="' . esc_attr__('via GeoRSS - please use RSS Reader like http://google.com/reader for example','lmm') . '"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-georss.png" alt="GeoRSS-Logo" /></a> <a target="_blank" href="' . LEAFLET_PLUGIN_URL . 'leaflet-georss.php?layer=all" title="' . esc_attr__('via GeoRSS - please use RSS Reader like http://google.com/reader for example','lmm') . '">' . __('Subscribe to markers','lmm') . '</a></p>';
		}
		if ($instance['lmm-widget-attributionlink'] == 'on') {
			echo '<p style="margin:0;"><span style="font-size:x-small;line-height:1em;">' . __('powered by','lmm') . ' <a href="http://www.mapsmarker.com/go" target="_blank" title="Leaflet Maps Marker WordPress Plugin" style="text-decoration:none;font-size:x-small;">MapsMarker.com</a></span></p>';
		}
		echo $after_widget;
	}//info: END function public function widget($args, $instance)
 }//info: END class Class_leaflet_recent_marker_widget extends WP_Widget
?>