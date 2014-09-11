<?php
/**
 * @package WordPress
 * @subpackage Studenten für Halle e.V.
 */
?>
<!-- begin footer -->
<div id="bottom">
  <div id="bottom_top">&nbsp;</div>
  <div id="bottom_all">
    <div id="navi3">
      <h1>Weitere Projekte</h1>
        <ul>
          <?php wp_list_pages('include=80,239,92,90,82,84,86,88&sort_column=desc&title_li='); ?>
        </ul>
    </div>
    <div id="impress">

<?php
global $more;
$more = 0;
?>
        <?php query_posts($query_string . "&posts_per_page=1&cat=5&order=asc"); ?>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
          <h1 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
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
<?php wp_footer(); ?>
</body>
</html>
