<?php foreach ( $messages as $message ): ?>
    <?php echo awpcp_print_message( $message ); ?>
<?php endforeach; ?>

<p><?php echo $payments->render_account_balance(); ?></p>

<p><?php _e( 'The credit in your account can be used to pay for posting your Ads. You can add more credit when posting a new Ad or using the "Add Credit" button below.', 'AWPCP' ); ?></p>

<form method="get" action="<?php echo esc_attr( $action_url ); ?>">
    <p class="form-submit">
        <input id="submit" class="button" type="submit" value="<?php echo esc_attr( __( 'Add Credit', 'AWPCP' ) ); ?>">
    </p>
</form>
