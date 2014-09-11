<?php get_header(); ?>

<div id="coreContent" class="searchresults">
  
  <div class="searchpanel">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
      <div id="search">
        <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="Search" />
      </div>
    </form>
  </div>

  <h2>Search Results</h2>

  
    	<?php if (have_posts()) : ?>

    		<?php while (have_posts()) : the_post(); ?>
      <div class="post hentry">
        <div class="postContent">
          <h3 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
          <div class="entry-content">
            <?php the_excerpt('Read the rest of this entry &raquo;'); ?>
          </div>
        </div>
        <div class="postMeta">
          <?php
          $arc_year = get_the_time('Y');
          $arc_month = get_the_time('m');
          $arc_day = get_the_time('d');
          ?>
        
          <div class="postDate"><span>Published:</span> <abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><a href="<?php echo get_day_link("$arc_year", "$arc_month", 
          "$arc_day"); ?>"><?php the_time('F j, Y'); ?></a></abbr></div>
        	<div class="categories"><span>Filed Under:</span> <?php the_category(', '); ?></div>
        	<?php the_tags('<span>Tags:</span> ', ' : ', ''); ?>
        </div>
      </div>
		<?php endwhile; ?>

    <div class="pageNav">
      <div class="prev"><?php next_posts_link('&laquo; Older') ?></div>
      <div class="next"><?php previous_posts_link('Newer &raquo;') ?></div>
    </div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>

</div>


<?php get_footer(); ?>

