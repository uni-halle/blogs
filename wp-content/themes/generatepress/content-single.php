<?php
/**
 * @package GeneratePress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_article_schema( 'BlogPosting' ); ?>>
	<div class="inside-article">
		<?php do_action( 'generate_before_content'); ?>
		
		<header class="entry-header">
			<?php if ( generate_show_title() ) : ?>
				<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
			<?php endif; ?>
			<div class="entry-meta">
				<?php generate_posted_on(); ?>
			</div><!-- .entry-meta -->
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
		<?php do_action( 'generate_after_entry_content'); ?>

		<footer class="entry-meta">
			<?php generate_entry_meta(); ?>
			<?php generate_content_nav( 'nav-below' ); ?>
			<?php edit_post_link( __( 'Edit', 'generate' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
		<?php do_action( 'generate_after_content'); ?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
