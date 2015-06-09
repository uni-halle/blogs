<?php

require_once(AWPCP_DIR . '/frontend/class-awpcp-meta.php');

require_once(AWPCP_DIR . '/frontend/shortcode-raw.php');

require_once(AWPCP_DIR . '/frontend/page-place-ad.php');
require_once(AWPCP_DIR . '/frontend/page-edit-ad.php');
require_once(AWPCP_DIR . '/frontend/page-renew-ad.php');
require_once(AWPCP_DIR . '/frontend/page-show-ad.php');
require_once(AWPCP_DIR . '/frontend/page-reply-to-ad.php');
require_once(AWPCP_DIR . '/frontend/page-search-ads.php');
require_once(AWPCP_DIR . '/frontend/page-browse-ads.php');
require_once(AWPCP_DIR . '/frontend/page-browse-categories.php');


class AWPCP_Pages {
    private $output = array();

	public function __construct() {
		$this->meta = awpcp_meta();

		$this->show_ad = new AWPCP_Show_Ad_Page();
		$this->browse_ads = new AWPCP_BrowseAdsPage();
		$this->browse_categories = new AWPCP_BrowseCategoriesPage();

		// fix for theme conflict with ThemeForest themes.
		new AWPCP_RawShortcode();

		add_action('init', array($this, 'init'));
	}

	public function init() {
        // page shortcodes
		add_shortcode('AWPCPPLACEAD', array($this, 'place_ad'));
		add_shortcode('AWPCPEDITAD', array($this, 'edit_ad'));
		add_shortcode('AWPCP-RENEW-AD', array($this, 'renew_ad'));
		add_shortcode('AWPCPSEARCHADS', array($this, 'search_ads'));
		add_shortcode('AWPCPREPLYTOAD', array($this, 'reply_to_ad'));

		add_shortcode('AWPCPPAYMENTTHANKYOU', array($this, 'nopp'));
		add_shortcode('AWPCPCANCELPAYMENT', array($this, 'noop'));
		add_shortcode('AWPCPBROWSEADS', array($this->browse_ads, 'dispatch'));
		add_shortcode('AWPCPBROWSECATS', array($this->browse_categories, 'dispatch'));

        add_shortcode('AWPCPSHOWAD', array( $this, 'show_ad' ) );
		add_shortcode('AWPCPCLASSIFIEDSUI', 'awpcpui_homescreen');

        add_shortcode('AWPCPLATESTLISTINGS', array($this, 'listings_shortcode'));
        add_shortcode('AWPCPRANDOMLISTINGS', array($this, 'random_listings_shortcode'));
        add_shortcode('AWPCPSHOWCAT', array($this, 'category_shortcode'));

        add_shortcode( 'AWPCPBUYCREDITS', array( $this, 'buy_credits' ) );

        add_action( 'wp_ajax_awpcp-flag-ad', array( $this, 'ajax_flag_ad' ) );
        add_action( 'wp_ajax_nopriv_awpcp-flag-ad', array( $this, 'ajax_flag_ad' ) );

		do_action('awpcp_setup_shortcode');
	}

    public function noop() {
        return '';
    }

	public function place_ad() {
        do_action('awpcp-shortcode', 'place-ad');

		if ( ! isset( $this->place_ad_page ) ) {
			$this->place_ad_page = new AWPCP_Place_Ad_Page();
        }

		return $this->place_ad_page->dispatch();
	}

	public function edit_ad() {
        if ( ! isset( $this->output['edit-ad'] ) ) {
            do_action('awpcp-shortcode', 'edit-ad');

            if ( ! isset( $this->edit_ad_page ) ) {
                $this->edit_ad_page = new AWPCP_EditAdPage();
            }

            $this->output['edit-ad'] = $this->edit_ad_page->dispatch();
        }

        return $this->output['edit-ad'];
	}

	public function renew_ad() {
		if (!isset($this->renew_ad_page))
			$this->renew_ad_page = new AWPCP_RenewAdPage();
		return is_null($this->renew_ad_page) ? '' : $this->renew_ad_page->dispatch();
	}

    public function show_ad() {
        if ( ! isset( $this->output['show-ad'] ) ) {
            $this->output['show-ad'] = $this->show_ad->dispatch();
        }

        return $this->output['show-ad'];
    }

	public function search_ads() {
		if (!isset($this->search_ads_page))
			$this->search_ads_page = new AWPCP_SearchAdsPage();
		return $this->search_ads_page->dispatch();
	}

	public function reply_to_ad() {
        do_action('awpcp-shortcode', 'reply-to-ad');

		if ( ! isset( $this->reply_to_ad_page ) ) {
			$this->reply_to_ad_page = new AWPCP_ReplyToAdPage();
        }

		return $this->reply_to_ad_page->dispatch();
	}

    /**
     * @since 3.0.2
     */
    public function buy_credits() {
        static $output = null;
        if ( is_null( $output ) ) {
            $output = awpcp_buy_credits_page()->dispatch();
        }
        return $output;
    }

