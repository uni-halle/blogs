<?php

function awpcp_listing_upload_limits() {
    if ( ! isset( $GLOBALS['awpcp-listing-upload-limits'] ) ) {
        $GLOBALS['awpcp-listing-upload-limits'] = new AWPCP_ListingUploadLimits(
            awpcp_file_types(),
            awpcp_payments_api(),
            awpcp()->settings
        );
    }

    return $GLOBALS['awpcp-listing-upload-limits'];
}

class AWPCP_ListingUploadLimits {

    private $file_types;
    private $payments;
    private $settings;

    public function __construct( $file_types, $payments, $settings ) {
        $this->file_types = $file_types;
        $this->payments = $payments;
        $this->settings = $settings;
    }

    public function get_upload_limits_for_payment_term( $payment_term ) {
        if ( awpcp_are_images_allowed() ) {
            $upload_limits = array( 'images' => $this->get_upload_limits_for_images_in_payment_term( $payment_term ) );
        } else {
            $upload_limits = array();
        }

        return apply_filters( 'awpcp-upload-limits-for-payment-term', $upload_limits, $payment_term );
    }

    private function get_upload_limits_for_images_in_payment_term( $payment_term ) {
        return $this->build_upload_limits_for_images( $payment_term->images );
    }

    private function build_upload_limits_for_images( $allowed_file_count, $uploaded_file_count = 0 ) {
        $mime_types = $this->file_types->get_allowed_file_mime_types_in_group( 'image' );
        $extensions = $this->file_types->get_allowed_file_extesions_in_group( 'image' );

        return array(
            'mime_types' => $mime_types,
            'extensions' => $extensions,
            'allowed_file_count' => $allowed_file_count,
            'uploaded_file_count' => $uploaded_file_count,
            'min_file_size' => $this->settings->get_option( 'minimagesize' ),
            'max_file_size' => $this->settings->get_option( 'maximagesize' ),
            'min_image_width' => $this->settings->get_option( 'imgminwidth' ),
            'min_image_height' => $this->settings->get_option( 'imgminheight' ),
        );
    }

    public function get_upload_limits_for_free_board() {
        if ( awpcp_are_images_allowed() ) {
            $upload_limits = array( 'images' => $this->get_upload_limits_for_images_in_free_board() );
        } else {
            $upload_limits = array();
        }

        return apply_filters( 'awpcp-upload-limits-for-free-board', $upload_limits );
    }

    private function get_upload_limits_for_images_in_free_board() {
        return $this->build_upload_limits_for_images( $this->settings->get_option( 'imagesallowedfree', 0 ) );
    }

    public function can_add_file_to_listing( $listing, $file ) {
        $limits = $this->get_listing_upload_limits( $listing );

        $can_add_file = false;
        foreach ( $limits as $file_type => $type_limits ) {
            if ( in_array( $file->get_mime_type(), $type_limits['mime_types'] ) ) {
                $can_add_file = $type_limits['allowed_file_count'] > $type_limits['uploaded_file_count'];
                break;
            }
        }

        // TODO: do we really need this filter?
        return apply_filters( 'awpcp-can-add-file-to-listing', $can_add_file, $listing, $limits );
    }

    public function get_listing_upload_limits( $listing ) {
        $payment_term = $this->payments->get_ad_payment_term( $listing );

        if ( awpcp_are_images_allowed() ) {
            $upload_limits = array( 'images' => $this->get_listing_upload_limits_for_images( $listing, $payment_term ) );
        } else {
            $upload_limits = array();
        }

        return apply_filters( 'awpcp-listing-upload-limits', $upload_limits, $listing, $payment_term );
    }

    private function get_listing_upload_limits_for_images( $listing, $payment_term ) {
        if ( $payment_term && $payment_term->images ) {
            $upload_limits = $this->get_upload_limits_for_images_in_payment_term( $payment_term );
        } else {
            $upload_limits = $this->get_upload_limits_for_images_in_free_board();
        }

        $upload_limits['uploaded_file_count'] = $listing->count_image_files();

        return $upload_limits;
    }

    public function get_listing_upload_limits_by_file_type( $listing, $file_type ) {
        $upload_limits = $this->get_listing_upload_limits( $listing );

        if ( isset( $upload_limits[ $file_type ] ) ) {
            return $upload_limits[ $file_type ];
        } else {
            return array(
                'mime_types' => array(),
                'extensions' => array(),
                'allowed_file_count' => 0,
                'uploaded_file_count' => 0,
                'min_file_size' => 0,
                'max_file_size' => 0,
            );
        }
    }
}
