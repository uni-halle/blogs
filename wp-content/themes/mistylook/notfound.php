<div class="post" id="post-<?php the_ID(); ?>">
	<div class="posttitle">
		<h2><?php _e('Ooops...Where did you get such a link ?','ml');?></h2>
		<p class="post-info"><?php _e('Server cannot locate what you are looking for !','ml');?></p>
	</div>
	<div class="entry">
		<p><?php _e('The Server tried all of its options before returning this page to you.','ml');?></p>
		<p><img src="<?php bloginfo('stylesheet_directory') ;?>/img/404.gif" alt="404" class="left" /><?php _e('You are looking for something that is not here now.','ml');?><br/>
			<?php _e('You can always try doing a <strong>search</strong> or browsing through the <strong>Archives</strong>.','ml');?><br/><?php _e('Don\'t loose your hope just yet.','ml');?><br style="clear:both" /></p>
	</div>
	<p class="postmetadata"><?php _e('Posted as Not Found','ml');?></p>
</div>