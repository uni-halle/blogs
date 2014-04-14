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
            $this->output['show-ad'] = showad();
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
        global $wpdb;

        wp_enqueue_script('awpcp');

        $attrs = shortcode_atts(array('menu' => true, 'limit' => 10), $attrs);
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $limit = absint($attrs['limit']);

        $ads = AWPCP_Ad::get_enabled_ads(array('limit' => $limit));

        $config = array('show_menu' => $show_menu, 'show_intro' => false);

        return awpcp_render_ads($ads, 'latest-listings-shortcode', $config, false);
    }

    public function random_listings_shortcode($attrs) {
        global $wpdb;

        wp_enqueue_script('awpcp');

        $attrs = shortcode_atts(array('menu' => true, 'limit' => 10), $attrs);
        $show_menu = awpcp_parse_bool($attrs['menu']);
        $limit = absint($attrs['limit']);

        $ads = AWPCP_Ad::get_random_ads($limit);

        $config = array('show_menu' => $show_menu, 'show_intro' => false);

        return awpcp_render_ads($ads, 'ramdom-listings-shortcode', $config, false);
    }

    public function category_shortcode($attrs) {
        global $wpdb;

        $default = array( 'id' => 0, 'children' => true, 'items_per_page' => 10 );
        extract( shortcode_atts( $default, $attrs ) );

        // request param overrides shortcode param
        $items_per_page = awpcp_request_param( 'results', $items_per_page );
        // set the number of items per page, to make sure both the shortcode handler
        // and the awpcp_display_ads function are using the same value
        $_REQUEST['results'] = $_GET['results'] = $items_per_page;

        $category = $id > 0 ? AWPCP_Category::find_by_id($id) : null;
        $children = awpcp_parse_bool($children);

        if ( is_null( $category ) ) {
            return __('Category ID must be valid for Ads to display.', 'category shortcode', 'AWPCP');
        }

        if ($children) {
            // show children categories and disable possible sidebar (Region Control sidelist)
            $before = awpcp_display_the_classifieds_category( '', $category->id, false, 1 );
            // $before = awpcp_render_categories( $category->id, array( 'sidelist' => false, 'columns' => 1 ) );
        } else {
            $before = '';
        }

        if ( $children ) {
            $where = '( ad_category_id=%1$d OR ad_category_parent_id = %1$d ) AND disabled = 0';
        } else {
            $where = 'ad_category_id=%1$d AND disabled = 0';
        }
        $where = $wpdb->prepare($where, $category->id);

        $order = get_awpcp_option( 'groupbrowseadsby' );

        // required so awpcp_display_ads shows the name of the current category
        $_REQUEST['category_id'] = $category->id;

        $base_url = sprintf( 'custom:%s', awpcp_current_url() );

        return awpcp_display_ads( $where, '', '', $order, $base_url, $before );
    }

    /* Ajax handlers */

    public function ajax_flag_ad() {
        $response = 0;

        if ( check_ajax_referer( 'flag_ad', 'nonce' ) ) {
            $ad = AWPCP_Ad::find_by_id( intval( awpcp_request_param( 'ad', 0 ) ) );

            if ( ! is_null( $ad ) ) {
                $response = $ad->flag();
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

	} elseif ($action == 'unsetregion') {
		if (isset($_SESSION['theactiveregionid'])) {
			unset($_SESSION['theactiveregionid']);
		}
	}


	$categoriesviewpagename = sanitize_title(get_awpcp_option('view-categories-page-name'));
	$browsestat='';

	$browsestat = get_query_var('cid');
	$layout = get_query_var('layout');

	$isadmin=checkifisadmin();

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
		// display latest ads on mainpage
		$order = get_awpcp_option( 'groupbrowseadsby' );
		$output = awpcp_display_ads( '', 1, '', $order, $adorcat='ad' );
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
		if (isset($_SESSION['theactiveregionid'])) {
			$theactiveregionid = $_SESSION['theactiveregionid'];
			$theactiveregionname = get_theawpcpregionname($theactiveregionid);
		}
		$output .= awpcp_region_control_selector();
	}

	$output .= "<div class=\"classifiedcats\">";

	//Display the categories
	// $output .= awpcp_display_the_classifieds_category($awpcppagename);
    $output .= awpcp_render_categories( 0, array(
        'columns' => get_awpcp_option( 'view-categories-columns' ),
        'hide_empty' => get_awpcp_option( 'hide-empty-categories' ),
        'show_children' => true,
        'show_ad_count' => get_awpcp_option( 'showadcount' ),
        'show_sidebar' => true,
    ) );

	$output .= "</div>";
	$removeLink = get_awpcp_option('removepoweredbysign');

	if ( field_exists($field='removepoweredbysign') && !($removeLink) ) {
		$output .= "<p><font style=\"font-size:smaller\">";
		$output .= __("Powered by ","AWPCP");
		$output .= "<a href=\"http://www.awpcp.com\">Another Wordpress Classifieds Plugin</a> </font></p>";
	} elseif ( field_exists($field='removepoweredbysign') && ($removeLink) ) {

	} else {
//		$output .= "<p><font style=\"font-size:smaller\">";
//		$output .= __("Powered by ","AWPCP");
//		$output .= "<a href=\"http://www.awpcp.com\">Another Wordpress Classifieds Plugin</a> </font></p>";
	}

	$output .= "</div>";

	return $output;
}
//	End function display the home screen


