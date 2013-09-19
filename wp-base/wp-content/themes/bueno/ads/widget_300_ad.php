<div id="advert_300x250" class="wrap widget">

	<?php if (get_option('woo_ad_300_adsense') <> "") { echo stripslashes(get_option('woo_ad_300_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_300_url'); ?>"><img src="<?php echo get_option('woo_ad_300_image'); ?>" alt="advert" /></a>
		
	<?php } ?>	

</div>