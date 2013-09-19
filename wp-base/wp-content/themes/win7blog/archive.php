<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>
	<h2 class="pagetitle">
		<?php
		/* If this is a category archive */ 
		if (is_category()) {
			printf( __('Archive for the &#8216;%s&#8217; Category', 'win7blog'), single_cat_title('',false) );
		/* If this is a tag archive */
		} elseif (is_tag()) {
			printf( __('Posts Tagged &#8216;%s&#8217;', 'win7blog'), single_tag_title('',false) );
		/* If this is a daily archive */ 
		} elseif (is_day()) {
			printf( __('Archive for %s', 'win7blog' ), get_the_time(__('F jS, Y', 'win7blog')) );
		/* If this is a monthly archive */ 
		} elseif (is_month()) { 
			printf( __('Archive for %s', 'win7blog' ), get_the_time(__('F, Y', 'win7blog')) );
		/* If this is a yearly archive */ 
		} elseif (is_year()) { 
			printf( __('Archive for %s', 'win7blog' ), get_the_time(__('Y', 'win7blog')) );
		/* If this is an author archive */ 
		} elseif (is_author()) { 
			_e('Author Archive', 'win7blog');
		/* If this is a paged archive */ 
		} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { 
			_e('Blog Archives', 'win7blog');
		} ?>
	</h2>
<?php rewind_posts() ?>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="post">
				<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permanent Link to %s', 'win7blog'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a><span class="datetime"> (<?php the_time('Y-n-j'); ?>)</span></h3>
				<div class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'win7blog' ), '', ' |' ) ?>
					<?php printf( __( 'Category: %s', 'win7blog' ), get_the_category_list(', ') ); echo ' |'; ?>
					<?php if($win7blog_options['hide_post_tags'] != 'on') the_tags( __( 'Tags: ', 'win7blog' ), ', ', ' |' ) ?>
					<?php if(function_exists('the_views')) { the_views(); echo '|';} ?>
					<?php comments_popup_link( __('No Comments', 'win7blog'), __('1 Comment', 'win7blog'), __('% Comments', 'win7blog') ) ?>
				</div>
				<div class="entry-content">
<?php the_excerpt( __( 'More &raquo;', 'win7blog' ) ) ?>

				</div>
			</div><!-- .post -->

<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'win7blog')) ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'win7blog')) ?></div>
		</div>

		</div><!-- #content .hfeed -->
		<?php get_sidebar() ?>
	</div><!-- #container -->

<?php get_footer() ?>