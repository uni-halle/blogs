<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Choco
 */

get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<div class="post">
						<?php if ( is_front_page() ) { ?>
							<h2 class="post-title"><?php the_title(); ?></h2>
						<?php } else { ?>
							<h1 class="post-title"><?php the_title(); ?></h1>
						<?php } ?>

						<div class="entry">
							<?php if( has_post_thumbnail() ){ ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
								</a>
							<?php } ?>
							<?php the_content( '<p class="serif">Read the rest of this page &raquo;</p>' ); ?>
							<div class="cl">&nbsp;</div>
							<?php wp_link_pages( array( 'before' => '<div class="page-navigation"><p><strong>'. __( 'Pages:', 'choco' ) .' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							<?php edit_post_link( __( 'Edit', 'choco' ), '<span class="edit-link">', '</span>' ); ?>
						</div>
					</div>
					<?php comments_template( '', true ); ?>
<?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>