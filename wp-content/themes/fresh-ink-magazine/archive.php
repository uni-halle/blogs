<?php get_header(); ?>
	
		<div id="content">
			<div id="archivemarg"><?php if (have_posts()) : ?>
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<h2 class="pagetitle"><?php _e("Archive for the", "magazinetheme"); ?> '<?php echo single_cat_title(); ?>' <?php _e("Category", "magazinetheme"); ?> </h2>
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h2 class="pagetitle"><?php _e("Archive for", "magazinetheme"); ?>
				<?php the_time('F jS, Y'); ?>
			</h2>
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h2 class="pagetitle"><?php _e("Archive for", "magazinetheme"); ?>
				<?php the_time('F, Y'); ?>
			</h2>
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h2 class="pagetitle"><?php _e("Archive for", "magazinetheme"); ?>
				<?php the_time('Y'); ?>
			</h2>
			<?php /* If this is a search */ } elseif (is_search()) { ?>
			<h2 class="pagetitle"><?php _e("Search Results", "magazinetheme"); ?></h2>
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h2 class="pagetitle"><?php _e("Author Archive", "magazinetheme"); ?></h2>
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				<h2 class="pagetitle"><?php _e("Blog Archives", "magazinetheme"); ?>Blog Archives</h2>
				<?php } ?></div>
			<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="postmetadata">
			<?php _e("By ", "magazinetheme"); ?><?php the_author(); ?> 
			<?php _e(" Posted in  ", "magazinetheme"); ?>
				<?php the_category(', ') ?> <?php the_tags(', ') ?>
				 <strong>|</strong> 
				<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
		</div>

		<div class="entry">
			<?php the_excerpt(); ?>
			<span><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>"><?php _e('Read the rest of this entry &raquo;', 'magazinetheme'); ?></a></span>
		</div>

	
    
    
 

</div>
			<!-- end post-->
			<?php endwhile; ?>
			<div class="navigationtest">
			 
<?php get_pagination() ?>  

		</div>

			<?php else : ?>
			<h2 class="center"><?php _e("Not Found  ", "magazinetheme"); ?></h2>
			<?php get_search_form(); ?>
			<?php endif; ?>
		</div>
		<!-- end content div-->
		<?php get_sidebar(); ?>

<?php get_footer(); ?>
