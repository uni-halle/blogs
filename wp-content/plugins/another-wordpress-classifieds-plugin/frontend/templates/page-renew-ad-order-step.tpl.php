<h2><?php _ex('Select Payment Term', 'renew ad order step', 'AWPCP') ?></h2>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php foreach ($transaction_errors as $error): ?>
    <?php echo awpcp_print_message($error, array('error')) ?>
<?php endforeach ?>

<?php if (!awpcp_current_user_is_admin()): ?>
<?php echo $payments->render_account_balance() ?>
<?php endif ?>

<form class="awpcp-order-form" method="post">
    <?php echo $payments->render_payment_terms_form_field( $transaction, $table, $form_errors ); ?>

    <p class="form-submit">
        <input class="button" type="submit" value="<?php echo esc_attr( __( 'Continue', 'AWPCP' ) ); ?>" id="submit" name="submit">
        <?php if (!is_null($transaction)): ?>
        <input type="hidden" value="<?php echo esc_attr( $transaction->id ); ?>" name="transaction_id">
        <?php endif; ?>
        <input type="hidden" value="order" name="step">
    </p>
</form>
