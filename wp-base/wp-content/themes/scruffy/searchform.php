<div id="search">
	
	<form name="searchform" id="searchform" method="get" action="<?php bloginfo('home'); ?>/#content">
		<input class="text" type="text" name="s" id="s" value="<?php echo wp_specialchars($s, 1); ?>"/>
		<input class="submit" type="image" src="<?php bloginfo('template_directory'); ?>/img/submit.jpg" value="search" />
	</form>
	
</div><!-- /search -->