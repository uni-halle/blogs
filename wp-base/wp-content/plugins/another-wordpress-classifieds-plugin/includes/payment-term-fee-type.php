<?php

class AWPCP_FeeType extends AWPCP_PaymentTermType {

    const TYPE = 'fee';

    public function __construct() {
        parent::__construct(_x('Fee', 'payment term type', 'AWPCP'), self::TYPE, '');
        add_action('awpcp-transaction-status-updated', array($this, 'update_buys_count'), 10, 2);
    }

    public function update_buys_count($transaction, $status) {
        if ($transaction->is_completed() && $transaction->was_payment_successful()) {
            if ($transaction->get('payment-term-type', false) !== self::TYPE)
                return;

            $term = self::find_by_id($transaction->get('payment-term-id'));

            if (is_null($term)) return;

            $term->buys = $term->buys + 1;
            $term->save();
        }
    }

    public function find_by_id($id) {
        if (absint($id) === 0)
            return $this->get_free_payment_term();
        return AWPCP_Fee::find_by_id($id);
    }

    private function get_free_payment_term() {
        return new AWPCP_Fee(array(
            'id' => 0,
            'name' => __('Free Listing', 'AWPCP'),
            'description' => '',
            'duration_amount' => get_awpcp_option('addurationfreemode'),
            'duration_interval' => AWPCP_Fee::INTERVAL_DAY,
            'price' => 0,
            'credits' => 0,
            'categories' => array(),
            'images' => get_awpcp_option('imagesallowedfree'),
            'ads' => 1,
            'characters' => get_awpcp_option( 'maxcharactersallowed' ),
            'title_characters' => get_awpcp_option( 'characters-allowed-in-title' ),
            'buys' => 0,
            'featured' => 0,
            'private' => 0,
        ));
    }

    public function get_payment_terms() {
        global $wpdb;

        if (!awpcp_payments_api()->payments_enabled()) {
            return array($this->get_free_payment_term());
        }

        $order = get_awpcp_option( 'fee-order' );
        $direction = get_awpcp_option( 'fee-order-direction' );

        switch ($order) {
            case 1:
                $orderby = array( 'adterm_name', $direction );
                break;
            case 2:
                $orderby = array( "amount $direction, adterm_name", $direction );
                break;
            case 3:
                $orderby = array( "imagesallowed $direction, adterm_name", $direction );
                break;
            case 5:
                $orderby = array( "_duration_interval $direction, rec_period $direction, adterm_name", $direction );
                break;
        }

        if ( awpcp_current_user_is_admin() ) {
            $args = array(
                'orderby' => $orderby[0],
                'order' => $orderby[1],
            );
        } else {
            $args = array(
                'where' => 'private = 0',
                'orderby' => $orderby[0],
                'order' => $orderby[1],
            );
        }

        return AWPCP_Fee::query($args);
    }

    public function get_user_payment_terms($user_id) {
        static $terms = null;

        if ( is_null( $terms ) ) {
            $terms = $this->get_payment_terms();
        }

        return $terms;
    }
}
