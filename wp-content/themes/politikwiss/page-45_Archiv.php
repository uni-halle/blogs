<?php /* Template Name: Archivseite Master 45 */ ?>
<?php get_header( '45'); ?>
<h1 class="container willkommen-120_ue"><span class="block_grundstyle">Archiv</span></h1>
<section id="archiv" class="container main_content_container col_gap">
    <h2 class="sr-only">Hier finden Sie alle Neuigkeiten im Archiv</h2>
    <?php $args=array( 'post_type'=>'News-45_75', 'orderby' => 'date', 'posts_per_page' => -1 ); $News_120 = new WP_Query( $args); if( $News_120->have_posts() ) :?>
    <?php while( $News_120->have_posts() ) : $News_120->the_post();?>
    <article>
	<h2><span class="block_grundstyle" href=""><?php the_title(); ?></span></h2>
        <p class="post_date"><i><?php the_time('j. F Y'); ?></i></p>
        <div class="post_content">
            <?php the_content(); ?>
        </div>
    </article>
    <?php endwhile; ?>
    <?php endif; ?>
</section>
</main>
<!-- Ende von Main -->
<?php get_footer('45'); ?>




