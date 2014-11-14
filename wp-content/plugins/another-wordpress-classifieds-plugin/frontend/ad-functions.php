<?php

/**
 * Return an array of Ad Fees.
 *
 * @since 2.0.7
 * @deprecated  since 3.0
 */
function awpcp_get_fees() {
	global $wpdb;

	$sql = 'SELECT * FROM ' . AWPCP_TABLE_ADFEES . ' ORDER BY adterm_name ASC';
	$results = $wpdb->get_results($sql);

	return is_array($results) ? $results : array();
}


function awpcp_get_ad_images_information($ad_id) {
	if ($ad_id <= 0) {
		return __('An unexpected error ocurred. No Ad was specified.', 'AWPCP');
	}

	if (get_awpcp_option('imagesallowdisallow') != 1) {
		$images_allowed = 0;
	} else {
		$images_allowed = awpcp_get_ad_number_allowed_images($ad_id);
	}

	if ($images_allowed > 0) {
		$images_uploaded = get_total_imagesuploaded($ad_id);
		$images_left = max($images_allowed - $images_uploaded, 0);
	} else {
		$images_uploaded = 0;
		$images_left = 0;
	}

	return array($images_allowed, $images_uploaded, $images_left);
}


/**
 * Generic function to calculate an date relative to a given start date.
 *
 * @since 2.0.7
 */
function awpcp_calculate_end_date($increment, $period, $start_date) {
	$periods = array('D' => 'DAY', 'W' => 'WEEK', 'M' => 'MONTH', 'Y' => 'YEAR');
	if (in_array($period, array_keys($periods))) {
		$period = $periods[$period];
	}

	// 0 means no expiration date, we understand that as ten years
	if ($increment == 0 && $period == 'DAY') {
		$increment = 3650;
	} else if ($increment == 0 && $period == 'WEEK') {
		$increment = 5200;
	} else if ($increment == 0 && $period == 'MONTH') {
		$increment = 1200;
	} else if ($increment == 0 && $period == 'YEAR') {
		$increment = 10;
	}

	return date('Y-m-d H:i:s', strtotime("+ $increment $period", $start_date));
}

/**
 * If an Ad was passed, calculates Ad End Date from current End Date.
 * If no Ad was passed, calculates Ad End Date as if Ad would have
 * been posted at the current time.
 *
 * TODO: Use the new $ad->calculate_end_date() method.
 */
function awpcp_calculate_ad_end_date($duration, $interval='DAY', $ad=null) {
	$now = awpcp_datetime( 'timestamp' );
	$end_date = is_null($ad) ? $ad->ad_enddate : 0;
	// if the Ad's end date is in the future, use that as starting point 
	// for the new end date, else use current date.
	$start_date = $end_date > $now ? $end_date : $now;
	return awpcp_calculate_end_date($duration, $interval, $start_date);
}


/**
 * ...
 *
 * @param $id	Ad ID.
 * @param $transaction	Payment Transaction associated to the Ad being posted
 *
 * It must be possible to have more than one transaction associated to a single
 * Ad, for example, when an Ad has been posted AND renewed one or more times.
 *
 * TODO: this can be moved into the Ad class. We actually don't need a transaction,
 * because the payment_status is stored in the Ad object. We need, however, to update
 * the payment_status when the Ad is placed AND renewed. ~2012-09-19
 */
function awpcp_calculate_ad_disabled_state($id=null, $transaction=null, $payment_status=null) {
    if ( is_null( $payment_status ) && ! is_null( $transaction ) ) {
        $payment_status = $transaction->payment_status;
    }

    $payment_is_pending = $payment_status == AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING;

    if ( awpcp_current_user_is_admin() ) {
        $disabled = 0;
    } else if ( get_awpcp_option( 'adapprove' ) == 1 ) {
        $disabled = 1;
    } else if ( $payment_is_pending && get_awpcp_option( 'enable-ads-pending-payment' ) == 1 ) {
        $disabled = 0;
    } else if ( $payment_is_pending ) {
        $disabled = 1;
    } else {
        $disabled = 0;
    }

    return $disabled;
}


/**
 * @since 3.0.2
 */
function awpcp_ad_renewed_user_email( $ad ) {
	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_format_email_address( $ad->ad_contact_email, $ad->ad_contact_name );
	$mail->subject = sprintf( get_awpcp_option( 'ad-renewed-email-subject' ), $ad->get_title() );

	$introduction = get_awpcp_option( 'ad-renewed-email-body' );

	$template = AWPCP_DIR . '/frontend/templates/email-ad-renewed-success-user.tpl.php';
	$mail->prepare( $template, compact( 'ad', 'introduction' ) );

	return $mail;
}


