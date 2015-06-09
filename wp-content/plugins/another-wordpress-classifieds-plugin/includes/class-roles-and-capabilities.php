<?php

function awpcp_roles_and_capabilities() {
    return new AWPCP_RolesAndCapabilities( awpcp()->settings, awpcp_request() );
}

class AWPCP_RolesAndCapabilities {

    private $settings;
    private $request;

    public function __construct( $settings, $request ) {
        $this->settings = $settings;
        $this->request = $request;
    }

    public function setup_roles_capabilities() {
        $administrator_roles = $this->get_administrator_roles_names();
        array_walk( $administrator_roles, array( $this, 'add_administrator_capabilities_to_role' ) );

        $this->create_moderator_role();
    }

    public function get_administrator_roles_names() {
        $selected_roles = $this->settings->get_option( 'awpcpadminaccesslevel' );
        return $this->get_administrator_roles_names_from_string( $selected_roles );
    }

    public function get_administrator_roles_names_from_string( $string ) {
        $configured_roles = explode( ',', $string );

        if ( in_array( 'editor', $configured_roles ) ) {
            $roles_names = array( 'administrator', 'editor' );
        } else {
            $roles_names = array( 'administrator' );
        }

        return $roles_names;
    }

    public function get_administrator_capabilities() {
        return array_merge( array( 'manage_classifieds' ), $this->get_moderator_capabilities() );
    }

    public function get_moderator_capabilities() {
        return array( 'manage_classifieds_listings'/*, 'edit_classifieds_listings'*/ );
    }

    public function add_administrator_capabilities_to_role( $role_name ) {
        $role = get_role( $role_name );
        return $this->add_capabilities_to_role( $role, $this->get_administrator_capabilities() );
    }

    private function add_capabilities_to_role( $role, $capabilities ) {
        return array_map( array( $role, 'add_cap' ), $capabilities );
    }

    public function remove_administrator_capabilities_from_role( $role_name ) {
        $role = get_role( $role_name );
        return array_map( array( $role, 'remove_cap' ), $this->get_administrator_capabilities() );
    }

    private function create_moderator_role() {
        $role = get_role( 'awpcp-moderator' );

        $capabilities = array_merge( array( 'read' ), $this->get_moderator_capabilities() );
        $capabilities = array_combine( $capabilities, array_pad( array(), count( $capabilities ), true ) );

        if ( is_null( $role ) ) {
            $role = add_role( 'awpcp-moderator', __( 'Classifieds Moderator', 'AWPCP' ), $capabilities );
        } else {
            $this->add_capabilities_to_role( $role, array_keys( $capabilities ) );
        }
    }

    public function current_user_is_administrator() {
        return $this->current_user_can( $this->get_administrator_capabilities() );
    }

    private function current_user_can( $capabilities ) {
        // If the current user is being setup before the "init" action has fired,
        // strange (and difficult to debug) role/capability issues will occur.
        if ( ! did_action( 'set_current_user' ) ) {
            _doing_it_wrong( __FUNCTION__, "Trying to call current_user_is_*() before the current user has been set.", '3.3.1' );
        }

        return $this->user_can( $this->request->get_current_user(), $capabilities );
    }

    private function user_can( $user, $capabilities ) {
        if ( ! is_object( $user ) || empty( $capabilities ) ) {
            return false;
        }

        foreach ( $capabilities as $capability ) {
            if ( ! user_can( $user, $capability ) ) {
                return false;
            }
        }

        return true;
    }

    public function current_user_is_moderator() {
        return $this->current_user_can( $this->get_moderator_capabilities() );
    }

    public function user_is_administrator( $user_id ) {
        $this->user_can( get_userdata( $user_id ), $this->get_administrator_capabilities() );
    }
}
