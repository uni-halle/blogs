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
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<!-- Start Content -->
<div class="SC" role="main">

<!-- Side - List Post-->
<div class="SL">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" class="post">
                <div class="post-head">
					<?php if ( is_front_page() ) { ?>
						<h2><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1><?php the_title(); ?></h1>
					<?php } ?>
                  </div>        
					<div class="post-con">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .post-con -->
				</div><!-- #post-## -->
<?php //comments_template( '', true ); ?>
<?php endwhile; ?>
</div>
<!-- End - Side Left -->

<!-- Start - Side Right -->
<div class="SR">
 <?php get_sidebar(); ?>
</div>
<!-- End - Side Right -->

</div>
<!-- End - SC -->
<?php get_footer(); ?>
