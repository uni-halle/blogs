<?php get_header() ?>
	
	<div id="container">

	<?php the_post() ?>		
<div class="left-col">
<h2 class="page-title author"><?php printf(__('Archives:<br/><span class="vcard">%s</span>', 'sandbox'), "$authordata->display_name") ?></h2>
			<div class="archive-meta"><?php if ( !(''== $authordata->user_description) ) : echo apply_filters('archive_meta', $authordata->user_description); endif; ?></div>
			<div class="author-links">Posts by <?php the_author_posts_link(); ?><br/>
<?php the_author(); ?>&rsquo;s <a href="<?php the_author_url(); ?>">Website</a></p></div>
			</div>


			<div id="content">

<?php rewind_posts(); while (have_posts()) : the_post(); ?>	

			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class(); ?>">
			<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), get_the_title()) ?>" rel="bookmark"><?php the_title() ?></a></h3>
				<div class="entry-content">
<?php the_excerpt(''.__('Read More <span class="meta-nav">&raquo;</span>', 'sandbox').'') ?>
				</div>
				<div class="entry-meta">
					<span class="author vcard"><?php printf(__('By %s', 'sandbox'), '<a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf(__('View all posts by %s', 'sandbox'), $authordata->display_name) . '">'.get_the_author().'</a>') ?></span>
					<span class="meta-sep">|</span>
					<span><?php the_date('d M y'); ?></span>
					<span class="meta-sep">|</span>
<?php if ( $cats_meow = sandbox_cats_meow(', ') ) : /* only show categories other than the one queried */ ?>
					<span class="cat-links"><?php printf(__('Also posted in %s', 'sandbox'), $cats_meow) ?></span>
					<span class="meta-sep">|</span>
<?php endif ?>
<?php edit_post_link(__('Edit', 'sandbox'), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n"); ?>
					<span class="comments-link"><?php comments_popup_link(__('Comments (0)', 'sandbox'), __('Comments (1)', 'sandbox'), __('Comments (%)', 'sandbox')) ?></span>
				</div>
				</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox')) ?></div>
			</div>
			</div><!-- #content -->
			<?php get_sidebar() ?>
			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'sandbox')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('<span class="meta-nav">&raquo;</span> Newer posts', 'sandbox')) ?></div>
			</div>
		</div><!-- #container -->
	<?php include (TEMPLATEPATH . '/bottom.php'); ?>	
<?php get_footer() ?>