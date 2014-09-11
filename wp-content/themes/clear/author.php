<?php get_header();?>
<div id="main">
	<div id="content">
	<?php
	global $wp_query;
	$curauth = $wp_query->get_queried_object();
?>
<h2>About: <?php echo $curauth->nickname; ?></h2>
<dl>
<dt>Full Name</dt>
<dd><?php echo $curauth->first_name. ' ' . $curauth->last_name ;?></dd>
<dt>Website</dt>
<dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
<dt>Details</dt>
<dd><?php echo $curauth->description; ?></dd>
</dl>

			<h2>Posts by <?php echo $curauth->nickname; ?>:</h2>
			<ul class="authorposts">
			<!-- The Loop -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li>
				<em><?php the_time('d M Y'); ?></em>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a>
			</li>
			<?php endwhile; else: ?>
			<p><?php _e('No posts by this author.'); ?></p>

			<?php endif; ?>
			<!-- End Loop -->			
		</ul>
		<p align="center"><?php posts_nav_link() ?></p>
	</div>
  <?php get_sidebar();?>
  <?php get_footer();?>