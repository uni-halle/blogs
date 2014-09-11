<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

<?php if ( is_day() ) : ?>
<div class="comment-count">
			<h2 class="page-title"><?php printf(__('Daily Archives: <span>%s</span>', 'sandbox'), get_the_time(get_option('date_format'))) ?></h2>
			</div>
<?php elseif ( is_month() ) : ?>
<div class="comment-count">
			<h2 class="page-title"><?php printf(__('Monthly Archives: <span>%s</span>', 'sandbox'), get_the_time('F Y')) ?></h2>
			</div>
<?php elseif ( is_year() ) : ?>
<div class="comment-count">
			<h2 class="page-title"><?php printf(__('Yearly Archives: <span>%s</span>', 'sandbox'), get_the_time('Y')) ?></h2>
			</div>
<?php elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) : ?>
			<div class="comment-count">
			<h2 class="page-title"><?php _e('Blog Archives', 'sandbox') ?></h2>
			</div>
<?php endif; ?>


<?php rewind_posts() ?>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
			</div>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="preview">
					<div class="entry-date bigdate"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time('d M'); ?></abbr></div>
					<h2 class="entry-title post-content-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><span><?php the_title() ?></span></a></h2>
					<div class="entry-content post-content">
						<h4><?php the_title() ?></h4>
						<p><?php the_excerpt(); ?></p>

						<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'sandbox' ) . '&after=</div>') ?>
					</div>
					<span class="attach-post-image" style="height:300px;display:block;background:url('<?php the_post_image_url('large'); ?>') center center repeat">&nbsp;</span>
				</div><!-- .preview -->
	
				<div class="entry-meta">
					<span class="author vcard"><?php printf(__('By %s', 'sandbox'), '<a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a>') ?></span><br />
					<span class="cat-links"><?php printf(__(' Filed under: %s', 'sandbox'), get_the_category_list(', ')) ?></span><br />
					<span><?php the_tags(__('<span class="tag-links">Tags: ', 'sandbox'), ", ", "</span><br \/>\n\t\t\t\t\t\n") ?>
					<span class="comments-link">Comments: <?php comments_popup_link(__('Add a Comment', 'sandbox'), __('1', 'sandbox'), __('%', 'sandbox')) ?></span>
<?php edit_post_link(__('Edit', 'sandbox'), "\t\t\t\t\t<span class=\"edit-link\">", "</span><br \/>\n\t\t\t\t\t\n"); ?></span>
				</div><!-- .entry-meta -->

			</div><!-- .post -->

<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
			</div>

		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>