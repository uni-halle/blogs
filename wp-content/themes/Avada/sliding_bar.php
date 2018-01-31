<?php
/**
 * The template for sliding bars.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// Set some variables.
$sliding_bar_position = Avada()->settings->get( 'slidingbar_position' );
$sliding_bar_columns = Avada()->settings->get( 'slidingbar_widgets_columns' );
$sliding_bar_content_alignment = Avada()->settings->get( 'slidingbar_content_align' );
$side_header_width = ( 'Top' === Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );
$mobile_breakpoint = $side_header_width + intval( Avada()->settings->get( 'content_break_point' ) );
$toggle_style = Avada()->settings->get( 'slidingbar_toggle_style' );

// Setup the main wrapper classes.
$sliding_bar_classes = 'slidingbar-area fusion-sliding-bar-area fusion-widget-area fusion-sliding-bar-position-' . $sliding_bar_position . ' fusion-sliding-bar-text-align-' . $sliding_bar_content_alignment . ' fusion-sliding-bar-toggle-' . $toggle_style;
$sliding_bar_classes .= ( Avada()->settings->get( 'slidingbar_sticky' ) ) ? ' fusion-sliding-bar-sticky' : '';
$sliding_bar_classes .= ( Avada()->settings->get( 'slidingbar_border' ) ) ? ' fusion-sliding-bar-border' : '';
$sliding_bar_classes .= ( Avada()->settings->get( 'slidingbar_open_on_load' ) ) ? ' open-on-load' : '';

// Set the classes depending on the position.
if ( 'top' === $sliding_bar_position || 'bottom' === $sliding_bar_position ) {
	$sliding_bar_row_or_inner_wrapper_class = 'fusion-row';
	$sliding_bar_columns_wrapper_class = 'fusion-columns row fusion-columns-' . $sliding_bar_columns . 'columns columns-' . $sliding_bar_columns;
	$column_width = ( '5' === $sliding_bar_columns ) ? 2 : 12 / $sliding_bar_columns;
} else {
	$sliding_bar_row_or_inner_wrapper_class = 'fusion-sliding-bar-content-wrapper';
	$sliding_bar_columns_wrapper_class = 'fusion-sliding-bar-content';
}
?>
<div id="slidingbar-area" class="<?php echo esc_attr( $sliding_bar_classes ); ?>" data-breakpoint="<?php echo esc_attr( $mobile_breakpoint ); ?>" data-toggle="<?php echo esc_attr( $toggle_style ); ?>">
	<?php if ( 'menu' !== Avada()->settings->get( 'slidingbar_toggle_style' ) ) : ?>
		<div class="fusion-sb-toggle-wrapper">
			<a class="fusion-sb-toggle" href="#"><span class="screen-reader-text"><?php esc_html_e( 'Toggle Sliding Bar Area', 'Avada' ); ?></span></a>
		</div>
	<?php endif; ?>

	<div id="slidingbar" class="fusion-sliding-bar">
		<div class="<?php echo esc_attr( $sliding_bar_row_or_inner_wrapper_class ); ?>">
			<?php if ( 'menu' === Avada()->settings->get( 'slidingbar_toggle_style' ) ) : ?>
				<div class="fusion-sb-toggle-wrapper">
					<a class="fusion-sb-close" href="#"><span class="screen-reader-text"><?php esc_html_e( 'Close Sliding Bar Area', 'Avada' ); ?></span></a>
				</div>
			<?php endif; ?>			
			<div class="<?php echo esc_attr( $sliding_bar_columns_wrapper_class ); ?>">

				<?php // Render as many widget columns as have been chosen in Theme Options. ?>
				<?php for ( $i = 1; $i < 7; $i++ ) : ?>
					<?php if ( $i <= $sliding_bar_columns ) : ?>
						<?php
						$sliding_bar_column_class = 'fusion-column';
						if ( 'top' === $sliding_bar_position || 'bottom' === $sliding_bar_position ) {
							$last_column = ( $sliding_bar_columns === $i ) ? ' fusion-column-last' : '';
							$sliding_bar_column_class .= $last_column . ' col-lg-' . $column_width . ' col-md-' . $column_width . ' col-sm-' . $column_width;
						}
						?>
						<div class="<?php echo esc_attr( $sliding_bar_column_class ); ?>">
						<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'avada-slidingbar-widget-' . $i ) ) : ?>
							<?php // All is good, dynamic_sidebar() already called the rendering. ?>
						<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
				<div class="fusion-clearfix"></div>
			</div>
		</div>
	</div>
</div>
<?php
wp_reset_postdata();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
