<?php echo awpcp_print_message($message); ?>

<p><?php _ex('You are about to pay for the following items.', 'checkout-payment page', 'another-wordpress-classifieds-plugin'); ?></p>

<h3><?php _ex('Payment Terms', 'checkout-payment page', 'another-wordpress-classifieds-plugin'); ?></h3>

<?php echo $this->render_account_balance(); ?>

<?php echo $this->render_transaction_items($transaction); ?>

<?php echo $output; ?>
