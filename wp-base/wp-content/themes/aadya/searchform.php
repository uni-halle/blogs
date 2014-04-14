<?php
/**
 * Searchform
 *
 * Custom template for search form
 *
 * @package Aadya
 * @since Aadya 1.0.0
 */
?>

<form class="form-inline" role="form" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">	
	<div class="input-group">
	<input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'aadya' ); ?>">
	<span class="input-group-btn">
		<button type="submit" class="btn btn-primary"><?php esc_attr_e( 'Search', 'aadya' ); ?></button>
	</span>
	</div>
</form>

