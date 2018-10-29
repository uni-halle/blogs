<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Erases all personal data the plugin has for the given email address.
 */
class AWPCP_PersonalDataEraser {

    /**
     * @since 3.8.6
     */
    public function __construct( $data_eraser ) {
        $this->data_eraser = $data_eraser;
    }

    /**
     * @since 3.8.6
     */
    public function erase_personal_data( $email_address, $page = 1 ) {
        $user    = get_user_by( 'email', $email_address );
        $objects = $this->data_eraser->get_objects( $user, $email_address, $page );
        $result  = $this->erase_objects( $objects );

        return array(
            'items_removed'  => $result['items_removed'],
            'items_retained' => $result['items_retained'],
            'messages'       => $result['messages'],
            'done'           => count( $objects ) < $this->data_eraser->get_page_size(),
        );
    }

    /**
     * @since 3.8.6
     */
    private function erase_objects( $objects ) {
        if ( empty( $objects ) ) {
            return array(
                'items_removed'  => false,
                'items_retained' => false,
                'messages'       => array(),
            );
        }

        return $this->data_eraser->erase_objects( $objects );
    }
}
