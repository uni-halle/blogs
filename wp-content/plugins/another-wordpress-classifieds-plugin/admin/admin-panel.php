<?php
/**
 * AWPCP Classifieds Management Panel functions
 */

require_once(AWPCP_DIR . '/admin/admin-panel-home.php');
require_once(AWPCP_DIR . '/admin/admin-panel-upgrade.php');
require_once(AWPCP_DIR . '/admin/admin-panel-csv-importer.php');
require_once(AWPCP_DIR . '/admin/admin-panel-debug.php');
// require_once(AWPCP_DIR . '/admin/admin-panel-categories.php');
require_once(AWPCP_DIR . '/admin/admin-panel-fees.php');
require_once(AWPCP_DIR . '/admin/admin-panel-credit-plans.php');
require_once(AWPCP_DIR . '/admin/admin-panel-listings.php');
require_once(AWPCP_DIR . '/admin/admin-panel-settings.php');
require_once(AWPCP_DIR . '/admin/admin-panel-uninstall.php');
require_once(AWPCP_DIR . '/admin/admin-panel-users.php');


class AWPCP_Admin {

	public function __construct() {
		$this->title = _x('AWPCP Classifieds Management System', 'awpcp admin menu', 'AWPCP');
		$this->menu = _x('Classifieds', 'awpcp admin menu', 'AWPCP');

		// not a page, but an extension to the Users table
		$this->users = new AWPCP_AdminUsers();

		$this->home = new AWPCP_AdminHome();
		$this->upgrade = new AWPCP_AdminUpgrade(false, false, $this->menu);
		$this->settings = new AWPCP_Admin_Settings();
		$this->credit_plans = new AWPCP_AdminCreditPlans();
		// $this->categories = new AWPCP_AdminCategories();
		$this->fees = new AWPCP_AdminFees();
		$this->listings = new AWPCP_Admin_Listings();
		$this->importer = new AWPCP_Admin_CSV_Importer();
		$this->debug = new AWPCP_Admin_Debug();
		$this->uninstall = new AWPCP_Admin_Uninstall();

		add_action('wp_ajax_disable-quick-start-guide-notice', array($this, 'disable_quick_start_guide_notice'));
		add_action('wp_ajax_disable-widget-modification-notice', array($this, 'disable_widget_modification_notice'));

		add_action('admin_init', array($this, 'init'));
		add_action('admin_enqueue_scripts', array($this, 'scripts'));
		add_action('admin_menu', array($this, 'menu'));

		add_action('admin_notices', array($this, 'notices'));
		add_action( 'awpcp-admin-notices', array( $this, 'check_duplicate_page_names' ) );

		// make sure AWPCP admins (WP Administrators and/or Editors) can edit settings
		add_filter('option_page_capability_awpcp-options', 'awpcp_admin_capability');

		// hook filter to output Admin panel sidebar. To remove the sidebar
		// just remove this action
		add_filter('awpcp-admin-sidebar', 'awpcp_admin_sidebar_output', 10, 2);
	}


	public function notices() {
		if ( ! awpcp_current_user_is_admin() ) return;

		if ( awpcp_request_param( 'page', false ) == 'awpcp-admin-upgrade' ) return;

		if ( get_option( 'awpcp-pending-manual-upgrade' ) ) {
			ob_start();
				include( AWPCP_DIR . '/admin/templates/admin-pending-manual-upgrade-notice.tpl.php' );
				$html = ob_get_contents();
			ob_end_clean();

			echo $html;

			return;
		}

		if (get_awpcp_option('show-quick-start-guide-notice')) {
			ob_start();
				include(AWPCP_DIR . '/admin/templates/admin-quick-start-guide-notice.tpl.php');
				$html = ob_get_contents();
			ob_end_clean();

			echo $html;
		}

		if (get_awpcp_option('show-widget-modification-notice')) {
			ob_start();
				include(AWPCP_DIR . '/admin/templates/admin-widget-modification-notice.tpl.php');
				$html = ob_get_contents();
			ob_end_clean();

			echo $html;
		}

		do_action( 'awpcp-admin-notices' );
	}

	/**
	 * Shows a notice if any of the AWPCP pages shares its name with the
	 * dynamic page View Categories.
	 *
	 * If a page share its name with the View Categories page, that page
	 * will become unreachable.
	 *
	 * @since 3.0.2
	 */
	public function check_duplicate_page_names() {
		global $wpdb;

		$view_categories_option = 'view-categories-page-name';
		$view_categories = sanitize_title( awpcp_get_page_name( $view_categories_option ) );
		$view_categories_url = awpcp_get_view_categories_url();

		$duplicates = array();
		$awpcp_pages = array();
		$wp_pages = array();
		$pages = array();

		$posts = get_posts( array( 'post_type' => 'page', 'name' => $view_categories ) );
		foreach ( $posts as $post ) {
			if ( $view_categories_url == get_permalink( $post->ID ) ) {
				$duplicates[] = $post;
			}
		}

		if ( !empty( $duplicates ) ) {
			$query = 'SELECT id, page FROM ' . AWPCP_TABLE_PAGES .  ' WHERE page != %s';
			$query = $wpdb->prepare( $query, 'view-categories-page-name' );
			$pages = $wpdb->get_results( $query, OBJECT_K );
		} else {
			$pages = array();
		}

		foreach ( $duplicates as $page ) {
			if ( isset( $pages[ $page->ID ] ) ) {
				$awpcp_pages[] = ucwords( awpcp()->settings->get_option_label( $pages[ $page->ID ]->page ) );
			} else {
				$wp_pages[] = $page->post_title;
			}
		}

		if ( !empty( $awpcp_pages ) || !empty( $wp_pages ) ) {
			$view_categories_label = awpcp()->settings->get_option_label( $view_categories_option );
			$view_categories_label = sprintf( '<strong>%s</strong>', ucwords( $view_categories_label ) );
		}

		if ( !empty( $awpcp_pages ) ) {
			$duplicated_pages = '<strong>' . join( '</strong>, <strong>', $awpcp_pages ) . '</strong>';

			$message = _n( count( $awpcp_pages),
						  '%1$s has the same name as the %2$s. That will cause %1$s to become unreachable. Please make sure you don\'t have duplicate page names.',
						  '%1$s have the same name as the %2$s. That will cause %1$s to become unreachable. Please make sure you don\'t have duplicate page names.',
						  'AWPCP' );
			$message = sprintf( $message, $duplicated_pages, $view_categories_label );

			echo awpcp_print_error( $message );
		}

		if ( !empty( $wp_pages ) ) {
			$duplicated_pages = '<strong>' . join( '</strong>, <strong>', $wp_pages ) . '</strong>';

			$message = _n( count( $wp_pages),
						  'Page %1$s has the same name as the %2$s. That will cause page %1$s to become unreachable. Please make sure you don\'t have duplicate page names.',
						  'Pages %1$s have the same name as the %2$s. That will cause pages %1$s to become unreachable. Please make sure you don\'t have duplicate page names.',
						  'AWPCP' );
			$message = sprintf( $message, $duplicated_pages, $view_categories_label );

			echo awpcp_print_error( $message );
		}
	}


