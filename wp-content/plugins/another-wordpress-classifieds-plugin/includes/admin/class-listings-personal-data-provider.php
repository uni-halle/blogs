<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Exporter and eraser for Listings personal data.
 */
class AWPCP_ListingsPersonalDataProvider implements AWPCP_PersonalDataProviderInterface {

    /**
     * @var media
     */
    private $media;

    /**
     * @var object
     */
    private $regions;

    /**
     * @var
     */
    private $data_formatter;

    /**
     * @var
     */
    private $db;

    /**
     * @since 3.8.6
     */
    public function __construct( $media, $regions, $data_formatter, $db ) {
        $this->media          = $media;
        $this->regions        = $regions;
        $this->data_formatter = $data_formatter;
        $this->db             = $db;
    }

    /**
     * @since 3.8.6
     */
    public function get_page_size() {
        return 10;
    }

    /**
     * @since 3.8.6
     */
    public function get_objects( $user, $email_address, $page ) {
        $items_per_page = $this->get_page_size();

        return AWPCP_Ad::query( array(
            'where' => $this->get_user_listings_conditions( $user, $email_address ),
            'limit' => $items_per_page,
            'offset' => ( $page - 1 ) * $items_per_page,
        ) );
    }

    /**
     * @since 3.8.6
     */
    private function get_user_listings_conditions( $user, $email_address ) {
        if ( is_object( $user ) ) {
            return $this->db->prepare( 'user_id = %d OR ad_contact_email = %s', $user->ID, $email_address );
        }

        return $this->db->prepare( 'ad_contact_email = %s', $email_address );
    }

    /**
     * @since 3.8.6
     */
    public function export_objects( $listings ) {
        // TODO: Let premium modules define additional properties.
        $items = apply_filters( 'awpcp_listings_personal_data_items_descriptions', array(
            'ID'                          => __( 'Classified ID', 'another-wordpress-classifieds-plugin' ),
            'contact_name'                => __( 'Contact Name', 'another-wordpress-classifieds-plugin' ),
            'contact_phone'               => __( 'Contact Phone Number', 'another-wordpress-classifieds-plugin' ),
            'contact_phone_number_digits' => __( 'Contact Phone Number Digits', 'another-wordpress-classifieds-plugin' ),
            'contact_email'               => __( 'Contact Email', 'another-wordpress-classifieds-plugin' ),
            'ad_country'                  => __( 'Country', 'another-wordpress-classifieds-plugin' ),
            'ad_state'                    => __( 'State', 'another-wordpress-classifieds-plugin' ),
            'ad_city'                     => __( 'City', 'another-wordpress-classifieds-plugin' ),
            'ad_county_village'           => __( 'County', 'another-wordpress-classifieds-plugin' ),
            'website_url'                 => __( 'Website URL', 'another-wordpress-classifieds-plugin' ),
            'payer_email'                 => __( 'Payer Email', 'another-wordpress-classifieds-plugin' ),
            'ip_address'                  => __( 'Author IP', 'another-wordpress-classifieds-plugin' ),
        ) );

        $region_items = array(
            'country' => __( 'Country', 'another-wordpress-classifieds-plugin' ),
            'state'   => __( 'State', 'another-wordpress-classifieds-plugin' ),
            'city'    => __( 'City', 'another-wordpress-classifieds-plugin' ),
            'county'  => __( 'County', 'another-wordpress-classifieds-plugin' ),
        );

        $media_items = array(
            'URL' => __( 'URL', 'another-wordpress-classifieds-plugin' ),
        );

        $export_items = array();

        foreach ( $listings as $listing ) {
            $data = $this->data_formatter->format_data( $items, $this->get_listing_properties( $listing ) );

            foreach ( $this->regions->find_by_ad_id( $listing->ad_id ) as $region ) {
                $data = array_merge( $data, $this->data_formatter->format_data( $region_items, (array) $region ) );
            }

            $export_items[] = array(
                'group_id'    => 'awpcp-classifieds',
                'group_label' => __( 'Classifieds Listings', 'another-wordpress-classifieds-plugin' ),
                'item_id'     => "awpcp-classified-{$listing->ad_id}",
                'data'        => $data,
            );
        }

        foreach ( $this->get_listings_media( $listings ) as $media_record ) {
            $data = $this->data_formatter->format_data( $media_items, $this->get_media_properties( $media_record ) );

            $export_items[] = array(
                'group_id'    => 'awpcp-media',
                'group_label' => __( 'Classifieds Media', 'another-wordpress-classifieds-plugin' ),
                'item_id'     => "awpcp-media-{$media_record->id}",
                'data'        => $data,
            );
        }

        return $export_items;
    }

    /**
     * @since 3.8.6
     */
    private function get_listing_properties( $listing ) {
        $properties = array(
            'ID'                          => $listing->ad_id,
            'contact_name'                => $listing->ad_contact_name,
            'contact_phone'               => $listing->ad_contact_phone,
            'contact_phone_number_digits' => $listing->phone_number_digits,
            'contact_email'               => $listing->ad_contact_email,
            'website_url'                 => $listing->websiteurl,
            'payer_email'                 => $listing->payer_email,
            'ip_address'                  => $listing->posterip,
        );

        return apply_filters( 'awpcp_listing_personal_data_properties', $properties, $listing );
    }

    /**
     * @since 3.8.6
     */
    private function get_listings_media( $listings ) {
        return $this->media->query( array(
            'ad_id' => wp_list_pluck( $listings, 'ad_id' ),
        ) );
    }

    /**
     * @since 3.8.6
     */
    private function get_media_properties( $media_record ) {
        return array(
            'URL' => $media_record->get_url( 'original' ),
        );
    }

    /**
     * @since 3.8.6
     */
    public function erase_objects( $listings ) {
        $items_removed  = false;
        $items_retained = false;
        $messages       = array();

        foreach ( $listings as $listing ) {
            if ( $listing->delete() ) {
                $items_removed = true;
                continue;
            }

            $items_retained = true;

            $message = __( 'An unknown error occurred while trying to delete information for classified {listing_id}.', 'another-wordpress-classifieds-plugin' );
            $message = str_replace( '{listing_id}', $listing->ad_id, $message );

            $messages[] = $message;
        }

        return compact( 'items_removed', 'items_retained', 'messages' );
    }
}
