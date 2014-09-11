<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header() ?>
	
	<div id="container">
	
	<div class="left-col"><h2 class="entry-title"><?php the_title() ?></h2>
<div class="author-desc"><p>You can view a list of entries by category or by month.</p></div>
	</div>
		<div id="content">
		

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				
				<div class="entry-content">


					<ul id="archives-page" class="xoxo">
						<li id="category-archives" class="content-column">
							<h3><?php _e('Archives by Category', 'sandbox') ?></h3>
							<ul>
								<?php wp_list_categories('title_li=&sort_column=name&show_count=1&show_last_updated=1&use_desc_for_title=1') ?> 
							</ul>
						</li>
						<li id="monthly-archives" class="content-column">
							<h3><?php _e('Archives by Month', 'sandbox') ?></h3>
							<ul>
								<?php wp_get_archives('type=monthly&show_post_count=1') ?>
							</ul>
						</li>
					</ul>


				</div>
			</div><!-- .post -->
		</div><!-- #content -->
<?php get_sidebar() ?>
		<div id="nav-above" class="navigation">
			<div class="nav-previous"><?php previous_post_link('<span class="meta-nav">&laquo;</span> %link') ?></div>
				<div class="nav-next"><?php next_post_link('<span class="meta-nav">&raquo;</span> %link') ?></div>
		</div>
	</div><!-- #container -->
<?php include (TEMPLATEPATH . '/bottom.php'); ?>	
<?php get_footer() ?>