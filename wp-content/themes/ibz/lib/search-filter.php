<?php
/**
 * SEARCH FILTER (display only blog posts in search results)
 */

function SearchFilter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
return $query;
}

add_filter('pre_get_posts','SearchFilter');

?>