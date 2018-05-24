<?php

/**
 * @since 3.0.2
 */
function awpcp_listings_api() {
    if ( ! isset( $GLOBALS['awpcp-listings-api'] ) ) {
        $GLOBALS['awpcp-listings-api'] = new AWPCP_ListingsAPI(
            awpcp_listings_metadata(),
            awpcp_request(),
            awpcp()->settings
        );
    }

    return $GLOBALS['awpcp-listings-api'];
}

class AWPCP_ListingsAPI {

    private $metadata = null;
    private $request = null;
    private $settings = null;

    public function __construct( $metadata, /*AWPCP_Request*/ $request = null, $settings ) {
        $this->metadata = $metadata;
        $this->request = $request;
        $this->settings = $settings;

        add_action( 'template_redirect', array( $this, 'dispatch' ) );
    }

    /**
     * @since 3.0.2
     * @deprecated 3.4
     */
    public static function instance() {
        _deprecated_function( __FUNCTION__, '3.4', 'awpcp_listings_api' );
        return awpcp_listings_api();
    }

    /**
     * @since 3.0.2
     * @tested
     */
    public function dispatch() {
        $awpcpx = $this->request->get_query_var( 'awpcpx' );
        $module = $this->request->get_query_var( 'awpcp-module', $this->request->get_query_var( 'module' ) );
        $action = $this->request->get_query_var( 'awpcp-action', $this->request->get_query_var( 'action' ) );

        if ( $awpcpx && $module == 'listings' ) {
            switch ( $action ) {
                case 'verify':
                    $this->handle_email_verification_link();
            }
        }
    }

    /**
     * @since 3.0.2
     */
    public function handle_email_verification_link() {
        $ad_id = $this->request->get_query_var( 'awpcp-ad' );
        $hash = $this->request->get_query_var( 'awpcp-hash' );

        $ad = AWPCP_Ad::find_by_id( $ad_id );

        if ( is_null( $ad ) || ! awpcp_verify_email_verification_hash( $ad_id, $hash ) ) {
            wp_redirect( awpcp_get_main_page_url() );
            return;
        }

        $this->verify_ad( $ad );

        wp_redirect( esc_url_raw( add_query_arg( 'verified', true, url_showad( $ad->ad_id ) ) ) );
        return;
    }

    /**
     * API Methods
     */

    public function create_ad() {}
    public function update_ad() {}

    /**
     * @since 3.0.2
     */
    public function consolidate_new_ad( $ad, $transaction ) {
        do_action( 'awpcp-place-ad', $ad, $transaction );

        $this->metadata->set( $ad->ad_id, 'reviewed', false );

        if ( $ad->verified && ! awpcp_current_user_is_moderator() ) {
            $this->send_ad_posted_email_notifications( $ad, array(), $transaction );
        } else if ( ! $ad->verified ) {
            $this->send_verification_email( $ad );
        }

        if ( ! $ad->verified && ! $ad->disabled ) {
            $ad->disable();
        }

        $transaction->set( 'ad-consolidated-at', awpcp_datetime() );
    }

    /**
     * @since 3.0.2
     */
    public function consolidate_existing_ad( $ad ) {
        $should_disable_listing = awpcp_should_disable_existing_listing( $ad );

        // if Ad is enabled and should be disabled, then disable it, otherwise
        // do not alter the Ad disabled status.
        if ( $should_disable_listing && ! $ad->disabled ) {
            $ad->disable();
            $ad->clear_disabled_date();
        } else if ( $should_disable_listing ) {
            $ad->clear_disabled_date();
        }

        if ( $ad->verified && ! awpcp_current_user_is_moderator() ) {
            $this->send_ad_updated_email_notifications( $ad );
        }
    }

    public function update_listing_verified_status( $listing, $transaction ) {
        if ( $listing->verified ) {
            return;
        } else if ( $this->should_mark_listing_as_verified( $listing, $transaction ) ) {
            $listing->verified = true;
            $listing->verified_at = awpcp_datetime();
        } else {
            $listing->verified = false;
            $listing->verified_at = null;
        }
    }

    private function should_mark_listing_as_verified( $listing, $transaction ) {
        if ( ! $this->settings->get_option( 'enable-email-verification' ) ) {
            return true;
        } else if ( is_user_logged_in() ) {
            return true;
        } else if ( $transaction->payment_is_completed() || $transaction->payment_is_pending() ) {
            return true;
        }
        return false;
    }

