<?php

function awpcp_user_notifications_settings() {
    return new AWPCP_UserNotificationsSettings();
}

class AWPCP_UserNotificationsSettings {

    public function register_settings( $settings ) {
        $this->register_subscriber_notifications_settings( $settings );
        $this->register_moderator_notifications_settings( $settings );
        $this->register_administrator_notifications_settings( $settings );
    }

    private function register_subscriber_notifications_settings( $settings ) {
        $key = $settings->add_section( 'listings-settings', __( 'User Notifications', 'AWPCP' ), 'user-notifications', 3, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'send-user-ad-posted-notification',
            __( 'Listing Created', 'AWPCP' ),
            'checkbox',
            1,
            __( 'An email will be sent when a listing is created.', 'AWPCP' )
        );

        $settings->add_setting( $key, 'send-ad-enabled-email', __( 'Listing Enabled', 'AWPCP' ), 'checkbox', 1, __( 'Notify Ad owner when the Ad is enabled.', 'AWPCP' ) );
        $settings->add_setting( $key, 'sent-ad-renew-email', __( 'Listing Needs to be Renewed', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent to remind the user to Renew the Ad when the Ad is about to expire.', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'ad-renew-email-threshold',
            __( 'When should AWPCP send the expiration notice?', 'AWPCP' ),
            'textfield',
            5,
            __( 'Enter the number of days before the ad expires to send the email.', 'AWPCP' )
        );

        $settings->add_setting( $key, 'notifyofadexpiring', __( 'Listing Expired', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when the Ad expires.', 'AWPCP' ) );
    }

    private function register_moderator_notifications_settings( $settings ) {
        $key = $settings->add_section( 'listings-settings', __( 'Moderator Notifications', 'AWPCP' ), 'moderator-notifications', 4, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'send-listing-posted-notification-to-moderators',
            __( 'Listing Created', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'notifyofadposted' ),
            __( 'An email will be sent to moderators when a listing is created.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'send-listing-updated-notification-to-moderators',
            __( 'Listing Edited', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'notifyofadposted' ),
            __( 'An email will be sent to moderators when a listing is edited.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'send-listing-awaiting-approval-notification-to-moderators',
            __( 'Listing Awaiting Approval', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'notifyofadposted' ),
            __( 'An email will be sent to moderator users every time a listing needs to be approved.', 'AWPCP' )
        );
    }

    private function register_administrator_notifications_settings( $settings ) {
        $key = $settings->add_section( 'listings-settings', __( 'Admin Notifications', 'AWPCP' ), 'admin-notifications', 5, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'notifyofadposted',
            __( 'Listing Created', 'AWPCP' ),
            'checkbox',
            1,
            __( 'An email will be sent when a listing is created.', 'AWPCP' )
        );

        $settings->add_setting( $key, 'notifyofadexpired', __( 'Listing Expired', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when the Ad expires.', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'send-listing-updated-notification-to-administrators',
            __( 'Listing Edited', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'notifyofadposted' ),
            __( 'An email will be sent to administrator when a listing is edited.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'send-listing-awaiting-approval-notification-to-administrators',
            __( 'Listing Awaiting Approval', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'notifyofadposted' ),
            __( 'An email will be sent to administrator users every time a listing needs to be approved.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'send-listing-flagged-notification-to-administrators',
            __( 'Listing Was Flagged', 'AWPCP' ),
            'checkbox',
            true,
            __( 'An email will be sent to administrator users when a listing is flagged.', 'AWPCP' )
        );
    }
}
