<?php get_header() ?>

		<!-- <div class="post"> -->
<?php the_post() ?>

<?php if ( is_day() ) : ?>
			<h3 class="page-title"><?php printf(__('Daily Archives: <span>%s</span>'), get_the_time(get_option('date_format'))) ?></h3>
<?php elseif ( is_month() ) : ?>
			<h3 class="page-title"><?php printf(__('Monthly Archives: <span>%s</span>'), get_the_time('F Y')) ?></h3>
<?php elseif ( is_year() ) : ?>
			<h3 class="page-title"><?php printf(__('Yearly Archives: <span>%s</span>'), get_the_time('Y')) ?></h3>
<?php elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) : ?>
			<h3 class="page-title"><?php _e('Blog Archives') ?></h3>
<?php endif; ?>
<!--</div> -->
<?php rewind_posts() ?>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php typograph_post_class() ?>">
				<div class="post-date"><span class="post-month"><?php the_time('M') ?></span> <span class="post-day"><?php the_time('d') ?></span></div>
				
				<div class="post-date">
					<span class="post-month"><?php the_time('M') ?></span>
					<span class="post-day"><?php the_time('d') ?></span>
					<span class="comments-link"><?php comments_popup_link(__('0'), __('1'), __('%'), __(''), __('NO')) ?></span>
					<span class="comments-heading">comments</span>
				</div>

				
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				<!-- <div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s &#8211; %2$s'), the_date('', '', '', false), get_the_time()) ?></abbr></div> -->
				<div class="entry-content">
<?php the_excerpt(''.__('Read More <span class="meta-nav">&raquo;</span>').'') ?>

				</div>
				
				
			</div><!-- .post -->

<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')) ?></div>
			</div>

		</div><!-- #content .hfeed -->
	
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>