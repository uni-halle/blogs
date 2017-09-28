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


class AWPCP_Pages {
    private $output = array();

	public function __construct() {
		$this->meta = awpcp_meta();

		$this->show_ad = new AWPCP_Show_Ad_Page();
		$this->browse_ads = new AWPCP_BrowseAdsPage();

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

		add_shortcode('AWPCPPAYMENTTHANKYOU', array($this, 'noop'));
		add_shortcode('AWPCPCANCELPAYMENT', array($this, 'noop'));

        add_shortcode( 'AWPCPBROWSECATS', array( $this->browse_ads, 'dispatch' ) );
		add_shortcode('AWPCPBROWSEADS', array($this->browse_ads, 'dispatch'));

        add_shortcode('AWPCPSHOWAD', array( $this, 'show_ad' ) );
		add_shortcode('AWPCPCLASSIFIEDSUI', 'awpcpui_homescreen');

        add_shortcode('AWPCPLATESTLISTINGS', array($this, 'listings_shortcode'));
        add_shortcode('AWPCPRANDOMLISTINGS', array($this, 'random_listings_shortcode'));
        add_shortcode('AWPCPSHOWCAT', array($this, 'category_shortcode'));
        add_shortcode( 'AWPCPUSERLISTINGS', array( $this, 'user_listings_shortcode' ) );

        add_shortcode( 'AWPCPBUYCREDITS', array( $this, 'buy_credits' ) );

        add_action( 'wp_ajax_awpcp-flag-ad', array( $this, 'ajax_flag_ad' ) );
        add_action( 'wp_ajax_nopriv_awpcp-flag-ad', array( $this, 'ajax_flag_ad' ) );

		do_action('awpcp_setup_shortcode');
	}

    public function noop() {
        return '';
    }

	public function place_ad() {
        if ( ! isset( $this->output['place-ad'] ) ) {
            do_action('awpcp-shortcode', 'place-ad');

            if ( ! isset( $this->place_ad_page ) ) {
                $this->place_ad_page = new AWPCP_Place_Ad_Page();
            }

            $this->output['place-ad'] = $this->place_ad_page->dispatch();
        }

		return $this->output['place-ad'];
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
        if ( ! isset( $this->output['search-ads'] ) ) {
            $page = new AWPCP_SearchAdsPage();
            $this->output['search-ads'] = $page->dispatch();
        }

        return $this->output['search-ads'];
	}

