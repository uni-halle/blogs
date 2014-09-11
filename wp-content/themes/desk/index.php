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
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<div class="dater">
				<a href="<?php the_permalink(); ?>"><?php the_time('l, F j, Y'); ?></a></div>
		</div>
		<div class="entry">
			<?php the_post_thumbnail(array(120,120), array('class' => 'alignright')); ?>
			<?php the_content('Read on &raquo;'); ?></div>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'desk' ), 'after' => '</div>' ) ); ?>

			<div class="clear"></div>
	</div>
	
	<p class="postmetadata"><?php if ($dk_settings['desk_hidetags'] == '') { the_tags('<span class="tags">Tags: ', ', ', '</span><br />'); } ?>
	<span class="cats">Posted in <?php the_category(', ') ?></span> | <?php edit_post_link('Edit', '', ' | '); ?>
	<span class="comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
	</span></p>
	
	<?php endwhile; ?>
	<div class="pnavigation">
		<p class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?>
		</p>
		<p class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?>
		</p>
	</div>
	<?php else : ?>
	<h4 class="center">Not Found</h4>
	<p class="center">Sorry, but you are looking for something that isn&#39;t here.</p>
	<?php endif; ?></div>
<?php get_sidebar(); ?><?php get_footer(); ?>