    /**
     * @since 3.0.2
     * @tested
     */
    public function verify_ad( $ad ) {
        if ( $ad->verified ) return;

        $timestamp = current_time( 'timestamp' );
        $now = awpcp_datetime( 'mysql', $timestamp );

        $ad->verified = true;
        $ad->verified_at = awpcp_datetime();
        $ad->set_start_date( $now );
        $ad->set_end_date( $ad->get_payment_term()->calculate_end_date( $timestamp ) );

        if ( $ad->disabled && awpcp_should_enable_new_listing_with_payment_status( $ad, $ad->payment_status ) ) {
            $ad->enable( /*approve images?*/ ! get_awpcp_option( 'imagesapprove', false ) );
        }

        if ( ! awpcp_current_user_is_moderator() ) {
            $this->send_ad_posted_email_notifications( $ad );
        }

        $ad->save();
    }

    /**
     * @since 3.0.2
     */
    public function get_ad_alerts( $ad ) {
        $alerts = array();

        if ( get_awpcp_option( 'adapprove' ) == 1 && $ad->disabled ) {
            $alerts[] = get_awpcp_option( 'notice_awaiting_approval_ad' );
        }

        if ( get_awpcp_option( 'imagesapprove' ) == 1 ) {
            $alerts[] = __( "If you have uploaded images your images will not show up until an admin has approved them.", 'another-wordpress-classifieds-plugin' );
        }

        return $alerts;
    }

    /**
     * @since 3.0.2
     */
    public function send_ad_posted_email_notifications( $ad, $messages = array(), $transaction = null ) {
        $messages = array_merge( $messages, $this->get_ad_alerts( $ad ) );

        awpcp_send_listing_posted_notification_to_user( $ad, $transaction, join( "\n\n", $messages ) );
        awpcp_send_listing_posted_notification_to_moderators( $ad, $transaction, join( "\n\n", $messages ) );

        $moderate_listings = get_awpcp_option( 'adapprove' );
        $moderate_images = get_awpcp_option('imagesapprove') == 1;

        if ( ( $moderate_listings || $moderate_images ) && $ad->disabled ) {
            awpcp_send_listing_awaiting_approval_notification_to_moderators(
                $ad, $moderate_listings, $moderate_images
            );
        }
    }

    /**
     * @since 3.0.2
     */
    public function send_ad_updated_email_notifications( $ad, $messages = array() ) {
        $messages = array_merge( $messages, $this->get_ad_alerts( $ad ) );

        awpcp_send_listing_updated_notification_to_user( $ad, join( "\n\n", $messages ) );
        awpcp_send_listing_updated_notification_to_moderators( $ad, join( "\n\n", $messages ) );

        $moderate_modifications = get_awpcp_option( 'disable-edited-listings-until-admin-approves' );
        $moderate_images = get_awpcp_option('imagesapprove') == 1;

        if ( ( $moderate_modifications || $moderate_images ) && $ad->disabled ) {
            awpcp_send_listing_awaiting_approval_notification_to_moderators(
                $ad, $moderate_modifications, $moderate_images
            );
        }
    }

    /**
     * @since 3.0.2
     */
    public function send_verification_email( $ad ) {
        $mail = new AWPCP_Email;
        $mail->to[] = awpcp_format_recipient_address( $ad->ad_contact_email, $ad->ad_contact_name );
        $subject = get_awpcp_option( 'verifyemailsubjectline' );
        $message = get_awpcp_option( 'verifyemailbodymessage' );
        $mail->subject = str_replace( '$title', $ad->get_title(), $subject );

        $verification_link = awpcp_get_email_verification_url( $ad->ad_id );

        $message = str_replace( '$title', $ad->get_title(), $message );
        $message = str_replace( '$author_name', $ad->ad_contact_name, $message );
        $message = str_replace( '$verification_link', $verification_link, $message );
        $message = str_replace( '$website_name', awpcp_get_blog_name(), $message );
        $message = str_replace( '$website_url', home_url(), $message );

        $mail->body = $message;

        if ( $mail->send() ) {
            $emails_sent = intval( awpcp_get_ad_meta( $ad->ad_id, 'verification_emails_sent', true ) );
            awpcp_update_ad_meta( $ad->ad_id, 'verification_email_sent_at', awpcp_datetime() );
            awpcp_update_ad_meta( $ad->ad_id, 'verification_emails_sent', $emails_sent + 1 );
        }
    }

    /**
     * @since 3.4
     */
    public function flag_listing( $listing ) {
        $listing->flagged = true;

        if ( $result = $listing->save() ) {
            awpcp_send_listing_was_flagged_notification( $listing );
        }

        return $result;
    }

    /**
     * @since 3.4
     */
    public function unflag_listing( $listing ) {
        $listing->flagged = false;
        return $listing->save();
    }
}