	public function init() {
		add_filter( 'parent_file', array( $this, 'parent_file' ) );

		// This functions were executed on plugins_loaded. However,
		// to avoid execution of AWPCP functions without propperly
		// upgrading the plugin database, we execute them here, only
		// after AWPCP_Admin has been instatiated by AWPCP.
		awpcp_handle_admin_requests();
	}

	public function scripts() {
	}

	private function get_manage_credits_section_url() {
		return add_query_arg( 'action', 'awpcp-manage-credits', admin_url( 'users.php' ) );
	}

	/**
	 * A hack to show the WP Users associated to a submenu under
	 * Classifieds menu.
	 *
	 * @since 3.0.2
	 */
	public function parent_file($parent_file) {
		global $current_screen, $submenu_file, $typenow;

		if ( $current_screen->base == 'users' && awpcp_request_param( 'action' ) == 'awpcp-manage-credits' ) {
			// make Classifieds menu the current menu
			$parent_file = 'awpcp.php';
			// highlight Manage Credits submenu in Classifieds menu
			$submenu_file = $this->get_manage_credits_section_url();
			// make $typenow non empty so Users menu is not highlighted
			// in _wp_menu_output, despite the fact we are showing the
			// All Users page.
			$typenow = 'hide-users-menu';
		}

		return $parent_file;
	}

	public function menu() {
		global $submenu;

		global $hasregionsmodule;
		global $hasextrafieldsmodule;

		$capability = awpcp_admin_capability();

		if (get_option('awpcp-pending-manual-upgrade')) {
			$parts = array($this->upgrade->title, $this->upgrade->menu, $this->upgrade->page);
			$page = add_menu_page($parts[0], $parts[1], $capability, $parts[2], array($this->upgrade, 'dispatch'), MENUICO);

		} else {
			$parent = $this->home->page;

			$parts = array($this->home->title, $this->home->menu, $this->home->page);
			$page = add_menu_page($parts[0], $parts[1], $capability, $parts[2], array($this->home, 'dispatch'), MENUICO);

			// add hidden upgrade page, so the URL works even if there are no
			// pending manual upgrades please note that this is a hack and
			// it is important to use a subpage as parent page for it to work
			$parts = array($this->title, $this->menu, $this->upgrade->page);
			$page = add_submenu_page('awpcp-admin-uninstall', $parts[0], $parts[1], $capability, $parts[2], array($this->home, 'dispatch'), MENUICO);

			$page = add_submenu_page($parent, __('Configure General Options', 'AWPCP'), __('Settings', 'AWPCP'), $capability, 'awpcp-admin-settings', array($this->settings, 'dispatch'));
			add_action('admin_print_styles-' . $page, array($this->settings, 'scripts'));

			$parts = array($this->credit_plans->title, $this->credit_plans->menu, $this->credit_plans->page);
			$page = add_submenu_page($parent, $parts[0], $parts[1], $capability, $parts[2], array($this->credit_plans, 'dispatch'));
			add_action('admin_print_styles-' . $page, array($this->credit_plans, 'scripts'));

			if ( current_user_can( $capability ) ) {
				$url = $this->get_manage_credits_section_url();
				$submenu['awpcp.php'][] = array( __( 'Manage Credit', 'AWPCP' ), $capability, $url );
			}

			$parts = array($this->fees->title, $this->fees->menu, $this->fees->page);
			$page = add_submenu_page($parent, $parts[0], $parts[1], $capability, $parts[2], array($this->fees, 'dispatch'));
			add_action('admin_print_styles-' . $page, array($this->fees, 'scripts'));

			// $parts = array($this->categories->title, $this->categories->menu, $this->categories->page);
			// $page = add_submenu_page($parent, $parts[0], $parts[1], $capability, $parts[2], array($this->categories, 'dispatch'));
			// add_action('admin_print_styles-' . $page, array($this->categories, 'scripts'));

			add_submenu_page($parent, __('Add/Edit Categories', 'AWPCP'), __('Categories', 'AWPCP'), $capability, 'awpcp-admin-categories', 'awpcp_opsconfig_categories');

			$parts = array($this->listings->title, $this->listings->menu, $this->listings->page);
			$page = add_submenu_page($parent, $parts[0], $parts[1], $capability, 'awpcp-listings', array($this->listings, 'dispatch'));
			add_action('admin_print_styles-' . $page, array($this->listings, 'scripts'));
			// add_submenu_page($parent, 'Manage Ad Listings', 'Listings', $capability, 'Manage1', 'awpcp_manage_viewlistings');

			// allow plugins to define additional sub menu entries
			do_action('awpcp_admin_add_submenu_page', $parent, $capability);

			if ($hasextrafieldsmodule) {
				add_submenu_page($parent, __('Manage Extra Fields', 'AWPCP'), __('Extra Fields', 'AWPCP'), $capability, 'Configure5', 'awpcp_add_new_field');
			}

			$hook = add_submenu_page($parent, __('Import Ad', 'AWPCP'), __('Import', 'AWPCP'), $capability, 'awpcp-import', array($this->importer, 'dispatch'));
			add_action("load-{$hook}", array($this->importer, 'scripts'));

			add_submenu_page($parent, 'Debug', 'Debug', $capability, 'awpcp-debug', array($this->debug, 'dispatch'));

			$parts = array($this->uninstall->title, $this->uninstall->menu, $this->uninstall->page);
			add_submenu_page($parent, $parts[0], $parts[1], $capability, $parts[2], array($this->uninstall, 'dispatch'));

			// allow plugins to define additional menu entries
			do_action('awpcp_add_menu_page');
		}
	}

	public function upgrade() {
		global $plugin_page;

		if (!isset($this->upgrade) && isset($this->pages[$plugin_page]))
			$this->upgrade = new AWPCP_AdminUpgrade($plugin_page, $this->pages[$plugin_page]);
		return $this->upgrade->dispatch();
	}

    public function disable_quick_start_guide_notice() {
        global $awpcp;
        $awpcp->settings->update_option('show-quick-start-guide-notice', false);
        die('Success!');
    }

    public function disable_widget_modification_notice() {
        global $awpcp;
        $awpcp->settings->update_option('show-widget-modification-notice', false);
        die('Success!');
    }
}


// // if there's a page name collision remove AWPCP menus so that nothing can be accessed
// add_action('init', 'awpcp_pagename_warning_check', -1);
// function awpcp_pagename_warning_check() { 
// 	if (!get_option('awpcp_pagename_warning', false)) {
// 		return;
// 	}
//     remove_action('admin_menu', 'awpcp_launch');
// }


// // display a warning if necessary
// add_action('admin_notices', 'awpcp_pagename_warning', 10);
// function awpcp_pagename_warning() { 
// 	if (!get_option('awpcp_pagename_warning', false)) {
// 		return;
// 	}
// 	echo '<div id="message" class="error"><p><strong>';	
// 	echo 'WARNING: </strong>A page named AWPCP already exists. You must either delete that page and its subpages, or rename them before continuing with the plugin configuration.';
// 	echo '</p></div>';
// }




// START FUNCTION: Check if the user side classified page exists


