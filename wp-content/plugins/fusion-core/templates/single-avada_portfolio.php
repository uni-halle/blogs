<?php
/**
 * Portfolio Template.
 *
 * @package Fusion-Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'Avada' ) ) {
	exit( 'This feature requires the Avada theme.' );
}
?>

<?php get_header(); ?>
<div id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	fusion_cached_query( $query_string . '&paged=' . $paged );
	$nav_categories  = ( isset( $_GET['portfolioCats'] ) ) ? $_GET['portfolioCats'] : '';
	?>

	<?php $post_pagination = get_post_meta( $post->ID, 'pyre_post_pagination', true ); ?>
	<?php if ( ( Avada()->settings->get( 'portfolio_pn_nav' ) && 'no' !== $post_pagination ) || ( ! Avada()->settings->get( 'portfolio_pn_nav' ) && 'yes' === $post_pagination ) ) : ?>
		<div class="single-navigation clearfix">
			<?php
			if ( $nav_categories ) {
				$prev_args = array(
					'format'      => '%link',
					'link'        => esc_html__( 'Previous', 'fusion-core' ),
					'in_same_tax' => 'portfolio_category',
					'in_cats'     => $nav_categories,
					'return'      => 'href',
				);
			} else {
				$prev_args = array(
					'format' => '%link',
					'link'   => esc_html__( 'Previous', 'fusion-core' ),
					'return' => 'href',
				);
				// PolyLang tweak.
				if ( function_exists( 'pll_default_language' ) ) {
					$prev_args['in_same_tax'] = 'language';
				}
			}
			$previous_post_link = fusion_previous_post_link_plus( apply_filters( 'fusion_builder_portfolio_prev_args', $prev_args ) );
			?>

			<?php if ( $previous_post_link ) : ?>
				<?php if ( $nav_categories ) : ?>
					<?php $previous_post_link = fusion_add_url_parameter( $previous_post_link, 'portfolioCats', $nav_categories ); ?>
				<?php endif; ?>
				<a href="<?php echo esc_url_raw( $previous_post_link ); ?>" rel="prev"><?php esc_html_e( 'Previous', 'fusion-core' ); ?></a>
			<?php endif; ?>

			<?php
			if ( $nav_categories ) {
				$next_args = array(
					'format'      => '%link',
					'link'        => esc_html__( 'Next', 'fusion-core' ),
					'in_same_tax' => 'portfolio_category',
					'in_cats'     => $nav_categories,
					'return'      => 'href',
				);
			} else {
				$next_args = array(
					'format' => '%link',
					'link'   => esc_html__( 'Next', 'fusion-core' ),
					'return' => 'href',
				);
				// PolyLang tweak.
				if ( function_exists( 'pll_default_language' ) ) {
					$next_args['in_same_tax'] = 'language';
				}
			}
			$next_post_link = fusion_next_post_link_plus( apply_filters( 'fusion_builder_portfolio_next_args', $next_args ) );
			?>

			<?php if ( $next_post_link ) : ?>
				<?php if ( $nav_categories ) : ?>
					<?php $next_post_link = fusion_add_url_parameter( $next_post_link, 'portfolioCats', $nav_categories ); ?>
				<?php endif; ?>
				<a href="<?php echo esc_url_raw( $next_post_link ); ?>" rel="next"><?php esc_html_e( 'Next', 'fusion-core' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php $full_image = ''; ?>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'portfolio_featured_images' ) ) : ?>
					<?php if ( 0 < avada_number_of_featured_images() || get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
						<div class="fusion-flexslider flexslider fusion-post-slideshow post-slideshow fusion-flexslider-loading">
							<ul class="slides">
								<?php if ( get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
									<li>
										<div class="full-video">
											<?php echo get_post_meta( $post->ID, 'pyre_video', true ); // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && ( ! fusion_get_mismatch_option( 'portfolio_disable_first_featured_image', 'show_first_featured_image', $post->ID ) || 'no' === fusion_get_mismatch_option( 'portfolio_disable_first_featured_image', 'show_first_featured_image', $post->ID ) ) ) : ?>
									<?php $attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
									<?php $full_image       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
									<?php $attachment_data  = wp_get_attachment_metadata( get_post_thumbnail_id() ); ?>
									<?php $alt_tag = esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); ?>
									<li>
										<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
											<a href="<?php echo esc_url_raw( $full_image[0] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( get_post_field( 'post_excerpt', get_post_thumbnail_id() ) ); ?>" data-title="<?php echo esc_attr( get_post_field( 'post_title', get_post_thumbnail_id() ) ); ?>" data-caption="<?php echo esc_attr( get_post_field( 'post_excerpt', get_post_thumbnail_id() ) ); ?>">
												<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
											</a>
										<?php else : ?>
											<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
										<?php endif; ?>
									</li>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'avada_portfolio' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_image = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $full_image       = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $attachment_data  = wp_get_attachment_metadata( $attachment_new_id ); ?>
										<?php $alt_tag = esc_attr( get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true ) ); ?>
										<li>
											<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
												<a href="<?php echo esc_url_raw( $full_image[0] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( get_post_field( 'post_excerpt', $attachment_new_id ) ); ?>" data-title="<?php echo esc_attr( get_post_field( 'post_title', $attachment_new_id ) ); ?>" data-caption="<?php echo esc_attr( get_post_field( 'post_excerpt', $attachment_new_id ) ); ?>">
													<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
												</a>
											<?php else : ?>
												<img src="<?php echo esc_url_raw( $attachment_image[0] ); ?>" alt="<?php echo esc_attr( $alt_tag ); ?>" role="presentation" />
											<?php endif; ?>
										</li>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; // Portfolio single image theme option check. ?>
			<?php endif; // Password check. ?>

			<?php
			$portfolio_width          = ( 'half' === fusion_get_option( 'portfolio_featured_image_width', 'width', $post->ID ) ) ? 'half' : 'full';
			$portfolio_width          = ( ! Avada()->settings->get( 'portfolio_featured_images' ) && 'half' === $portfolio_width ) ? 'full' : $portfolio_width;
			$project_desc_title_style = ( ! fusion_get_option( 'portfolio_project_desc_title', 'project_desc_title', $post->ID ) || 'no' === fusion_get_option( 'portfolio_project_desc_title', 'project_desc_title', $post->ID ) ) ? 'display:none;' : '';
			$project_desc_width_style = ( 'full' === $portfolio_width && ( ! fusion_get_option( 'portfolio_project_details', 'project_details', $post->ID ) || 'no' === fusion_get_option( 'portfolio_project_details', 'project_details', $post->ID ) ) ) ? ' width:100%;' : '';
			$project_details          = ( in_array( fusion_get_option( 'portfolio_project_details', 'project_details', $post->ID ), array( 'yes', '1', 1 ), true ) ) ? true : false;
			?>
			<div class="project-content">
				<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
				<div class="project-description post-content<?php echo ( $project_details ) ? ' fusion-project-description-details' : ''; ?>" style="<?php echo esc_attr( $project_desc_width_style ); ?>">
					<?php if ( ! post_password_required( $post->ID ) ) : ?>
						<?php echo apply_filters( 'fusion_portfolio_post_project_description_label', '<h3 style="' . $project_desc_title_style . '">' . esc_html__( 'Project Description', 'fusion-core' ) . '</h3>', esc_attr__( 'Project Description', 'fusion-core' ), $project_desc_title_style, 'h3' ); // WPCS: XSS ok. ?>
					<?php endif; ?>
					<?php the_content(); ?>
					<?php
					if ( function_exists( 'fusion_link_pages' ) ) {
						fusion_link_pages();
					}
					?>
				</div>

				<?php if ( ! post_password_required( $post->ID ) && $project_details ) : ?>
					<div class="project-info">
						<?php
						$project_details_title = esc_html__( 'Project Details', 'fusion-core' );
						$project_details_tag = 'h3';
						echo apply_filters( 'fusion_portfolio_post_project_details_label', '<' . $project_details_tag . '>' . $project_details_title . '</' . $project_details_tag . '>', $project_details_title, $project_details_tag ); // WPCS: XSS ok.
						?>

						<?php if ( get_the_term_list( $post->ID, 'portfolio_skills', '', '<br />', '' ) ) : ?>
							<div class="project-info-box">
								<?php
								$project_skills_title = esc_html__( 'Skills Needed:', 'fusion-core' );
								$project_skills_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_skills_label', '<' . $project_skills_tag . '>' . $project_skills_title . '</' . $project_skills_tag . '>', $project_skills_title, $project_skills_tag ); // WPCS: XSS ok.
								?>
								<div class="project-terms">
									<?php echo get_the_term_list( $post->ID, 'portfolio_skills', '', '<br />', '' ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( get_the_term_list( $post->ID, 'portfolio_category', '', '<br />', '' ) ) : ?>
							<div class="project-info-box">
								<?php
								$project_categories_title = esc_html__( 'Categories:', 'fusion-core' );
								$project_categories_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_categories_label', '<' . $project_categories_tag . '>' . $project_categories_title . '</' . $project_categories_tag . '>', $project_categories_title, $project_categories_tag ); // WPCS: XSS ok.
								?>
								<div class="project-terms">
									<?php echo get_the_term_list( $post->ID, 'portfolio_category', '', '<br />', '' ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( get_the_term_list( $post->ID, 'portfolio_tags', '', '<br />', '' ) ) : ?>
							<div class="project-info-box">
								<?php
								$project_tags_title = esc_html__( 'Tags:', 'fusion-core' );
								$project_tags_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_tags_label', '<' . $project_tags_tag . '>' . $project_tags_title . '</' . $project_tags_tag . '>', $project_tags_title, $project_tags_tag ); // WPCS: XSS ok.
								?>
								<div class="project-terms">
									<?php echo get_the_term_list( $post->ID, 'portfolio_tags', '', '<br />', '' ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php
						$project_url = get_post_meta( $post->ID, 'pyre_project_url', true );
						$project_url_text = get_post_meta( $post->ID, 'pyre_project_url_text', true );
						?>

						<?php if ( $project_url && $project_url_text ) : ?>
							<?php $link_target = ( in_array( fusion_get_option( 'portfolio_link_icon_target', 'link_icon_target', $post->ID ), array( '1', 1, 'yes' ), true ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
							<div class="project-info-box">
								<?php
								$project_project_url_title = esc_html__( 'Project URL:', 'fusion-core' );
								$project_project_url_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_project_url_label', '<' . $project_project_url_tag . '>' . $project_project_url_title . '</' . $project_project_url_tag . '>', $project_project_url_title, $project_project_url_tag );  // WPCS: XSS ok.
								?>
								<span><a href="<?php echo esc_url_raw( $project_url ); ?>"<?php echo $link_target; // WPCS: XSS ok. ?>><?php echo $project_url_text; // WPCS: XSS ok. ?></a></span>
							</div>
						<?php endif; ?>

						<?php
						$copy_url = get_post_meta( $post->ID, 'pyre_copy_url', true );
						$copy_url_text = get_post_meta( $post->ID, 'pyre_copy_url_text', true );
						?>

						<?php if ( $copy_url && $copy_url_text ) : ?>
							<?php $link_target = ( in_array( fusion_get_option( 'portfolio_link_icon_target', 'link_icon_target', $post->ID ), array( '1', 1, 'yes' ), true ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
							<div class="project-info-box">
								<?php
								$project_copyright_title = esc_html__( 'Copyright:', 'fusion-core' );
								$project_copyright_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_copyright_label', '<' . $project_copyright_tag . '>' . $project_copyright_title . '</' . $project_copyright_tag . '>', $project_copyright_title, $project_copyright_tag ); // WPCS: XSS ok.
								?>
								<span><a href="<?php echo esc_url_raw( $copy_url ); ?>"<?php echo $link_target; // WPCS: XSS ok. ?>><?php echo $copy_url_text; // WPCS: XSS ok. ?></a></span>
							</div>
						<?php endif; ?>

						<?php if ( Avada()->settings->get( 'portfolio_author' ) ) : ?>
							<div class="project-info-box<?php echo ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) && Avada()->settings->get( 'disable_rich_snippet_author' ) ) ? ' vcard' : ''; ?>">
								<?php
								$project_author_title = esc_html__( 'By:', 'fusion-core' );
								$project_author_tag = 'h4';
								echo apply_filters( 'fusion_portfolio_post_author_label', '<' . $project_author_tag . '>' . $project_author_title . '</' . $project_author_tag . '>', $project_author_title, $project_author_tag ); // WPCS: XSS ok.
								?>
								<span<?php echo ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) && Avada()->settings->get( 'disable_rich_snippet_author' ) ) ? ' class="fn"' : ''; ?>><?php the_author_posts_link(); ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="portfolio-sep"></div>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php avada_render_social_sharing( 'portfolio' ); ?>
				<?php echo avada_render_related_posts( 'avada_portfolio' ); // WPCS: XSS ok. Render Related Posts. ?>

				<?php if ( Avada()->settings->get( 'portfolio_comments' ) ) : ?>
					<?php wp_reset_postdata(); ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</article>
	<?php endif; ?>
</div>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
