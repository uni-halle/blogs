<?php

function awpcp_users_collection() {
    global $wpdb;
    return new AWPCP_UsersCollection( $wpdb, awpcp_payments_api() );
}

class AWPCP_UsersCollection {

    private $db;
    private $payments;

    public function __construct( $db, $payments ) {
        $this->db = $db;
        $this->payments = $payments;
    }

    /**
     * @since next-release
     */
    public function get( $user_id ) {
        $user = $this->find_by_id( $user_id );

        if ( is_null( $user ) ) {
            throw new AWPCP_Exception( sprintf( 'No User was found with ID: %d.', $user_id ) );
        }

        return $user;
    }

    public function find_by_id( $user_id ) {
        $query = $this->build_full_user_information_query( array( 'user_id' => $user_id ) );
        $user_information = $this->db->get_results( $query );

        $users = $this->consolidate_users_information( $user_information );

        return count( $users ) > 0 ? array_shift( $users ) : null;
    }

    private function build_full_user_information_query( $args = array() ) {
        $args = wp_parse_args( $args, array( 'user_id' => null, 'search_string' => null, 'limit' => null ) );

        $query = 'SELECT <wp-users>.ID, <wp-users>.user_login, <wp-users>.user_email, <wp-users>.user_url, <wp-users>.display_name, <wp-usermeta>.meta_key, <wp-usermeta>.meta_value ';
        $query.= 'FROM <wp-users> JOIN <wp-usermeta> ON (<wp-usermeta>.user_id = <wp-users>.ID) ';

        $conditions[] = "<wp-usermeta>.meta_key IN ('first_name', 'last_name', 'nickname', 'awpcp-profile')";

        if ( ! empty( $args['user_id'] ) ) {
            $conditions[] = $this->db->prepare( '<wp-users>.ID = %d', $args['user_id'] );
        } else if ( ! empty( $args['search_string'] ) ) {
            $conditions[] = "(<wp-users>.user_login LIKE '%<term>%' OR <wp-users>.display_name LIkE '%<term>%' OR <wp-usermeta>.meta_value LIKE '%<term>%')";
        }

        $query.= 'WHERE ' . implode( ' AND ', $conditions ) . ' ';
        $query.= 'ORDER BY <wp-users>.display_name ASC, <wp-users>.ID ASC ';

        if ( ! empty( $args['limit'] ) ) {
            $query .= $this->db->prepare( 'LIMIT %d', $args['limit'] );
        }

        $query = str_replace( '<wp-users>', $this->db->users, $query );
        $query = str_replace( '<wp-usermeta>', $this->db->usermeta, $query );
        $query = str_replace( '<term>', esc_sql( $args['search_string'] ), $query );

        return $query;
    }

    private function consolidate_users_information( $users_information ) {
        $users = array();

        foreach ( $users_information as $information ) {
            if ( ! isset( $users[ $information->ID ] ) ) {
                $users[ $information->ID ] = new stdClass();
                $users[ $information->ID ]->ID = $information->ID;
                $users[ $information->ID ]->user_login = $information->user_login;
                $users[ $information->ID ]->user_email = $information->user_email;
                $users[ $information->ID ]->user_url = $information->user_url;
                $users[ $information->ID ]->display_name = $information->display_name;

                $users[ $information->ID ]->value = $users[ $information->ID ]->display_name;

                $payment_terms = $this->payments->get_user_payment_terms( $information->ID );
                $payment_terms_ids = array();

                foreach ( $payment_terms as $type => $terms ) {
                    foreach ( $terms as $term ) {
                        $payment_terms_ids[] = "{$term->type}-{$term->id}";
                    }
                }

                $users[ $information->ID ]->payment_terms = $payment_terms_ids;
            }

            if ( $information->meta_key == 'awpcp-profile' ) {
                $profile_info = maybe_unserialize( $information->meta_value );
                $users[ $information->ID ]->address = awpcp_array_data( 'address', '', $profile_info );
                $users[ $information->ID ]->phone = awpcp_array_data( 'phone', '', $profile_info );
                $users[ $information->ID ]->city = awpcp_array_data( 'city', '', $profile_info );
                $users[ $information->ID ]->state = awpcp_array_data( 'state', '', $profile_info );
            } else {
                $users[ $information->ID ]->{$information->meta_key} = $information->meta_value;
            }
        }

        return $users;
    }

    public function find_by_search_string( $search_string ) {
        $query = $this->build_full_user_information_query( array( 'search_string' => $search_string, 'limit' => 100 ) );
        $users_information = $this->db->get_results( $query );

        return $this->consolidate_users_information( $users_information );
    }

    public function get_users_with_full_information() {
        $query = $this->build_full_user_information_query();
        $users_information = $this->db->get_results( $query );

        return $this->consolidate_users_information( $users_information );
    }

    public function get_users_with_basic_information() {
        $query = "SELECT <wp-users>.ID, <wp-users>.user_login, <wp-users>.user_email, <wp-users>.user_url, <wp-users>.display_name ";
        $query.= 'FROM <wp-users> ';
        $query.= 'ORDER BY <wp-users>.display_name ASC, <wp-users>.ID ASC ';

        $query = str_replace( '<wp-users>', $this->db->users, $query );

        return $this->db->get_results( $query );
    }
}
