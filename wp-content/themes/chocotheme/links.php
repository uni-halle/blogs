<?php
/*
 * Template Name: Links
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Choco
 */
?>

<?php get_header(); ?>
	<div class="post">
		<h1><?php _e( 'Links:', 'choco' ); ?></h1>
		<div class="entry">
			<ul class="links-list">
				<?php wp_list_bookmarks(); ?>
			</ul>
		</div><!-- .entry -->
	</div><!-- .post -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>