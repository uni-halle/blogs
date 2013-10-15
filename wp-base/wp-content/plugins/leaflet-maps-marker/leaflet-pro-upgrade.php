<?php
/*
    Pro Upgrade - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-pro-upgrade.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
?>
<div class="wrap">
<?php
include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php');
$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
$action = isset($_POST['action']) ? $_POST['action'] : '';
if (extension_loaded('ionCube Loader')) { if ( function_exists('ioncube_loader_iversion') ) { $ic_lv = ioncube_loader_iversion(); $lmm_ic_lv = (int)substr($ic_lv,0,1); } else { $ic_lv = ioncube_loader_version(); $lmm_ic_lv = (int)substr($ic_lv,0,1); } if ($lmm_ic_lv >= 4) { $sf = ''; } else { $sf = strrev('orp-'); } } else { $sf = strrev('orp-'); }
if ( $action == NULL ) {
	if (!file_exists($lmm_pro_readme)) {
		echo '<h3 style="font-size:23px;">' . __('Upgrade to Pro','lmm') . '</h3>';
		echo '<div style="float:left;margin: 0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/logo-mapsmarker-pro.png" alt="Pro Logo" title="Leaflet Maps Marker Pro Logo"></div>';
		echo '<form method="post"><input type="hidden" name="action" value="upgrade_to_pro_version" />';
		wp_nonce_field('pro-upgrade-nonce');
		echo '<p style="padding-top:15px;">' . __('If you like using Leaflet Maps Marker, you might also be interested in starting a free 30-day-trial of Leaflet Maps Marker Pro, which offers even more features, higher performance and more. <br/>Below you find some highlights you will get when going pro (please click on the heading for more details):','lmm') . '</p>';
		echo '<p style="clear:both;">
			<div id="accordion">
				<h3>' . __('integration of the latest leaflet.js version','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				<div style="float:right;margin:0 10px 10px 0;"><a href="http://www.leafletjs.com" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-leaflet.png"></a></div>' . __('Leaflet Maps Marker Pro supports the latest leaflet.js version, which is the core library used for displaying maps.','lmm') . '
				</p>
				<p>
				' . __('Major highlights:','lmm') . '
				<ul style="list-style-type:disc;margin-left:15px;">
					<li>' . __('support for IE10 touch devices','lmm') . '</li>
					<li>' . __('support for Metro apps','lmm') . '</li>
					<li>' . __('a much better panning inertia implementation','lmm') . '</li>
					<li>' . __('improved zoom animation curve for a better feel overal','lmm') . '</li>
					<li>' . __('improved scroll wheel zoom to be more responsive','lmm') . '</li>
					<li>' . __('hand cursors for dragging','lmm') . '</li>
					<li>' . __('optimized zoom control design','lmm') . '</li>
				</ul>
				' . __('But the real power of the leaflet.js version used in Leaflet Maps Marker pro comes with about a hundred of subtle improvements and bugfixes, improving usability, performance and overall "feel" of browsing the map even further.','lmm') . '
				</p>
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-leaflet-changelog" target="_blank">' . sprintf(__('Click here to get the full changelog for leaflet.js v%1s currently integrated in the pro version','lmm'), '0.6.4') . '</a> (' . sprintf(__('v%1s is used in the free version','lmm'), '0.4.5') . ')
				</p>
				</div>

				<h3>' . __('mobile optimized maps through use of native javascript instead of jQuery','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-no-jquery.png"></div>' . __('Maps will be loaded much faster with Leaflet Maps Marker Pro – especially on mobile devices - as no jQuery is needed anymore for displaying maps on frontend. This reduces the download size of each map by about 90kb and also minimizes the browser resources needed for displaying maps.','lmm') . '
				</p>
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-nojquery" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('option to remove MapsMarker.com backlinks','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to hide MapsMarker.com-backlinks from maps, KML files and from the Wikitude app:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-backlink.jpg"><br/><br/>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-backlink-kml.jpg"><br/><br/>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-wikitude-backlink.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-backlink-uploadbutton" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('Marker clustering','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to create beautifully animated marker clusters for layer maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-clustering.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-clustering" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('GPX tracks','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to also display GPX tracks with optional metadata on your maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-gpx.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-gpx" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('HTML5 fullscreen maps','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-html5-fullscreen.png"></div>' . __('Leaflet Maps Marker Pro allows you to add a fullscreen button to maps. Clicking on this button will open an HTML5 fullscreen map without leaving the page you are currently viewing.','lmm') . '
				</p>
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-htlm5-fullscreen-maps" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('Minimaps','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to add a small map in the corner which shows the same as the main map with a set zoom offset:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-minimap.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-minimaps" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('mobile web app support for fullscreen maps and optimized mobile viewport','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro enables you to save the link to the fullscreen map to the homescreen on iOS devices and reopen the map with an optional launch image as web app – meaning the display of the map in fullscreen mode with no address bar:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-webapp.jpg">
				<p>
				' . __('Furthermore the viewport of the device used is considered, which results in optimized display of fullscreen maps especially on mobile devices:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-viewport-mobile.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-webapp" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('custom Google Maps styling','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allow you to easily customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-google-styling-preview.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-google-styling" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('QR codes with custom backgrounds','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				<div style="float:left;margin:0 10px 10px 0;"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-visualead.png"></div>' . __('Leaflet Maps Marker Pro allows you to use custom backgrounds for QR codes.','lmm') . '
				<br/><br/>
				' . __('Additionally the pro version does not display the visualead logo on the QR code output pages.','lmm') . '
				<br/><br/>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-qrcode" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('Google Adsense for maps integration','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro supports Google Adsense for maps. This allows you to add different types of ads to your Google maps:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/help-adsense.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-adsense" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('upload icon button & custom icon directory','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Uploading new icons gets easier with Leaflet Maps Marker Pro - no more need to use a FTP client, just click on the new upload button and add new icons from WordPress admin area easily:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-icon-upload.jpg">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-backlink-uploadbutton" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('backup and restore of settings','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to backup and restore your settings which makes it possible to quickly switch between different plugin profiles. This is especially useful if you want to deploy the plugin with custom configuration on multiple sites:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-backup-restore-settings.png">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-backup-restore" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('advanced recent marker widget','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to customize which markers and layers to include or exclude in the recent marker widget:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-advanced-widget.png">
				<p>
				' . __('Furthermore can also remove the attribution link from the recent marker widget:','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-advanced-widget-noattribution.png">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-advanced-widget" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('MapsMarker API','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Manage your markers and layers through a highly customizable REST API, which supports GET & POST requests, JSON & XML as formats and was developed with a focus on security.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-mapsmarker-api.png">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-mapsmarker-api" target="_blank">' . __('For more details please visit the MapsMarker API docs.','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('whitelabel backend admin pages','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to remove all backlinks and logos on backend as well as making the pages and menu entries for Tools, Settings, Support, License visible to admins only.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-preview-whitelabel-backend.png">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-whitelabel" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('advanced permission settings','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('Leaflet Maps Marker Pro allows you to set the user level needed for editing and deleting marker and layer maps from other users.','lmm') . '
				</p>
				<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-feature-advanced-permissions.png">
				<p>
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/pro-feature-advanced-permissions" target="_blank">' . __('Click here to get more information about this pro feature on mapsmarker.com','lmm') . '</a>
				</p>
				</div>

				<h3>' . __('additional optimizations and improvements','lmm') . '</h3>
				<div>
				<ul style="list-style-type:disc;margin-left:15px;">
					<li><a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/v1.2.1p" target="_blank">' . __('improved performance for layer maps with a huge number of markers (parsing of GeoJSON is up to 3 times faster)','lmm') . '</a></li>
					<li><a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/v1.3p" target="_blank">' . __('support for shortcodes in popup texts','lmm') . '</a></li>
				</ul>
				</div>

				<h3>' . __('features planned for future releases','lmm') . '</h3>
				<div>
				<p style="margin:0 0 1em 0;">
				' . __('We are working hard on delivering the best mapping solution available for WordPress - helping you to share your favorite spots. Therefore we are commited to constantly improving Leaflet Maps Marker Pro. Below you find some highlights from our development roadmap - if an important one is missing for you, let us know and we will check if we can include it in a future release:','lmm') . '
				</p>
				<ul style="list-style-type:disc;margin-left:15px;">
					<li>' . __('filtering markers on frontend','lmm') . '</li>
					<li>' . __('support for displaying KML files','lmm') . '</li>
					<li>' . __('adding markers from frontend','lmm') . '</li>
					<li>' . __('support for Google Street View','lmm') . '</li>
					<li>' . __('import and export function for markers and layers','lmm') . '</li>
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
				<a class="pro-upgrade-external-links" href="http://www.mapsmarker.com/contact" target="_blank">' . __('Visit our contact form to submit your feature request or idea','lmm') . '</a>
				</p>
				</div>

			</div>
				<script type="text/javascript">
					(function($) {
						$(document).ready(function(){
							$( "#accordion" ).accordion({
								active: "false",
								icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" },
								heightStyle: "content",
								collapsible: "true"
							});
						})
					})(jQuery);
				</script>
				</p>';

		echo '<h3 style="font-size:20px;">' . __('Live demo','lmm') . '</h3>';
		echo '<p>' . sprintf(__('Please visit <a href="%1s" target="_blank">%2s</a> for demo maps comparing the free and pro version.','lmm'), 'http://www.mapsmarker.com/comparison','mapsmarker.com/comparison') . '</p>';
		echo '<p>' . sprintf(__('If you want to test the admin area of the pro version, please visit <a href="%1s" target="_blank">%2s</a>.','lmm'), 'http://demo.mapsmarker.com/', 'demo.mapsmarker.com') . '</p>';
		echo '<a href="http://www.mapsmarker.com/comparison" target="_blank"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/pro-free-comparison.jpg" alt="Free/Pro comparison" title="' . esc_attr__('show live demo on mapsmarker.com','lmm') . '"></a></h2>';
		echo '<p>' . __('For more details, showcases and reviews please also visit <a style="text-decoration:none;" href="http://www.mapsmarker.com">www.mapsmarker.com</a>','lmm') . '</p>';
		$dl_l = 'https://www.mapsmarker.com/upgrade' . $sf;
		$dl_lt = 'www.mapsmarker.com/upgrade' . $sf;
		echo '<p>' . sprintf(__('To start your free 30-day-trial of Leaflet Maps Marker Pro, please click on the button "start installation" below. This will start the download of Leaflet Maps Marker Pro from <a style="text-decoration:none;" href="%1s">%2s</a> and installation as a separate plugin.<br/>Afterwards please activate the pro plugin and you will be guided through the process to receive a free 30-day-trial license without any obligations. Your trial will expire automatically unless you purchase a valid pro license. You can also switch back to the free version at any time.','lmm'), $dl_l, $dl_lt) . '</p>';
		if ( current_user_can( 'install_plugins' ) ) {
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('start installation','lmm') . ' &raquo;" class="submit button-primary" />';
		} else {
			echo '<div class="error" style="padding:10px;"><strong>' . sprintf(__('Warning: your user does not have the capability to install new plugins - please contact your administrator (%1s)','lmm'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=' . esc_attr__('Please install the plugin "Leaflet MapsMarker Pro"','lmm') . '">' . get_bloginfo('admin_email') . '</a>' ) . '</strong></div>';
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('start installation','lmm') . ' &raquo;" class="submit button-secondary" disabled="disabled" />';
		}
		echo '</form>';
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
	if (!wp_verify_nonce( $_POST['_wpnonce'], 'pro-upgrade-nonce') ) { wp_die('<br/>'.__('Security check failed - please call this function from the according Leaflet Maps Marker admin page!','lmm').''); };
	if ($action == 'upgrade_to_pro_version') {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		add_filter( 'https_ssl_verify', '__return_false' ); //info: otherwise SSL error on localhost installs.
		add_filter( 'https_local_ssl_verify', '__return_false' ); //info: not sure if needed, added to be sure
		$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin() );
		$dl = 'https://www.mapsmarker.com/upgrade' . $sf;
		$upgrader->install( $dl );
		//info: check if download was successful
		$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		if (file_exists($lmm_pro_readme)) {
			echo '<p>' . __('Please activate the plugin by clicking the link above','lmm') . '</p>';
		} else {
			$dl_l = 'https://www.mapsmarker.com/upgrade' . $sf;
			$dl_lt = 'www.mapsmarker.com/upgrade' . $sf;
			echo '<p>' . sprintf(__('The pro plugin package could not be downloaded automatically. Please download the plugin from <a href="%1s">%2s</a> and upload it to the directory /wp-content/plugins on your server manually','lmm'), $dl_l, $dl_lt) . '</p>';
		}
	}
}
?>
</div>
<!--wrap-->