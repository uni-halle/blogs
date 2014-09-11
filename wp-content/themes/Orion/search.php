<?php get_header(); ?>



<div class="eleven columns ">
<div id="content" >

<div class="subtitle">
<p>	<?php $mySearch =& new WP_Query("s=$s & showposts=-1");	$num = $mySearch->post_count;	echo $num.' search results for '; the_search_query();?> in <?php  get_num_queries(); ?> <?php timer_stop(1); ?> seconds.</p>
</div>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
		
<article class="post" id="post-<?php the_ID(); ?>">
<div class="title">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
</div>

<div class="postmeta">
	<span class="author">Posted by <?php the_author(); ?> </span> <span class="clock">  <?php the_time('M - j - Y'); ?></span>
</div>

<div class="entry">
<?php wpe_excerpt('wpe_excerptlength_index', ''); ?>
<div class="clear"></div>
</div>
</article>
<?php endwhile; ?>

<?php getpagenavi(); ?>

<?php else : ?>

<div class="post"><div class="title">
		<h4>Your search - <?php the_search_query();?> - did not match any entries.</h4>
</div>


<div class="entry">
<p>Suggestions:</p>
<ul>
   <li>  Make sure all words are spelled correctly.</li>
   <li>  Try different keywords.</li>
   <li>  Try more general keywords.</li>
</ul>
</div>
</div>

<?php endif; ?>

</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>