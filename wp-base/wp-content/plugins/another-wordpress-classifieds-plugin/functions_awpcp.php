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
	// debugp($ERROR, $QUERY, $PHPFILE, $LINE);
	trigger_error("(SQL) $ERROR", E_USER_ERROR);
}

/**
 * Wrapper for mysql_query which triggers an error if the query fails.
 */
function awpcp_query($query, $LINE) {
	global $wpdb;
	//Query, and if failure happens, emit an appropriate error
	$res = array();
	if (!($res=mysql_query($query))) {
		sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], $LINE);
	}
	return $res;
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

function awpcp_submit_spam($ad_id) {
	if (function_exists('akismet_init')) {
		$wpcom_api_key = get_option('wordpress_api_key');

		if (!empty($wpcom_api_key)) {
			require_once(ABSPATH . WPINC . '/pluggable.php');
			_log("Now submitting ad " . $ad_id . " as spam");
			global $wpdb, $akismet_api_host, $akismet_api_port, $current_user, $current_site;
			$ad_id = (int) $ad_id;
			$tbl_ads = $wpdb->prefix . "awpcp_ads";
			$query = "SELECT * FROM " . $tbl_ads . " WHERE ad_id = ".$ad_id;
			$res = awpcp_query($query, __LINE__);
			if ($ad_record=mysql_fetch_array($res)) {
				if ( $ad_record['disabled'] == 1 ) {
					_log("Ad " . $ad_id . " already marked as spam");
					return;
				}
				$content = array();
				_log("Ad " . $ad_id . " constructing Akismet call");
				//Construct an Akismet-like query:
				$content['user_ip'] = $ad_record['posterip'];
				$content['comment_author'] = $ad_record['ad_contact_name'];
				$content['comment_author_email'] = $ad_record['ad_contact_email'];
				$content['comment_author_url'] = $ad_record['websiteurl'];
				$content['comment_content'] = $ad_record['ad_details'];
				$content['blog'] = get_option('home');
				$content['blog_lang'] = get_locale();
				$content['blog_charset'] = get_option('blog_charset');
				$content['permalink'] = '';
				get_currentuserinfo();
				if ( is_object($current_user) ) {
					$content['reporter'] = $current_user->user_login;
				}
				if ( is_object($current_site) ) {
					$content['site_domain'] = $current_site->domain;
				}
				$content['user_role'] = 'Editor'; // probably best to present the user with some level of authority
				$query_string = '';
				foreach ( $content as $key => $data ) {
					$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
				}
				_log("Ad " . $ad_id . " query: " . $query_string);
				$response = akismet_http_post($query_string, $akismet_api_host, "/1.1/submit-spam", $akismet_api_port);
				_log("Ad " . $ad_id . " spammed, Akismet said: ");
				foreach ($response as $key => $value) {
					_log($key." - ".$value."");
				}
			} else {
				_log("Ad " . $ad_id . " not found, cannot mark as spam");
			}
		} else {
			global $message;
			$message="<div style=\"background-color: #FF99CC;\" id=\"message\" class=\"updated fade\">";
			$message.=__("Please disable spam control on your AWPCP settings because you do not have Akismet properly configured (missing API key)","AWPCP");
			$message.="</div>";
		}
	} else {
		global $message;
		$message="<div style=\"background-color: #FF99CC;\" id=\"message\" class=\"updated fade\">";
		$message.=__("Please disable spam control on your AWPCP settings because you do not have Akismet installed","AWPCP");
		$message.="</div>";
	}
}

//Function to detect spammy posts.  Requires Akismet to be installed.
function awpcp_check_spam($name, $website, $email, $details) {
	$content = array();

	//Construct an Akismet-like query:
	$content['comment_type'] = 'comment';
	//$content['comment_author'] = $name; // don't send this, it reduces accuracy
	$content['comment_author_email'] = $email;
	//$content['comment_author_url'] = $website; // don't send this, it reduces accuracy
	$content['comment_content'] = $details;

	// innocent until proven guilty
	$isSpam = FALSE;

	if (function_exists('akismet_init')) {

		$wpcom_api_key = get_option('wordpress_api_key');

		if (!empty($wpcom_api_key)) {

			global $akismet_api_host, $akismet_api_port;

			// set remaining required values for akismet api
			$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', awpcp_getip() );
			$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$content['referrer'] = get_option('home'); // use site home page instead of $_SERVER['HTTP_REFERER']; seems to work better
			$content['blog'] = get_option('home');

			//if (empty($content['referrer'])) {
			//	$content['referrer'] = get_permalink();
			//}

			$queryString = '';

			foreach ($content as $key => $data) {
				if (!empty($data)) {
					$queryString .= $key . '=' . urlencode(stripslashes($data)) . '&';
				}
			}

			$response = akismet_http_post($queryString, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);

			if ($response[1] == 'true') {
				//update_option('akismet_spam_count', get_option('akismet_spam_count') + 1);
				$isSpam = TRUE;
			}

		} else {
			global $message;
			$message="<div style=\"background-color: #FF99CC;\" id=\"message\" class=\"updated fade\">";
			$message.=__("Please disable spam control on your AWPCP settings because you do not have Akismet properly configured (missing API key)","AWPCP");
			$message.="</div>";
		}
	} else {
		global $message;
		$message="<div style=\"background-color: #FF99CC;\" id=\"message\" class=\"updated fade\">";
		$message.=__("Please disable spam control on your AWPCP settings because you do not have Akismet installed","AWPCP");
		$message.="</div>";
	}

	// Akismet says it's not spam or Akismet disabled? Check using the blacklisted words configured in WP, if any:
	if ( !$isSpam)
	    $isSpam = wp_blacklist_check($name, $email, $website, $details, preg_replace( '/[^0-9., ]/', '', awpcp_getip() ), $_SERVER['HTTP_USER_AGENT']);

	_log("Ad spam check final answer: " . $isSpam);

	return $isSpam;
}

