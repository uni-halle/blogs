<?php get_header() ?>

<div id="container">
		<div id="content">
		
<!-- Begin editing categories below here. -->
		
<ul class="latest">
<?php $feature_post = get_posts( 'category=1&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=1&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>

  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>


<ul class="latest">
<?php $feature_post = get_posts( 'category=2&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=2&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>
  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>

<ul class="latest">
<?php $feature_post = get_posts( 'category=3&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=3&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>
  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>

<ul class="latest">
<?php $feature_post = get_posts( 'category=4&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=4&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>
  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>

<ul class="latest">
<?php $feature_post = get_posts( 'category=5&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=5&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>
  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>

<ul class="latest">
<?php $feature_post = get_posts( 'category=6&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=6&numberposts=3' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<?php if (function_exists('c2c_get_custom')) : ?>
 <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo c2c_get_custom('post-image','<img src="','" alt="<?php the_title(); ?>" class="post-image" />',''); ?></a></li>
<?php endif; ?>
  		<li class="list-time"><?php the_time('d'); ?>.<?php the_time('M'); ?></li>
  		<li class="list-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
  		<li class="latest-excerpt"><?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>

<!-- Stop editing categories here. -->

  
 <div class="clear"></div>
 
<!-- You can edit this part, but be careful. -->
 
 <div class="two-col">
 <h2>About</h2>
<p>Futurosity Magazine Theme was designed by <a href="http://www.upstartblogger.com/" title="Upstart Blogger">Upstart Blogger</a> and is based on a designed previously used on <a href="http://www.futurosity.com/" title="Futurosity">Futurosity</a>. You will always find the latest information for this theme at <a href="http://www.upstartblogger.com/wordpress-theme-upstart-blogger-futurosity-magazine" title="Permalink to WordPress Theme: Upstart Blogger Futurosity Magazine">WordPress Theme: Upstart Blogger Futurosity Magazine Theme</a>. You can delete this text by editing home.php in the ub_futurositymag theme folder.</strong></p>
</div>
 
  <div class="two-col">
  <h2>What?</h2>
<p> What goes here? You decide! Edit this in <strong>home.php</strong>.</p>
  </div>
 
  <div class="one-col">
  <h2>Join</h2>
<p><strong>Join <?php bloginfo('blog_name'); ?></strong>. Post comments and submit stories&mdash;engage, converse, create. Login, or join now.</p>

				<ul>
					<?php wp_register() ?>

					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>

				</ul>

 </div>
 

 
  <div class="one-col">
  <h2>Subscribe</h2>
				<ul>
					<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'sandbox') ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'sandbox') ?></a></li>
				</ul>

				<a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><img src="<?php bloginfo('template_url'); ?>/images/feedbot.gif" width="125" style="border:none;" alt="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" /></a>
 </div>
 

 
  <div class="clear"></div>
 
 <div class="recent-comments">
        <h2>Comments</h2>
        
        <?php if (function_exists('get_recent_comments')) : ?>
				<ul>
              <?php get_recent_comments(); ?>
              </ul>
		<?php endif; ?>

              </div>
 <div class="sited"><h2>Sited</h2>
 
 <!-- Edit this with the path to the image you want to feature. -->
 <img src="<?php bloginfo('template_url'); ?>/screenshot.png" alt="Featured picture" style="float:left;"/>
 <!-- End edit. -->
 
 <ul class="sited">
<?php $feature_post = get_posts( 'category=7&numberposts=10' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
  		<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a> <?php the_excerpt(); ?></li>
<?php endforeach; ?>
</ul>
</div>

<!-- Stop editing here. -->
 		</div><!-- #content -->
	</div><!-- #container -->
<?php include (TEMPLATEPATH . '/bottom.php'); ?>
	<?php get_footer() ?>