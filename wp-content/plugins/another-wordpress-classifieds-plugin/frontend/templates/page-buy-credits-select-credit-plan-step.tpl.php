<?php foreach ( $messages as $message ): ?>
    <?php echo awpcp_print_message( $message ); ?>
<?php endforeach; ?>

<?php foreach ( $errors as $error ): ?>
    <?php echo awpcp_print_error( $error ); ?>
<?php endforeach; ?>

<?php echo $payments->render_account_balance(); ?>

<h3><?php _ex( 'Select a Credit Plan', 'add credit order step', 'AWPCP' ); ?></h3>

<form method="post">

    <?php echo $payments->render_credit_plans_table( $transaction, true ); ?>

    <p class="form-submit">
        <input class="button" type="submit" value="<?php _e( 'Continue', 'AWPCP' ); ?>" id="submit" name="submit">
        <?php if ( ! is_null( $transaction ) ): ?>
        <input type="hidden" value="<?php echo esc_attr( $transaction->id ); ?>" name="transaction_id">
        <?php endif; ?>
        <input type="hidden" value="select-credit-plan" name="step">
    </p>
</form>
