<?php get_header(); ?>
	<?php if (have_posts()) : ?> 
			<div id="page-title">
				<div class="page-title-content">
					<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
					<?php /* If this is a category archive */ if (is_category()) { ?>
						<h3 class="page-title">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h3>
					<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
						<h3 class="page-title">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h3>
					<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
						<h3 class="page-title">Archive for <?php the_time('F jS, Y'); ?></h3>
					<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
						<h3 class="page-title">Archive for <?php the_time('F, Y'); ?></h3>
					<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
						<h3 class="page-title">Archive for <?php the_time('Y'); ?></h3>
					<?php /* If this is an author archive */ } elseif (is_author()) { ?>
						<h3 class="page-title">Author Archive</h3>
					<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
						<h3 class="page-title">Blog Archives</h3>
					<?php } ?>
					<div class="post-meta-single">
						<div class="small">
							<p class="small verdana">You can use the search form below to go through the content and find a specific post or page:</p>
							<?php include (TEMPLATEPATH . '/searchform.php'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="left-content">
			<?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<div class="post-date"><span class="month block"><?php the_time('M'); ?> </span><span class="day"><?php the_time('d'); ?> </span></div>
				<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
				<?php the_content(''); ?>
			</div>
			<?php endwhile; ?>
			
				<h5 class="small <?php echo $style; ?>"><?php posts_nav_link(' | ',__('&laquo; Newer Posts'),  __('Older Posts &raquo;')); ?></h5>
			
		</div>
	</div>
	<div id="right-col">
		<?php get_sidebar(); ?>
	</div>
</div></div>
	<?php  endif; ?>
<?php get_footer(); ?>