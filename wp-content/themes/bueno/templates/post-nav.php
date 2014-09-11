<?php
/**
 * The template part for displaying posts navigation.
 *
 * @package bueno
 */
?> 
<?php if(!is_singular()) { ?>

  <?php if ( function_exists('bueno_pagination') ) : ?>
  	<?php bueno_pagination($wp_query->max_num_pages); ?>
  <?php else : ?>
    <?php if ( $wp_query->max_num_pages > 1 ) : ?>
      <ul class="pager">
        <li class="previous">
          <?php next_posts_link( __('&laquo; Older Entries', 'bueno')) ?>
        </li><!--.older-->
        <li class="next">
          <?php previous_posts_link(__('Newer Entries &raquo;', 'bueno')) ?>
        </li><!--.newer-->
      </ul><!--.oldernewer-->
    <?php endif; ?>
  <?php endif; ?>
<?php } else { ?>
<div class="single-post-nav">
    <?php previous_post_link('%link', '<span>%title</span>'); ?>
    <?php next_post_link('%link', '<span>%title</span>'); ?>
</div>
<?php } ?>
<!-- Posts navigation -->