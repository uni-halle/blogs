<?php

class AWPCP_ListingsModerationSettings {

    public function register_settings( $settings ) {
        // Section: Ad/Listings - Moderation

        $key = $settings->add_section( 'listings-settings', __( 'Moderation', 'another-wordpress-classifieds-plugin' ), 'moderation', 10, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'onlyadmincanplaceads', __( 'Only admin can post Ads', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If checked only administrator users will be allowed to post Ads.', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'adapprove',
            __( 'Disable listings until administrator approves', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            __( 'New Ads will be in a disabled status, not visible to visitors, until the administrator approves them.', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'disable-edited-listings-until-admin-approves',
            __( 'Disable listings until administrator approves modifications', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            $settings->get_option( 'adapprove' ),
            __( 'Listings will be in a disabled status after the owners modifies them and until the administrator approves them.', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting( $key, 'enable-ads-pending-payment', __( 'Enable paid ads that are pending payment.', 'another-wordpress-classifieds-plugin' ), 'checkbox', get_awpcp_option( 'disablependingads', 1 ), __( 'Enable paid ads that are pending payment.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'enable-email-verification', __( 'Have non-registered users verify the email address used to post new Ads', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'A message with an email verification link will be sent to the email address used in the contact information. New Ads will remain disabled until the user clicks the verification link.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'email-verification-first-threshold', __( 'Number of days before the verification email is sent again', 'another-wordpress-classifieds-plugin' ), 'textfield', 5, '' );
        $settings->add_setting( $key, 'email-verification-second-threshold', __( 'Number of days before Ads that remain in a unverified status will be deleted', 'another-wordpress-classifieds-plugin' ), 'textfield', 30, '' );
        $settings->add_setting( $key, 'notice_awaiting_approval_ad', __( 'Awaiting approval message', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'All ads must first be approved by the administrator before they are activated in the system. As soon as an admin has approved your ad it will become visible in the system. Thank you for your business.', 'another-wordpress-classifieds-plugin' ), __( 'This message is shown to users right after they post an Ad, if that Ad is awaiting approval from the administrator. The message may also be included in email notifications sent when a new Ad is posted.', 'another-wordpress-classifieds-plugin') );

        $settings->add_setting( $key, 'ad-poster-email-address-whitelist', __( 'Allowed domains in Ad poster email', 'another-wordpress-classifieds-plugin' ), 'textarea', '', __( 'Only email addresses with a domain in the list above will be allowed. *.foo.com will match a.foo.com, b.foo.com, etc. but foo.com will match foo.com only. Please type a domain per line.', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting( $key, 'noadsinparentcat', __( 'Prevent ads from being posted to top level categories?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, '' );
        $settings->add_setting( $key, 'use-multiple-category-dropdowns', __( 'Use multiple dropdowns to choose categories', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If checked, a dropdown with top level categories will be shown. When the user chooses a category, a new dropdown will apper showing the sub-categories of the selected category, if any. Useful if your website supports a high number of categories.', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting( $key, 'addurationfreemode', __( 'Free ads deletion threshold', 'another-wordpress-classifieds-plugin' ), 'textfield', 0, __( 'Free listings will be deleted after the number of days entered in this field (0 for no deletion). Use the setting below to disable expired listings instead of deleting them.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'autoexpiredisabledelete', __( 'Disable expired ads instead of deleting them?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If checked, listings will be disabled, not deleted, after the number of days set as the deletion threshold have passed.', 'another-wordpress-classifieds-plugin' ) );
    }

    public function validate_all_settings( $options, $group ) {
        if ( isset( $options[ 'requireuserregistration' ] ) && $options[ 'requireuserregistration' ] && get_awpcp_option( 'enable-email-verification' ) ) {
            $message = __( "Email verification was disabled because you enabled Require Registration. Registered users don't need to verify the email address used for contact information.", 'another-wordpress-classifieds-plugin' );
            awpcp_flash( $message, 'error' );

            $options[ 'enable-email-verification' ] = 0;
        }

        return $options;
    }

    public function validate_group_settings( $options, $group ) {
        if ( isset( $options[ 'enable-email-verification' ] ) && $options[ 'enable-email-verification' ] && get_awpcp_option( 'requireuserregistration' ) ) {
            $message = __( "Email verification was not enabled because Require Registration is on. Registered users don't need to verify the email address used for contact information.", 'another-wordpress-classifieds-plugin' );
            awpcp_flash( $message, 'error' );

            $options[ 'enable-email-verification' ] = 0;
        }

        return $options;
    }
}
