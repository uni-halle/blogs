<?php

function awpcp_media_uploaded_notification() {
    return new AWPCP_Media_Uploaded_Notification(
        awpcp_files_collection(),
        awpcp_listings_collection(),
        awpcp()->settings,
        awpcp_wordpress(),
        awpcp_request()
    );
}

class AWPCP_Media_Uploaded_Notification {

    private $attachments;
    private $listings;
    private $settings;
    private $wordpress;
    private $request;

    public function __construct( $attachments, $listings, $settings, $wordpress, $request ) {
        $this->attachments = $attachments;
        $this->listings = $listings;
        $this->settings = $settings;
        $this->wordpress = $wordpress;
        $this->request = $request;
    }

    public function maybe_schedule_notification( $file, $listing ) {
        $context = $this->request->param( 'context' );

        if ( $context == 'post-listing' && $this->is_listing_posted_notification_enabled() ) {
            return;
        }

        if ( $context == 'edit-listing' && $this->is_listing_edited_notification_enabled() ) {
            return;
        }

        if ( ! $this->should_send_media_uploaded_notification() && ! $file->is_awaiting_approval() ) {
            return;
        }

        $this->schedule_single_event( $file, $listing );
        $this->store_uploaded_media_information( $file, $listing );
    }

    private function is_listing_posted_notification_enabled() {
        return $this->settings->get_option( 'notifyofadposted' );
    }

    private function is_listing_edited_notification_enabled() {
        return $this->settings->get_option( 'send-listing-updated-notification-to-administrators' );
    }

    private function should_send_media_uploaded_notification() {
        return $this->settings->get_option( 'send-media-uploaded-notification-to-administrators' );
    }

    private function schedule_single_event( $uploaded_file, $listing ) {
        $event_name = "awpcp-media-uploaded-notification";
        $event_args = array( $listing->ad_id );

        if ( wp_next_scheduled( $event_name, $event_args ) ) {
            wp_clear_scheduled_hook( $event_name, $event_args );
        }

        $this->wordpress->schedule_single_event( time() + 300, $event_name, $event_args );
    }

    private function store_uploaded_media_information( $uploaded_file, $listing ) {
        $uploaded_media = $this->get_uploaded_media_information( $listing->ad_id );
        $uploaded_media[] = (array) $uploaded_file;

        $option_name = $this->get_uploaded_media_information_option_name( $listing->ad_id );
        update_option( $option_name, $uploaded_media, false );
    }

    private function get_uploaded_media_information( $listing_id ) {
        $option_name = $this->get_uploaded_media_information_option_name( $listing_id );
        return get_option( $option_name, array() );
    }

    private function get_uploaded_media_information_option_name( $listing_id ) {
        return "awpcp-media-uploaded-notification-files-{$listing_id}";
    }

    public function send_notification( $listing_id ) {
        try {
            $listing = $this->listings->get( $listing_id );
        } catch ( AWPCP_Exception $e ) {
            return;
        }

        $attachments_awaiting_approval = array();
        $other_attachments = array();

        foreach ( $this->get_uploaded_media_information( $listing_id ) as $attachment_data ) {
            try {
                $attachment = $this->attachments->get( $attachment_data['id'] );
            } catch ( AWPCP_Exception $e ) {
                continue;
            }

            if ( $attachment->is_awaiting_approval() ) {
                $attachments_awaiting_approval[] = $attachment;
            } else {
                $other_attachments[] = $attachment;
            }
        }

        if ( empty( $attachments_awaiting_approval ) && empty( $other_attachments ) ) {
            return;
        }

        if ( $other_attachments ) {
            $subject = __( 'New media was uploaded to listing: <listing-title>', 'another-wordpress-classifieds-plugin' );
        } else {
            $subject = __( 'New media is awaiting approval in listing: <listing-title>', 'another-wordpress-classifieds-plugin' );
        }

        $message = new AWPCP_Email;
        $message->to = array( awpcp_admin_email_to() );
        $message->subject = str_replace( '<listing-title>', $listing->ad_title, $subject );

        $query_args = array( 'action' => 'view', 'id' => $listing->ad_id );
        $view_listing_url = add_query_arg( $query_args, awpcp_get_admin_listings_url() );

        $query_args = array( 'action' => 'manage-images', 'id' => $listing->ad_id );
        $manage_listing_media_url = add_query_arg( $query_args, awpcp_get_admin_listings_url() );

        $params = array(
            'attachments_awaiting_approval' => $attachments_awaiting_approval,
            'other_attachments' => $other_attachments,
            'listing_title' => $listing->ad_title,
            'view_listing_url' => $view_listing_url,
            'manage_listing_media_url' => $manage_listing_media_url,
        );

        $template = AWPCP_DIR . '/templates/email/listing-media-upload-notification.plain.tpl.php';
        $message->prepare( $template, $params );

        return $message->send();
    }
}
