<?php get_header(); ?>

<!-- main column -->

<?php if (have_posts()) : ?>
  
	<?php while (have_posts()) : the_post(); ?>

		<!-- post -->
	   
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="sticky-notice"></div>
			<div class="post-title">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			</div>
			<div class="post-sub">
				<div class="post-date">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/24.png" width="16" height="16" align="left" alt="" title="<?php _e('Date','disciplede') ;?>" border="0" class="icon">
					<?php the_time(__('M j, Y') ) ?>
				</div>
				<div class="post-author">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/39.png" width="16" height="16" align="left" alt="" title="<?php _e('Author','disciplede') ;?>" border="0" class="icon">
					<?php the_author_posts_link() ?>
				</div> 
				<div class="post-cat">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/34.png" width="16" height="16" align="left" alt="" title="<?php _e('Category','disciplede') ;?>" border="0" class="icon">
					<?php the_category(', ') ?>
				</div>
			</div>
			<div class="post-text">
				<?php the_content(__('<p><b>Continue Reading &raquo;</b></p>', 'disciplede') ); ?>
			</div>
			<div class="post-foot">
				<?php if ('open' == $post->comment_status) : ?>
				<div class="post-comments">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/18.png" width="16" height="16" align="left" alt="" border="0" class="icon" /><?php comments_popup_link(__('No Comments','disciplede'), __('1 Comment','disciplede'), __('% Comments','disciplede') ); ?>
				</div>
				<?php endif ?>
				<span class="post-edit"><?php edit_post_link(__('Edit','disciplede'), '(', ')&nbsp; &nbsp;'); ?></span>
				<span class="post-tags"><?php the_tags(__('Tagged ','disciplede'), ', ', ''); ?></span>
			</div>
		</div>

		<div class="sep"></div>

		<!--/post -->

		<div class="post">

			<?php comments_template(); ?>

		</div>
		
	<?php endwhile; ?>
		
	<div class="post">
		<div style="float:left;"><?php next_posts_link(__('&laquo; Older Posts','disciplede') ) ?></div>
		<div style="float:right;"><?php previous_posts_link(__('Newer Posts &raquo;','disciplede') ) ?></div>
	</div>

	<?php else : ?>

	<div class="post">
		<h2><?php _e('Not Found','disciplede') ;?></h2>
		<p><?php _e('Sorry, but you are looking for something that isn&acute;t here','disciplede') ;?></p>
	</div>
		
<?php endif; ?>

<!-- /main column -->

<?php get_footer(); ?>