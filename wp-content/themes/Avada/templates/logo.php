<?php
/**
 * Logo template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$logo_opening_markup = '<div class="';
$logo_closing_markup = '</div>';
if ( 'v7' === Avada()->settings->get( 'header_layout' ) && ! Avada()->settings->get( 'logo_background' ) ) {
	$logo_opening_markup = '<li class="fusion-middle-logo-menu-logo ';
	$logo_closing_markup = '</li>';
} elseif ( 'v7' === Avada()->settings->get( 'header_layout' ) && Avada()->settings->get( 'logo_background' ) && 'Top' === Avada()->settings->get( 'header_position' ) ) {
	$logo_opening_markup = '<li class="fusion-logo-background fusion-middle-logo-menu-logo"><div class="';
	$logo_closing_markup = '</div></li>';
} elseif ( Avada()->settings->get( 'logo_background' ) && 'v4' !== Avada()->settings->get( 'header_layout' ) && 'v5' !== Avada()->settings->get( 'header_layout' ) && 'Top' === Avada()->settings->get( 'header_position' ) ) {
	$logo_opening_markup = '<div class="fusion-logo-background"><div class="';
	$logo_closing_markup = '</div></div>';
}
if ( '' !== Avada()->settings->get( 'logo', 'url' ) || '' !== Avada()->settings->get( 'logo_retina', 'url' ) ) {
	// @codingStandardsIgnoreLine
	echo $logo_opening_markup; ?>fusion-logo" data-margin-top="<?php echo esc_attr( Avada()->settings->get( 'logo_margin', 'top' ) ); ?>" data-margin-bottom="<?php echo esc_attr( Avada()->settings->get( 'logo_margin', 'bottom' ) ); ?>" data-margin-left="<?php echo esc_attr( Avada()->settings->get( 'logo_margin', 'left' ) ); ?>" data-margin-right="<?php echo esc_attr( Avada()->settings->get( 'logo_margin', 'right' ) ); ?>">
	<?php
} else {
	// @codingStandardsIgnoreLine
	echo $logo_opening_markup; ?>fusion-logo" data-margin-top="0px" data-margin-bottom="0px" data-margin-left="0px" data-margin-right="0px">
	<?php
}
	/**
	 * The avada_logo_prepend hook.
	 */
	do_action( 'avada_logo_prepend' );
	?>
	<?php if ( ( Avada()->settings->get( 'logo', 'url' ) && '' !== Avada()->settings->get( 'logo', 'url' ) ) || ( Avada()->settings->get( 'logo_retina', 'url' ) && '' !== Avada()->settings->get( 'logo_retina', 'url' ) ) ) : ?>
		<a class="fusion-logo-link" href="<?php echo esc_url_raw( home_url( '/' ) ); ?>">
			<!-- standard logo -->
			<?php
			$standard_logo = Avada()->images->get_logo_image_srcset( 'logo', 'logo_retina' )
			?>
			<img src="<?php echo esc_url_raw( $standard_logo['url'] ); ?>" srcset="<?php echo esc_attr( $standard_logo['srcset'] ); ?>" width="<?php echo esc_attr( $standard_logo['width'] ); ?>" height="<?php echo esc_attr( $standard_logo['height'] ); ?>"<?php echo $standard_logo['style']; // WPCS: XSS ok. ?> alt="<?php bloginfo( 'name' ); ?> <?php esc_attr_e( 'Logo', 'Avada' ); ?>" retina_logo_url="<?php echo esc_url_raw( $standard_logo['is_retina'] ); ?>" class="fusion-standard-logo" />

			<!-- mobile logo -->
			<?php
			if ( Avada()->settings->get( 'mobile_logo', 'url' ) && '' !== Avada()->settings->get( 'mobile_logo', 'url' ) ) {
				$mobile_logo = Avada()->images->get_logo_image_srcset( 'mobile_logo', 'mobile_logo_retina' );
			?>
				<img src="<?php echo esc_url_raw( $mobile_logo['url'] ); ?>" srcset="<?php echo esc_attr( $mobile_logo['srcset'] ); ?>" width="<?php echo esc_attr( $mobile_logo['width'] ); ?>" height="<?php echo esc_attr( $mobile_logo['height'] ); ?>"<?php echo $mobile_logo['style']; // WPCS: XSS ok. ?> alt="<?php bloginfo( 'name' ); ?> <?php esc_attr_e( 'Logo', 'Avada' ); ?>" retina_logo_url="<?php echo esc_url_raw( $mobile_logo['is_retina'] ); ?>" class="fusion-mobile-logo" />
			<?php } ?>
			<!-- sticky header logo -->
			<?php
			if ( Avada()->settings->get( 'sticky_header_logo', 'url' ) && '' !== Avada()->settings->get( 'sticky_header_logo', 'url' ) && ( in_array( Avada()->settings->get( 'header_layout' ), array( 'v1', 'v2', 'v3', 'v6', 'v7' ) ) || ( ( in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) && 'menu_and_logo' === Avada()->settings->get( 'header_sticky_type2_layout' ) ) ) ) ) {
				$sticky_logo = Avada()->images->get_logo_image_srcset( 'sticky_header_logo', 'sticky_header_logo_retina' );
			?>
				<img src="<?php echo esc_url_raw( $sticky_logo['url'] ); ?>" srcset="<?php echo esc_attr( $sticky_logo['srcset'] ); ?>" width="<?php echo esc_attr( $sticky_logo['width'] ); ?>" height="<?php echo esc_attr( $sticky_logo['height'] ); ?>"<?php echo $sticky_logo['style']; // WPCS: XSS ok. ?> alt="<?php bloginfo( 'name' ); ?> <?php esc_attr_e( 'Logo', 'Avada' ); ?>" retina_logo_url="<?php echo esc_url_raw( $sticky_logo['is_retina'] ); ?>" class="fusion-sticky-logo" />
			<?php } ?>
		</a>
	<?php endif; ?>
	<?php
	/**
	 * The avada_logo_append hook.
	 *
	 * @hooked avada_header_content_3 - 10.
	 */
	do_action( 'avada_logo_append' );

	?>
<?php
echo $logo_closing_markup; // WPCS: XSS ok.

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
