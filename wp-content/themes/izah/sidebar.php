<?php
/**
 * The sidebar containing the main widget area
 */
 ?>

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
<aside id="sidebar" class="sidebar cell position-6 width-2">
	<div id="widget-area" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- .widget-area -->
</aside>
<?php endif; ?>


