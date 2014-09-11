<?php get_header(); 

function show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}
?>

	<div id="htmlcontent">

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h1><?php single_cat_title(); ?>&#8217; Category</h1>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1><span class="grey">Tags:</span> <?php single_tag_title(); ?></h1>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1><?php the_time(get_option('date_format')); ?></h1>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1><span class="grey">Verlauf:</span> <?php the_time('F Y'); ?></h1>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1><span class="grey">Verlauf:</span> <?php the_time('Y'); ?></h1>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1>Author Archive</h1>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1>Blog Archives</h1>
 	  <?php } ?>

<?php if (show_posts_nav()) : ?>
		<div class="navigation">
			<div class="alignleft" id="skip"><?php next_posts_link('&laquo; Ältere') ?></div>
			<div class="alignright" id="skip"><?php previous_posts_link('Neuere &raquo;') ?></div>
		</div>
<?php endif; ?>

		<?php while (have_posts()) : the_post(); 
		 		
		?>
			
			<div class="post" id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>

				<div class="postmetadata"><?php the_time(get_option('date_format'))  ?>, <?php the_author(); ?>	</div>
				<div class="entry">
					 <?php the_excerpt(); ?> 
					<?php /* the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); */?>	
				</div>
	<div class="tagscomments"><?php comments_number('Keine Kommentare','Ein Kommentar','% Kommentare'); ?><br /><?php the_tags(__('Tags:', '') . ' ', ', ', '<br />'); ?><?php edit_post_link(__('Bearbeiten', ''), '&rarr; ', ' '); ?>  </div>

			</div>

		<?php endwhile; ?>
		<?php if (show_posts_nav()) : ?>
		<div class="navigation">
			<div class="alignleft" id="skip"><?php next_posts_link('&laquo; Ältere') ?></div>
			<div class="alignright" id="skip"><?php previous_posts_link('Neuere &raquo;') ?></div>
		</div>
		<?php endif; ?>		
		
	<?php else : ?>

		<h1>Kein Eintrag.</h1>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
