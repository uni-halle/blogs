<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Formats data from a list of properties in format expected by the Data Exporter API.
 */
class AWPCP_DataFormatter {

    /**
     * @since 3.8.6
     */
    public function format_data( $items, $properties ) {
        $data = array();

        foreach ( $items as $key => $name ) {
            if ( empty( $properties[ $key ] ) ) {
                continue;
            }

            $data[] = array(
                'name'  => $name,
                'value' => $properties[ $key ],
            );
        }

        return $data;
    }
}
