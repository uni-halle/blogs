<?php get_header(); ?>

<div id="content">

	<?php if(have_posts()): ?>
	<?php while(have_posts()) : the_post();?>
	
	<div class="post first" id="post-<?php the_ID(); ?>">
		
		<?php 
			$post_parent = $post->post_parent;
			$post_parent_id = get_post($post_parent);
			$post_parent_title = $post_parent_id->post_title;
			$post_parent_link = $post_parent_id->guid;
		?>
		
		<h2 class="title">
			<a href="<?php echo $post_parent_link; ?>#content" title="Back to <?php echo $post_parent_title; ?>"><?php echo $post_parent_title; ?></a>
			 &raquo; <?php the_title();?>
		</h2>
		
		<p class="postmetadata">
			<span class="date"><?php the_time('d F Y') ;?></span>
			<span class="author"><?php _e('By'); ?> <?php  the_author_posts_link(); ?></span>
			<span class="comments"><?php comments_number('0', '1', '%'); ?></span>
			<span class="category"><?php _e('In'); ?> <?php the_category(', ') ?></span>
			<?php edit_post_link('Edit', '<span class="edit">', '</span>'); ?>
		</p>
		
		<div class="entry">
			
			<p class="alignc">
				<a href="<?php echo $post->guid; ?>" title="View <?php the_title();?> in maximum size"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
			</p>
			
			<?php if ( !empty($post->post_content) ) the_content(); ?>
			
			<div id="image_nav">
				<p class="prev floatl"><?php previous_image_link() ?></p>
				<p class="next floatr"><?php next_image_link() ?></p>
			</div>
			
		</div><!-- /entry -->
		
		<p class="trackback"><a href="<?php trackback_url(); ?>" title="Trackback URL for <?php the_title();?>">Trackback URL</a></p>
		
	</div><!-- /post -->
		
	<div id="comments">
		<?php comments_template(); ?>
	</div><!--/comments-->	
		
	<?php endwhile; ?>		
	<?php else: ?>
	
	<div class="post">
		<h2><?php _e('Not Found'); ?></h2>
	</div><!-- /post -->
	
	<?php endif; ?>
	
</div><!-- /full -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>