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


        $settings->add_setting(
            $key,
            'addurationfreemode',
            __( 'Duration of listings in free mode (in days)', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            0,
            __( 'The end date for listings posted in free mode will be calculated using the value in this field. You can enter 0 to keep listings enabled for 10 years.', 'another-wordpress-classifieds-plugin' )
        );

        $disable_expired_listings_setting_name = __( 'Disable expired listings instead of deleting them?', 'another-wordpress-classifieds-plugin' );

        $settings->add_setting(
            $key,
            'autoexpiredisabledelete',
            $disable_expired_listings_setting_name,
            'checkbox',
            0,
            __( 'If checked, listings will remain in disabled indefinitely after they expire. If not checked, listings will be deleted after the number of days set in the next setting.', 'another-wordpress-classifieds-plugin' )
        );

        $days_before_expired_listings_are_deleted_description = __( 'If the <setting-name> setting is NOT checked, the listings will be permanently deleted from the system, after the number of days specified in this field have passed since each listing was disabled.', 'another-wordpress-classifieds-plugin' );
        $days_before_expired_listings_are_deleted_description = str_replace(
            '<setting-name>',
            '<strong>' . $disable_expired_listings_setting_name . '</strong>',
            $days_before_expired_listings_are_deleted_description
        );

        $settings->add_setting(
            $key,
            'days-before-expired-listings-are-deleted',
            __( 'Number of days before expired listings are deleted', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            7,
            $days_before_expired_listings_are_deleted_description
        );
        $settings->add_behavior( $key, 'days-before-expired-listings-are-deleted', 'shownUnless', 'autoexpiredisabledelete' );

        $settings->add_setting(
            $key,
            'allow-start-date-modification',
            __( 'Allow users to edit the start date of their listings?', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            ''
        );
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
