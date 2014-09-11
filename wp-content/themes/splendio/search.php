<?php
/**
 * The template for displaying Search Results pages.
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

<?php if ( have_posts() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
				<div id="post-0" class="post no-results not-found">
                 <div class="post-head">
					<h2 class="page-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2><br />
                  </div>
					<div class="post-con">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
					</div><!-- .post-con -->
				</div><!-- #post-0 -->
<?php endif; ?>
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