    /* Shortcodes */

    public function listings_shortcode($attrs) {
        wp_enqueue_script('awpcp');

        $attrs = shortcode_atts(array('menu' => true, 'limit' => 10), $attrs);
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $limit = absint($attrs['limit']);

        $query = array(
            'limit' => $limit,
        );

        $options = array(
            'show_menu_items' => $show_menu,
        );

        return awpcp_display_listings( $query, 'latest-listings-shortcode', $options );
    }

    public function random_listings_shortcode($attrs) {
        wp_enqueue_script('awpcp');

        $attrs = shortcode_atts(array('menu' => true, 'limit' => 10), $attrs);
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $limit = absint($attrs['limit']);

        $random_query = array(
            'fields' => 'ad_id',
            'raw' => true,
        );

        $random_listings = awpcp_listings_collection()->find_enabled_listings_with_query( $random_query );
        $random_listings_ids = awpcp_get_properties( $random_listings, 'ad_id' );
        shuffle( $random_listings_ids );

        $query = array(
            'id' => array_slice( $random_listings_ids, 0, $limit ),
            'limit' => $limit,
        );

        $options = array(
            'show_menu_items' => $show_menu,
        );

        return awpcp_display_listings( $query, 'random-listings-shortcode', $options );
    }

    public function category_shortcode( $attrs ) {
        static $output = null;

        if ( is_null( $output ) ) {
            $output = awpcp_category_shortcode()->render( $attrs );
        }

        return $output;
    }

    /* Ajax handlers */

    public function ajax_flag_ad() {
        $response = 0;

        if ( check_ajax_referer( 'flag_ad', 'nonce' ) ) {
            $ad = AWPCP_Ad::find_by_id( intval( awpcp_request_param( 'ad', 0 ) ) );

            if ( ! is_null( $ad ) ) {
                $response = awpcp_listings_api()->flag_listing( $ad );
            }
        }

        echo $response; die();
    }
}



// Set Home Screen

function awpcpui_homescreen() {
	global $classicontent;

	$awpcppagename = sanitize_title( get_currentpagename() );

	if (!isset($classicontent) || empty($classicontent)) {
		$classicontent=awpcpui_process($awpcppagename);
	}
	return $classicontent;
}


function awpcpui_process($awpcppagename) {
	global $hasrssmodule, $hasregionsmodule, $awpcp_plugin_url;

	$output = '';
	$action = '';

	$awpcppage = get_currentpagename();
	if (!isset($awpcppagename) || empty($awpcppagename)) {
		$awpcppagename = sanitize_title($awpcppage, $post_ID='');
	}

	if (isset($_REQUEST['a']) && !empty($_REQUEST['a'])) {
		$action=$_REQUEST['a'];
	}

	// TODO: this kind of requests should be handled in Region Control's own code
	if (($action == 'setregion') || '' != get_query_var('regionid')) {
		if ($hasregionsmodule ==  1) {
			if (isset($_REQUEST['regionid']) && !empty($_REQUEST['regionid'])) {
				$region_id = $_REQUEST['regionid'];
			} else {
				$region_id = get_query_var('regionid');
			}

			// double check module existence :\
			if (method_exists('AWPCP_Region_Control_Module', 'set_location')) {
				$region = awpcp_region_control_get_entry(array('id' => $region_id));
				$regions = AWPCP_Region_Control_Module::instance();
				$regions->set_location($region);
			}
		}

	}

	$categoriesviewpagename = sanitize_title(get_awpcp_option('view-categories-page-name'));
	$browsestat='';

	$browsestat = get_query_var('cid');
	$layout = get_query_var('layout');

	$isadmin=checkifisadmin();

    awpcp_enqueue_main_script();

	$isclassifiedpage = checkifclassifiedpage($awpcppage);
	if (($isclassifiedpage == false) && ($isadmin == 1)) {
		$output .= __("Hi admin, you need to go to your dashboard and setup your classifieds.","AWPCP");

	} elseif (($isclassifiedpage == false) && ($isadmin != 1)) {
		$output .= __("You currently have no classifieds","AWPCP");

	} elseif ($browsestat == $categoriesviewpagename) {
		$output .= awpcp_display_the_classifieds_page_body($awpcppagename);

	} elseif ($layout == 2) {
		$output .= awpcp_display_the_classifieds_page_body($awpcppagename);

	} else {
		$output .= awpcp_load_classifieds($awpcppagename);
	}

	return $output;
}


function awpcp_load_classifieds($awpcppagename) {
	if (get_awpcp_option('main_page_display') == 1) {
        $query = array(
            'limit' => absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) ),
            'offset' => absint( awpcp_request_param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
        );

        $output = awpcp_display_listings_in_page( $query, 'main-page' );
	} else {
		$output = awpcp_display_the_classifieds_page_body( $awpcppagename );
	}

	return $output;
}


