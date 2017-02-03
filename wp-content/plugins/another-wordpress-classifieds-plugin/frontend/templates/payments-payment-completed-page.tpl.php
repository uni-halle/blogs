<form id="awpcp-payment-completed-form" method="post" action=<?php echo esc_attr($action) ?>>
    <h3><?php _ex('Transaction Details', 'payment completed page', 'another-wordpress-classifieds-plugin') ?></h3>

    <?php echo $this->render_transaction_items($transaction) ?>
    <h3><?php echo $title ?></h3>

    <p><?php echo $text ?></p>

    <?php echo $this->render_transaction_errors($transaction) ?>

    <input type="hidden" value="<?php echo esc_attr( $transaction->id ); ?>" name="transaction_id">
    <?php foreach ($hidden as $name => $value): ?>
    <input type="hidden" value="<?php echo esc_attr($value) ?>" name="<?php echo esc_attr($name) ?>">
    <?php endforeach ?>

    <?php if ($success): ?>
    <p class="awpcp-form-submit">
        <input class="button" type="submit" value="<?php _e('Continue', 'another-wordpress-classifieds-plugin') ?>" id="submit" name="submit">
    </p>
    <?php endif ?>
</form>
