<?php
/**
 * Template Name: Archives
 *
 * A custom page template without sidebar.
 */
?>

<?php 
$file_dir=get_bloginfo('template_directory');
wp_enqueue_script('newscript', $file_dir.'/includes/js/archives-page.js', false, '1.0'); ?>
<?php get_header(); ?>


	<div id="content" class="narrow">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
		<div class="post-header">
			<h1 class="page-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		</div>
		
		<?php the_content( __('<p> Read more &raquo;</p>', 'jenny') ); ?>
		<?php wp_link_pages( __('before=<div class="post-page-links">Pages:&after=</div>', 'jenny')) ; ?>
			
		<p><a href="<?php home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a> has 
		<strong>
		<?php
		$count_posts = wp_count_posts();
		$published_posts = $count_posts->publish;
		echo $published_posts;
		?>
		</strong>
		posts/articles and 
		<strong>
		<?php
		$count_pages = wp_count_posts('page');
		$published_pages = $count_pages->publish;
		echo $published_pages;
		?>
		</strong>
		pages!</p>
		
		<div class="archivesection">
		<h3><?php if ($published_posts > 50) { _e('Last 50 Articles', 'jenny'); } else { _e('Lastest Articles', 'jenny');} ?></h3>
		<div class="archiveslist">
		<ul>
		<?php
		$myposts = get_posts('numberposts=50&offset=0');
		foreach($myposts as $post) :
		?>
		<li><?php the_time('d/m/y') ?>: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endforeach; ?>
		</ul>
		</div>
		</div>
		<hr />
		
			
		<div class="archivesection">
		<h3><?php _e('Monthly Archives', 'jenny'); ?></h3>
		<div class="archiveslist">
		<ul>
		<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
		<ul>
		</div>
		</div>
		<hr />
		
		<div class="archivesection">
		<h3><?php _e('Category Archives', 'jenny'); ?></h3>
		<div class="archiveslist">
		<ul>
		<?php wp_list_categories('title_li=&hierarchical=0&sort_column=name&optiondates=1&optioncount=1'); ?>
		</ul>
		</div>
		</div>
		<hr />
		
		</div>

		<?php endwhile; ?>
		
	<?php else : ?>
		
		<h2 class="page-title"><?php _e('Not Found', 'jenny'); ?></h2>
		<p><?php _e('Sorry, but you are looking for something that is not here.', 'jenny'); ?></p>
		<?php get_search_form(); ?>
		
	<?php endif; ?>
	
	</div>
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
