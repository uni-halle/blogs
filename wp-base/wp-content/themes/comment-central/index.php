<?php

get_header(); ?>
<div id="top"></div>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

<div class="comment_icon"><?php comments_popup_link('No Comments', '1 Comment', '% comments'); ?></div>			

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>


			<div class="date">
					<?php the_time('Y') ?><br>
					<?php the_time('m.d') ?>
				</div>	

		
				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>

<p class="postmetadata"><?php the_tags('Tags: ', ', ', ''); ?> Category <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?></p>

<div class="line"> </div>

				
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>

