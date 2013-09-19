<?php query_posts($query_string.'&posts_per_page=15') ?>

<?php get_header(); ?>
<div id="content-container">
<div id="content">

	<div id="body">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle"><?php _e('Search Results', 'elegant-grunge') ?></h2>


		<?php while (have_posts()) : the_post(); ?>
			<div class="search_result">
			<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'elegant-grunge'), get_the_title()); ?>"><?php the_title(); ?></a></h4>
			<small><?php the_excerpt(); ?></small>
			<p class="metadata">
				<?php comments_popup_link(__('no comments', 'elegant-grunge'), __('1 comment', 'elegant-grunge'), __('% comments', 'elegant-grunge')); ?>
				<?php the_tags('&nbsp;&nbsp;|&nbsp;&nbsp;'.__('tags:', 'elegant-grunge').' ', ', ', ''); ?>
				<?php if ( count(($categories=get_the_category())) > 1 || $categories[0]->cat_ID != 1 ) : ?>
				 | <?php _e('posted in', 'elegant-grunge') ?> <?php the_category(', ') ?>
				<?php endif; ?>
				<?php edit_post_link(__('Edit', 'elegant-grunge'), '&nbsp;&nbsp;|&nbsp;&nbsp;', ''); ?>
			</p>
			</div>
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'elegant-grunge')) ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'elegant-grunge')) ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e('No posts found. Try a different search?', 'elegant-grunge') ?></h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

	<?php if ( get_option('page_setup') != 'no-sidebar' ) get_sidebar(); ?>


</div>

<div class="clear"></div>
</div>
<?php get_footer(); ?>