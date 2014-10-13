<form action="<?php echo esc_attr( $google_checkout_url ); ?>" method="post">
	<input type="hidden" name="item_name_1" value="<?php echo esc_attr( $item->name ); ?>" />
	<input type="hidden" name="item_description_1" value="<?php echo esc_attr( $item->name ); ?>" />
	<input type="hidden" name="item_price_1" value="<?php echo esc_attr( $amount ); ?>" />
	<input type="hidden" name="item_currency_1" value="<?php echo esc_attr( $currency ); ?>" />
	<input type="hidden" name="item_quantity_1" value="1" />
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.display-disposition" value="OPTIMISTIC"/>
	<?php $text = __("Your listing has not been fully submitted yet. To complete the process you need to click the link below.", "AWPCP") ?>
	<?php $text.= sprintf( '<br/><a href="%s">%s</a>', esc_attr( $return_url ), esc_html( $return_url) ); ?>
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.description" value="<?php echo esc_attr($text) ?>" />
	<!--<input type="hidden" name="shopping-cart.items.item-1.digital-content.key" value="<?php echo esc_attr( $key ); ?>" />-->
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.url" value="<?php echo esc_attr( $return_url ); ?>" />
	<input type="hidden" name="_charset_" value="utf-8" />
	<input type="image" src="<?php echo esc_attr( $button_url ); ?>" alt="<?php echo esc_attr( __( "Pay With Google Checkout", "AWPCP" ) ); ?>" /></form>
</form>
