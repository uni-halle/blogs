<?php
/**
 * A class which is used to add various options to all widgets.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * A class which is used to add various options to all widgets.
 */
class Avada_Widget_Style {

	/**
	 * Widget options we're going to add.
	 *
	 * @since 5.3.0
	 * @access private
	 * @var array
	 */
	private $widget_options;

	/**
	 * Construct the object.
	 *
	 * @since 5.3.0
	 * @access public
	 */
	public function __construct() {

		$this->init_options();

		add_filter( 'in_widget_form', array( $this, 'add_widget_styling_options' ), 10, 3 );
		add_filter( 'widget_update_callback', array( $this, 'save_widget_styling_options' ), 10, 4 );
		add_filter( 'dynamic_sidebar_params', array( $this, 'add_widget_styles' ) );
	}

	/**
	 * Init all options that we're going to add.
	 *
	 * @since 5.3.0
	 * @access private
	 * @return void
	 */
	private function init_options() {

		$this->widget_options = array(
			array(
				'key'          => 'fusion_padding_color',
				'title'        => __( 'Padding', 'Avada' ),
				'description'  => __( 'Controls the padding for this widget container. Enter value including any valid CSS unit, ex: 10px.', 'Avada' ),
				'css_property' => 'padding',
				'type'         => 'text',
			),
			array(
				'key'          => 'fusion_bg_color',
				'title'        => __( 'Background Color', 'Avada' ),
				'description'  => __( 'Controls the background color for this widget container.', 'Avada' ),
				'css_property' => 'background-color',
				'type'         => 'colorpickeralpha',
			),
			array(
				'key'          => 'fusion_bg_radius_size',
				'title'        => __( 'Background Radius', 'Avada' ),
				'description'  => __( 'Controls the background radius for this widget container.', 'Avada' ),
				'css_property' => 'border-radius',
				'type'         => 'text',
			),
			array(
				'key'          => 'fusion_border_size',
				'title'        => __( 'Border Size', 'Avada' ),
				'description'  => __( 'Controls the border size for this widget container.', 'Avada' ),
				'css_property' => 'border-width',
				'type'         => 'text',
			),
			array(
				'key'          => 'fusion_border_style',
				'title'        => __( 'Border Style', 'Avada' ),
				'description'  => __( 'Controls the border style for this widget container.', 'Avada' ),
				'css_property' => 'border-style',
				'type'         => 'select',
				'options'      => array(
					''       => __( 'None', 'Avada' ),
					'solid'  => __( 'Solid', 'Avada' ),
					'dotted' => __( 'Dotted', 'Avada' ),
					'dashed' => __( 'Dashed', 'Avada' ),
				),
			),
			array(
				'key'          => 'fusion_border_color',
				'title'        => __( 'Border Color', 'Avada' ),
				'description'  => __( 'Controls the border color for this widget container.', 'Avada' ),
				'css_property' => 'border-color',
				'type'         => 'colorpickeralpha',
			),
			array(
				'key'          => 'fusion_align',
				'title'        => __( 'Content Align', 'Avada' ),
				'description'  => __( 'Controls content alignment for this widget container. Inherit means it will inherit alignment from it\'s parent element.', 'Avada' ),
				'css_property' => 'text-align',
				'type'         => 'select',
				'options'      => array(
					''       => __( 'Inherit', 'Avada' ),
					'left'   => __( 'Left', 'Avada' ),
					'right'  => __( 'Right', 'Avada' ),
					'center' => __( 'Center', 'Avada' ),
				),
			),
			array(
				'key'          => 'fusion_align_mobile',
				'title'        => __( 'Mobile Content Align', 'Avada' ),
				'description'  => __( 'Controls mobile content alignment for this widget container. Inherit means it will inherit alignment from it\'s parent element.', 'Avada' ),
				'css_property' => 'text-align',
				'type'         => 'select',
				'options'      => array(
					''        => __( 'Inherit', 'Avada' ),
					'left'    => __( 'Left', 'Avada' ),
					'right'   => __( 'Right', 'Avada' ),
					'center'  => __( 'Center', 'Avada' ),
				),
			),
		);
	}

