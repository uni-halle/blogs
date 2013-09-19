<?php get_header(); ?>
<div id="main" class="g25">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
<div class="pt"><h2>&para; <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent link to <?php the_title(); ?>"><?php the_title(); ?></a></h2></div>
<div class="text">
<?php the_excerpt(); ?>
<p><a href="<?php the_permalink() ?>">Read more...</a></p>
<div class="meta">&sect;<?php the_ID(); ?> &middot; <?php the_time('F j, Y'); ?> &middot; <?php the_category(', ') ?> &middot; <?php comments_popup_link('(No comments)', '1 comment', '% comments'); ?> &middot; <?php the_tags('Tags: ', ', ', ' '); ?></div>
</div>
<?php wp_link_pages () ?>
<br /><hr />
</div>
<div class="clear"></div>
<!--
 <?php trackback_rdf(); ?>
 -->
<?php endwhile; ?>
<div class="navigation">
<div class="alignleft"><?php next_posts_link('&larr; Older entries') ?></div>
<div class="alignright"><?php previous_posts_link('Newer entries &rarr;') ?></div>
</div>
<div class="clear"></div>
<?php else : ?>
<?php include(TEMPLATEPATH."/notfound.php");?>
<?php endif; ?>
</div>
<?php include(TEMPLATEPATH."/sidebar.php");?>
<div class="clear"></div>
</div>
<div class="clear"></div>
<?php get_footer(); ?>