function awpcp_blacklist_check($author, $email, $url, $comment, $user_ip, $user_agent) {

	// hook similar the related WP hook, lets people develop their own ad scanning filters
        do_action('awpcp_blacklist_check', $author, $email, $url, $comment, $user_ip, $user_agent);

        $mod_keys = trim( get_option('blacklist_keys') );

        if ( '' == $mod_keys )
                return false; // If no blacklist words are set then there's nothing to do here

        $words = explode("\n", $mod_keys );

        foreach ( (array) $words as $word ) {

                $word = trim($word);

                // Skip empty lines
                if ( empty($word) ) { continue; }

                // Do some escaping magic so that '#' chars in the spam words don't break things:
                $word = preg_quote($word, '#');

                $pattern = "#$word#i";
                if (
                           preg_match($pattern, $author)
                        || preg_match($pattern, $email)
                        || preg_match($pattern, $url)
                        || preg_match($pattern, $comment)
                        || preg_match($pattern, $user_ip)
                        || preg_match($pattern, $user_agent)
                 )
                        return true;
        }
        return false;
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
	return AWPCP_Settings_API::instance()->get_option($option, $default, $reload);
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
// END FUNCTION
function awpcp_is_classifieds()
{
	global $post,$table_prefix;
	$awpcppageid=$post->ID;
	$classifiedspagecontent="[AWPCPCLASSIFIEDSUI]";

	$query="SELECT post_content FROM {$table_prefix}posts WHERE ID='$awpcppageid' AND post_type='page' AND post_status='publish'";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		list($thepostcontentvalue)=$rsrow;
	}

	$istheclassifiedspage= (strcasecmp($thepostcontentvalue, $classifiedspagecontent) == 0);
	return $istheclassifiedspage;

}


// START FUNCTION: Check if the user is an admin
function checkifisadmin() {
	return awpcp_current_user_is_admin() ? 1 : 0;
}


// END FUNCTION
function awpcpistableempty($table){
	global $wpdb;

	$myreturn=true;
	$query="SELECT count(*) FROM ".$table."";
	$res = awpcp_query($query, __LINE__);
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=false;
	}
	return $myreturn;
}

function awpcpisqueryempty($table, $where){
	global $wpdb;

	$myreturn=true;
	$query="SELECT count(*) FROM ".$table." ".$where;
	$res = awpcp_query($query, __LINE__);
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=false;
	}
	return $myreturn;
}
// START FUNCTION: Check if the admin has setup any listing fee options
function adtermsset(){
	global $wpdb;
	$myreturn = !awpcpistableempty(AWPCP_TABLE_ADFEES);
	return $myreturn;
}
// END FUNCTION

/**
 * Get the product ID for 2 Checkout.. or something like that.
 */
function get_2co_prodid($adterm_id) {
	global $wpdb;

	$query = "SELECT twoco_pid FROM " . AWPCP_TABLE_ADFEES . " WHERE adterm_id='$adterm_id'";
	$res = awpcp_query($query, __LINE__);

	$twoco_pid='';
	while ($rsrow=mysql_fetch_row($res)) {
		list($twoco_pid)=$rsrow;
	}

	return $twoco_pid;
}

// START FUNCTION: Check if the admin has setup some categories
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
// START FUNCTION: Count the total number of active ads in the  system
function countlistings($is_active) {

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$totallistings='';

	$query="SELECT count(*) FROM ".$tbl_ads." WHERE disabled='". !$is_active ."'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($totallistings)=$rsrow;
	}
	return $totallistings;
}
// END FUNCTION
// START FUNCTION: Count the total number of categories
function countcategories(){

	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$totalcategories='';

	$query="SELECT count(*) FROM ".$tbl_categories."";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($totalcategories)=$rsrow;
	}
	return $totalcategories;
}
// END FUNCTION
// START FUNCTION: Count parent categories
function countcategoriesparents(){

	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$totalparentcategories='';
	$query="SELECT count(*) FROM ".$tbl_categories." WHERE category_parent_id=0";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($totalparentcategories)=$rsrow;
	}
	return $totalparentcategories;
}
// END FUNCTION
// START FUNCTION: Count children categories
function countcategorieschildren(){

	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$totalchildrencategories='';
	$query="SELECT count(*) FROM ".$tbl_categories." WHERE category_parent_id!=0";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($totalchildrencategories)=$rsrow;
	}
	return $totalchildrencategories;
}
// END FUNCTION
// START FUNCTION: get number of images allowed per ad term id
function get_numimgsallowed($adtermid){
	global $wpdb;
	$tbl_ad_fees = $wpdb->prefix . "awpcp_adfees";
	$imagesallowed='';
	$query="SELECT imagesallowed FROM ".$tbl_ad_fees." WHERE adterm_id='$adtermid'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($imagesallowed)=$rsrow;
	}
	return $imagesallowed;
}
// END FUNCTION

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

