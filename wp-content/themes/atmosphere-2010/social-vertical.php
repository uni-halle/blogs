<?php $shortname = 'drcms'; ?>
<?php global $themeOptions; ?>
<?php if( !$themeOptions[$shortname. '_hide_vertical_icons'] ) : ?>
	<div id="vsocial-bar">
		<div class="vsocial-item"><a href="#">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/topofpage.png" style="width:24px; height:24px;" alt="Top of Page" />
		</a></div>
		<?php if( $themeOptions[$shortname .'_icon_1'] && $themeOptions[$shortname .'_icon_1_url'] ): ?>
		<div class="vsocial_item"><a href="<?php echo $themeOptions[$shortname .'_icon_1_url']; ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_icon_1']; ?>" style="width:24px; height:24px;" alt="<?php echo $themeOptions[$shortname .'_icon_1_alt']; ?>" /></a></div>
		<?php endif; ?>
		<?php if( $themeOptions[$shortname .'_icon_2'] && $themeOptions[$shortname .'_icon_2_url'] ): ?>
		<div class="vsocial_item"><a href="<?php echo $themeOptions[$shortname .'_icon_2_url']; ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_icon_2']; ?>" style="width:24px; height:24px;" alt="<?php echo $themeOptions[$shortname .'_icon_2_alt']; ?>" /></a></div>
		<?php endif; ?>
		<?php if( $themeOptions[$shortname .'_icon_3'] && $themeOptions[$shortname .'_icon_3_url'] ): ?>
		<div class="vsocial_item"><a href="<?php echo $themeOptions[$shortname .'_icon_3_url']; ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_icon_3']; ?>" style="width:24px; height:24px;" alt="<?php echo $themeOptions[$shortname .'_icon_3_alt']; ?>" /></a></div>
		<?php endif; ?>
		<?php if( $themeOptions[$shortname .'_icon_4'] && $themeOptions[$shortname .'_icon_4_url'] ): ?>
		<div class="vsocial_item"><a href="<?php echo $themeOptions[$shortname .'_icon_4_url']; ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_icon_4']; ?>" style="width:24px; height:24px;" alt="<?php echo $themeOptions[$shortname .'_icon_4_alt']; ?>" /></a></div>
		<?php endif; ?>
		<?php if( $themeOptions[$shortname .'_icon_5'] && $themeOptions[$shortname .'_icon_5_url'] ): ?>
		<div class="vsocial_item"><a href="<?php echo $themeOptions[$shortname .'_icon_5_url']; ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_icon_5']; ?>" style="width:24px; height:24px;" alt="<?php echo $themeOptions[$shortname .'_icon_5_alt']; ?>" /></a></div>
		<?php endif; ?>
		<?php if( $themeOptions[$shortname .'_rss_icon'] ) :?>
		<div class="vsocial_item"><a href="<?php bloginfo('rss2_url'); ?>" target="_blank">
			<img src="<?php echo get_template_directory_uri(); ?>/images/icons/<?php echo $themeOptions[$shortname .'_rss_icon']; ?>" style="width:24px; height:24px;" alt="RSS" /></a></div>
		<?php endif; ?>
	</div>
<?php endif; ?>