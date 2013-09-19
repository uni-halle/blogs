<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>
			<div id="nav-above" class="navigation">
				<h2 class="entry-title author"><?php printf(__('Author Archives.', 'sandbox'), "<span class='url fn n' href='#' title='$authordata->display_name' rel='me'>$authordata->display_name</span>") ?></h2>
				<div id="nav-above-content"><h2 class="entry-title"><span class="small"><?php the_author() ?></span></h2><?php if ( !(''== $authordata->user_description) ) : echo apply_filters('archive_meta', $authordata->user_description); endif; ?></div>
			</div>

<?php rewind_posts(); while (have_posts()) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class(); ?>">
				<div class="entry-content ">
					<p class="thePic clearfix">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_image('thumbnail'); ?></a>
					</p>
					<p>
						<?php the_content_rss('', TRUE, '', 40); ?><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark">Read More</a>
					</p>
				</div>
				<div class="entry-meta">
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<ul class="meta-list">
						<li><span class="entry-date">Posted on <abbr class="published green" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'sandbox'), the_date('', '', '', false), get_the_time()) ?></abbr>.</span></li>
						<li><span class="cat-links"><?php printf(__('Posted in %s', 'sandbox'), get_the_category_list(', ')) ?>.</span></li>
						<?php the_tags(__('<li><span class="tag-links">Tagged as ', 'sandbox'), ", ", ".</span></li>") ?>
						<li><span class="comments-link">Read <?php comments_popup_link(__('Comments (Be the first!)', 'sandbox'), __('Comments (1)', 'sandbox'), __('Comments (%)', 'sandbox')) ?>.</span></li>
<?php edit_post_link(__('Edit', 'sandbox'), "\t\t\t\t\t<li class=\"edit-link\">", "</li>\n\t\t\t\t\t\n"); ?>
					</ul>
				</div>
			</div><!-- .post -->

<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
			</div>
	
		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>