<?php get_header() ?>

	<div id="container">
		<div id="content">
			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span>', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('<span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
			</div>
		
<?php while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="featured <?php sandbox_post_class() ?>">
				<div class="entry-date bigdate"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time('d M'); ?></abbr></div>
				<h2 class="entry-title post-content-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><span><?php the_title() ?></span></a></h2>
				<div class="entry-content post-content">
					<h4><?php the_title() ?></h4>
					<p><?php the_excerpt(); ?></p>

				<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'sandbox' ) . '&after=</div>') ?>
				</div>
				<span class="attach-post-image" style="height:300px;display:block;background:url('<?php the_post_image_url('large'); ?>') center center repeat">&nbsp;</span>
			</div><!-- .post -->

<?php comments_template() ?>

<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
 			</div>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>