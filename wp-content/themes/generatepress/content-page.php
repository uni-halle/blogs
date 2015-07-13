<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package GeneratePress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="http://schema.org/CreativeWork" itemscope="itemscope">
	<div class="inside-article">
		<?php do_action( 'generate_before_content'); ?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php do_action( 'generate_after_entry_header'); ?>
		<div class="entry-content" itemprop="text">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'generate' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php do_action( 'generate_after_content'); ?>
		<?php edit_post_link( __( 'Edit', 'generate' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