function awpcp_menu_items() {
    $menu_items = awpcp_get_menu_items();

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
        $items['post-listing'] = array( 'url' => $place_ad_url, 'title' => $place_ad_page_name );
    }

    if ( $show_edit_ad_item ) {
        if ( get_awpcp_option('enable-user-panel') ) {
            $edit_ad_url = awpcp_get_user_panel_url();
        } else {
            $edit_ad_url = awpcp_get_page_url( 'edit-ad-page-name' );
        }

        $edit_ad_page_name = get_awpcp_option( 'edit-ad-page-name' );
        $items['edit-listing'] = array( 'url' => $edit_ad_url, 'title' => $edit_ad_page_name );
    }

    if ( $show_browse_ads_item ) {
        $browse_ads_page_name = get_awpcp_option('browse-ads-page-name');
        if ( is_page( sanitize_title( $browse_ads_page_name ) ) ) {
            if ( get_awpcp_option( 'main_page_display' ) ) {
                $browse_cats_url = awpcp_get_view_categories_url();
            } else {
                $awpcp_page_id = awpcp_get_page_id_by_ref( 'main-page-name' );

                // we don't use get_permalink because it will return the homepage URL
                // if the main AWPCP page happens to be also the front page, and that
                // will break our rewrite rules
                $permalink_structure = get_option( 'permalink_structure' );
                if ( ! empty( $permalink_structure ) ) {
                    $base_url = home_url( get_page_uri( $awpcp_page_id ) );
                } else {
                    $base_url = add_query_arg( 'page_id', $awpcp_page_id, home_url() );
                }

                $browse_cats_url = user_trailingslashit( $base_url );
            }

            $view_categories_page_name = get_awpcp_option( 'view-categories-page-name' );
            $items['browse-listings'] = array( 'url' => $browse_cats_url, 'title' => $view_categories_page_name );
        } else {
            $browse_ads_url = awpcp_get_page_url( 'browse-ads-page-name' );
            $items['browse-listings'] = array( 'url' => $browse_ads_url, 'title' => $browse_ads_page_name );
        }
    }

    if ( $show_search_ads_item ) {
        $search_ads_page_name = get_awpcp_option( 'search-ads-page-name' );
        $search_ads_url = awpcp_get_page_url( 'search-ads-page-name' );
        $items['search-listings'] = array( 'url' => $search_ads_url, 'title' => $search_ads_page_name );
    }

    $items = apply_filters( 'awpcp_menu_items', $items );

    return $items;
}


/**
 * Renders the HTML content for a Category Item to be inserted inside a
 * LI or P element.
 */