/**
 * @since 3.0.2
 */
function awpcp_ad_renewed_admin_email( $ad, $body ) {
	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_admin_email_to();
	$mail->subject = sprintf( __( 'The classifieds listing "%s" has been successfully renewed.', 'AWPCP' ), $ad->ad_title );

	$template = AWPCP_DIR . '/frontend/templates/email-ad-renewed-success-admin.tpl.php';
	$mail->prepare( $template, compact( 'body' ) );

	return $mail;
}


/**
 * @since 2.1.2
 */
function awpcp_send_ad_renewed_email($ad) {
	// send notification to the user
	$user_email = awpcp_ad_renewed_user_email( $ad );
	$user_email->send();

	// send notification to the admin
	$admin_email = awpcp_ad_renewed_admin_email( $ad, $user_email->body );
	$admin_email->send();
}

/**
 * @since 2.0.7
 */
function awpcp_renew_ad_success_message($ad, $text=null, $send_email=true) {
	if (is_null($text)) {
		$text = __("The Ad has been successfully renewed. New expiration date is %s. ", 'AWPCP');
	}

	$return = '';
	if (is_admin()) {
		$return = sprintf('<a href="%1$s">%2$s</a>', awpcp_get_user_panel_url(), __('Return to Listings', 'AWPCP'));
	}

	if ($send_email) {
		awpcp_send_ad_renewed_email($ad);
	}

	return sprintf("%s %s", sprintf($text, $ad->get_end_date()), $return);
}


/**
 * Deletes an image
 *
 * @param $picid int The id of the image to delete.
 * @param $adid int The id of the Ad the image belongs to.
 * @param $force boolean True if image should be deleted even if curent
 * 						 user is not admin.
 * @deprecated use awpcp_media_api()->delete()
 */
function deletepic( $picid, $adid, $adtermid, $adkey, $editemail, $force=false ) {
	_deprecated_function( __FUNCTION__, '3.0.2', 'awpcp_media_api()->delete()' );
}


function deletead($adid, $adkey, $editemail, $force=false, &$errors=array()) {
	$output = '';
	$awpcppage = get_currentpagename();
	$awpcppagename = sanitize_title($awpcppage, $post_ID='');
	$quers = setup_url_structure($awpcppagename);

	$isadmin = checkifisadmin() || $force;

	if (get_awpcp_option('onlyadmincanplaceads') && ($isadmin != 1)) {
		$message = __("You do not have permission to perform the function you are trying to perform. Access to this page has been denied","AWPCP");
		$errors[] = $message;

	} else {
		global $wpdb, $nameofsite;

		$tbl_ads = $wpdb->prefix . "awpcp_ads";
		$tbl_ad_photos = $wpdb->prefix . "awpcp_adphotos";
		$savedemail=get_adposteremail($adid);

		if ( $isadmin == 1 || strcasecmp( $editemail, $savedemail ) == 0 ) {
			$ad = AWPCP_Ad::find_by_id($adid);
			if ( $ad && $ad->delete() ) {
				if (($isadmin == 1) && is_admin()) {
					$message=__("The Ad has been deleted","AWPCP");
					return $message;
				} else {
					$message=__("Your Ad details and any photos you have uploaded have been deleted from the system","AWPCP");
					$errors[] = $message;
				}
			} else if ( $ad === null ) {
				$errors[] = __( "The specified Ad doesn't exists.", 'AWPCP' );
			} else {
				$errors[] = __( "There was an error trying to delete the Ad. The Ad was not deleted.", 'AWPCP' );
			}
		} else {
			$message=__("Problem encountered. Cannot complete  request","AWPCP");
			$errors[] = $message;
		}
	}

	$output .= "<div id=\"classiwrapper\">";
	$output .= awpcp_menu_items();
	$output .= "<p>";
	$output .= $message;
	$output .= "</p>";
	$output .= "</div>";

	return $output;
}


/**
 * @since 3.0.2
 */
function awpcp_ad_posted_user_email( $ad, $transaction = null, $message='' ) {
	$admin_email = awpcp_admin_recipient_email_address();

	$payments_api = awpcp_payments_api();
	$show_total_amount = $payments_api->payments_enabled();
	$show_total_credits = $payments_api->credit_system_enabled();
	$currency_code = $payments_api->get_currency();

	if ( ! is_null( $transaction ) ) {
		$transaction_totals = $transaction->get_totals();
		$total_amount = $transaction_totals['money'];
		$total_credits = $transaction_totals['credits'];
	} else {
		$total_amount = 0;
		$total_credits = 0;
	}

	$params = compact(
		'ad',
		'admin_email',
		'transaction',
		'currency_code',
		'show_total_amount',
		'show_total_credits',
		'total_amount',
		'total_credits',
		'message'
	);

	$email = new AWPCP_Email;
	$email->to[] = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
	$email->subject = get_awpcp_option('listingaddedsubject');
	$email->prepare( AWPCP_DIR . '/frontend/templates/email-place-ad-success-user.tpl.php', $params );

	return $email;
}


