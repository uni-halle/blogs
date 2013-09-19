<?php get_header() ?>

<div class="singlepost">
<?php the_post() ?>
			<div id="post-<?php the_ID(); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="entry-content">
<?php the_content() ?>

<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: '), "</div>\n", 'number'); ?>

<?php edit_post_link(__('Edit'),'<span class="edit-link">','</span>') ?>

				</div>
				</div>
			</div><!-- .post -->

<?php if ( get_post_custom_values('comments') ) comments_template() // Add a key+value of "comments" to enable comments on this page ?>

		</div><!-- #content -->
	
	<?php get_sidebar() ?>
	</div><!-- #container -->


<?php get_footer() ?>