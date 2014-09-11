<?php get_header(); ?>
<!-- start content items -->
<div class="CON">


<!-- start center -->
<div class="SC">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="Post" id="post-<?php the_ID(); ?>">
<div class="PostHead">
<h1><?php the_title(); ?></h1>
<small class="PostAuthor">Author: <?php the_author() ?> <?php edit_post_link('Edit'); ?></small>
<p class="PostDate">
<small class="day"><?php the_time('j') ?></small>
<small class="month"><?php the_time('M') ?></small>
<small class="year"><? // php the_time('Y') ?></small>
</p>
</div>

<div class="PostContent">
 <?php the_content("<p>Read the rest of this entry &raquo;</p>"); ?>
</div>
<div class="PostDet">
 <li class="PostCateg">Filed under: <?php the_category(', ') ?></li>
</div>
</div>
<br clear="all" />

<ul class="Note">
<li class="NoteTrackBack"><?php comments_rss_link(__('<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post')); ?></li>
<?php if ( pings_open() ) : ?>
<li class="NoteRss"><a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a></li>
</ul>
<?php endif; ?>


<?php comments_template(); ?>
<?php endwhile; else : ?>

<h2><?php _e('Not Found'); ?></h2>
<p><?php _e('Sorry, but the page you requested cannot be found.'); ?></p>
<?php endif; ?>
</div> 
<!-- end center -->
<!-- start content left -->
<div class="SR">
<?php get_sidebar(); ?>
</div> 
<!-- end content left -->
</div> 
<?php get_footer(); ?>
