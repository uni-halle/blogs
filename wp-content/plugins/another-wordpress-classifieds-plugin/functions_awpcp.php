<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('You are not allowed to call this page directly.');
}

///
///
// Another Wordpress Classifieds Plugin: This file: functions_awpcp.php
///
//Debugger helper:
if(!function_exists('_log')){
	function _log( $message ) {
		if( WP_DEBUG === true ){
			if( is_array( $message ) || is_object( $message ) ){
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}



function sqlerrorhandler($ERROR, $QUERY, $PHPFILE, $LINE) {
	define("SQLQUERY", $QUERY);
	define("SQLMESSAGE", $ERROR);
	define("SQLERRORLINE", $LINE);
	define("SQLERRORFILE", $PHPFILE);
	trigger_error("(SQL) $ERROR", E_USER_ERROR);
}

//Error handler installed in main awpcp.php file, after this file is included.
function add_slashes_recursive( $variable ) {
	if (is_string($variable)) {
		return addslashes($variable);
	} elseif (is_array($variable)) {
		foreach($variable as $i => $value) {
			$variable[$i] = add_slashes_recursive($value);
		}
	}

	return $variable ;
}

/**
 * @deprecated Use WP's stripslashes_deep()
 */
function strip_slashes_recursive( $variable )
{
	if ( is_string( $variable ) )
	return stripslashes( $variable ) ;
	if ( is_array( $variable ) )
	foreach( $variable as $i => $value )
	$variable[ $i ] = strip_slashes_recursive( $value ) ;

	return $variable ;
}

function string_contains_string_at_position($haystack, $needle, $pos = 0, $case=true) {
	if ($case) {
		$result = (strpos($haystack, $needle, 0) === $pos);
	} else {
		$result = (stripos($haystack, $needle, 0) === $pos);
	}
	return $result;
}

function string_starts_with($haystack, $needle, $case=true) {
	return string_contains_string_at_position($haystack, $needle, 0, $case);
}

function string_ends_with($haystack, $needle, $case=true) {
	return string_contains_string_at_position($haystack, $needle, (strlen($haystack) - strlen($needle)), $case);
}

// START FUNCTION: retrieve individual options from settings table
function get_awpcp_setting($column, $option) {
	global $wpdb;
	$tbl_ad_settings = $wpdb->prefix . "awpcp_adsettings";
	$myreturn=0;
	$tableexists=checkfortable($tbl_ad_settings);

	if($tableexists)
	{
		$query="SELECT ".$column." FROM  ".$tbl_ad_settings." WHERE config_option='$option'";
		$res = $wpdb->get_var($query);
		$myreturn = strip_slashes_recursive($res);
	}
	return $myreturn;
}

function get_awpcp_option($option, $default='', $reload=false) {
	return awpcp()->settings->get_option( $option, $default, $reload );
}

function get_awpcp_option_group_id($option) {
	return get_awpcp_setting('config_group_id', $option);
}

function get_awpcp_option_type($option) {
	return get_awpcp_setting('option_type', $option);
}

function get_awpcp_option_config_diz($option) {
	return get_awpcp_setting('config_diz', $option);
}

// START FUNCTION: Check if the user is an admin
function checkifisadmin() {
	return awpcp_current_user_is_admin() ? 1 : 0;
}

function awpcpistableempty($table){
	global $wpdb;

	$query = 'SELECT COUNT(*) FROM ' . $table;
	$results = $wpdb->get_var( $query );

	if ( $results !== false && intval( $results ) === 0 ) {
		return true;
	} else {
		return false;
	}
}

function awpcpisqueryempty($table, $where){
	global $wpdb;

	$query = 'SELECT COUNT(*) FROM ' . $table . ' ' . $where;
	$count = $wpdb->get_var( $query );

	if ( $count !== false && intval( $count ) === 0 ) {
		return true;
	} else {
		return false;
	}
}

function adtermsset(){
	global $wpdb;
	$myreturn = !awpcpistableempty(AWPCP_TABLE_ADFEES);
	return $myreturn;
}

function categoriesexist(){

	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$myreturn=!awpcpistableempty($tbl_categories);
	return $myreturn;
}
// END FUNCTION
function adtermidinuse($adterm_id)
{
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$myreturn=!awpcpisqueryempty($tbl_ads, " WHERE adterm_id='$adterm_id'");
	return $myreturn;
}

function countlistings($is_active) {
	global $wpdb;

	$query = 'SELECT COUNT(*) FROM ' . AWPCP_TABLE_ADS . ' WHERE disabled = %d';
	$query = $wpdb->prepare( $query, $is_active ? false : true );

	return $wpdb->get_var( $query );
}

function countcategories(){
	return AWPCP_Category::query( array( 'fields' => 'count' ) );
}

function countcategoriesparents() {
	$params = array(
		'fields' => 'count',
		'where' => 'category_parent_id = 0'
	);

	return AWPCP_Category::query( $params );
}

function countcategorieschildren(){
	$params = array(
		'fields' => 'count',
		'where' => 'category_parent_id != 0'
	);

	return AWPCP_Category::query( $params );
}

// this way if user paid for ad user continues to be allowed number of images paid for
// START FUNCTION: Check to see how many images an ad is currently using
function get_total_imagesuploaded($ad_id) {
	global $wpdb;

	$query = "SELECT count(*) FROM " . AWPCP_TABLE_MEDIA . " WHERE ad_id=%d";
	$images = absint( $wpdb->get_var( $wpdb->prepare( $query, $ad_id ) ) );

	return $images;
}
// END FUNCTION



function awpcp_get_term_duration($adtermid) {
	global $wpdb;

	$query = 'SELECT rec_period, rec_increment FROM ' . AWPCP_TABLE_ADFEES . ' ';
	$query.= 'WHERE adterm_id = %d';

	$term = $wpdb->get_row($wpdb->prepare($query, $adtermid));

	if (is_null($term)) {
		return array();
	}

	$duration = $term->rec_period;
	$increment = $term->rec_increment;

	// a value of zero or less means "never expires" or in AWPCP
	// terms: it will expire in 10 years
	if ($duration <= 0) {
		if ($increment == 'D') {
			$duration = 3650;
		} else if ($increment == 'W') {
			$duration = 520;
		} else if ($increment == 'M') {
			$duration = 120;
		} else if ($increment == 'Y') {
			$duration = 10;
		}
	}

	return array('duration' => $duration, 'increment' => $increment);
}

function get_adpostername($adid) {
	return get_adfield_by_pk('ad_contact_name', $adid);
}

function get_adposteremail($adid) {
	return get_adfield_by_pk('ad_contact_email', $adid);
}

function get_adstartdate($adid) {
	return get_adfield_by_pk('ad_startdate', $adid);
}
// END FUNCTION
// START FUNCTION: Get the number of times an ad has been viewed
function get_numtimesadviewd($adid)
{
	return get_adfield_by_pk('ad_views', $adid);
}
// END FUNCTION: Get the number of times an ad has been viewed
// START FUNCTION: Get the ad title based on having the ad ID
function get_adtitle($adid) {
	return strip_slashes_recursive(get_adfield_by_pk('ad_title', $adid));
}

// START FUNCTION: Create list of top level categories for admin category management
function get_categorynameid($cat_id = 0,$cat_parent_id= 0,$exclude)
{

	global $wpdb;
	$optionitem='';
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	if(isset($exclude) && !empty($exclude))
	{
		$excludequery="AND category_id !='$exclude'";
	}else{$excludequery='';}

	$catnid=$wpdb->get_results("select category_id as cat_ID, category_parent_id as cat_parent_ID, category_name as cat_name from " . AWPCP_TABLE_CATEGORIES . " WHERE category_parent_id=0 AND category_name <> '' $excludequery");

	foreach($catnid as $categories)
	{

		if($categories->cat_ID == $cat_parent_id)
		{
			$optionitem .= "<option selected='selected' value='$categories->cat_ID'>$categories->cat_name</option>";
		}
		else
		{
			$optionitem .= "<option value='$categories->cat_ID'>$categories->cat_name</option>";
		}

	}

	return $optionitem;
}
// END FUNCTION: create list of top level categories for admin category management

// START FUNCTION: Create the list with both parent and child categories selection for ad post form
function get_categorynameidall($cat_id = 0) {
	global $wpdb;

	$optionitem='';

	// Start with the main categories
	$query = "SELECT category_id,category_name FROM " . AWPCP_TABLE_CATEGORIES . " ";
	$query.= "WHERE category_parent_id=0 AND category_name <> '' ";
	$query.= "ORDER BY category_order, category_name ASC";

	$query_results = $wpdb->get_results( $query, ARRAY_N );

	foreach ( $query_results as $rsrow ) {
		$cat_ID = $rsrow[0];
		$cat_name = stripslashes(stripslashes($rsrow[1]));

		$opstyle = "class=\"dropdownparentcategory\"";

		if($cat_ID == $cat_id) {
			$maincatoptionitem = "<option $opstyle selected='selected' value='$cat_ID'>$cat_name</option>";
		} else {
			$maincatoptionitem = "<option $opstyle value='$cat_ID'>$cat_name</option>";
		}

		$optionitem.="$maincatoptionitem";

		// While still looping through main categories get any sub categories of the main category

		$maincatid = $cat_ID;

		$query = "SELECT category_id,category_name FROM " . AWPCP_TABLE_CATEGORIES . " ";
		$query.= "WHERE category_parent_id=%d ";
		$query.= "ORDER BY category_order, category_name ASC";

		$query = $wpdb->prepare( $query, $maincatid );

		$sub_query_results = $wpdb->get_results( $query, ARRAY_N );

		foreach ( $sub_query_results as $rsrow2) {
			$subcat_ID = $rsrow2[0];
			$subcat_name = stripslashes(stripslashes($rsrow2[1]));

			if($subcat_ID == $cat_id) {
				$subcatoptionitem = "<option selected='selected' value='$subcat_ID'>- $subcat_name</option>";
			} else {
				$subcatoptionitem = "<option  value='$subcat_ID'>- $subcat_name</option>";
			}

			$optionitem.="$subcatoptionitem";
		}
	}

	return $optionitem;
}

// END FUNCTION: create drop down list of categories for ad post form
// START FUNCTION: Retrieve the category name
function get_adcatname($cat_ID) {
	global $wpdb;

	$cname='';
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	if(isset($cat_ID) && (!empty($cat_ID))){
		$query="SELECT category_name from " . AWPCP_TABLE_CATEGORIES . " WHERE category_id='$cat_ID'";
		$cname = $wpdb->get_results($query, ARRAY_A);
		foreach($cname as $cn) {
			$cname = $cn['category_name'];
		}
	}

	return empty($cname) ? '' : stripslashes_deep($cname);
}

function get_adcatorder($cat_ID){
	global $wpdb;
	$corder='';
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	if(isset($cat_ID) && (!empty($cat_ID))){
		$query="SELECT category_order from " . AWPCP_TABLE_CATEGORIES . " WHERE category_id='$cat_ID'";
		$corder = $wpdb->get_var($query);
	}
	return $corder;
}
//Function to retrieve ad location data:
function get_adfield_by_pk($field, $adid) {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$thevalue='';
	if(isset($adid) && (!empty($adid))){
		$query="SELECT ".$field." from ".$tbl_ads." WHERE ad_id='$adid'";
		$thevalue = $wpdb->get_var($query);
	}
	return $thevalue;
}


function get_adcategory($adid){
	return get_adfield_by_pk('ad_category_id', $adid);
}

function get_adparentcatname($cat_ID){
	global $wpdb;

	if ( $cat_ID == 0 ) {
		return __( 'Top Level Category', 'AWPCP' );
	}

	$query = 'SELECT category_name FROM ' . AWPCP_TABLE_CATEGORIES . ' WHERE category_id = %d';
	$query = $wpdb->prepare( $query, $cat_ID );

	return $wpdb->get_var( $query );
}

function get_cat_parent_ID($cat_ID){
	global $wpdb;

	$query = 'SELECT category_parent_id FROM ' . AWPCP_TABLE_CATEGORIES . ' WHERE category_id = %d';
	$query = $wpdb->prepare( $query, $cat_ID );

	return $wpdb->get_var( $query );
}

function ads_exist() {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$myreturn=!awpcpistableempty($tbl_ads);
	return $myreturn;
}
// END FUNCTION: check if any ads exist in the system
// START FUNCTION: Check if there are any ads in a specified category
function ads_exist_cat($catid) {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$myreturn=!awpcpisqueryempty($tbl_ads, " WHERE ad_category_id='$catid' OR ad_category_parent_id='$catid'");
	return $myreturn;
}
// END FUNCTION: check if a category has ads
function category_has_children($catid) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";
	$myreturn=!awpcpisqueryempty($tbl_categories, " WHERE category_parent_id='$catid'");
	return $myreturn;
}

function category_is_child($catid) {
	global $wpdb;

	$query = 'SELECT category_parent_id FROM ' . AWPCP_TABLE_CATEGORIES . ' WHERE category_id = %d';
	$query = $wpdb->prepare( $query, $catid );

	$parent_id = $wpdb->get_var( $query );

	if ( $parent_id !== false && $parent_id != 0 ) {
		return true;
	} else {
		return false;
	}
}

// TODO: cache the results of this function
function total_ads_in_cat($catid) {
    global $wpdb, $hasregionsmodule;

    $totaladsincat = '';

    // never allow Unpaid, Unverified or Disabled Ads
    $conditions[] = "payment_status != 'Unpaid'";
    $conditions[] = 'verified = 1';
    $conditions[] = 'disabled = 0';

    if( ( get_awpcp_option( 'enable-ads-pending-payment' ) == 0 ) && ( get_awpcp_option( 'freepay' ) == 1 ) ) {
        $conditions[] = "payment_status != 'Pending'";
    }

    // TODO: ideally there would be a function to get all visible Ads,
    // and modules, like Regions, would use hooks to include their own
    // conditions.
    if ($hasregionsmodule == 1) {
        if (isset($_SESSION['theactiveregionid'])) {
            $theactiveregionid = $_SESSION['theactiveregionid'];

            if (function_exists('awpcp_regions_api')) {
            	$regions = awpcp_regions_api();
            	$conditions[] = $regions->sql_where( $theactiveregionid );
            }
        }
    }

    $conditions[] = "(ad_category_id='$catid' OR ad_category_parent_id='$catid')";

    // TODO: at some point we should start using the Category model.
    $query = 'SELECT count(*) FROM ' . AWPCP_TABLE_ADS;
    $query = sprintf( '%s WHERE %s', $query, implode( ' AND ', $conditions ) );

    return $wpdb->get_var( $query );
}

//Function to replace addslashes_mq, which is causing major grief.  Stripping of undesireable characters now done
// through above strip_slashes_recursive_gpc.
function clean_field($foo) {
	return add_slashes_recursive($foo);
}


// END FUNCTION: replace underscores with dashes for search engine friendly urls
// START FUNCTION: get the page ID when the page name is known
// Get the id of a page by its name
function awpcp_get_page_id($name) {
	global $wpdb;
	if (!empty($name)) {
		$sql = "SELECT ID FROM {$wpdb->posts} WHERE post_name = '$name'";
		$id = $wpdb->get_var($sql);
		return $id;
	}
	return 0;
}

/**
 * Returns the ID of WP Page associated to a page-name setting.
 *
 * TOOD: get all page entries in one query an cache the result during the request
 *
 * @param $refname the name of the setting that holds the name of the page
 */
function awpcp_get_page_id_by_ref($refname) {
	global $wpdb;
	$query = 'SELECT page, id FROM ' . AWPCP_TABLE_PAGES . ' WHERE page = %s';
	$page = $wpdb->get_results($wpdb->prepare($query, $refname));
	if (!empty($page)) {
		return array_shift($page)->id;
	} else {
		return false;
	}
}

/**
 * Return the IDs of WP pages associated with AWPCP pages.
 *
 * @return array Array of Page IDs
 */
function awpcp_get_page_ids_by_ref($refnames) {
	global $wpdb;

	$refnames = (array) $refnames;
	$query = 'SELECT id FROM ' . AWPCP_TABLE_PAGES . ' ';

	if (!empty($refnames))
		$query = sprintf("%s WHERE page IN ('%s')", $query, join("','", $refnames));

	return $wpdb->get_col($query);
}

/**
 * Setup the structure of the URLs based on if permalinks are on and SEO urls
 * are turned on.
 *
 * Actually it doesn't take into account if SEO urls are on. It also takes an
 * argument that is expected to have the same value ALWAYS.
 *
 * Is easier to get the URL for a given page using:
 * get_permalink(awpcp_get_page_id(sanitize-title($human-readable-pagename)));
 * or
 * get_permalink(awpcp_get_page_id_by_ref(<setting that stores that pages name>))
 */
function setup_url_structure($awpcpthepagename) {
	$quers = '';
	$theblogurl = get_bloginfo('url');
	$permastruc = get_option('permalink_structure');

	if(strstr($permastruc,'index.php')) {
		$theblogurl.="/index.php";
	}

	if(isset($permastruc) && !empty($permastruc)) {
		$quers="$theblogurl/$awpcpthepagename";
	} else {
		$quers="$theblogurl";
	}

	return $quers;
}

function url_showad($ad_id) {
	$ad = AWPCP_Ad::find_by_id( $ad_id );

	if ( is_null( $ad ) ) return false;

	$modtitle = sanitize_title( $ad->get_title() );
	$seoFriendlyUrls = get_awpcp_option('seofriendlyurls');
	$permastruc = get_option('permalink_structure');

	$awpcp_showad_pageid = awpcp_get_page_id_by_ref('show-ads-page-name');
	$base_url = get_permalink($awpcp_showad_pageid);
	$url = false;

	$params = array('id' => $ad_id);

	if($seoFriendlyUrls && isset($permastruc) && !empty($permastruc)) {
		$url = sprintf('%s/%s/%s', trim($base_url, '/'), $ad_id, $modtitle);

		$region = $ad->get_first_region();

		$parts = array();
		if( get_awpcp_option( 'showcityinpagetitle' ) && $region ) {
			$parts[] = sanitize_title( awpcp_array_data( 'city', '', $region ) );
		}
		if( get_awpcp_option( 'showstateinpagetitle' ) && $region ) {
			$parts[] = sanitize_title( awpcp_array_data( 'state', '', $region ) );
		}
		if( get_awpcp_option( 'showcountryinpagetitle' ) && $region ) {
			$parts[] = sanitize_title( awpcp_array_data( 'country', '', $region ) );
		}
		if( get_awpcp_option( 'showcountyvillageinpagetitle' ) && $region ) {
			$parts[] = sanitize_title( awpcp_array_data( 'county', '', $region ) );
		}
		if( get_awpcp_option('showcategoryinpagetitle') ) {
			$awpcp_ad_category_id = $ad->ad_category_id;
			$parts[] = sanitize_title(get_adcatname($awpcp_ad_category_id));
		}

		// always append a slash (RSS module issue)
		$url = sprintf( "%s%s", trailingslashit( $url ), join( '/', array_filter( $parts ) ) );
		$url = user_trailingslashit($url);
	} else {
		$base_url = user_trailingslashit($base_url);
		$url = add_query_arg($params, $base_url);
	}

	return $url;
}

function url_browsecategory($cat_id) {
	$permalinks = get_option('permalink_structure');
	$base_url = awpcp_get_page_url('browse-categories-page-name');

	$cat_name = get_adcatname($cat_id);
	$cat_slug = sanitize_title($cat_name);

	if (get_awpcp_option('seofriendlyurls')) {
		if (!empty($permalinks)) {
			$url_browsecats = sprintf('%s/%s/%s', trim($base_url, '/'), $cat_id, $cat_slug);
		} else {
			$params = array('a' => 'browsecat', 'category_id' => $cat_id);
			$url_browsecats = add_query_arg($params, $base_url);
		}
	} else {
		if (!empty($permalinks)) {
			$params = array('category_id' => "$cat_id/$cat_slug");
		} else {
			$params = array('a' => 'browsecat', 'category_id' => $cat_id);
		}
		$url_browsecats = add_query_arg($params, $base_url);
	}

	return user_trailingslashit($url_browsecats);
}

function url_placead() {
	return user_trailingslashit(awpcp_get_page_url('place-ad-page-name'));
}

/**
 * @deprecated deprecated since 2.0.6.
 */
function url_classifiedspage() {
	return awpcp_get_main_page_url();
}

function url_searchads() {
	return user_trailingslashit(awpcp_get_page_url('search-ads-page-name'));
}

function url_editad() {
	return user_trailingslashit(awpcp_get_page_url('edit-ad-page-name'));
}

/**
 * @deprecated since 2.0.7
 */ 
function checkfortable($table) {
	return awpcp_table_exists($table);
}

function add_config_group_id($cvalue,$coption) {
	global $wpdb;

	$query = 'UPDATE ' . AWPCP_TABLE_ADSETTINGS . ' SET config_group_id = %d WHERE config_option = %s';
	$query = $wpdb->prepare( $query, $cvalue, $coption );

	$wpdb->query( $query );
}

/**
 * Returns the current name of the AWPCP main page.
 */
function get_currentpagename() {
	return get_awpcp_option('main-page-name');
}

function field_exists($field) {
	global $wpdb;

	if ( ! checkfortable( AWPCP_TABLE_ADSETTINGS ) ) {
		return false;
	}

	$query = 'SELECT config_value FROM ' . AWPCP_TABLE_ADSETTINGS . ' WHERE config_option = %s';
	$query = $wpdb->prepare( $query, $field );

	$value = $wpdb->get_var( $config_value );

	if ( $value === false || is_null( $value ) ) {
		return false;
	} else {
		return true;
	}
}

function isValidURL($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

/**
 * @since 3.0.2
 */
function awpcp_is_valid_email_address($email) {
	return filter_var( $email, FILTER_VALIDATE_EMAIL ) !== false;
}

/**
 * @deprecated since 3.0.2. @see awpcp_is_valid_email_address()
 */
function isValidEmailAddress($email) {
	return awpcp_is_valid_email_address( $email );
}

function defaultcatexists($defid) {
	global $wpdb;

	$query = 'SELECT COUNT(*) FROM ' . AWPCP_TABLE_CATEGORIES . ' WHERE category_id = %d';
	$query = $wpdb->prepare( $query, $defid );

	$count = $wpdb->get_var( $query );

	if ( $count !== false && $count > 0 ) {
		return true;
	} else {
		return false;
	}

}

// START FUNCTION: function to create a default category with an ID of  1 in the event a default category with ID 1 does not exist
function createdefaultcategory($idtomake,$titletocallit) {
	global $wpdb;

	$wpdb->insert( AWPCP_TABLE_CATEGORIES, array( 'category_name' => $titletocallit, 'category_parent_id' => 0 ) );

	$query = 'UPDATE ' . AWPCP_TABLE_CATEGORIES . ' SET category_id = 1 WHERE category_id = %d';
	$query = $wpdb->prepare( $query, $wpdb->insert_id );

	$wpdb->query( $query );
}
// END FUNCTION: create default category


//////////////////////
// START FUNCTION: function to delete multiple ads at once used when admin deletes a category that contains ads but does not move the ads to a new category
//////////////////////
function massdeleteadsfromcategory($catid) {
	$ads = AWPCP_Ad::find_by_category_id($catid);
	foreach ($ads as $ad) {
		$ad->delete();
	}
}


// END FUNCTION: sidebar widget
// START FUNCTION: make sure there's not more than one page with the name of the classifieds page
function checkforduplicate($cpagename_awpcp) {
	global $wpdb;

	$awpcppagename = sanitize_title( $cpagename_awpcp );

	$query = "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s";
	$query = $wpdb->prepare( $query, $awpcppagename, 'post' );

	$post_ids = $wpdb->get_col( $query );

	if ( $post_ids !== false ) {
		return count( $post_ids );
	} else {
		return '';
	}
}

function create_ad_postedby_list($name) {
	global $wpdb;

	$output = '';
	$query = 'SELECT DISTINCT ad_contact_name FROM ' . AWPCP_TABLE_ADS . ' WHERE disabled = 0';

	$results = $wpdb->get_col( $query );

	foreach ( $results as $contact_name ) {
		if ( strcmp( $contact_name, $name ) === 0 ) {
			$output .= "<option value=\"$contact_name\" selected=\"selected\">$contact_name</option>";
		} else {
			$output .= "<option value=\"$contact_name\">$contact_name</option>";
		}
	}

	return $output;
}

function awpcp_strip_html_tags( $text )
{
	// Remove invisible content
	$text = preg_replace(
	array(
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
	// Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
	),
	array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
	),
	$text );
	return strip_tags( $text );
}
// END FUNCTION


// Override the SMTP settings built into WP if the admin has enabled that feature 
// add_action('phpmailer_init','awpcp_phpmailer_init_smtp');
function awpcp_phpmailer_init_smtp( $phpmailer ) { 

	// smtp not enabled? 
	$enabled = get_awpcp_option('usesmtp');
	if ( !$enabled || 0 == $enabled ) return; 

	$host = get_awpcp_option('smtphost');
	$port = get_awpcp_option('smtpport');
	$username = get_awpcp_option('smtpusername');
	$password = get_awpcp_option('smtppassword');

	// host and port not set? gotta have both. 
	if ( '' == trim( $hostname ) || '' == trim( $port ) )
	    return;

	// still got defaults set? can't use those. 
	if ( 'mail.example.com' == trim( $hostname ) ) return;
	if ( 'smtp_username' == trim( $username ) ) return;

	$phpmailer->Mailer = 'smtp';
	$phpmailer->Host = $host;
	$phpmailer->Port = $port;

	// If there's a username and password then assume SMTP Auth is necessary and set the vars: 
	if ( '' != trim( $username )  && '' != trim( $password ) ) { 
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = $username;
		$phpmailer->Password = $password;
	}

	// that's it! 
}


function awpcp_process_mail($senderemail='', $receiveremail='',  $subject='', 
							$body='', $sendername='', $replytoemail='', $html=false) 
{
	$headers =	"MIME-Version: 1.0\n" .
	"From: $sendername <$senderemail>\n" .
	"Reply-To: $replytoemail\n";

	if ($html) {
		$headers .= "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
	} else {
		$headers .= "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
	}

	$subject = $subject;
	$message = "$body\n\n" . awpcp_format_email_sent_datetime() . "\n\n";

	_log("Processing email");

	if (wp_mail($receiveremail, $subject, $message, $headers )) {
		_log("Sent via WP");
		return 1;

	} elseif (awpcp_send_email($senderemail, $receiveremail, $subject, $body,true)) {
		_log("Sent via send_email");
		return 1;

	} elseif (@mail($receiveremail, $subject, $body, $headers)) {
		_log("Sent via mail");
		return 1;

	} else {
	    _log("SMTP not configured properly, all attempts failed");
	    return 0;
	}
}

function awpcp_format_email_sent_datetime() {
	$time = date_i18n( awpcp_get_datetime_format(), current_time( 'timestamp' ) );
	return sprintf( __( 'Email sent %s.', 'AWPCP' ), $time );
}

// make sure the IP isn't a reserved IP address
function awpcp_validip($ip) {

	if (!empty($ip) && ip2long($ip)!=-1) {

		$reserved_ips = array (
		array('0.0.0.0','2.255.255.255'),
		array('10.0.0.0','10.255.255.255'),
		array('127.0.0.0','127.255.255.255'),
		array('169.254.0.0','169.254.255.255'),
		array('172.16.0.0','172.31.255.255'),
		array('192.0.2.0','192.0.2.255'),
		array('192.168.0.0','192.168.255.255'),
		array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r) {
			$min = ip2long($r[0]);
			$max = ip2long($r[1]);
			if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max))
			return false;
		}

		return true;

	} else {

		return false;

	}
}

