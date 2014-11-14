<?php

/**
 * @since 3.3
 */
function awpcp_listings_collection() {
    return new AWPCP_ListingsCollection( $GLOBALS['wpdb'] );
}

/**
 * @since 3.2.2
 */
class AWPCP_ListingsCollection {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    /**
     * @since 3.3
     */
    public function get( $listing_id ) {
        $listing = AWPCP_Ad::find_by_id( $listing_id );

        if ( is_null( $listing ) ) {
            $message = __( 'No Ad was found with id: %d', 'AWPCP' );
            throw new AWPCP_Exception( sprintf( $message, $listing_id ) );
        }

        return $listing;
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

    /**
     * @since 3.3
     */
    public function count_enabled_listings() {
        return $this->count_valid_listings( array( 'disabled = 0' ) );
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
     * @since next-release
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
