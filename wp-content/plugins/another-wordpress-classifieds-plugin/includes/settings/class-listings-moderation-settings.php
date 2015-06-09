<?php

class AWPCP_ListingsModerationSettings {

    public function register_settings( $settings ) {
        // Section: Ad/Listings - Moderation

        $key = $settings->add_section( 'listings-settings', __( 'Moderation', 'AWPCP' ), 'moderation', 10, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'onlyadmincanplaceads', __( 'Only admin can post Ads', 'AWPCP' ), 'checkbox', 0, __( 'If checked only administrator users will be allowed to post Ads.', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'adapprove',
            __( 'Disable listings until administrator approves', 'AWPCP' ),
            'checkbox',
            0,
            __( 'New Ads will be in a disabled status, not visible to visitors, until the administrator approves them.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'disable-edited-listings-until-admin-approves',
            __( 'Disable listings until administrator approves modifications', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'adapprove' ),
            __( 'Listings will be in a disabled status after the owners modifies them and until the administrator approves them.', 'AWPCP' )
        );

        $settings->add_setting( $key, 'enable-ads-pending-payment', __( 'Enable paid ads that are pending payment.', 'AWPCP' ), 'checkbox', get_awpcp_option( 'disablependingads', 1 ), __( 'Enable paid ads that are pending payment.', 'AWPCP' ) );
        $settings->add_setting( $key, 'enable-email-verification', __( 'Have non-registered users verify the email address used to post new Ads', 'AWPCP' ), 'checkbox', 0, __( 'A message with an email verification link will be sent to the email address used in the contact information. New Ads will remain disabled until the user clicks the verification link.', 'AWPCP' ) );
        $settings->add_setting( $key, 'email-verification-first-threshold', __( 'Number of days before the verification email is sent again', 'AWPCP' ), 'textfield', 5, '' );
        $settings->add_setting( $key, 'email-verification-second-threshold', __( 'Number of days before Ads that remain in a unverified status will be deleted', 'AWPCP' ), 'textfield', 30, '' );
        $settings->add_setting( $key, 'notice_awaiting_approval_ad', __( 'Awaiting approval message', 'AWPCP' ), 'textarea', __( 'All ads must first be approved by the administrator before they are activated in the system. As soon as an admin has approved your ad it will become visible in the system. Thank you for your business.', 'AWPCP' ), __( 'This message is shown to users right after they post an Ad, if that Ad is awaiting approval from the administrator. The message may also be included in email notifications sent when a new Ad is posted.', 'AWPCP') );

        $settings->add_setting( $key, 'ad-poster-email-address-whitelist', __( 'Allowed domains in Ad poster email', 'AWPCP' ), 'textarea', '', __( 'Only email addresses with a domain in the list above will be allowed. *.foo.com will match a.foo.com, b.foo.com, etc. but foo.com will match foo.com only. Please type a domain per line.', 'AWPCP' ) );

        $settings->add_setting( $key, 'noadsinparentcat', __( 'Prevent ads from being posted to top level categories?', 'AWPCP' ), 'checkbox', 0, '' );
        $settings->add_setting( $key, 'use-multiple-category-dropdowns', __( 'Use multiple dropdowns to choose categories', 'AWPCP' ), 'checkbox', 0, __( 'If checked, a dropdown with top level categories will be shown. When the user chooses a category, a new dropdown will apper showing the sub-categories of the selected category, if any. Useful if your website supports a high number of categories.', 'AWPCP' ) );

        $settings->add_setting( $key, 'addurationfreemode', __( 'Free Ads expiration threshold', 'AWPCP' ), 'textfield', 0, __( 'Expire free ads after how many days? (0 for no expiration).', 'AWPCP' ) );
        $settings->add_setting( $key, 'autoexpiredisabledelete', __( 'Disable expired ads instead of deleting them?', 'AWPCP' ), 'checkbox', 0, __( 'Check to disable.', 'AWPCP' ) );
    }

    public function validate_all_settings( $options, $group ) {
        if ( isset( $options[ 'requireuserregistration' ] ) && $options[ 'requireuserregistration' ] && get_awpcp_option( 'enable-email-verification' ) ) {
            $message = __( "Email verification was disabled because you enabled Require Registration. Registered users don't need to verify the email address used for contact information.", 'AWPCP' );
            awpcp_flash( $message, 'error' );

            $options[ 'enable-email-verification' ] = 0;
        }

        return $options;
    }

    public function validate_group_settings( $options, $group ) {
        if ( isset( $options[ 'enable-email-verification' ] ) && $options[ 'enable-email-verification' ] && get_awpcp_option( 'requireuserregistration' ) ) {
            $message = __( "Email verification was not enabled because Require Registration is on. Registered users don't need to verify the email address used for contact information.", 'AWPCP' );
            awpcp_flash( $message, 'error' );

            $options[ 'enable-email-verification' ] = 0;
        }

        return $options;
    }
}
