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
 * @since 3.3
 */
function awpcp_apply_function_deep( $function, $value ) {
    if ( is_array( $value ) ) {
        foreach ( $value as $key => $data ) {
            $value[ $key ] = awpcp_apply_function_deep( $function, $data );
        }
    } elseif ( is_object( $value ) ) {
        $vars = get_object_vars( $value );
        foreach ( $vars as $key => $data ) {
            $value->{$key} = awpcp_apply_function_deep( $function, $data );
        }
    } elseif ( is_string( $value ) ) {
        $value = call_user_func( $function, $value );
    }

    return $value;
}

/**
 * @since 3.3
 */
function awpcp_strip_all_tags_deep( $string ) {
    return awpcp_apply_function_deep( 'wp_strip_all_tags', $string );
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
 * Returns the given date as MySQL date string, Unix timestamp or
 * using a custom format.
 *
 * @since 3.0.2
 * @param $format 	'mysql', 'timestamp', 'awpcp', 'awpcp-date', 'awpcp-time'
 *					  or first arguemnt for date() function.
 */
function awpcp_datetime( $format='mysql', $date=null ) {
	if ( is_null( $date ) || strlen( $date ) === 0 ) {
		$timestamp = current_time( 'timestamp' );
	} else if ( is_string( $date ) ) {
		$timestamp = strtotime( $date );
	} else {
        $timestamp = $date;
    }

	switch ( $format ) {
		case 'mysql':
			return date( 'Y-m-d H:i:s', $timestamp );
		case 'timestamp':
			return $timestamp;
		case 'awpcp':
			return date_i18n( awpcp_get_datetime_format(), $timestamp) ;
		case 'awpcp-date':
			return date_i18n( awpcp_get_date_format(), $timestamp );
		case 'awpcp-time':
			return date_i18n( awpcp_get_time_format(), $timestamp );
		default:
			return date_i18n( $format, $timestamp );
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

function awpcp_extend_date_to_end_of_the_day( $datetime ) {
    $next_day = strtotime( '+ 1 days', $datetime );
    $zero_hours_next_day = strtotime( date( 'Y-m-d', $next_day ) );
    $end_of_the_day = $zero_hours_next_day - 1;

    return $end_of_the_day;
}

function awpcp_is_mysql_date( $date ) {
	$regexp = '/^\d{4}-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/';
	return preg_match( $regexp, $date ) === 1;
}


/**
 * Returns a WP capability required to be considered an AWPCP admin.
 *
 * http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table
 *
 * @since 2.0.7
 */
function awpcp_admin_capability() {
    return 'manage_classifieds';
}

/**
 * @since 3.3.2
 */
function awpcp_admin_roles_names() {
    return awpcp_roles_and_capabilities()->get_administrator_roles_names();
}

/**
 * Check if current user is an Administrator according to
 * AWPCP settings.
 */
function awpcp_current_user_is_admin() {
    return awpcp_roles_and_capabilities()->current_user_is_administrator();
}

/**
 * @since 3.4
 */
function awpcp_current_user_is_moderator() {
    return awpcp_roles_and_capabilities()->current_user_is_moderator();
}


function awpcp_user_is_admin($id) {
    return awpcp_roles_and_capabilities()->user_is_administrator( $id );
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
					   'awpcp_ads_to_action',
					   'post_type');

	$params = array_merge($_GET, $_POST);
	foreach ($blacklist as $param) {
		unset($params[$param]);
	}

	extract(shortcode_atts(array('offset' => 0, 'results' => 10, 'total' => 10), $config));

	$pages = ceil($total / $results);
	$page = floor($offset / $results) + 1;
    $items = array();
    $radius = 5;

    if ( ( $page - $radius ) > 2 ) {
        $items[] = awpcp_render_pagination_item( '&laquo;&laquo;', 1, $results, $params, $url );
    }

    if ( ( $page - $radius ) > 1 ) {
        $items[] = awpcp_render_pagination_item( '&laquo;', $page - $radius - 1, $results, $params, $url );
    }

	for ($i=1; $i <= $pages; $i++) {
        if ( $page == $i ) {
            $items[] = sprintf('%d', $i);
        } else if ( $i < ( $page - $radius ) ) {
            // pass
        } else if ( $i > ( $page + $radius ) ) {
            // pass
        } else {
            $items[] = awpcp_render_pagination_item( $i, $i, $results, $params, $url );
        }
	}

    if ( $page < ( $pages - $radius ) ) {
        $items[] = awpcp_render_pagination_item( '&raquo;', $page + $radius + 1, $results, $params, $url );
    }

    if ( ( $page + $radius ) < ( $pages - 1 ) ) {
        $items[] = awpcp_render_pagination_item( '&raquo;&raquo;', $pages, $results, $params, $url );
    }

	$pagination = implode( '', $items );
	$options = awpcp_pagination_options( $results );

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/listings-pagination.tpl.php');
		$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

function awpcp_render_pagination_item( $label, $page, $results_per_page, $params, $url ) {
    $params = array_merge(
        $params,
        array(
            'offset' => ( $page - 1 ) * $results_per_page,
            'results' => $results_per_page,
        )
    );

    $url = add_query_arg( urlencode_deep( $params ), $url );

    return sprintf( '<a href="%s">%s</a>', esc_url( $url ), $label );
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
function awpcp_region_fields( $context='details', $enabled_fields = null ) {
    $enabled_fields = is_null( $enabled_fields ) ? awpcp_get_enabled_region_fields() : $enabled_fields;

    $fields = apply_filters( 'awpcp-region-fields', false, $context, $enabled_fields );

    if ( false === $fields ) {
    	$fields = awpcp_default_region_fields( $context, $enabled_fields );
    }

    return $fields;
}

/**
 * @since 3.3.1
 */
function awpcp_get_enabled_region_fields() {
    return array(
        'country' => get_awpcp_option( 'displaycountryfield' ),
        'state' => get_awpcp_option( 'displaystatefield' ),
        'city' => get_awpcp_option( 'displaycityfield' ),
        'county' => get_awpcp_option( 'displaycountyvillagefield' ),
    );
}

/**
 * @since 3.0.2
 */
function awpcp_default_region_fields( $context='details', $enabled_fields = null ) {
    $enabled_fields = is_null( $enabled_fields ) ? awpcp_get_enabled_region_fields() : $enabled_fields;
    $show_city_field_before_county_field = get_awpcp_option( 'show-city-field-before-county-field' );

    $always_shown = in_array( $context, array( 'details', 'search' ) );
    $can_be_required = $context !== 'search';
    $_fields = array();

    if ( $enabled_fields['country'] ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaycountryfieldreqop' ) );
        $_fields['country'] = array(
            'type' => 'country',
            'label' => __('Country', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate countries by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $enabled_fields['state'] ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaystatefieldreqop' ) );
        $_fields['state'] = array(
            'type' => 'state',
            'label' => __('State/Province', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate states by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $enabled_fields['city'] ) {
    	$required = $can_be_required && ( (bool) get_awpcp_option( 'displaycityfieldreqop' ) );
        $_fields['city'] = array(
            'type' => 'city',
            'label' => __('City', 'AWPCP') . ( $required ? '*' : '' ),
            'help' => __('separate cities by commas', 'AWPCP'),
            'required' => $required,
            'alwaysShown' => $always_shown,
        );
    }
    if ( $enabled_fields['county'] ) {
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
                $conditions[] = $wpdb->prepare( "{$column} LIKE '%%%s%%'", trim( $value ) );
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

    if ( get_awpcp_option( 'enable-ads-pending-payment' ) == 0 && get_awpcp_option( 'freepay' ) == 1 ) {
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
 * @deprecated since 3.2.3
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

	$info = awpcp_utf8_pathinfo($original);

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
        // Create items array in proper order. The code below was the only
        // way I found to insert an item in an arbitrary position of an
        // array preserving keys. array_splice dropped the key of the inserted
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
 * @since 2.1.4
 */
function awpcp_get_page_name($pagename) {
	return get_awpcp_option($pagename);
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

/**
 * Taken and adapted from: http://stackoverflow.com/a/6795671/201354
 */
function awpcp_array_filter_recursive( $input, $callback = null ) {
    foreach ( $input as &$value ) {
        if ( is_array( $value ) ) {
            $value = awpcp_array_filter_recursive( $value, $callback );
        }
    }

    if ( is_callable( $callback ) ) {
        return array_filter( $input, $callback );
    } else {
        return array_filter( $input );
    }
}

/**
 * Alternative to array_merge_recursive that keeps numeric keys.
 *
 * @since 3.4
 */
function awpcp_array_merge_recursive( $a, $b ) {
    $merged = $a;

    foreach ( $b as $key => $value ) {
        if ( isset( $merged[ $key ] ) && is_array( $merged[$key] ) && is_array( $value ) ) {
            $merged[ $key ] = awpcp_array_merge_recursive( $merged[ $key ], $value );
        } else {
            $merged[ $key ] = $value;
        }
    }

    return $merged;
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

function awpcp_get_object_property_from_alternatives( $object, $alternatives, $default = '' ) {
    foreach ( (array) $alternatives as $key ) {
        $value = awpcp_get_property( $object, $key );

        if ( strlen( $value ) == 0 ) {
            continue;
        }

        return $value;
    }

    return $default;
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

function awpcp_get_currency_code() {
    $currency_code = get_awpcp_option( ''. 'currency-code' );

    if ( function_exists( 'mb_strtoupper' ) ) {
        return mb_strtoupper( $currency_code );
    } else {
        return strtoupper( $currency_code );
    }
}


/**
 * XXX: Referenced in FAQ: http://awpcp.com/forum/faq/why-doesnt-my-currency-code-change-when-i-set-it/
 */
function awpcp_get_currency_symbol() {
	$currency_symbols = awpcp_currency_symbols();
	$currency_code = awpcp_get_currency_code();

    foreach (  $currency_symbols as $currency_symbol => $currency_codes ) {
        if ( in_array( $currency_code, $currency_codes ) ) {
            return $currency_symbol;
        }
    }

    return $currency_code;
}

/**
 * @since 3.4
 */
function awpcp_currency_symbols() {
    return array(
        '$' => array( 'CAD', 'AUD', 'NZD', 'SGD', 'HKD', 'USD' ),
        '&yen;' => array( 'JPY' ),
        '&euro;' => array( 'EUR' ),
        '&pound;' => array( 'GBP' ),
    );
}

/**
 * @since 3.0
 */
function awpcp_format_money($value) {
    if ( get_awpcp_option( 'show-currency-symbol' ) != 'do-not-show-currency-symbol' ) {
        $show_currency_symbol = true;
    } else {
        $show_currency_symbol = false;
    }

    return awpcp_get_formmatted_amount( $value, $show_currency_symbol );
}

function awpcp_format_money_without_currency_symbol( $value ) {
    return awpcp_get_formmatted_amount( $value, false );
}

function awpcp_get_formmatted_amount( $value, $include_symbol ) {
    $thousands_separator = get_awpcp_option('thousands-separator');
    $decimal_separator = get_awpcp_option('decimal-separator');
    $decimals = get_awpcp_option('show-decimals') ? 2 : 0;

    $symbol_position = get_awpcp_option( 'show-currency-symbol' );
    $symbol = $include_symbol ? awpcp_get_currency_symbol() : '';

    if ( $include_symbol && $symbol_position == 'show-currency-symbol-on-left' ) {
        $template = '<currenct-symbol><separator><amount>';
    } else if ( $include_symbol && $symbol_position == 'show-currency-symbol-on-right' ) {
        $template = '<amount><separator><currenct-symbol>';
    } else {
        $template = '<amount>';
    }

    if ( get_awpcp_option( 'include-space-between-currency-symbol-and-amount' ) ) {
        $separator = '&nbsp;';
    } else {
        $separator = '';
    }

    if ($value >= 0) {
        $number = number_format($value, $decimals, $decimal_separator, $thousands_separator);
        $formatted = $template;
    } else {
        $number = number_format(- $value, $decimals, $decimal_separator, $thousands_separator);
        $formatted = '(' . $template . ')';
    }

    $formatted = str_replace( '<currenct-symbol>', $symbol, $formatted );
    $formatted = str_replace( '<amount>', $number, $formatted );
    $formatted = str_replace( '<separator>', $separator, $formatted );

    return $formatted;
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

function awpcp_flash( $message, $class = array( 'awpcp-updated', 'updated') ) {
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

function awpcp_print_form_errors( $errors ) {
    foreach ( $errors as $index => $error ) {
        if ( is_numeric( $index ) ) {
            echo awpcp_print_message( $error, array( 'error' ) );
        } else {
            echo awpcp_print_message( $error, array( 'error', 'ghost' ) );
        }
    }
}

function awpcp_print_message( $message, $class = array( 'awpcp-updated', 'updated' ) ) {
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
		return '<p>' . __( 'The images or files with pale red background have been rejected by an administrator user. Likewise, files with a pale yellow background are awaiting approval. Files that are awaiting approval and rejected files, cannot be shown in the frontend.', 'AWPCP' ) . '</p>';
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
	return strtolower( awpcp_utf8_pathinfo( $filename, PATHINFO_EXTENSION ) );
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
 * TODO: move memoization to where the information is needed. Having it here is the perfect
 *          scenarion for hard to track bugs.
 * @since  2.1.4
 */
function awpcp_column_exists($table, $column) {
    static $column_exists = array();

    if ( ! isset( $column_exists[ "$table-$column" ] ) ) {
        $column_exists[ "$table-$column" ] = awpcp_check_if_column_exists( $table, $column );
    }

    return $column_exists[ "$table-$column" ];
}

/**
 * @since 3.4
 */
function awpcp_check_if_column_exists( $table, $column ) {
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
 * XXX: This may be necessary only for email addresses used in the Reply-To header.
 *
 * @since 3.0.2
 */
function awpcp_encode_address_name($str) {
    return awpcp_phpmailer()->encodeHeader( $str, 'phrase' );
}

/**
 * Returns or creates an instance of PHPMailer.
 *
 * Extracted from wp_mail()'s code.
 *
 * @since 3.4
 */
function awpcp_phpmailer() {
    global $phpmailer;

    // (Re)create it, if it's gone missing
    if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $phpmailer = new PHPMailer( true );
    }

    $phpmailer->CharSet = apply_filters( 'wp_mail_charset', get_bloginfo( 'charset' ) );

    return $phpmailer;
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

function awpcp_moderators_email_to() {
    $users = get_users( array( 'role' => 'awpcp-moderator' ) );
    $email_addresses = array();

    foreach ( $users as $user ) {
        $properties = array( 'display_name', 'user_login', 'username' );
        $user_name = awpcp_get_object_property_from_alternatives( $user->data, $properties );
        $user_email = $user->data->user_email;

        $email_addresses[] = awpcp_format_email_address( $user_email, $user_name );
    }

    return $email_addresses;
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
    $manage_images_url = add_query_arg( urlencode_deep( $params ), admin_url( 'admin.php' ) );

	if ( false == $ad_approve && $images_approve ) {
		$subject = __( 'Images on Ad "%s" are awaiting approval', 'AWPCP' );

		$message = __( 'Images on Ad "%s" are awaiting approval. You can approve the images going to the Manage Images section for that Ad and clicking the "Enable" button below each image. Click here to continue: %s.', 'AWPCP');
		$messages = array( sprintf( $message, $ad->get_title(), $manage_images_url ) );
	} else {
		$subject = __( 'The Ad "%s" is awaiting approval', 'AWPCP' );

		$message = __('The Ad "%s" is awaiting approval. You can approve the Ad going to the Manage Listings section and clicking the "Enable" action shown on top. Click here to continue: %s.', 'AWPCP');
		$params = array('page' => 'awpcp-listings',  'action' => 'view', 'id' => $ad->ad_id);
	    $url = add_query_arg( urlencode_deep( $params ), admin_url( 'admin.php' ) );

	    $messages[] = sprintf( $message, $ad->get_title(), $url );

	    if ( $images_approve ) {
		    $message = __( 'Additionally, You can approve the images going to the Manage Images section for that Ad and clicking the "Enable" button below each image. Click here to continue: %s.', 'AWPCP' );
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

/**
 * @since 3.3
 */
function awpcp_enqueue_main_script() {
    wp_enqueue_script( 'awpcp' );
}

/**
 * @since 3.3
 */
function awpcp_maybe_add_thickbox() {
    if ( get_awpcp_option( 'awpcp_thickbox_disabled' ) ) {
        return;
    }

    add_thickbox();
}


/**
 * @since 3.2.1
 */
function awpcp_load_plugin_textdomain( $__file__, $text_domain ) {
    $basename = dirname( plugin_basename( $__file__ ) );
    $locale = apply_filters( 'plugin_locale', get_locale(), $text_domain );

    // Load user translation from wp-content/languages/plugins/$domain-$locale.mo
    $mofile = WP_LANG_DIR . '/plugins/' . $text_domain . '-' . $locale . '.mo';
    load_textdomain( $text_domain, $mofile );

    // Load user translation from wp-content/languages/another-wordpress-classifieds-plugin/$domain-$locale.mo
    $mofile = WP_LANG_DIR . '/' . $basename . '/' . $text_domain . '-' . $locale . '.mo';
    load_textdomain( $text_domain, $mofile );

    // Load translation included in plugin's languages directory. Stop if the file is loaded.
    $mofile = WP_PLUGIN_DIR . '/' . $basename . '/languages/' . $text_domain . '-' . $locale . '.mo';
    if ( load_textdomain( $text_domain, $mofile ) ) {
        return true;
    }

    // Try loading the translations from the plugin's root directory. WordPress will also
    // look for a file in wp-content/languages/plugins/$domain-$locale.mo.
    $mofile = WP_PLUGIN_DIR . '/' . $basename . '/' . $text_domain . '-' . $locale . '.mo';
    if ( load_textdomain( $text_domain, $mofile ) ) {
        return true;
    }

    return false;
}

function awpcp_utf8_strlen( $string ) {
	if ( function_exists( 'mb_strlen' ) ) {
		return mb_strlen( $string, 'UTF-8' );
	} else {
		return preg_match_all( '(.)su', $string, $matches );
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

/**
 * from http://stackoverflow.com/a/4459219/201354.
 *
 * @since 3.3
 */
function awpcp_utf8_pathinfo( $path, $path_parts_types = null ) {
    $modified_path = awpcp_add_path_prefix( $path );
    $path_parts = is_null( $path_parts_types ) ? pathinfo( $modified_path ) : pathinfo( $modified_path, $path_parts_types );
    $path_parts = awpcp_remove_path_prefix( $path_parts, $path_parts_types );

    return $path_parts;
}

function awpcp_add_path_prefix( $path, $prefix = '_629353a' ) {
    if ( strpos( $path, '/' ) === false ) {
        $modified_path = $prefix . $path;
    } else {
        $modified_path = str_replace( '/', "/$prefix", $path );
    }

    return $modified_path;
}

function awpcp_remove_path_prefix( $path_parts, $path_part_type, $prefix = '_629353a' ) {
    if ( is_array( $path_parts ) ) {
        foreach ( $path_parts as $key => $value ) {
            $path_parts[ $key ] = str_replace( $prefix, '', $value );
        }
    } else if ( is_string( $path_parts ) ) {
        $path_parts = str_replace( $prefix, '', $path_parts );
    }

    return $path_parts;
}

function awpcp_utf8_basename( $path, $suffix = null ) {
    $modified_path = awpcp_add_path_prefix( $path );
    $basename = basename( $modified_path );
    return awpcp_remove_path_prefix( $basename, PATHINFO_BASENAME );
}

/**
 * @param string    $path           Path to the file whose unique filename needs to be generated.
 * @param string    $filename       Target filename. The unique filename will be as similar as
 *                                  possible to this name.
 * @param array     $directories    The generated name must be unique in all directories in this array.
 * @since 3.4
 */
function awpcp_unique_filename( $path, $filename, $directories ) {
    $pathinfo = awpcp_utf8_pathinfo( $filename );

    $name = $pathinfo['filename'];
    $extension = $pathinfo['extension'];
    $file_size = filesize( $path );
    $timestamp = microtime();
    $salt = wp_salt();
    $counter = 0;

    do {
        $hash = hash( 'crc32b', "$name-$extension-$file_size-$timestamp-$salt-$counter" );
        $new_filename = "$name-$hash.$extension";
        $counter = $counter + 1;
    } while ( awpcp_is_filename_already_used( $new_filename, $directories ) );

    return $new_filename;
}

/**
 * @since 3.4
 */
function awpcp_is_filename_already_used( $filename, $directories ) {
    foreach ( $directories as $directory ) {
        if ( file_exists( "$directory/$filename" ) ) {
            return true;
        }
    }

    return false;
}

/**
 * @since 3.3
 */
function awpcp_register_activation_hook( $__FILE__, $callback ) {
    $file = WP_CONTENT_DIR . '/plugins/' . basename( dirname( $__FILE__ ) ) . '/' . basename( $__FILE__ );
    register_activation_hook( $file, $callback );
}

function awpcp_register_deactivation_hook( $__FILE__, $callback ) {
    $file = WP_CONTENT_DIR . '/plugins/' . basename( dirname( $__FILE__ ) ) . '/' . basename( $__FILE__ );
    register_deactivation_hook( $file, $callback );
}

/**
 * @since 3.4
 */
function awpcp_are_images_allowed() {
    $allowed_image_extensions = array_filter( awpcp_get_option( 'allowed-image-extensions', array() ) );
    return count( $allowed_image_extensions ) > 0;
}
