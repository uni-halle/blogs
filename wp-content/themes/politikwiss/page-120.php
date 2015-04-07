<?php /* Template Name: Master 120 */ ?>
<?php get_header( '120'); ?>
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
<section>
    <h2 class="sr-only">Allgemeine Informationen</h2>
    <div class="willkommen-120">
        <p class="container"><strong>Willkommen</strong>
            auf der Startseite des politikwissenschaftlichen Masterstudiengangs Parlamentsfragen und Zivilgesellschaft der Martin-Luther-Universität Halle-Wittenberg. Hier erhalten Sie alle Informationen rund um den Studiengang in Halle (Saale).
        </p>
    </div>
   
</section>

<h1 class="container willkommen-120_ue"><span class="block_grundstyle">Aktuelles</span></h1>
<section id="news_120" class="container main_content_container col_gap">
<h2 class="sr-only">Neuigkeiten</h2>    

    <?php $args=array( 'post_type'=>'News-120', 'orderby' => 'date', 'posts_per_page' => 4 ); $News_120 = new WP_Query( $args); if( $News_120->have_posts() ) :?>
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

    




<div class="archivlink"><a href="/masterpolitikwissenschaften/?p=182"><span class="block_grundstyle">Zum Archiv</span></a></div>
</section>



</main>
<!-- Ende von Main -->

<?php get_footer('120'); ?>




