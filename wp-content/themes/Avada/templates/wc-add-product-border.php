<?php
/**
 * Adds product border.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1.0
 */

$separator_styles_array = explode( ' ', Avada()->settings->get( 'title_style_type' ) );
$separator_styles = '';

foreach ( $separator_styles_array as $separator_style ) {
	$separator_styles .= ' sep-' . $separator_style;
}
?>
<div class="product-border fusion-separator<?php echo esc_attr( $separator_styles ); ?>"></div>
