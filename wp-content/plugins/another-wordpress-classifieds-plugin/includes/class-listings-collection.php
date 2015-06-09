<?php

/**
 * @since 3.3
 */
function awpcp_listings_collection() {
    return new AWPCP_ListingsCollection( awpcp_listings_finder(), awpcp()->settings, $GLOBALS['wpdb'] );
}

/**
 * @since 3.2.2
 */
class AWPCP_ListingsCollection {

    private $finder;
    private $settings;
    private $db;

    public function __construct( $finder, $settings, $db ) {
        $this->finder = $finder;
        $this->settings = $settings;
        $this->db = $db;
    }

    /**
     * @since 3.3
     */
    public function get( $listing_id ) {
        if ( $listing_id <= 0 ) {
            $this->throw_no_listing_was_found_with_id_exception( $listing_id );
        }

        $listings = $this->finder->find( array( 'id' => $listing_id, 'limit' => null, 'order' => null ) );

        if ( empty( $listings ) ) {
            $this->throw_no_listing_was_found_with_id_exception( $listing_id );
        }

        return array_shift( $listings );
    }

    private function throw_no_listing_was_found_with_id_exception( $listing_id ) {
        $message = __( 'No Listing was found with id: %d.', 'AWPCP' );
        throw new AWPCP_Exception( sprintf( $message, $listing_id ) );
    }

    /**
     * @since 3.2.2
     */
    public function find_by_id( $ad_id ) {
        return AWPCP_Ad::find_by_id( $ad_id );
    }

    /**
     * @since 3.3
     */
    public function find_all_by_id( $identifiers ) {
        $identifiers = array_filter( array_map( 'intval', $identifiers ) );

        if ( count( $identifiers ) > 0 ) {
            $where = 'ad_id IN ( ' . implode( ',', $identifiers ) . ' )';
            return AWPCP_Ad::query( array( 'where' => $where ) );
        } else {
            return array();
        }
    }

    /**
     * @since 3.3
     */
    private function find_valid_listings( $params = array() ) {
        $params = wp_parse_args( $params, array(
            'items_per_page' => 10,
            'page' => 1,
            'conditions' => array(),
        ) );

        $params['conditions'] = AWPCP_Ad::get_where_conditions_for_valid_ads( $params['conditions'] );

        return AWPCP_Ad::query( array(
            'where' => implode( ' AND ', $params['conditions'] ),
            'limit' => $params['items_per_page'],
            'offset' => ( $params['page'] - 1 ) * $params['items_per_page']
        ) );
    }

    /**
     * @since 3.3
     */
    private function count_valid_listings( $conditions = array() ) {
        $conditions = AWPCP_Ad::get_where_conditions_for_valid_ads( $conditions );
        return AWPCP_Ad::count( implode( ' AND ', $conditions ) );
    }

    /**
     * @since 3.3
     */
    public function find_listings( $params = array() ) {
        return $this->find_valid_listings( $params );
    }

    /**
     * @since 3.3
     */
    public function count_listings() {
        return $this->count_valid_listings();
    }

    /**
     * @since 3.3
     */
    public function find_enabled_listings( $params = array() ) {
        $params = array_merge( $params, array( 'conditions' => array( 'disabled = 0' ) ) );
        return $this->find_valid_listings( $params );
    }

    public function find_enabled_listings_with_query( $query ) {
        return $this->finder->find( $this->make_enabled_listings_query( $query ) );
    }

    private function make_enabled_listings_query( $query ) {
        return $this->make_valid_listings_query( array_merge( $query, array( 'disabled' => false ) ) );
    }

    private function make_valid_listings_query( $query ) {
        return $this->make_successfully_paid_listings_query( array_merge( $query, array( 'verified' => true ) ) );
    }

    private function make_successfully_paid_listings_query( $query ) {
        $enable_listings_pending_payment = $this->settings->get_option( 'enable-ads-pending-payment' );
        $payments_are_enabled = $this->settings->get_option( 'freepay' ) == 1;

        if ( ! $enable_listings_pending_payment && $payments_are_enabled ) {
            $query['payment_status'] = array( 'compare' => 'not', 'values' => array( 'Pending', 'Unpaid' ) );
        } else {
            $query['payment_status'] = array( 'compare' => 'not', 'values' => 'Unpaid' );
        }

        return $query;
    }

