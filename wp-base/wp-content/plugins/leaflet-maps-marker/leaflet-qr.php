<?php
/*
    QR code generator - Leaflet Maps Marker Plugin
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
	$lmm_options = get_option( 'leafletmapsmarker_options' );
	if (isset($_GET['layer'])) {
			$url = LEAFLET_PLUGIN_URL . 'leaflet-fullscreen.php?layer=' . htmlspecialchars($_GET['layer']);
	} else if (isset($_GET['marker'])) {
			$url = LEAFLET_PLUGIN_URL . 'leaflet-fullscreen.php?marker=' . htmlspecialchars($_GET['marker']);
	}
	//info: visualead settings
	if ($lmm_options['qrcode_provider'] == 'visualead') {
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://api.visualead.com/v1/generate");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
		curl_setopt($ch, CURLOPT_POST,true);
		$filedata = urlencode(LEAFLET_PLUGIN_URL . 'inc/img/logo-qr-code.png');
		$api_key = strrev('b7a8f1a0e750-c488-44b4-c34b-2592e115');
		$data=array(
			'api_key'=>$api_key,
			'image'=>$filedata,
			'qr_x'=>4,
			'qr_y'=>5,
			'qr_size'=>124,
			'output_type' => 1,
			'action'=>'url',
			'content'=>array('url'=>$url)
		);
		$data = json_encode($data);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		$output = curl_exec($ch);
		curl_close($ch);
		$results = json_decode($output);
		if($results->response ==1){
			$image_decoded= base64_decode($results->image);
			echo '<a href="data:image/png;base64,' . $results->image . '" title="' . sprintf(esc_attr__('QR code image for link to full screen map (%s)','lmm'),$url) . '"><img src="data:image/png;base64,' . $results->image . '" alt="QR-Code"/></a>';
			echo '<br/><a href="http://www.visualead.com" target="_blank" title="' . esc_attr__('QR code powered by visualead.com','lmm') . '"><img style="margin:10px 0 0 35px;" src="' . LEAFLET_PLUGIN_URL . 'inc/img/logo-visualead.png"></a>';
		}
	//info: Google QR settings
	} else if ($lmm_options['qrcode_provider'] == 'google') {
		$google_qr_link = 'https://chart.googleapis.com/chart?chs=' . $lmm_options[ 'misc_qrcode_size' ] . 'x' . $lmm_options[ 'misc_qrcode_size' ] . '&cht=qr&chl=' . $url;
		echo '<script type="text/javascript">window.location.href = "' . $google_qr_link . '";</script>  ';
	}
} //info: end plugin active check
?>