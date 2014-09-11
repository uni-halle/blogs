<?php get_header(); ?>

	<div id="content">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); ?>
<?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
		<h2 class="posttitle"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment">&laquo;<?php echo get_the_title($post->post_parent); ?></a></h2>
		
		<h4><?php the_title(); ?></h4>
		
			<div class="entry clearfloat">
		<p class="<?php echo $classname; ?>"><?php echo $attachment_link; ?></p>
	
		</div>
		</div>

	<?php comments_template(); ?>
	<?php endwhile; else: ?>

		<p>Sorry, no attachments matched your criteria.</p>

<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>