function awpcp_display_classifieds_category_item($category, $class='toplevelitem', $ads_in_cat=false) {
	global $awpcp_imagesurl;

	// $permastruc = get_option('permalink_structure');
	// $awpcp_browsecats_pageid=awpcp_get_page_id_by_ref('browse-categories-page-name');

	// // Category URL
	// $modcatname1=cleanstring($category[1]);
	// $modcatname1=add_dashes($modcatname1);

	// $base_url = get_permalink($awpcp_browsecats_pageid);
	// if (get_awpcp_option('seofriendlyurls')) {
	// 	if (isset($permastruc) && !empty($permastruc)) {
	// 		$url_browsecats = sprintf('%s/%s/%s', trim($base_url, '/'), $category[0], $modcatname1);
	// 	} else {
	// 		$params = array('a' => 'browsecat', 'category_id' => $category[0]);
	// 		$url_browsecats = add_query_arg($params, $base_url);
	// 	}
	// } else {
	// 	if (isset($permastruc) && !empty($permastruc)) {
	// 		$params = array('category_id' => "$category[0]/$modcatname1");
	// 		$url_browsecats = add_query_arg($params, $base_url);
	// 	} else {
	// 		$params = array('a' => 'browsecat', 'category_id' => $category[0]);
	// 		$url_browsecats = add_query_arg($params, $base_url);
	// 	}
	// }
	$url_browsecats = url_browsecategory($category[0]);

	// Category icon
	if (function_exists('get_category_icon')) {
		$category_icon = get_category_icon($category[0]);
	}

	// Ads count
	if ( $ads_in_cat === false && get_awpcp_option('showadcount') == 1) {
		$ads_in_cat = '(' . total_ads_in_cat($category[0]) . ')';
	} else if ( $ads_in_cat !== false ) {
        $ads_in_cat = '(' . $ads_in_cat . ')';
    } else {
		$ads_in_cat = '';
	}

	if ( isset( $category_icon ) && !empty( $category_icon ) && function_exists( 'awpcp_category_icon_url' ) ) {
        $cat_icon_url = awpcp_category_icon_url( $category_icon );
		$cat_icon = "<img class=\"categoryicon\" src=\"$cat_icon_url\" alt=\"$category[1]\" border=\"0\"/>";
		$cat_icon = sprintf('<a href="%s">%s</a>', esc_url($url_browsecats), $cat_icon);
	} else {
		$cat_icon = '';
	}

	return $cat_icon . '<a class="' . $class . '" href="' . $url_browsecats . '">' . $category[1] . '</a> ' . $ads_in_cat;
}


function awpcp_render_category_item($category, $args=array()) {
    global $awpcp_imagesurl;

    extract( wp_parse_args( $args, array(
        'link_class' => 'toplevelitem',
        'show_ad_count' => true,
        'collapse_categories' => false,
    ) ) );

    $url_browsecats = url_browsecategory( $category->id );

    if ( function_exists( 'get_category_icon' ) ) {
        $category_icon = get_category_icon( $category->id );
        if ( ! empty( $category_icon ) && function_exists( 'awpcp_category_icon_url' ) ) {
            $icon_url = awpcp_category_icon_url( $category_icon );
            $image = '<img class="categoryicon" src="%s" alt="%s" border="0" />';
            $image = sprintf( $image, $icon_url, esc_attr( $category->name ) );
            $icon = sprintf( '<a href="%s">%s</a>', esc_url( $url_browsecats ), $image );
        }
    }

    if ( $collapse_categories ) {
        $handler = $level === 1 ? '<a class="js-handler" href="#"><span></span></a>' : '';
    } else {
        $handler = '';
    }

    $output = '%s <a class="%s" href="%s">%s</a> %s %s';

    return sprintf( $output, isset( $icon ) ? $icon : '',
                             $link_class,
                             esc_url( $url_browsecats ),
                             esc_attr( $category->name ),
                             $show_ad_count ? "({$category->ad_count})" : '',
                             $handler);
}


