<?php get_header(); ?>

	<div id="content" class="narrow">

<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>
	<h1 class="page-title"><?php printf( __('Author Archives: %s', 'jenny'), "<span class='capitalize'>".get_the_author() ."</span>"  ); ?></h1>

<?php
// If a user has filled out their description, show a bio on their entries.
if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email')); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( __('About %s', 'jenny'), "<span class='capitalize'>".get_the_author() ."</span>" ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info -->
<?php endif; ?>

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();
?>

		<?php if ( have_posts() ) :?>
		<?php while (have_posts()) : the_post(); ?>
			
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<p><?php _e('By ','jenny'); ?><?php the_author_posts_link(); ?> | <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_time('F j, Y') ?></a></p>
			</div>
			
			<?php the_post_thumbnail(array( 150, 150 ), array( 'class' => 'alignleft' )); ?>
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
		<p><?php _e('Sorry, but you are looking for something that is not here.', 'jenny'); ?></p>
		<?php get_search_form(); ?>
	
	<?php endif; ?>

	</div><!--#content-->
	
	<hr />
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>