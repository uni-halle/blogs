<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" <?php $sticky_index=win7blog_sticky_index(); post_class('stick-post'.$sticky_index); ?>>

				<?php if (is_sticky()): ?>
					<div class="lili">
				<?php endif ?>

				<div class="post_datetime">
					<?php the_time('Y-n'); ?>
					<div class="post_day"><?php the_time('j'); ?></div>
				</div>
				
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permanent Link to %s', 'win7blog'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2>

				<div class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'win7blog' ), '', ' |' ) ?>
					<?php printf( __( 'Category: %s', 'win7blog' ), get_the_category_list(', ') ); echo ' |'; ?>
					<?php if($win7blog_options['hide_post_tags'] != 'on') the_tags( __( 'Tags: ', 'win7blog' ), ', ', ' |' ) ?>
					<?php if(function_exists('the_views')) { the_views(); echo ' |';} ?>
					<?php comments_popup_link( __('No Comments', 'win7blog'), __('1 Comment', 'win7blog'), __('% Comments', 'win7blog') ) ?>
				</div>

				<div class="entry-content">
				<?php the_content( __( 'More &raquo;', 'win7blog' ) ) ?>
				<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'win7blog' ) . '&after=</div>') ?>
				</div>
				
			<?php if (is_sticky()): ?>
					<div class="entry-titlex" style="<?php echo win7blog_title_margin($sticky_index) ?>">
						<a href="<?php the_permalink() ?>" title="<?php printf( __('Permanent Link to %s', 'win7blog'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a>
						<span class="clear-content"><?php $sticky_length = 140; $title_length = strlen(get_the_title()); $text_length = $sticky_length - $title_length; echo win7blog_clear_content(get_the_content(), $text_length); ?></span>
					</div>
					<div class="entry-metax">
						<?php if(function_exists('the_views')) { ?><span class="views"><?php the_views(); ?></span><?php } ?>
						<?php comments_popup_link( __('No Comments', 'win7blog'), __('1 Comment', 'win7blog'), __('% Comments', 'win7blog') ) ?>
						<a href="<?php the_permalink() ?>" title="<?php printf( __('Permanent Link to %s', 'win7blog'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php echo __('Read More', 'win7blog') ?></a>
						<?php edit_post_link( __( 'Edit', 'win7blog' ), "<span class=\"edit-link\">", "</span>" ) ?>
					</div>					
				</div><!-- .lili -->
			<?php endif ?>
				
			</div><!-- .post -->
			<div class="fixed"></div>

			<?php comments_template(); ?>

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__( '&laquo; Older Entries', 'win7blog' )) ?></div>
				<div class="nav-next"><?php previous_posts_link(__( 'Newer Entries &raquo;', 'win7blog' )) ?></div>
			</div>

		</div><!-- #content -->
		<?php get_sidebar() ?>
	</div><!-- #container -->

<?php get_footer() ?>