// retrieve the ad poster's IP if possible
function awpcp_getip() {
	if ( awpcp_validip(awpcp_array_data("HTTP_CLIENT_IP", '', $_SERVER)) ) {
		return $_SERVER["HTTP_CLIENT_IP"];
	}

	foreach ( explode(",", awpcp_array_data("HTTP_X_FORWARDED_FOR", '', $_SERVER)) as $ip ) {
		if ( awpcp_validip(trim($ip) ) ) {
			return $ip;
		}
	}

	if (awpcp_validip(awpcp_array_data("HTTP_X_FORWARDED", '', $_SERVER))) {
		return $_SERVER["HTTP_X_FORWARDED"];

	} elseif (awpcp_validip(awpcp_array_data('HTTP_FORWARDED_FOR', '', $_SERVER))) {
		return $_SERVER["HTTP_FORWARDED_FOR"];

	} elseif (awpcp_validip(awpcp_array_data("HTTP_FORWARDED", '', $_SERVER))) {
		return $_SERVER["HTTP_FORWARDED"];

	} else {
		return awpcp_array_data("REMOTE_ADDR", '', $_SERVER);
	}
}

function awpcp_get_ad_share_info($id) {
	global $wpdb;

	$ad = AWPCP_Ad::find_by_id($id);
	$info = array();

	if (is_null($ad)) {
		return null;
	}

	$info['url'] = url_showad($id);
	$info['title'] = stripslashes($ad->ad_title);
	$info['description'] = strip_tags(stripslashes($ad->ad_details));
	$info['description'] = str_replace("\n", " ", $info['description']);

	if ( awpcp_utf8_strlen( $info['description'] ) > 300 ) {
		$info['description'] = awpcp_utf8_substr( $info['description'], 0, 300 ) . '...';
	}

	$info['images'] = array();

	$info['published-time'] = awpcp_datetime( 'Y-m-d', $ad->ad_postdate );
	$info['modified-time'] = awpcp_datetime( 'Y-m-d', $ad->ad_last_updated );

	$images = awpcp_media_api()->find_by_ad_id( $ad->ad_id, array( 'enabled' => true ) );

	foreach ( $images as $image ) {
		$info[ 'images' ][] = $image->get_url( 'large' );
	}

	return $info;
}
