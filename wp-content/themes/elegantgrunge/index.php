<?php get_header(); ?>
<div id="content-container">

<div id="content">

	<div id="body">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				
				<div class="date">
					<span class="month"><?php the_time('M') ?></span>
					<span class="day"><?php the_time('j') ?></span>
					<span class="year"><?php the_time('Y') ?></span>
				</div>
				
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'elegant-grunge'), get_the_title()); ?>"><?php the_title(); ?></a></h2>

				<?php if ( get_option("show_author") ) : ?>
				<div class="author"><?php the_author() ?></div>
				<?php endif ;?>
				
				<!-- <div class="info">by <?php the_author() ?></div> -->

				<div class="entry">
					<?php the_content(__('Continue reading', 'elegant-grunge')); ?>
				</div>

				<div class="clear"></div>

				<p class="metadata">
					<?php comments_popup_link(__('no comments', 'elegant-grunge'), __('1 comment', 'elegant-grunge'), __('% comments', 'elegant-grunge')); ?>
					<?php the_tags('&nbsp;&nbsp;|&nbsp;&nbsp;'.__('tags:', 'elegant-grunge').' ', ', ', ''); ?>
					<?php if ( count(($categories=get_the_category())) > 1 || $categories[0]->cat_ID != 1 ) : ?>
					 | <?php _e('posted in', 'elegant-grunge')?> <?php the_category(', ') ?>
					<?php endif; ?>
					<?php edit_post_link(__('Edit', 'elegant-grunge'), '&nbsp;&nbsp;|&nbsp;&nbsp;', ''); ?>
				</p>
				
			</div>
			
			<div class="hr"><hr /></div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="next"><?php next_posts_link(__('&laquo; Older Entries', 'elegant-grunge')) ?></div>
			<div class="previous"><?php previous_posts_link(__('Newer Entries &raquo;', 'elegant-grunge')) ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'elegant-grunge') ?></h2>
		<p class="center"><?php _e('Sorry, but you are looking for something that isn\'t here.', 'elegant-grunge') ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

	</div>

	<?php if ( get_option('page_setup') != 'no-sidebar'  ) get_sidebar(); ?>

</div>
<div class="clear"></div>
</div>

<?php get_footer(); ?>
