<h2><?php _ex('Select Payment/Category', 'place ad order step', 'AWPCP'); ?></h2>

<?php awpcp_print_messages(); ?>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message); ?>
<?php endforeach ?>

<?php foreach ($transaction_errors as $error): ?>
    <?php echo awpcp_print_message($error, array('error')); ?>
<?php endforeach ?>

<?php if (!awpcp_current_user_is_admin()): ?>
<?php echo $payments->render_account_balance(); ?>
<?php endif ?>

<form class="awpcp-order-form" method="post">
    <h3><?php _ex('Please select a Category for your Ad', 'place ad order step', 'AWPCP'); ?></h3>

    <p class="awpcp-form-spacer">
        <label for="place-ad-category"><?php _ex('Ad Category', 'place ad order step', 'AWPCP'); ?></label>
        <select class="required" id="place-ad-category" name="category">
            <option value=""><?php _ex('Select a Category', 'place ad order step', 'AWPCP'); ?></option>
            <?php echo get_categorynameidall(awpcp_array_data('category', '', $form)); ?>
        </select>
        <?php echo awpcp_form_error('category', $form_errors); ?>
    </p>

    <?php if (awpcp_current_user_is_admin()): ?>
    <h3><?php _ex('Please select the owner for this Ad', 'place ad order step', 'AWPCP')?></h3>
    <?php echo $page->users_dropdown(awpcp_array_data('user', '', $form), $form_errors); ?>
    <?php endif ?>

    <?php echo $payments->render_payment_terms_form_field( $transaction, $table, $form_errors ); ?>

    <p class="form-submit">
        <input class="button" type="submit" value="<?php _e('Continue', 'AWPCP'); ?>" id="submit" name="submit">
        <?php if (!is_null($transaction)): ?>
        <input type="hidden" value="<?php echo esc_attr($transaction->id) ?>" name="awpcp-txn">
        <?php endif; ?>
        <input type="hidden" value="order" name="step">
    </p>
</form>
