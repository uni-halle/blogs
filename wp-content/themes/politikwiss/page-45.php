<?php /* Template Name: Master 45 */?>
<?php get_header('45'); ?>

    <div class="willkommen-120">
    <div class="container">
        <?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else: ?>
<?php endif; ?>
</div>
</div>
   
<div class="sliderwrap">
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
</div>
<h1 class="container willkommen-120_ue"><span class="block_grundstyle">Aktuelles</span></h1>
<section id="news_120" class="container main_content_container col_gap">
<h2 class="sr-only">Neuigkeiten rund um den Studiengang</h2>    

    <?php $args=array( 'post_type'=>'News-45_75', 'orderby' => 'date', 'posts_per_page' => 4 ); $News_120 = new WP_Query( $args); if( $News_120->have_posts() ) :?>
    <?php while( $News_120->have_posts() ) : $News_120->the_post();?>
    <article>
	<h2><span class="block_grundstyle"><?php the_title(); ?></span></h2>
        <p class="post_date"><i><?php the_time('j. F Y'); ?></i></p>
        <div class="post_content">
            <?php the_content(); ?>
        </div>
</article>
    <?php endwhile; ?>
    <?php endif; ?>
<div class="archivlink"><a href="/masterpolitikwissenschaften/?p=289"><span class="block_grundstyle">Zum Archiv</span></a></div>
</section>
</main>
<!-- Ende von Main -->
 <?php get_footer('45'); ?> 



    
