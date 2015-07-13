<?php
/**
 * The template is specifically for WooCommerce.
 *
 * This is the template that is used by WooCommerce.
 *
 * @package GeneratePress
 */

get_header(); ?>

	<div id="primary" <?php generate_content_class();?> itemprop="mainContentOfPage">
		<main id="main" <?php generate_main_class(); ?> role="main">
			<?php do_action('generate_before_main_content'); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="http://schema.org/CreativeWork" itemscope="itemscope">
				<div class="inside-article">
					<?php do_action( 'generate_before_content'); ?>
					<div class="entry-content" itemprop="text">
						<?php if ( function_exists( 'woocommerce_content' ) ) :
							woocommerce_content(); 
						endif; ?>
					</div><!-- .entry-content -->
					<?php do_action( 'generate_after_content'); ?>
				</div><!-- .inside-article -->
			</article><!-- #post-## -->
			<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer();