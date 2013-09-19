<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */
?>
<?php get_header(' '); ?>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
query_posts($query_string .'&posts_per_page=10&paged=' . $paged);
?>

	<div id="page-heading">
		<h1><?php _e('Search Results For','MLUphysik'); ?>: "<?php the_search_query(); ?>"</h1>
    </div>
    
<?php
if (have_posts()) :
?>
            
	<div id="masonry-wrap">
		<?php get_template_part( 'loop' , 'entry') ?>
	</div>
	<?php if (function_exists("pagination")) { pagination(); } ?>   
        
<?php else : ?>
    
    <div id="post" class="post clearfix">
    	<div class="entry">
    		<?php _e('No results found for that query.', 'MLUphysik'); ?>
		</div>
    </div>
    <!-- END post  -->
    
<?php endif; ?>

		  
<?php get_footer(' '); ?>