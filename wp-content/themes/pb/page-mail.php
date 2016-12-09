<?php
if (!empty($_GET['list'])) {
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename=e-mail.txt');
	$contacts = array ();
	$args = array (
		'posts_per_page' => -1,
		'include' => $_GET['list']
		);
	$posts = get_posts( $args );
	foreach ( $posts as $post ) {
		setup_postdata ( $post );
		$fields = get_fields();
		if ( $fields['ansprechpartner'] ) {
   			foreach ( $fields['ansprechpartner'] as $key => $value ) {
				$person = '';
				if ($fields['ansprechpartner'][$key]['titel']) $person .= trim($fields['ansprechpartner'][$key]['titel']) . ' ';
				$person .= trim($fields['ansprechpartner'][$key]['vorname']) . ' ' . trim($fields['ansprechpartner'][$key]['nachname']) . ' <' . trim(strtolower($fields['ansprechpartner'][$key]['e-mail'])) . '>';
        		array_push($contacts, $person);
  	  		}
		}
	}
	wp_reset_postdata();
	$contacts = array_unique($contacts);
	echo implode(";\r\n", $contacts);
}
?>
