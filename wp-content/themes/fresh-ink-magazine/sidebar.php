<div id="side-bar">


<div id="side-barmid">
	<div id="searchform">
					<?php get_search_form(); ?>
			   </div><br /><br /><br />

		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) { ?>

		<h2><?php _e("recent articles", "magazinetheme"); ?></h2>
	
	<ul><?php wp_get_archives('title_li=&type=postbypost&limit=10'); ?>
  </ul>
   

		<h2><?php _e("Past Archives", "magazinetheme"); ?></h2>
	
	<ul><?php wp_get_archives('type=monthly','99','custom','<li>','</li>'); ?></ul>
  

			<h2><?php _e("meta", "magazinetheme"); ?></h2>
			
				<?php wp_loginout(); ?><br /><br />
				<?php wp_meta(); ?>
		

		<?php } ?>
	
</div>

</div>