<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
                <div class="post-top">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php if ( function_exists('the_title_attribute')) the_title_attribute(); else the_title(); ?>"><?php the_title(); ?></a></h2>
					<div class="post_comments"><p><?php comments_number('0', '1', '%'); ?></p></div>
                </div>
				<div class="info">
					Posted on : <?php the_time('d-m-Y') ?> | By : <b><?php the_author() ?></b> | In : <span><?php the_category(', ') ?></span>
				</div>
				<div class="info">
					<?php the_tags(); ?>
				</div>
				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>

					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				</div>
				<div class="post_digtwit">
					<h2>Share this :</h2>
					<ul>
						<li><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;<?php the_title(); ?>"><img src="<?php bloginfo('template_url')?>/images/ico_stumble.png" alt="Stumble upon" /></a></li>
						<?php $twitter_id = obwp_get_meta(SHORTNAME.'_twitter_id'); ?>
						<li><a href="http://twitter.com/home?status=RT @<?php echo $twitter_id; ?> <?php
the_title(); ?> - <?php the_permalink() ?>"><img src="<?php bloginfo('template_url')?>/images/ico_twitter.png" alt="twitter" /></a></li>
						<?php if(function_exists('digg_digg_generate')) { ?><li><?php digg_digg_generate(); ?></li><?php } ?>
					</ul>
				</div>
			</div>
			
			<?php if(function_exists('related_posts')) : ?>
			<div id="wp_related_posts">
				<?php related_posts(array('before_related'=>'<h2>'.__('Related posts','yarpp').'</h2><ul>', 'after_related'=>'</ul>')); ?>
			</div>
			<?php endif; ?>
			<div id="comment_container">
			<?php comments_template(); ?>
			</div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
