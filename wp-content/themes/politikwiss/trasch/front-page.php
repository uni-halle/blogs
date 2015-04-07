 <?php get_header(); ?> 

<!--Slider-->
<?php 
$args = array(
	'post_type' 	=> 'slides',
	'orderby' 		=> 'menu_order',
	'post_per_page' => -1
);
$slides = new WP_Query( $args);
	if( $slides->have_posts() ) :?>
		<div class="flexslider">
  			<ul class="slides">
   			<?php while( $slides->have_posts() ) : $slides->the_post();?>
                <li>
                  <?php the_post_thumbnail('featured-thumbnail'); ?>
                </li>
            <?php endwhile; ?>
            </ul>
            </div>	
	<?php endif; ?>
<!--Slider Ende-->

<?php get_template_part( 'templates/dummy', 'page' ); ?>




 <?php get_footer(); ?> 



    
