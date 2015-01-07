<?php
/**
 * The template for search field.
 *
 *
 * @package WordPress
 * @subpackage Choco
 */
?>
<form action="<?php echo home_url( '/' ); ?>" id="searchform" method="get">
	<label for="s" class="screen-reader-text"><?php _e( 'Search', 'choco' ); ?></label>
	<div class="field-place">
		<input type="text" class="field" id="s" name="s" value=""/>
		<input type="submit" class="button" value="Search" id="searchsubmit"/>
	</div>
</form>