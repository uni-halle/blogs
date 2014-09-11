<?php get_header(); ?>

<!-- main column -->

<?php if (have_posts()) : ?>
  
	<?php while (have_posts()) : the_post(); ?>

		<!-- post -->
	   
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="post-title" style="margin-bottom:10px;">
				<h1><?php the_title(); ?></h1>
			</div>
			<div class="post-text">
				<?php the_content(__('<p><b>Continue Reading &raquo;</b></p>') ); ?>
			</div>
			<div class="post-foot">
				<span class="post-edit"><?php edit_post_link(__('Edit','disciplede'), '(', ')&nbsp; &nbsp;'); ?></span>
			</div>
		</div>
		
		<div style="text-align:center;"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/sep.gif" alt="" border="0" /></div>

		<!--/post -->

		<div class="post" id="comments">
			<?php comments_template(); ?>
		</div>
		
	<?php endwhile; ?>

	<?php else : ?>

	<div class="post">
		<h2><?php _e('Not Found','disciplede') ;?></h2>
		<p><?php _e('Sorry, but you are looking for something that isn&acute;t here.','disciplede') ;?></p>
	</div>
		
<?php endif; ?>

<!-- /main column -->

<?php get_footer(); ?>