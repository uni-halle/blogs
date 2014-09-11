<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class() ?>">
				<div class="full-photo">
					<div class="bigdate photo-credit">&copy; <?php the_time('Y'); ?> <?php the_author(); ?></div>
<?php the_attachment_image('large'); ?>
				</div>
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">					
<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>', 'sandbox').''); ?>

<?php wp_link_pages('before=<div class="page-link">' .__('Pages:', 'sandbox') . '&after=</div>') ?>
					<div class="exif-data">
					<h4>Exif Data</h4>
					<?php grab_exif_data() ?>
					</div>
				</div>
				<div class="entry-meta">
					<a href="<?php echo get_permalink($post->post_parent) ?>" class="attachment-title" rel="attachment">&laquo; Back to: <?php echo get_the_title($post->post_parent) ?></a>
					<?php printf(__('This photograph was taken by %1$s and posted on <abbr class="published" title="%2$sT%3$s">%4$s at %5$s</abbr>. Bookmark the <a href="%8$s" title="Permalink to %9$s" rel="bookmark">permalink</a>. Follow any comments here with the <a href="%10$s" title="Comments RSS to %9$s" rel="alternate" type="application/rss+xml">RSS feed for this post</a>.', 'sandbox'),
						'<span class="author vcard"><a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a></span>',
						get_the_time('Y-m-d'),
						get_the_time('H:i:sO'),
						the_date('', '', '', false),
						get_the_time(),
						get_the_category_list(', '),
						get_the_tag_list(' '.__('and tagged').' ', ', ', ''),
						get_permalink(),
						wp_specialchars(get_the_title(), 'double'),
						comments_rss() ) ?>

<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Comments and trackbacks open ?>
					<?php printf(__('<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'sandbox'), get_trackback_url()) ?>
<?php elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Only trackbacks open ?>
					<?php printf(__('Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'sandbox'), get_trackback_url()) ?>
<?php elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Only comments open ?>
					<?php printf(__('Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'sandbox')) ?>
<?php elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Comments and trackbacks closed ?>
					<?php _e('Both comments and trackbacks are currently closed.') ?>
<?php endif; ?>

<?php edit_post_link(__('Edit', 'sandbox'), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>"); ?>

				</div>
			</div><!-- .post -->

			<div id="nav-below" class="navigation">
				<div class="browse"><h3>Browse</h3></div>
				<div class="nav-previous"><small><?php ps_previous_image_link( '<div>&laquo; Previous Image</div> %link' ) ?></small></div>
				<div class="nav-next"><small><?php ps_next_image_link( '<div>Next Image &raquo;</div> %link' ) ?></small></div>
			</div>

<?php comments_template(); ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>