function checkifclassifiedpage($pagename) {
	global $wpdb, $table_prefix;

	$id = awpcp_get_page_id_by_ref('main-page-name');
	$query = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE ID = %d';
	$page_id = $wpdb->get_var($wpdb->prepare($query, $id));

	return $page_id === $id;
}


function awpcp_home_screen() {
	global $awpcp_db_version;
	global $awpcp_imagesurl;

	global $hasextrafieldsmodule, $extrafieldsversioncompatibility;

	global $message;

	// check if there is a duplicate page conflict
	$main_page_name = get_awpcp_option('main-page-name');
    $page_conflict = checkforduplicate(add_slashes_recursive(sanitize_title($main_page_name)));

	ob_start();
		include(AWPCP_DIR . '/admin/templates/admin-panel-home.tpl.php');
		$content = ob_get_contents();
	ob_end_clean();

	echo $content;
}


function awpcp_get_categories_hierarchy() {
	$categories = AWPCP_Category::query();

	$hierarchy = array();
	foreach ( $categories as $category ) {
		if ( $category->parent > 0 ) {
			if ( !isset( $hierarchy[ $category->parent ] ) ) {
				$hierarchy[ $category->parent ] = array();
			}
			$hierarchy[ $category->parent ][] = $category->id;
		}
	}

	return $hierarchy;
}

function awpcp_admin_categories_render_category_items($categories, &$children, $start=0, $per_page=10, &$count, $parent=0, $level=0) {
	$end = $start + $per_page;

	$items = array();
	foreach ($categories as $key => $category) {
		if ( $count >= $end ) break;

		if ( $category->parent != $parent ) continue;

		if ( $count == $start && $category->parent > 0 ) {
			$category_parent = AWPCP_Category::find_by_id( $category->parent );
			$items[] = awpcp_admin_categories_render_category_item( $category_parent, $level - 1, $start, $per_page );
		}

		if ( $count >= $start ) {
			$items[] = awpcp_admin_categories_render_category_item( $category, $level, $start, $per_page  );
		}

		$count++;

		if ( isset( $children[ $category->id ] ) ) {
			$_children = awpcp_admin_categories_render_category_items( $categories, $children, $start, $per_page, $count, $category->id, $level + 1 );
			$items = array_merge( $items, $_children );
		}
	}

	return $items;
}

function awpcp_admin_categories_render_category_item($category, $level, $start, $per_page) {
	global $hascaticonsmodule, $awpcp_imagesurl;

	if ( function_exists('get_category_icon') ) {
		$category_icon = get_category_icon( $category->id );
	}

	if ( isset( $category_icon ) && !empty( $category_icon ) && function_exists( 'awpcp_category_icon_url' )  ) {
		$caticonsurl = awpcp_category_icon_url( $category_icon );
		$thecategoryicon = '<img style="vertical-align:middle;margin-right:5px;" src="%s" alt="%s" border="0" />';
		$thecategoryicon = sprintf( $thecategoryicon, esc_url( $caticonsurl ), esc_attr( $category->name ) );
	} else {
		$thecategoryicon = '';
	}

	$params = array( 'page' => 'awpcp-admin-categories', 'cat_ID' => $category->id );
	$admin_listings_url = add_query_arg( $params, admin_url( 'admin.php' ) );

	$thecategory_parent_id = $category->parent;
	$thecategory_parent_name = stripslashes(get_adparentcatname($thecategory_parent_id));
	$thecategory_order = $category->order ? $category->order : 0;
	$thecategory_name = sprintf( '%s%s<a href="%s">%s</a>', str_repeat( '&mdash;&nbsp;', $level ),
															$thecategoryicon,
															$admin_listings_url,
															esc_attr( stripslashes( $category->name ) ) );

	$totaladsincat = total_ads_in_cat( $category->id );

	$params = array( 'cat_ID' => $category->id, 'offset' => $start, 'results' => $per_page );
	$admin_categories_url = add_query_arg( $params, awpcp_get_admin_categories_url() );

	if ($hascaticonsmodule == 1 && is_installed_category_icon_module()) {
		$url = add_query_arg( 'action', 'managecaticon', $admin_categories_url );
		$managecaticon = "<a href=\"$url\"><img src=\"$awpcp_imagesurl/icon_manage_ico.png\" alt=\"";
		$managecaticon.= __("Manage Category Icon", "AWPCP");
		$managecaticon.= "\" title=\"" . __("Manage Category Icon", "AWPCP") . "\" border=\"0\"/></a>";
	} else {
		$managecaticon = '';
	}

	$awpcpeditcategoryword = __("Edit Category","AWPCP");
	$awpcpdeletecategoryword = __("Delete Category","AWPCP");


	$row = '<tr>';
	$row.= '<td style="font-weight:normal; text-align: center;">' . $category->id . '</td>';
	$row.= "<td style=\"border-bottom:1px dotted #dddddd;font-weight:normal;\"><label><input type=\"checkbox\" name=\"category_to_delete_or_move[]\" value=\"{$category->id}\" /> $thecategory_name ($totaladsincat)</label></td>";
	$row.= "<td style=\"border-bottom:1px dotted #dddddd;font-weight:normal;\">$thecategory_parent_name</td>";
	$row.= "<td style=\"border-bottom:1px dotted #dddddd;font-weight:normal;\">$thecategory_order</td>";
	$row.= "<td style=\"border-bottom:1px dotted #dddddd;font-size:smaller;font-weight:normal;\">";
	$url = add_query_arg( 'action', 'editcat', $admin_categories_url );
	$row.= "<a href=\"$url\"><img src=\"$awpcp_imagesurl/edit_ico.png\" alt=\"$awpcpeditcategoryword\" title=\"$awpcpeditcategoryword\" border=\"0\"/></a>";
	$url = add_query_arg( 'action', 'delcat', $admin_categories_url );
	$row.= "<a href=\"$url\"><img src=\"$awpcp_imagesurl/delete_ico.png\" alt=\"$awpcpdeletecategoryword\" title=\"$awpcpdeletecategoryword\" border=\"0\"/></a>";
	$row.= $managecaticon;
	$row.= "</td>";
	$row.= "</tr>";

	return $row;
}


