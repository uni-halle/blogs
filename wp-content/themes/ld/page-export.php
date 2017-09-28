<?php
if (!empty($_GET['list'])) {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=export.csv');
	$args = array (
		'posts_per_page' => -1,
		'include' => $_GET['list']
		 );
	$posts = get_posts( $args );
	$rows = array();
	$i = 0;
	foreach ( $posts as $post ) {
		setup_postdata ( $post );
		$fields = get_fields();
		$rows[$i] = array();
		// Artikelnummer
		$rows[$i]['Artikelnummer'] = trim($post->post_title);
		// Kategorie
		$rows[$i]['Kategorie'] = $fields['kategorie'];
		// Titel
		if ($fields['titel']) {
			$rows[$i]['Titel'] = $fields['titel'];
		} else {
			$rows[$i]['Titel'] = '';
		}
		// Autoren
		if ($fields['autoren']) {
			$autoren = array();
			foreach ($fields['autoren'] as $key => $value) {
				$autoren[] = $fields['autoren'][$key]['name'];
			}
			$rows[$i]['Autor'] = implode(', ', $autoren);
	  } else {
			$rows[$i]['Autor'] = '';
		}
		// Bezeichnung
    if ($fields['bezeichnung']) {
			$rows[$i]['Bezeichnung'] = $fields['bezeichnung'];
		} else {
			$rows[$i]['Bezeichnung'] = '';
		}
		// Status
		switch ($fields['status']) {
			case 1:
				$rows[$i]['Status'] = 'Ausleihbar';
				break;
			case 2:
				$rows[$i]['Status'] = 'Reserviert';
				break;
			case 3:
				$rows[$i]['Status'] = 'Gesperrt';
				break;
			case 4:
				$rows[$i]['Status'] = 'Verloren';
				break;
		}
		// Kategorie
		$rows[$i]['Ort'] = $fields['ort'];
		// Entleiher
		if ($fields['entleiher']) {
      $entleiher = get_user_by('login', $fields['entleiher']);
      $rows[$i]['Entleiher'] = $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
    } else {
			$rows[$i]['Entleiher'] = '';
		}
		$i++;
	}
	wp_reset_postdata();

	// Spalten zählen
	$numbers = array();
	foreach ( $rows as $row ) {
		foreach ( $row as $key => $value ) {
			if (is_array($value)) {
				if ($numbers[$key] < count($row[$key])) {
					$numbers[$key] = count($row[$key]);
				}
			} else {
				$numbers[$key] = 1;
			}
		}
	}

	// Kopfzeile auffüllen und ausgeben
	$output = fopen('php://output', 'w');
	$headline = array();
	foreach ( $numbers as $title => $number ){
		$headline[] = $title;
		for ($j = 1; $j < $number; $j++) {
			$headline[] = '';
		}
	}
	fputcsv($output, $headline);

	// Zeilen auffüllen und ausgeben
	foreach ( $rows as $row ) {
		$fillup = array();
		foreach ( $row as $key => $value ) {
			if (is_array($value)) {
				$fillup = array_merge($fillup, $value);
				for ($k = count($value); $k < $numbers[$key]; $k++) {
					$fillup[] = '';
				}
			} else {
				$fillup[] = $value;
			}
		}
		fputcsv($output, $fillup);
	}

	fclose($output);
}
?>
