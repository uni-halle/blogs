<?php
/**
 * The template for displaying search forms in Catch Box
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */
$options = catchbox_get_options();
$search_text = esc_attr( $options['search_display_text'] );
?>
	<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
			<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'catch-box' ); ?></span>
			<input type="search" class="search-field" placeholder="<?php echo esc_attr( $search_text ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'catch-box' ); ?>" />
		</label>
		<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'catch-box' ); ?></span></button>
	</form>
