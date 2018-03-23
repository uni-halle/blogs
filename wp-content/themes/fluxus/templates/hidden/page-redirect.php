<?php
/**
 * Template Name: Umleitung zur ersten Unterseite
 */
?>
<?php
	$childs = get_pages("child_of=".$post->ID."&sort_column=menu_order");
	if($childs) {
		$firstchild = $childs[0];
		wp_redirect(get_permalink($firstchild->ID));
	}
?>