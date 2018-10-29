<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Exports all personal data the plugin has for the given email address.
 */
class AWPCP_PersonalDataExporter {

    /**
     * @since 3.8.6
     */
    public function __construct( $data_exporter ) {
        $this->data_exporter = $data_exporter;
    }

    /**
     * @since 3.8.6
     */
    public function export_personal_data( $email_address, $page = 1 ) {
        $user    = get_user_by( 'email', $email_address );
        $objects = $this->data_exporter->get_objects( $user, $email_address, $page );

        return array(
            'data' => $this->export_objects( $objects ),
            'done' => count( $objects ) < $this->data_exporter->get_page_size(),
        );
    }

    /**
     * @since 3.8.6
     */
    private function export_objects( $objects ) {
        if ( empty( $objects ) ) {
            return array();
        }

        return $this->data_exporter->export_objects( $objects );
    }
}
