<?php get_header(); ?>
<script type="text/javascript">

stepcarousel.setup({
	galleryid: 'board_carusel', //id of carousel DIV
	beltclass: 'belt', //class of inner "belt" DIV containing all the panel DIVs
	panelclass: 'board_item', //class of panel DIVs each holding content
	autostep: {enable:true, moveby:1, pause:<?php echo FEATURED_SPEED*1000; ?>},
	panelbehavior: {speed:500, wraparound:false, persist:false},
	defaultbuttons: {enable: false, moveby: 1, leftnav: ['http://i34.tinypic.com/317e0s5.gif', -5, 80], rightnav: ['http://i38.tinypic.com/33o7di8.gif', -20, 80]},
	statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
	contenttype: ['inline'] //content setting ['inline'] or ['external', 'path_to_external_file']
})

</script>

	<div id="content" class="narrowcolumn">
	
	<div id="board">
		<div id="board_items">
			<div id="board_body">
				<h2>Featured Posts</h2>
				<div id="board_carusel">
					<div class="belt">
					<?php $coint_i = carousel_featured_posts(obwp_get_meta(SHORTNAME.'_featured_cat_id'), FEATURED_POSTS); ?>
					</div>
				</div>
			</div>
			<ul id="board_carusel_nav">
				<li><a href="javascript:stepcarousel.stepBy('board_carusel', -1)"><img src="<?php bloginfo('template_url')?>/images/button_prev.png" alt="Prev" /></a></li>
				<li><a href="javascript:stepcarousel.stepBy('board_carusel', 1)"><img src="<?php bloginfo('template_url')?>/images/button_next.png" alt="Next" /></a></li>
			</ul>
		</div>
	</div>

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
                <div class="post-top">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php if ( function_exists('the_title_attribute')) the_title_attribute(); else the_title(); ?>"><?php the_title(); ?></a></h2>
					<div class="post_comments"><p><?php comments_number('0', '1', '%'); ?></p></div>
                </div>
				<div class="info">
					Posted on : <?php the_time('d-m-Y') ?> | By : <b><?php the_author() ?></b> | In : <span><?php the_category(', ') ?></span>
				</div>
				<div class="info">
					<?php the_tags(); ?>
				</div>
				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>
				<div class="postmetadata">
					<a href="<?php the_permalink() ?>">Continue Reading</a>
				</div>
			</div>

		<?php endwhile; ?>
	
		<?php 
		$next_page = get_next_posts_link('Previous'); 
		$prev_pages = get_previous_posts_link('Next');
		if(!empty($next_page) || !empty($prev_pages)) :
		?>
		<!-- navigation -->
		<div class="navigation">
			<?php if(!function_exists('wp_pagenavi')) : ?>
            <div class="alignleft"><?php next_posts_link('Previous') ?></div>
            <div class="alignright"><?php previous_posts_link('Next') ?></div>
            <?php else : wp_pagenavi(); endif; ?>
		</div>
		<!-- /navigation -->
		<?php endif; ?>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>