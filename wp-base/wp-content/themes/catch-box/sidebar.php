<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package Catch Themes
 * @subpackage Catch_Box
 * @since Catch Box 1.0
 */
?>

<?php 
/** 
 * catchbox_above_secondary hook
 */
do_action( 'catchbox_above_secondary' );

$options = catchbox_get_theme_options();
$layout = $options['theme_layout'];
	
if ( $layout == 'content-onecolumn' || $layout == 'no-sidebar' || is_page_template( 'page-disable-sidebar.php' ) || is_page_template( 'page-fullwidth.php' ) || is_page_template( 'page-onecolumn.php' ) ) : 
	return false;
else :
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'catchbox' ); ?></h3>
                    <div class="widget-content">
                        <ul>
                            <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </div>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'catchbox' ); ?></h3>
                    <div class="widget-content">
                        <ul>
                            <?php wp_register(); ?>
                            <li><?php wp_loginout(); ?></li>
                            <?php wp_meta(); ?>
                        </ul>
                  	</div>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
<?php endif;

/** 
 * catchbox_below_secondary hook
 */
do_action( 'catchbox_below_secondary' ); ?>