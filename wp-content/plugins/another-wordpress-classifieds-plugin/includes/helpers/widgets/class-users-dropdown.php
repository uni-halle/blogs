<?php

function awpcp_users_dropdown() {
    return new AWPCP_UsersDropdown( awpcp_users_collection(), awpcp_request() );
}

class AWPCP_UsersDropdown extends AWPCP_UserField {

    private $users;

    public function __construct( $users, $request ) {
        parent::__construct( $request );
        $this->users = $users;
    }

    public function render( $args = array() ) {
        $args = wp_parse_args( $args, array(
            'include-full-user-information' => true,
            'selected' => null,
        ) );

        $args['selected'] = $this->find_selected_user( $args );

        if ( $args['include-full-user-information'] ) {
            $users = $this->users->get_users_with_full_information();
        } else {
            $users = $this->users->get_users_with_basic_information();
        }

        $template = AWPCP_DIR . '/frontend/templates/html-widget-users-dropdown.tpl.php';
        $args = array_merge( $args, array( 'users' => $users ) );

        return $this->render_template( $template, $args );
    }
}
