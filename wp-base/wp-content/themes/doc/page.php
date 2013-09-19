<?php get_header(); ?>
<div id="main" class="g25">
<?php while (have_posts()) : the_post(); ?>
<div class="post">
<div class="pt"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent link to <?php the_title(); ?>"><?php the_title(); ?></a></h2></div>
<div class="text">
<?php the_content(); ?>
</div>
<?php wp_link_pages () ?>
<div class="meta">&sect;<?php the_ID(); ?> &middot; By <?php the_author_posts_link(); ?> &middot; <?php the_time('F j, Y'); ?> &middot;</div>
</div>
<br /><hr />
<div class="clear"></div>
<!--
 <?php trackback_rdf(); ?>
 -->

<div id="com">
<?php comments_template();  ?>
</div>
<?php endwhile; ?>
</div>
<?php include(TEMPLATEPATH."/sidebar.php");?>
</div>
<div class="clear"></div>
<?php get_footer(); ?>