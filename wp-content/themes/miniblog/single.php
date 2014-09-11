<?php get_header(); ?>

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
	</div>
	<div class="post-right">
		<div class="post-content-left">
			<?php $thereisimage = get_post_meta($post->ID, $mb_image_key, true);
			if($thereisimage){ ?>
				<?php image_attachment($mb_image_key, 98, 98); ?>
			<?php }else{?>
				<img src="<?php bloginfo('template_url');?>/images/post-default.gif" alt="Post Image" height="98" width="98" />
			<?php }?>
			Posted by <a href="<?php the_author_url(); ?>"><?php the_author();?></a>.
		</div>
		<div class="post-content-right">
			<div class="post-header">
				<h1><?php the_title(); ?></h1>
			</div>
			<div class="post-content">
				<?php the_content(); ?>
				<?php edit_post_link('<p>(Edit)</p> ', ''); ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php comments_template();?>

<?php endwhile; ?>
	
<div class="below-post">
	<span class="go-left">
		<span class="bold">Previous Post</span><br/>
		<?php previous_post_link('&laquo; %link') ?>
	</span>
	<span class="go-right align-right">
		<span class="bold">Next Post</span><br/>
		<?php next_post_link(' %link &raquo;') ?>
	</span>
	<div class="clear"></div>
</div>
	
<?php else : ?>
<div style="padding:0px 0px 0px 90px;">
<h2 class="center">Not Found</h2>
<p class="center">Sorry, but you are looking for something that isn't here.</p>
</div>		
<?php endif; ?>

 </div>

 <!--End of Single.php-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>