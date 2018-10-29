<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Interface for Perwonal Data Provider implementations.
 */
interface AWPCP_PersonalDataProviderInterface {

    /**
     * @since 3.8.6
     */
    public function get_page_size();

    /**
     * @since 3.8.6
     */
    public function get_objects( $user, $email_address, $page );

    /**
     * @since 3.8.6
     */
    public function export_objects( $objects );

    /**
     * @since 3.8.6
     */
    public function erase_objects( $objects );
}
