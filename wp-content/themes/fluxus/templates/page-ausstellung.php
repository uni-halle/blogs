<?php
/**
 * Template Name: Ausstellungsinhalte
 */
?>
<?php get_header(); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

	<?php if(get_post_meta( $post->ID, "_pagelist", true )) { get_template_part('/templates/parts/pagelist', ''); } ?>

</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>