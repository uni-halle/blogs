<?php
/**
 * The searchform
 *
 * @package Tempera
 */
?>

<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _e( 'Search for:', 'tempera' ); ?></span>
		<input type="search" class="s" placeholder="<?php echo esc_attr_e( 'Search', 'tempera' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="searchsubmit"><span class="screen-reader-text"><?php echo _e( 'Search', 'tempera' ); ?></span><i class="crycon-search"></i></button>
</form>
