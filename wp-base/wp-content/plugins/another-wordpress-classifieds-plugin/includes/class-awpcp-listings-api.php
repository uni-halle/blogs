<?php

/**
 * @since 3.0.2
 */
function awpcp_listings_api() {
    return new AWPCP_ListingsAPI( awpcp_request(), awpcp()->settings );
}


class AWPCP_ListingsAPI {
    private static $instance = null;

    private $request = null;
    private $settings = null;

    public function __construct( /*AWPCP_Request*/ $request = null, $settings ) {
        $this->request = $request;
        $this->settings = $settings;

        add_action( 'template_redirect', array( $this, 'dispatch' ) );
    }

    /**
     * @since 3.0.2
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new AWPCP_ListingsAPI();
        }
        return self::$instance;
    }

    /**
     * @since 3.0.2
     * @tested
     */
    public function dispatch() {
        $awpcpx = $this->request->get_query_var( 'awpcpx' );
        $module = $this->request->get_query_var( 'awpcp-module' );
        $action = $this->request->get_query_var( 'awpcp-action' );

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

        wp_redirect( add_query_arg( 'verified', true, url_showad( $ad->ad_id ) ) );
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

        if ( $ad->verified ) {
            $this->send_ad_posted_email_notifications( $ad, array(), $transaction );
        } else {
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
        // if Ad is enabled and should be disabled, then disable it, otherwise
        // do not alter the Ad disabled status.
        if ( ! $ad->disabled && $ad->should_be_disabled() ) {
            $ad->disable();
            $ad->clear_disabled_date();
        } else if ( $ad->disabled ) {
            $ad->clear_disabled_date();
        }

        if ( $ad->verified ) {
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

        // TODO: move awpcp_calculate_ad_disabled_state() function to Ad model
        if ( $ad->disabled && ! awpcp_calculate_ad_disabled_state( null, null, $ad->payment_status ) ) {
            $ad->enable( /*approve images?*/ ! get_awpcp_option( 'imagesapprove', false ) );
        }

        $this->send_ad_posted_email_notifications( $ad );

        $ad->save();
    }

    /**
     * @since 3.0.2
     */
    public function get_ad_alerts( $ad ) {
        $alerts = array();

        if ( ! $ad->verified ) {
            $alerts[] = __( 'You need to verify the email address used as the contact email address for this Ad. The Ad will remain in a disabled status until you verify you address. A verification email has been sent to you.', 'AWPCP' );
        }

        if ( get_awpcp_option( 'adapprove' ) == 1 && $ad->disabled ) {
            $alerts[] = get_awpcp_option( 'notice_awaiting_approval_ad' );
        }

        if ( get_awpcp_option( 'imagesapprove' ) == 1 ) {
            $alerts[] = __( "If you have uploaded images your images will not show up until an admin has approved them.", "AWPCP" );
        }

        return $alerts;
    }

    /**
     * @since 3.0.2
     */
    public function send_ad_posted_email_notifications( $ad, $messages = array(), $transaction = null ) {
        $messages = array_merge( $messages, $this->get_ad_alerts( $ad ) );
        return awpcp_ad_posted_email( $ad, $transaction, join( "\n\n", $messages ) );
    }

    /**
     * @since 3.0.2
     */
    public function send_ad_updated_email_notifications( $ad, $messages = array() ) {
        $messages = array_merge( $messages, $this->get_ad_alerts( $ad ) );

        // send user notification
        if ( ! awpcp_current_user_is_admin() || AWPCP_Ad::belongs_to_user( $ad->ad_id, wp_get_current_user()->ID ) ) {
            awpcp_ad_updated_email( $ad, join( "\n\n", $messages ) );
        }

        $ad_approve = get_awpcp_option('adapprove') == 1;
        $images_approve = get_awpcp_option('imagesapprove') == 1;

        // send admin notification
        if ( ( $ad_approve || $images_approve ) && $ad->disabled ) {
            awpcp_ad_awaiting_approval_email( $ad, $ad_approve, $images_approve );
        }
    }

    /**
     * @since 3.0.2
     */
    public function send_verification_email( $ad ) {
        $mail = new AWPCP_Email;
        $mail->to[] = awpcp_format_email_address( $ad->ad_contact_email, $ad->ad_contact_name );
        $mail->subject = sprintf( __( 'Verify the email address used for Ad "%s"', 'AWPCP' ), $ad->get_title() );

        $verification_link = awpcp_get_email_verification_url( $ad->ad_id );

        $template = AWPCP_DIR . '/frontend/templates/email-ad-awaiting-verification.tpl.php';
        $mail->prepare( $template, array(
            'contact_name' => $ad->ad_contact_name,
            'ad_title' => $ad->get_title(),
            'verification_link' => $verification_link
        ) );

        if ( $mail->send() ) {
            $emails_sent = intval( awpcp_get_ad_meta( $ad->ad_id, 'verification_emails_sent', true ) );
            awpcp_update_ad_meta( $ad->ad_id, 'verification_email_sent_at', awpcp_datetime() );
            awpcp_update_ad_meta( $ad->ad_id, 'verification_emails_sent', $emails_sent + 1 );
        }
    }
}
