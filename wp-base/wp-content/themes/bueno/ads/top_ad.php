	<?php if (get_option('woo_ad_top_adsense') <> "") { echo stripslashes(get_option('woo_ad_top_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_top_url'); ?>"><img src="<?php echo get_option('woo_ad_top_image'); ?>" width="468" height="60" alt="advert" /></a>
		
	<?php } ?>	