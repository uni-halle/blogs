<?php get_header(); ?>

<?php
$args = array(
	'post_type' => 'page',
	'numberposts' => 1000,
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'posts_per_page' => 1000,
	);
										
	$loop = new WP_Query( $args );
	?>
	
	<?php if ($loop->have_posts()) : while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php if (has_post_thumbnail( $post->ID ) ) {
						$thumbImage = get_the_post_thumbnail($post->ID, $cg_attachment);
						} ?>
			
			<figure><?php echo $thumbImage; ?></figure>

			<h1><?php the_title(); ?><?php edit_post_link( __( '✎', 'property_showcase' ), '<span class="edit-link"> ', '</span>' ); ?></h1>

			<div class="entry">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>

<?php get_footer(); ?>
