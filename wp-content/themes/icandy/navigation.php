<?php
global $wp_rewrite;
$page_start = get_pagenum_link(1);
if (strpos($page_start, '?') || ! $wp_rewrite->using_permalinks()) {
	$page_format = '';
	$page_start = add_query_arg('paged', '%#%');
} else {
	$page_format = (substr($page_start, -1 ,1) == '/' ? '' : '/') .
	user_trailingslashit('page/%#%/', 'paged');;
	$page_start .= '%_%';
}

echo '<div class="navigation-pages">'. "\n";
echo paginate_links( array(
	'base' => $page_start,
	'format' => $page_format,
	'total' => $wp_query->max_num_pages,
	'mid_size' => 2,
	'current' => ($paged ? $paged : 1),
        'type' => 'list',
        'prev_text' => '<strong>&lt;</strong>',
        'next_text' => '<strong>&gt;</strong>',
));
echo "\n</div><div class='clear'></div>\n";
?>