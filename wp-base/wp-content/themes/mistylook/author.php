<?php get_header();?>
<div id="content">
<div id="content-main">
<div class="post">
<?php
	global $wp_query;
	$curauth = $wp_query->get_queried_object();
?>
<h2><?php _e('About','ml');?>: <?php echo $curauth->nickname; ?></h2>
<dl>
<dt><?php _e('Full Name','ml');?></dt>
<dd><?php echo $curauth->first_name. ' ' . $curauth->last_name ;?></dd>
<dt><?php _e('Website','ml');?></dt>
<dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
<dt><?php _e('Details','ml');?></dt>
<dd><?php echo $curauth->description; ?></dd>
</dl>

			<h2><?php _e('Posts by','ml');?> <?php echo $curauth->nickname; ?>:</h2>
			<ul class="authorposts">
			<!-- The Loop -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li>
				<h4>
				<em><?php the_time(__('d M Y','ml')); ?></em>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a>
				</h4>
			</li>
			<?php endwhile; else: ?>
			<p><?php _e('No posts by this author.','ml'); ?></p>

			<?php endif; ?>
			<!-- End Loop -->
		</ul>
		<p align="center"><?php posts_nav_link() ?></p>
	</div>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>