function awpcp_render_categories_items($parent=0, $args=array()) {
    global $wpdb;

    // same as the args for awpcp_render_categories
    extract( $args );

    $categories = AWPCP_Category::query( array(
        'where' => $wpdb->prepare( 'category_parent_id = %d', $parent ),
        'orderby' => 'category_order ASC, category_name',
        'order' => 'ASC',
    ) );

    if ( $hide_empty || get_awpcp_option( 'showadcount' ) ) {
        $approved = array();
        foreach ( $categories as $category ) {
            $count = total_ads_in_cat( $category->id );

            if ( $hide_empty && $count == 0 ) continue;

            $category->ad_count = $count;
            $approved[] = $category;
        }

        $categories = $approved;
    }

    $collapse_categories = get_awpcp_option( 'collapse-categories-columns' );

    $item_args = array(
        'level' => $level,
        'link_class' => '',
        'show_ad_count' => $show_ad_count,
        'collapse_categories' => $collapse_categories,
    );

    if ( $level === 1) {
        $container_class = 'top-level-categories showcategoriesmainlist';
        $item_args['link_class'] = 'toplevelitem';
    } else {
        $container_class = 'sub-categories showcategoriessublist';
        // do not group sub-categories
        $columns = count( $categories );
    }

    $output = '';

    $k = 0;
    foreach ( $categories as $category ) {
        if ( $k > 0 && $k % $columns === 0 ) {
            $output .= '</ul>';
        }

        if ( $k === 0 || $k % $columns === 0 ) {
            if ( $level > 1 && $collapse_categories ) {
                $output .= '<ul class="' . $container_class . ' clearfix" data-collapsible="true">';
            } else {
                $output .= '<ul class="' . $container_class . ' clearfix">';
            }
        }

        if ($level === 1) {
            $output.= sprintf( '<li class="columns-%d">', $columns );
            $output.= '<p class="top-level-category maincategoryclass ">';
            $output.= awpcp_render_category_item( $category, $item_args );
            $output.= '</p>';
        } else {
            $output.= '<li>';
            $output.= awpcp_render_category_item( $category, $item_args );
        }

        if ($show_children) {
            $output.= awpcp_render_categories_items($category->id, array_merge( $args, array( 'level' => $level + 1 ) ) );
        }

        $output.= '</li>';

        $k = $k + 1;
    }

    if ( count( $categories ) > 0 ) {
        $output .= '</ul>';
    }

    return $output;
}


function awpcp_render_categories( $parent=0, $args=array() ) {
    global $hasregionsmodule;

    $args = wp_parse_args( $args, array(
        'level' => 1,
        'columns' => get_awpcp_option('view-categories-columns', 2),
        'show_sidebar' => true,
        'hide_empty' => false,
        'show_children' => true,
    ) );

    extract( $args );

    if ( $show_sidebar && $hasregionsmodule === 1 && get_awpcp_option( 'showregionssidelist' ) ) {
        $sidebar = awpcp_region_control_render_sidelist();
        $showing_sidebar = true;
    } else {
        $showing_sidebar = false;
    }

    $categories = awpcp_render_categories_items( $parent, $args );

    if ( !empty( $categories ) ) {
        if ( $showing_sidebar ) {
            $output = '<div id="awpcpcatlayout" class="awpcp-categories-list">%s<div class="awpcpcatlayoutleft">%s</div></div><div class="fixfloat"></div>';
            $output = sprintf( $output, $sidebar, $categories );
        } else {
            $output = '<div id="awpcpcatlayout" class="awpcp-categories-list">%s</div><div class="fixfloat"></div>';
            $output = sprintf( $output, $categories );
        }
    } else {
        $output = '';
    }

    return $output;
}

/**
 * @deprecated  since 3.0-beta20
 * @param  [type]  $awpcppagename [description]
 * @param  integer $parent        [description]
 * @return [type]                 [description]
 */
