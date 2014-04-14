<?php if ( get_awpcp_option( 'freepay' ) == 1 ): ?>
<h2><?php _ex( 'Select Payment/Category', 'place ad order step', 'AWPCP' ); ?></h2>
<?php else: ?>
<h2><?php _ex( 'Select Category', 'place ad order step', 'AWPCP' ); ?></h2>
<?php endif; ?>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message); ?>
<?php endforeach ?>

<?php foreach ($transaction_errors as $error): ?>
    <?php echo awpcp_print_message($error, array('error')); ?>
<?php endforeach ?>

<?php if ( ! $skip_payment_term_selection && ! awpcp_current_user_is_admin() ): ?>
<?php echo $payments->render_account_balance(); ?>
<?php endif ?>

<form class="awpcp-order-form" method="post">
    <h3><?php _ex('Please select a Category for your Ad', 'place ad order step', 'AWPCP'); ?></h3>

    <p class="awpcp-form-spacer">
        <?php $dropdown = new AWPCP_CategoriesDropdown(); ?>
        <?php echo $dropdown->render( array( 'selected' => awpcp_array_data('category', '', $form), 'name' => 'category' ) ); ?>
        <?php echo awpcp_form_error('category', $form_errors); ?>
    </p>

    <?php if (awpcp_current_user_is_admin()): ?>
    <h3><?php _ex('Please select the owner for this Ad', 'place ad order step', 'AWPCP')?></h3>
    <?php echo $page->users_dropdown(awpcp_array_data('user', '', $form), $form_errors); ?>
    <?php endif ?>

    <?php if ( ! $skip_payment_term_selection ): ?>
    <?php echo $payments->render_payment_terms_form_field( $transaction, $table, $form_errors ); ?>
    <?php endif; ?>

    <p class="form-submit">
        <input class="button" type="submit" value="<?php _e('Continue', 'AWPCP'); ?>" id="submit" name="submit">
        <?php if (!is_null($transaction)): ?>
        <input type="hidden" value="<?php echo esc_attr( $transaction->id ); ?>" name="transaction_id">
        <?php endif; ?>
        <input type="hidden" value="order" name="step">
    </p>
</form>
