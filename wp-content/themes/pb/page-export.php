<?php
function removeReturn ($text) {
	return str_replace(array("\r\n", "\n", chr(13)), ' ', $text);
}

if (!empty($_GET['list'])) {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=export.csv');
	$output = fopen('php://output', 'w');
	$headline = array(
		'Titel',
		'Kategorie',
		'Kurzbeschreibung'
	);
	fputcsv($output, $headline);
	$args = array (
		'posts_per_page' => -1,
		'include' => $_GET['list']
		 );
	$posts = get_posts( $args );
	foreach ( $posts as $post ) {
		setup_postdata ( $post );
		$fields = get_fields();
		$nextline = array(
			trim($post->post_title),
			trim($fields['kategorie']),
			removeReturn(trim($fields['kurzbeschreibung']))
		);
		fputcsv($output, $nextline);
	}
	wp_reset_postdata();
	fclose($output);
}
?>
