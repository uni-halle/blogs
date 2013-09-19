<div class="advert_468x60">

	<?php if (get_option('woo_ad_content_adsense') <> "") { echo stripslashes(get_option('woo_ad_content_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_content_url'); ?>"><img src="<?php echo get_option('woo_ad_content_image'); ?>" width="468" height="60" alt="advert" /></a>
		
	<?php } ?>	

</div>