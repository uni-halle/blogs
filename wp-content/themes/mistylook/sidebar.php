<div id="sidebar">
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>
<?php if(is_home()) mistylook_ShowAbout(); ?>
<?php if(!is_home()) mistylook_ShowRecentPosts();?>
<li class="sidebox">
	<h3><?php _e('Archives','ml'); ?></h3>
	<ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>
</li>

<li class="sidebox">
	<h3><?php _e('Categories','ml'); ?></h3>
	<ul>
		<?php
		if (function_exists('wp_list_categories'))
		{
			wp_list_categories('show_count=1&hierarchical=1&title_li=');
		}
		else
		{
			wp_list_cats('optioncount=1');
		}
		?>
	</ul>
</li>
<?php if (function_exists('wp_tag_cloud')) {	?>
<li class="sidebox">
	<h3><?php _e('Tags','ml'); ?></h3>
	<p>
		<?php wp_tag_cloud(); ?>
	</p>
</li>
<?php } ?>
<li class="sidebox">
	<h3><?php _e('Pages','ml'); ?></h3>
	<ul><?php wp_list_pages('title_li=' ); ?></ul>
</li>
<?php if(is_home()) { mistylook_ShowLinks(); ?>
<li class="sidebox">
	<h3><?php _e('Meta','ml'); ?></h3>
	<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional','ml');?>"><?php _e('Valid','ml');?> <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
		<li><a href="http://gmpg.org/xfn/"><abbr title="<?php _e('XHTML Friends Network','ml');?>">XFN</abbr></a></li>
		<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.','ml');?>">WordPress</a></li>
		<?php wp_meta(); ?>
	</ul>
</li>
<?php }?>
  <?php endif; ?>
</ul>
</div><!-- end id:sidebar -->
</div><!-- end id:content -->
</div><!-- end id:container -->