<?php

function sub_page_list() {
	global $wpdb;
	$sql = "SELECT p.ID, p.post_title, p.guid, pm.meta_value FROM " . $wpdb->posts . " AS p LEFT JOIN ";
	$sql .= "(SELECT post_id, meta_value FROM " . $wpdb->postmeta . " AS ipm WHERE meta_key = 'subtitle') ";
	$sql .= "AS pm ON p.ID = pm.post_id ";
	$sql .= "WHERE p.post_type = 'page' AND p.post_parent = 0 AND p.post_status = 'publish' ";
	$sql .= "ORDER BY p.menu_order ASC ";
	$sql .= "LIMIT 0, 10";
	$rows = $wpdb->get_results($sql,OBJECT);
	if($rows) {
		foreach($rows as $row) {
			echo "<li><span>&mdash;</span>";
			$link_url = get_permalink($row->ID);
			echo "<a href=\"$link_url\">$row->post_title</a>";
			echo "</li>";
		}
	}
}
 
// Filter the menu to add the list
function childtheme_page_menu() { ?>
<li><a href="<?php bloginfo('home') ?>/">Home</a></li>                      
<?php sub_page_list(); ?>  
<?php }
add_filter('wp_page_menu','childtheme_page_menu');

function extend_blockquotes($text) {
	$new_text = str_replace('<blockquote>', '<blockquote><div class="blockquote_extender"><span>&lsquo;</span></div>', $text);
	return $new_text;
}
add_filter('the_content', 'extend_blockquotes');

?>