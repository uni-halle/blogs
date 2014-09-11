<?php get_header() ?>

<!--		<div class="singlepost">-->

<?php if (have_posts()) : ?>

		<h3 class="page-title"><?php _e('Search Results for:') ?> <span id="search-terms"><?php echo wp_specialchars(stripslashes($_GET['s']), true); ?></span></h3>
		<!--</div>  END POST -->
			
<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php typograph_post_class() ?>">
				<div class="post-date">
					<span class="post-month"><?php the_time('M') ?></span>
					<span class="post-day"><?php the_time('d') ?></span>
					<span class="comments-link"><?php comments_popup_link(__('0'), __('1'), __('%'), __(''), __('NO')) ?></span>
					<span class="comments-heading">comments</span>
				</div>
				
				<div class="cat-links">
					<?php printf(__('%s'), get_the_category_list(', ')) ?>
					<?php edit_post_link(__('Edit'), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n"); ?>
					
				</div>

				
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				
				<div class="entry-content">
<?php the_excerpt(''.__('Read More <span class="meta-nav">&raquo;</span>').'') ?>

				</div>
				
				
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')) ?></div>
			</div>

<?php else : ?>

			<div id="post-0" class="post noresults">
				<h2 class="entry-title"><?php _e('Nothing Found') ?></h2>
				<div class="entry-content">
					<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.') ?></p>
				</div>
				<form id="noresults-searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="noresults-s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="40" />
						<input id="noresults-searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find') ?>" />
					</div>
				</form>
			</div><!-- .post -->

<?php endif; ?>

		</div><!-- #content -->
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>