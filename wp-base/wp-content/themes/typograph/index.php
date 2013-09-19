<?php get_header() ?>

			<?php
				$postnum = 1;
				$showFirstAd = 1;
			?> 

			<?php while ( have_posts() ) : the_post() ?>

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
					<?php the_content(''.__('Get the whole story <span class="meta-nav">&raquo;</span>').''); ?>
					<?php wp_link_pages('before=<div class="page-link">' .__('Pages:') . '&after=</div>') ?>
				</div>
				<div class="cat-links">
					<?php the_tags(__('<span class="tag-links">Tags: '), ", ", "</span>\n\t\t\t\t\t") ?>
				</div>

				<!-- Insert ad after first post -->				
				<?php if ($postnum == $showFirstAd) { ?>
					<div class="indexAd"><a href="<?php echo get_option('home') ?>/" title="You can edit this ad by going editing the index.php file or opening /images/exampleAd.gif" rel="home"><img src="<?php bloginfo('template_directory') ?>/images/exampleAd.gif" alt="You can edit this ad by going editing the index.php file or opening /images/exampleAd.gif" width="516" height="60" /></a></div>
				<?php } ?>

				<?php $postnum++; ?> 
				<!-- END Insert ad after first post -->
				
				
			</div><!-- .post -->

			<div class="clear"></div>

<?php comments_template() ?>


<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>')) ?></div>
			</div>
		
		</div><!-- #content -->
	
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>