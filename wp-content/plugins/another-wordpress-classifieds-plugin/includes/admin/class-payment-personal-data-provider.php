<?php
/**
 * @package AWPCP\Admin
 */

/**
 * Exporter and eraser for Payment personal data.
 */
class AWPCP_PaymentPersonalDataProvider implements AWPCP_PersonalDataProviderInterface {

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

        return AWPCP_Payment_Transaction::query( array( 'user_id' => $user->ID ) );
    }

    /**
     * @since 3.8.6
     */
    public function export_objects( $payment_transactions ) {
        $items = array(
            'ID'          => __( 'Payment Transaction', 'another-wordpress-classifieds-plugin' ),
            'payer_email' => __( 'Payer Email', 'another-wordpress-classifieds-plugin' ),
        );

        $export_items = array();

        foreach ( $payment_transactions as $payment_transaction ) {
            $data = $this->data_formatter->format_data( $items, $this->get_payment_transaction_properties( $payment_transaction ) );

            $export_items[] = array(
                'group_id' =>  'awpcp-payments',
                'group_label' => __( 'Classifieds Payment Information', 'another-wordpress-classifieds-plugin' ),
                'item_id'     => "awpcp-payment-transaction-{$payment_transaction->id}",
                'data'        => $data,
            );
        }

        return $export_items;
    }

    /**
     * @since 3.8.6
     */
    private function get_payment_transaction_properties( $payment_transaction ) {
        return array(
            'ID'          => $payment_transaction->id,
            'payer_email' => $payment_transaction->payer_email,
        );
    }

    /**
     * @since 3.8.6
     */
    public function erase_objects( $payment_transactions ) {
        $items_removed  = false;
        $items_retained = false;
        $messages       = array();

        foreach ( $payment_transactions as $payment_transaction ) {
            if ( $payment_transaction->delete() ) {
                $items_removed = true;
                continue;
            }

            $items_retained = true;

            $message = __( 'An unknown error occurred while trying to delete classifieds payment information for transaction {transaction_id}.', 'another-wordpress-classifieds-plugin' );
            $message = str_replace( '{transaction_id}', $payment_transaction->id, $message );

            $messages[] = $message;
        }

        return compact( 'items_removed', 'items_retained', 'messages' );
    }
}
