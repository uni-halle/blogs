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


// TODO: do we need this?
// --- Yes!
function display_awpcp_image_upload_form($ad_id, $adterm_id, $adkey, $adaction, $nextstep, $adpaymethod, $awpcpuperror) {
	$awpcp_image_upload_form='';
	$totalimagesuploaded=0;

	$max_image_size=get_awpcp_option('maximagesize');

	$numimgsallowed = awpcp_get_ad_number_allowed_images($ad_id, $adterm_id);

	if (adidexists($ad_id)) {
		$totalimagesuploaded=get_total_imagesuploaded($ad_id);
	}

	$numimgsleft=($numimgsallowed - $totalimagesuploaded);

	if (!empty($adterm_id)) {
		$awpcp_payment_fee = get_adfee_amount($adterm_id);
		if ($awpcp_payment_fee <= 0) {
			$nextstep = "finish";
		}
	}

	if ($nextstep == 'finishnoform') {
		$showimageuploadform='';
	} elseif ($nextstep == 'paymentnoform') {
		$showimageuploadform='';
	} else {
		global $awpcp_plugin_path;
		if ($numimgsallowed >= 1) {
			$showimageuploadform="<p>";
			$showimageuploadform.=__("Image slots available", "AWPCP");
			$showimageuploadform.=" <b>$numimgsleft</b>";
			$showimageuploadform.="</p>";

			$showimageuploadform.="<p>";
			$showimageuploadform.=__("Max image size", "AWPCP");
			$max_size = ($max_image_size/1000);
			$showimageuploadform.=" <b>$max_size KB</b>";
			$showimageuploadform.="</p>";
		}

		if (get_awpcp_option('imagesapprove') == 1)
		{
			$showimageuploadform.="<p>";
			$showimageuploadform.=__("Image approval is in effect so any new images you upload will not be visible to viewers until an admin has approved it","AWPCP");
			$showimageuploadform.="</p>";
		}

		if (isset($awpcpuperror) && !empty($awpcpuperror))
		{
			$showimageuploadform.="<p>";
			foreach($awpcpuperror as $theawpcpuerror)
			{
				$showimageuploadform.=$theawpcpuerror;
			}
			$showimageuploadform.="</p>";
		}

		if (!isset($adaction) || empty($adaction))
		{
			$adaction="notset";
		}
		if (!isset($adterm_id) || empty($adterm_id))
		{
			$adterm_id=-1;
		}
		if ($numimgsallowed != 0) {
			$showimageuploadform.="<div class=\"theuploaddiv\">
			<form id=\"AWPCPForm1\" name=\"AWPCPForm1\" method=\"post\" ENCTYPE=\"Multipart/form-data\">
			<p id=\"showhideuploadform\">
			<input type=\"hidden\" name=\"adid\" value=\"$ad_id\" />
			<input type=\"hidden\" name=\"adtermid\" value=\"$adterm_id\" />
			<input type=\"hidden\" name=\"nextstep\" value=\"$nextstep\" />
			<input type=\"hidden\" name=\"adpaymethod\" value=\"$adpaymethod\" />
			<input type=\"hidden\" name=\"adaction\" value=\"$adaction\" />
			<input type=\"hidden\" name=\"adkey\" value=\"$adkey\" />
			<input type=\"hidden\" name=\"a\" value=\"awpcpuploadfiles\" />";
			$showimageuploadform.=__("If adding images to your ad, select your image from your local computer","AWPCP");
			$showimageuploadform.=":<br/><br/>";

			for ($i=0;$i<$numimgsleft;$i++)
			{
				$uploadinput="<div class=\"uploadform\"><input type=\"file\" name=\"AWPCPfileToUpload$i\" id=\"AWPCPfileToUpload$i\" size=\"18\" />
				 </div>";
				$showimageuploadform.="$uploadinput";
			}

			$showimageuploadform.="</p><p style=\"clear:both;text-align:center;\"><input type=\"submit\" value=\"";
			$showimageuploadform.=__("Upload Selected Files","AWPCP");
			$showimageuploadform.="\" class=\"button\" id=\"awpcp_buttonForm\" /></p>";
			$showimageuploadform.="</form>";
			$showimageuploadform.="</div>";
		}
	}


	$awpcp_image_upload_form.=$showimageuploadform;


	$awpcp_image_upload_form.="<div class=\"fixfloat\"></div>";
	$awpcp_image_upload_form.="<div class=\"finishbutton\"><div class=\"finishbuttonleft\">";

	if (($nextstep == 'payment') || ($nextstep == 'paymentnoform')) {
		$clicktheword1=__("Go To Next Step", "AWPCP");
		$clicktheword2=__("continue", "AWPCP");
	} elseif (($nextstep == 'finish') || ($nextstep == 'finishnoform')) {
		$clicktheword1=__("Post Ad Without Images", "AWPCP");
		$clicktheword2=__("complete", "AWPCP");
	} else {
		$clicktheword1=__("Post Ad Without Images", "AWPCP");
		$clicktheword2=__("complete", "AWPCP");
	}

	if ($numimgsallowed <= 0){
		$awpcp_image_upload_form.= sprintf( __(' <p>Please click the %1$s button to <b>%2$s</b> this process.</p>','AWPCP'), $clicktheword1, $clicktheword2 );
	} else {
		$awpcp_image_upload_form.= sprintf( __(' <p>If you prefer not to upload any images please click the <b>%1$s</b> button to %2$s this process.</p>','AWPCP'), $clicktheword1, $clicktheword2 );
	}
	$awpcp_image_upload_form.="</div><div class=\"finishbuttonright\">";

	$finishbutton="
				<form method=\"post\" id=\"awpcpui_process\">";
	if (($nextstep == 'payment') || ($nextstep == 'paymentnoform'))
	{
		$finishbutton.="<input type=\"hidden\" name=\"a\" value=\"loadpaymentpage\" />";
		$finishbutton.="<input type=\"hidden\" name=\"adpaymethod\" value=\"$adpaymethod\" />";
	}
	elseif ($nextstep == 'finish')
	{
		$finishbutton.="<input type=\"hidden\" name=\"a\" value=\"adpostfinish\" />";
	}
	else
	{
		$finishbutton.="<input type=\"hidden\" name=\"a\" value=\"adpostfinish\" />";
	}
	$finishbutton.="
				<input type=\"hidden\" name=\"adid\" value=\"$ad_id\" />
				<input type=\"hidden\" name=\"adkey\" value=\"$adkey\" />
				<input type=\"hidden\" name=\"adaction\" value=\"$adaction\" />
				<input type=\"hidden\" name=\"adtermid\" value=\"$adterm_id\" />
				<input type=\"hidden\" name=\"adpaymethod\" value=\"$adpaymethod\" />
				<input type=\"Submit\" class=\"button\" value=\"";
	// if (($nextstep == 'payment') || ($nextstep == 'paymentnoform'))
	// {
	// 	$finishbutton.=__("Go To Next Step","AWPCP");
	// }
	// elseif ($nextstep == 'payment')
	// {
	// 	$finishbutton.=__("Finish","AWPCP");
	// }
	// else
	// {
	// 	$finishbutton.=__("Finish","AWPCP");
	// }
	$finishbutton.=$clicktheword1;

	$finishbutton.="\" name=\"submit\" />
				</form>";
	$awpcp_image_upload_form.="$finishbutton";
	$awpcp_image_upload_form.="</div><div class=\"fixfloat\"></div></div>";



	return $awpcp_image_upload_form;
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
	$now = awpcp_time(null, 'timestamp');
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
	if (!is_null($payment_status)) {
		$is_pending = $payment_status == AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING;
	} else if (!is_null($transaction)) {
		$payment_status = $transaction->get('payment-status');
		$is_pending = $payment_status == AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING;
	} else {
		// set to false to bypass disablependingads verification
		$is_pending = false;
	}

	if (awpcp_current_user_is_admin()) {
		$disabled = 0;
	} else if (get_awpcp_option('adapprove') == 1) {
		$disabled = 1;
	// if disablependingads == 1, pending Ads should be enabled
	} else if ($is_pending && get_awpcp_option('disablependingads') == 0) {
		$disabled = 1;
	} else {
		$disabled = 0;
	}

	return $disabled;
}


