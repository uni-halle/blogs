<?php
/**
 * @package GeneratePress
 */
$generate_settings = wp_parse_args( 
	get_option( 'generate_settings', array() ), 
	generate_get_defaults() 
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemprop="blogPost" itemtype="http://schema.org/BlogPosting" itemscope="itemscope">
	<div class="inside-article">
		<?php do_action( 'generate_before_content'); ?>
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( generate_get_link_url() ) ), '</a></h2>' ); ?>
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php generate_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
		<?php do_action( 'generate_after_entry_header'); ?>
		
		<?php
		if ( is_search() || 'excerpt' == $generate_settings['post_content'] ) : ?>
		<div class="entry-summary" itemprop="text">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content" itemprop="text">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'generate' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php endif; ?>
		
		<?php do_action( 'generate_after_entry_content'); ?>

		<footer class="entry-meta">
			<?php generate_entry_meta(); ?>
		</footer><!-- .entry-meta -->
		<?php do_action( 'generate_after_content'); ?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
