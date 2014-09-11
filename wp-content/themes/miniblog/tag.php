<?php get_header(); ?>

<div class="post-announcement2">
	<h3><?php single_cat_title('Results > Posts Tagged With > ','TRUE'); ?></h3>
</div>

<!--For Theme Options-->
	<?php
		global $options;
		foreach ($options as $value) {
		if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
		}
	?>
<!--End of For Theme Options-->

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="post-container" id="post-<?php the_ID(); ?>">
	<div class="post-left">
		<div class="post-tab">
			<span class="tab-big">
			<?php the_time('M'); ?>
			</span>
			<?php the_time('j'); ?>
		</div>
		<?php if(is_sticky()) {?>
			<div class="post-tab">
				<span class="tab-big">
				Sticky
				</span>
				Post
			</div>
		<?php } ?>
	</div>
	<div class="post-right">
		<div class="post-content-left">
			<?php $thereisimage = get_post_meta($post->ID, $mb_image_key, true);
			if($thereisimage){ ?>
				<a href="<?php the_permalink() ?>"><?php image_attachment($mb_image_key, 98, 98); ?></a>
			<?php }else{?>
				<a href="<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/images/post-default.gif" alt="Post Image" height="98" width="98" /></a>
		<?php }?>
			Posted by <a href="<?php the_author_url(); ?>"><?php the_author();?></a>.
		</div>
		<div class="post-content-right">
			<div class="post-header">
				<div class="post-header-title">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
				</div>
				<div class="post-comments">
					<?php comments_popup_link('0', '1', '%'); ?>
				</div>
			</div>
			<div class="post-content">
				<?php the_excerpt(); ?>
				<p class="post-more"><?php edit_post_link('(Edit) ', ''); ?><a href="<?php the_permalink() ?>">Read on &raquo;</a></p>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<!--Comments Template Here-->

<?php endwhile; ?>
	
<div class="below-post go-center">
	<?php
		include('wp-pagenavi.php');
		if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
	?>
</div>
	
<?php else : ?>
<div style="padding:0px 0px 0px 90px;">
<h2 class="center">Not Found</h2>
<p class="center">Sorry, but you are looking for something that isn't here.</p>
</div>		
<?php endif; ?>

 </div>
 <!--End of Tag.php-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>