<form action="<?php echo $google_checkout_url ?>" method="post">
	<input type="hidden" name="item_name_1" value="<?php echo $item->name ?>" />
	<input type="hidden" name="item_description_1" value="<?php echo $item->name ?>" />
	<input type="hidden" name="item_price_1" value="<?php echo $amount ?>" />
	<input type="hidden" name="item_currency_1" value="<?php echo $currency ?>" />
	<input type="hidden" name="item_quantity_1" value="1" />
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.display-disposition" value="OPTIMISTIC"/>
	<?php $text = __("Your listing has not been fully submitted yet. To complete the process you need to click the link below.", "AWPCP") ?>
	<?php $text.= sprintf('<br/><a href="%s">%s</a>', $return_url, $return_url) ?>
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.description" value="<?php echo esc_attr($text) ?>" />
	<!--<input type="hidden" name="shopping-cart.items.item-1.digital-content.key" value="<?php echo $key ?>" />-->
	<input type="hidden" name="shopping-cart.items.item-1.digital-content.url" value="<?php echo $return_url ?>" />
	<input type="hidden" name="_charset_" value="utf-8" />
	<input type="image" src="<?php echo $button_url ?>" alt="<?php _e("Pay With Google Checkout", "AWPCP"); ?>" /></form>
</form>