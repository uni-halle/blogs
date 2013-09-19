<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '&laquo; %title' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title &raquo;' ) ?></div>
			</div>

			<div id="post-<?php the_ID() ?>" class="post">
				<h2 class="single-title"><?php the_title() ?></h2>
				<?php if(win7blog_is_original()): ?>
					<div id="clarify"><?php echo __('[Statement]  Author: ', 'win7blog') ?><a href="<?php bloginfo('home') ?>/"><?php the_author(); ?></a>
					<?php echo __('  First Published on: ', 'win7blog') ?><a href=<?php _e(get_permalink(),'win7blog') ?>><?php _e(get_permalink(),'win7blog') ?></a></div>
				<?php endif ?>
				<div class="entry-content">
					<?php the_content() ?>
					<div class="single-meta">
						<span style="float:left;"><?php the_author(); echo __(' posted at ', 'win7blog'); the_time('Y-n-j'); ?></span>
						<?php if(function_exists('the_views')) { the_views(); printf(' |');} ?>
						<?php printf( __( 'Category: %s', 'win7blog' ), get_the_category_list(', ') ) ?>
						<?php if($win7blog_options['hide_post_tags'] != 'on') the_tags( __( '| Tags: ', 'win7blog' ), ", ", "" ) ?>
						<?php edit_post_link( __( 'Edit', 'win7blog' ), '| ', "" ) ?>
					</div>
					<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'win7blog' ) . '&after=</div>') ?>
				</div>
			</div><!-- .post -->
<?php comments_template() ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '&laquo; %title' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title &raquo;' ) ?></div>
			</div>
			
		</div><!-- #content -->
		<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>