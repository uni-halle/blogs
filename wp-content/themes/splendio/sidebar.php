<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 * @package WordPress
 */
?>

<div class="widget-area" role="complementary">
<!-- Start Recent Comments -->
<?php if (function_exists('mdv_recent_comments')) { ?>
<div class="widget-special">
<h3>Recent Comments</h3>
 <ul>
 <?php mdv_recent_comments(); ?>
 </ul>
</div>
<?php } ?>
<!-- End Recent Comments -->

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
if ( ! dynamic_sidebar( 'sidebar-widget-area' ) ) : ?>
 <div class="widget widget-archives">
  <h3 class="widget-title"><?php _e( 'Tagcloud', 'twentyten' ); ?></h3>
   <div>
    <?php wp_tag_cloud (); ?>
  </div>
 </div>
<?php endif; // end primary widget area ?>
</div>
<!-- #sidebar .widget-area -->

