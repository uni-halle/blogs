<?php get_header() ?>

	<div id="container">
		<div id="content">
		
<!-- Begin editing categories below here. -->
		
		 <ul class="latest">
<?php $feature_post = get_posts( 'category=1&numberposts=1' ); ?>
<?php foreach( $feature_post as $post ) : setup_postdata( $post ); ?>
<li><h2 class="latest"><?php the_category(' '); ?></h2></li>
<?php endforeach; ?>
<?php $feature_post = get_posts( 'category=1&numberposts=1' ); ?>
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
<?php $feature_post = get_posts( 'category=2&numberposts=1' ); ?>
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
<?php $feature_post = get_posts( 'category=3&numberposts=1' ); ?>
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
<?php $feature_post = get_posts( 'category=4&numberposts=1' ); ?>
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
<?php $feature_post = get_posts( 'category=5&numberposts=1' ); ?>
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
<?php $feature_post = get_posts( 'category=6&numberposts=1' ); ?>
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


				<div class="left-col">
				<?php rewind_posts(); ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php the_category(); ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="excerpt"><?php the_excerpt(); ?></div>
				<p class="author">By <?php the_author_posts_link(); ?></p>
				
				
<div class="author-desc"><p><?php the_author_description(); ?></p></div>
<div class="author-links">Posts by <?php the_author_posts_link(); ?><br/>
<?php the_author(); ?>&rsquo;s <a href="<?php the_author_url(); ?>">Website</a>
<?php edit_post_link(__('Edit', 'sandbox'), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>"); ?></div>

		
	<div id="nav-above" class="navigation">
	<h3>Browse</h3>		
				<div class="nav-previous"><?php previous_post_link('<span class="meta-nav">&laquo;</span> %link') ?></div>
				<div class="nav-next"><?php next_post_link('<span class="meta-nav">&raquo;</span> %link') ?></div>
				
				<h3>Browse in <?php 
foreach((get_the_category()) as $cat) { 
echo $cat->cat_name . ' '; 
} ?></h3>
			<div class="nav-previous"><?php previous_post_link('&laquo; %link', '%title', TRUE); ?></div>
			<div class="nav-next"><?php next_post_link('&raquo; %link', '%title', TRUE); ?></div>
			</div><!-- #nav-above -->


				</div>




			<div id="post-<?php the_ID(); ?>" class="<?php sandbox_post_class(); ?>">
								<div class="entry-content">
								
<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>', 'sandbox').''); ?>

<?php link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'sandbox'), "</div>\n", 'number'); ?>

<?php if (function_exists('the_tags') ) : ?>
<?php the_tags(); ?>
<?php endif; ?>
				</div>
				

<div class="entry-meta">			
			<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Comments and trackbacks open ?>
					<?php printf(__('<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'sandbox'), get_trackback_url()) ?>
<?php elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) : // Only trackbacks open ?>
					<?php printf(__('Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'sandbox'), get_trackback_url()) ?>
<?php elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Only comments open ?>
					<?php printf(__('Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'sandbox')) ?>
<?php elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) : // Comments and trackbacks closed ?>
					<?php _e('Both comments and trackbacks are currently closed.') ?>
<?php endif; ?>

</div>

		<div id="nav-below" class="navigation">
		<h3>Browse</h3>
				<div class="nav-previous"><?php previous_post_link('<span class="meta-nav">&laquo;</span> %link') ?></div>
				<div class="nav-next"><?php next_post_link('<span class="meta-nav">&raquo;</span> %link') ?></div>
				<h3>Browse in <?php 
foreach((get_the_category()) as $cat) { 
echo $cat->cat_name . ' '; 
} ?></h3>
			<div class="nav-previous"><?php previous_post_link('&laquo; %link', '%title', TRUE); ?></div>
			<div class="nav-next"><?php next_post_link('&raquo; %link', '%title', TRUE); ?></div>

				<?php if ( function_exists('related_posts')) :?>
			<h3>Related Posts</h3>
			<ul>				
			<?php related_posts(); ?>
			</ul>
	
		<?php endif; ?>

			</div>

<?php comments_template(); ?>
<?php endwhile;?><?php endif; ?>
								
				<?php the_post(); ?>
		</div><!-- .post -->
		<?php get_sidebar() ?>
				</div><!-- #content -->

	</div><!-- #container -->
<?php include (TEMPLATEPATH . '/bottom.php'); ?>
<?php get_footer() ?>