// START FUNCTION: Get the total number of days an ad term last based on term ID value
function get_num_days_in_term($adtermid) {
	$numdaysinterm='';
	global $wpdb;
	$tbl_ad_fees = $wpdb->prefix . "awpcp_adfees";

	$query="SELECT rec_period from ".$tbl_ad_fees." WHERE adterm_id='$adtermid'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($numdaysinterm)=$rsrow;
	}

	if ('' == $numdaysinterm || 0 == $numdaysinterm) {
		$numdaysinterm = 3650; // 100 years, equivalent of never expires
	}

	return $numdaysinterm;
}
// END FUNCTION
// START FUNCTION: Get the id for the ad term based on having the ad ID
function get_adterm_id($adid) {
	return get_adfield_by_pk('adterm_id', $adid);
}
// END FUNCTION
// START FUNCTION: Get the ad term name for the ad term based on having the ad term ID
function get_adterm_name($adterm_id) {

	global $wpdb;
	$tbl_ad_fees = $wpdb->prefix . "awpcp_adfees";

	$adterm_name='';

	$query="SELECT adterm_name from ".$tbl_ad_fees." WHERE adterm_id='$adterm_id'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($adterm_name)=$rsrow;
	}
	return $adterm_name;
}
// END FUNCTION
// START FUNCTION: Get the ad recperiod based on having the ad term ID
function get_fee_recperiod($adterm_id) {

	global $wpdb;
	$tbl_ad_fees = $wpdb->prefix . "awpcp_adfees";

	$recperiod='';

	$query="SELECT rec_period from ".$tbl_ad_fees." WHERE adterm_id='$adterm_id'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($recperiod)=$rsrow;
	}
	return $recperiod;
}
// END FUNCTION
// START FUNCTION: Get the ad posters name based on having the ad ID
function get_adpostername($adid) {
	return get_adfield_by_pk('ad_contact_name', $adid);
}
// END FUNCTION
// START FUNCTION: Get the ad posters access key based on given ID
function get_adkey($adid) {
	return get_adfield_by_pk('ad_key', $adid);
}
// END FUNCTION
// START FUNCTION: Get the ad title based on having the ad email
function get_adcontactbyem($email) {
	$adcontact='';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_contact_name from ".$tbl_ads." WHERE ad_contact_email='$email'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($adcontact)=$rsrow;
	}
	return $adcontact;

}
// END FUNCTION
// START FUNCTION: Get the ad posters name based on having the ad email
function get_adtitlebyem($email) {
	$adtitle='';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_title from ".$tbl_ads." WHERE ad_contact_email='$email'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($adtitle)=$rsrow;
	}
	return strip_slashes_recursive($adtitle);

}
// END FUNCTION
// START FUNCTION: Get the ad posters email based on having the ad ID
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
// END FUNCTION
// START FUNCTION: Get the ad term fee amount for the ad term based on having the ad term ID
function get_adfee_amount($adterm_id) {

	global $wpdb;
	$tbl_ad_fees = $wpdb->prefix . "awpcp_adfees";

	$adterm_amount='';

	$query="SELECT amount from ".$tbl_ad_fees." WHERE adterm_id='$adterm_id'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($adterm_amount)=$rsrow;
	}
	return $adterm_amount;
}
// END FUNCTION: get ad term fee amount based on ad term ID




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

	$catnid=$wpdb->get_results("select category_id as cat_ID, category_parent_id as cat_parent_ID, category_name as cat_name from ".$tbl_categories." WHERE category_parent_id=0 AND category_name <> '' $excludequery");

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

	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res)) {

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

		$res2 = awpcp_query($query, __LINE__);

		while ($rsrow2=mysql_fetch_row($res2)) {
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

function get_categorycheckboxes( $cats=array(), $adterm_id=null, $field_name='fee_cats' ) {
	global $wpdb;

	$tbl_categories = $wpdb->prefix . "awpcp_categories";
	$tbl_fees = $wpdb->prefix . "awpcp_adfees";

	$optionitem='';

	$catswithfees = array();
	if (!empty($adterm_id)) {
		$sql = 'select categories from '.$tbl_fees.' where adterm_id = '.$adterm_id;
		$catswithfees = $wpdb->get_var($sql);

		if (!empty($catswithfees)) {
			$catswithfees = explode(',', $catswithfees);
		} else {
			$catswithfees = array();
		}
	}


	// Start with the main categories

	$query="SELECT category_id,category_name FROM ".$tbl_categories." WHERE category_parent_id=0 and category_name <> '' ORDER BY category_order, category_name ASC";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res)) {

		$cat_ID=$rsrow[0];
		$cat_name=$rsrow[1];

		$opstyle="class=\"checkboxparentcategory\"";

		if(in_array($cat_ID, $catswithfees)) {
			$maincatoptionitem = "<label><input name='{$field_name}[]' type='checkbox' $opstyle checked='checked' value='$cat_ID'> $cat_name</label><br/>";
		} else {
			$maincatoptionitem = "<label><input name='{$field_name}[]' type='checkbox' $opstyle value='$cat_ID'> $cat_name</label><br/>";
		}

		$optionitem.="$maincatoptionitem";

		// While still looping through main categories get any sub categories of the main category

		$maincatid=$cat_ID;

		$query="SELECT category_id,category_name FROM ".$tbl_categories." WHERE category_parent_id='$maincatid' ORDER BY category_order, category_name ASC";
		$res2 = awpcp_query($query, __LINE__);

		while ($rsrow2=mysql_fetch_row($res2)) {
			$subcat_ID=$rsrow2[0];
			$subcat_name=$rsrow2[1];

			if( in_array($subcat_ID, $catswithfees) )
			{
				$subcatoptionitem = "<label><input name='{$field_name}[]' type='checkbox' checked='checked' value='$subcat_ID'> &nbsp; &nbsp; - $subcat_name</label><br/>";
			}
			else {
				$subcatoptionitem = "<label><input name='{$field_name}[]' type='checkbox' value='$subcat_ID'> &nbsp; &nbsp; - $subcat_name</label><br/>";
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
		$query="SELECT category_name from ".$tbl_categories." WHERE category_id='$cat_ID'";
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
		$query="SELECT category_order from ".$tbl_categories." WHERE category_id='$cat_ID'";
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
	$tbl_categories = $wpdb->prefix . "awpcp_categories";
	$cname='';

	if ($cat_ID == 0) {
		$cname = __('Top Level Category', 'AWPCP');
	}

	else
	{
		if(isset($cat_ID) && (!empty($cat_ID)))
		{
			$query="SELECT category_name from ".$tbl_categories." WHERE category_id='$cat_ID'";
			$res = awpcp_query($query, __LINE__);

			while ($rsrow=mysql_fetch_row($res))
			{
				list($cname)=$rsrow;
			}
		}
	}
	return $cname;
}
// END FUNCTION: get the name of the category parent
// START FUNCTION: Retrieve the parent category ID
function get_cat_parent_ID($cat_ID){

	global $wpdb;
	$cpID='';
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	if(isset($cat_ID) && (!empty($cat_ID))){
		$query="SELECT category_parent_id from ".$tbl_categories." WHERE category_id='$cat_ID'";
		$res = awpcp_query($query, __LINE__);
		while ($rsrow=mysql_fetch_row($res)) {
			list($cpID)=$rsrow;
		}
	}
	return $cpID;
}
// END FUNCTION: get the ID or the category parent
// START FUNCTION: Check if the transaction ID coming back from paypal or 2checkout is a duplicate
function isdupetransid($transid){
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$myreturn=!awpcpisqueryempty($tbl_ads, " WHERE ad_transaction_id='$transid'");
	return $myreturn;
}
// END FUNCTION: check if a transaction ID from paypal or 2checkout is already in the system
// START FUNCTION: Check if there are any ads in the system
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
// START FUNCTION: Check if the category has children
function category_has_children($catid) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";
	$myreturn=!awpcpisqueryempty($tbl_categories, " WHERE category_parent_id='$catid'");
	return $myreturn;
}
// END FUNCTION: check if a category has children
// START FUNCTION: Check if the category is a child
function category_is_child($catid) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";
	$myreturn=false;

	$query="SELECT category_parent_id FROM ".$tbl_categories." WHERE category_id='$catid'";
	$res = awpcp_query($query, __LINE__);
	while ($rsrow=mysql_fetch_row($res)) {
		list($cparentid)=$rsrow;
		if( $cparentid != 0 )
		{
			$myreturn=true;
		}
	}
	return $myreturn;
}

// END FUNCTION: check if a category is a child

// TODO: cache the results of this function
function total_ads_in_cat($catid) {
    global $wpdb,$hasregionsmodule;

    $totaladsincat = '';

    // never allow Unpaid Ads
    $filter = " AND payment_status != 'Unpaid' ";
    $filter = " AND verified = 1 ";
    // the name of the disablependingads setting gives the wrong meaning,
    // it actually means "Enable Paid Ads that are Pendings payment", so when
    // the setting has a value of 1, pending Ads should NOT be excluded.
    // I'll change the next condition considering the above
    if((get_awpcp_option('disablependingads') == 0) && (get_awpcp_option('freepay') == 1)){
        $filter = " AND payment_status != 'Pending'";
    }

    // TODO: ideally there would be a function to get all visible Ads,
    // and modules, like Regions, would use hooks to include their own
    // conditions.
    if ($hasregionsmodule == 1) {
        if (isset($_SESSION['theactiveregionid'])) {
            $theactiveregionid = $_SESSION['theactiveregionid'];

            if (function_exists('awpcp_regions_api')) {
            	$regions = awpcp_regions_api();
            	$filter .= ' AND ' . $regions->sql_where($theactiveregionid);
            }
        }
    }

    // TODO: at some point we should start using the Category model.
    $query = "SELECT count(*) FROM " . AWPCP_TABLE_ADS . " ";
    $query.= "WHERE (ad_category_id='$catid' OR ad_category_parent_id='$catid') ";
    // $query.= "AND disabled = 0 AND (flagged IS NULL OR flagged =0) $filter";
    $query.= "AND disabled = 0 $filter";

    $res = awpcp_query($query, __LINE__);
    while ($rsrow=mysql_fetch_row($res)) {
        list($totaladsincat)=$rsrow;
    }
    return $totaladsincat;
}

// END FUNCTION: check how many ads are in a category
// START FUNCTION: Check if there are any ads in the system
function images_exist() {
	global $wpdb;
	$tbl_ad_photos = $wpdb->prefix . "awpcp_ads";
	$myreturn=!awpcpistableempty($tbl_ad_photos);
	return $myreturn;
}
// END FUNCTION: Check if images exist in system

/**
 * Remove unwanted characters from string and setup for use with search engine
 * friendly urls.
 *
 * @deprecated deprecated since 2.0.6. Use sanitize_title instead.
 */
function cleanstring($text) {
	$code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
	$code_entities_replace = array('_','_','','','','','','','','','','','','','','','','','','','','','','','');
	$text = str_replace($code_entities_match, $code_entities_replace, $text);
	if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
		$text="".(filter_var($text, FILTER_SANITIZE_URL))."";
	}
	return $text;
}


