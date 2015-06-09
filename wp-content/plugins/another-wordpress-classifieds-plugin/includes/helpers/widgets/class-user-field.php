<?php

function awpcp_users_field() {
    if ( get_awpcp_option( 'user-field-widget' ) == 'dropdown' ) {
        return awpcp_users_dropdown();
    } else {
        return awpcp_users_autocomplete();
    }
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
