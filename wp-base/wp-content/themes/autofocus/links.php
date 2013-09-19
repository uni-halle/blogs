<?php
/*
Template Name: Links Page
*/
?>
<?php get_header() ?>
	
	<div id="container">
		<div id="content">
			<div class="category">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				</div>


<?php the_post() ?>
			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class() ?>">
				<!-- <?php the_content(); ?> -->
					<ul id="links-page" class="xoxo">
<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>') ?>
					</ul>

			</div><!-- .post -->

<?php if ( get_post_custom_values('comments') ) comments_template() // Add a key/value of "comments" to enable comments on pages! ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>