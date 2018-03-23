<?php
/**
 * Template Name: Hauptseite
 */
?>
<?php get_header(); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

	<?php while (have_posts()) : the_post() ?>
		<article <?php post_class("content__article article typo"); ?>>
			<?php the_title("<h1 class='article__title'>", "</h1>"); ?>
			<?php the_content("mehr…"); ?>
		</article>
	<?php endwhile; ?>

</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>