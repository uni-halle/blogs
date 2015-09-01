<?php
/**
 * The Search form template.
 *
 * Changes the Markup for the search form
 *
 */
?>

<form role="search" method="get" action="<?php echo home_url( '/' ); ?>" id="searchform">
    <label class="visuallyhidden" for="search-field"><?php echo _x( 'Search for:', 'label' ) ?></label>
    <input type="search" id="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'muhlenbergcenter' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>">
    <input type="submit" class="visuallyhidden" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>">
</form>