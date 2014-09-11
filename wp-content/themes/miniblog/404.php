<?php get_header(); ?>

<div class="post-container" id="post-<?php the_ID(); ?>">
	<div class="post-left">
		<div class="post-tab">
			<span class="tab-big">
			404
			</span>
			Error
		</div>
	</div>
	<div class="post-right">
		<div class="post-content-left">
			<img src="<?php bloginfo('template_url');?>/images/post-default.gif" alt="Post Image" height="98" width="98" />
			Posted by the Internet.
		</div>
		<div class="post-content-right">
			<div class="post-header">
				<h1>404 - Page Not Found</h1>
			</div>
			<div class="post-content">
				The page you are looking for cannot be found.
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

 </div>

 <!--End of 404.php-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>