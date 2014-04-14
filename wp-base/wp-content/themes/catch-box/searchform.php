<?php
/**
 * The template for displaying search forms in Catch Box
 *
 * @package Catch Themes
 * @subpackage Catch_Box
 * @since Catch Box 1.0
 */
$options = catchbox_get_theme_options();
if ( empty( $options['search_display_text'] ) || $options['search_display_text'] == 'Search' ) { 
	$search_text =  __( 'Search', 'catchbox' );
}
else {
	$search_text = esc_attr( $options['search_display_text'] );
}
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label for="s" class="assistive-text"><?php echo $search_text; ?></label>
        <input type="text" class="field" name="s" id="s" placeholder="<?php echo $search_text; ?>" />
        <input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php echo $search_text; ?>" />
	</form>
