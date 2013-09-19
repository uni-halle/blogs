<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="maincontent">

			<?php /* If this is a 404 page */ if (is_404()) { ?>
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<h3 class="im-here">You are currently browsing the archives for the <strong><?php single_cat_title(''); ?></strong> category.</h3>

			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<h3 class="im-here">You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives
			for the day <?php the_time('l, F jS, Y'); ?>.</h3>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h3 class="im-here">You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives
			for <?php the_time('F, Y'); ?>.</h3>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h3 class="im-here">You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives
			for the year <?php the_time('Y'); ?>.</h3>

			<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<h3 class="im-here">You have searched the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives
			for <strong>'<?php the_search_query(); ?>'</strong>.</h3>

			<?php } ?>
            
	<?php if (have_posts()) : ?>
    
		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
            <p class="date">Posted on <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?></p>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry">
					<?php the_excerpt('Read the rest of this entry &raquo;'); ?>
				<p><?php edit_post_link('Edit'); ?> </p>
				</div>
    <ul class="actions">
        <li class="comments-number"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li>
        <li class="cats"><?php the_category(', ') ?></li>
        <li class="author">by <strong><?php the_author(); ?></strong></li>
    </ul>

			</div>

		<?php endwhile; ?>

	<?php else : ?>
			<div class="post">
		<h2>Not Found - Error 404</h2>
		<p>Sorry, but you are looking for something that isn't here.</p>
 		<p>Please try to make a new search.</p>
       </div>
	<?php endif; ?>

</div><!-- #maincontent -->
<?php include (TEMPLATEPATH . '/sidebar-extra.php'); ?>

<?php get_footer(); ?>
