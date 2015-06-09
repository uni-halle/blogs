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

/**
 * @since new-release
 */
function awpcp_get_option( $option, $default = '', $reload = false ) {
	return get_awpcp_option( $option, $default, $reload );
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

// START FUNCTION: Retrieve the category name
function get_adcatname($cat_ID) {
	try {
		$category = awpcp_categories_collection()->get( $cat_ID );
		$category_name = stripslashes_deep( $category->name );
	} catch( AWPCP_Exception $e ) {
		$category_name = '';
	}

	return $category_name;
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

//Function to replace addslashes_mq, which is causing major grief.  Stripping of undesireable characters now done
// through above strip_slashes_recursive_gpc.
function clean_field($foo) {
	return add_slashes_recursive($foo);
}
// END FUNCTION: replace underscores with dashes for search engine friendly urls

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

/**
 * @since 3.4
 */
function awpcp_is_email_address_allowed( $email_address ) {
    $wildcard = 'BCCsyfxU6HMXyyasic6t';
    $pattern = '[a-zA-Z0-9-]*';

    $domains_whitelist = str_replace( '*', $wildcard, get_awpcp_option( 'ad-poster-email-address-whitelist' ) );
    $domains_whitelist = preg_quote( $domains_whitelist );
    $domains_whitelist = str_replace( $wildcard, $pattern, $domains_whitelist );
    $domains_whitelist = str_replace( "{$pattern}\.", "(?:{$pattern}\.)?", $domains_whitelist );
    $domains_whitelist = array_filter( explode( "\n", $domains_whitelist ) );
    $domains_whitelist = array_map( 'trim', $domains_whitelist );

    $domains_pattern = '/' . implode( '|', $domains_whitelist ) . '/';

    if ( empty( $domains_whitelist ) ) {
		return true;
    }

    $domain = substr( $email_address, strpos( $email_address, '@' ) + 1 );

    if ( preg_match( $domains_pattern, $domain ) ) {
		return true;
    }

    return false;
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
// END FUNCTION

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
function awpcp_phpmailer_init_smtp( $phpmailer ) { 
	// smtp not enabled? 
	$enabled = get_awpcp_option('usesmtp');
	if ( !$enabled || 0 == $enabled ) return; 

	$hostname = get_awpcp_option('smtphost');
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
	$phpmailer->Host = $hostname;
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
