<?php 
/* @package WordPress
 * @subpackage Desk
 */
?>
<?php
get_header();
?>
<div id="post">
	<?php if ( have_posts() ) : ?>
				<h2 class="page-title"><?php printf( __( 'Search Results for "%s"', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				
				?><?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="posthead">
		

			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<div class="dater">
				<?php the_time('l, F j, Y'); ?></div>
		
			</div>
			
			
			<div class="entry">
				<?php the_content('Read on &raquo;'); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'desk' ), 'after' => '</div>' ) ); ?>
			</div>
	<p class="postmetadata"><?php if ($dk_settings['desk_hidetags'] == '') { the_tags('<span class="tags">Tags: ', ', ', '</span><br />'); } ?>
	<span class="cats">Posted in <?php the_category(', ') ?></span> | <?php edit_post_link('Edit', '', ' | '); ?>
	<span class="comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
	</span></p>
		
		</div>
<?php endwhile; ?>

<?php endif; ?>

<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
						
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?></div>
<?php get_sidebar(); ?><?php get_footer(); ?>