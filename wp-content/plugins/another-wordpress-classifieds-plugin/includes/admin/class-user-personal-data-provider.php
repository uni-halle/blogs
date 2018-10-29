<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Exporter and eraser for User personal data.
 */
class AWPCP_UserPersonalDataProvider implements AWPCP_PersonalDataProviderInterface {

    /**
     * @var
     */
    private $data_formatter;

    /**
     * @since 3.8.6
     */
    public function __construct( $data_formatter ) {
        $this->data_formatter = $data_formatter;
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
        if ( ! is_object( $user ) ) {
            return array();
        }

        $metadata = get_user_meta( $user->ID, 'awpcp-profile', true );

        if ( ! $metadata ) {
            return array();
        }

        $user->classifieds_profile = $metadata;

        return array( $user );
    }

    /**
     * @since 3.8.6
     */
    public function export_objects( $users ) {
        $items = array(
            'address' => __( 'Contact Address', 'another-wordpress-classifieds-plugin' ),
            'phone'   => __( 'Contact Phone', 'another-wordpress-classifieds-plugin' ),
            'country' => __( 'Default Country', 'another-wordpress-classifieds-plugin' ),
            'state'   => __( 'Default State', 'another-wordpress-classifieds-plugin' ),
            'city'    => __( 'Default City', 'another-wordpress-classifieds-plugin' ),
            'county'  => __( 'Default County', 'another-wordpress-classifieds-plugin' ),
        );

        $export_items = array();

        foreach ( $users as $user ) {
            $data = $this->data_formatter->format_data( $items, $user->classifieds_profile );

            if ( empty( $data ) ) {
                continue;
            }

            $export_items[] = array(
                'group_id'    => 'awpcp-profile',
                'group_label' => __( 'Classifieds Profile', 'another-wordpress-classifieds-plugin' ),
                'item_id'     => "user-{$user->ID}",
                'data'        => $data,
            );
        }

        return $export_items;
    }

    /**
     * @since 3.8.6
     */
    public function erase_objects( $users ) {
        $items_removed  = false;
        $items_retained = false;
        $messages       = array();

        foreach ( $users as $user ) {
            if ( delete_user_meta( $user->ID, 'awpcp-profile' ) ) {
                $items_removed = true;
                continue;
            }

            $items_retained = true;

            $messages[] = __( 'An unknown error occurred while trying to delete information from Classifieds Profile', 'another-wordpress-classifieds-plugin' );
        }

        return compact( 'items_removed', 'items_retained', 'messages' );
    }
}
