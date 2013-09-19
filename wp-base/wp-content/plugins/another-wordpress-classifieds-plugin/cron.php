<?php

function awpcp_cron_schedules($schedules) {
	$schedules['monthly'] = array(
		'interval'=> 2592000,
		'display'=>  __('Once Every 30 Days')
	);
	return $schedules;
}

// ensure we get the expiration hooks scheduled properly:
function awpcp_schedule_activation() {
	if (!wp_next_scheduled('doadexpirations_hook')) {
		wp_schedule_event(time(), 'hourly', 'doadexpirations_hook');
	}

	if (!wp_next_scheduled('doadcleanup_hook')) {
		wp_schedule_event(time(), 'monthly', 'doadcleanup_hook');
	}

	if (!wp_next_scheduled('awpcp_ad_renewal_email_hook')) {
		wp_schedule_event(time(), 'daily', 'awpcp_ad_renewal_email_hook');
	}

	if (!wp_next_scheduled('awpcp-clean-up-payment-transactions')) {
		wp_schedule_event(time(), 'daily', 'awpcp-clean-up-payment-transactions');
	}

	add_action('doadexpirations_hook', 'doadexpirations');
	add_action('doadcleanup_hook', 'doadcleanup');
	add_action('awpcp_ad_renewal_email_hook', 'awpcp_ad_renewal_email');
	add_action('awpcp-clean-up-payment-transactions', 'awpcp_clean_up_payment_transactions');

	// wp_schedule_event(time() + 10, 'hourly', 'doadexpirations_hook');
	// wp_schedule_event(time() + 10, 'monthly', 'doadcleanup_hook');
	// wp_schedule_event(time() + 10, 'daily', 'awpcp_ad_renewal_email_hook');
	// wp_schedule_event(time() + 10, 'daily', 'awpcp-clean-up-payment-transactions');

	// debug('System date is: ' . date('d-m-Y H:i:s'),
	// 	  'Ad Expiration: ' . date('d-m-Y H:i:s', wp_next_scheduled('doadexpirations_hook')),
	// 	  'Ad Cleanup: ' . date('d-m-Y H:i:s', wp_next_scheduled('doadcleanup_hook')),
	// 	  'Ad Renewal Email: ' . date('d-m-Y H:i:s', wp_next_scheduled('awpcp_ad_renewal_email_hook')),
	// 	  'Payment transactions: ' . date('d-m-Y H:i:s', wp_next_scheduled('awpcp-clean-up-payment-transactions')));
}


/*
 * Function to disable ads run hourly
 */
function doadexpirations() {
    global $wpdb, $nameofsite;

    $notify_admin = get_awpcp_option('notifyofadexpired');
    $notify_expiring = get_awpcp_option('notifyofadexpiring');
    // disable the ads or delete the ads?
    // 1 = disable, 0 = delete
    $disable_ads = get_awpcp_option('autoexpiredisabledelete');

    // allow users to use %s placeholder for the website name in the subject line
    $subject = get_awpcp_option('adexpiredsubjectline');
    $subject = sprintf($subject, $nameofsite);
    $bodybase = get_awpcp_option('adexpiredbodymessage');

    $admin_sender_email = awpcp_admin_sender_email_address();
    $admin_recipient_email = awpcp_admin_recipient_email_address();


    $ads = AWPCP_Ad::find("ad_enddate <= NOW() AND disabled != 1 AND payment_status != 'Unpaid'");

    foreach ($ads as $ad) {
        $ad->disable();

        if ($notify_expiring == 1 && $disable_ads) {
            $user_email = get_adposteremail($ad->ad_id);

            if ('' == $user_email) continue; // no email, can't send a message without it.

            $adtitle = get_adtitle($ad->ad_id);
            $adcontact = get_adpostername($ad->ad_id);
            $adstartdate = date("D M j Y G:i:s", strtotime(get_adstartdate($ad->ad_id)));

            $body = $bodybase;
            $body.= "\n\n";
            $body.= __("Listing Details", "AWPCP");
            $body.= "\n\n";
            $body.= __("Ad Title:", "AWPCP");
            $body.= " $adtitle";
            $body.= "\n\n";
            $body.= __("Posted:", "AWPCP");
            $body.= " $adstartdate";
            $body.= "\n\n";
            $body.= __("Renew your ad by visiting:", "AWPCP");
            $body.= " " . awpcp_get_renew_ad_url($ad->ad_id);
            $body.= "\n\n";

            awpcp_process_mail($admin_sender_email, $user_email, $subject, $body, $nameofsite, $admin_recipient_email);

            if ( $notify_admin ) {
                awpcp_process_mail($admin_sender_email, $admin_recipient_email, $subject, $body, $nameofsite, $admin_recipient_email);
            }
        }
    }
}