// END FUNCTION: remove unwanted characters from string to be used in URL for search engine friendliness
// START FUNCTION: replace underscores with dashes for search engine friendly urls
function add_dashes($text) {
	$text=str_replace("_","-",$text);
	$text=str_replace(" ","-",$text);
	return $text;
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
		$sql = "SELECT ID FROM $wpdb->posts WHERE post_name = '$name'";
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


// END FUNCTION: Get the ID from wordpress posts table where the post_name is known
// START FUNCTION: Get the page guid
function awpcp_get_guid($awpcpshowadspageid){
	global $wpdb;
	$awpcppageguid = $wpdb->get_var("SELECT guid FROM $wpdb->posts WHERE ID ='$awpcpshowadspageid'");
	return $awpcppageguid;
}
// END FUNCTION: Get the page guid


// START FUNCTION: 
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

// START FUNCTION: get the parent_id of the post

//XXX: never used?
function get_page_parent_id($awpcpwppostpageid){
	global $wpdb;
	$awpcppageparentid = $wpdb->get_var("SELECT post_parent FROM $wpdb->posts WHERE ID = '$awpcpwppostpageid'");
	return $awpcppageparentid;
}


// END FUNCTION: get the parent id of a wordpress post where the post ID is known



// START FUNCTION: get the name of a wordpress entry from table posts where the parent id is present
//XXX: never used?
function get_awpcp_parent_page_name($awpcppageparentid) {

	global $wpdb;
	$awpcpparentpagename = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE ID = '$awpcppageparentid'");
	return $awpcpparentpagename;
}
// END FUNCTION: get the name of a wordpress wp_post entry where the ID of the post parent is present



/**
 * @deprecated since 2.0.7
 */ 
function checkfortable($table) {
	return awpcp_table_exists($table);
}



// START FUNCTION: add field config_group_id to table adsettings v 1.0.5.6 update specific
function add_config_group_id($cvalue,$coption) {
	global $wpdb;
	$tbl_ad_settings = $wpdb->prefix . "awpcp_adsettings";
	//Escape quotes:
	$cvalue = add_slashes_recursive($cvalue);
	$query="UPDATE ".$tbl_ad_settings." SET config_group_id='$cvalue' WHERE config_option='$coption'";
	$res = awpcp_query($query, __LINE__);
}
// END FUNCTION: add field config_group_id to table adsettings v 1.0.5.6 update specific



// START FUNCTION: check if a specific ad id already exists
function adidexists($adid) {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$adidexists=false;
	$query="SELECT count(*) FROM ".$tbl_ads." WHERE ad_id='$adid'";
	if (($res=mysql_query($query))) {
		$adidexists=true;
	}

	return $adidexists;
}


function categoryidexists($adcategoryid) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_adcategories";
	$categoryidexists=false;
	$query="SELECT count(*) FROM ".$tbl_categories." WHERE categoryid='$adcategoryid'";
	if (($res=mysql_query($query))) {
		$categoryidexists=true;
	}

	return $categoryidexists;
}
// END FUNCTION: check if a specific ad id already exists



