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
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) :
				//Helper Text
				if ( current_user_can( 'edit_theme_options' ) ) { ?>
					<aside id="widget-default-text" class="widget widget_text">
			           	<h3 class="widget-title"><?php _e( 'Primary Sidebar Widget Area', 'catch-box' ); ?></h3>
	           			<div class="textwidget">
	                   		<p><?php _e( 'This is the Primary Sidebar Widget Area if you are using a two column site layout option.', 'catch-box' ); ?></p>
	                   		<p><?php printf( __( 'By default it will load Search and Archives widgets as shown below. You can add widget to this area by visiting your <a href="%s">Widgets Panel</a> which will replace default widgets.', 'catch-box' ), admin_url( 'widgets.php' ) ); ?></p>
	                 	</div>
		       		</aside><!-- #widget-default-text -->
				<?php
				} ?>
				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'catch-box' ); ?></h3>
                    <div class="widget-content">
                        <ul>
                            <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </div>
				</aside>

				<aside id="default-search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
<?php endif;

/** 
 * catchbox_below_secondary hook
 */
do_action( 'catchbox_below_secondary' ); ?>