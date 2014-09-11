<!--Start Search -->
<div class="search">
 <h3>Search</h3>
  <form id="search" action="<?php bloginfo('url'); ?>/">
    <fieldset>
    <input type="text" value="<?php the_search_query(); ?>" name="s" style="width: 250px;" />
    </fieldset>
    </form>
</div>
<!--End Search -->

<!--Start Recent -->
<div class="recent">
 <ul class="tabs">
  <li><a href="#r-posts"><span>Latest Posts</span></a></li><li><a href="#r-com"><span>Latest Comments</span></a></li><li><a href="#r-tags"><span>Tags</span></a></li>
 </ul>
 <br clear="all" />
<ul id="r-posts">
 <?php $posts = get_posts("numberposts=10&orderby=post_date&order=DESC"); foreach($posts as $post) : ?>	
  <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
 <?php endforeach; ?>
</ul>
<ul id="r-com">
 <?php dp_recent_comments(3); ?>
</ul>
<div id="r-tags">
 <?php wp_tag_cloud(''); ?>
</div>
</div>
<!--End Recent -->


<!-- Start Flickr Photostream -->
<?php if (function_exists('get_flickrrss')) { ?>
<div class="flickr">
  <h3>Flickr PhotoStream</h3>
  <ul>
   <?php get_flickrrss(4); ?> 
  </ul>
</div>
<?php } ?>
<!-- End Flickr Photostream -->

<div class="about-all"><div class="about">
<h3>About</h3>
This is an example of a WordPress page, you could edit this to put information about yourself or your site so readers know where you are coming from.
</div></div>


<br />
<!--Start Dynamic Sidebar -->
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
<?php endif; ?>
<!--End Dynamic Sidebar -->