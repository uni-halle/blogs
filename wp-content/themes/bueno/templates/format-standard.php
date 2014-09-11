<?php
/**
 * The template part for displaying standart post format.
 *
 * @package bueno
 */
?>	
	<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>

		<?php get_template_part('templates/post-thumb'); ?>

		<header class="post-header">

			<?php if(!is_singular()) : ?>

				<?php if ( has_post_thumbnail() && ( 'noimg' != of_get_option('post_image_size') ) ) get_template_part('templates/post-meta'); ?>
				<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<?php if ( !has_post_thumbnail() || ( 'noimg' == of_get_option('post_image_size') ) ) get_template_part('templates/post-meta'); ?>
				
			<?php else :?>
				
				<h1 class="post-title"><?php the_title(); ?></h1>
				<?php get_template_part('templates/post-meta'); ?>

			<?php endif; ?>

		</header>
		
		<?php if(!is_singular()) : ?>
		
		<!-- Post Content -->
		<div class="post_content">
			<?php if ( 'false' != of_get_option('post_excerpt')) { ?>
				<div class="excerpt">
				<?php 
					$excerpt = get_the_excerpt();
					if (has_excerpt()) {
						the_excerpt();
					} else {
						echo apply_filters( 'the_excerpt', bueno_string_limit_words($excerpt,20) );
					}
				?>
				</div>
			<?php } ?>
			<?php if ( 'false' != of_get_option('post_button')) { ?>
				<a href="<?php the_permalink() ?>" class="btn btn-small read-more-btn"><?php _e('Read more', 'bueno'); ?></a>
			<?php } ?>
		</div>
		
		<?php else :?>
		
		<!-- Post Content -->
		<div class="post_content">
		
			<?php the_content(''); ?>
			<?php 
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'bueno' ), 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); 
			?>
		</div>
		<!-- //Post Content -->
		<?php if( has_tag() ) { ?>
		<footer class="post-footer">
			<i class="icon-tags"></i> <?php the_tags('Tags: ', ' ', ''); ?>
		</footer>
		<?php } ?>
		<?php endif; ?>

	</article>

	<?php if ( is_single() && get_the_author_meta( 'description' ) ) {
		get_template_part( 'templates/post-author-bio' );
	} ?>