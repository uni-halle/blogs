<?php
/**
 * The template for displaying portfolio items (4 columns).
 * It can be loaded by placing [portfolio-4col] shortcode 
 * inside your page.
 */
?>

<?php 
	$maja_option = maja_get_global_options();
	global $post;
?>

<?php if ($maja_option['maja_quicksand'] =='1') { // display if category filtering is enabled ?> 

    <ul class="filter filter_portfolio">
        <li class="active"><a href="javascript:void(0)" class="all">All</a></li>
        
        <?php
			global $terms;
			global $args;		
            $terms = get_terms('filter', $args); // Get the taxonomy
            $count = count($terms);  // set a count to the amount of categories in our taxonomy
            $i=0; // set a count value to 0
            
            if ($count > 0) { // test if the count has any categories
                foreach ($terms as $term) { // break each of the categories into individual elements
                    
                    $i++; // increase the count by 1
                    
                    // rewrite the output for each category
					global $term_list;
                    $term_list .= '<li><a href="javascript:void(0)" class="'. $term->slug .'">' . $term->name . '</a></li>';
                    
                    // if count is equal to i then output blank
                    if ($count != $i) {
                        $term_list .= '';
                    } else {
                        $term_list .= '';
                    }
                }
                
                echo $term_list; // print out each of the categories in our new format
            }
        ?>
    </ul>
    
	<div class="separator no_wrapper"></div> 
     
<?php } ?>  

<ul class="filterable-grid portfolio_group col4_portfolio">

    <?php $wpbp = new WP_Query(array( 'post_type' => 'portfolio', 'posts_per_page' =>'-1') ); ?>
    
		<?php if ($wpbp->have_posts()) : while ($wpbp->have_posts()) : $wpbp->the_post(); // The Loop ?>
        
            <?php 
                $terms = get_the_terms( get_the_ID(), 'filter' ); // Get The Taxonomy 'Filter' Categories
                $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' ); 
                $large_image = $large_image[0];
                $item_url = get_post_meta($post->ID, 'portfolio_video_url', true); // get video url from meta box
				global $count;
            ?>
    
            <li class="one_fourth" data-id="id-<?php echo $count; ?>" data-type="<?php if ($terms != '') foreach ($terms as $term) { echo strtolower(preg_replace('/\s+/', '-', $term->name)). ' '; } ?>">
                
                <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
                    
                    <?php if($item_url != '') { // if 'video url' box is empty display an image ?>  
                    
                        <a class="portfolio_thumbnail" rel="prettyPhoto[gallery]" href="<?php echo $item_url; ?>"><?php the_post_thumbnail('portfolio-thumbnail-4col'); ?></a> 
                        
                   <?php } else { // if there is an 'video url' display the video ?>
                   
                        <a class="portfolio_thumbnail" rel="prettyPhoto[gallery]" href="<?php echo $large_image ?>"><?php the_post_thumbnail('portfolio-thumbnail-4col'); ?></a>	
                                								
                    <?php } ?> 
                                                      
                <?php endif; ?>	
                
                <h5><?php the_title(); ?></h5>
                <?php the_content(); ?>
                    
            </li>
    
        <?php $count++; // Increase the count by 1 ?>		
        <?php endwhile; endif; // END the Wordpress Loop ?>
        
    <?php wp_reset_query(); // Reset the Query Loop?>

</ul>