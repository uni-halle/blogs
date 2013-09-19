<?php
    if ($attempts > 0) {
        foreach ($transaction_errors as $error) {
            echo awpcp_print_message($error, array('error'));
        }
    }
?>

<p><?php _ex('You are about to pay for the following items. Please review the order and choose a payment method.', 'checkout step', 'AWPCP') ?></p>

<form class="awpcp-checkout-form" method="post">

    <h3><?php _ex('Payment Terms', 'checkout step', 'AWPCP'); ?></h3>

    <?php echo $this->render_account_balance(); ?>

    <?php echo $this->render_transaction_items($transaction); ?>

    <h3><?php _ex('Payment Method', 'checkout step', 'AWPCP'); ?></h3>

    <?php echo $this->render_payment_methods($transaction); ?>

    <p class="form-submit">
        <input class="button" type="submit" value="<?php _e('Continue', 'AWPCP') ?>" id="submit" name="submit">
        <input type="hidden" value="<?php echo esc_attr($transaction->id) ?>" name="awpcp-txn">
        <input type="hidden" value="<?php echo esc_attr($attempts + 1) ?>" name="attempts">
        <?php foreach ($hidden as $name => $value): ?>
        <input type="hidden" value="<?php echo esc_attr($value) ?>" name="<?php echo esc_attr($name) ?>">
        <?php endforeach ?>
    </p>

</form>