	/**
	 * Add widget options to form
	 *
	 * @since 5.3.0
	 * @access public
	 * @param object      $widget   WP_Widget object, passed by reference.
	 * @param null|string $return   Return null if new fields are added.
	 * @param array       $instance An array of the widget's settings.
	 */
	public function add_widget_styling_options( $widget, $return, $instance ) {
		$this->start_widget_options();
		?>
		<?php foreach ( $this->widget_options as $option ) : ?>
			<?php
			$field_css_classes = 'option-field fusion-builder-option-container';

			if ( 'colorpickeralpha' === $option['type'] ) {
				$field_css_classes .= ' pyre_field avada-color colorpickeralpha';
			}

			$value                 = isset( $instance[ $option['key'] ] ) ? $instance[ $option['key'] ] : '';
			$option['description'] = isset( $option['description'] ) ? $option['description'] : '';
			?>
			<li class="fusion-builder-option">
				<div class="option-details">
					<h3><?php echo esc_html( $option['title'] ); ?></h3>
					<p class="description"><?php echo esc_html( $option['description'] ); ?></p>
				</div>

				<div class="<?php echo esc_attr( $field_css_classes ); ?>">
				<?php if ( 'select' === $option['type'] ) : ?>
					<select id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
					>
					<?php foreach ( $option['options'] as $val => $title ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php selected( esc_attr( $val ), esc_attr( $value ) ); ?>><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
					</select>
				<?php elseif ( 'colorpickeralpha' === $option['type'] ) : ?>
					<input type="text" id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							class="fusion-builder-color-picker-hex color-picker"
							data-alpha="true"
							name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
						/>
				<?php else : ?>
					<input type="text" id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
						name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
					/>
				<?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		<?php
		$this->end_widget_options();
		return;
	}

	/**
	 * Open widget options container.
	 */
	private function start_widget_options() {
	?>
	<div class="fusion-menu-options-container">
		<a class="button button-primary button-large fusion-menu-option-trigger" href="#">
			<?php esc_html_e( 'Avada Widget Options', 'Avada' ); ?>
		</a>
		<div class="fusion_builder_modal_overlay" style="display:none"></div>
		<div class="fusion-options-holder fusion-builder-modal-settings-container" style="display:none">
			<div class="fusion-builder-modal-container fusion_builder_module_settings">
				<div class="fusion-builder-modal-top-container">
					<h2>
						<?php esc_attr_e( 'Avada Widget Options', 'Avada' ); ?>
						<div class="fusion-modal-description">
							<?php esc_html_e( 'These options apply to the widget container, not the actual widget.', 'Avada' ); ?>
						</div>
					</h2>
					<div class="fusion-builder-modal-close fusiona-plus2"></div>
				</div>
				<div class="fusion-builder-modal-bottom-container">
					<a href="#" class="fusion-builder-modal-save" ><span><?php esc_attr_e( 'Save', 'Avada' ); ?></span></a>
					<a href="#" class="fusion-builder-modal-close" ><span><?php esc_attr_e( 'Cancel', 'Avada' ); ?></span></a>
				</div>
				<div class="fusion-builder-main-settings fusion-builder-main-settings-full">
					<ul class="fusion-builder-module-settings">
	<?php
	}

	/**
	 * Close widget options container.
	 */
	public function end_widget_options() {
		?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
	}

	/**
	 * Save widget options.
	 *
	 * @since 5.3.0
	 * @access public
	 * @param array     $instance     An array of current widget's settings.
	 * @param array     $new_instance An array of new widget's settings.
	 * @param array     $old_instance An array of old widget's settings.
	 * @param WP_Widget $widget       The current widget instance.
	 * @return mixed
	 */
	public function save_widget_styling_options( $instance, $new_instance, $old_instance, $widget ) {

		foreach ( $this->widget_options as $option ) {
			$instance[ $option['key'] ] = ! empty( $new_instance[ $option['key'] ] ) ? sanitize_text_field( $new_instance[ $option['key'] ] ) : '';
		}

		return $instance;
	}

	/**
	 * Prints widget styles based on options.
	 * Desktop styles are printed using style element attribute.
	 * Mobile styles are printed inline.
	 *
	 * @since 5.3.0
	 * @access public
	 * @param array $params Widget params.
	 * @return mixed
	 */
	public function add_widget_styles( $params ) {

		global $wp_registered_widgets;

		if ( ! isset( $params[0] ) ) {
			return $params;
		}

		$sidebar_id = $params[0]['id']; // Get the id for the current sidebar we're processing.
		$widget_id  = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[ $widget_id ];
		$widget_num = $widget_obj['params'][0]['number'];
		$widget_opt = $this->get_widget_opt( $widget_obj );

		$style        = '';
		$style_mobile = '';

		// If calendar and no alignment set, set to default.
		if ( isset( $widget_opt[ $widget_num ] ) && ! isset( $widget_opt[ $widget_num ]['fusion_align'] ) && false !== strpos( $widget_id, 'calendar' ) ) {
			$widget_opt[ $widget_num ]['fusion_align'] = '';
		}

		foreach ( $this->widget_options as $option ) {
			if ( isset( $widget_opt[ $widget_num ][ $option['key'] ] ) ) {

				if ( 'fusion_align' === $option['key'] && false !== strpos( $widget_id, 'calendar' ) ) {
					$alignment = ( '' === $widget_opt[ $widget_num ][ $option['key'] ] ) ? 'default' : $widget_opt[ $widget_num ][ $option['key'] ];
					$params[0]['before_widget'] = str_replace( 'class="', 'class="fusion-widget-align-' . $alignment . ' ', $params[0]['before_widget'] );
				}

				if ( 'fusion_align' === $option['key'] || 'fusion_align_mobile' === $option['key'] ) {

					if ( 'fusion_align_mobile' === $option['key'] && '' === $widget_opt[ $widget_num ][ $option['key'] ] && '' !== $widget_opt[ $widget_num ]['fusion_align'] ) {

						if ( false !== strpos( $sidebar_id, 'avada-footer-widget-' ) && Avada()->settings->get( 'footer_widgets_center_content' ) ) {
							$widget_opt[ $widget_num ][ $option['key'] ] = 'center';
						} else {
							$widget_opt[ $widget_num ][ $option['key'] ] = 'initial';
						}
					}

					if ( '' !==  $widget_opt[ $widget_num ][ $option['key'] ] ) {
						$alignment = ( '' === $widget_opt[ $widget_num ][ $option['key'] ] ) ? 'default' : $widget_opt[ $widget_num ][ $option['key'] ];
						$css_class = ( 'fusion_align' === $option['key'] ? 'fusion-widget-align-' : 'fusion-widget-mobile-align-' ) . $alignment;

						$params[0]['before_widget'] = str_replace( 'class="', 'class="' . esc_attr( $css_class ) . ' ', $params[0]['before_widget'] );
					}
				}

				if ( '' !== $widget_opt[ $widget_num ][ $option['key'] ] ) {
					if ( false === strpos( $option['key'], 'mobile' ) ) {
						$style .= $option['css_property'] . ': ' . $widget_opt[ $widget_num ][ $option['key'] ] . ';';

						if ( 'border-radius' === $option['css_property'] ) {
							$style .= 'overflow:hidden;';
						}
					} else {
						$style_mobile .= '#' . $widget_id . '{' . $option['css_property'] . ':' . $widget_opt[ $widget_num ][ $option['key'] ] . ' !important;}';
					}
				}
			}
		}

		// Set border color to transparent and border size to 0px it those field were left empty, but border style isn't.
		if ( isset( $widget_opt[ $widget_num ]['fusion_border_style'] ) && '' !== $widget_opt[ $widget_num ]['fusion_border_style'] ) {
			if ( ! isset( $widget_opt[ $widget_num ]['fusion_border_color'] ) || '' === $widget_opt[ $widget_num ]['fusion_border_color'] ) {
				$style .= 'border-color:transparent;';
			}

			if ( ! isset( $widget_opt[ $widget_num ]['fusion_border_size'] ) || '' === $widget_opt[ $widget_num ]['fusion_border_size'] ) {
				$style .= 'border-width:0px;';
			}
		}

		if ( ! empty( $style ) ) {
			$params[0]['before_widget'] = str_replace( '>', ' style="' . esc_attr( $style ) . '">', $params[0]['before_widget'] );
		}

		if ( ! empty( $style_mobile ) ) {
			$params[0]['before_widget'] = '<style type="text/css" scoped="scoped">@media (max-width: ' . Avada()->settings->get( 'content_break_point' ) . 'px){' . $style_mobile . '}</style>' . $params[0]['before_widget'];
		}

		return $params;
	}

	/**
	 * Get widget options.
	 *
	 * @since 5.3.0
	 * @access private
	 * @param object $widget WP_Widget object.
	 * @return mixed|void
	 */
	private function get_widget_opt( $widget ) {
		$widget_opt = get_option( $widget['callback'][0]->option_name );

		return $widget_opt;
	}

}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
