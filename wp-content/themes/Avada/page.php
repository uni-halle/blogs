<?php
/**
 * Template used for pages.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_pages' ) ) : ?>
					<?php if ( 0 < avada_number_of_featured_images() || get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
						<div class="fusion-flexslider flexslider post-slideshow">
							<ul class="slides">
								<?php if ( get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
									<li>
										<div class="full-video">
											<?php echo get_post_meta( $post->ID, 'pyre_video', true ); // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_data = Avada()->images->get_attachment_data( get_post_thumbnail_id() ); ?>
									<?php if ( is_array( $attachment_data ) ) : ?>
										<li>
											<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
												<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
											</a>
										</li>
									<?php endif; ?>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'page' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_data = Avada()->images->get_attachment_data( $attachment_new_id ); ?>
										<?php if ( is_array( $attachment_data ) ) : ?>
											<li>
												<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
													<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
												</a>
											</li>
										<?php endif; ?>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // Password check. ?>

			<div class="post-content">
				<?php the_content(); ?>
				<?php fusion_link_pages(); ?>
			</div>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php $woo_thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); ?>
					<?php $is_woo_thanks_page = ( ! get_option( 'woocommerce_thanks_page_id' ) ) ? false : is_page( get_option( 'woocommerce_thanks_page_id' ) ); ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! $is_woo_thanks_page ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // Password check. ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