// START FUNCTION: get the current name of the classfieds page
function display_setup_text() {
	$awpcpsetuptext="<h2>";
	$awpcpsetuptext.=__("Setup Process","AWPCP");
	$awpcpsetuptext.="</h2>";
	$awpcpsetuptext.="<p>";
	$awpcpsetuptext.=__("It looks like you have not yet told the system how you want your classifieds to operate.","AWPCP");
	$awpcpsetuptext.="</p>";
	$awpcpsetuptext.="<p>";
	$awpcpsetuptext.=__("Please begin by setting up the options for your site. The system needs to know a number of things about how you want to run your classifieds.","AWPCP");
	$awpcpsetuptext.='
		<p>
		    <form action="?page=Configure1&mspgs=1" method="post">
			    <input type="hidden" name="userpagename" value="AWPCP" >
				<input type="hidden" name="showadspagename" value="Show Ad" >
				<input type="hidden" name="placeadpagename" value="Place Ad" >
				<input type="hidden" name="page-name-renew-ad" value="Renew Ad" >
				<input type="hidden" name="browseadspagename" value="Browse Ads" >
				<input type="hidden" name="replytoadpagename" value="Reply To Ad" >
				<input type="hidden" name="paymentthankyoupagename" value="Payment Thank You" >
				<input type="hidden" name="paymentcancelpagename" value="Cancel Payment" >
				<input type="hidden" name="searchadspagename" value="Search Ads" >
				<input type="hidden" name="browsecatspagename" value="Browse Categories" >
				<input type="hidden" name="editadpagename" value="Edit Ad" >
				<input type="hidden" name="categoriesviewpagename" value="View Categories" >
				<input type="hidden" name="cgid" value="10" >
				<input type="hidden" name="makesubpagesa" value="" >
				<input type="hidden" name="confirmsave" value="1">
				<input type="hidden" name="awpcp_installationcomplete" value="1">
				<input type="submit" name="savesettings" class="button" value="Click here to complete setup" style="width: 375px;font: 22px Georgia; margin:20px auto;padding: 10px"> 
			</form>
		</p>';


	return $awpcpsetuptext;
}


