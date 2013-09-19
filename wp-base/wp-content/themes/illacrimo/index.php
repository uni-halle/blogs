<?php get_header(); ?>
<!-- Container -->
<div class="CON">

<!-- Start SC -->
<div class="SC">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="Post" id="post-<?php the_ID(); ?>" style="padding-bottom: 40px;">

<div class="PostHead">
<h1><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<small class="PostAuthor">Author: <?php the_author() ?> <?php edit_post_link('Edit'); ?></small>
<p class="PostDate">
<small class="day"><?php the_time('j') ?></small>
<small class="month"><?php the_time('M') ?></small>
<small class="year"><? // php the_time('Y') ?></small>
</p>
</div>
  
<div class="PostContent">
<?php the_content('Read the rest of this entry &raquo;'); ?>
</div>
<div class="PostDet">
 <li class="PostCom"><?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></li>
 <li class="PostCateg">Filed under: <?php the_category(', ') ?></li>
</div>
</div>

<!--<?php trackback_rdf(); ?>-->
<div class="clearer"></div>
<?php endwhile; ?>
  
<div class="Nav"><?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?></div>

<?php else : ?>
<h2><?php _e('Not Found'); ?></h2>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
</div> 
<!-- End SC -->

<?php get_sidebar(); ?>
<?php trackTheme("illacrimo");  ?>

<!-- Container -->
</div>

<?php get_footer(); ?>