function awpcp_display_the_classifieds_category($awpcppagename, $parent=0, $sidebar=true, $columns=false, $hide_empty=false) {
	global $wpdb;
	global $awpcp_imagesurl;
	global $hasregionsmodule;

	$usingsidelist = 0;

	$awpcp_page_id=awpcp_get_page_id_by_ref('main-page-name');
	$browsecatspagename=sanitize_title(get_awpcp_option('browse-categories-page-name'));

	$table_cols = 1;
	$query = "SELECT category_id,category_name FROM " . AWPCP_TABLE_CATEGORIES . " ";
	$query.= "WHERE category_parent_id = %d AND category_name <> '' ";
	$query.= "ORDER BY category_order, category_name ASC";

	$results = $wpdb->get_results( $wpdb->prepare( $query, $parent ), ARRAY_N );

    $myreturn = '';

    if ( count( $results ) > 0 ) {
        $myreturn = '<div id="awpcpcatlayout" class="awpcp-categories-list">';// Open the container division

        // For use with regions module if sidelist is enabled
        if ($sidebar && $hasregionsmodule == 1) {
            if (get_awpcp_option('showregionssidelist')) {
                $awpcpregions_sidepanel = awpcp_region_control_render_sidelist();
                $usingsidelist = true;
            }
        }

        if ($usingsidelist) {
            $myreturn.="$awpcpregions_sidepanel<div class=\"awpcpcatlayoutleft\">";
        }

        $i = 0;
        if ($columns === false) {
            $columns = get_awpcp_option('view-categories-columns', 2);
        }

        foreach ( $results as $rsrow ) {
            if ($i > 0 && $i % $columns == 0) {
                $myreturn .= '</ul>';
            }
            if ($i == 0 || $i % $columns == 0) {
                $myreturn .= '<ul class="showcategoriesmainlist clearfix">';
            }

            $myreturn .= '<li class="columns-' . $columns . '">';
            $myreturn .= '<p class="maincategoryclass">';
            $myreturn .= awpcp_display_classifieds_category_item($rsrow);
            $myreturn .= '</p>';

            $mcid = $rsrow[0];

            $query = "SELECT category_id,category_name FROM ". AWPCP_TABLE_CATEGORIES ." ";
            $query.= "WHERE category_parent_id='$mcid' AND category_name <> '' ";
            $query.= "ORDER BY category_order,category_name ASC";
            $res2 = awpcp_query($query, __LINE__);

            if (mysql_num_rows($res2)) {
                $myreturn .= "<ul class=\"showcategoriessublist\">";
                while ($rsrow2=mysql_fetch_row($res2)) {
                    $myreturn .= "<li>";
                    $myreturn .= awpcp_display_classifieds_category_item($rsrow2, '');
                    $myreturn .= "</li>";
                }
                $myreturn .= "</ul>";
            }

            $myreturn .= "</li>";
            $i++;
        }

        $myreturn .= "</ul>";

        if ($usingsidelist) {
            $myreturn.='</div>'; // To close div class awpcplayoutleft
        }

        $myreturn .= '</div>';// Close the container division
        $myreturn .= "<div class=\"fixfloat\"></div>";
    }

	return $myreturn;
}


/**
 * Configure the page to display to user for purpose of editing images during
 * Ad editing process.
 *
 * This function is not really used.
 *
 * @deprecated
 */