/**
 * Returns the current name of the AWPCP main page.
 */
function get_currentpagename() {
	return get_awpcp_option('main-page-name');
}



// START FUNCTION: delete the classfied page name from database as needed
function deleteuserpageentry() {

	global $wpdb;
	$tbl_pagename = $wpdb->prefix . "awpcp_pagename";

	$query="TRUNCATE ".$tbl_pagename."";
	$res = awpcp_query($query, __LINE__);
	mysql_query($query);
}
// END FUNCTION: delete the user page entry from awpcp_pagename table




// START FUNCTION: check if the classifieds page exists in the wp posts table
function findpagebyname($pagename) {
	global $wpdb,$table_prefix;
	$myreturn=false;

	$query="SELECT post_title FROM {$table_prefix}posts WHERE post_title='$pagename'";
	$res = awpcp_query($query, __LINE__);
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}



function findpage($pagename,$shortcode) {
	global $wpdb,$table_prefix;
	$myreturn=false;

	$query="SELECT post_title FROM {$table_prefix}posts WHERE post_title='$pagename' AND post_content LIKE '%$shortcode%'";
	$res = awpcp_query($query, __LINE__);
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}




// START FUNCTION: check ad_settings to see if a particular function exists to prevent duplicate entery when updating plugin
function field_exists($field) {
	global $wpdb;
	$tbl_ad_settings = $wpdb->prefix . "awpcp_adsettings";

	$tableexists=checkfortable($tbl_ad_settings);

	if($tableexists) {
		$query="SELECT config_value FROM  ".$tbl_ad_settings." WHERE config_option='$field'";
		$res = awpcp_query($query, __LINE__);
		if (mysql_num_rows($res)) {
			$myreturn=true;
		} else {
			$myreturn=false;
		}
		return $myreturn;
	}
	return false;
}
// END FUNCTION: check if ad_settings field exists


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


/**
 * Unused function
 */
function renewsubscription($adid) {
	global $wpdb;

	$query = "SELECT payment_status FROM " . AWPCP_TABLE_ADS . " WHERE ad_id='$adid'";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res)) {
		list($paymentstatus)=$rsrow;
	}

	if($paymentstatus != 'Cancelled') {
		return true;
	}

	return false;
}
// END FUNCTION: process auto ad expiration



// START FUNCTION: Function to check for the existence of a default category with a category ID of 1 (used with mass category deletion)
function defaultcatexists($defid) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$myreturn=false;
	$query="SELECT * FROM ".$tbl_categories." WHERE category_id='$defid'";
	$res = awpcp_query($query, __LINE__);
	if (mysql_num_rows($res)) {
		$myreturn=true;
	}

	return $myreturn;

}
// END FUNCTION: check if default category exists



// START FUNCTION: function to create a default category with an ID of  1 in the event a default category with ID 1 does not exist
function createdefaultcategory($idtomake,$titletocallit) {
	global $wpdb;
	$tbl_categories = $wpdb->prefix . "awpcp_categories";

	$query="INSERT INTO ".$tbl_categories." SET category_name='$titletocallit',category_parent_id=0";
	$res = awpcp_query($query, __LINE__);
	$newdefid=mysql_insert_id();

	$query="UPDATE ".$tbl_categories." SET category_id=1 WHERE category_id='$newdefid'";
	$res = awpcp_query($query, __LINE__);
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
	$awpcppagename = sanitize_title($cpagename_awpcp, $post_ID='');

	$pageswithawpcpname=array();
	global $wpdb,$table_prefix;
	$totalpageswithawpcpname='';

	$query="SELECT ID FROM {$table_prefix}posts WHERE post_name = '$awpcppagename' AND post_type='post'";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		while ($rsrow=mysql_fetch_row($res))
		{
			$pageswithawpcpname[]=$rsrow[0];
		}
		$totalpageswithawpcpname=count($pageswithawpcpname);
	}

	return $totalpageswithawpcpname;
}



