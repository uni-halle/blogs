<?php
/**
 * The template for displaying Category Archive pages.
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
 <h1 class="page-title"><?php printf( __( 'Category: %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
  <?php
    $category_description = category_description();
	if ( ! empty( $category_description ) )
	echo '<div class="archive-meta">' . $category_description . '</div>';
	/* Run the loop for the category page to output the posts.
	* If you want to overload this in a child theme then include a file
	* called loop-category.php and that will be used instead.
	*/
	get_template_part( 'loop', 'category' ); ?>
    <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>  
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
