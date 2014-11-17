<?php
/*
    Pro Upgrade - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-pro-upgrade.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
?>
<div class="wrap">
<?php
include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php');
$first_run = (isset($_GET['first_run']) ? 'true' : 'false');

$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
$action = isset($_POST['action']) ? $_POST['action'] : '';
if ( $action == NULL ) {
	if (!file_exists($lmm_pro_readme)) {
		echo '<div class="pro-upgrade-logo-rtl"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/logo-mapsmarker-pro.png" alt="Pro Logo" title="Leaflet Maps Marker Pro Logo"></div>';
		if ($first_run == 'true') {
			echo '<h3 style="font-size:23px;margin:28px 0 0 0;">' . __('Optional: start a free trial of Maps Marker Pro','lmm') . '</h3>';
		} else {
			echo '<h3 style="font-size:23px;margin:28px 0 0 0;">' . __('Upgrade to Pro','lmm') . '</h3>';
		}
		echo '<form method="post"><input type="hidden" name="action" value="upgrade_to_pro_version" />';
		wp_nonce_field('pro-upgrade-nonce');
		echo '<p>' . __('If you like using Leaflet Maps Marker, you might also be interested in starting a free 30-day-trial of Leaflet Maps Marker Pro, which offers even more features, higher performance and more.','lmm');
		$dl_l = 'https://www.mapsmarker.com/upgrade-pro';
		$dl_lt = 'mapsmarker.com/upgrade-pro';
		echo '</p><p style="clear:both;">' . sprintf(__('Please click on the button below - this will start the download of Leaflet Maps Marker Pro from <a style="text-decoration:none;" href="%1s">%2s</a> and installation as a separate plugin.','lmm'), $dl_l, $dl_lt);
		echo '<br/>' . __('As next step please activate the pro plugin and you will be guided through the process to receive a free 30-day-trial license without any obligations.','lmm');
		echo '<br/>' . sprintf(__('Your trial will expire automatically unless you purchase an unexpiring license key at %1$s','lmm'), '<a href="https://www.mapsmarker.com/order" style="text-decoration:none;" target="_blank">mapsmarker.com/order</a>') . ' (<a href="https://www.mapsmarker.com/terms-of-services" target="_blank" style="text-decoration:none;">' . __('Terms of Service','lmm') . '</a>/<a href="https://www.mapsmarker.com/privacy-policy" target="_blank" style="text-decoration:none;">' . __('Privacy Policy','lmm') . '</a>)';
		echo '<br/>' . __('You can also switch back to the free version at any time without loosing any data.','lmm');
		echo '</p>';
		if ( current_user_can( 'install_plugins' ) ) {
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('start free 30-day-trial','lmm') . ' &raquo;" class="submit button-primary" />';
		} else {
			echo '<div class="error" style="padding:10px;"><strong>' . sprintf(__('Warning: your user does not have the capability to install new plugins - please contact your administrator (%1s)','lmm'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=' . esc_attr__('Please install the plugin "Leaflet MapsMarker Pro"','lmm') . '">' . get_bloginfo('admin_email') . '</a>' ) . '</strong></div>';
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('start free 30-day-trial','lmm') . ' &raquo;" class="submit button-secondary" disabled="disabled" />';
		}
		if ($first_run == 'true') {
			echo ' <a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker" style="text-decoration:none;">' . __('or continue using the lite edition','lmm') . '</a>';
		}
		echo '</form>';

		echo '<hr noshade size="1" style="margin-top:25px;"/><h2 style="margin-top:10px;">' . __('Highlights of Maps Marker Pro','lmm') . '</h2>';
		
		echo '<p>' . sprintf(__('For demo maps please visit %1s which also allows you to test the admin area of the pro version.','lmm'), '<a href="https://demo.mapsmarker.com/" target="_blank" style="text-decoration:none;">demo.mapsmarker.com</a>') . '</p>';
		
		echo '<p>' . sprintf(__('If you want to compare the free and pro version side by side, please visit %1s.','lmm'), '<a href="https://www.mapsmarker.com/comparison" target="_blank" style="text-decoration:none;">mapsmarker.com/comparison</a>') . '</p>';
		
		//info: different backgrounds for WP3.8+
		global $wp_version;
		if ( version_compare( $wp_version, '3.8-alpha', '>' ) ) { //info: for mp6 theme compatibility
			$bgcolor = '#FFFFFF';
		} else {
			$bgcolor = '#F2F2F2';
		}
		echo '<p style="clear:both;">
			<div id="pro-features">
				<span class="pro-feature-header">' . __('integration of the latest leaflet.js version','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0;">
				<div style="float:right;margin:0 10px 10px 0;"><a href="http://www.leafletjs.com" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-leaflet.png"></a></div>' . __('Leaflet Maps Marker Pro supports the latest leaflet.js version, which is the core library used for displaying maps.','lmm') . ' ' . __('Major highlights:','lmm') . '
				<ul style="list-style-type:disc;margin-left:15px;">
					<li>' . __('significantly improved controls design on mobile devices','lmm') . '</li>
					<li>' . __('improved zoom animation curve for a better feel overal','lmm') . '</li>
					<li>' . __('support for IE11 touch devices','lmm') . ' & ' . __('support for Metro apps','lmm') . '</li>
					<li>' . __('a much better panning inertia implementation','lmm') . '</li>
					<li>' . __('improved scroll wheel zoom to be more responsive','lmm') . '</li>
					<li>' . __('hand cursors for dragging','lmm') . '</li>
				</ul>
				' . __('But the real power of the leaflet.js version used in Leaflet Maps Marker pro comes with about a hundred of subtle improvements and bugfixes, improving usability, performance and overall "feel" of browsing the map even further.','lmm') . '
				</p>
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-leaflet-changelog" target="_blank">' . sprintf(__('Click here to get the full changelog for leaflet.js v%1s currently integrated in the pro version','lmm'), '0.7.3 (05/2014)') . '</a> (' . sprintf(__('v%1s is used in the free version','lmm'), '0.4.5 (10/2012)') . ')
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('mobile optimized maps through use of native javascript instead of jQuery','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-no-jquery.png"></div>' . __('Maps will be loaded much faster with Leaflet Maps Marker Pro – especially on mobile devices - as no jQuery is needed anymore for displaying maps on frontend. This reduces the download size of each map by about 90kb and also minimizes the browser resources needed for displaying maps.','lmm') . '
				</p>
				<p style="margin-bottom:25px;">
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-nojquery" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('option to remove MapsMarker.com backlinks','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to hide MapsMarker.com-backlinks from maps, KML files and from the Wikitude app:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-backlink.jpg"><br/><br/>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-backlink-kml.jpg"><br/><br/>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-wikitude-backlink.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-backlink-uploadbutton" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('Marker clustering','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to create beautifully animated marker clusters for layer maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-clustering.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-clustering" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('geolocation support: show and follow your location when viewing maps','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-geolocation	.jpg">
				</p>
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.9p" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('support for CSV/XLS/XLSX/ODS import and export for bulk additions and bulk updates','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to easily perform bulk updates on markers and layers by using the integrated import feature:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-import.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-import" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('GPX tracks','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to also display GPX tracks with optional metadata on your maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-gpx.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-gpx" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('HTML5 fullscreen maps','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-html5-fullscreen.png"></div>' . __('Leaflet Maps Marker Pro allows you to add a fullscreen button to maps. Clicking on this button will open an HTML5 fullscreen map without leaving the page you are currently viewing.','lmm') . '
				</p>
				<p style="margin-bottom:80px;">
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-htlm5-fullscreen-maps" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('Minimaps','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to add a small map in the corner which shows the same as the main map with a set zoom offset:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-minimap.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-minimaps" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('mobile web app support for fullscreen maps and optimized mobile viewport','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro enables you to save the link to the fullscreen map to the homescreen on iOS devices and reopen the map with an optional launch image as web app – meaning the display of the map in fullscreen mode with no address bar:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-webapp.jpg">
				<p>
				' . __('Furthermore the viewport of the device used is considered, which results in optimized display of fullscreen maps especially on mobile devices:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-viewport-mobile.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-webapp" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('custom Google Maps styling','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allow you to easily customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-google-styling-preview.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-google-styling" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('QR codes with custom backgrounds','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-visualead.png"></div>' . __('Leaflet Maps Marker Pro allows you to use custom backgrounds for QR codes.','lmm') . ' (' . __('custom visualead API key required!','lmm') . ')
				<br/><br/>
				' . __('Additionally the pro version does not display the visualead logo on the QR code output pages.','lmm') . '
				<br/><br/>
				' . __('Since pro v1.5 QR code images are also cached for a higher performance.','lmm') . '
				<br/><br/>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-qrcode" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				<p style="margin-bottom:95px;"></p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('Google Adsense for maps integration','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro supports Google Adsense for maps. This allows you to add different types of ads to your Google maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-adsense.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-adsense" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('upload icon button & custom icon directory','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Uploading new icons gets easier with Leaflet Maps Marker Pro - no more need to use a FTP client, just click on the new upload button and add new icons from WordPress admin area easily:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-icon-upload.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-backlink-uploadbutton" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('backup and restore of settings','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to backup and restore your settings which makes it possible to quickly switch between different plugin profiles. This is especially useful if you want to deploy the plugin with custom configuration on multiple sites:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-backup-restore-settings.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-backup-restore" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('advanced recent marker widget','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to customize which markers and layers to include or exclude in the recent marker widget:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-advanced-widget.png">
				<p>
				' . __('Furthermore can also remove the attribution link from the recent marker widget:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-advanced-widget-noattribution.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-advanced-widget" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('MapsMarker API','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Manage your markers and layers through a highly customizable REST API, which supports GET & POST requests, JSON & XML as formats and was developed with a focus on security.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-mapsmarker-api.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-mapsmarker-api" target="_blank">' . __('For more details please visit the MapsMarker API docs.','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('whitelabel backend admin pages','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to remove all backlinks and logos on backend as well as making the pages and menu entries for Tools, Settings, Support, License visible to admins only.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-whitelabel-backend.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-whitelabel" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('advanced permission settings','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('Leaflet Maps Marker Pro allows you to set the user level needed for editing and deleting marker and layer maps from other users.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-advanced-permissions.png">
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/pro-feature-advanced-permissions" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('additional optimizations and improvements','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<ul style="list-style-type:disc;margin-left:15px;margin-top:0;">
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.2.1p" target="_blank">' . __('improved performance for layer maps with a huge number of markers (parsing of GeoJSON is up to 3 times faster)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.6p" target="_blank">' . __('improved performance for layer maps by asynchronous loading of markers via GeoJSON','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.3p" target="_blank">' . __('support for shortcodes in popup texts','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5p" target="_blank">' . __('support for setting global maximum zoom level to 21 (tiles from basemaps with lower native zoom levels will be upscaled automatically)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.1p" target="_blank">' . __('support for duplicating markers','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.7p" target="_blank">' . __('support for dynamic switching between simplified and advanced editor (no more reloads needed)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.7p" target="_blank">' . __('support for filtering of marker icons on backend (based on filename)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.7p" target="_blank">' . __('support for changing marker IDs and layer IDs from the tools page','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.7p" target="_blank">' . __('support for bulk updates of marker maps on the tools page for selected layers only','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.8p" target="_blank">' . __('option to add markernames to popups automatically (default = false)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.5.8p" target="_blank">' . __('map moves back to initial position after popup is closed','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.6p" target="_blank">' . __('option to disable loading of Google Maps API for higher performance if alternative basemaps are used only','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.6p" target="_blank">' . sprintf(__('map parameters can be overwritten within shortcodes (e.g. %1s)','lmm'), '[mapsmarker marker="1" height="100"]') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.8p" target="_blank">' . __('tool for monitoring "active shortcodes for already deleted maps"','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.8p" target="_blank">' . __('layer maps: center map on markers and open popups by clicking on list of marker entries','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.9p" target="_blank">' . __('search function for layerlist on marker edit page','lmm') . '</a></li> 
					<li><a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/v1.9.2p" target="_blank">' . __('improved accessibility/screen reader support by using proper alt texts','lmm') . '</a></li> 
				</ul>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>

				<span class="pro-feature-header">' . __('features planned for future releases','lmm') . '</span>
				<div class="pro-feature-content" style="background:' . $bgcolor . ';">
				<p style="margin:0 0 10px 0;">
				' . __('We are working hard on delivering the best mapping solution available for WordPress - helping you to share your favorite spots. Therefore we are commited to constantly improving Leaflet Maps Marker Pro. Below you find some highlights from our development roadmap - if an important one is missing for you, let us know and we will check if we can include it in a future release:','lmm') . '
				</p>
				<ul style="list-style-type:disc;margin-left:15px;">
					<li>' . __('filtering markers on frontend','lmm') . '</li>
					<li>' . __('support for displaying KML files','lmm') . '</li>
					<li>' . __('adding markers from frontend','lmm') . '</li>
					<li>' . __('support for Google Street View','lmm') . '</li>
					<li>' . __('better integration into the publication workflow (adding markers from posts or as custom post type)','lmm') . '</li>
					<li>' . __('support for Wikitude AR launchlinks','lmm') . '</li>
					<li>' . __('search for markers on frontend','lmm') . '</li>
					<li>' . __('draw features like polylines, polygons, rectangles, circles and markers on maps','lmm') . '</li>
					<li>' . __('email notify on marker/layer actions','lmm') . '</li>
					<li>' . __('assign markers to multiple layers','lmm') . '</li>
					<li>' . __('support for permalinks','lmm') . ' (http://your-domain.com/maps/marker/1/kml)</li>
					<li>' . __('support for geocoding services other than Google Places','lmm') . '</li>
					<li>' . __('better integration with other plugins','lmm') . ' (Google XML Sitemap, Contact Form 7, Event Organizer...)</li>
					<li>...</li>
				</ul>
				<p>
				<a class="pro-upgrade-external-links" href="https://www.mapsmarker.com/contact" target="_blank">' . __('Visit our contact form to submit your feature request or idea','lmm') . '</a>
				</p>
				</div>
				<p><a href="#top" class="upgrade-top-link">' . __('back to top to start free 30-day-trial','lmm') . '</a></p>
			</div>
			</p>
			<p>' . __('For more details, showcases and reviews please also visit <a style="text-decoration:none;" href="http://www.mapsmarker.com">www.mapsmarker.com</a>','lmm') . '</p>';
	} else if (file_exists($lmm_pro_readme)) {
		echo '<h3 style="font-size:23px;">' . __('Upgrade to pro version','lmm') . '</h3>';
		echo '<div class="error" style="padding:10px;"><strong>' . __('You already downloaded "Leaflet Maps Marker Pro" to your server but did not activate the plugin yet!','lmm') . '</strong></div>';
		if ( current_user_can( 'install_plugins' ) ) {
			echo sprintf(__('Please navigate to <a href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Leaflet Maps Marker Pro".','lmm'), LEAFLET_WP_ADMIN_URL . 'plugins.php');
		} else {
			echo sprintf(__('Please contact your administrator (%1s) to activate the plugin "Leaflet Maps Marker Pro".','lmm'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=' . esc_attr__('Please activate the plugin "Maps Marker Pro"','lmm') . '">' . get_bloginfo('admin_email') . '</a>' );
		}
	}
} else {
	if (!wp_verify_nonce( $_POST['_wpnonce'], 'pro-upgrade-nonce') ) { wp_die('<br/>'.__('Security check failed - please call this function from the according admin page!','lmm').''); };
	if ($action == 'upgrade_to_pro_version') {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		add_filter( 'https_ssl_verify', '__return_false' ); //info: otherwise SSL error on localhost installs.
		add_filter( 'https_local_ssl_verify', '__return_false' ); //info: not sure if needed, added to be sure
		$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin() );
		$dl = 'https://www.mapsmarker.com/upgrade-pro';
		$upgrader->install( $dl );
		//info: check if download was successful
		$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		if (file_exists($lmm_pro_readme)) {
			echo '<p>' . __('Please activate the plugin by clicking the link above','lmm') . '</p>';
		} else {
			$dl_l = 'https://www.mapsmarker.com/upgrade-pro';
			$dl_lt = 'www.mapsmarker.com/upgrade-pro';
			echo '<p>' . sprintf(__('The pro plugin package could not be downloaded automatically. Please download the plugin from <a href="%1s">%2s</a> and upload it to the directory /wp-content/plugins on your server manually','lmm'), $dl_l, $dl_lt) . '</p>';
		}
	}
}
?>
</div>
<!--wrap-->