<?php

// for PHP4 users, even though it's not technically supported:
if (!function_exists('array_walk_recursive')) {
	function array_walk_recursive(&$input, $funcname, $userdata = "") {
	    if (!is_callable($funcname)) {
	        return false;
	    }
	    if (!is_array($input)) {
	        return false;
	    }

	    foreach ($input AS $key => $value) {
	        if (is_array($input[$key])) {
	            array_walk_recursive($input[$key], $funcname, $userdata);
	        } else {
	            $saved_value = $value;
	            if (!empty($userdata)) {
	                $funcname($value, $key, $userdata);
	            } else {
	                $funcname($value, $key);
	            }
	            if ($value != $saved_value) {
	                $input[$key] = $value;
	            }
	        }
	    }
	    return true;
	}
}


if (!function_exists('wp_strip_all_tags')) {
	/**
	 * Properly strip all HTML tags including script and style
	 *
	 * @since 2.9.0
	 *
	 * @param string $string String containing HTML tags
	 * @param bool $remove_breaks optional Whether to remove left over line breaks and white space chars
	 * @return string The processed string.
	 */
	function wp_strip_all_tags($string, $remove_breaks = false) {
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
		$string = strip_tags($string);

		if ( $remove_breaks )
			$string = preg_replace('/[\r\n\t ]+/', ' ', $string);

		return trim($string);
	}
}


if (!function_exists('esc_textarea')) {
	/**
	 * Escaping for textarea values.
	 *
	 * @since 3.1
	 *
	 * @param string $text
	 * @return string
	 */
	function esc_textarea( $text ) {
		$safe_text = htmlspecialchars( $text, ENT_QUOTES );
		return apply_filters( 'esc_textarea', $safe_text, $text );
	}
}


if (!function_exists('wp_trim_words')) {
	/**
	 * Trims text to a certain number of words.
	 *
	 * @param string $text Text to trim.
	 * @param int $num_words Number of words. Default 55.
	 * @param string $more What to append if $text needs to be trimmed. Default '&hellip;'.
	 * @return string Trimmed text.
	 */
	function wp_trim_words( $text, $num_words = 55, $more = null ) {
		if ( null === $more )
			$more = __( '&hellip;', 'AWPCP' );
		$original_text = $text;
		$text = wp_strip_all_tags( $text );
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( ' ', $words_array );
			$text = $text . $more;
		} else {
			$text = implode( ' ', $words_array );
		}
		return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
	}
}


function awpcp_esc_attr($text) {
	// WP adds slashes to all request variables
	$text = stripslashes($text);
	// AWPCP adds more slashes
	$text = stripslashes($text);
	$text = esc_attr($text);
	return $text;
}

function awpcp_esc_textarea($text) {
	$text = stripslashes($text);
	$text = stripslashes($text);
	$text = esc_textarea($text);
	return $text;
}

/**
 * @since 3.0.2
 */
function awpcp_strptime( $date, $format ) {
	if ( function_exists( 'strptime' ) ) {
		return strptime( $date, $format );
	} else {
		return awpcp_strptime_replacement( $date, $format );
	}
}

/**
 * @since 3.0.2
 */
function awpcp_strptime_replacement( $date, $format ) {
    $masks = array(
        '%d' => '(?P<d>[0-9]{2})',
        '%m' => '(?P<m>[0-9]{2})',
        '%y' => '(?P<y>[0-9]{2})',
        '%Y' => '(?P<Y>[0-9]{4})',
        '%H' => '(?P<H>[0-9]{2})',
        '%M' => '(?P<M>[0-9]{2})',
        '%S' => '(?P<S>[0-9]{2})',
        // usw..
    );

    $regexp = "#" . strtr( preg_quote( $format ), $masks ) . "#";
    if ( ! preg_match( $regexp, $date, $out ) ) {
        return false;
    }

    $unparsed = preg_replace( $regexp, '', $date );

    if ( isset( $out['y'] ) && strlen( $out['y'] ) ) {
    	$out['Y'] = ( $out['y'] > 69 ? 1900 : 2000 ) + $out['y'];
    }

    $ret = array(
        'tm_sec' => (int) awpcp_array_data( 'S', 0, $out),
        'tm_min' => (int) awpcp_array_data( 'M', 0, $out),
        'tm_hour' => (int) awpcp_array_data( 'H', 0, $out),
        'tm_mday' => (int) awpcp_array_data( 'd', 0, $out),
        'tm_mon' => awpcp_array_data( 'm', 0, $out) ? awpcp_array_data( 'm', 0, $out) - 1 : 0,
        'tm_year' => awpcp_array_data( 'Y', 0, $out) > 1900 ? awpcp_array_data( 'Y', 0, $out) - 1900 : 0,
        'unparsed' => $unparsed,
    );

    return $ret;
}

/**
 * @since	3.0
 */
function awpcp_date_formats() {
	static $translations;

	if ( ! is_array( $translations ) ) {
		$translations = array(
			'd' => 'dd',
			'j' => 'd',
			's' => null,
			'l' => 'DD',
			'D' => 'D',
			'm' => 'mm',
			'n' => 'm',
			'F' => 'MM',
			'M' => 'M',
			'Y' => 'yy',
			'y' => 'y',
			'c' => 'ISO_8601',
			'r' => 'RFC_822',
		);
	}

	return $translations;
}


/**
 * @since	3.0
 */
function awpcp_time_formats() {
	static $translations;

	if ( ! is_array( $translations ) ) {
		$translations = array(
			'a' => 'p',
			'A' => 'P',
			'g' => 'h',
			'h' => 'hh',
			'G' => 'H',
			'H' => 'HH',
			'i' => 'mm',
			's' => 'ss',
			'T' => null,
			'c' => null,
			'r' => null
		);
	}

	return $translations;
}


/**
 * Translates PHP date format strings to jQuery Datepicker format.
 * @since  	3.0
 */
function awpcp_datepicker_format($format) {
	return _awpcp_replace_format($format, awpcp_date_formats());
}

/**
 * Translates PHP time format strings to jQuery TimePicker format.
 * @since	3.0
 */
function awpcp_timepicker_format($format) {
	return _awpcp_replace_format($format, awpcp_time_formats());
}


/**
 * @since	3.0
 */
function _awpcp_replace_format($format, $translations) {
	$pattern = join( '|', array_map( 'preg_quote', array_keys( $translations ) ) );

	preg_match_all( "/$pattern/s", $format, $matches );

	$processed = array();
	foreach ( $matches[0] as $match ) {
		if ( ! isset( $processed[ $match ] ) ) {
			$format = str_replace( $match, $translations[ $match ], $format );
			$processed[ $match ] = true;
		}
	}

	return $format;
}


/**
 * @since	3.0
 */
function awpcp_get_date_format() {
	return get_awpcp_option('date-format');
}


/**
 * @since	3.0
 */
function awpcp_get_time_format() {
	return get_awpcp_option('time-format');
}


/**
 * @since	3.0
 */
function awpcp_get_datetime_format() {
	$format = get_awpcp_option('date-time-format');
	$format = str_replace('<date>', '******', $format);
	$format = str_replace('<time>', '*^*^*^', $format);
	$format = preg_replace('/(\w)/', '\\\\$1', $format);
	$format = str_replace('******', awpcp_get_date_format(), $format);
	$format = str_replace('*^*^*^', awpcp_get_time_format(), $format);
	return $format;
}



/**
 * TODO: consider using date_i18n
 * Returns the given date as MySQL date string, Unix timestamp or
 * using a custom format.
 *
 * @since 2.0.7
 * @param $format 	'mysql', 'timestamp', or first arguemnt for date() function.
 * @deprecated	since 3.0.2, use awpcp_datetime()
 */
function awpcp_time($date=null, $format='mysql') {
	_deprecated_function( __FUNCTION__, '3.0.2', 'awpcp_datetime()' );
	return awpcp_datetime( $format, $date );
}


/**
 * TODO: consider using date_i18n
 * Returns the given date as MySQL date string, Unix timestamp or
 * using a custom format.
 *
 * @since 3.0.2
 * @param $format 	'mysql', 'timestamp', 'awpcp', 'awpcp-date', 'awpcp-time'
 *					  or first arguemnt for date() function.
 */
function awpcp_datetime( $format='mysql', $date=null ) {
	if ( is_null( $date ) || strlen( $date ) === 0 ) {
		$date = current_time( 'timestamp' );
	} else if ( is_string( $date ) ) {
		$date = strtotime( $date );
	} // else, we asume a timestamp

	switch ( $format ) {
		case 'mysql':
			return date( 'Y-m-d H:i:s', $date );
		case 'timestamp':
			return $date;
		case 'awpcp':
			return date( awpcp_get_datetime_format(), $date) ;
		case 'awpcp-date':
			return date( awpcp_get_date_format(), $date );
		case 'awpcp-time':
			return date( awpcp_get_time_format(), $date );
		default:
			return date( $format, $date );
	}
}


