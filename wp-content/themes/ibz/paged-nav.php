<?php
/**
 * The template for displaying
 * numbered navigation for pages 
 * e.g. "Previous 1 2 3 Next"
 */
?>

<nav class="pages">
    <?php
        global $wp_query;
        
        $big = 999999999; // need an unlikely integer
        
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
			'end_size' => 1,
			'mid_size' => 2,
            'total' => $wp_query->max_num_pages,
        ) );
    ?>
</nav>