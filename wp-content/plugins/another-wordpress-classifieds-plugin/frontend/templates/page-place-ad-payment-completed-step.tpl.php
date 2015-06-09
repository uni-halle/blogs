<h2><?php echo $payments->render_payment_completed_page_title($transaction) ?></h2>

<?php
    if ( isset( $transaction ) && get_awpcp_option( 'show-create-listing-form-steps' ) ) {
        echo awpcp_render_listing_form_steps( 'payment', $transaction );
    }
?>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php echo $payments->render_payment_completed_page($transaction, $url, $hidden) ?>
