<?php get_header(); ?>
<div id="main" class="g25">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
<div class="pt"><h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent link to <?php the_title(); ?>"><?php the_title(); ?></a></h1></div>
<div class="text">
<?php the_content(); ?>
<?php wp_link_pages(); ?>
</div>
<div class="meta">&sect;<?php the_ID(); ?> &middot; <?php the_time('F j, Y'); ?> &middot; <?php the_category(', ') ?> &middot; <?php the_tags('Tags: ', ', ', ' '); ?> &middot; [<a href="javascript:window.print()">Print</a>] <?php edit_post_link('Edit', '[ ', ' ]'); ?></div>
<div class="social">
<ul>
<li><a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink() ?>">[E-mail this post]</a></li>
<li><a href="http://del.icio.us/post?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>">Save on Delicious</a></li>
<li><a href="http://reddit.com/submit?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>">Submit to Reddit</a></li>
<li><a href="http://www.digg.com/submit?phase=2&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>">Digg it</a></li>
<li><a href="http://furl.net/storeIt.jsp?t=<?php the_title() ?>&amp;u=<?php the_permalink() ?>">Store on Furl</a></li>
<li><a href="http://technorati.com/faves?add=<?php the_permalink() ?>">Fave on Technorati</a></li>
</ul>
</div>
</div>
<hr />
<!--
 <?php trackback_rdf(); ?>
 -->
<div style="clear"></div>
<div id="com">
<?php comments_template();  ?>
<div style="clear"></div>
</div>
<?php endwhile; ?>

<div class="navigation" style="clear:both;">
<div class="alignleft"><?php previous_post_link('&larr; %link'); ?></div>
<div class="alignright"><?php next_post_link('%link &rarr;'); ?></div>
</div>
<?php else : ?>
<?php include(TEMPLATEPATH."/notfound.php");?>
<?php endif; ?>
</div>
</div>
<?php include(TEMPLATEPATH."/sidebar.php");?>
<?php get_footer(); ?>