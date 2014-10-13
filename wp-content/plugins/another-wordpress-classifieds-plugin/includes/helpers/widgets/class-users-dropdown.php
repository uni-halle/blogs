<?php

function awpcp_users_field() {
    if ( get_awpcp_option( 'user-field-widget' ) == 'dropdown' ) {
        return awpcp_users_dropdown();
    } else {
        return awpcp_users_autocomplete();
    }
}

function awpcp_users_dropdown() {
    return new AWPCP_UsersDropdown( awpcp_users_collection(), awpcp_request() );
}

abstract class AWPCP_UserField {

    protected $request;

    public function __construct( $request ) {
        $this->request = $request;
    }

    abstract public function render( $args = array() );

    protected function find_selected_user( $args ) {
        if ( ! is_null( $args['selected'] ) && empty( $args['selected'] ) ) {
            if ( $current_user = $this->request->get_current_user() ) {
                $args['selected'] = $current_user->ID;
            }
        }

        return $args['selected'];
    }

    protected function render_template( $template, $args = array() ) {
        $args = wp_parse_args( $args, array(
            'include-full-user-information' => true,
            'required' => false,
            'selected' => false,
            'label' => false,
            'default' => __( 'Select an User', 'AWPCP' ),
            'id' => null,
            'name' => 'user',
            'class' => array(),
        ) );

        if ( $args['required'] ) {
            $args['class'][] = 'required';
        }

        ob_start();
        include( $template );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
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