// END FUNCTION: make sure there's not more than one page with the name of the classifieds page
// START FUNCTION: create a drop down list containing names of ad posters
function create_ad_postedby_list($name) {
	$output = '';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT DISTINCT ad_contact_name FROM ".$tbl_ads." WHERE disabled=0";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res)) {
		if (strcmp($rsrow[0], $name) === 0) {
			$output .= "<option value=\"$rsrow[0]\" selected=\"selected\">$rsrow[0]</option>";
		} else {
			$output .= "<option value=\"$rsrow[0]\">$rsrow[0]</option>";
		}
	}
	return $output;
}

function awpcp_array_range($from, $to, $step){

	$array = array();
	for ($x=$from; $x <= $to; $x += $step){
		$array[] = $x;
	}
	return $array;

}

function awpcp_get_max_ad_price()
{
	$query="SELECT MAX(ad_item_price) as endval FROM ".$tbl_ads."";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		$maxadprice=$rsrow[0];
	}
	return $maxadprice;
}
// END FUNCTION: create a drop down list containing price option
// START FUNCTION: create a drop down list containing cities options from saved cities in database
function create_dropdown_from_current_cities()
{
	$output = '';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$listofsavedcities=array();

	$query="SELECT DISTINCT ad_city FROM ".$tbl_ads." WHERE ad_city <> '' AND disabled = 0 ORDER by ad_city ASC";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		$listofsavedcities[]=$rsrow[0];
		$savedcitieslist=array_unique($listofsavedcities);
	}

	foreach ($savedcitieslist as $savedcity)
	{
		$output .= "<option value=\"" . esc_attr($savedcity) . "\">" . stripslashes($savedcity) . "</option>";
	}
	return $output;
}
// END FUNCTION: create a drop down list containing cities options from saved cities in database
// START FUNCTION: create a drop down list containing state options from saved states in database
function create_dropdown_from_current_states()
{
	$output = '';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$listofsavedstates=array();

	$query="SELECT DISTINCT ad_state FROM ".$tbl_ads." WHERE ad_state <> '' AND disabled = 0 ORDER by ad_state ASC";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		$listofsavedstates[]=$rsrow[0];
		$savedstateslist=array_unique($listofsavedstates);
	}

	foreach ($savedstateslist as $savedstate)
	{
		$output .= "<option value=\"" . esc_attr($savedstate) . "\">" . stripslashes($savedstate) . "</option>";
	}
	return $output;
}
// END FUNCTION: create a drop down list containing states options from saved states in database
// START FUNCTION: create a drop down list containing county/village options from saved states in database
function create_dropdown_from_current_counties()
{
	$output = '';
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$listofsavedcounties=array();

	$query="SELECT DISTINCT ad_county_village FROM ".$tbl_ads." WHERE ad_county_village <> '' AND disabled = 0 ORDER by ad_county_village ASC";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		$listofsavedcounties[]=$rsrow[0];
		$savedcountieslist=array_unique($listofsavedcounties);

	}
	foreach ($savedcountieslist as $savedcounty)
	{
		$output .= "<option value=\"" . esc_attr($savedcounty) . "\">" . stripslashes($savedcounty) . "</option>";
	}
	return $output;
}
// END FUNCTION: create a drop down list containing county/village options from saved states in database
// START FUNCTION: create a drop down list containing country options from saved countries in database
function create_dropdown_from_current_countries() {
	global $wpdb;
	$output = '';
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT DISTINCT ad_country FROM ".$tbl_ads." WHERE ad_country <> '' AND disabled = 0 ORDER by ad_country ASC";
	$res = awpcp_query($query, __LINE__);

	$listofsavedcountries = array();
	while ($rsrow=mysql_fetch_row($res)) {
		$listofsavedcountries[] = $rsrow[0];
	}
	$savedcountrieslist = $listofsavedcountries;

	foreach ($savedcountrieslist as $savedcountry) {
		$output .= "<option value=\"" . esc_attr($savedcountry) . "\">" . stripslashes($savedcountry) . "</option>";
	}
	return $output;
}
// END FUNCTION: create a drop down list containing country options from saved states in database
// START FUNCTION: Check if ads table contains city data
function adstablehascities()
{
	$myreturn=false;

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_city FROM ".$tbl_ads." WHERE ad_city <> ''";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		$myreturn=true;
	}

	return $myreturn;

}
// END FUNCTION: Check if ads table contains city data
// START FUNCTION: Check if ads table contains state data
function adstablehasstates()
{
	$myreturn=false;

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_state FROM ".$tbl_ads." WHERE ad_state <> ''";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		$myreturn=true;
	}

	return $myreturn;

}
// END FUNCTION: Check if ads table contains state data
// START FUNCTION: Check if ads table contains country data
function adstablehascountries()
{

	$myreturn=false;

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_country FROM ".$tbl_ads." WHERE ad_country <> ''";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		$myreturn=true;
	}

	return $myreturn;

}
// END FUNCTION: Check if ads table contains country data
// START FUNCTION: Check if ads table contains county data
function adstablehascounties()
{

	$myreturn=false;

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_county_village FROM ".$tbl_ads." WHERE ad_county_village <> ''";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		$myreturn=true;
	}

	return $myreturn;

}
// END FUNCTION: Check if ads table contains county data
// START FUNCTION: check if there are any values entered into the price field for any ad
function price_field_has_values()
{
	$myreturn=false;

	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$query="SELECT ad_item_price FROM ".$tbl_ads." WHERE ad_item_price > 0";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res))
	{
		$myreturn=true;
	}

	return $myreturn;
}
// END FUNCTION: check if there are any values entered into the price field for any ad

