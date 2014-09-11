<?php
/**
 * The template for displaying search forms in Esquire
 *
 * @package Esquire
 * @since Esquire 1.0
 */
?>
	<form method="get" id="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field" name="s" id="searchfield" placeholder="<?php esc_attr_e( 'Search', 'esquire' ); ?>" />
		<input type="image" src="<?php echo get_template_directory_uri(); ?>/img/icon-search.png" class="formbutton" id="searchgo" />
	</form>
