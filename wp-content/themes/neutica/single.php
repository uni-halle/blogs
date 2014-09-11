	<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class(); ?>">
				<div class="entry-content">
<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>', 'sandbox').''); ?>

					<?php wp_link_pages('before=<div class="page-link">' .__('Pages:', 'sandbox') . '&after=</div>') ?>
				</div>
				<div class="entry-meta">
					<h2 class="entry-title"><?php the_title(); ?></h2>
					<ul class="meta-list">
					<?php printf(__('<li>Written by %1$s.</li><li>Posted on <abbr class="published green" title="%2$sT%3$s">%4$s</abbr>.</li><li>Filed under %6$s.</li>%7$s<li>Bookmark the <a href="%8$s" title="Permalink to %9$s" rel="bookmark">Permalink</a>.</li><li>Subscribe to <a href="%10$s" title="Comments RSS to %9$s" rel="alternate" type="application/rss+xml">Comments RSS feed</a>.</li>', 'sandbox'),
						'<span class="author vcard"><a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a></span>',
						get_the_time('Y-m-d'),
						get_the_time('H:i:sO'),
						the_date('', '', '', false),
						get_the_time(),
						get_the_category_list(', '),
						get_the_tag_list(''.__('<li>Tagged as').' ', ', ', '.</li>'),
						get_permalink(),
						wp_specialchars(get_the_title(), 'double'),
						comments_rss() ) ?>

<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Comments and trackbacks open ?>
					<?php printf(__('<li>Post a <a class="comment-link" href="#respond" title="Post a comment"> Comment</a>.</li><li>Leave a <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.</li>', 'sandbox'), get_trackback_url()) ?>
<?php elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Only trackbacks open ?>
					<?php printf(__('<li>Comments are closed, but you can leave a <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.</li>', 'sandbox'), get_trackback_url()) ?>
<?php elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Only comments open ?>
					<?php printf(__('<li>Trackbacks are closed, but you can post a <a class="comment-link" href="#respond" title="Post a comment">Comment</a>.</li>', 'sandbox')) ?>
<?php elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Comments and trackbacks closed ?>
					<?php _e('<li>Both comments and trackbacks are currently closed.</li>') ?>
<?php endif; ?>

<?php edit_post_link(__('Edit', 'sandbox'), "\n\t\t\t\t\t<li class=\"edit-link\">", "</li>"); ?>
					</ul>
<?php comments_template(); ?>

				</div>
				
				
			</div><!-- .post -->

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link('%link', '<span class="meta-nav">Older Entry</span>: %title') ?></div>
				<div class="nav-next"><?php next_post_link('%link', '<span class="meta-nav">Newer Entry</span>: %title') ?></div>
			</div>



		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>
