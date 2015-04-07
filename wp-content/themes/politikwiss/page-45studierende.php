<?php /* Template Name: Master 45 Studierende */ ?>
<?php get_header('45'); ?> 
<h1 class="sr-only">Absolventen<h1>
<section id="absolventen" class="container main_content_container">
<h2 class="sr-only">Hier finden Sie die Absolventen des Studiengangs</h2>
 <?php 
    $args = array(
        'post_type' 	=> 'Absolventen 45-75',
        'orderby' 		=> 'menu_order',
        'post_per_page' => 5
    );
    $Absolventen = new WP_Query( $args);
        if( $Absolventen->have_posts() ) :?>
            
    <?php while( $Absolventen->have_posts() ) : $Absolventen->the_post();?>
    <article class="<?php the_category_unlinked(' '); ?> clearfix">
     <h2><span class="block_grundstyle"><?php the_title(); ?></span></h2>
       <figure>
        <?php the_post_thumbnail( 'absolventen_thumb' ); ?>
      </figure>	
      <?php the_content(); ?>
       <div class="absolventen_meta"><?php the_meta(); ?></figcaption>
    </article>
    <?php endwhile; ?>
    <?php endif; ?>
</section>
</main> <!-- Ende von Main -->
<?php get_footer('45'); ?>








    
