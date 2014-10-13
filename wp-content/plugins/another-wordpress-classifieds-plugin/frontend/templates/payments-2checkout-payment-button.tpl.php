<form action="https://www2.2checkout.com/2co/buyer/purchase" method="post">
	<?php if ($is_recurring): ?>
	<input type='hidden' name="sid" value="<?php echo esc_attr( $x_login ); ?>" />
	<input type='hidden' name="quantity" value=1 />
	<input type='hidden' name="product_id" value="<?php echo esc_attr( $item->id ); ?>" />
	<input type='hidden' name="x_twocorec" value="1" />
	<?php else: ?>
	<input type="hidden" name="x_login" value="<?php echo esc_attr( $x_login ); ?>" />
	<?php endif ?>

	<input type="hidden" name="id_type" value="1" />
	<input type="hidden" name="fixed" value="Y" />
	<input type="hidden" name="pay_method" value="CC" />
	<input type="hidden" name="x_receipt_link_url" value="<?php echo esc_attr( $return_url ); ?>" />
	<input type="hidden" name="x_invoice_num" value="1" />
	<input type="hidden" name="x_amount" value="<?php echo esc_attr( $amount ); ?>" />
	<input type="hidden" name="c_prod" value="<?php echo esc_attr( $item->id ); ?>" />
	<input type="hidden" name="c_name" value="<?php echo esc_attr( $item->name ); ?>" />
	<input type="hidden" name="c_description" value="<?php echo esc_attr( $item->name ); ?>" />
	<input type="hidden" name="c_tangible" value="N" />
	<input type="hidden" name="x_item_number" value="<?php echo esc_attr( $item->id ); ?>" />
	<input type="hidden" name="x_custom" value="<?php echo esc_attr( $custom ); ?>" />

	<?php if ($is_test_mode_enabled): ?>
	<input type="hidden" name="demo" value="Y" />
	<?php endif ?>

	<input type="image" src="<?php echo esc_attr( $awpcp_imagesurl ); ?>/buybow2checkout.gif" border="0" name="submit" alt="<?php echo esc_attr( __( 'Pay With 2Checkout', 'AWPCP' ) ); ?>" />
</form>
