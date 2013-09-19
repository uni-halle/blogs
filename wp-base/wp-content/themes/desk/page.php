<?php 
/* @package WordPress
 * @subpackage Desk
 */
?>
<?php
get_header();
?>
<div id="post">
	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="posthead">
			<h2><?php the_title(); ?>
			</h2>
			
		</div>
		<div class="entry">
			<?php the_content('Read on &raquo;'); ?></div>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'desk' ), 'after' => '</div>' ) ); ?>
			<p class="postmetadata2"><?php edit_post_link('Edit'); ?></p>
			<div class="clear"></div>
	</div>
	<?php if ($dk_settings['desk_pagecommentdisable'] == '') {
	comments_template(); }?>
	<?php endwhile; ?>
	
	<?php else : ?>
	<h4 class="center">Not Found</h4>
	<p class="center">Sorry, but you are looking for something that isn&#39;t here.</p>
	<?php endif; ?></div>
<?php get_sidebar(); ?><?php get_footer(); ?>