/**
 * @since 2.1.4
 */
function awpcp_ad_posted_email( $ad, $transaction = null, $message = '', $notify_admin = true ) {
	$result = false;

	// user email
	$user_message = awpcp_ad_posted_user_email( $ad, $transaction, $message );
	if (get_awpcp_option('send-user-ad-posted-notification', true)) {
		$result = $user_message->send();
	}

	// admin email
	if ($notify_admin && get_awpcp_option('notifyofadposted')) {
		// grab the body to be included in the email sent to the admin
		$content = $user_message->body;

		$admin_message = new AWPCP_Email;
		$admin_message->to[] = awpcp_admin_email_to();
		$admin_message->subject = __("New classified Ad listing posted.", "AWPCP");

		$params = array('page' => 'awpcp-listings',  'action' => 'view', 'id' => $ad->ad_id);
		$url = add_query_arg($params, admin_url('admin.php'));

		$template = AWPCP_DIR . '/frontend/templates/email-place-ad-success-admin.tpl.php';
		$admin_message->prepare($template, compact('content', 'url'));

		$admin_message->send();
	}

	return $result;
}


function awpcp_render_ads($ads, $context='listings', $config=array(), $pagination=array()) {
	$config = shortcode_atts(array('show_menu' => true, 'show_intro' => true), $config);

	if (has_action('awpcp_browse_ads_template_action') || has_filter('awpcp_browse_ads_template_filter')) {
		do_action('awpcp_browse_ads_template_action');
		$output = apply_filters('awpcp_browse_ads_template_filter');
		return;
	} else if (file_exists(AWPCP_DIR . "/awpcp_display_ads_my_layout.php") && get_awpcp_option('activatemylayoutdisplayads')) {
		include(AWPCP_DIR . "/awpcp_display_ads_my_layout.php");
		return;
	}

	$items = awpcp_render_listings_items( $ads, $context );

	$before_content = apply_filters('awpcp-listings-before-content', array(), $context);
	$after_content = apply_filters('awpcp-listings-after-content', array(), $context);
	$pagination_block = is_array( $pagination ) ? awpcp_pagination( $pagination, '' ) : '';

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/listings.tpl.php');
		$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

/**
 * Renders each listing using the layout configured in the plugin
 * settings.
 *
 * @since next-release
 *
 * @param Array $listings An array of AWPCP_Ad objects.
 * @param string $context The context where the listings will be shown: listings, ?.
 * @return Array An array of rendered items.
 */
function awpcp_render_listings_items( $listings, $context ) {
	$parity = array( 'displayaditemseven', 'displayaditemsodd' );
	$layout = get_awpcp_option('displayadlayoutcode');

	if ( empty( $layout) ) {
		$layout = awpcp()->settings->get_option_default_value( 'displayadlayoutcode' );
	}

	$items = array();
	foreach ( $listings as $i => $listing ) {
		$rendered_listing = awpcp_do_placeholders( $listing, $layout, $context );
		$rendered_listing = str_replace( "\$awpcpdisplayaditems", $parity[$i % 2], $rendered_listing );

		if ( function_exists( 'awpcp_featured_ads' ) ) {
			$rendered_listing = awpcp_featured_ad_class( $listing->ad_id, $rendered_listing );
		}

		$items[] = apply_filters( 'awpcp-render-listing-item', $rendered_listing, $i + 1 );
	}

	return $items;
}

//	START FUNCTION: display listing of ad titles when browse ads is clicked

/**
 * TODO: create a render function to render all pages, and allow modules or
 * other parts of the plugin to insert custom content. Remove $before_content
 * when this is done.
 *
 * @param  [type] $where          [description]
 * @param  [type] $byl            [description]
 * @param  [type] $hidepager      [description]
 * @param  [type] $grouporderby   [description]
 * @param  [type] $adorcat        [description]
 * @param  string $before_content [description]
 * @return [type]                 [description]
 */
function awpcp_display_ads($where, $byl, $hidepager, $grouporderby, $adorcat, $before_content='') {
	global $wpdb;
	global $awpcp_imagesurl, $awpcp_plugin_path;
	global $hasregionsmodule, $hasextrafieldsmodule;

	$output = '';
	$awpcppage=get_currentpagename();
	$awpcppagename = sanitize_title($awpcppage);
	$quers=setup_url_structure($awpcppagename);
	$permastruc=get_option('permalink_structure');

	$showadspagename=sanitize_title(get_awpcp_option('show-ads-page-name'));
	$browseadspagename = sanitize_title(get_awpcp_option('browse-ads-page-name'));
	$browsecatspagename=sanitize_title(get_awpcp_option('browse-categories-page-name'));

	$awpcp_browsecats_pageid=awpcp_get_page_id_by_ref('browse-categories-page-name');
	$awpcpwppostpageid=awpcp_get_page_id_by_ref('main-page-name');
	$browseadspageid=awpcp_get_page_id_by_ref('browse-ads-page-name');

	$searchadspageid=awpcp_get_page_id_by_ref('search-ads-page-name');

	$displayadthumbwidth = get_awpcp_option('displayadthumbwidth');

	$url_browsecats='';

	// filters to provide alternative method of storing custom layouts (e.g. can be outside of this plugin's directory)
	if ( has_action('awpcp_browse_ads_template_action') || has_filter('awpcp_browse_ads_template_filter') ) {
		do_action('awpcp_browse_ads_template_action');
		$output = apply_filters('awpcp_browse_ads_template_filter');
		return;

	} else if (file_exists("$awpcp_plugin_path/awpcp_display_ads_my_layout.php") &&
			   get_awpcp_option('activatemylayoutdisplayads'))
	{
		include("$awpcp_plugin_path/awpcp_display_ads_my_layout.php");

	} else {
		$output .= "<div id=\"classiwrapper\">";

		$isadmin = checkifisadmin();
		$uiwelcome=stripslashes_deep(get_awpcp_option('uiwelcome'));

		$output .= apply_filters( 'awpcp-content-before-listings-page', '' );
		$output .= "<div class=\"uiwelcome\">$uiwelcome</div>";
		$output .= awpcp_menu_items();

		if ($hasregionsmodule ==  1) {
			if (isset($_SESSION['theactiveregionid'])) {
				$theactiveregionid=$_SESSION['theactiveregionid'];
				$theactiveregionname = addslashes(get_theawpcpregionname($theactiveregionid));
			}

			// Do not show Region Control form when showing Search Ads page
			// search result. Changing the current location will redirect the user
			// to the form instead of a filterd version of the form and that's confusing
			global $post;
			// this is a poor test to see if we are in Search Ads page
			if ($post->post_name == sanitize_title(get_awpcp_option('search-ads-page-name')) &&
				isset($_POST['a']) && $_POST['a'] == 'dosearch') {
				// do nothing
			} else {
				$output .= awpcp_region_control_selector();
			}
		}

		$output .= $before_content;

		$tbl_ads = $wpdb->prefix . "awpcp_ads";
		$tbl_ad_photos = $wpdb->prefix . "awpcp_adphotos";

		$from="$tbl_ads";

		$ads_exist = ads_exist();

		if (!$ads_exist) {
			$showcategories="<p style=\"padding:10px\">";
			$showcategories.=__("There are currently no ads in the system","AWPCP");
			$showcategories.="</p>";
			$pager1='';
			$pager2='';

		} else {
			$awpcp_image_display_list=array();

			if ($adorcat == 'cat') {
				$tpname = get_permalink($awpcp_browsecats_pageid);
			} elseif ($adorcat == 'search') {
				$tpname = get_permalink($searchadspageid);
			} elseif ( preg_match( '/^custom:/', $adorcat ) ) {
				$tpname = str_replace( 'custom:', '', $adorcat );
			} else {
				$tpname = get_permalink($browseadspageid);
			}

			$results = get_awpcp_option( 'adresultsperpage', 10 );
			$results = absint( awpcp_request_param( 'results', $results ) );
			$offset = absint( awpcp_request_param( 'offset', 0 ) );

			if ( $results === 0 ) {
				$results = 10;
			}

			$args = array(
				'order' => AWPCP_Ad::get_order_conditions( $grouporderby ),
				'offset' => $offset,
				'limit' => $results,
			);
			$ads = AWPCP_Ad::get_enabled_ads( $args, array( $where ) );

			// get_where_conditions() is called from get_enabled_ads(), we need the
			// WHERE conditions here to pass them to create_pager()
			$where = AWPCP_Ad::get_where_conditions( array( $where ) );

			if (!isset($hidepager) || empty($hidepager) ) {
				//Unset the page and action here...these do the wrong thing on display ad
				unset($_GET['page_id']);
				unset($_POST['page_id']);
				//unset($params['page_id']);
				$pager1=create_pager($from, join( ' AND ', $where ),$offset,$results,$tpname);
				$pager2=create_pager($from, join( ' AND ', $where ),$offset,$results,$tpname);
			} else {
				$pager1='';
				$pager2='';
			}

			$items = awpcp_render_listings_items( $ads, 'listings' );

			$opentable = "";
			$closetable = "";

			if (empty($ads)) {
				$showcategories="<p style=\"padding:20px;\">";
				$showcategories.=__("There were no ads found","AWPCP");
				$showcategories.="</p>";
				$pager1='';
				$pager2='';
			} else {
				$showcategories = smart_table($items, intval($results/$results), $opentable, $closetable);
			}
		}

		$show_category_id = absint( awpcp_request_param( 'category_id' ) );

		if (!isset($url_browsecatselect) || empty($url_browsecatselect)) {
			$url_browsecatselect = get_permalink($awpcp_browsecats_pageid);
		}

		if ($ads_exist) {
			$category_id = (int) awpcp_request_param('category_id', -1);
			$category_id = $category_id === -1 ? (int) get_query_var('cid') : $category_id;

			$output .= "<div class=\"changecategoryselect\"><form method=\"post\" action=\"$url_browsecatselect\">";

			$output .= '<div class="awpcp-category-dropdown-container">';
			$dropdown = new AWPCP_CategoriesDropdown();
			$output .= $dropdown->render( array( 'context' => 'search', 'name' => 'category_id', 'selected' => $category_id ) );
			$output .= '</div>';

			$output .= "<input type=\"hidden\" name=\"a\" value=\"browsecat\" />&nbsp;<input class=\"button\" type=\"submit\" value=\"";
			$output .= __("Change Category","AWPCP");
			$output .= "\" /></form></div>";

			$output .= "<div class=\"pager\">$pager1</div><div class=\"fixfloat\"></div>";

			$output .= "<div id='awpcpcatname' class=\"fixfloat\">";

			if ($category_id > 0) {
				$output .= "<h3>" . __("Category: ", "AWPCP") . get_adcatname($category_id) . "</h3>";
			}

			$output .= "</div>";
		}

		$output .= apply_filters('awpcp-display-ads-before-list', '');
		$output .= "$showcategories";

		if ($ads_exist) {
			$output .= "&nbsp;<div class=\"pager\">$pager2</div>";
		}

		if ($byl) {
			if (field_exists($field='removepoweredbysign') && !(get_awpcp_option('removepoweredbysign'))) {
				$output .= "<p><font style=\"font-size:smaller\">";
				$output .= __("Powered by ","AWPCP");
				$output .= "<a href=\"http://www.awpcp.com\">Another Wordpress Classifieds Plugin</a> </font></p>";

			} elseif (field_exists($field='removepoweredbysign') && (get_awpcp_option('removepoweredbysign'))) {
				// ...

			} else {
				// $output .= "<p><font style=\"font-size:smaller\">";
				// $output .= __("Powered by ","AWPCP");
				// $output .= "<a href=\"http://www.awpcp.com\">Another Wordpress Classifieds Plugin</a> </font></p>";
			}
		}

		$output .= apply_filters( 'awpcp-content-after-listings-page', '' );
		$output .= "</div>";

	}
	return $output;
}


/**
 * Generates HTML to display login form when user is not registered.
 * @tested
 */
function awpcp_login_form($message=null, $redirect=null) {
	if ( is_null( $redirect ) ) {
		$redirect = awpcp_current_url();
	}

	$registration_url = get_awpcp_option( 'registrationurl' );
	if ( empty( $registration_url ) ) {
		if ( function_exists( 'wp_registration_url' ) ) {
			$registration_url = wp_registration_url();
		} else {
			$registration_url = site_url( 'wp-login.php?action=register', 'login' );
		}
	}

	$redirect_to = urlencode( add_query_arg( 'register', true, $redirect ) );
	$register_url = add_query_arg( array( 'redirect_to' => $redirect_to ), $registration_url );

	$redirect_to = urlencode( add_query_arg( 'reset', true, $redirect ) );
	$lost_password_url = add_query_arg( array( 'redirect_to' => $redirect_to ), wp_lostpassword_url() );

	ob_start();
		include( AWPCP_DIR . '/frontend/templates/login-form.tpl.php' );
		$form = ob_get_contents();
	ob_end_clean();

 	return $form;
}


function awpcp_user_payment_terms_sort($a, $b) {
	$result = strcasecmp($a->type, $b->type);
	if ($result == 0) {
		$result = strcasecmp($a->name, $b->name);
	}
	return $result;
}
