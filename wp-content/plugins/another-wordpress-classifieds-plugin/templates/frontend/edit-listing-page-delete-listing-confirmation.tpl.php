<?php
    foreach ( $messages as $message ) {
        echo awpcp_print_message( $message );
    }
?>

<p><?php echo sprintf( '<a href="%s">%s</a>.', esc_url( $main_page_url ), esc_html( __( 'Return to main classifieds', 'AWPCP' ) ) ); ?></p>
