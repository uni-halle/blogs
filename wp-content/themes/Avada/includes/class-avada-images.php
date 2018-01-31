<?php
/**
 * Handle images in Avada.
 * Includes responsive-images tweaks.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      3.8
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle images in Avada.
 * Includes responsive-images tweaks.
 */
class Avada_Images extends Fusion_Images {

	/**
	 * The grid image meta.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $grid_image_meta;

	/**
	 * An array of the accepted widths.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $grid_accepted_widths;

	/**
	 * An array of supported layouts.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $supported_grid_layouts;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		// The parent-class constructor.
		parent::__construct();

		self::$grid_image_meta        = array();
		self::$grid_accepted_widths   = array( '200', '400', '600', '800', '1200' );
		self::$supported_grid_layouts = array( 'masonry', 'grid', 'timeline', 'large', 'portfolio_full', 'related-posts' );

		$options = get_option( Avada::get_option_name() );
		if ( isset( $options['status_lightbox'] ) && $options['status_lightbox'] ) {
			add_filter( 'wp_get_attachment_link', array( $this, 'prepare_lightbox_links' ) );
		}

		add_filter( 'jpeg_quality', array( $this, 'set_jpeg_quality' ) );
		add_filter( 'wp_editor_set_quality', array( $this, 'set_jpeg_quality' ) );

		add_filter( 'fusion_library_content_break_point', array( $this, 'content_break_point' ) );
		add_filter( 'fusion_library_content_width', array( $this, 'content_width' ) );
		add_filter( 'fusion_library_main_image_breakpoint', array( $this, 'main_image_breakpoint' ) );
		add_filter( 'fusion_library_image_base_size_width', array( $this, 'base_size_width' ), 10, 4 );
		add_filter( 'fusion_library_grid_main_break_point', array( $this, 'grid_main_break_point' ) );
		add_filter( 'fusion_library_image_grid_initial_sizes', array( $this, 'image_grid_initial_sizes' ), 10, 3 );
	}

	/**
	 * Modify the image quality and set it to chosen Theme Options value.
	 *
	 * @since 3.9
	 * @return string The new image quality.
	 */
	public function set_jpeg_quality() {
		return Avada()->settings->get( 'pw_jpeg_quality' );
	}

	/**
	 * Returns the content break-point.
	 *
	 * @access public
	 * @since 5.1.0
	 * @return int
	 */
	public function content_break_point() {
		$side_header_width   = ( 'Top' === Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );
		return $side_header_width + intval( Avada()->settings->get( 'content_break_point' ) );
	}

	/**
	 * Returns the content-width.
	 *
	 * @access public
	 * @since 5.1.0
	 * @return int
	 */
	public function content_width() {
		return Avada()->layout->get_content_width();
	}

	/**
	 * Returns the main image breakpoint.
	 *
	 * @access public
	 * @since 5.1.0
	 * @return int
	 */
	public function main_image_breakpoint() {
		$main_break_point  = (int) Avada()->settings->get( 'grid_main_break_point' );
		$side_header_width = ( 'Top' === Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );
		return $main_break_point + $side_header_width;
	}

	/**
	 * Returns bas width of an image container.
	 *
	 * @since 5.1.0
	 * @access public
	 * @param int    $width        The image width in pixels.
	 * @param string $layout       The layout name.
	 * @param int    $columns      The number of columns used as a divider.
	 * @param int    $gutter_width The gutter width - in pixels.
	 * @return int
	 */
	public function base_size_width( $width, $layout, $columns = 1, $gutter_width = 30 ) {
		if ( false !== strpos( $layout, 'large' ) ) {
			return (int) Avada()->layout->get_content_width();
		}
		$columns = ( 1 > intval( $columns ) ) ? 1 : intval( $columns );
		if ( 'timeline' === $layout ) {
			return absint( Avada()->layout->get_content_width() * 0.8 / $columns );
		}
		$width = Avada()->layout->get_content_width();

		if ( isset( $gutter_width ) ) {
			$width -= intval( $gutter_width ) * ( $columns - 1 );
		}
		return absint( $width / $columns );
	}

	/**
	 * Returns the main image breakpoint.
	 *
	 * @access public
	 * @since 5.1.0
	 * @return int
	 */
	public function grid_main_break_point() {
		return (int) Avada()->settings->get( 'grid_main_break_point' );
	}

	/**
	 * Returns the initial $sizes.
	 *
	 * @access public
	 * @since 5.1.0
	 * @param string $sizes      The sizes formatted as a media query or empty.
	 * @param int    $breakpoint The breakpoint in pixels.
	 * @param int    $columns    The number of columns.
	 * @return string
	 */
	public function image_grid_initial_sizes( $sizes = '', $breakpoint = 800, $columns = 1 ) {
		// Make sure image sizes will be correct for 100% width pages.
		if ( Avada()->layout->is_current_wrapper_hundred_percent() ) {
			return '(min-width: ' . ( $breakpoint + 200 ) . 'px) ' . round( 100 / $columns ) . 'vw, ';
		}
		return $sizes;
	}

