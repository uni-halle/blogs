<?php get_header();?>
                    
<?php is_tag(); ?>
<?php if (have_posts()) : ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>
<h2 class="pagetitle">Category : <?php single_cat_title(); ?></h2>
<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
<?php /* If this is an author archive */ } elseif (is_author()) { ?>
<h2 class="pagetitle">Author Archive</h2>
<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
<h2 class="pagetitle">Blog Archives</h2>
<?php } ?>
<?php endif; ?>

<!-- ************************************************************* -->
<?php if (have_posts()) : while (have_posts()) : the_post();
	$feedback ='feedback';
	$type = 'excerpt';
	include (TEMPLATEPATH . "/inner_content.php");
endwhile; ?>
	<div class="navigation">
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
        <div style="clear:both;"></div>
	</div>
<?php else: ?>
	<div class="error"><?php _e('Sorry, no posts matched your criteria.'); ?></div>
<?php endif; ?>
<!-- ************************************************************* -->
        </div>
    </div><!-- close innerContent -->
<?php get_sidebar();?>
<?php get_footer(); ?>