<?php foreach ( $messages as $message ): ?>
    <?php echo awpcp_print_message( $message ); ?>
<?php endforeach; ?>

<p><?php echo $payments->render_account_balance(); ?></p>

<p><?php _e( 'The credit in your account can be used to pay for posting your Ads. You can add credit when posting a new Ad or using the "Add Credit" button below.', 'AWPCP' ); ?></p>

<a class="button" href="<?php echo $url; ?>"><?php _e( 'Add Credit', 'AWPCP' ); ?></a>