function awpcp_opsconfig_categories() {
	global $wpdb, $message, $awpcp_imagesurl, $clearform, $hascaticonsmodule;

	$cpagename_awpcp = get_awpcp_option('main-page-name');
	$awpcppagename = sanitize_title($cpagename_awpcp);

	$action='';
	$output = '';

		$tbl_ad_categories = $wpdb->prefix . "awpcp_categories";
		$offset=(isset($_REQUEST['offset'])) ? (clean_field($_REQUEST['offset'])) : ($offset=0);
		$results=(isset($_REQUEST['results']) && !empty($_REQUEST['results'])) ? clean_field($_REQUEST['results']) : ($results=10);

		if ( isset( $_REQUEST['results'] ) && !empty( $_REQUEST['results'] ) ) {
			$results = max( intval( $_REQUEST['results'] ), 5 );
			update_user_option( get_current_user_id(), 'awpcp-admin-categories-results', $results );
		} else {
			$results = intval( get_user_option( 'awpcp-admin-categories-results', get_current_user_id() ) );
		}

		if ( empty( $results ) ) {
			$results = 10;
		}

		$cat_ID='';
		$category_name='';
		$aeaction='';
		$category_parent_id='';
		$promptmovetocat='';
		$aeaction='';

		///////////////////
		// Check for existence of a category ID and action

		if ( isset($_REQUEST['editcat']) && !empty($_REQUEST['editcat']) )
		{
			$cat_ID=$_REQUEST['editcat'];
			$action = "edit";
		}
		elseif ( isset($_REQUEST['delcat']) && !empty($_REQUEST['delcat']) )
		{
			$cat_ID=$_REQUEST['delcat'];
			$action = "delcat";
		}
		elseif ( isset($_REQUEST['managecaticon']) && !empty($_REQUEST['managecaticon']) )
		{
			$cat_ID=$_REQUEST['managecaticon'];
			$action = "managecaticon";
		}
		elseif (isset($_REQUEST['cat_ID']) && !empty($_REQUEST['cat_ID']))
		{
			$cat_ID=$_REQUEST['cat_ID'];
		}


		if ( !isset($action)  || empty($action) )
		{
			if ( isset($_REQUEST['action']) && !empty($_REQUEST['action']) )
			{
				$action=$_REQUEST['action'];
			}

		}
		if ( $action == 'edit' )
		{
			$aeaction='edit';
		}

		if ( $action == 'editcat' )
		{
			$aeaction='edit';
		}

		if ( $action == 'delcat' )
		{
			$aeaction='delete';
		}

		if ( $action == 'managecaticon' ) {
			$output .= "<div class=\"wrap\"><h2>";
			$output .= __("AWPCP Classifieds Management System Categories Management","AWPCP");
			$output .= "</h2>
			";

			global $awpcp_plugin_path;

			if ($hascaticonsmodule == 1) {
				if (is_installed_category_icon_module()) {
					$output .= load_category_icon_management_page($defaultid=$cat_ID,$offset,$results);
				}
			}

			$output .= "</div>";
			return $output;
		}

		if ( $action == 'setcategoryicon' ) {
			global $awpcp_plugin_path;

			if ($hascaticonsmodule == 1) {
				if (is_installed_category_icon_module()) {
					if (isset($_REQUEST['cat_ID']) && !empty($_REQUEST['cat_ID'])) {
						$thecategory_id=$_REQUEST['cat_ID'];
					}

					if (isset($_REQUEST['category_icon']) && !empty($_REQUEST['category_icon'])) {
						$theiconfile=$_REQUEST['category_icon'];
					} elseif ( isset( $_REQUEST['clear_icon'] ) ) {
						$theiconfile = null;
					} else {
						$theiconfile = '';
					}

					if (isset($_REQUEST['offset']) && !empty($_REQUEST['offset'])) {
						$offset=$_REQUEST['offset'];
					}

					$message=set_category_icon($thecategory_id,$theiconfile,$offset,$results);
					if (isset($message) && !empty($message)) {
						$clearform=1;
					}
				}
			}
		}

		if (isset($clearform) && ($clearform == 1)) {
			$action = $aeaction = null;
		}

		$category_name=get_adcatname($cat_ID);
		$category_order=get_adcatorder($cat_ID);
		$category_order = ($category_order != 0 ? $category_order : 0);
		$cat_parent_ID=get_cat_parent_ID($cat_ID);

		$add_label = __( 'Ad A New Category', 'AWPCP' );
		$add_url = awpcp_get_admin_categories_url();
		$addnewlink = '<a class="" title="%1$s" href="%2$s"" accesskey="s">%1$s</a>';
		$addnewlink = sprintf( $addnewlink, $add_label, $add_url );

		if ($aeaction == 'edit')
		{
			$aeword1=__("You are currently editing the category shown below","AWPCP");
			$aeword2=__("Save Category Changes","AWPCP");
			$aeword3=__("Parent Category","AWPCP");
			$aeword4=__("Category List Order","AWPCP");
		}
		elseif ($aeaction == 'delete')
		{
			if ( $cat_ID != 1)
			{
				$aeword1=__("If you're sure that you want to delete this category please press the delete button","AWPCP");
				$aeword2=__("Delete Category","AWPCP");
				$aeword3=__("Parent Category","AWPCP");
				$aeword4='';

				if (ads_exist_cat($cat_ID))
				{
					if ( category_is_child($cat_ID) ) {
						$movetocat=get_cat_parent_ID($cat_ID);
					}
					else
					{
						$movetocat=1;
					}

					$movetoname=get_adcatname($movetocat);
					if ( empty($movetoname) )
					{
						$movetoname=__("Untitled","AWPCP");
					}

					$promptmovetocat="<p>";
					$promptmovetocat.=__("The category contains ads. If you do not select a category to move them to the ads will be moved to:","AWPCP");
					$promptmovetocat.="<b>$movetoname</b></p>";

					$defaultcatname=get_adcatname($catid=1);

					if ( empty($defaultcatname) )
					{
						$defaultcatname=__("Untitled","AWPCP");
					}

					if (category_has_children($cat_ID))
					{
						$promptmovetocat.="<p>";
						$promptmovetocat.=__("The category also has children. If you do not specify a move-to category the children will be adopted by","AWPCP");
						$promptmovetocat.="<b>$defaultcatname</b><p><b>";
						$promptmovetocat.=__("Note","AWPCP");
						$promptmovetocat.=":</b>";
						$promptmovetocat.=__("The move-to category specified applies to both ads and categories","AWPCP");
						$promptmovetocat.="</p>";
					}
					$promptmovetocat.="<p align=\"center\"><select name=\"movetocat\"><option value=\"0\">";
					$promptmovetocat.=__("Please select a Move-To category","AWPCP");
					$promptmovetocat.="</option>";
					$categories=  get_categorynameid($cat_ID,$cat_parent_ID,$exclude=$cat_ID);
					$promptmovetocat.="$categories</select>";
				}

				$thecategoryparentname=get_adparentcatname($cat_parent_ID);
			}
			else
			{
				$aeword1 = __( 'Sorry but you cannot delete %s. It is the default category and the default category cannot be deleted.', 'AWPCP' );
				$aeword1 = sprintf( $aeword1, '<strong>' . $category_name . '</strong>' );

				$aeword2='';
				$aeword3='';
				$aeword4='';
			}
		}
		else
		{
			if ( empty($aeaction) )
			{
				$aeaction="newcategory";
			}

			$aeword1 = __( 'Add a New Category', 'AWPCP' );
			$aeword2=__("Add New Category","AWPCP");
			$aeword3=__("List Category Under","AWPCP");
			$aeword4=__("Category List Order","AWPCP");
			$addnewlink = '';
		}
		if ($aeaction == 'delete')
		{
			$orderinput='';
			if ($cat_ID == 1)
			{
				$categorynameinput='';
				$selectinput='';
			}
			else
			{
				$categorynameinput="<p style=\"background:transparent url($awpcp_imagesurl/delete_ico.png) left center no-repeat;padding-left:20px;\">";
				$categorynameinput.=__("Category to Delete","AWPCP");
				$categorynameinput.=": $category_name</p>";
				$selectinput="<p style=\"background:#D54E21;padding:3px;color:#ffffff;\">$thecategoryparentname</p>";
				$submitbuttoncode="<input type=\"submit\" class=\"button-primary button\" name=\"createeditadcategory\" value=\"$aeword2\" />";
			}
		}
		elseif ($aeaction == 'edit')
		{
			$section_icon_style = "background:transparent url($awpcp_imagesurl/edit_ico.png) left center no-repeat;padding-left:20px;";

			$categorynameinput = "<p>";
			$categorynameinput.=__("Category to Edit","AWPCP");
			$categorynameinput.=": $category_name ";
			$categorynamefield = "<input name=\"category_name\" id=\"cat_name\" type=\"text\" class=\"inputbox\" value=\"$category_name\" size=\"40\" style=\"width: 220px\"/>";
			$selectinput="<select name=\"category_parent_id\"><option value=\"0\">";
			$selectinput.=__("Make This a Top Level Category","AWPCP");
			$selectinput.="</option>";
			$orderinput="<input name=\"category_order\" id=\"category_order\" type=\"text\" class=\"inputbox\" value=\"$category_order\" size=\"3\"/>";
			$categories=  get_categorynameid($cat_ID,$cat_parent_ID,$exclude='');
			$selectinput.="$categories
						</select>";
			$submitbuttoncode="<input type=\"submit\" class=\"button-primary button\" name=\"createeditadcategory\" value=\"$aeword2\" />";
		}
		else {
			$section_icon_style = "background:transparent url($awpcp_imagesurl/post_ico.png) left center no-repeat;padding-left:20px;";

			$categorynameinput="<p>";
			$categorynameinput .= __( 'Enter the category name', 'AWPCP' );
			$categorynamefield ="<input name=\"category_name\" id=\"cat_name\" type=\"text\" class=\"inputbox\" value=\"$category_name\" size=\"40\" style=\"width: 220px\"/>";
			$selectinput="<select name=\"category_parent_id\"><option value=\"0\">";
			$selectinput.=__("Make This a Top Level Category","AWPCP");
			$selectinput.="</option>";
			$orderinput="<input name=\"category_order\" id=\"category_order\" type=\"text\" class=\"inputbox\" value=\"$category_order\" size=\"3\"/>";
			$categories=  get_categorynameid($cat_ID,$cat_parent_ID,$exclude='');
			$selectinput.="$categories
					</select>";
			$submitbuttoncode="<input type=\"submit\" class=\"button-primary button\" name=\"createeditadcategory\" value=\"$aeword2\" />";
		}

		// Start the page display
		$output .= "<div class=\"wrap\"><h2>";
		$output .= __("AWPCP Classifieds Management System Categories Management","AWPCP");
		$output .= "</h2>";
		if (isset($message) && !empty($message))
		{
			$output .= $message;
		}

		$sidebar = awpcp_admin_sidebar();
		$output .= $sidebar;

		if (empty($sidebar)) {
			$output .= "<div style=\"padding:10px;\"><p>";
		} else {
			$output .= "<div style=\"padding:10px; width: 75%\"><p>";
		}

		$output .= "<b>";
		$output .= __("Icon Meanings","AWPCP");
		$output .= ":</b> &nbsp;&nbsp;&nbsp;<img src=\"$awpcp_imagesurl/edit_ico.png\" alt=\"";
		$output .= __("Edit Category","AWPCP");
		$output .= "\" border=\"0\"/>";
		$output .= __("Edit Category","AWPCP");
		$output .= " &nbsp;&nbsp;&nbsp;<img src=\"$awpcp_imagesurl/delete_ico.png\" alt=\"";
		$output .= __("Delete Category","AWPCP");
		$output .= "\" border=\"0\"/>";
		$output .= __("Delete Category","AWPCP");


		if ($hascaticonsmodule == 1 && is_installed_category_icon_module() ) {
			$label = __("Manage Category Icon", "AWPCP");
			$output .= " &nbsp;&nbsp;&nbsp;<img src=\"$awpcp_imagesurl/icon_manage_ico.png\" alt=\"";
			$output .= $label;
			$output .= "\" border=\"0\"/>";
			$output .= $label;
		}

		$output .= "
			 </div>";

		if (empty($sidebar)) {
			$output .= "<div class=\"postbox\" style=\"padding:10px;\"><p>";
		} else {
			$output .= "<div class=\"postbox\" style=\"width:75%;float:left;padding:10px;\">";
		}

		$output .= "<form method=\"post\" id=\"awpcp_launch\">
			 <input type=\"hidden\" name=\"category_id\" value=\"$cat_ID\" />
			  <input type=\"hidden\" name=\"aeaction\" value=\"$aeaction\" />
			  <input type=\"hidden\" name=\"offset\" value=\"$offset\" />
			  <input type=\"hidden\" name=\"results\" value=\"$results\" />

			<p style=\"line-height: 1.3em; $section_icon_style\"> $aeword1</p>
			<table width=\"75%\" cellpadding=\"5\"><tr>
			<td>$categorynameinput</td>
			<td>$aeword3</td>
			<td>$aeword4</td>
			</tr>
			<tr>
			<td>$categorynamefield</td>
			<td>$selectinput</td>
			<td>$orderinput</td>
			</tr>
			</table>

			$promptmovetocat

			<p style=\"margin-top:5px;\" class=\"submit\">$submitbuttoncode $addnewlink</p>
			 </form>
			 </div>";

		if (empty($sidebar)) {
			$output .= "<div style=\"margin:0;padding:0px 0px 10px 0;\"><p>";
		} else {
			$output .= "<div style=\"margin:0;padding:0px 0px 10px 0;float:left;width:75%\">";
		}

		///////////////////////////
		// Show the paginated categories list for management
		//////////////////////////

		$where="category_name <> ''";

		$pager1=create_pager( AWPCP_TABLE_CATEGORIES, $where,$offset,$results,$tpname='');
		$pager2=create_pager( AWPCP_TABLE_CATEGORIES, $where,$offset,$results,$tpname='');

		$output .= "$pager1 <form name=\"mycats\" id=\"mycats\" method=\"post\">
		 <p><input type=\"submit\" name=\"deletemultiplecategories\" class=\"button\" value=\"";
		$output .= __("Delete Selected Categories","AWPCP");
		$output .= "\" />
		 <input type=\"submit\" name=\"movemultiplecategories\" class=\"button\" value=\"";
		$output .= __("Move Selected Categories","AWPCP");
		$output .= "\" />
		 <select name=\"moveadstocategory\"><option value=\"0\">";
		$output .= __("Select Move-To category","AWPCP");
		$output .= "</option>";
		$movetocategories=  get_categorynameid($cat_id = 0,$cat_parent_id= 0,$exclude);
		$output .= "$movetocategories</select></p>
		<p>";
		$output .= __( 'Delete categories should do this with existing Ads', 'AWPCP' );
		$output .= ": <label><input type=\"radio\" name=\"movedeleteads\" value=\"1\" checked='checked' > " . __( 'Move Ads to new category', 'AWPCP' ) . "</label>";
		$output .= " <label><input type=\"radio\" name=\"movedeleteads\" value=\"2\" > " . __( 'Delete Ads too', 'AWPCP' ) . "</label></p>";

		$children = awpcp_get_categories_hierarchy();
		$categories = AWPCP_Category::query( array(
			'orderby' => 'category_order, category_name',
			'order' => 'ASC',
		) );

		$count = 0;
		$items = awpcp_admin_categories_render_category_items( $categories, $children, $offset, $results, $count );

		$opentable='<table class="listcatsh"><tr>';
		$opentable.='<td style="width:10%; text-align: center;">' . __('Category ID', 'AWPCP') . '</td>';
		$opentable.="<td style=\"width:30%;padding:5px;\"><label><input type=\"checkbox\" onclick=\"CheckAll()\" />&nbsp;";
		$opentable.=__("Category Name (Total Ads)","AWPCP");
		$opentable.="</label></td><td style=\"width:35%;padding:5px;\">";
		$opentable.=__("Parent","AWPCP");
		$opentable.="</td><td style=\"width:5%;padding:5px;\">";
		$opentable.=__("Order","AWPCP");
		$opentable.="</td><td style=\"width:20%;padding:5px;;\">";
		$opentable.=__("Action","AWPCP");
		$opentable.="</td></tr>";

		$closetable='<tr>';
		$closetable.='<td>' . __('Category ID', 'AWPCP') . '</td>';
		$closetable.='<td style="padding:5px;">';
		$closetable.=__("Category Name (Total Ads)","AWPCP");
		$closetable.="</td><td style=\"padding:5px;\">";
		$closetable.=__("Parent","AWPCP");
		$closetable.="</td><td style=\"padding:5px;\">";
		$closetable.=__("Order","AWPCP");
		$closetable.="</td><td style=\"padding:5px;\">";
		$closetable.=__("Action","AWPCP");
		$closetable.="</td></tr></table>";

		$theitems=smart_table2($items,intval($results/$results),$opentable,$closetable, false);
		$showcategories="$theitems";

		$output .= "
		<style>
		table.listcatsh { width: 100%; padding: 0px; border: none; border: 1px solid #dddddd;}
		table.listcatsh td { font-size: 12px; border: none; background-color: #F4F4F4;
		vertical-align: middle; font-weight: bold; }
		table.listcatsh tr.special td { border-bottom: 1px solid #ff0000;  }
		table.listcatsc { width: 100%; padding: 0px; border: none; border: 1px solid #dddddd;}
		table.listcatsc td { width:33%;border: none;
		vertical-align: middle; padding: 5px; font-weight: normal; }
		table.listcatsc tr.special td { border-bottom: 1px solid #ff0000;  }
		</style>
		$showcategories
		</form>$pager2</div>";

	echo $output;
}

function awpcp_pages() {
	$pages = array('main-page-name' => array(get_awpcp_option('main-page-name'), '[AWPCP]'));
	return $pages + awpcp_subpages();
}

function awpcp_subpages() {
	$pages = array(
		'show-ads-page-name' => array(get_awpcp_option('show-ads-page-name'), '[AWPCPSHOWAD]'),
		'reply-to-ad-page-name' => array(get_awpcp_option('reply-to-ad-page-name'), '[AWPCPREPLYTOAD]'),
		'edit-ad-page-name' => array(get_awpcp_option('edit-ad-page-name'), '[AWPCPEDITAD]'),
		'place-ad-page-name' => array(get_awpcp_option('place-ad-page-name'), '[AWPCPPLACEAD]'),
		'renew-ad-page-name' => array(get_awpcp_option('renew-ad-page-name'), '[AWPCP-RENEW-AD]'),
		'browse-ads-page-name' => array(get_awpcp_option('browse-ads-page-name'), '[AWPCPBROWSEADS]'),
		'browse-categories-page-name' => array(get_awpcp_option('browse-categories-page-name'), '[AWPCPBROWSECATS]'),
		'search-ads-page-name' => array(get_awpcp_option('search-ads-page-name'), '[AWPCPSEARCHADS]'),
		'payment-thankyou-page-name' => array(get_awpcp_option('payment-thankyou-page-name'), '[AWPCPPAYMENTTHANKYOU]'),
		'payment-cancel-page-name' => array(get_awpcp_option('payment-cancel-page-name'), '[AWPCPCANCELPAYMENT]')
	);

	$pages = apply_filters('awpcp_subpages', $pages);

	return $pages;
}

function awpcp_create_pages($awpcp_page_name, $subpages=true) {
	global $wpdb;
	
	$refname = 'main-page-name';
	$date = date("Y-m-d");

	// create AWPCP main page if it does not exist
	if (!awpcp_find_page($refname)) {
		$awpcp_page = array(
			'post_author' => 1,
			'post_date' => $date,
			'post_date_gmt' => $date,
			'post_content' => '[AWPCPCLASSIFIEDSUI]',
			'post_title' => add_slashes_recursive($awpcp_page_name),
			'post_status' => 'publish',
			'post_name' => sanitize_title($awpcp_page_name),
			'post_modified' => $date,
			'comments_status' => 'closed',
			'post_content_filtered' => '[AWPCPCLASSIFIEDSUI]',
			'post_parent' => 0,
			'post_type' => 'page',
			'menu_order' => 0
		);
		$id = wp_insert_post($awpcp_page);

		$previous = awpcp_get_page_id_by_ref($refname);
		if ($previous === false) {
			$wpdb->insert(AWPCP_TABLE_PAGES, array('page' => $refname, 'id' => $id));
		} else {
			$wpdb->update(AWPCP_TABLE_PAGES, array('page' => $refname, 'id' => $id), 
					  array('page' => $refname));	
		}
	} else {
		$id = awpcp_get_page_id_by_ref($refname);
	}

	// create subpages
	if ($subpages) {
		awpcp_create_subpages($id);
	}
}

function awpcp_create_subpages($awpcp_page_id) {
	$pages = awpcp_subpages();

	foreach ($pages as $key => $page) {
		awpcp_create_subpage($key, $page[0], $page[1], $awpcp_page_id);
	}
	
	do_action('awpcp_create_subpage');
}

/**
 * Creates a subpage of the main AWPCP page.
 * 
 * This functions takes care of checking if the main AWPCP
 * page exists, finding its id and verifying that the new
 * page doesn't exist already. Useful for module plugins.
 */
function awpcp_create_subpage($refname, $name, $shortcode, $awpcp_page_id=null) {
	global $wpdb;

	$id = 0;
	if (!empty($name)) {
		// it is possible that the main AWPCP page does not exist, in that case
		// we should create Subpages without a parent.
		if (is_null($awpcp_page_id) && awpcp_find_page('main-page-name')) {
			$awpcp_page_id = awpcp_get_page_id_by_ref('main-page-name');
		} else if (is_null(($awpcp_page_id))) {
			$awpcp_page_id = '';
		}

		if (!awpcp_find_page($refname)) {
			$id = maketheclassifiedsubpage($name, $awpcp_page_id, $shortcode);
		}
	}

	if ($id > 0) {
		$previous = awpcp_get_page_id_by_ref($refname);
		if ($previous === false) {
			$wpdb->insert(AWPCP_TABLE_PAGES, array('page' => $refname, 'id' => $id));
		} else {
			$wpdb->update(AWPCP_TABLE_PAGES, array('page' => $refname, 'id' => $id), 
					  array('page' => $refname));	
		}
	}

	return $id;
}


function maketheclassifiedsubpage( $page_name, $parent_page_id, $short_code ) {
	$post_date = date("Y-m-d");
	$parent_page_id = intval( $parent_page_id );
	$post_name = sanitize_title( $page_name );
	$page_name = add_slashes_recursive( $page_name );

	$page_id = wp_insert_post( array(
		'post_date' => $post_date,
		'post_date_gmt' => $post_date,
		'post_title' => $page_name,
		'post_content' => $short_code,
		'post_status' => 'publish',
		'comment_status' => 'closed',
		'post_name' => $post_name,
		'post_modified' => $post_date,
		'post_modified_gmt' => $post_date,
		'post_content_filtered' => $short_code,
		'post_parent' => $parent_page_id,
		'post_type' => 'page',
	) );

	return $page_id;
}


/**
 * A function created to wrap code intended to handle
 * Admin Panel requests.
 *
 * The body of this function was in the content of awpcp.php
 * being executed every time the plugin file was read.
 *
 * The part of this function that handles Fees is @deprecated since 2.1.4.
 * The part of this function that handles Ads is @deprecated since 2.1.4.
 * The part of this function that handles Categories is still being used.
 */
function awpcp_handle_admin_requests() {
	global $wpdb;
	global $message;

	if (isset($_REQUEST['createeditadcategory']) && !empty($_REQUEST['createeditadcategory']))
	{
		$tbl_ad_categories = $wpdb->prefix . "awpcp_categories";
		$tbl_ads = $wpdb->prefix . "awpcp_ads";

		$category_id=clean_field($_REQUEST['category_id']);


		if (isset($_REQUEST['$movetocat']) && !empty($_REQUEST['$movetocat']))
		{
			$movetocat=clean_field($_REQUEST['movetocat']);
		}
		if (isset($_REQUEST['$deletetheads']) && !empty($_REQUEST['$deletetheads']))
		{
			$deletetheads=$_REQUEST['deletetheads'];
		}

		$aeaction=clean_field($_REQUEST['aeaction']);

		if ( $aeaction == 'newcategory' ) {
			$name = stripslashes_deep( awpcp_request_param( 'category_name' ) );
			$parent = intval( awpcp_request_param( 'category_parent_id' ) );
			$order = intval( awpcp_request_param( 'category_order' ) );

			$category = new AWPCP_Category( null, $name, null, $order, $parent );

			try {
				awpcp_categories_collection()->save( $category );
				$themessagetoprint = __( 'The new category was successfully added.', 'AWPCP' );
			} catch ( AWPCP_Exception $e ) {
				$themessagetoprint = $e->getMessage();
			}
		}
		elseif ($aeaction == 'delete')
		{
			if (isset($_REQUEST['category_name']) && !empty($_REQUEST['category_name']))
			{
				$category_name=clean_field($_REQUEST['category_name']);
			}
			if (isset($_REQUEST['category_parent_id']) && !empty($_REQUEST['category_parent_id']))
			{
				$category_parent_id=clean_field($_REQUEST['category_parent_id']);
			}

			// Make sure this is not the default category. If it is the default category alert that the default category can only be renamed not deleted
			if ($category_id == 1)
			{
				$themessagetoprint=__("Sorry but you cannot delete the default category. The default category can only be renamed","AWPCP");
			} else {
				//Proceed with the delete instructions

				// Move any ads that the category contains if move-to category value is set and does not equal zero

				if ( isset($movetocat) && !empty($movetocat) && ($movetocat != 0) )
				{
					$movetocatparent=get_cat_parent_ID($movetocat);

					$query = 'UPDATE ' . AWPCP_TABLE_ADS . ' SET ad_category_id = %d ad_category_parent_id=%d ';
					$query.= 'WHERE ad_category_id = %d';
					$query = $wpdb->prepare( $query, $movetocat, $movetocatparent, $category_id );

					$wpdb->query( $query );

					// Must also relocate ads where the main category was a child of the category being deleted
					$query = 'UPDATE ' . AWPCP_TABLE_ADS . ' SET ad_category_parent_id = %d WHERE ad_category_parent_id = %d';
					$query = $wpdb->prepare( $query, $movetocat, $category_id );

					$wpdb->query( $query );

					// Must also relocate any children categories to the the move-to-cat
					$query = 'UPDATE ' . AWPCP_TABLE_CATEGORIES . ' SET category_parent_id = %d WHERE category_parent_id = %d';
					$wpdb->prepare( $query, $movetocat, $category_id );

					$wpdb->query( $query );
				}


				// Else if the move-to value is zero move the ads to the parent category if category is a child or the default category if
				// category is not a child

				elseif ( !isset($movetocat) || empty($movetocat) || ($movetocat == 0) )
				{

					// If the category has a parent move the ads to the parent otherwise move the ads to the default

					if ( category_is_child($category_id) )
					{

						$movetocat=get_cat_parent_ID($category_id);
					}
					else
					{
						$movetocat=1;
					}

					$movetocatparent=get_cat_parent_ID($movetocat);

					// Adjust any ads transferred from the main category
					$query="UPDATE ".$tbl_ads." SET ad_category_id='$movetocat', ad_category_parent_id='$movetocatparent' WHERE ad_category_id='$category_id'";
					$wpdb->query( $query );

					// Must also relocate any children categories to the the move-to-cat
					$query="UPDATE ".$tbl_ad_categories." SET category_parent_id='$movetocat' WHERE category_parent_id='$category_id'";
					$wpdb->query( $query );

					// Adjust  any ads transferred from children categories
					$query="UPDATE ".$tbl_ads." SET ad_category_parent_id='$movetocat' WHERE ad_category_parent_id='$category_id'";
					$wpdb->query( $query );
				}

				$query = "DELETE FROM  " . AWPCP_TABLE_CATEGORIES . " WHERE category_id='$category_id'";
				$wpdb->query( $query );

				do_action( 'awpcp-category-deleted', $category_id );

				$themessagetoprint=__("The category has been deleted","AWPCP");
			}
		}
		elseif ($aeaction == 'edit')
		{
			$category = AWPCP_Category::find_by_id( $category_id );
			$category->name = clean_field( awpcp_request_param( 'category_name' ) );
			$category->parent = intval( clean_field( awpcp_request_param( 'category_parent_id' ) ) );
			$category->order = intval( awpcp_request_param( 'category_order', 0 ) );

			try {
				awpcp_categories_collection()->save( $category );
				$themessagetoprint = __( 'Your category changes have been saved.', 'AWPCP' );
			} catch ( AWPCP_Exception $e ) {
				$themessagetoprint = $e->getMessage();
			}
		}
		else
		{
			$themessagetoprint=__("No changes made to categories.","AWPCP");
		}

		$message="<div style=\"background-color: rgb(255, 251, 204);\" id=\"message\" class=\"updated fade\">$themessagetoprint</div>";
		$clearform=1;
	}

	// Move multiple categories

	if ( isset($_REQUEST['movemultiplecategories']) && !empty($_REQUEST['movemultiplecategories']) )
	{
		$tbl_ad_categories = $wpdb->prefix . "awpcp_categories";
		$tbl_ads = $wpdb->prefix . "awpcp_ads";

		// First get the array of categories to be deleted
		$categoriestomove=clean_field($_REQUEST['category_to_delete_or_move']);

		// Next get the value for where the admin wants to move the ads
		if ( isset($_REQUEST['moveadstocategory']) && !empty($_REQUEST['moveadstocategory'])  && ($_REQUEST['moveadstocategory'] != 0) )
		{
			$moveadstocategory=clean_field($_REQUEST['moveadstocategory']);

			// Next loop through the categories and move them to the new category

			foreach($categoriestomove as $cattomove)
			{

				if ($cattomove != $moveadstocategory)
				{
					// First update all the ads in the category to take on the new parent ID
					$query = "UPDATE " . AWPCP_TABLE_ADS . " SET ad_category_parent_id='$moveadstocategory' WHERE ad_category_id='$cattomove'";
					$wpdb->query( $query );

					$query = "UPDATE " . AWPCP_TABLE_CATEGORIES . " SET category_parent_id='$moveadstocategory' WHERE category_id='$cattomove'";
					$wpdb->query( $query );
				}

			}

			$themessagetoprint=__("With the exception of any category that was being moved to itself, the categories have been moved","AWPCP");
		}
		else
		{
			$themessagetoprint=__("The categories have not been moved because you did not indicate where you want the categories to be moved to","AWPCP");
		}

		$message="<div style=\"background-color: rgb(255, 251, 204);\" id=\"message\" class=\"updated fade\">$themessagetoprint</div>";
	}

	// Delete multiple categories
	if ( isset($_REQUEST['deletemultiplecategories']) && !empty($_REQUEST['deletemultiplecategories']) )
	{
		$tbl_ad_categories = $wpdb->prefix . "awpcp_categories";
		$tbl_ads = $wpdb->prefix . "awpcp_ads";

		// First get the array of categories to be deleted
		$categoriestodelete=clean_field($_REQUEST['category_to_delete_or_move']);

		// Next get the value of move/delete ads
		if ( isset($_REQUEST['movedeleteads']) && !empty($_REQUEST['movedeleteads']) )
		{
			$movedeleteads=clean_field($_REQUEST['movedeleteads']);
		}
		else
		{
			$movedeleteads=1;
		}

		// Next get the value for where the admin wants to move the ads
		if ( isset($_REQUEST['moveadstocategory']) && !empty($_REQUEST['moveadstocategory'])  && ($_REQUEST['moveadstocategory'] != 0) )
		{
			$moveadstocategory=clean_field($_REQUEST['moveadstocategory']);
		}
		else
		{
			$moveadstocategory=1;
		}

		// Next make sure there is a default category with an ID of 1 because any ads that exist in the
		// categories will need to be moved to a default category if admin has checked move ads but
		// has not selected a move to category

		if ( ($moveadstocategory == 1) && (!(defaultcatexists($defid=1))) )
		{
			createdefaultcategory($idtomake=1,$titletocallit='Untitled');
		}

		// Next loop through the categories and move all their ads
		foreach($categoriestodelete as $cattodel)
		{
			// Make sure this is not the default category which cannot be deleted
			if ($cattodel != 1)
			{
				// If admin has instructed moving ads move the ads
				if ($movedeleteads == 1)
				{
					// Now move the ads if any
					$movetocat=$moveadstocategory;
					$movetocatparent=get_cat_parent_ID($movetocat);

					// Move the ads in the category main
					$query = "UPDATE " . AWPCP_TABLE_ADS . " SET ad_category_id='$movetocat',ad_category_parent_id='$movetocatparent' WHERE ad_category_id='$cattodel'";
					$wpdb->query( $query );

					// Must also relocate ads where the main category was a child of the category being deleted
					$query = "UPDATE " . AWPCP_TABLE_ADS . " SET ad_category_parent_id='$movetocat' WHERE ad_category_parent_id='$cattodel'";
					$wpdb->query( $query );

					// Must also relocate any children categories that do not exist in the categories to delete loop to the the move-to-cat
					$query = "UPDATE " . AWPCP_TABLE_CATEGORIES . " SET category_parent_id='$movetocat' WHERE category_parent_id='$cattodel' AND category_id NOT IN (".implode(',',$categoriestodelete).")";

					$wpdb->query( $query );
				}
				elseif ($movedeleteads == 2)
				{
					$movetocat=$moveadstocategory;

					// If the category has children move the ads in the child categories to the default category

					if ( category_has_children($cattodel) )
					{
						//  Relocate the ads ads in any children categories of the category being deleted
						$query = "UPDATE " . AWPCP_TABLE_ADS . " SET ad_category_parent_id='$movetocat' WHERE ad_category_parent_id='$cattodel'";
						$wpdb->query( $query );

						// Relocate any children categories that exist under the category being deleted
						$query = "UPDATE " . AWPCP_TABLE_CATEGORIES . " SET category_parent_id='$movetocat' WHERE category_parent_id='$cattodel'";
						$wpdb->query( $query );
					}

					// Now delete the ads because the admin has checked Delete ads if any
					massdeleteadsfromcategory($cattodel);
				}

				// Now delete the categories
				$query = "DELETE FROM  " . AWPCP_TABLE_CATEGORIES . " WHERE category_id='$cattodel'";
				$wpdb->query( $query );

				$themessagetoprint=__("The categories have been deleted","AWPCP");
			}

		}

		$message="<div style=\"background-color: rgb(255, 251, 204);\" id=\"message\" class=\"updated fade\">$themessagetoprint</div>";
	}
}



/**
 * Calls awpcp-admin-sidebar filter to output Admin panel sidebar.
 *
 * To remove Admin panel sidebar remove the mentioned filter on init.
 *
 * XXX: this may belong to AdminPage class
 */
function awpcp_admin_sidebar($float='') {
	$html = apply_filters('awpcp-admin-sidebar', '', $float);
	return $html;
}

/**
 * XXX: this may belong to AdminPage class
 */
function awpcp_admin_sidebar_output($html, $float) {
	global $awpcp;

	$modules = array(
		'premium' => array(
			'installed' => array(),
			'not-installed' => array(),
		),
		'other' => array(
			'installed' => array(),
			'not-installed' => array(),
		),
	);

	$premium_modules = $awpcp->get_premium_modules_information();
	foreach ($premium_modules as $module) {
		if ($module['installed']) {
			$modules['premium']['installed'][] = $module;
		} else {
			$modules['premium']['not-installed'][] = $module;
		}
	}

	$apath = get_option('siteurl') . '/wp-admin/images';
	$float = '' == $float ? 'float:right !important' : $float;
	$url = AWPCP_URL;

	ob_start();
		include(AWPCP_DIR . '/admin/templates/admin-sidebar.tpl.php');
		$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
