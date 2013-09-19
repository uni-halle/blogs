<!-- XXX: Recurring Payments are not supported, the following form sends information for an Add to Cart operation -->
<form action="<?php echo awpcp_esc_attr($paypal_url); ?>" method="post">
    <input type="hidden" value="2" name="rm">
    <input type="hidden" value="_cart" name="cmd">
    <input type="hidden" value="utf-8" name="charset">
    <input type="hidden" value="<?php echo get_awpcp_option('paypalemail'); ?>" name="business">
	<input type="hidden" value="<?php echo $currency; ?>" name="currency_code" />
	<input type="hidden" value="<?php echo $custom; ?>" name="custom" />

	<input type="hidden" value="<?php echo awpcp_esc_attr($return_url); ?>" name="return" />
	<input type="hidden" value="<?php echo awpcp_esc_attr($notify_url); ?>" name="notify_url" />
	<input type="hidden" value="<?php echo awpcp_esc_attr($cancel_url); ?>" name="cancel_return" />

	<input type="hidden" value="<?php echo awpcp_esc_attr($item->name); ?>" name="item_name_1" />
	<input type="hidden" value="<?php echo $item->id; ?>" name="item_number_1" />
	<input type="hidden" value="<?php echo $amount; ?>" name="amount_1" />
    <input type="hidden" value="1" name="quantity_1">

	<input type="hidden" value="1" name="no_shipping" />
	<input type="hidden" value="1" name="no_note" />
    <input type="hidden" value="1" name="upload">

	<?php if ($is_test_mode_enabled): ?>
	<input type="hidden" name="test_ipn" value="1" />
	<?php endif ?>

    <?php $text = _x('Return to %s', 'paypal-checkout-form', 'AWPCP'); ?>
    <input type="hidden" value="<?php echo sprintf($text, awpcp_get_blog_name()); ?>" name="cbt">

	<?php $alt = __("Make payments with PayPal - it's fast, free and secure!", "AWPCP"); ?>
	<input type="image" src="<?php echo $awpcp_imagesurl; ?>/payments-paypal-checkout-express.gif" border="0" name="submit" alt="<?php echo $alt; ?>" />

	<?php /*if ($is_recurring): ?>
	<input type="hidden" name="cmd" value="_xclick-subscriptions" />
	<input type="hidden" name="a3" value="<?php echo $amount ?>" />
	<input type="hidden" name="p3" value="<?php echo $item->increment ?>" />
	<input type="hidden" name="t3" value="<?php echo $item->period ?>" />
	<input type="hidden" name="src" value="1" />
	<input type="hidden" name="sra" value="1" />
	<input type="hidden" name="no_note" value="1" />
	<?php else: ?>
	<!-- <input type="hidden" name="cmd" value="_xclick" /> -->
	<input type="hidden" name="cmd" value="_cart" />
	<?php endif*/ ?>
</form>