function editimages($adtermid, $adid, $adkey, $editemail) {
    _deprecated_function( __FUNCTION__, '3.0.2', 'No longer needed.' );

	global $wpdb;

	$output = '';

	$imgstat = '';
	$awpcpuperror = '';

	if (strcasecmp($editemail, get_adposteremail($adid)) == 0) {

		$imagecode = '<h2>' . __('Manage your Ad images','AWPCP') . '</h2>';

		if (!isset($adid) || empty($adid)) {
			$imagecode.=__("There has been a problem encountered. The system is unable to continue processing the task in progress. Please start over and if you encounter the problem again, please contact a system administrator.","AWPCP");

		} else {
			// First make sure images are allowed
			if (get_awpcp_option('imagesallowdisallow') == 1) {
				// Next figure out how many images user is allowed to upload
				$numimgsallowed = awpcp_get_ad_number_allowed_images($adid, $adtermid);

				// Next figure out how many (if any) images the user has previously uploaded
				$totalimagesuploaded = get_total_imagesuploaded($adid);

				// Next determine if the user has reached their image quota and act accordingly
				if ($totalimagesuploaded >= 1) {
					$imagecode.="<p>";
					$imagecode.=__("Your images are displayed below. The total number of images you are allowed is","AWPCP");
					$imagecode.=": $numimgsallowed</p>";

					if (($numimgsallowed - $totalimagesuploaded) == 0) {
						$imagecode.="<p>";
						$imagecode.=__("If you want to change your images you will first need to delete the current images","AWPCP");
						$imagecode.="</p>";
					}

					$admin_must_approve = get_awpcp_option('imagesapprove');
					if ($admin_must_approve == 1) {
						$imagecode.="<p>";
						$imagecode.=__("Image approval is in effect so any new images you upload will not be visible to viewers until an admin has approved it","AWPCP");
						$imagecode.="</p>";
					}

					// Display the current images
					$imagecode .= "<div id=\"displayimagethumbswrapper\"><div id=\"displayimagethumbs\"><ul>";
					$theimage = '';

					$query = "SELECT key_id,image_name,disabled FROM " . AWPCP_TABLE_ADPHOTOS . " ";
					$query.= "WHERE ad_id='$adid' ORDER BY image_name ASC";

					$res = awpcp_query($query, __LINE__);

					while ($rsrow=mysql_fetch_row($res)) {
						list($ikey,$image_name,$disabled) = $rsrow;

						$ikey = sprintf(join('_', array($ikey, $adid, $adtermid, $adkey, $editemail)));
						$ikey = str_replace('@', '-', $ikey);
						$actions = array();

						$editadpageid = awpcp_get_page_id_by_ref('edit-ad-page-name');
						$url_editpage = get_permalink($editadpageid);

						$href = add_query_arg(array('a' => 'dp', 'k' => str_replace('@','-',$ikey)), $url_editpage);
						$actions[] = sprintf('<a href="%s">%s</a>', $href, _x('Delete', 'edit ad', 'AWPCP'));

						$transval = '';
						if ((awpcp_current_user_is_admin() || !$admin_must_approve) && $disabled == 1) {
							$transval = 'class="imgtransparency"';
							$href = add_query_arg(array('a' => 'enable-picture', 'k' => $ikey), $url_editpage);
							$actions[] = sprintf('<a href="%s">%s</a>', $href, _x('Enable', 'edit ad', 'AWPCP'));
						} else if (awpcp_current_user_is_admin() || !$admin_must_approve) {
							$href = add_query_arg(array('a' => 'disable-picture', 'k' => $ikey), $url_editpage);
							$actions[] = sprintf('<a href="%s">%s</a>', $href, _x('Disable', 'edit ad', 'AWPCP'));
						} else if ($disabled == 1) {
							$transval = 'class="imgtransparency"';
							$actions[] = '<font style="font-size:smaller;">' . __('Disabled','AWPCP') . '</font>';
						}

						$large_image = awpcp_get_image_url($image_name, 'large');
						$thumbnail = awpcp_get_image_url($image_name, 'thumbnail');

						$theimage .= "<li>";
						$theimage .= "<a class=\"thickbox\" href=\"" . $large_image . "\">";
						$theimage .= "<img $transval src=\"" . $thumbnail . "\"/>";
						$theimage .= "</a>";
						$theimage .= sprintf("<br/>%s", join(' | ', $actions));
						$theimage .= "</li>";
					}

					$imagecode.=$theimage;
					$imagecode.="</ul></div></div>";
					$imagecode.="<div class=\"fixfloat\"></div>";

				} elseif ($totalimagesuploaded < 1) {
					$imagecode.=__("You do not currently have any images uploaded. Use the upload form below to upload your images. If you do not wish to upload any images simply click the finish button. If uploading images, be careful not to click the finish button until after you've uploaded all your images","AWPCP");
				}

				if ($totalimagesuploaded < $numimgsallowed) {
					$max_image_size=get_awpcp_option('maximagesize');
					$showimageuploadform=display_awpcp_image_upload_form($adid,$adtermid,$adkey,$adaction='editad',$nextstep='finish',$adpaymethod='',$awpcpuperror);
				} else {
					$showimageuploadform=display_awpcp_image_upload_form($adid,$adtermid,$adkey,$adaction='editad',$nextstep='finishnoform',$adpaymethod='',$awpcpuperror);
				}

			}

			$imagecode.=$showimageuploadform;
			$imagecode.="<div class=\"fixfloat\"></div>";
		}

		$output .= "<div id=\"classiwrapper\">$imagecode</div>";
	}
	return $output;
}
