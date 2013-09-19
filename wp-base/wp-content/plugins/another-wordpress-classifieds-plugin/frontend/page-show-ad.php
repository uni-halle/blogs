<?php

class AWPCP_Show_Ad_Page {

	public function AWPCP_Show_Ad_Page() {
		add_action('init', array($this, 'init'));
		add_filter('awpcp-ad-details', array($this, 'oembed'));
	}

	public function init() {
		$regex = '#http://my\.brainshark\.com/([^\s]+)-(\d+)#i';
		wp_embed_register_handler('brainshark', $regex, array($this, 'oembed_handler_brainshark'));
	}

	/**
	 * Copied from Google Video handler in wp-includes/media.php
	 */
	public function oembed_handler_brainshark($matches, $attr, $url, $rawattr) {
		// If the user supplied a fixed width AND height, use it
		if (!empty($rawattr['width']) && !empty($rawattr['height'])) {
			$width  = (int) $rawattr['width'];
			$height = (int) $rawattr['height'];
		} else {
			list($width, $height) = wp_expand_dimensions(440, 366, $attr['width'], $attr['height']);
		}

		$pi = $matches[2];

		$html = '<object width="' . $width . '" height="' . $height . '" id="bsplayer94201" name="bsplayer94201" data="http://www.brainshark.com/brainshark/viewer/getplayer.ashx" type="application/x-shockwave-flash"><param name="movie" value="http://www.brainshark.com/brainshark/viewer/getplayer.ashx" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="flashvars" value="pi='. $pi . '&dm=5&pause=1" /><a href="http://www.brainshark.com/brainshark/viewer/fallback.ashx?pi='. $pi . '"><video width="' . $width . '" height="' . $height . '" controls="true" poster="http://www.brainshark.com/brainshark/brainshark.net/common/getimage.ashx?pi='. $pi . '&w=' . $width . '&h=' . $height . '&sln=1"><source src="http://www.brainshark.com/brainshark/brainshark.net/apppresentation/getmovie.aspx?pi='. $pi . '&fmt=2" /><img src="http://www.brainshark.com/brainshark/brainshark.net/apppresentation/splash.aspx?pi='. $pi . '" width="' . $width . '" height="' . $height . '" border="0" /></video></a></object>';

		return apply_filters('embed_brainshark', $html, $matches, $attr, $url, $rawattr );
	}

	/**
	 * Acts on awpcp-ad-details filter to add oEmbed support
	 */
	public function oembed($content) {
		global $wp_embed;

		$usecache = $wp_embed->usecache;
		$wp_embed->usecache = false;
		$content = $wp_embed->run_shortcode($content);
		$content = $wp_embed->autoembed($content);
		$wp_embed->usecache = $usecache;

		return $content;
	}
}


/**
 * @since 3.0
 */
function awpcp_get_ad_location($ad_id, $country=false, $county=false, $state=false, $city=false) {
	$places = array();

	if (!empty($city)) {
		$places[] = $city;
	}
	if (!empty($county)) {
		$places[] = $county;
	}
	if (!empty($state)) {
		$places[] = $state;
	}
	if (!empty($country)) {
		$places[] = $country;
	}

	if (!empty($places)) {
		$location = sprintf('%s: %s', __("Location","AWPCP"), join(', ', $places));
	} else {
		$location = '';
	}

	return $location;
}


/**
 * Handles AWPCPSHOWAD shortcode.
 *
 * @param $adid An Ad ID.
 * @param $omitmenu
 * @param $preview true if the function is used to show an ad just after
 *				   it was posted to the website.
 * @param $send_email if true and $preview=true, a success email will be send
 * 					  to the admin and poster user.
 *
 * @return Show Ad page content.
 */