//	START FUNCTION: show the classifieds page body
function awpcp_display_the_classifieds_page_body($awpcppagename) {
	global $hasregionsmodule;

	$output = '';

	if (!isset($awpcppagename) || empty($awpcppagename)) {
		$awpcppage=get_currentpagename();
		$awpcppagename = sanitize_title($awpcppage, $post_ID='');
	}

	$output .= "<div id=\"classiwrapper\">";
	$uiwelcome=strip_slashes_recursive(get_awpcp_option('uiwelcome'));
	$output .= "<div class=\"uiwelcome\">$uiwelcome</div>";

	// Place the menu items
	$output .= awpcp_menu_items();

	if ($hasregionsmodule ==  1) {
		$output .= awpcp_region_control_selector();
	}

	$output .= "<div class=\"classifiedcats\">";

	//Display the categories
    $params = array(
        'show_in_columns' => get_awpcp_option( 'view-categories-columns' ),
        'show_empty_categories' => ! get_awpcp_option( 'hide-empty-categories' ),
        'show_children_categories' => true,
        'show_listings_count' => get_awpcp_option( 'showadcount' ),
        'show_sidebar' => true,
    );
    $output .= awpcp_categories_list_renderer()->render( $params );

	$output .= "</div>";

	$output .= "</div>";

	return $output;
}
//	End function display the home screen


function awpcp_menu_items() {
    $menu_items = array_filter( awpcp_get_menu_items(), 'is_array' );

    ob_start();
        include ( AWPCP_DIR . '/frontend/templates/main-menu.tpl.php' );
        $output = ob_get_contents();
    ob_end_clean();

    return $output;
}

function awpcp_get_menu_items() {
    $items = array();

    $user_is_allowed_to_place_ads = ! get_awpcp_option( 'onlyadmincanplaceads' ) || awpcp_current_user_is_admin();
    $show_place_ad_item = $user_is_allowed_to_place_ads && get_awpcp_option( 'show-menu-item-place-ad' );
    $show_edit_ad_item = $user_is_allowed_to_place_ads && get_awpcp_option( 'show-menu-item-edit-ad' );
    $show_browse_ads_item = get_awpcp_option( 'show-menu-item-browse-ads' );
    $show_search_ads_item = get_awpcp_option( 'show-menu-item-search-ads' );

    if ( $show_place_ad_item ) {
        $place_ad_url = awpcp_get_page_url( 'place-ad-page-name' );
        $place_ad_page_name = get_awpcp_option( 'place-ad-page-name' );
        $items['post-listing'] = array( 'url' => $place_ad_url, 'title' => esc_html( $place_ad_page_name ) );
    }

    if ( $show_edit_ad_item ) {
        $items['edit-listing'] = awpcp_get_edit_listing_menu_item();
    }

    if ( $show_browse_ads_item ) {
        if ( is_awpcp_browse_listings_page() || is_awpcp_browse_categories_page() ) {
            if ( get_awpcp_option( 'main_page_display' ) ) {
                $browse_cats_url = awpcp_get_view_categories_url();
            } else {
                $browse_cats_url = awpcp_get_main_page_url();
            }

            $view_categories_page_name = get_awpcp_option( 'view-categories-page-name' );
            $items['browse-listings'] = array( 'url' => $browse_cats_url, 'title' => esc_html( $view_categories_page_name ) );
        } else {
            $browse_ads_page_name = get_awpcp_option('browse-ads-page-name');
            $browse_ads_url = awpcp_get_page_url( 'browse-ads-page-name' );
            $items['browse-listings'] = array( 'url' => $browse_ads_url, 'title' => esc_html( $browse_ads_page_name  ) );
        }
    }

    if ( $show_search_ads_item ) {
        $search_ads_page_name = get_awpcp_option( 'search-ads-page-name' );
        $search_ads_url = awpcp_get_page_url( 'search-ads-page-name' );
        $items['search-listings'] = array( 'url' => $search_ads_url, 'title' => esc_html( $search_ads_page_name ) );
    }

    $items = apply_filters( 'awpcp_menu_items', $items );

    return $items;
}

function awpcp_get_edit_listing_menu_item() {
    $listings = awpcp_listings_collection();
    $authorization = awpcp_listing_authorization();
    $request = awpcp_request();
    $settings = awpcp()->settings;

    try {
        $listing = $listings->get( $request->get_ad_id() );
    } catch( AWPCP_Exception $e ) {
        $listing = null;
    }

    if ( is_object( $listing ) && $authorization->is_current_user_allowed_to_edit_listing( $listing ) ) {
        $edit_ad_url = awpcp_get_edit_listing_direct_url( $listing );
    } else if ( ! $settings->get_option( 'requireuserregistration' ) ) {
        $edit_ad_url = awpcp_get_edit_listing_generic_url();
    } else {
        $edit_ad_url = null;
    }

    if ( is_null( $edit_ad_url ) ) {
        return null;
    } else {
        $edit_ad_page_name = $settings->get_option( 'edit-ad-page-name' );
        return array( 'url' => $edit_ad_url, 'title' => esc_html( $edit_ad_page_name ) );
    }
}
