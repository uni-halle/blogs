<?php get_header(); ?>

<div id="coreContent">


    <div class="post hentry single">
      <div class="postContent">
        <h2 class="entry-title">404 - Page Not Found</h2>
        <div class="entry-content">
          <p>Unfortunately the content you're looking for isn't here. There may be a misspelling in your web address or you may have clicked a link for content that no longer exists. Perhaps you would be interested in our most recent articles.</p>
        </div>
      </div>
   </div>


   <h4>Recent Articles</h4>

   <?php query_posts('cat=&showposts=5'); ?>
   <ul id="recentPosts">

   		<?php while (have_posts()) : the_post(); ?>

      <li>
        <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
        <div class="postDate"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_date('F j, Y') ?></abbr></div>
      </li>

   		<?php endwhile; ?>
   
    </ul>
   	</div>

</div>



<?php get_footer(); ?>
