<?php

class AWPCP_WP_Members_Login_Form_Implementation {

    public function render( $redirect, $message = null ) {
        $form = $message ? awpcp_print_message( $message ) : '';
        $form.= '[wpmem_form login redirect_to="' . $redirect . '" /]';

        return do_shortcode( $form );
    }
}
