<?php get_header(); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

	<?php while (have_posts()) : the_post() ?>
		<article <?php post_class("content__article article typo"); ?>>
			<?php //the_title("<h1 class='article__title'>", "</h1>"); ?>
			<?php the_content("mehr…"); ?>
			<?php 
				if(!empty($_GET['searchkey'])){ 
					echo "<p class='backtosearchp'><a class='backtosearchlink' href='javascript:history.back();'>zurück zu den Suchergebnissen für »" . $_GET['searchkey'] . "«</a></p>";
				} 
			?>
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

</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>