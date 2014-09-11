<?php get_header() ?>

	<div id="container">
		<div id="content">
<?php $feature_post = get_posts( 'category_name=feature&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>

			<div id="featured" style="background:#241C0F url('<?php the_post_image_url('full'); ?>') center center repeat;">
				<div class="featured-post">
					<small>Feature:</small>
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
					<p class="featured-content">
<?php the_content_rss('', TRUE, '', 20); ?><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark">Read More</a>
					</p> <!-- END featured-content -->
				</div>
			</div>
<?php endforeach; ?>

<?php while ( have_posts() ) : the_post() ?>
			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				<div class="entry-content">
					<p class="thePic clearfix">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_image('thumbnail'); ?></a>
					</p>
					<p>
						<?php the_content_rss('', TRUE, '', 40); ?><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark">Read More</a>
					</p>
				</div><!-- .entry-content -->
				
				<div class="entry-meta clearfix">
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
					<ul class="meta-list">
						<li><span class="author vcard"><?php printf(__('Written by %s', 'sandbox'), '<a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a>') ?></span></li>
						<li><span class="entry-date">Posted on <abbr class="published green" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'sandbox'), the_date('', '', '', false), get_the_time()) ?>.</abbr></span></li>
						<li><span class="cat-links"><?php printf(__('Filed under %s', 'sandbox'), get_the_category_list(', ')) ?>.</span></li>
<?php the_tags(__('<li><span class="tag-links">Tagged as ', 'sandbox'), ", ", ".</span></li>") ?>
						<li><span class="comments-link">Read <?php comments_popup_link(__('Comments (Be the first!)', 'sandbox'), __('Comments (1)', 'sandbox'), __('Comments (%)', 'sandbox')) ?>.</span></li>
<?php edit_post_link(__('Edit.', 'sandbox'), "\t\t\t\t\t<li class=\"edit-link\">", "</li>"); ?>
					</ul>
				</div>
			</div><!-- .post -->

<?php comments_template() ?>
<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;Older posts</span>', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('<span class="meta-nav">Newer posts&raquo;</span>', 'sandbox')) ?></div>
			</div>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>