function awpcp_set_datetime_date( $datetime, $date ) {
    $base_timestamp = strtotime( $datetime );
    $base_year_month_day_timestamp = strtotime( date( 'Y-m-d', strtotime( $datetime ) ) );
    $time_of_the_day_in_seconds = $base_timestamp - $base_year_month_day_timestamp;

    $target_year_month_day_timestamp = strtotime( date( 'Y-m-d', strtotime( $date ) ) );

    $new_datetime_timestamp = $target_year_month_day_timestamp + $time_of_the_day_in_seconds;

    return awpcp_datetime( 'mysql', $new_datetime_timestamp );
}

function awpcp_is_mysql_date( $date ) {
	$regexp = '/^\d{4}-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/';
	return preg_match( $regexp, $date ) === 1;
}


/**
 * Get a WP User. See awpcp_get_users for details.
 *
 * @param $id int 	User ID
 */
function awpcp_get_user_data($id) {
	$users = awpcp_get_users( $id );
	if (!empty($users)) {
		return array_shift($users);
	}
	return null;
}


/**
 * Get list of WP registered users, adding special attributes to
 * each User object, as needed by AWPCP.
 *
 * Attributes added are:
 * - username
 * - address
 * - city
 * - state
 *
 * @param $where string 	SQL Where clause to filter users
 */
function awpcp_get_users( $user_id = null ) {
	global $wpdb;

	$query = 'SELECT <wp-users>.ID, <wp-users>.user_login, <wp-users>.user_email, <wp-users>.user_url, <wp-users>.display_name, <wp-user-meta>.meta_key, <wp-user-meta>.meta_value ';
	$query.= 'FROM <wp-users> JOIN <wp-user-meta> ON (<wp-user-meta>.user_id = <wp-users>.ID) ';
	$query.= "WHERE <wp-user-meta>.meta_key IN ('first_name', 'last_name', 'awpcp-profile') ";

	if ( ! is_null( $user_id ) ) {
		$query .= $wpdb->prepare( ' AND <wp-users>.ID = %d ', $user_id );
	}

	$query.= 'ORDER BY <wp-users>.display_name ASC, <wp-users>.ID ASC';

	$query = str_replace( '<wp-users>', $wpdb->users, $query );
	$query = str_replace( '<wp-user-meta>', $wpdb->usermeta, $query);

	$users_info = $wpdb->get_results( $query );
	$users = array();

	$profile_info = null;

	foreach ( $users_info as $k => $info ) {
		if ( ! isset( $users[ $info->ID ] ) ) {
			$users[ $info->ID ] = new stdClass();
			$users[ $info->ID ]->ID = $info->ID;
			$users[ $info->ID ]->user_login = $info->user_login;
			$users[ $info->ID ]->user_email = $info->user_email;
			$users[ $info->ID ]->user_url = $info->user_url;
			$users[ $info->ID ]->display_name = $info->display_name;
		}

		if ( $info->meta_key == 'awpcp-profile' ) {
			$profile_info = maybe_unserialize( $info->meta_value );
			$users[ $info->ID ]->address = awpcp_array_data( 'address', '', $profile_info );
			$users[ $info->ID ]->phone = awpcp_array_data( 'phone', '', $profile_info );
			$users[ $info->ID ]->city = awpcp_array_data( 'city', '', $profile_info );
			$users[ $info->ID ]->state = awpcp_array_data( 'state', '', $profile_info );
		} else {
			$users[ $info->ID ]->{$info->meta_key} = $info->meta_value;
		}
	}

	return $users;
}


/**
 * Returns a WP capability required to be considered an AWPCP admin.
 *
 * http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table
 *
 * @since 2.0.7
 */
function awpcp_admin_capability() {
	$roles = explode(',', get_awpcp_option('awpcpadminaccesslevel'));
	if (in_array('editor', $roles))
		return 'edit_pages';
	// default to: only WP administrator users are AWPCP admins
	return 'install_plugins';
}


/**
 * Check if current user is an Administrator according to
 * AWPCP settings.
 */
function awpcp_current_user_is_admin() {
	$capability = awpcp_admin_capability();
	return current_user_can($capability);
}


function awpcp_user_is_admin($id) {
	$capability = awpcp_admin_capability();
	return user_can($id, $capability);
}


function awpcp_get_grid_item_css_class($classes, $pos, $columns, $rows) {
	if ($pos < $columns)
		$classes[] = 'first-row';
	if ($pos >= (($rows - 1) * $columns))
		$classes[] = 'last-row';
	if ($pos == 0 || $pos % $columns == 0)
		$classes[] = 'first-column';
	if (($pos + 1) % $columns == 0)
		$classes[] = 'last-column';
	return $classes;
}

/**
 * @since 	3.0
 * @param  	array 	$params
 * @param  	string 	$url
 * @return 	String	HTML
 */
function awpcp_pagination($config, $url) {

	$blacklist = array('page_id',
					   'offset',
					   'results',
					   'PHPSESSID',
					   'aeaction',
					   'category_id',
					   'cat_ID',
					   'action',
					   'aeaction',
					   'category_name',
					   'category_parent_id',
					   'createeditadcategory',
					   'deletemultiplecategories',
					   'movedeleteads',
					   'moveadstocategory',
					   'category_to_delete',
					   'tpname',
					   'category_icon',
					   'sortby',
					   'adid',
					   'picid',
					   'adkey',
					   'editemail',
					   'deletemultipleads',
					   'spammultipleads',
					   'awpcp_ads_to_action',
					   'post_type');

	$params = array_merge($_GET, $_POST);
	foreach ($blacklist as $param) {
		unset($params[$param]);
	}

	extract(shortcode_atts(array('offset' => 0, 'results' => 10, 'total' => 10), $config));

	$pages = ceil($total / $results);
	$page = floor($offset / $results) + 1;

	for ($i=1; $i <= $pages; $i++) {
		if ($page == $i) {
			$items[] = sprintf('%d', $i);
		} else {
			$href_params = array_merge($params, array('offset' => ($i-1) * $results, 'results' => $results));
			$href = add_query_arg($href_params, $url);
			$items[] = sprintf('<a href="%s">%d</a>', esc_attr($href), esc_attr($i));
		}
	}

	$pagination = join('', $items);
	$options = awpcp_pagination_options( $results );

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/listings-pagination.tpl.php');
		$html = ob_get_contents();
	ob_end_clean();

	return $html;
}


function awpcp_get_categories() {
	global $wpdb;

	$sql = 'SELECT * FROM ' . AWPCP_TABLE_CATEGORIES;
	$results = $wpdb->get_results($sql);

	return $results;
}

function awpcp_get_categories_ids() {
	static $categories;

	if (!is_array($categories)) {
		$categories = awpcp_get_properties( awpcp_get_categories(), 'category_id' );
	}

	return $categories;
}

function _awpcp_get_categories_checkboxes($field_name, $categories=array(), $selected=array(), $editable=true) {
	$checked = 'checked="checked"';
	$template = '<label class="selectit"><input type="checkbox" id="in-category-%1$d" %3$s name="%4$s[]" value="%1$d"> %2$s</label>';

	$items = '';
	foreach ($categories as $category) {
		$items .= sprintf('<li id="category-%1$d">', $category->id);

		$attributes = '';
		if (in_array($category->id, $selected))
			$attributes .= 'checked="checked"';
		if (!$editable)
			$attributes .= 'disabled="disabled"';

		$items .= sprintf($template, $category->id, $category->name, $attributes, $field_name);

		$children = AWPCP_Category::find(array('parent' => $category->id));
		if (!empty($children)) {
			$items .= _awpcp_get_categories_checkboxes($field_name, $children, $selected, $editable);
		}

		$items .= '</li>';
	}

	return '<ul>' . $items . '</ul>';
}

function awpcp_get_categories_checkboxes($selected=array(), $editable=true, $field_name='categories') {
	global $wpdb;

	$categories = AWPCP_Category::find(array('parent' => 0));

	return _awpcp_get_categories_checkboxes( $field_name, $categories, (array) $selected, $editable );
}

/**
 * @since 3.0
 */
function awpcp_get_comma_separated_categories_list($categories=array(), $threshold=5) {
	$names = awpcp_get_properties( $categories, 'name' );
	return awpcp_get_comma_separated_list( $names, $threshold, __( 'None', 'AWPCP' ) );
}

/**
 * @since 3.0
 */
function awpcp_get_comma_separated_list($items=array(), $threshold=5, $none='') {
	$items = array_filter( $items, 'strlen' );
	$count = count( $items );

	if ( $count > $threshold ) {
		$message = _x( '%s and %d more.', 'comma separated list of things', 'AWPCP' );
		$items = array_splice( $items, 0, $threshold - 1 );
		return sprintf( $message, join( ', ', $items ), $count - $threshold + 1 );
	} else if ( $count > 0 ) {
		return sprintf( '%s.', join( ', ', $items ) );
	} else {
		return $none;
	}
}

