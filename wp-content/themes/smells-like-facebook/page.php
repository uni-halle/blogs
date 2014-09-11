<?php get_header(); ?>

<div id="post-container">
	<div id="posts">

<?php while(have_posts()) : the_post(); ?>
	<div class="post">
		<div class="post-gravatar">
			<a href="<?php echo get_author_posts_url($authordata->ID); ?>"><?php echo get_avatar(get_the_author_email(), '50') ?></a>
		</div>
		<div class="post-text">
			<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php the_content('(more..)'); ?>
			<div class="post-meta">
				<?php the_time('j F Y'); echo ' at '; the_time('H:i'); ?> - 
				<a class="respondlink notajax" href="<?php the_permalink(); ?>#respond">Comments</a> - 
				<?php edit_post_link('Edit', ' - ', '') ?>
			</div>
			<?php comments_template(); ?>
		</div>
	</div>
<?php endwhile; ?>

	<input type="hidden" name="title" value="<?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" />

	</div>
</div>
	
<?php get_footer(); ?>