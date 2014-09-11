<?php
/**
 * The template for displaying 
 * search form inside blog sidebar
 */
?>

<?php global $s; ?>

<form class="search" method="get" id="searchform" action="<?php echo home_url(); ?>">

    	<input type="text" value="<?php echo esc_html($s, 1); ?>" name="s" id="s" size="15" placeholder="<?php _e('Search...', 'maja'); ?>" />
        <div class="search_button">
        	<input type="submit" id="search_submit" value="Search" />
        </div>
        
</form>