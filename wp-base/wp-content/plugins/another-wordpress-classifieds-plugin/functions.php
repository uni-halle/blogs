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
	 * @since 3.3.0
	 *
	 * @param string $text Text to trim.
	 * @param int $num_words Number of words. Default 55.
	 * @param string $more What to append if $text needs to be trimmed. Default '&hellip;'.
	 * @return string Trimmed text.
	 */
	function wp_trim_words( $text, $num_words = 55, $more = null ) {
		if ( null === $more )
			$more = __( '&hellip;' );
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
 */
function awpcp_time($date=null, $format='mysql') {
	if (is_null($date) || strlen($date) === 0) {
		$date = current_time('timestamp');
	} else if (is_string($date)) {
		$date = strtotime($date);
	} // else, we asume a timestamp

	switch ($format) {
		case 'mysql':
			return date('Y-m-d H:i:s', $date);
		case 'timestamp':
			return $date;
		case 'awpcp':
			return date(awpcp_get_datetime_format(), $date);
		case 'awpcp-date':
			return date(awpcp_get_date_format(), $date);
		case 'awpcp-time':
			return date(awpcp_get_time_format(), $date);
		default:
			return date($format, $date);
	}
}


/**
 * Get a WP User. See awpcp_get_users for details.
 *
 * @param $id int 	User ID
 */
function awpcp_get_user_data($id) {
	$users = awpcp_get_users('WHERE ID = ' . intval($id));
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
function awpcp_get_users($where='') {
	global $wpdb;

	$users = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users $where");

	foreach ($users as $k => $user) {
		$data = get_userdata($user->ID);
		$profile = get_user_meta($user->ID, 'awpcp-profile', true);

		$users[$k] = new stdClass();
		$users[$k]->ID = $user->ID;
		$users[$k]->user_email = empty($profile['email']) ? $data->user_email : $profile['email'];
		$users[$k]->user_login = awpcp_get_property($data, 'user_login', '');
		$users[$k]->display_name = awpcp_get_property($data, 'display_name', '');
		$users[$k]->first_name = awpcp_get_property($data, 'first_name', '');
		$users[$k]->last_name = awpcp_get_property($data, 'last_name', '');
		$users[$k]->username = awpcp_array_data('username', '', $profile);
		$users[$k]->user_url = awpcp_get_property($data, 'user_url', '');

		$users[$k]->address = awpcp_array_data('address', '', $profile);
		$users[$k]->phone = awpcp_array_data('phone', '', $profile);
		$users[$k]->city = awpcp_array_data('city', '', $profile);
		$users[$k]->state = awpcp_array_data('state', '', $profile);
	}

	usort( $users, create_function( '$a, $b', 'return strcasecmp( $a->display_name, $b->display_name );' ) );

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
 */
function awpcp_region_fields($translations) {
	$fields = array();

	if (get_awpcp_option('displaycountryfield')) {
		$fields['country'] = array(
			'class' => 'country-field',
			'name' => $translations['country'],
			'label' => __('Country', 'AWPCP'),
			'help' => __('separate countries by commas', 'AWPCP'),
			'required' => get_awpcp_option( 'displaycountryfieldreqop', false ),
		);
	}
	if (get_awpcp_option('displaystatefield')) {
		$fields['state'] = array(
			'class' => 'state-field',
			'name' => $translations['state'],
			'label' => __('State/Province', 'AWPCP'),
			'help' => __('separate states by commas', 'AWPCP'),
			'required' => get_awpcp_option( 'displaystatefieldreqop', false ),
		);
	}
	if (get_awpcp_option('displaycityfield')) {
		$fields['city'] = array(
			'class' => 'city-field',
			'name' => $translations['city'],
			'label' => __('City', 'AWPCP'),
			'help' => __('separate cities by commas', 'AWPCP'),
			'required' => get_awpcp_option( 'displaycityfieldreqop', false ),
		);
	}
	if (get_awpcp_option('displaycountyvillagefield')) {
		$fields['county'] = array(
			'class' => 'county-field',
			'name' => $translations['county'],
			'label' => __('County/Village/Other', 'AWPCP'),
			'help' => __('separate counties by commas', 'AWPCP'),
			'required' => get_awpcp_option( 'displaycountyvillagefieldreqop', false ),
		);
	}

	return $fields;
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
		foreach ( $fields as &$field ) {
			$field['required'] = false;
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

	return apply_filters('awpcp_number_images_allowed', $allowed, $ad_id);
}


/**
 */
function awpcp_get_ad_images($ad_id) {
	global $wpdb;

	$query = "SELECT * FROM " . AWPCP_TABLE_ADPHOTOS . " ";
	$query.= "WHERE ad_id=%d ORDER BY image_name ASC";

	return $wpdb->get_results($wpdb->prepare($query, $ad_id));
}

/**
 *
 */
function awpcp_get_image_url($image, $suffix='') {
	static $uploads = array();

	if (empty($uploads))
		$uploads = array_shift(awpcp_setup_uploads_dir());

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
 */
// TODO: eventually move this to AWPCP_Ad
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
 * The returned URL has no trailing slash.
 *
 * @since 2.0.7
 */
function awpcp_get_page_url($pagename) {
	$id = awpcp_get_page_id_by_ref($pagename);

	if (get_option('permalink_structure')) {
		$url = home_url(get_page_uri($id));
	} else {
		$url = add_query_arg('page_id', $id, home_url());
	}

	return rtrim($url, '/');
}

/**
 * Returns a link that can be used to initiate the Ad Renewal process.
 *
 * @since 2.0.7
 */
function awpcp_get_renew_ad_url($ad_id) {
	if (get_awpcp_option('enable-user-panel') == 1) {
		$url = awpcp_get_user_panel_url();
		$url = add_query_arg(array('id' => $ad_id, 'action' => 'renew-ad'), $url);
	} else {
		$url = awpcp_get_page_url('renew-ad-page-name');
		$url = add_query_arg(array('ad_id' => $ad_id), $url);
	}

	return $url;
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
function awpcp_get_user_panel_url() {
	return admin_url('admin.php?page=awpcp-panel');
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
function awpcp_get_blog_name() {
	$blog_name = get_option('blogname');
	if (empty($blog_name)) {
		$blog_name = _x('Classifieds Website', 'default blog title', 'AWPCP');
	}
	return $blog_name;
}


function awpcp_post_param($name, $default='') {
	return awpcp_array_data($name, $default, $_POST);
}

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
	$thousands_separator = $thousands_separator ? $thousands_separator : get_awpcp_option('thousands-separator');
	$decimal_separator = $decimal_separator ? $decimal_separator : get_awpcp_option('decimal-separator');

	$pattern = '/^-?(?:\d+|\d{1,3}(?:\\' . $thousands_separator . '\\d{3})+)?(?:\\' . $decimal_separator . '\\d+)?$/';

	if (preg_match($pattern, $value)) {
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
    $wpdb->hide_errors();
    $result = $wpdb->query("SELECT `$column` FROM $table");
    $wpdb->show_errors();
    return $result !== false;
}


/** Email functions
 ---------------------------------------------------------------------------- */

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
	return sprintf( '%s <%s>', awpcp_get_blog_name(), awpcp_admin_sender_email_address() );
}

/**
 * Return the name and email address of the account that should receive notifications intented for
 * administrator users.
 *
 * @since	3.0
 * @return	string	name <email@address>
 */
function awpcp_admin_email_to() {
	return sprintf( '%s <%s>', awpcp_get_blog_name(), awpcp_admin_recipient_email_address() );
}

/**
 * @since  2.1.4
 */
function awpcp_ad_enabled_email($ad) {
	// user email
	$mail = new AWPCP_Email;
	$mail->to[] = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
	$mail->subject = sprintf(__('Your Ad "%s" has been approved', 'AWPCP'), $ad->get_title());

	$template = AWPCP_DIR . '/frontend/templates/email-ad-enabled-user.tpl.php';
	$mail->prepare($template, compact('ad'));

	$mail->send();
}

function awpcp_ad_updated_email($ad, $message) {
	$admin_email = awpcp_admin_recipient_email_address();

	// user email

	$mail = new AWPCP_Email;
	$mail->to[] = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
	$mail->subject = sprintf(__('Your Ad "%s" has been successfully updated', 'AWPCP'), $ad->get_title());

	$template = AWPCP_DIR . '/frontend/templates/email-ad-updated-user.tpl.php';
	$mail->prepare($template, compact('ad', 'message', 'admin_email'));

	$mail->send();
}

function awpcp_ad_awaiting_approval_email($ad, $ad_approve, $images_approve) {
	// admin email

	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_admin_email_to();
	$mail->subject = sprintf(__('The Ad "%s" is awaiting approval', "AWPCP"), $ad->get_title());

	$params = array('page' => 'awpcp-listings',  'action' => 'view', 'id' => $ad->ad_id);
    $url = add_query_arg($params, admin_url('admin.php'));

	$template = AWPCP_DIR . '/frontend/templates/email-ad-awaiting-approval-admin.tpl.php';
	$mail->prepare($template, compact('ad', 'url'));

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
