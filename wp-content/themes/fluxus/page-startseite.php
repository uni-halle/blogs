<?php
/**
 * Template Name: Startseite
 */
?>
<?php get_template_part('header', 'startseite'); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

	<?php while (have_posts()) : the_post() ?>
		<article <?php post_class("content__article article typo"); ?>>
			<?php //the_title("<h1 class='article__title'>", "</h1>"); ?>
			<?php the_content("mehr…"); ?>
		</article>
	<?php endwhile; ?>

	<?php
		if(empty($_GET['searchkey'])){ 
			$pages = get_pages(array( 'sort_order' => 'asc', 'sort_column' => 'menu_order' ));
			foreach($pages as $key => $page){
				if($page->ID == $post->ID){
					$counter = $key;
				}
			}
			if(!empty($pages[$counter + 1])){
				echo "<div class='main__nextbutton'>";
				echo "<a href='" . get_permalink($pages[$counter + 1]->ID) . "?dir=fw'><img class='main__nextbuttonimg' src='" . get_template_directory_uri() . "/img/arrdowngrey.svg' /></a>";
				echo "</div>";
			}
		}
	?>
		<article <?php post_class("content__article article typo"); ?> style="margin-top: 0; padding-top: 0;">
			<p style="text-align: right;"><small>Unterstützt durch</small></p>
			<a href="http://www.humboldt-foundation.de" target="_blank"><img class="size-thumbnail wp-image-915 qu" style="box-shadow: none; width: 10em; height: auto; max-width: 100%; float: right;" src="<?php echo get_template_directory_uri(); ?>/img/logos/humbold-stiftung.svg" alt="" /></a>
		</article>

</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>