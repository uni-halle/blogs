<?php /* Template Name: Master 45 Lehrende */ ?>
<?php get_header('45'); ?> 
<h1 class="sr-only">Hier finden Sie Informationen zu den Lehrenden des Instituts für Politikwissenschaft</h1>
<div class="willkommen-120">
     <div class="container">
         <?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
         <?php the_content(); ?>
         <?php endwhile; else: ?>
         <?php endif; ?>
        </div>
    </div>
<section id="lehrende" class="container main_content_container lehrende_willkommen col_gap">
<h2 class="sr-only">Hier sind die Lehrenden des Studiengangs aufgelistet</h2>
  <?php 
    $args = array(
        'post_type' 	=> 'Lehrende',
        'orderby' 		=> 'menu_order',
        'posts_per_page' => -1
    );
    $Lehrende = new WP_Query( $args );
        if( $Lehrende->have_posts() ) :?>
     <?php while( $Lehrende->have_posts() ) : $Lehrende->the_post();?>
    <article class="lehrende">
     <figure>
     <h2><span class="block_grundstyle"><?php the_title(); ?></span></h2>
      <?php the_post_thumbnail( 'lehrende_thumb' ); ?>
     </figure>	
     <div class="post_content_lehrende">
     <?php the_content(); ?></div>
    </article>
    <?php endwhile; ?>
    <?php endif; ?>
</section>
</main> <!-- Ende von Main -->
<?php get_footer('45'); ?>






    
