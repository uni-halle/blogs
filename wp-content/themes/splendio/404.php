<?php
/**
 * The template for displaying 404 pages (Not Found).
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

			<div id="post-0" class="post error404 not-found">
             <div class="post-head">
				<h1><?php _e( 'Not Found', 'twentyten' ); ?></h1>
              </div> 
				<div class="post-con">
					<p><?php _e( 'Apologies, but the page you requested could not be found.<br /> Perhaps searching will help.', 'twentyten' ); ?></p>
				</div><!-- .post-con -->
			</div><!-- #post-0 -->

</div>
<!-- End - Side Left -->

<!-- Start - Side Right -->
<div class="SR">
 <?php get_sidebar(); ?>
</div>
<!-- End - Side Right -->

</div>
<!-- End - SC -->

<script type="text/javascript">
// focus on search field after it has loaded
document.getElementById('s') && document.getElementById('s').focus();
</script>

<?php get_footer(); ?>