	/**
	 * Gets the logo data (url, width, height ) for the specified option name
	 *
	 * @since 4.0
	 * @param string $logo_option_name The name of the logo option.
	 * @return array The logo data.
	 */
	public function get_logo_data( $logo_option_name ) {

		$logo_data = array(
			'url'    => '',
			'width'  => '',
			'height' => '',
		);

		$logo_url = set_url_scheme( Avada()->settings->get( $logo_option_name, 'url' ) );

		if ( $logo_url ) {
			$logo_data['url'] = $logo_url;

			/*
			 * Get data from normal logo, if we are checking a retina logo.
			 * Except for the main retina logo, because it can be set witout default one because of BC.
			 */
			if ( false !== strpos( $logo_option_name, 'retina' ) && 'logo_retina' !== $logo_option_name ) {
				$logo_url = set_url_scheme( Avada()->settings->get( str_replace( '_retina', '', $logo_option_name ), 'url' ) );
			}

			$logo_attachment_data = $this->get_attachment_data_from_url( $logo_url );

			if ( $logo_attachment_data ) {
				// For the main retina logo, we have to set the sizes correctly, for all others they are correct.
				if ( 'logo_retina' === $logo_option_name ) {
					$logo_data['width']  = $logo_attachment_data['width'] / 2;
					$logo_data['height'] = $logo_attachment_data['height'] / 2;
				} else {
					$logo_data['width']  = $logo_attachment_data['width'];
					$logo_data['height'] = $logo_attachment_data['height'];
				}
			}
		}

		return $logo_data;
	}

	/**
	 * Get normal and retina logo images in srcset.
	 *
	 * @since 5.3
	 * @param string $normla_logo The name of the normal logo option.
	 * @param string $retina_logo The name of the retina logo option.
	 * @return array The logo data.
	 */
	public function get_logo_image_srcset( $normla_logo, $retina_logo ) {

		$logo_srcset_data = array(
			'url'       => '',
			'srcset'    => '',
			'style'     => '',
			'is_retina' => false,
			'width'     => '',
			'height'    => '',
		);

		$logo_url                     = set_url_scheme( Avada()->settings->get( $normla_logo, 'url' ) );
		$logo_srcset_data['srcset']   = $logo_url . ' 1x';

		// Get retina logo, if default one is not set.
		if ( '' === $logo_url ) {
			$logo_url                      = set_url_scheme( Avada()->settings->get( $retina_logo, 'url' ) );
			$logo_data                     = self::get_logo_data( $retina_logo );
			$logo_srcset_data['style']     = '';
			$logo_srcset_data['srcset']    = $logo_url . ' 1x';
			$logo_srcset_data['url']       = $logo_url;

			if ( '' !== $logo_data['width'] ) {
				$logo_srcset_data['style'] = ' style="max-height:' . $logo_data['height'] . 'px;height:auto;"';
			}
		} else {
			$logo_data = Avada()->images->get_logo_data( $normla_logo );
			$logo_srcset_data['style']     = '';
			$logo_srcset_data['url']       = $logo_url;
			$logo_srcset_data['is_retina'] = false;
		}

		$logo_srcset_data['width']  = $logo_data['width'];
		$logo_srcset_data['height'] = $logo_data['height'];

		if ( Avada()->settings->get( $retina_logo, 'url' ) && '' !== Avada()->settings->get( $retina_logo, 'url' ) && '' !== Avada()->settings->get( $retina_logo, 'width' ) && '' !== Avada()->settings->get( $retina_logo, 'height' ) ) {
			$retina_logo                   = set_url_scheme( Avada()->settings->get( $retina_logo, 'url' ) );
			$logo_srcset_data['srcset']   .= ', ' . $retina_logo . ' 2x';
			$logo_srcset_data['is_retina'] = $retina_logo;

			if ( '' !== $logo_data['width'] ) {
				$logo_srcset_data['style'] = ' style="max-height:' . $logo_data['height'] . 'px;height:auto;"';
			}
		}
		return $logo_srcset_data;
	}

	/**
	 * Action to output a placeholder image.
	 *
	 * @param  string $featured_image_size     Size of the featured image that should be emulated.
	 *
	 * @return void
	 */
	public function render_placeholder_image( $featured_image_size = 'full' ) {
		global $_wp_additional_image_sizes;

		if ( in_array( $featured_image_size, array( 'full', 'fixed' ) ) ) {
			$height = apply_filters( 'avada_set_placeholder_image_height', '150' );
			$width  = '1500px';
		} else {
			// @codingStandardsIgnoreStart
			@$height = $_wp_additional_image_sizes[ $featured_image_size ]['height'];
			@$width  = $_wp_additional_image_sizes[ $featured_image_size ]['width'] . 'px';
			// @codingStandardsIgnoreEnd
		}
		?>
		<div class="fusion-placeholder-image" data-origheight="<?php echo esc_attr( $height ); ?>" data-origwidth="<?php echo esc_attr( $width ); ?>" style="height:<?php echo esc_attr( $height ); ?>px;width:<?php echo esc_attr( $width ); ?>;"></div>
		<?php
	}

	/**
	 * Returns the image class according to aspect ratio.
	 *
	 * @param  array $attachment The attachment.
	 * @return string The image class.
	 */
	public function get_image_orientation_class( $attachment ) {

		$sixteen_to_nine_ratio = 1.77;

		if ( ! isset( $attachment[1] ) || ! isset( $attachment[2] ) || empty( $attachment[1] ) || empty( $attachment[2] ) ) {
			return 'fusion-image-grid';
		}

		// Landscape.
		if ( $attachment[1] / $attachment[2] > $sixteen_to_nine_ratio ) {
			return 'fusion-image-landscape';
		}

		// Portrait.
		if ( $attachment[2] / $attachment[1] > $sixteen_to_nine_ratio ) {
			return 'fusion-image-portrait';
		}
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