/**
 * Returns an array of Region fields. Only those enabled
 * in the settings will be returned.
 *
 * @param $translations array 	Allow developers to change the name
 * 								attribute of the form field associated
 *								to this Region Field.
 * @since 3.0.2
 */
function awpcp_region_fields( $context='details' ) {
    $fields = apply_filters( 'awpcp-region-fields', false, $context );

    if ( false === $fields ) {
    	$fields = awpcp_default_region_fields( $context );
    }

    return $fields;
}

/**
 * @since 3.0.2
 */
function awpcp_default_region_fields( $context='details', $cache=true ) {
    $show_country_field = get_awpcp_option( 'displaycountryfield' );
    $show_state_field = get_awpcp_option( 'displaystatefield' );
    $show_city_field = get_awpcp_option( 'displaycityfield' );
    $show_county_field = get_awpcp_option( 'displaycountyvillagefield' );
    $show_city_field_before_county_field = get_awpcp_option( 'show-city-field-before-county-field' );

    $always_shown = in_array( $context, array( 'details', 'search' ) );
    $can_be_required = $context !== 'search';
    $_fields = array();

    if ( $show_country_field ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaycountryfieldreqop' ) );
        $_fields['country'] = array(
            'type' => 'country',
            'label' => __('Country', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate countries by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $show_state_field ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaystatefieldreqop' ) );
        $_fields['state'] = array(
            'type' => 'state',
            'label' => __('State/Province', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate states by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $show_city_field ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaycityfieldreqop' ) );
        $_fields['city'] = array(
            'type' => 'city',
            'label' => __('City', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate cities by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $show_county_field ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaycountyvillagefieldreqop' ) );
        $_fields['county'] = array(
            'type' => 'county',
            'label' => __('County/Village/Other', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate counties by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }

    if ( ! $show_city_field_before_county_field ) {
        $fields = array();
        foreach( array( 'country', 'state', 'county', 'city' ) as $field ) {
            if ( isset( $_fields[ $field ] ) ) {
                $fields[ $field ] = $_fields[ $field ];
            }
        }
    } else {
        $fields = $_fields;
    }

    return $fields;
}


/**
 * TODO: this belongs to an Ads API.
 * @since 3.0.2
 */
function awpcp_regions_search_conditions($regions=array()) {
	global $wpdb;

	if ( empty( $regions ) ) return array();

	$conditions = array();
	$fields = null;

	foreach ( $regions as $region ) {
		if ( $fields === null ) {
			$fields = array_reverse( array_keys( awpcp_region_fields() ) );
		}

		foreach ( $fields as $column ) {
			$value = isset( $region[ $column ] ) ? trim( $region[ $column ] ) : '';
			if ( ! empty( $value ) ) {
				$conditions[] = sprintf( "{$column} LIKE '%%%s%%'", esc_sql( trim( $value ) ) );
				break;
			}
		}
	}

	if ( empty( $conditions ) ) return array();

	$sql = 'SELECT ad_id FROM ' . AWPCP_TABLE_AD_REGIONS . ' ';
	$sql.= 'WHERE ' . join( ' OR ', $conditions );

	return array( AWPCP_TABLE_ADS . '.`ad_id` IN ( ' . $sql . ' )' );
}


/**
 * @since 3.0
 */
function awpcp_get_region_field_entries($field) {
	global $wpdb;

	$columns = array(
		'country' => 'ad_country',
		'county' => 'ad_county_village',
		'state' => 'ad_state',
		'city' => 'ad_city'
	);

	// TODO: shouldn't this conditions be the default behavior in AWPCP_Ad::query?
	$conditions[] = "disabled = 0";
	$conditions[] = "verified = 1";
	$conditions[] = "payment_status != 'Unpaid'";
    if (get_awpcp_option('disablependingads') == 0 && get_awpcp_option('freepay') == 1) {
        $conditions[] = "payment_status != 'Pending'";
    }

	$args = array(
		'fields' => sprintf('%1$s region_name, COUNT(ad_id) AS count_enabled', $columns[$field]),
		'where' => join(' AND ', $conditions),
		'order' => array( "{$columns[$field]} ASC" ),
		'groupby' => $columns[$field],
	);

	if ($entries = AWPCP_Ad::query($args, true)) {
		$empty = new stdClass;
		$empty->region_name = __('Select Option', 'AWPCP');
		$empty->count_enabled = false;
		$empty->dummy = true;
		array_unshift($entries, $empty);
	} else {
		$entries = array();
	}

	return $entries;
}

/**
 * @since  3.0
 */
function awpcp_render_region_form_field_options($entries, $selected=false) {
	$selected = count($entries) == 1 ? $entries[0]->region_name : $selected;
	$template = '<option value="%1$s" %2$s>%3$s</option>';

	$options = array();
	foreach ($entries as $entry) {
		$attribute = $entry->region_name == $selected ? 'selected="selected"' : '';
		if (awpcp_get_property($entry, 'dummy', false)) {
			$label = $entry->region_name;
			$value = "";
		} else {
			$label = sprintf('%1$s (%2$d)', $entry->region_name, $entry->count_enabled);
			$value = $entry->region_name;
		}
		$options[] = sprintf($template, esc_attr($value), $attribute, stripslashes($label));
	}

	return join('', $options);
}


/**
 * Generates HTML for Region fields. Only those fields enabled
 * in the settings will be returned.
 *
 * @param $query array 			Default or selected values for form fields.
 * @param $translations array 	Allow developers to change the name
 * 								attribute of the form field associated
 *								to this Region Field.
 */
function awpcp_region_form_fields($query, $translations=null, $context='details', $errors=array()) {
	if (is_null($translations)) {
		$translations = array('country', 'state', 'city', 'county');
		$translations = array_combine($translations, $translations);
	}

	$fields = array();
	foreach (awpcp_region_fields($translations) as $key => $field) {
		$field['value'] = awpcp_array_data($key, '', $query);
		if ($context === 'search' && get_awpcp_option('buildsearchdropdownlists')) {
			$field['entries'] = awpcp_get_region_field_entries($key);
			$field['options'] = awpcp_render_region_form_field_options($field['entries'], $field['value']);
		} else {
			$field['entries'] = array();
			$field['options'] = '';
		}
		$fields[$key] = $field;
	}

	// no field is required in Search Ads screen
	if ( $context == 'search' ) {
		foreach ( $fields as $key => $field ) {
			$fields[$key]['required'] = false;
		}
	}

	$ordered = array('country', 'state', 'city', 'county');

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/region-control-form-fields.tpl.php');
		$html = ob_get_contents();
	ob_end_clean();

	return $html;
}


function awpcp_country_list_options($value=false, $use_names=true) {
	$countries = array(
	    'US' => 'United States',
	    'AL' => 'Albania',
	    'DZ' => 'Algeria',
	    'AD' => 'Andorra',
	    'AO' => 'Angola',
	    'AI' => 'Anguilla',
	    'AG' => 'Antigua and Barbuda',
	    'AR' => 'Argentina',
	    'AM' => 'Armenia',
	    'AW' => 'Aruba',
	    'AU' => 'Australia',
	    'AT' => 'Austria',
	    'AZ' => 'Azerbaijan Republic',
	    'BS' => 'Bahamas',
	    'BH' => 'Bahrain',
	    'BB' => 'Barbados',
	    'BE' => 'Belgium',
	    'BZ' => 'Belize',
	    'BJ' => 'Benin',
	    'BM' => 'Bermuda',
	    'BT' => 'Bhutan',
	    'BO' => 'Bolivia',
	    'BA' => 'Bosnia and Herzegovina',
	    'BW' => 'Botswana',
	    'BR' => 'Brazil',
	    'BN' => 'Brunei',
	    'BG' => 'Bulgaria',
	    'BF' => 'Burkina Faso',
	    'BI' => 'Burundi',
	    'KH' => 'Cambodia',
	    'CA' => 'Canada',
	    'CV' => 'Cape Verde',
	    'KY' => 'Cayman Islands',
	    'TD' => 'Chad',
	    'CL' => 'Chile',
	    'C2' => 'China',
	    'CO' => 'Colombia',
	    'KM' => 'Comoros',
	    'CK' => 'Cook Islands',
	    'CR' => 'Costa Rica',
	    'HR' => 'Croatia',
	    'CY' => 'Cyprus',
	    'CZ' => 'Czech Republic',
	    'CD' => 'Democratic Republic of the Congo',
	    'DK' => 'Denmark',
	    'DJ' => 'Djibouti',
	    'DM' => 'Dominica',
	    'DO' => 'Dominican Republic',
	    'EC' => 'Ecuador',
	    'SV' => 'El Salvador',
	    'ER' => 'Eritrea',
	    'EE' => 'Estonia',
	    'ET' => 'Ethiopia',
	    'FK' => 'Falkland Islands',
	    'FO' => 'Faroe Islands',
	    'FJ' => 'Fiji',
	    'FI' => 'Finland',
	    'FR' => 'France',
	    'GF' => 'French Guiana',
	    'PF' => 'French Polynesia',
	    'GA' => 'Gabon Republic',
	    'GM' => 'Gambia',
	    'DE' => 'Germany',
	    'GI' => 'Gibraltar',
	    'GR' => 'Greece',
	    'GL' => 'Greenland',
	    'GD' => 'Grenada',
	    'GP' => 'Guadeloupe',
	    'GT' => 'Guatemala',
	    'GN' => 'Guinea',
	    'GW' => 'Guinea Bissau',
	    'GY' => 'Guyana',
	    'HN' => 'Honduras',
	    'HK' => 'Hong Kong',
	    'HU' => 'Hungary',
	    'IS' => 'Iceland',
	    'IN' => 'India',
	    'ID' => 'Indonesia',
	    'IE' => 'Ireland',
	    'IL' => 'Israel',
	    'IT' => 'Italy',
	    'JM' => 'Jamaica',
	    'JP' => 'Japan',
	    'JO' => 'Jordan',
	    'KZ' => 'Kazakhstan',
	    'KE' => 'Kenya',
	    'KI' => 'Kiribati',
	    'KW' => 'Kuwait',
	    'KG' => 'Kyrgyzstan',
	    'LA' => 'Laos',
	    'LV' => 'Latvia',
	    'LS' => 'Lesotho',
	    'LI' => 'Liechtenstein',
	    'LT' => 'Lithuania',
	    'LU' => 'Luxembourg',
	    'MG' => 'Madagascar',
	    'MW' => 'Malawi',
	    'MY' => 'Malaysia',
	    'MV' => 'Maldives',
	    'ML' => 'Mali',
	    'MT' => 'Malta',
	    'MH' => 'Marshall Islands',
	    'MQ' => 'Martinique',
	    'MR' => 'Mauritania',
	    'MU' => 'Mauritius',
	    'YT' => 'Mayotte',
	    'MX' => 'Mexico',
	    'FM' => 'Micronesia',
	    'MN' => 'Mongolia',
	    'MS' => 'Montserrat',
	    'MA' => 'Morocco',
	    'MZ' => 'Mozambique',
	    'NA' => 'Namibia',
	    'NR' => 'Nauru',
	    'NP' => 'Nepal',
	    'NL' => 'Netherlands',
	    'AN' => 'Netherlands Antilles',
	    'NC' => 'New Caledonia',
	    'NZ' => 'New Zealand',
	    'NI' => 'Nicaragua',
	    'NE' => 'Niger',
	    'NU' => 'Niue',
	    'NF' => 'Norfolk Island',
	    'NO' => 'Norway',
	    'OM' => 'Oman',
	    'PW' => 'Palau',
	    'PA' => 'Panama',
	    'PG' => 'Papua New Guinea',
	    'PE' => 'Peru',
	    'PH' => 'Philippines',
	    'PN' => 'Pitcairn Islands',
	    'PL' => 'Poland',
	    'PT' => 'Portugal',
	    'QA' => 'Qatar',
	    'CG' => 'Republic of the Congo',
	    'RE' => 'Reunion',
	    'RO' => 'Romania',
	    'RU' => 'Russia',
	    'RW' => 'Rwanda',
	    'KN' => 'Saint Kitts and Nevis Anguilla',
	    'PM' => 'Saint Pierre and Miquelon',
	    'VC' => 'Saint Vincent and Grenadines',
	    'WS' => 'Samoa',
	    'SM' => 'San Marino',
	    'ST' => 'São Tomé and Príncipe',
	    'SA' => 'Saudi Arabia',
	    'SN' => 'Senegal',
	    'SC' => 'Seychelles',
	    'SL' => 'Sierra Leone',
	    'SG' => 'Singapore',
	    'SK' => 'Slovakia',
	    'SI' => 'Slovenia',
	    'SB' => 'Solomon Islands',
	    'SO' => 'Somalia',
	    'ZA' => 'South Africa',
	    'KR' => 'South Korea',
	    'ES' => 'Spain',
	    'LK' => 'Sri Lanka',
	    'SH' => 'St. Helena',
	    'LC' => 'St. Lucia',
	    'SR' => 'Suriname',
	    'SJ' => 'Svalbard and Jan Mayen Islands',
	    'SZ' => 'Swaziland',
	    'SE' => 'Sweden',
	    'CH' => 'Switzerland',
	    'TW' => 'Taiwan',
	    'TJ' => 'Tajikistan',
	    'TZ' => 'Tanzania',
	    'TH' => 'Thailand',
	    'TG' => 'Togo',
	    'TO' => 'Tonga',
	    'TT' => 'Trinidad and Tobago',
	    'TN' => 'Tunisia',
	    'TR' => 'Turkey',
	    'TM' => 'Turkmenistan',
	    'TC' => 'Turks and Caicos Islands',
	    'TV' => 'Tuvalu',
	    'UG' => 'Uganda',
	    'UA' => 'Ukraine',
	    'AE' => 'United Arab Emirates',
	    'GB' => 'United Kingdom',
	    'UY' => 'Uruguay',
	    'VU' => 'Vanuatu',
	    'VA' => 'Vatican City State',
	    'VE' => 'Venezuela',
	    'VN' => 'Vietnam',
	    'VG' => 'Virgin Islands (British)',
	    'WF' => 'Wallis and Futuna Islands',
	    'YE' => 'Yemen',
	    'ZM' => 'Zambia',
	);

	$options[] ='<option value="">' . __('-- Choose a Country --', 'AWPCP') . '</option>';

	foreach ($countries as $code => $name) {
		if ($use_names) {
			$selected = $value == $name ? ' selected="selected"' : '';
			$options[] = sprintf('<option value="%s"%s>%s</option>', $name, $selected, $name);
		} else {
			$selected = $value == $code ? ' selected="selected"' : '';
			$options[] = sprintf('<option value="%s"%s>%s</option>', $code, $selected, $name);
		}
	}

	return join('', $options);
}

function awpcp_state_list_options() {
	return '<option selected value="">-- Choose a State --</option>
	<option value="ZZ">None</option>
	<optgroup label="United States">
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
	</optgroup>
	<optgroup label="Canada">
	<option value="AB">Alberta</option>
	<option value="BC">British Columbia</option>
	<option value="MB">Manitoba</option>
	<option value="NB">New Brunswick</option>
	<option value="NF">Newfoundland and Labrador</option>
	<option value="NT">Northwest Territories</option>
	<option value="NS">Nova Scotia</option>
	<option value="NU">Nunavut</option>
	<option value="ON">Ontario</option>
	<option value="PE">Prince Edward Island</option>
	<option value="PQ">Quebec</option>
	<option value="SK">Saskatchewan</option>
	<option value="YT">Yukon Territory</option>
	</optgroup>
	<optgroup label="Australia">
	<option value="AC">Australian Capital Territory</option>
	<option value="NW">New South Wales</option>
	<option value="NO">Northern Territory</option>
	<option value="QL">Queensland</option>
	<option value="SA">South Australia</option>
	<option value="TS">Tasmania</option>
	<option value="VC">Victoria</option>
	<option value="WS">Western Australia</option>
	</optgroup>';
}


/**
 * AWPCP misc functions
 *
 * TODO: merge content from functions_awpcp.php,
 * fileop.class.php, dcfunctions.php, upload_awpcp.php
 * as needed.
 */


/**
 * Return number of allowed images for an Ad, according to its
 * Ad ID or Fee Term ID.
 *
 * @param $ad_id 		int 	Ad ID.
 * @param $ad_term_id 	int 	Ad Term ID.
 */
function awpcp_get_ad_number_allowed_images($ad_id) {
	$ad = AWPCP_Ad::find_by_id($ad_id);

	if (is_null($ad)) {
		return 0;
	}

	$payment_term = $ad->get_payment_term();

	if ( ! is_null( $payment_term ) ) {
		$allowed = $payment_term->images;
	} else {
		$allowed = get_awpcp_option('imagesallowedfree');
	}

	return $allowed;
}


/**
 * @deprecated since 3.0.2 - use awpcp_media_api()->find_images_by_ad_id() instead
 */
function awpcp_get_ad_images( $ad_id ) {
	_deprecated_function( __FUNCTION__, '3.0.2', 'awpcp_media_api()->find_images_by_ad_id()' );

	global $wpdb;

	$query = "SELECT * FROM " . AWPCP_TABLE_ADPHOTOS . " ";
	$query.= "WHERE ad_id=%d ORDER BY image_name ASC";

	return $wpdb->get_results($wpdb->prepare($query, $ad_id));
}

/**
 * @deprecated 3.0.2 use $media->get_url()
 */
function awpcp_get_image_url($image, $suffix='') {
	_deprecated_function( __FUNCTION__, '3.0.2', 'AWPCP_Media::get_url()' );

	static $uploads = array();

	if ( empty( $uploads ) ) {
		$uploads = awpcp_setup_uploads_dir();
		$uploads = array_shift( $uploads );
	}

	$images = trailingslashit(AWPCPUPLOADURL);
	$thumbnails = trailingslashit(AWPCPTHUMBSUPLOADURL);

	if (is_object($image))
		$basename = $image->image_name;
	if (is_string($image))
		$basename = $image;

	$original = $images . $basename;
	$thumbnail = $thumbnails . $basename;
	$part = empty($suffix) ? '.' : "-$suffix.";

	$info = pathinfo($original);

	if ($suffix == 'original') {
		$alternatives = array($original);
	} else if ($suffix == 'large') {
		$alternatives = array(
			str_replace(".{$info['extension']}", "$part{$info['extension']}", $original),
			$original
		);
	} else {
		$alternatives = array(
			str_replace(".{$info['extension']}", "$part{$info['extension']}", $thumbnail),
			$thumbnail,
			$original
		);
	}

	foreach ($alternatives as $imagepath) {
		if (file_exists(str_replace(AWPCPUPLOADURL, $uploads, $imagepath))) {
			return $imagepath;
		}
	}

	return false;
}


/**
 *
 * @deprecated use awpcp_media_api()->set_ad_primary_image()
 */
function awpcp_set_ad_primary_image($ad_id, $image_id) {
	global $wpdb;

	$query = 'UPDATE ' . AWPCP_TABLE_ADPHOTOS . ' ';
	$query.= "SET is_primary = 0 WHERE ad_id = %d";

	if ($wpdb->query($wpdb->prepare($query, $ad_id)) === false)
		return false;

	$query = 'UPDATE ' . AWPCP_TABLE_ADPHOTOS . ' ';
	$query.= 'SET is_primary = 1 WHERE ad_id = %d AND key_id = %d';
	$query = $wpdb->prepare($query, $ad_id, $image_id);

	return $wpdb->query($query) !== false;
}


/**
 * Get the primary image of the given Ad.
 *
 * @param  int	$ad_id	Ad's ID
 * @return object	an StdClass object representing an image
 * @deprecated use awpcp_media_api()->get_ad_primary_image()
 */
function awpcp_get_ad_primary_image($ad_id) {
	global $wpdb;

	$query = 'SELECT * FROM ' . AWPCP_TABLE_ADPHOTOS . ' ';
	$query.= 'WHERE ad_id = %d AND is_primary = 1 AND disabled = 0';

	$results = $wpdb->get_results($wpdb->prepare($query, $ad_id));

	if (!empty($results)) return $results[0];

	$query = 'SELECT * FROM ' . AWPCP_TABLE_ADPHOTOS . ' ';
	$query.= 'WHERE ad_id = %d AND disabled = 0 ORDER BY key_id LIMIT 0,1';

	$results = $wpdb->get_results($wpdb->prepare($query, $ad_id));

	return empty($results) ? null : $results[0];
}


function awpcp_array_insert($array, $index, $key, $item, $where='before') {
	$all = array_merge($array, array($key => $item));
	$keys = array_keys($array);
	$p = array_search($index, $keys);

	if ($p !== FALSE) {
		if ($where === 'before')
			array_splice($keys, max($p, 0), 0, $key);
		else if ($where === 'after')
			array_splice($keys, min($p+1, count($keys)), 0, $key);

		$array = array();
		// create items array in proper order.
		// the code below was the only way I find to insert an
		// item in an arbitrary position of an array preserving
		// keys. array_splice dropped the key of the inserted
		// value.
		foreach($keys as $key) {
			$array[$key] = $all[$key];
		}
	}

	return $array;
}

function awpcp_array_insert_before($array, $index, $key, $item) {
	return awpcp_array_insert($array, $index, $key, $item, 'before');
}

function awpcp_array_insert_after($array, $index, $key, $item) {
	return awpcp_array_insert($array, $index, $key, $item, 'after');
}

/**
 * Inserts a menu item after one of the existing items.
 *
 * This function should be used by plugins when handling
 * the awpcp_menu_items filter.
 *
 * @param $items 	array 	Existing items
 * @param $after 	string 	key of item we want to place the new
 * 							item after
 * @param $key 		string 	New item's key
 * @param $item 	array 	New item's description
 */
function awpcp_insert_menu_item($items, $after, $key, $item) {
	return awpcp_array_insert_after($items, $after, $key, $item);
}

/**
 * Insert a submenu item in a WordPress admin menu, after an
 * existing item.
 *
 * Menu item should have already been added using add_submenu_page
 * or a similar function.
 *
 * @param $slug		string	Slug for the item to insert.
 * @param $after	string	Slug of the item to insert after.
 */
function awpcp_insert_submenu_item_after($menu, $slug, $after) {
    global $submenu;

    $items = isset($submenu[$menu]) ? $submenu[$menu] : array();
    $to = -1; $from = -1;

    foreach ($items as $k => $item) {
        // insert after Fees
        if (strcmp($item[2], $after) === 0)
            $to = $k;
        if (strcmp($item[2], $slug) === 0)
            $from = $k;
    }

    if ($to >= 0 && $from >= 0) {
        array_splice($items, $to + 1, 0, array($items[$from]));
        // current was added at the end of the array using add_submenu_page
        unset($items[$from + 1]);
        // use array_filter to restore array keys
        $submenu[$menu] = array_filter($items);
    }
}


/**
 * Check if the page identified by $refname exists.
 */
function awpcp_find_page($refname) {
	global $wpdb;

	$query = 'SELECT posts.ID, page FROM ' . $wpdb->posts . ' AS posts ';
	$query.= 'LEFT JOIN ' . AWPCP_TABLE_PAGES . ' AS pages ';
	$query.= 'ON (posts.ID = pages.id) WHERE pages.page = %s';

	$query = $wpdb->prepare($query, $refname);
	$pages = $wpdb->get_results($query);

	return $pages !== false && !empty($pages);
}

/**
 * Return name of current AWPCP page.
 *
 * This is part of an effor to put all AWPCP functions under
 * the same namespace.
 */
function awpcp_get_main_page_name() {
	return get_awpcp_option('main-page-name');
}

/**
 * Always return the full URL, even if AWPCP main page
 * is also the home page.
 */
function awpcp_get_main_page_url() {
	$id = awpcp_get_page_id_by_ref('main-page-name');

	if (get_option('permalink_structure')) {
		$url = home_url(get_page_uri($id));
	} else {
		$url = add_query_arg('page_id', $id, home_url());
	}

	return user_trailingslashit($url);
}

/**
 * @since 2.1.4
 */
function awpcp_get_page_name($pagename) {
	return get_awpcp_option($pagename);
}

/**
 * Returns a link to an AWPCP page identified by $pagename.
 *
 * Always return the full URL, even if the page is set as
 * the homepage.
 *
 * The returned URL has no trailing slash. However, if the
 * $trailinghslashit parameter is set to true, the returned URL
 * will be passed through user_trailingslashit() function.
 *
 * If permalinks are disabled, the home url will have
 * a trailing slash.
 *
 * @since 2.0.7
 */
function awpcp_get_page_url($pagename, $trailingslashit=false) {
	global $wp_rewrite;

	$id = awpcp_get_page_id_by_ref($pagename);

	if (get_option('permalink_structure')) {
		$permalink = $wp_rewrite->get_page_permastruct();
		$permalink = str_replace( '%pagename%', get_page_uri( $id ), $permalink );

		$url = home_url( $permalink );
		$url = $trailingslashit ? user_trailingslashit( $url ) : rtrim($url, '/');
	} else {
		$url = add_query_arg( 'page_id', $id, home_url('/') );
	}

	return $url;
}

/**
 * @since 3.0.2
 */
function awpcp_get_view_categories_url() {
    $permalinks = get_option('permalink_structure');
    $main_page_id = awpcp_get_page_id_by_ref('main-page-name');
    $page_name = get_awpcp_option('view-categories-page-name');
    $slug = sanitize_title($page_name);

    if ( !empty( $permalinks ) ) {
        $url = sprintf( '%s/%s', trim( home_url( get_page_uri( $main_page_id ) ), '/' ), $slug );
        $url = user_trailingslashit( $url );
    } else {
        $url = add_query_arg( array( 'page_id' => $main_page_id, 'layout' => 2 ), home_url('/') );
    }

    return $url;
}


/**
 * Returns a link that can be used to initiate the Ad Renewal process.
 *
 * @since 2.0.7
 */
function awpcp_get_renew_ad_url($ad_id) {
	$hash = awpcp_get_renew_ad_hash( $ad_id );
	if ( get_awpcp_option( 'enable-user-panel' ) == 1 ) {
		$url = awpcp_get_user_panel_url();
		$url = add_query_arg( array( 'id' => $ad_id, 'action' => 'renew', 'awpcprah' => $hash ), $url );
	} else {
		$url = awpcp_get_page_url('renew-ad-page-name');
		$url = add_query_arg( array( 'ad_id' => $ad_id, 'awpcprah' => $hash ), $url );
	}

	return $url;
}

/**
 * @since 3.0.2
 */
function awpcp_get_renew_ad_hash( $ad_id ) {
	return md5( sprintf( 'renew-ad-%d-%s', $ad_id, wp_salt() ) );
}

/**
 * @since 3.0.2
 */
function awpcp_verify_renew_ad_hash( $ad_id, $hash ) {
	return strcmp( awpcp_get_renew_ad_hash( $ad_id ), $hash ) === 0;
}

/**
 * @since 3.0.2
 */
function awpcp_get_email_verification_url( $ad_id ) {
	$hash = awpcp_get_email_verification_hash( $ad_id );

    if ( get_option( 'permalink_structure' ) ) {
        return home_url( "/awpcpx/listings/verify/{$ad_id}/$hash" );
    } else {
        $params = array(
            'awpcpx' => true,
            'module' => 'listings',
            'action' => 'verify',
            'awpcp-ad' => $ad_id,
            'awpcp-hash' => $hash,
        );

        return add_query_arg( $params, home_url( 'index.php' ) );
    }

	return user_trailingslashit( $url );
}

/**
 * @since 3.0.2
 */
function awpcp_get_email_verification_hash( $ad_id ) {
	return wp_hash( sprintf( 'verify-%d', $ad_id ) );
}

/**
 * @since 3.0.2
 */
function awpcp_verify_email_verification_hash( $ad_id, $hash ) {
	return strcmp( awpcp_get_email_verification_hash( $ad_id ) , $hash ) === 0;
}

/**
 * Returns a link to the page where visitors can contact the Ad's owner
 *
 * @since  3.0.0
 */
function awpcp_get_reply_to_ad_url($ad_id, $ad_title=null) {
	$base_url = awpcp_get_page_url('reply-to-ad-page-name');
	$permalinks = get_option('permalink_structure');
	$url = false;

	if (!is_null($ad_title)) {
		$title = sanitize_title($ad_title);
	} else {
		$title = sanitize_title(AWPCP_Ad::find_by_id($ad_id)->ad_title);
	}

	if (get_awpcp_option('seofriendlyurls')) {
		if (get_option('permalink_structure')) {
			$url = sprintf("%s/%s/%s", $base_url, $ad_id, $title);
			$url = user_trailingslashit($url);
		}
	}

	if ($url === false) {
		$base_url = user_trailingslashit($base_url);
		$url = add_query_arg(array('i' => $ad_id), $base_url);
	}

	return $url;
}

/**
 * @since  3.0
 */
function awpcp_get_admin_panel_url() {
	return add_query_arg( 'page', 'awpcp.php', admin_url('admin.php'));
}

/**
 * @since 3.0.2
 */
function awpcp_get_admin_settings_url( $section = false ) {
	return add_query_arg( array( 'page' => 'awpcp-admin-settings', 'g' => $section ), admin_url( 'admin.php' ) );
}

/**
 * @since 3.2.1
 */
function awpcp_get_admin_credit_plans_url() {
	return add_query_arg( 'page', 'awpcp-admin-credit-plans', admin_url( 'admin.php' ) );
}

/**
 * @since 3.2.1
 */
function awpcp_get_admin_fees_url() {
	return add_query_arg( 'page', 'awpcp-admin-fees', admin_url( 'admin.php' ) );
}

/**
 * @since 3.0.2
 */
function awpcp_get_admin_categories_url() {
	return add_query_arg( 'page', 'awpcp-admin-categories', admin_url( 'admin.php' ) );
}

/**
 * @since  3.0
 */
function awpcp_get_admin_upgrade_url() {
	return add_query_arg( 'page', 'awpcp-admin-upgrade', admin_url('admin.php'));
}

/**
 * Returns a link to Manage Listings
 *
 * @since 2.1.4
 */
function awpcp_get_admin_listings_url() {
	return admin_url('admin.php?page=awpcp-listings');
}

/**
 * Returns a link to Ad Management (a.k.a User Panel).
 *
 * @since 2.0.7
 */
function awpcp_get_user_panel_url( $params=array() ) {
	return add_query_arg( $params, admin_url( 'admin.php?page=awpcp-panel' ) );
}


function awpcp_current_url() {
	return (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Returns the domain used in the current request, optionally stripping
 * the www part of the domain.
 *
 * @since 2.0.6
 * @param $www 	boolean		true to include the 'www' part,
 *							false to attempt to strip it.
 */
function awpcp_get_current_domain($www=true, $prefix='') {
	$domain = awpcp_array_data('HTTP_HOST', '', $_SERVER);
	if (empty($domain)) {
		$domain = awpcp_array_data('SERVER_NAME', '', $_SERVER);
	}

	if (!$www && substr($domain, 0, 4) === 'www.') {
		$domain = $prefix . substr($domain, 4);
	}

	return $domain;
}

/**
 * Bulds WordPress ajax URL using the same domain used in the current request.
 *
 * @since 2.0.6
 */
function awpcp_ajaxurl($overwrite=false) {
	static $ajaxurl = false;

	if ($overwrite || $ajaxurl === false) {
		$url = admin_url('admin-ajax.php');
		$parts = parse_url($url);
		$ajaxurl = str_replace($parts['host'], awpcp_get_current_domain(), $url);
	}

	return $ajaxurl;
}

/**
 * @since 3.0-beta
 */
function awpcp_get_blog_name($decode_html=true) {
	$blog_name = get_option('blogname');

	if (empty($blog_name)) {
		$blog_name = _x('Classifieds Website', 'default blog title', 'AWPCP');
	}

	if ( $decode_html ) {
		$blog_name = html_entity_decode( $blog_name, ENT_QUOTES, 'UTF-8' );
	}

	return $blog_name;
}

/**
 * Use AWPCP_Request::post_param when possible.
 */
function awpcp_post_param($name, $default='') {
	return awpcp_array_data($name, $default, $_POST);
}

/**
 * Use AWPCP_Request::param when possible.
 */
function awpcp_request_param($name, $default='', $from=null) {
	return awpcp_array_data($name, $default, is_null($from) ? $_REQUEST : $from);
}

function awpcp_array_data($name, $default, $from=array()) {
	$value = isset($from[$name]) ? $from[$name] : null;

	if (is_array($value) && count($value) > 0) {
		return $value;
	} else if (!empty($value)) {
		return $value;
	}

	return $default;
}

function awpcp_get_property($object, $property, $default='') {
    if (is_object($object) && (isset($object->$property) ||
    	array_key_exists($property, get_object_vars($object)))) {
        return $object->$property;
    }
    return $default;
}

function awpcp_get_properties($objects, $property, $default='') {
	$results = array();
	foreach ($objects as $object) {
		$results[] = awpcp_get_property($object, $property, $default);
	}
	return $results;
}

/**
 * Input:
 *  Array
 *  (
 *      [a] => dosearch
 *      [keywordphrase] =>
 *      [searchcategory] =>
 *      [searchname] =>
 *      [searchpricemin] => 0
 *      [searchpricemax] => 0
 *      [regions] => Array
 *          (
 *              [0] => Array
 *                  (
 *                      [country] => Colombia
 *                      [state] => Boyacá
 *                      [city] => Tunja
 *                  )
 *
 *              [1] => Array
 *                  (
 *                      [country] => Colombia
 *                      [state] => Antioquia
 *                      [city] => Medellín
 *                  )
 *
 *              [2] => Array
 *                  (
 *                      [country] => Colombia
 *                      [state] => Boyacá
 *                      [city] => Tunja
 *                  )
 *
 *          )
 *
 *      [awpcp-test-min] =>
 *      [awpcp-test-max] =>
 *      [awpcp-select_list] =>
 *  )
 *
 * Output:
 * Array
 * (
 *      [a] => dosearch
 *      [keywordphrase] =>
 *      [searchcategory] =>
 *      [searchname] =>
 *      [searchpricemin] => 0
 *      [searchpricemax] => 0
 *      [regions[0][country]] => Colombia
 *      [regions[0][state]] => Boyacá
 *      [regions[0][city]] => Tunja
 *      [regions[1][country]] => Colombia
 *      [regions[1][state]] => Antioquia
 *      [regions[1][city]] => Medellín
 *      [regions[2][country]] => Colombia
 *      [regions[2][state]] => Boyacá
 *      [regions[2][city]] => Tunja
 *      [awpcp-test-min] =>
 *      [awpcp-test-max] =>
 *      [awpcp-select_list] =>
 * )
 * TODO: see WP's _http_build_query
 *
 * @since 3.0.2
 */
function awpcp_flatten_array($array) {
	if ( is_array( $array ) ) {
		$flat = array();
		_awpcp_flatten_array( $array, array(), $flat );
		return $flat;
	} else {
		return $array;
	}
}

/**
 * @since 3.0.2
 */
function _awpcp_flatten_array($array, $path=array(), &$return=array()) {
	if ( is_array( $array ) ) {
		foreach ( $array as $key => $value) {
			_awpcp_flatten_array( $value, array_merge( $path, array( $key ) ), $return );
		}
	} else if ( count( $path ) > 0 ){
		$first = $path[0];
		if ( count( $path ) > 1 ) {
			$return[ $first . '[' . join('][', array_slice( $path, 1 ) ) . ']'] = $array;
		} else {
			$return[ $first ] = $array;
		}
	}
}


/**
 * Parses 'yes', 'true', 'no', 'false', 0, 1 into bool values.
 *
 * @since  2.1.3
 * @param  mixed	$value	value to parse
 * @return bool
 */
function awpcp_parse_bool($value) {
	$lower = strtolower($value);
	if ($lower === 'true' || $lower === 'yes')
		return true;
	if ($lower === 'false' || $lower === 'no')
		return false;
	return $value ? true : false;
}


/**
 * XXX: Referenced in FAQ: http://awpcp.com/forum/faq/why-doesnt-my-currency-code-change-when-i-set-it/
 */
function awpcp_get_currency_symbol() {
	$dollar = array('CAD', 'AUD', 'NZD', 'SGD', 'HKD', 'USD');
	$code = get_awpcp_option('displaycurrencycode');

	if (in_array($code, $dollar)) {
		$symbol = "$";
	}

	if (($code == 'JPY')) {
		$symbol = "&yen;";
	}

	if (($code == 'EUR')) {
		$symbol = "&euro;";
	}

	if (($code == 'GBP')) {
		$symbol = "&pound;";
	}

	return empty($symbol) ? $code : $symbol;
}


/**
 * @since 3.0
 */
function awpcp_format_money($value, $include_symbol=true) {
	$thousands_separator = get_awpcp_option('thousands-separator');
	$decimal_separator = get_awpcp_option('decimal-separator');
	$decimals = get_awpcp_option('show-decimals') ? 2 : 0;
	$symbol = $include_symbol ? awpcp_get_currency_symbol() : '';

	if ($value >= 0) {
		$number = number_format($value, $decimals, $decimal_separator, $thousands_separator);
		return sprintf('%s%s', $symbol, $number);
	} else {
		$number = number_format(- $value, $decimals, $decimal_separator, $thousands_separator);
		return sprintf('(%s%s)', $symbol, $number);
	}
}

/**
 * @since 3.0
 */
function awpcp_parse_money($value, $decimal_separator=false, $thousands_separator=false) {
	if ( strlen( $value ) === 0 ) return false;

	$thousands_separator = $thousands_separator ? $thousands_separator : get_awpcp_option('thousands-separator');
	$decimal_separator = $decimal_separator ? $decimal_separator : get_awpcp_option('decimal-separator');

	$pattern = '/^-?(?:\d+|\d{1,3}(?:' . preg_quote( $thousands_separator ) . '\\d{3})+)?(?:' . preg_quote( $decimal_separator ) . '\\d+)?$/';

	if ( preg_match( $pattern, $value ) ) {
		$value = str_replace($thousands_separator, '', $value);
		$value = str_replace($decimal_separator, '.', $value);
		$number = floatval($value);
	} else {
		$number = false;
	}

	return $number;
}


/**
 * @since 2.1.4
 */
function awpcp_get_flash_messages() {
	if (is_user_logged_in()) {
		if ($messages = get_user_option('awpcp-messages', get_current_user_id())) {
			return $messages;
		}
		return array();
	} else if (isset($_COOKIE['awpcp-messages'])) {
		return get_option('awpcp-messages-' . $_COOKIE['awpcp-messages'], array());
	} else {
		return array();
	}
}

/**
 * @since 2.1.4
 */
function awpcp_update_flash_messages($messages) {
	if (is_user_logged_in()) {
		return update_user_option(get_current_user_id(), 'awpcp-messages', $messages);
	} else {
		if (!isset($_COOKIE['awpcp-messages']))
			$_COOKIE['awpcp-messages'] = uniqid();
		return update_option('awpcp-messages-' . $_COOKIE['awpcp-messages'], $messages);
	}
}

/**
 * @since 2.1.4
 */
function awpcp_clear_flash_messages() {
	if (is_user_logged_in()) {
		return delete_user_option(get_current_user_id(), 'awpcp-messages');
	} else if (isset($_COOKIE['awpcp-messages'])) {
		return delete_option('awpcp-messages-' . $_COOKIE['awpcp-messages']);
	}
	return true;
}

function awpcp_flash($message, $class='updated') {
	$messages = awpcp_get_flash_messages();
	$messages[] = array('message' => $message, 'class' => (array) $class);
	awpcp_update_flash_messages($messages);
}

/**
 */
function awpcp_print_messages() {
 	// The function is expected to be called only once per request. However,
 	// due to special circumstances it is possible that the function is called
 	// twice or more, usually with the results from the last call being the ones
 	// shown to the user. In those cases, the messages would be lost unless we
 	// cache the messages during the request. That's why we use a static $messages
 	// variable.
	static $messages = null;
	$messages = is_null($messages) ? awpcp_get_flash_messages() : $messages;

	foreach ($messages as $message) {
		echo awpcp_print_message($message['message'], $message['class']);
	}

	awpcp_clear_flash_messages();
}

function awpcp_print_message($message, $class=array('updated')) {
	$class = array_merge(array('awpcp-message'), $class);
	return '<div class="' . join(' ', $class) . '"><p>' . $message . '</p></div>';
}

function awpcp_print_error($message) {
	return awpcp_print_message($message, array('error'));
}


function awpcp_validate_error($field, $errors) {
	$error = awpcp_array_data($field, '', $errors);
	if (empty($error))
		return '';
	return '<label for="' . $field . '" generated="true" class="error" style="">' . $error . '</label>';
}

function awpcp_form_error($field, $errors) {
	$error = awpcp_array_data($field, '', $errors);
	return empty($error) ? '' : '<span class="awpcp-error">' . $error . '</span>';
}

function awpcp_attachment_background_color_explanation() {
	if ( get_awpcp_option( 'imagesapprove' ) ) {
		return '<p>' . _x( 'The images or files with pale red background have been rejected by an administrator user. Likewise, files with a pale yellow background are awaiting approval. Files that are awaiting approval and rejected files, cannot be shown in the frontend.', 'AWPCP' ) . '</p>';
	} else {
		return '';
	}
}

/**
 * @since 3.0.2
 */
function awpcp_module_not_compatible_notice( $module, $installed_version ) {
	global $awpcp_db_version;

	$modules = awpcp()->get_premium_modules_information();

	$name = $modules[ $module ][ 'name' ];
	$required_version = $modules[ $module ][ 'required' ];

	$message = __( 'This version of AWPCP %1$s module is not compatible with AWPCP version %2$s. Please get AWPCP %1$s %3$s or newer!', 'AWPCP' );
	$message = sprintf( $message, '<strong>' . $name . '</strong>', $awpcp_db_version, '<strong>' . $required_version . '</strong>' );
    $message = sprintf( '<strong>%s:</strong> %s', __( 'Error', 'AWPCP' ), $message );

    return awpcp_print_error( $message );
}


function awpcp_render_attributes($attrs) {
    $attributes = array();
    foreach ($attrs as $name => $value) {
        if (is_array($value))
            $value = join(' ', array_filter($value, 'strlen'));
        $attributes[] = sprintf('%s="%s"', $name, esc_attr($value));
    }
    return join(' ', $attributes);
}


function awpcp_uploaded_file_error($file) {
	$upload_errors = array(
		UPLOAD_ERR_OK        	=> __("No errors.", 'AWPCP'),
		UPLOAD_ERR_INI_SIZE    	=> __("The file is larger than upload_max_filesize.", 'AWPCP'),
		UPLOAD_ERR_FORM_SIZE    => __("The file is larger than form MAX_FILE_SIZE.", 'AWPCP'),
		UPLOAD_ERR_PARTIAL    	=> __("The file was only partially uploaded.", 'AWPCP'),
		UPLOAD_ERR_NO_FILE      => __("No file was uploaded.", 'AWPCP'),
		UPLOAD_ERR_NO_TMP_DIR   => __("Missing temporary directory.", 'AWPCP'),
		UPLOAD_ERR_CANT_WRITE   => __("Can't write file to disk.", 'AWPCP'),
		UPLOAD_ERR_EXTENSION    => __("The file upload was stopped by extension.", 'AWPCP')
	);

	return array($file['error'], $upload_errors[$file['error']]);
}

function awpcp_get_file_extension( $filename ) {
	return pathinfo( $filename, PATHINFO_EXTENSION );
}

/**
 * Recursively remove a directory.
 * @since 3.0.2
 */
function awpcp_rmdir($dir) {
	if ( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != "." && $object != ".." ) {
				if ( filetype( $dir . "/" . $object ) == "dir" ) {
					awpcp_rmdir( $dir . "/" . $object );
				} else {
					unlink( $dir . "/" . $object );
				}
			}
		}
		reset( $objects );
		rmdir( $dir );
	}
}


/**
 * @since 3.0.2
 */
function awpcp_directory_permissions() {
	return intval( get_awpcp_option( 'upload-directory-permissions', '0755' ), 8 );
}


/**
 * @since 2.0.7
 */
function awpcp_table_exists($table) {
    global $wpdb;
    $result = $wpdb->get_var("SHOW TABLES LIKE '" . $table . "'");
    return strcasecmp($result, $table) === 0;
}

/**
 * @since  2.1.4
 */
function awpcp_column_exists($table, $column) {
    global $wpdb;
    $suppress_errors = $wpdb->suppress_errors();
    $result = $wpdb->query("SELECT `$column` FROM $table");
    $wpdb->suppress_errors( $suppress_errors );
    return $result !== false;
}


/** Email functions
 ---------------------------------------------------------------------------- */

/**
 * Extracted from class-phpmailer.php (PHPMailer::EncodeHeader).
 *
 * @since 3.0.2
 */
function awpcp_encode_address_name($str) {
	if ( !preg_match('/[\200-\377]/', $str ) ) {
		// Can't use addslashes as we don't know what value has magic_quotes_sybase
		$encoded = addcslashes( $str, "\0..\37\177\\\"" );
		if ( ( $str == $encoded) && !preg_match( '/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str ) ) {
			return $encoded;
		} else {
			return "\"$encoded\"";
		}
	}

	return $str;
}

/**
 * @since 3.0.2
 */
function awpcp_format_email_address($address, $name) {
	return awpcp_encode_address_name( $name ) . " <" . $address . ">";
}

/**
 * Return the email address that should receive the notifications intented for
 * administrator users.
 *
 * @since	3.0
 * @return	string	email address
 */
function awpcp_admin_recipient_email_address() {
	$email_address = get_awpcp_option( 'admin-recipient-email' );
	if ( empty( $email_address ) ) {
		$email_address = get_option( 'admin_email' );
	}

	return $email_address;
}

/**
 * Return the email address used as the sender for email notifications.
 *
 * @since	3.0
 * @return	string	email address
 */
function awpcp_admin_sender_email_address($include_contact_name=false) {
	$email_address = get_awpcp_option( 'awpcpadminemail' );
	if ( empty( $email_address ) ) {
		$email_address = get_option( 'admin_email' );
	}

	return $email_address;
}

/**
 * Return the name and email address of the account that appears as the sender in
 * email notifications.
 *
 * @since	3.0
 * @return	string	name <email@address>
 */
function awpcp_admin_email_from() {
	return awpcp_format_email_address( awpcp_admin_sender_email_address(), awpcp_get_blog_name() );
}

/**
 * Return the name and email address of the account that should receive notifications intented for
 * administrator users.
 *
 * @since	3.0
 * @return	string	name <email@address>
 */
function awpcp_admin_email_to() {
	return awpcp_format_email_address( awpcp_admin_recipient_email_address(), awpcp_get_blog_name() );
}

/**
 * @since  2.1.4
 */
function awpcp_ad_enabled_email($ad) {
	// user email
	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_format_email_address( $ad->ad_contact_email, $ad->ad_contact_name );
	$mail->subject = sprintf(__('Your Ad "%s" has been approved', 'AWPCP'), $ad->get_title());

	$template = AWPCP_DIR . '/frontend/templates/email-ad-enabled-user.tpl.php';
	$mail->prepare($template, compact('ad'));

	$mail->send();
}

/**
 * @since 3.0.2
 */
function awpcp_ad_updated_user_email( $ad, $message ) {
	$admin_email = awpcp_admin_recipient_email_address();

	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_format_email_address( $ad->ad_contact_email, $ad->ad_contact_name );
	$mail->subject = sprintf(__('Your Ad "%s" has been successfully updated', 'AWPCP'), $ad->get_title());

	$template = AWPCP_DIR . '/frontend/templates/email-ad-updated-user.tpl.php';
	$mail->prepare($template, compact('ad', 'message', 'admin_email'));

	return $mail;
}


function awpcp_ad_updated_email( $ad, $message ) {
	// user email
	$mail = awpcp_ad_updated_user_email( $ad, $message );
	return $mail->send();
}

function awpcp_ad_awaiting_approval_email($ad, $ad_approve, $images_approve) {
	// admin email
	$params = array( 'page' => 'awpcp-listings',  'action' => 'manage-images', 'id' => $ad->ad_id );
    $manage_images_url = add_query_arg( $params, admin_url( 'admin.php' ) );

	if ( false == $ad_approve && $images_approve ) {
		$subject = __( 'Images on Ad "%s" are awaiting approval', 'AWPCP' );

		$message = __( 'Images on Ad "%s" are awaiting approval. You can approve the images going to the Manage Images sections for that Ad and clicking the "Enable" button below each image. Click here to continue: %s.', 'AWPCP');
		$messages = array( sprintf( $message, $ad->get_title(), $manage_images_url ) );
	} else {
		$subject = __( 'The Ad "%s" is awaiting approval', 'AWPCP' );

		$message = __('The Ad "%s" is awaiting approval. You can approve the Ad going to the Manage Listings section and clicking the "Enable" action shown on top. Click here to continue: %s.', 'AWPCP');
		$params = array('page' => 'awpcp-listings',  'action' => 'view', 'id' => $ad->ad_id);
	    $url = add_query_arg( $params, admin_url( 'admin.php' ) );

	    $messages[] = sprintf( $message, $ad->get_title(), $url );

	    if ( $images_approve ) {
		    $message = __( 'Additionally, You can approve the images going to the Manage Images sections for that Ad and clicking the "Enable" button below each image. Click here to continue: %s.', 'AWPCP' );
		    $messages[] = sprintf( $message, $manage_images_url );
		}
	}

	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_admin_email_to();
	$mail->subject = sprintf( $subject, $ad->get_title() );

	$template = AWPCP_DIR . '/frontend/templates/email-ad-awaiting-approval-admin.tpl.php';
	$mail->prepare( $template, compact( 'messages' ) );

	$mail->send();
}

/** Table Helper related functions
 ---------------------------------------------------------------------------- */

function awpcp_register_column_headers($screen, $columns, $sortable=array()) {
	$wp_list_table = new AWPCP_List_Table($screen, $columns, $sortable);
}

function awpcp_print_column_headers($screen, $id = true, $sortable=array()) {
	$wp_list_table = new AWPCP_List_Table($screen, array(), $sortable);
	$wp_list_table->print_column_headers($id);
}


/** Temporary solution to avoid breaking inline scripts due to wpauotp and wptexturize
 ---------------------------------------------------------------------------- */

/**
 * @since  2.1.2
 */
function awpcp_inline_javascript_placeholder($name, $script) {
	global $awpcp;

	if (!isset($awpcp->inline_scripts) || !is_array($awpcp->inline_scripts))
		$awpcp->inline_scripts = array();

	$awpcp->inline_scripts[$name] = $script;

	return "<AWPCPScript style='display:none'>$name</AWPCPScript>";
}

/**
 * @since  2.1.2
 */
function awpcp_inline_javascript($content) {
	global $awpcp;

	if (!isset($awpcp->inline_scripts) || !is_array($awpcp->inline_scripts))
		return $content;

	foreach ($awpcp->inline_scripts as $name => $script) {
		$content = preg_replace("{<AWPCPScript style='display:none'>$name</AWPCPScript>}", $script, $content);
	}

	return $content;
}

/**
 * @since  2.1.3
 */
function awpcp_print_inline_javascript() {
	global $awpcp;

	if (!isset($awpcp->inline_scripts) || !is_array($awpcp->inline_scripts))
		return;

	foreach ($awpcp->inline_scripts as $name => $script) {
		echo $script;
	}
}

/**
 * @since 3.2.1
 */
function awpcp_load_plugin_textdomain( $__file__, $text_domain ) {
	if ( get_awpcp_option( 'activatelanguages' ) ) {
		$basename = dirname( plugin_basename( $__file__ ) );

		if ( load_plugin_textdomain( $text_domain, false, $basename . '/languages/' ) ) {
			return true;
		}

		// main l10n MO file can be in the top level directory or inside the
		// languages directory. A file inside the languages directory is prefered.
		if ( $text_domain == 'AWPCP' ) {
			return load_plugin_textdomain( $text_domain, false, $basename );
		}
	}
}

function awpcp_utf8_strlen( $string ) {
	if ( function_exists( 'mb_strlen' ) ) {
		return mb_strlen( $string, 'UTF-8' );
	} else {
		return preg_match_all( '(.)su', $string );
	}
}

function awpcp_utf8_substr( $string, $start, $length=null ) {
	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $string, $start, $length, 'UTF-8' );
	} else {
		return awpcp_utf8_substr_pcre( $string, $start, $length );
	}
}

function awpcp_utf8_substr_pcre( $string, $start, $length=null ) {
	if ( is_null( $length ) ) {
		$length = awpcp_utf8_strlen( $string ) - $start;
	}

	if ( preg_match_all( '/.{' . $start . '}(.{' . $length . '})/su', $string, $matches ) ) {
		return $matches[1][0];
	} else {
		return '';
	}
}
