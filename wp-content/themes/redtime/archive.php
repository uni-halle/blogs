<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : ?>

		<?php $i=0; while (have_posts()) : the_post(); $i++; ?>

			<div class="post" id="post-<?php the_ID(); ?>">
                <div class="post-top">
                    <div class="post-title">
                        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php if ( function_exists('the_title_attribute')) the_title_attribute(); else the_title(); ?>"><?php the_title(); ?></a></h2>
						<h3>
							Posted by <span><?php the_author() ?></span>  |  Posted in <span><?php the_category(', ') ?></span>  |  Posted on <?php the_time('d-m-Y') ?>
						</h3>
						<h3>
							<span class="post_cats"><?php the_tags(); ?></span>
						</h3>
                    </div>
					<h4><?php comments_number('0', '1', '%'); ?></h4>
                </div>

				<div class="entry">
					<?php the_content('',FALSE,''); ?>
				</div>

                <div class="postmetadata">
                	<a href="<?php the_permalink() ?>" >Continue reading...</a>
                </div>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<?php if(!function_exists('wp_pagenavi')) : ?>
            <div class="alignleft"><?php next_posts_link('Previous') ?></div>
            <div class="alignright"><?php previous_posts_link('Next') ?></div>
            <?php else : wp_pagenavi(); endif; ?>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

<?php get_footer(); ?>