	public function reply_to_ad() {
        if ( ! isset( $this->output['reply-to-ad'] ) ) {
            do_action('awpcp-shortcode', 'reply-to-ad');
            $page = new AWPCP_ReplyToAdPage();
            $this->output['reply-to-ad'] = $page->dispatch();
        }

        return $this->output['reply-to-ad'];
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

    public function user_listings_shortcode( $attrs ) {
        wp_enqueue_script( 'awpcp' );

        $attrs = shortcode_atts( array(
            'user_id' => get_current_user_id(),
            'menu' => true,
            'limit' => null
        ), $attrs );

        $user_id = absint( $attrs['user_id'] );

        if ( $user_id === 0 ) {
            return '';
        }

        $query = array(
            'context' => 'public-listings',
            'limit' => absint( $attrs['limit'] ),
            'user_id' => $user_id,
        );

        $options = array(
            'show_menu_items' => awpcp_parse_bool( $attrs['menu'] )
        );

        return awpcp_display_listings( $query, 'user-listings-shortcode', $options );
    }

    public function listings_shortcode($attrs) {
        wp_enqueue_script('awpcp');

        $default_attrs = array(
            'menu' => true,
            'pagination' => false,
            'limit' => 10,
        );

        $attrs = shortcode_atts( $default_attrs, $attrs );
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $show_pagination = awpcp_parse_bool( $attrs['pagination'] );
        $limit = absint($attrs['limit']);

        $query = array(
            'context' => 'public-listings',
            'limit' => $limit,
        );

        $options = array(
            'show_menu_items' => $show_menu,
            'show_pagination' => $show_pagination,
        );

        return awpcp_display_listings( $query, 'latest-listings-shortcode', $options );
    }

    public function random_listings_shortcode($attrs) {
        wp_enqueue_script('awpcp');

        $attrs = shortcode_atts( array( 'category' => null, 'menu' => true, 'limit' => 10 ), $attrs );

        $categories = array_filter( array_map( 'absint', explode( ',', $attrs['category'] ) ) );
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $limit = absint($attrs['limit']);

        $query = array(
            'context' => 'public-listings',
            'category_id' => $categories,
            'orderby' => 'random',
            'limit' => $limit,
        );

        $options = array(
            'show_menu_items' => $show_menu,
        );

        return awpcp_display_listings( $query, 'random-listings-shortcode', $options );
    }

    public function category_shortcode( $attrs ) {
        $cache_key = crc32( maybe_serialize( $attrs ) );

        if ( ! isset( $this->output[ $cache_key ] ) ) {
            $this->output[ $cache_key ] = awpcp_category_shortcode()->render( $attrs );
        }

        return $this->output[ $cache_key ];
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

	$awpcppage = get_currentpagename();
	if (!isset($awpcppagename) || empty($awpcppagename)) {
		$awpcppagename = sanitize_title($awpcppage, $post_ID='');
	}

	$layout = get_query_var('layout');

	$isadmin=checkifisadmin();

    awpcp_enqueue_main_script();

	$isclassifiedpage = checkifclassifiedpage($awpcppage);
	if (($isclassifiedpage == false) && ($isadmin == 1)) {
		$output .= __("Hi admin, you need to go to your dashboard and setup your classifieds.", 'another-wordpress-classifieds-plugin');

	} elseif (($isclassifiedpage == false) && ($isadmin != 1)) {
		$output .= __("You currently have no classifieds", 'another-wordpress-classifieds-plugin');

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
            'context' => 'public-listings',
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
	$uiwelcome=stripslashes_deep(get_awpcp_option('uiwelcome'));
	$output .= "<div class=\"uiwelcome\">$uiwelcome</div>";

	// Place the menu items
    $output .= awpcp_render_classifieds_bar();

	if ( function_exists( 'awpcp_region_control_selector' ) && get_awpcp_option( 'show-region-selector', true ) ) {
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
    $params = array(
        'show-create-listing-button' => get_awpcp_option( 'show-menu-item-place-ad' ),
        'show-edit-listing-button' => get_awpcp_option( 'show-menu-item-edit-ad' ),
        'show-browse-listings-button' => get_awpcp_option( 'show-menu-item-browse-ads' ),
        'show-search-listings-button' => get_awpcp_option( 'show-menu-item-search-ads' ),
    );

    $menu_items = array_filter( awpcp_get_menu_items( $params ), 'is_array' );

    $navigation_attributes = array(
        'class' => array( 'awpcp-navigation', 'awpcp-menu-items-container', 'clearfix' ),
    );

    if ( get_awpcp_option( 'show-mobile-menu-expanded' ) ) {
        $navigation_attributes['class'][] = 'toggle-on';
    }

    $template = AWPCP_DIR . '/frontend/templates/main-menu.tpl.php';
    $params = compact( 'menu_items', 'navigation_attributes' );

    return awpcp_render_template( $template, $params );
}

function awpcp_get_menu_items( $params ) {
    $items = array();

    $user_is_allowed_to_place_ads = ! get_awpcp_option( 'onlyadmincanplaceads' ) || awpcp_current_user_is_admin();
    $show_place_ad_item = $user_is_allowed_to_place_ads && $params['show-create-listing-button'];
    $show_browse_ads_item = $params['show-browse-listings-button'];
    $show_search_ads_item = $params['show-search-listings-button'];

    if ( $show_place_ad_item ) {
        $place_ad_url = awpcp_get_page_url( 'place-ad-page-name' );
        $place_ad_page_name = get_awpcp_option( 'place-ad-page-name' );
        $items['post-listing'] = array( 'url' => $place_ad_url, 'title' => esc_html( $place_ad_page_name ) );
    }

    if ( awpcp_should_show_edit_listing_menu( $params ) ) {
        $edit_listing_menu_item = awpcp_get_edit_listing_menu_item();
    } else {
        $edit_listing_menu_item = null;
    }

    if ( $edit_listing_menu_item ) {
        $items['edit-listing'] = $edit_listing_menu_item;
    }

    if ( $show_browse_ads_item ) {
        if ( is_awpcp_browse_listings_page() || is_awpcp_browse_categories_page() ) {
            if ( get_awpcp_option( 'main_page_display' ) ) {
                $browse_cats_url = awpcp_get_view_categories_url();
            } else {
                $browse_cats_url = awpcp_get_main_page_url();
            }

            $view_categories_page_name = get_awpcp_option( 'view-categories-page-name' );

            if ( $view_categories_page_name ) {
                $items['browse-listings'] = array(
                    'url' => $browse_cats_url,
                    'title' => esc_html( $view_categories_page_name ),
                );
            }
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

function awpcp_should_show_edit_listing_menu( $params ) {
    if ( get_awpcp_option( 'onlyadmincanplaceads' ) && ! awpcp_current_user_is_admin() ) {
        return false;
    }

    if ( ! $params['show-edit-listing-button'] ) {
        return false;
    }

    if ( awpcp_query()->is_edit_listing_page() && awpcp_request()->get_current_listing_id() ) {
        return false;
    }

    return true;
}

function awpcp_get_edit_listing_menu_item() {
    $listings = awpcp_listings_collection();
    $authorization = awpcp_listing_authorization();
    $request = awpcp_request();
    $settings = awpcp()->settings;

    try {
        $listing = $listings->get( $request->get_current_listing_id() );
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