/*
 * Function run once per month to cleanup disabled / deleted ads.
 */
function doadcleanup() {
    global $wpdb;

    // get Unpaid Ads older than a month
    $conditions[] = "(payment_status = 'Unpaid' AND (ad_postdate + INTERVAL 30 DAY) < CURDATE()) ";

    // also, get Ads that were disabled more than a month ago, but only if the
    // 'disable instead of delete' flag is not set.
    if (get_awpcp_option('autoexpiredisabledelete') != 1) {
        $conditions[] = "(disabled=1 AND (disabled_date + INTERVAL 30 DAY) < CURDATE())";
    }

    $ads = AWPCP_Ad::find(join(' OR ', $conditions));

    foreach ($ads as $ad) {
        $ad->delete();
    }
}


/**
 * Check if any Ad is about to expire and send an email to the poster.
 *
 * This functions runs daily.
 */
function awpcp_ad_renewal_email() {
	global $wpdb, $nameofsite;

	if (!(get_awpcp_option('sent-ad-renew-email') == 1)) {
		return;
	}

	$threshold = intval(get_awpcp_option('ad-renew-email-threshold'));

	$query = 'ad_enddate <= ADDDATE(NOW(), INTERVAL %d DAY) AND ';
	$query.= 'disabled != 1 AND renew_email_sent != 1';
	$ads = AWPCP_Ad::find($wpdb->prepare($query, $threshold));

	$subject = get_awpcp_option('renew-ad-email-subject');
	$subject = sprintf($subject, $threshold);

    $admin_sender_email = awpcp_admin_sender_email_address();

	foreach ($ads as $ad) {
		$href = awpcp_get_renew_ad_url($ad->ad_id);

		// awpcp_process_mail doesn't support HTML
		$body = get_awpcp_option('renew-ad-email-body');
		$body = sprintf($body, $threshold) . "\n\n";
		$body.= __('Listing Details are below:', 'AWPCP') . "\n\n";
		$body.= __('Title', 'AWPCP') . ": " . $ad->ad_title . "\n";
		$body.= __('Posted on', 'AWPCP') . ": " . $ad->get_start_date() . "\n";
		$body.= __('Expires on', 'AWPCP') . ": " . $ad->get_end_date() . "\n\n";
		$text = __('You can renew your Ad visiting this link: %s', 'AWPCP');
		$body.= sprintf($text, $href);

		$result = awpcp_process_mail( $admin_sender_email,
                                      $ad->ad_contact_email,
                                      $subject,
                                      $body,
                                      $nameofsite,
                                      awpcp_admin_recipient_email_address() );

		if ($result == 1) {
			$ad->renew_email_sent = true;
			$ad->save();
		}
	}
}


/**
 * Remove incomplete payment transactions
 */
function awpcp_clean_up_payment_transactions() {
    $threshold = awpcp_time(current_time('timestamp') - 24*60*60, 'mysql');

    $transactions = AWPCP_Payment_Transaction::query(array(
        'status' => array(
            AWPCP_Payment_Transaction::STATUS_NEW,
            AWPCP_Payment_Transaction::STATUS_OPEN,
        ),
        'created' => array('<', $threshold),
    ));

    foreach ($transactions as $transaction) {
        $transaction->delete();
    }
}
