<?php get_header(); ?>

	<div id="content" class="narrow">

	<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="page-title"><?php _e('Archive For The ', 'jenny'); ?>&ldquo;<?php single_cat_title(); ?>&rdquo; <?php _e('Category', 'jenny'); ?></h1>
	 	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="page-title"><?php _e('Posts Tagged ', 'jenny'); ?>&ldquo;<?php single_tag_title(); ?>&rdquo;</h1>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="page-title"><?php _e('Archive For ', 'jenny'); ?><?php the_time('F jS, Y'); ?></h1>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="page-title"><?php _e('Archive For ', 'jenny'); ?><?php the_time('F, Y'); ?></h1>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="page-title"><?php _e('Archive For ', 'jenny'); ?><?php the_time('Y'); ?></h1>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="page-title"><?php _e('Author Archive', 'jenny'); ?></h1>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="page-title"><?php _e('Blog Archives', 'jenny'); ?></h1>
	 	<?php } ?>
			
		<?php while (have_posts()) : the_post(); ?>
			
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<p><?php _e('By ','jenny'); ?><?php the_author_posts_link(); ?> | <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_time('F j, Y') ?></a></p>
			</div>
			
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( ); ?>" rel="bookmark">
			<?php the_post_thumbnail(array( 150, 150 ), array( 'class' => 'alignleft' )); ?>
			</a>
			<?php the_excerpt(); ?>
			<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( ); ?>" rel="bookmark"><?php _e('Read more', 'jenny'); ?> &raquo;</a></p>
			
			<div class="post-meta">
				<ul>
					<li><?php comments_popup_link( __('Leave your comment', 'jenny'), __( '1 comment', 'jenny'), __('% comments', 'jenny')); ?> &bull; <?php the_category(' &bull; ');?></li>
					<?php the_tags( __('<li>Tagged as: ', 'jenny'), ' &bull; ', '</li>'); ?>
					<li><?php _e('Share on ', 'jenny'); ?><a href="http://twitter.com/home?status=Currently reading: <?php the_title_attribute(); ?> <?php the_permalink(); ?>"><?php _e('Twitter','jenny'); ?></a>, <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title_attribute(); ?>"><?php _e('Facebook', 'jenny'); ?></a>, <a href="http://del.icio.us/post?v=4;url=<?php the_permalink(); ?>"><?php _e('Delicious', 'jenny'); ?></a>, <a href="http://digg.com/submit?url=<?php the_permalink(); ?>"><?php _e('Digg', 'jenny'); ?></a>, <a href="http://www.reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title_attribute(); ?>"><?php _e('Reddit', 'jenny'); ?></a></li>
					<?php edit_post_link(__('Edit this post','jenny'), '<li>', '</li>'); ?>
				</ul>
			</div>
		</div>

		<?php endwhile; ?>
		
		<?php if (show_posts_nav()) : ?>
		
		<div class="post-navigation">
			<ul>
				<li><?php next_posts_link( __('&laquo; Previous Page')) ?></li>
				<li><?php previous_posts_link( __('Next Page &raquo;')) ?></li>
			</ul>
		</div>
		
		<?php endif; ?>
		
	<?php else : ?>

		<h2 class="page-title"><?php _e('Not Found', 'jenny'); ?></h2>
		<p><?php _e('There are not posts belonging to this category or tag. Try searching below:', 'jenny'); ?></p>
		<?php get_search_form(); ?>
	
	<?php endif; ?>

	</div>
	
	<hr />
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
