<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php if (have_posts()) : ?>

			<div id="nav-above" class="navigation">
				<h2 class="entry-title"><?php _e('Search Results:', 'sandbox') ?></h2>
				<div id="nav-above-content"><h2 class="entry-title"><span class="small"><?php echo wp_specialchars(stripslashes($_GET['s']), true); ?></span></h2></div>
			</div>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class(); ?>">
				<div class="entry-content">
					<p class="thePic clearfix">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_image('thumbnail'); ?></a>
					</p>
					<p>
						<?php the_content_rss('', TRUE, '', 40); ?><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark">Read More</a>
					</p>
				</div>
				<div class="entry-meta clearfix">
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
					<ul class="meta-list">
						<li><span class="author vcard"><?php printf(__('By %s', 'sandbox'), '<a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a>') ?></span></li>
						<li><span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s &#8211; %2$s', 'sandbox'), the_date('', '', '', false), get_the_time()) ?></abbr></span></li>
						<li><span class="cat-links"><?php printf(__('Posted in %s', 'sandbox'), get_the_category_list(', ')) ?></span></li>
						<li><span class="tag-links"><?php the_tags(__('Tagged ', 'sandbox'), ', ') ?></span></li>
						<li><span class="comments-link"><?php comments_popup_link(__('Comments (0)', 'sandbox'), __('Comments (1)', 'sandbox'), __('Comments (%)', 'sandbox')) ?></span></li>
<?php edit_post_link(__('Edit', 'sandbox'), "\t\t\t\t\t<li class=\"edit-link\">", "</li>\n\t\t\t\t\t\n"); ?>
					</ul>
				</div>
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo; Older posts</span>', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('<span class="meta-nav">Newer posts &raquo;</span>', 'sandbox')) ?></div>
			</div>

<?php else : ?>

			<div id="post-0" class="post noresults">
				<h2 class="entry-title"><?php _e('Nothing Found', 'sandbox') ?></h2>
				<div class="entry-content">
					<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'sandbox') ?></p>
				</div>
				<form id="noresults-searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="noresults-s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="40" />
						<input id="noresults-searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'sandbox') ?>" />
					</div>
				</form>
			</div><!-- .post -->

<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>