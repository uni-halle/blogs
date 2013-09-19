<?php get_header(); ?>
<div class="home fix">
  <div class="main">
    <div class="fix">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  			<div class="post single fix" id="post-<?php the_ID(); ?>">
  			  <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></h2>
  			  <div class="postMeta">
              <span class="comments"><a href="#comments"><?php comments_number('Keine Antworten', 'Eine Antwort', '% Meinungen' );?></a></span>
              <span class="date"> // <?php the_time('d M. Y') ?> // <?php the_category(', ') ?></span>
              </div>

              <div class="entry">
  					<?php the_content('<p>Den Rest weiterlesen &raquo;</p>'); ?>
  					<br />

  				</div>




  			</div>
  		<?php endwhile; else : ?>
  		<?php endif; ?>
  	</div>
  </div>

           <div class="sidebarwrapper">
           <?php include (TEMPLATEPATH . '/left.php'); ?>
           <?php include (TEMPLATEPATH . '/right.php'); ?>
           </div>

</div>

<?php comments_template(); ?>
<?php include (TEMPLATEPATH . '/ancillary.php'); ?>
<?php get_footer(); ?>