/**
 * This function is supposed to return a random image from the images associated 
 * to an Ad with id $ad_id but it returns the oldest image available instead.
 */
function get_a_random_image($ad_id) {
	global $wpdb;
	$tbl_ad_photos = $wpdb->prefix . "awpcp_adphotos";
	$awpcp_image_name='';

	$query="SELECT image_name FROM ".$tbl_ad_photos." WHERE ad_id='$ad_id' AND disabled=0 LIMIT 1";
	$res = awpcp_query($query, __LINE__);

	if (mysql_num_rows($res)) {
		list($awpcp_image_name)=mysql_fetch_row($res);
	}

	return $awpcp_image_name;
}


// START FUNCTION: check a specific ad to see if it is disabled or enabled
function check_if_ad_is_disabled($adid) {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";

	$myreturn=false;
	$query="SELECT disabled FROM ".$tbl_ads." WHERE ad_id='$adid'";
	$res = awpcp_query($query, __LINE__);

	while ($rsrow=mysql_fetch_row($res))
	{
		list($adstatusdisabled)=$rsrow;
	}
	if ($adstatusdisabled == 1)
	{
		$myreturn=true;
	}

	return $myreturn;
}
// END FUNCTION: check a specific ad to see if it is disabled or enabled
function check_ad_fee_paid($adid) {
	global $wpdb;
	$tbl_ads = $wpdb->prefix . "awpcp_ads";
	$adfeeispaid=false;
	$query="SELECT ad_fee_paid FROM ".$tbl_ads." WHERE ad_id='$adid'";
	while ($rsrow=mysql_fetch_row($res))
	{
		list($ad_fee_paid)=$rsrow;
	}
	if($ad_fee_paid > 0){$adfeeispaid=true;}

	return $adfeeispaid;
}

// START FUNCTION: Clear HTML tags
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


function is_at_least_awpcp_version($version)
{
	global $awpcp_plugin_data;
	if (version_compare($awpcp_plugin_data['Version'], $version, ">=")) {
		// you're on a later or equal version, this is good
		$ok = true;
	} else {
		// earlier version, this is bad
		$ok = false;
	}
	return $ok;
}

function awpcp_insert_tweet_button($layout, $adid, $title) {
	$properties = array(
		'url' => urlencode(url_showad($adid)),
		'text' => urlencode($title)
	);

	$url = add_query_arg($properties, 'http://twitter.com/share');

	$button = '<div class="tw_button awpcp_tweet_button_div">';
	$button.= '<a href="' . $url . '" rel="nofollow" class="twitter-share-button" target="_blank">';
	$button.= 'Tweet This'.'</a>';
	$button.= '</div>';

	$layout = str_replace('$tweetbtn', $button, $layout);

	return $layout;
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

	if ( strlen($info['description']) > 300 ) {
		$info['description'] = substr($info['description'], 0, 300) . '...';
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


function awpcp_insert_share_button($layout, $adid, $title) {
	global $awpcp_plugin_url;

	$info = awpcp_get_ad_share_info($adid);

	$href = 'http://www.facebook.com/sharer.php?';
	$href.= 's=100';

	foreach ($info['images'] as $k => $image) {
		$href.= '&p[images][' . $k . ']=' . urlencode($image);
	}

	// put them after the image URLs to avoid conflict with lightbox plugins
	// https://github.com/drodenbaugh/awpcp/issues/310
	// http://www.awpcp.com/forum/viewtopic.php?f=4&t=3470&p=15358#p15358
	$href.= '&p[url]=' . urlencode($info['url']);
	$href.= '&p[title]=' . urlencode($title);
	$href.= '&p[summary]=' . urlencode($info['description']);

	$button = '<div class="tw_button awpcp_tweet_button_div">';
	$button.= '<a href="' . $href . '" class="facebook-share-button" title="Share on Facebook" target="_blank"></a>';
	$button.= '</div>';

	$layout = str_replace('$sharebtn', $button, $layout);
	return $layout;
}

//
// Metadata API.
//

function awpcp_add_ad_meta( $ad_id, $meta_key, $meta_value, $unique = false ) {
    return add_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $unique );
}

function awpcp_update_ad_meta( $ad_id, $meta_key, $meta_value, $prev_value = '' ) {
    return update_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $prev_value );
}

function awpcp_delete_ad_meta( $ad_id, $meta_key, $meta_value = '', $delete_all = false) {
    return delete_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $delete_all );
}

function awpcp_get_ad_meta( $ad_id, $meta_key='', $single = false ) {
    return get_metadata( 'awpcp_ad', $ad_id, $meta_key, $single );
}
