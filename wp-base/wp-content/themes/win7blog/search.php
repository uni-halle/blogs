<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php if ( have_posts() ) : ?>

		<h2 class="page-title"><?php _e( 'Search:', 'win7blog' ) ?> <span><?php the_search_query() ?></span></h2>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link( __( '&laquo; Older Results', 'win7blog' ) ) ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer Results &raquo;', 'win7blog' ) ) ?></div>
			</div>

<?php while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="post">
				<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permanent Link to %s', 'win7blog'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a><span class="datetime"> (<?php the_time('Y-n-j'); ?>)</span></h3>

				<?php if ( $post->post_type == 'post' ) { ?>
				<div class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'win7blog' ), '', ' |' ) ?>
					<?php printf( __( 'Category: %s', 'win7blog' ), get_the_category_list(', ') ); echo ' |'; ?>
					<?php if($win7blog_options['hide_post_tags'] != 'on') the_tags( __( 'Tags: ', 'win7blog' ), ', ', ' |' ) ?>
					<?php if(function_exists('the_views')) { the_views(); echo ' |';} ?>
					<?php comments_popup_link( __('No Comments', 'win7blog'), __('1 Comment', 'win7blog'), __('% Comments', 'win7blog') ) ?>
				</div>
<?php } ?>
				<div class="entry-content">
					<?php the_excerpt( __( 'More &raquo;', 'win7blog' ) ) ?>

				</div>
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link( __( '&laquo; Older Results', 'win7blog' ) ) ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer Results &raquo;', 'win7blog' ) ) ?></div>
			</div>

<?php else : ?>

			<div id="post-0" class="post no-results not-found">
				<h2 class="entry-title"><?php _e( 'No Results', 'win7blog' ) ?></h2>
				<div class="entry-content">
					<p><?php _e('No posts found. Try a different search?', 'win7blog') ?></p>
				</div>
				<form id="searchform-no-results" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s-no-results" name="s" class="text" type="text" value="<?php the_search_query() ?>" size="40" />
						<input id="w7b_search_btn2" class="button" type="submit" value="<?php _e( 'Search', 'win7blog' ) ?>" />
					</div>
				</form>
			</div><!-- .post -->

<?php endif; ?>

		</div><!-- #content -->
		<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>