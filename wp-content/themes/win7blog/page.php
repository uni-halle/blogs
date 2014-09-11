<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="post">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content() ?>

<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'win7blog' ) . '&after=</div>') ?>
<?php if(function_exists('the_views')) { the_views(); printf(' | ');} ?>
<?php edit_post_link( __( 'Edit', 'win7blog' ), '<span class="edit-link">', '</span>' ) ?>

				</div>
			</div><!-- .post -->

<?php comments_template() ?>

		</div><!-- #content -->
		<?php get_sidebar() ?>
	</div><!-- #container -->

<?php get_footer() ?>