<?php
/**
 * @package WordPress
 * @subpackage Studenten für Halle e.V.
 */
get_header();
?>
<?php
global $more;
$more = 0;
?>
        <?php query_posts($query_string . "&posts_per_page=1&cat=1&order=asc"); ?>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
          <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
          <div class="storycontent">
            <?php the_content('(weiterlesen...)'); ?>
	
          </div>
        </div>
        <!-- <div class="feedback">
          <?php wp_link_pages(); ?>
          <?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
        </div> -->
        <?php endwhile; else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
  <?php get_footer(); ?>