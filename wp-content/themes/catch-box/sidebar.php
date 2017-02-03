<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */
?>

<?php
/**
 * catchbox_above_secondary hook
 */
do_action( 'catchbox_above_secondary' );

$layout = catchbox_get_theme_layout();

if ( 'no-sidebar-one-column' == $layout || 'no-sidebar' == $layout || is_page_template( 'page-disable-sidebar.php' ) || is_page_template( 'page-fullwidth.php' ) || is_page_template( 'page-onecolumn.php' ) ) :
	return false;
else :
?>
		<aside id="secondary" class="sidebar widget-area" role="complementary">
			<h2 class="screen-reader-text"><?php _e( 'Primary Sidebar Widget Area', 'catch-box' ); ?></h2>
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) :
				//Helper Text
				if ( current_user_can( 'edit_theme_options' ) ) { ?>
					<section id="widget-default-text" class="widget widget_text">
			           	<h2 class="widget-title"><?php _e( 'Primary Sidebar Widget Area', 'catch-box' ); ?></h2>
	           			<div class="textwidget">
	                   		<p><?php _e( 'This is the Primary Sidebar Widget Area if you are using a two column site layout option.', 'catch-box' ); ?></p>
	                   		<p><?php printf( __( 'By default it will load Search and Archives widgets as shown below. You can add widget to this area by visiting your <a href="%s">Widgets Panel</a> which will replace default widgets.', 'catch-box' ), esc_url( admin_url( 'widgets.php' ) ) ); ?></p>
	                 	</div>
		       		</section><!-- #widget-default-text -->
				<?php
				} ?>
				<section id="archives" class="widget">
					<h2 class="widget-title"><?php _e( 'Archives', 'catch-box' ); ?></h2>
                    <div class="widget-content">
                        <ul>
                            <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </div>
				</section>

				<section id="default-search" class="widget widget_search">
					<?php get_search_form(); ?>
				</section>

			<?php endif; // end sidebar widget area ?>
		</aside><!-- #secondary .widget-area -->
<?php endif;

/**
 * catchbox_below_secondary hook
 */
do_action( 'catchbox_below_secondary' );