<h2><?php echo esc_html( _x( 'Login/Registration', 'place ad login step', 'AWPCP' ) ); ?></h2>

<?php
    if ( get_awpcp_option( 'show-create-listing-form-steps' ) ) {
        echo awpcp_render_listing_form_steps( 'login' );
    }
?>

<?php echo awpcp_login_form( $message, $page_url ); ?>
