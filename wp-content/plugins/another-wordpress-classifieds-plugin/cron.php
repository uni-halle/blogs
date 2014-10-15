<?php

function awpcp_cron_schedules($schedules) {
	$schedules['monthly'] = array(
		'interval'=> 2592000,
		'display'=>  __('Once Every 30 Days', 'AWPCP')
	);
	return $schedules;
}

// ensure we get the expiration hooks scheduled properly:
function awpcp_schedule_activation() {
    add_action('doadexpirations_hook', 'doadexpirations');
    add_action('doadcleanup_hook', 'doadcleanup');
    add_action('awpcp_ad_renewal_email_hook', 'awpcp_ad_renewal_email');
    add_action('awpcp-clean-up-payment-transactions', 'awpcp_clean_up_payment_transactions');
    add_action( 'awpcp-clean-up-payment-transactions', 'awpcp_clean_up_non_verified_ads_handler' );

    if (!wp_next_scheduled('doadexpirations_hook')) {
        wp_schedule_event( time(), 'hourly', 'doadexpirations_hook' );
    }

    if (!wp_next_scheduled('doadcleanup_hook')) {
        wp_schedule_event( time(), 'daily', 'doadcleanup_hook' );
    }

    if (!wp_next_scheduled('awpcp_ad_renewal_email_hook')) {
        wp_schedule_event( time(), 'hourly', 'awpcp_ad_renewal_email_hook' );
    }

    if (!wp_next_scheduled('awpcp-clean-up-payment-transactions')) {
        wp_schedule_event( time(), 'daily', 'awpcp-clean-up-payment-transactions' );
    }

    if ( ! wp_next_scheduled( 'awpcp-clean-up-non-verified-ads' ) ) {
        wp_schedule_event( time(), 'daily', 'awpcp-clean-up-non-verified-ads' );
    }

    // if ( awpcp_current_user_is_admin() ) {
    //     wp_clear_scheduled_hook( 'doadexpirations_hook' );
    //     wp_clear_scheduled_hook( 'doadcleanup_hook' );
    //     wp_clear_scheduled_hook( 'awpcp_ad_renewal_email_hook' );
    //     wp_clear_scheduled_hook( 'awpcp-clean-up-payment-transactions' );
    //     wp_clear_scheduled_hook( 'awpcp-clean-up-non-verified-ads' );

    //     wp_schedule_event( time() + 10, 'hourly', 'doadexpirations_hook' );
    //     wp_schedule_event( time() + 10, 'daily', 'doadcleanup_hook' );
    //     wp_schedule_event( time() + 10, 'daily', 'awpcp_ad_renewal_email_hook' );
    //     wp_schedule_event( time() + 10, 'daily', 'awpcp-clean-up-payment-transactions' );
    //     wp_schedule_event( time() + 10, 'daily', 'awpcp-clean-up-non-verified-ads' );

    //     debugp(
    //         'System date is: ' . date('d-m-Y H:i:s'),
    //         'Ad Expiration: ' . date('d-m-Y H:i:s', wp_next_scheduled('doadexpirations_hook')),
    //         'Ad Cleanup: ' . date('d-m-Y H:i:s', wp_next_scheduled('doadcleanup_hook')),
    //         'Ad Renewal Email: ' . date('d-m-Y H:i:s', wp_next_scheduled('awpcp_ad_renewal_email_hook')),
    //         'Payment transactions: ' . date('d-m-Y H:i:s', wp_next_scheduled('awpcp-clean-up-payment-transactions')),
    //         'Unverified Ads: ' . date('d-m-Y H:i:s', wp_next_scheduled('awpcp-clean-up-non-verified-ads'))
    //     );
    // }
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


    $ads = AWPCP_Ad::find("ad_enddate <= NOW() AND disabled != 1 AND payment_status != 'Unpaid' AND verified = 1");

    foreach ($ads as $ad) {
        $ad->disable();

        if ( $notify_expiring == false && $notify_admin == false ) {
            continue;
        }

        $adtitle = get_adtitle( $ad->ad_id );
        $adstartdate = date( "D M j Y G:i:s", strtotime( get_adstartdate( $ad->ad_id ) ) );

        $body = $bodybase;
        $body.= "\n\n";
        $body.= __( "Listing Details", "AWPCP" );
        $body.= "\n\n";
        $body.= __( "Ad Title:", "AWPCP" );
        $body.= " $adtitle";
        $body.= "\n\n";
        $body.= __( "Posted:", "AWPCP" );
        $body.= " $adstartdate";
        $body.= "\n\n";

        $body.= __( "Renew your ad by visiting:", "AWPCP" );
        $body.= " " . urldecode( awpcp_get_renew_ad_url( $ad->ad_id ) );
        $body.= "\n\n";

        if ( $notify_expiring ) {
            $user_email = get_adposteremail( $ad->ad_id );
            if ( ! empty( $user_email ) ) {
                awpcp_process_mail( $admin_sender_email, $user_email, $subject, $body, $nameofsite, $admin_recipient_email );
            }
        }

        if ( $notify_admin ) {
            awpcp_process_mail( $admin_sender_email, $admin_recipient_email, $subject, $body, $nameofsite, $admin_recipient_email );
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

    // also, get Ads that were disabled more than a week ago, but only if the
    // 'disable instead of delete' flag is not set.
    if (get_awpcp_option('autoexpiredisabledelete') != 1) {
        $conditions[] = "(disabled=1 AND (disabled_date + INTERVAL 7 DAY) < CURDATE())";
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
	global $wpdb;

	if (!(get_awpcp_option('sent-ad-renew-email') == 1)) {
		return;
	}

    $notification = awpcp_listing_is_about_to_expire_notification();
    $admin_sender_email = awpcp_admin_sender_email_address();

	foreach ( awpcp_get_listings_about_to_expire() as $listing ) {
        // When the user clicks the renew ad link, AWPCP uses
        // the is_about_to_expire() method to decide if the Ad
        // can be renewed. We double check here to make
        // sure users can use the link in the email immediately.
        if ( ! $listing->is_about_to_expire() ) continue;

        $email = new AWPCP_Email();

        $email->from = $admin_sender_email;
        $email->to = $listing->ad_contact_email;
        $email->subject = $notification->render_subject( $listing );
        $email->body = $notification->render_body( $listing );

		if ( $email->send() ) {
			$listing->renew_email_sent = true;
			$listing->save();
		}
	}
}

function awpcp_get_listings_about_to_expire() {
    global $wpdb;

    $end_of_range = awpcp_calculate_end_of_renew_email_date_range_from_now();

    $conditions[] = $wpdb->prepare( 'ad_enddate <= %s', date( 'Y-m-d H:i:s', $end_of_range ) );
    $conditions[] = 'disabled != 1';
    $conditions[] = 'renew_email_sent != 1';

    return AWPCP_Ad::find( implode( ' AND ', $conditions ) );
}

function awpcp_calculate_end_of_renew_email_date_range_from_now() {
    $threshold = intval( get_awpcp_option( 'ad-renew-email-threshold' ) );
    $target_date = strtotime( "+ $threshold days", current_time( 'timestamp' ) );

    return $target_date;
}


/**
 * Remove incomplete payment transactions
 */
function awpcp_clean_up_payment_transactions() {
    $threshold = awpcp_datetime( 'mysql', current_time( 'timestamp' ) - 24 * 60 * 60 );

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

/**
 * @since 3.3
 */
function awpcp_clean_up_non_verified_ads_handler() {
    return awpcp_clean_up_non_verified_ads( awpcp_listings_api(), awpcp()->settings );
}

/**
 * @since 3.0.2
 */
function awpcp_clean_up_non_verified_ads( /* AWPCP_ListingsAPI */ $listings, $settings ) {
    global $wpdb;

    if ( ! $settings->get_option( 'enable-email-verification' ) ) {
        return;
    }

    $resend_email_threshold = $settings->get_option( 'email-verification-first-threshold' );
    $delete_ads_threshold = $settings->get_option( 'email-verification-second-threshold' );

    // delete Ads that have been in a non-verified state for more than M days

    $conditions = AWPCP_Ad::get_where_conditions_for_successfully_paid_listings( array(
        'verified = 0',
        $wpdb->prepare( 'ad_postdate < ADDDATE( NOW(), INTERVAL -%d DAY )', $delete_ads_threshold )
    ) );

    foreach ( AWPCP_Ad::find( join( ' AND ', $conditions ) ) as $ad ) {
        $ad->delete();
    }

    // re-send verificaiton email for Ads that have been in a non-verified state for more than N days

    $conditions = AWPCP_Ad::get_where_conditions_for_successfully_paid_listings( array(
        'verified = 0',
        $wpdb->prepare( 'ad_postdate < ADDDATE( NOW(), INTERVAL -%d DAY )', $resend_email_threshold )
    ) );

    foreach ( AWPCP_Ad::find( join( ' AND ', $conditions ) ) as $ad ) {
        if ( intval( awpcp_get_ad_meta( $ad->ad_id, 'verification_emails_sent', true ) ) <= 1 ) {
            $listings->send_verification_email( $ad );
        }
    }
}
