<?php get_header(); ?>

<div class="home fix">
  <div class="main">
    <div class="fix">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  			<div class="post" id="post-<?php the_ID(); ?>">

<!-- thumbnail wrapper -->
<div class="thumb main">

<!-- 235150image-covers -->
<?php $image = get_post_meta($post->ID, 'thumbnail', true); ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php echo $image; ?>" alt="" /></a>
<!-- 235150image end -->

<!-- thumbanil title -->
<div class="thumb-title">
<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title() ?>
 <?php comments_number('{0}', '{1}', '{%}' );?></a></h2>
</div>
<!-- thumbanil title end -->

  			</div>
  			</div>
  		  <?php endwhile; ?>

  		  <?php else : ?>
  		  <div class="post single">
    			<h2>Keine Trefferergebnisse</h2>
    			<div class="entry">
    				<p>Sieht so aus als h&auml;ttest du eine tote-Link-Seite gefunden oder eine Suchanfrage ohne Treffer ausge&uuml;bt.</p>
    			</div>
    		</div>
  		<?php endif; ?>




<!-- adsense -->

<!-- adsense end -->



<!-- page navi -->
<div class="pagenavi">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi('', '', '', '', 3, false);} ?>
</div>
<!-- page navi end -->



  	</div>
  </div>



          <div class="sidebarwrapper">
           <?php include (TEMPLATEPATH . '/left.php'); ?>
           <?php include (TEMPLATEPATH . '/right.php'); ?>
          </div>

</div>



<?php include (TEMPLATEPATH . '/ancillary.php'); ?>
<?php get_footer(); ?>