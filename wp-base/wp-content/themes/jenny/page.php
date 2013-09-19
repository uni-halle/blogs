<?php get_header(); ?>

	<div id="content" class="narrow">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h1 class="page-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</div>
			
			<?php the_content( __('<p>Read more &raquo;</p>', 'jenny') ); ?>
			<?php wp_link_pages( __('before=<div class="post-page-links">Pages:&after=</div>', 'jenny')) ; ?>
			
		</div>

		<?php endwhile; ?>
		
	<?php else : ?>
		
		<h2 class="page-title"><?php _e('Not Found', 'jenny'); ?></h2>
		<p><?php _e('Sorry, but you are looking for something that is not here.', 'jenny'); ?></p>
		
	<?php endif; ?>
	
	<?php comments_template(); ?>

	</div><!--#content-->
	
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
