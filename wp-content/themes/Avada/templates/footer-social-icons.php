<?php
/**
 * Footer social icons template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3.0
 */

?>
<div class="fusion-social-links-footer">
	<?php
	global $social_icons;

	$footer_social_icon_options = array(
		'position'          => 'footer',
		'icon_colors'       => Avada()->settings->get( 'footer_social_links_icon_color' ),
		'box_colors'        => Avada()->settings->get( 'footer_social_links_box_color' ),
		'icon_boxed'        => Avada()->settings->get( 'footer_social_links_boxed' ),
		'icon_boxed_radius' => Fusion_Sanitize::size( Avada()->settings->get( 'footer_social_links_boxed_radius' ) ),
		'tooltip_placement' => Avada()->settings->get( 'footer_social_links_tooltip_placement' ),
		'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
	);

	echo $social_icons->render_social_icons( $footer_social_icon_options ); // WPCS: XSS ok.
	?>
</div>
