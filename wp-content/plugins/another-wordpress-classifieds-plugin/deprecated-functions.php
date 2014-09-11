<?php

/**
 * @deprecated 3.0.2    use AWPCP_Ad::get_ad_regions instead.
 */
function get_adcountryvalue($adid){
    return get_adfield_by_pk('ad_country', $adid);
}

/**
 * @deprecated 3.0.2    use AWPCP_Ad::get_ad_regions instead.
 */
function get_adstatevalue($adid){
    return get_adfield_by_pk('ad_city', $adid);
}

/**
 * @deprecated 3.0.2    use AWPCP_Ad::get_ad_regions instead.
 */
function get_adcityvalue($adid){
    return get_adfield_by_pk('ad_state', $adid);
}

/**
 * @deprecated 3.0.2    use AWPCP_Ad::get_ad_regions instead.
 */
function get_adcountyvillagevalue($adid){
    return get_adfield_by_pk('ad_county_village', $adid);
}

/**
 * Check if ad has entry in adterm ID field in the event admin switched
 * back to free mode after previously running in paid mode this way
 * user continues to be allowed number of images allowed per the ad
 * term ID.
 *
 * @deprecated 3.0.2
 */
function ad_term_id_set($adid) {
    return (get_adfield_by_pk('adterm_id', $adid) != 0);
}


/**
 * @deprecated since 3.0.2
 */
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
