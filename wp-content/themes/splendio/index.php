<?php
/**
 * The main template file.
 */
get_header(); ?>

<!-- Start Content -->
<div class="SC" role="main">

<!-- Side - List Post-->
<div class="SL">

<!-- Start Slider -->
<?php if (is_file(ABSPATH . '/wp-content/plugins/wp-featured-content-slider/content-slider.php')) { ?>
<div class="featured">
 <span class="toptitle"><strong>Featured Article</strong></span>
 <?php  include (ABSPATH . '/wp-content/plugins/wp-featured-content-slider/content-slider.php'); ?>
</div>
<?php } ?>
<!-- End Slider -->

<!--start of Latest Posts-->
<div class="latest">
<span class="toptitle"><strong>Latest Post</strong></span>
<?php global $post; if ( is_front_page() ) { $myposts = get_posts('numberposts=1'); } else {$myposts = get_posts('offset=1'); } foreach($myposts as $post) : setup_postdata($post); ?>	  
<div id="post-<?php the_ID(); ?>" class="post indexpost">
<div class="post-head">
 <div class="post-date"><?php the_time('F j, Y') ?></div>
  <h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 </div>
  <?php if ( has_post_thumbnail()) : ?><div class="post-img"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail('large'); ?></a></div><?php endif; ?>
  <div class="post-con"><?php the_content(); ?></div>
 <ul class="post-det">
  <?php $tags_list = get_the_tag_list( '', ' | ' ); if ( $tags_list ): ?>
  <li class="post-tag"><?php printf( __( '<span class="%1$s"></span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?></li>
  <?php endif; ?>
  <li class="post-comment"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '[ 1 ] Comment', 'twentyten' ), __( '[ % ] Comments', 'twentyten' ) ); ?></li>
  <li class="post-tweet"><a href='http://twitter.com/share' class='twitter-share-button' data-url='<?php the_permalink(); ?>' data-text='<?php the_title(); ?>' data-count='vertical'>Tweet This !</a></li>
  <?php edit_post_link( __( 'Edit', 'twentyten' ), '<li class="post-edit">', '</li>' ); ?>
  </ul>
 </div>
<?php endforeach; ?>
<!--End of Post -->

</div>
<!--end of Latest Posts-->

<?php if (function_exists('mdv_recent_posts')) { ?>
<!-- Start - List -->
  <div class="list">
  <span class="toptitle"><strong>In other news</strong></span>
   <ul>
    <?php mdv_recent_posts(); ?>
   </ul>
  </div>
<!-- End - List-->
<?php } ?>

</div>
<!-- End - Side Left -->

<!-- Start - Side Right -->
<div class="SR">
 <?php get_sidebar(); ?>
</div>
<!-- End - Side Right -->

</div>
<!-- End - SC -->
<?php get_footer(); ?>