<?php /* Template Name: Master 120 Studierende */ ?>
<?php get_header('120'); ?> 
<h1 class="sr-only">Hier finden Sie Informationen zu den Absolventen des Master 120 Politikwissenschaft</h1>
<div class="willkommen-120">
<div class="container">
<?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
     <?php the_content(); ?>
     <?php endwhile; else: ?>
     <p><?php _e('Sorry nix hier'); ?></p>
     <?php endif; ?>
</div>
</div>

<section id="absolventen" class="container main_content_container">
<h2 class="sr-only">Alumni des Studiengangs</h2>
 <?php 
    $args = array(
        'post_type' 	=> 'Absolventen',
        'orderby' 		=> 'ID',
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
     <div class="absolventen_meta"> <?php the_meta(); ?> </div>
    </article>
    <?php endwhile; ?>
    <?php endif; ?>
</section>

<div class="container absolventen_infobox">
<p><strong>Sie sind ebenfalls Absolvent des Masterstudienganges in Halle? Wir freuen uns über Ihre Einschätzungen und Erfahrungen, </strong><a href="mailto:sven.siefken@politik.uni-halle.de">bitte melden Sie sich!</a>. </p>
</div>

</main> <!-- Ende von Main -->

<?php get_footer('120'); ?>








    