/**
 * @since 2.1.2
 */
function awpcp_send_ad_renewed_email($ad) {
    global $nameofsite;

    $admin_sender_email = awpcp_admin_sender_email_address();
    $admin_recipient_email = awpcp_admin_recipient_email_address();

    $subject = get_awpcp_option('ad-renewed-email-subject');
    $subject = sprintf($subject, $ad->ad_title);
    $introduction = get_awpcp_option('ad-renewed-email-body');

    // send notification to the user
    ob_start();
        include(AWPCP_DIR . '/frontend/templates/email-ad-renewed-success-user.tpl.php');
        $body = ob_get_contents();
    ob_end_clean();

    awpcp_process_mail($admin_sender_email, $ad->ad_contact_email, $subject, $body, $nameofsite, $admin_recipient_email);

    // send notification to the admin
    $subject = __('The classifieds listing "%s" has been successfully renewed.', 'AWPCP');
    $subject = sprintf($subject, $ad->ad_title);

    ob_start();
        include(AWPCP_DIR . '/frontend/templates/email-ad-renewed-success-admin.tpl.php');
        $body = ob_get_contents();
    ob_end_clean();

    awpcp_process_mail($admin_sender_email, awpcp_admin_recipient_email_address(), $subject, $body, $nameofsite, $admin_recipient_email);
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
 */
function deletepic($picid,$adid,$adtermid,$adkey,$editemail,$force=false)
{
	$output = '';
	$isadmin=checkifisadmin() || $force;
	$savedemail=get_adposteremail($adid);
	// TODO: this won't work for email address like jhon-doe@gmail.com
	$editemail = str_replace('-', '@', $editemail);

	// XXX: an user with the same email as the user who posted the Ad
	// can delete an image. This is how Ads and Users are associated.
	if ((strcasecmp($editemail, $savedemail) == 0) || ($isadmin == 1 ))
	{
		global $wpdb;
		$tbl_ad_photos = $wpdb->prefix . "awpcp_adphotos";

		$output .= "<div id=\"classiwrapper\">";

		$images = AWPCP_Image::find(array('id' => (int) $picid, 'ad_id' => (int) $adid));
		if (!empty($images)) {;
			if ($images[0]->delete() && $isadmin == 1 && ($force || is_admin())) {
				$message = __("The image has been deleted","AWPCP");
				return $message;
			}
		}

		$output .= editimages($adtermid,$adid,$adkey,$editemail);

	} else {
		$output .= __("Unable to delete you image, please contact the administrator.","AWPCP");
	}

	$output .= "</div>";
	return $output;
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

		if ((strcasecmp($editemail, $savedemail) == 0) || ($isadmin == 1 )) {
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
 * @since 2.1.4
 */
function awpcp_ad_posted_email($ad, $transaction, $message, $notify_admin=true) {
	$admin_email = awpcp_admin_recipient_email_address();

	// user email

	$user_message = new AWPCP_Email;
	$user_message->to[] = "{$ad->ad_contact_name} <{$ad->ad_contact_email}>";
	$user_message->subject = get_awpcp_option('listingaddedsubject');

	$template = AWPCP_DIR . '/frontend/templates/email-place-ad-success-user.tpl.php';
	$user_message->prepare($template, compact('ad', 'transaction', 'message', 'admin_email'));

	$result = false;
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


// TODO: update other ad_success_email calls
function ad_success_email($ad_id, $message, $notify_admin = true) {
	global $nameofsite;

	$ad = AWPCP_Ad::find_by_id($ad_id);

	if (is_null($ad)) {
		return __('An un expected error occurred while trying to send a notification email about your Ad being posted. Please contact an Administrator if your Ad is not being properly listed.', 'AWPCP');
	}

	$adposteremail = $ad->ad_contact_email;
	$adpostername = $ad->ad_contact_name;
	$listingtitle = $ad->ad_title;
	$transaction_id = $ad->ad_transaction_id;
	$key = $ad->get_access_key();

	$url_showad = url_showad($ad_id);
	$adlink = $url_showad;

	$listingaddedsubject = get_awpcp_option('listingaddedsubject');
	$mailbodyuser = get_awpcp_option('listingaddedbody');
	$subjectadmin = __("New classified ad listing posted","AWPCP");

	// emails are sent in plain text, blank lines in templates are required

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/email-place-ad-success-user.tpl.php');
		$user_email_body = ob_get_contents();
	ob_end_clean();

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/email-place-ad-success-admin.tpl.php');
		$admin_email_body = ob_get_contents();
	ob_end_clean();


	// email the buyer

	$admin_sender_email = awpcp_admin_sender_email_address();
	$admin_recipient_email = awpcp_admin_recipient_email_address();

	$send_success_notification = get_awpcp_option('send-user-ad-posted-notification', true);

	if ($send_success_notification) {
		$messagetouser = __( 'Your Ad has been submitted and an email has been sent to the email address you provided with information you will need to edit your listing.', 'AWPCP' );
		$awpcpdosuccessemail = awpcp_process_mail( $admin_sender_email,
												   $adposteremail,
												   $listingaddedsubject,
												   $user_email_body,
												   $nameofsite,
												   $admin_recipient_email );
	} else {
		$messagetouser = __("Your Ad has been submitted.","AWPCP");
		$awpcpdosuccessemail = true;
	}

	if (get_awpcp_option('adapprove') == 1 && $ad->disabled) {
		$awaitingapprovalmsg = get_awpcp_option('notice_awaiting_approval_ad');
		$messagetouser .= "<br/><br/>$awaitingapprovalmsg";
	}

	// email the administrator if the admin has this option set

	if (get_awpcp_option( 'notifyofadposted' ) && $notify_admin) {
		awpcp_process_mail( $admin_sender_email,
			 				$admin_recipient_email,
			 				$subjectadmin,
			 				$admin_email_body,
			 				$nameofsite,
			 				$admin_recipient_email );
	}

	if ($awpcpdosuccessemail) {
		$printmessagetouser = "$messagetouser";
	} else {
		$printmessagetouser = __("Although your Ad has been submitted, there was a problem encountered while attempting to email your Ad details to the email address you provided.","AWPCP");
	}

	return $printmessagetouser;
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

	$layout = get_awpcp_option('displayadlayoutcode');
	if (empty($layout)) {
		$layout = awpcp()->settings->get_option_default_value('displayadlayoutcode');
	}

	$parity = array('displayaditemseven', 'displayaditemsodd');

	$items = array();
	foreach ($ads as $i => $ad) {
		$_layout = awpcp_do_placeholders( $ad, $layout, $context );
		$_layout = str_replace("\$awpcpdisplayaditems", $parity[$i % 2], $_layout);

		if (function_exists('awpcp_featured_ads')) {
			$items[] = awpcp_featured_ad_class($ad->ad_id, $_layout);
		} else {
			$items[] = $_layout;
		}
	}

	$before_content = apply_filters('awpcp-listings-before-content', array(), $context);
	$after_content = apply_filters('awpcp-listings-after-content', array(), $context);

	if (is_array($pagination)) {
		$pagination_block = awpcp_pagination($pagination, '');
	} else {
		$pagination_block = '';
	}

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/listings.tpl.php');
		$output = ob_get_contents();
	ob_end_clean();

	return $output;
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

			$items = array();

			foreach ($ads as $ad) {
				$layout = get_awpcp_option('displayadlayoutcode');
				if (empty($layout)) {
					$layout = awpcp()->settings->get_option_default_value('displayadlayoutcode');
				}

				$layout = awpcp_do_placeholders( $ad, $layout, 'listings' );

				if (function_exists('awpcp_featured_ads')) {
					$layout = awpcp_featured_ad_class($ad->ad_id, $layout);
				}

				$items[] = $layout;
			}

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
			$output .= "<div class=\"fixfloat\"></div><div class=\"pager\">$pager1</div>";
			$output .= "<div class=\"changecategoryselect\"><form method=\"post\" action=\"$url_browsecatselect\"><select style='float:left' name=\"category_id\"><option value=\"-1\">";
			$output .= __("Select Category","AWPCP");
			$output .= "</option>";
			$allcategories=get_categorynameidall($show_category_id='');
			$output .= "$allcategories";
			$output .= "</select><input type=\"hidden\" name=\"a\" value=\"browsecat\" />&nbsp;<input class=\"button\" type=\"submit\" value=\"";
			$output .= __("Change Category","AWPCP");
			$output .= "\" /></form></div><div id='awpcpcatname' class=\"fixfloat\">";

			$category_id = (int) awpcp_request_param('category_id', -1);
			$category_id = $category_id === -1 ? (int) get_query_var('cid') : $category_id;
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

		$output .= "</div>";

	}
	return $output;
}


/**
 * Generates HTML to display login form when user is not registered.
 */
function awpcp_login_form($message=null, $redirect=null) {
	if ( is_null( $redirect ) ) {
		$redirect = awpcp_current_url();
	}

	$register_url = add_query_arg( array(
		'redirect_to' => add_query_arg( 'register', true, $redirect ),
	), site_url( 'wp-login.php?action=register', 'login' ) );

	$lost_password_url = add_query_arg( array(
		'redirect_to' => add_query_arg( 'reset', true, $redirect ),
	), wp_lostpassword_url() );

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


function awpcp_get_user_and_payment_terms_information() {
	$users = awpcp_get_users();
	$payment_terms = array();

	$payments = awpcp_payments_api();

	foreach ($users as $k => $user) {
		$user_terms = $payments->get_user_payment_terms($user->ID);
		$ids = array();

		foreach ($user_terms as $type => $terms) {
			foreach ($terms as $term) {
				$id = "{$term->type}-{$term->id}";
				if (!isset($payment_terms[$id])) {
					$payment_terms[$id] = $term;
				}
				$ids[] = $id;
			}
		}
		$users[$k]->payment_terms = join(',', $ids);
	}

	usort($payment_terms, 'awpcp_user_payment_terms_sort');

	return array($users, $payment_terms);
}


/**
 * Render the users dropdown used to post an Ad on behalf of another user.
 *
 * @param $user_id 		ID of selected user. Set to false to select none
 * 						of the users in the dropdown.
 */
function awpcp_render_users_dropdown($user_id='', $payment_term='') {
	global $current_user;
	get_currentuserinfo();

	list($users, $payment_terms) = awpcp_get_user_and_payment_terms_information();

	$json = json_encode($users);

	// TODO: is this really necesary?
	if ($user_id !== false && empty($user_id) && $current_user) {
		$selected = $current_user->ID;
	}

	usort($payment_terms, 'awpcp_user_payment_terms_sort');

	ob_start();
		include(AWPCP_DIR . '/frontend/templates/page-place-ad-details-step-users-dropdown.tpl.php');
		$html = ob_get_contents();
	ob_end_clean();

	return $html;
}
