<?php get_header() ?>
		
		<h3 class="page-title"><?php _e('Category Archives:') ?> <span><?php echo single_cat_title(); ?></span></h3>

		
			<div class="archive-meta"><?php if ( !(''== category_description()) ) : echo apply_filters('archive_meta', category_description()); endif; ?></div>
		
<?php while (have_posts()) : the_post(); ?>

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
				<!-- <div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s &#8211; %2$s'), the_date('', '', '', false), get_the_time()) ?></abbr></div> -->
				<div class="entry-content">
<?php the_content(''.__('Get the whole story <span class="meta-nav">&raquo;</span>').''); ?>

				</div>
	

			
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')) ?></div>
			</div>

		</div><!-- #content -->
	
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>