    public function find_expired_listings_with_query( $query ) {
        return $this->finder->find( $this->make_expired_listings_query( $query ) );
    }

    private function make_expired_listings_query( $query ) {
        $query['end_date'] = array( 'compare' => '<', 'value' => current_time( 'mysql' ) );
        return $this->make_valid_listings_query( $query );
    }

    public function find_listings_awaiting_approval_with_query( $query ) {
        return $this->finder->find( $this->make_listings_awaiting_approval_query( $query ) );
    }

    private function make_listings_awaiting_approval_query( $query ) {
        $query = array_merge( $query, array( 'disabled' => true, 'disabled_date' => 'NULL' ) );
        return $this->make_valid_listings_query( $query );
    }

    public function find_valid_listings_with_query( $query ) {
        return $this->finder->find( $this->make_valid_listings_query( $query ) );
    }

    public function find_successfully_paid_listings_with_query( $query ) {
        return $this->finder->find( $this->make_successfully_paid_listings_query( $query ) );
    }

    public function find_listings_with_query( $query ) {
        return $this->finder->find( $query );
    }

    /**
     * @since 3.3
     */
    public function count_enabled_listings() {
        return $this->count_valid_listings( array( 'disabled = 0' ) );
    }

    public function count_enabled_listings_with_query( $query ) {
        return $this->finder->count( $this->make_enabled_listings_query( $query ) );
    }

    public function count_expired_listings_with_query( $query ) {
        return $this->finder->count( $this->make_expired_listings_query( $query ) );
    }

    public function count_listings_awaiting_approval_with_query( $query ) {
        return $this->finder->count( $this->make_listings_awaiting_approval_query( $query ) );
    }

    public function count_valid_listings_with_query( $query ) {
        return $this->finder->count( $this->make_valid_listings_query( $query ) );
    }

    public function count_successfully_paid_listings_with_query( $query ) {
        return $this->finder->count( $this->make_successfully_paid_listings_query( $query ) );
    }

    public function count_listings_with_query( $query ) {
        return $this->finder->count( $query );
    }

    /**
     * @since 3.3
     */
    public function find_user_listings( $user_id, $params = array() ) {
        $params = array_merge( $params, array(
            'conditions' => array( $this->db->prepare( 'user_id = %d', $user_id ) )
        ) );

        return $this->find_valid_listings( $params );
    }

    /**
     * @since 3.3
     */
    public function count_user_listings( $user_id ) {
        $conditions = array( $this->db->prepare( 'user_id = %d', $user_id ) );
        return $this->count_valid_listings( $conditions );
    }

    /**
     * @since 3.3
     */
    public function find_user_enabled_listings( $user_id, $params = array() ) {
        $params = array_merge( $params, array(
            'conditions' => array( $this->db->prepare( 'user_id = %d', $user_id ), 'disabled = 0' )
        ) );

        return $this->find_valid_listings( $params );
    }

    /**
     * @since 3.3
     */
    public function count_user_enabled_listings( $user_id ) {
        $conditions = array( $this->db->prepare( 'user_id = %d', $user_id ), 'disabled = 0' );
        return $this->count_valid_listings( $conditions );
    }

    /**
     * @since 3.3
     */
    public function find_user_disabled_listings( $user_id, $params = array() ) {
        $params = array_merge( $params, array(
            'conditions' => array( $this->db->prepare( 'user_id = %d', $user_id ), 'disabled = 1' )
        ) );

        return $this->find_valid_listings( $params );
    }

    /**
     * @since 3.3
     */
    public function count_user_disabled_listings( $user_id ) {
        $conditions = array( $this->db->prepare( 'user_id = %d', $user_id ), 'disabled = 1' );
        return $this->count_valid_listings( $conditions );
    }

    /**
     * @since 3.3.2
     */
    public function count_enabled_listings_in_category( $category_id ) {
        $category_condition = '( ad_category_id = %1$d OR ad_category_parent_id = %1$d )';

        $conditions = array(
            $this->db->prepare( $category_condition, $category_id ),
            'disabled = 0',
        );

        return $this->count_valid_listings( $conditions );
    }
}