function showad($adid, $omitmenu, $preview=false, $send_email=true) {
	global $wpdb;

	wp_enqueue_script('awpcp-page-show-ad');

    $awpcp = awpcp();

    $awpcp->js->set( 'page-show-ad-flag-ad-nonce', wp_create_nonce('flag_ad') );

    $awpcp->js->localize( 'page-show-ad', array(
        'flag-confirmation-message' => __( 'Are you sure you want to flag this ad?', 'AWPCP' ),
        'flag-success-message' => __( 'This Ad has been flagged.', 'AWPCP' ),
        'flag-error-message' => __( 'An error occurred while trying to flag the Ad.', 'AWPCP' )
    ) );

	$preview = $preview === true || 'preview' == awpcp_array_data('adstatus', '', $_GET);
	$isadmin = awpcp_current_user_is_admin();
	$messages = array();

	$permastruc = get_option('permalink_structure');
	if (!isset($adid) || empty($adid)) {
		if (isset($_REQUEST['adid']) && !empty($_REQUEST['adid'])) {
			$adid = $_REQUEST['adid'];
		} elseif (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
			$adid = $_REQUEST['id'];
		} else if (isset($permastruc) && !empty($permastruc)) {
			$adid = get_query_var( 'id' );
		} else {
			$adid = 0;
		}
	}

	$adid = absint( $adid );

	if (!empty($adid)) {
		// filters to provide alternative method of storing custom
		// layouts (e.g. can be outside of this plugin's directory)
		$prefix = 'awpcp_single_ad_template';
		if (has_action("{$prefix}_action") || has_filter("{$prefix}_filter")) {
			do_action("{$prefix}_action");
			return apply_filters("{$prefix}_filter");

		} else {
			$results = AWPCP_Ad::query( array( 'where' => $wpdb->prepare( 'ad_id = %d', $adid ) ) );
			if (count($results) === 1) {
				$ad = array_shift($results);
			} else {
				$ad = null;
			}

			if (is_null($ad)) {
				$message = __("Sorry, that Ad is no available. Please try browsing or searching existing ads.", "AWPCP");
				return '<div id="classiwrapper">' . awpcp_print_error($message) . '</div><!--close classiwrapper-->';
			}

			if ($ad->user_id > 0 && $ad->user_id == wp_get_current_user()->ID) {
				$is_ad_owner = true;
			} else {
				$is_ad_owner = false;
			}

			if ($omitmenu) {
				$output = '<div id="classiwrapper">%s</div><!--close classiwrapper-->';
			} else {
				$output = '<div id="classiwrapper">%s%%s</div><!--close classiwrapper-->';
				$output = sprintf($output, awpcp_menu_items());
			}

			if (!$isadmin && !$is_ad_owner && !$preview && $ad->disabled == 1) {
				$message = __('The Ad you are trying to view is pending approval. Once the Administrator approves it, it will be active and visible.', 'AWPCP');
				return sprintf($output, awpcp_print_error($message));
			}

			if ($isadmin && $ad->disabled == 1) {
				$message = __('This Ad is currently disabled until the Administrator approves it. Only you (the Administrator) and the author can see it.', 'AWPCP');
				$messages[] = awpcp_print_error($message);
			} else if ($is_ad_owner && $ad->disabled == 1) {
				$message = __('This Ad is currently disabled until the Administrator approves it. Only you (the author) can see it.', 'AWPCP');
				$messages[] = awpcp_print_error($message);
			}

			$layout = get_awpcp_option('awpcpshowtheadlayout');
			if (empty($layout)) {
				$layout = awpcp()->settings->get_option_default_value('awpcpshowtheadlayout');
			}
			$layout = apply_filters('awpcp-single-ad-layout', $layout, $ad);

			$layout = awpcp_do_placeholders( $ad, $layout, 'single' );

			$output = sprintf($output, join('', $messages) . $layout);
			$output = apply_filters('awpcp-show-ad', $output, $adid);

			$ad->visit();
			$ad->save();
		}

	} else {
		$output = awpcp_display_ads( '', '', '', get_awpcp_option( 'groupbrowseadsby' ), '' );
	}

	return $output;
}
