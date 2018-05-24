<?php

class AWPCP_Show_Ad_Page {

	public function __construct() {
		add_filter('awpcp-ad-details', array($this, 'oembed'));
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

	public function dispatch() {
		awpcp_enqueue_main_script();

		$output = apply_filters( 'awpcp-show-listing-content-replacement', null );

		if ( is_null( $output ) ) {
			return showad();
		} else {
			return $output;
		}
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
		$location = sprintf('%s: %s', __("Location",'another-wordpress-classifieds-plugin'), join(', ', $places));
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
function showad( $adid=null, $omitmenu=false, $preview=false, $send_email=true, $show_messages=true ) {
	global $wpdb;

	awpcp_maybe_add_thickbox();
	wp_enqueue_script('awpcp-page-show-ad');

    $awpcp = awpcp();

    $awpcp->js->set( 'page-show-ad-flag-ad-nonce', wp_create_nonce('flag_ad') );

    $awpcp->js->localize( 'page-show-ad', array(
        'flag-confirmation-message' => __( 'Are you sure you want to flag this ad?', 'another-wordpress-classifieds-plugin' ),
        'flag-success-message' => __( 'This Ad has been flagged.', 'another-wordpress-classifieds-plugin' ),
        'flag-error-message' => __( 'An error occurred while trying to flag the Ad.', 'another-wordpress-classifieds-plugin' )
    ) );

	$preview = $preview === true || 'preview' == awpcp_array_data('adstatus', '', $_GET);
	$is_moderator = awpcp_current_user_is_moderator();
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
		if ( has_action( 'awpcp_single_ad_template_action' ) || has_filter( 'awpcp_single_ad_template_filter' ) ) {
			do_action( 'awpcp_single_ad_template_action' );
			return apply_filters( 'awpcp_single_ad_template_filter' );

		} else {
			$results = AWPCP_Ad::query( array( 'where' => $wpdb->prepare( 'ad_id = %d', $adid ) ) );
			if (count($results) === 1) {
				$ad = array_shift($results);
			} else {
				$ad = null;
			}

			if (is_null($ad)) {
				$message = __( 'Sorry, that listing is not available. Please try browsing or searching existing listings.', 'another-wordpress-classifieds-plugin' );
				return '<div id="classiwrapper">' . awpcp_print_error($message) . '</div><!--close classiwrapper-->';
			}

			if ($ad->user_id > 0 && $ad->user_id == wp_get_current_user()->ID) {
				$is_ad_owner = true;
			} else {
				$is_ad_owner = false;
			}

			$content_before_page = apply_filters( 'awpcp-content-before-listing-page', awpcp_render_classifieds_bar() );
			$content_after_page = apply_filters( 'awpcp-content-after-listing-page', '' );

			$output = '<div id="classiwrapper">%s<!--awpcp-single-ad-layout-->%s</div><!--close classiwrapper-->';
			$output = sprintf( $output, $content_before_page, $content_after_page );

			if (!$is_moderator && !$is_ad_owner && !$preview && $ad->disabled == 1) {
				$message = __('The Ad you are trying to view is pending approval. Once the Administrator approves it, it will be active and visible.', 'another-wordpress-classifieds-plugin');
				return str_replace( '<!--awpcp-single-ad-layout-->', awpcp_print_error( $message ), $output );
			}

			if ( awpcp_request_param('verified') && $ad->verified ) {
				$messages[] = awpcp_print_message( __( 'Your email address was successfully verified.', 'another-wordpress-classifieds-plugin' ) );
			}

			if ($show_messages && $is_moderator && $ad->disabled == 1) {
				$message = __('This Ad is currently disabled until the Administrator approves it. Only you (the Administrator) and the author can see it.', 'another-wordpress-classifieds-plugin');
				$messages[] = awpcp_print_error($message);
			} else if ( $show_messages && ( $is_ad_owner || $preview ) && ! $ad->verified ) {
				$message = __('This listing is currently disabled until you verify the email address used for the contact information. Only you (the author) can see the listing. A verification email has been sent to you.', 'another-wordpress-classifieds-plugin');
				$messages[] = awpcp_print_error($message);
			} else if ( $show_messages && ( $is_ad_owner || $preview ) && $ad->disabled == 1 ) {
				$message = __('This Ad is currently disabled until the Administrator approves it. Only you (the author) can see it.', 'another-wordpress-classifieds-plugin');
				$messages[] = awpcp_print_error($message);
			}

            $layout = awpcp_get_listing_single_view_layout( $ad );
			$layout = awpcp_do_placeholders( $ad, $layout, 'single' );

			$output = str_replace( '<!--awpcp-single-ad-layout-->', join('', $messages) . $layout, $output );
			$output = apply_filters('awpcp-show-ad', $output, $adid);

			if ( ! awpcp_request()->is_bot() ) {
				$ad->visit();
			}

			$ad->save();
		}
	} else {
		$query = array(
            'limit' => absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) ),
            'offset' => absint( awpcp_request_param( 'offset', 0 ) ),
			'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
		);

		$output = awpcp_display_listings_in_page( $query, 'show-listing' );
	}

	return $output;
}

function awpcp_get_listing_single_view_layout( $listing ) {
    $layout = get_awpcp_option( 'awpcpshowtheadlayout' );

    if ( empty( $layout ) ) {
        $layout = awpcp()->settings->get_option_default_value( 'awpcpshowtheadlayout' );
    }

    $layout = apply_filters( 'awpcp-single-ad-layout', $layout, $listing );

    if ( get_awpcp_option( 'allow-wordpress-shortcodes-in-single-template' ) ) {
        $layout = do_shortcode( $layout );
    }

    return $layout;
}
