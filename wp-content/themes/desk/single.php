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
			</h2><p class="postauthor">By <?php the_author_posts_link(); ?></p>
			<div class="dater">
				<?php the_time('l, F j, Y'); ?></div>
		</div>
		<div class="entry">
			<?php 
			if ($dk_settings['desk_hide_postimage'] == ''){
				the_post_thumbnail('medium', array('class' => 'alignright'));
			} ?>
			<?php the_content('Read on &raquo;'); ?></div>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'desk' ), 'after' => '</div>' ) ); ?>

			<div class="clear"></div>
	</div>
	<p class="postmetadata"><?php if ($dk_settings['desk_hidetags'] == '') { the_tags('<span class="tags">Tags: ', ', ', '</span><br />'); } ?>
	<span class="cats">Posted in <?php the_category(', ') ?></span> | <?php edit_post_link('Edit', '', ' | '); ?>
	<span class="comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
	</span></p>
	
			<?php comments_template(); ?>
	<?php endwhile; ?><?php else : ?>
	<h4 class="center">Not Found</h4>
	<p class="center">Sorry, but you are looking for something that isn&#39;t here.</p>
	<?php endif; ?>

	</div>
<?php get_sidebar(); ?><?php get_footer(); ?>