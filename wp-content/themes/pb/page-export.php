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
		// Titel
		$rows[$i]['Titel'] = trim($post->post_title);
		// Kategorie
		$rows[$i]['Kategorie'] = $fields['kategorie'];
		// Kurzbeschreibung
		$rows[$i]['Kurzbeschreibung'] = removeReturn(trim($fields['kurzbeschreibung']));
		// Label
		$rows[$i]['Label'] = array();
		if ($fields['label']){
			foreach ($fields['label'] as $label ){
					$rows[$i]['Label'][] = $label;
			}
		} else {
			$rows[$i]['Label'][] = '';
		}
		// Verweis
		$rows[$i]['Verweis'] = ($fields['verweis'] ? $fields['verweis'] : '');
		// Status
		switch ($fields['status']) {
			case 1:
				$rows[$i]['Status'] = 'In Planung';
				break;
			case 2:
				$rows[$i]['Status'] = 'In Bearbeitung';
				break;
			case 3:
				$rows[$i]['Status'] = 'Pausiert';
				break;
			case 4:
				$rows[$i]['Status'] = 'Abgeschlossen';
				break;
			case 5:
				$rows[$i]['Status'] = 'Abgebrochen';
				break;
			default:
				$rows[$i]['Status'] = '';
		}
		// Fortschritt
		$rows[$i]['Fortschritt'] = $fields['fortschritt'];
		// Beginn
		$rows[$i]['Beginn'] = date("d.m.Y", strtotime($fields['zeitraum'][0]['begin']));
		// Ende
		$rows[$i]['Ende'] = ($fields['zeitraum'][0]['ende'] ? date("d.m.Y", strtotime($fields['zeitraum'][0]['ende'])) : '');
		// Semester
		$semesters = listSemesterInRange($fields['zeitraum'][0]['begin'], ($fields['zeitraum'][0]['ende'] ? $fields['zeitraum'][0]['ende'] : date(Ymd)));
		$rows[$i]['Semester'] = array();
		foreach ( $semesters as $semester ){
			$rows[$i]['Semester'][] = convertDateToSemester($semester['start'], 4);
		}
		// Fakultät
		$rows[$i]['Fakultät'] = $fields['fakultat'];
		// Einrichtung
		$rows[$i]['Einrichtung'] = array();
		if ($fields['einrichtung']){
			foreach ($fields['einrichtung'] as $key => $value ){
					$rows[$i]['Einrichtung'][] = trim($fields['einrichtung'][$key]['bezeichnung']);
			}
		} else {
			$rows[$i]['Einrichtung'][] = '';
		}
		// Ansprechpartner
		$rows[$i]['Ansprechpartner'] = array();
		foreach ($fields['ansprechpartner'] as $key => $value ){
			$rows[$i]['Ansprechpartner'][] = ($fields['ansprechpartner'][$key]['titel'] ? $fields['ansprechpartner'][$key]['titel'] . ' ' : '') . trim($fields['ansprechpartner'][$key]['vorname']) . ' ' . trim($fields['ansprechpartner'][$key]['nachname']) . ' <' . trim(strtolower($fields['ansprechpartner'][$key]['e-mail'])) . '>';
		}
		// Arbeitsbereich
		$rows[$i]['Arbeitsbereich'] = array();
		foreach ($fields['arbeitsbereich'] as $key => $value ){
			$rows[$i]['Arbeitsbereich'][] = $fields['arbeitsbereich'][$key]['arbeitsbereich'];
		}
		// Facharbeitsgruppe
		$rows[$i]['Facharbeitsgruppe'] = array();
		if ($fields['facharbeitsgruppe']){
			foreach ($fields['facharbeitsgruppe'] as $key => $value ){
				$rows[$i]['Facharbeitsgruppe'][] = $fields['facharbeitsgruppe'][$key]['arbeitsgruppe'];
			}
		} else {
			$rows[$i]['Facharbeitsgruppe'][] = '';
		}
		// Themenarbeitsgruppe
		$rows[$i]['Themenarbeitsgruppe'] = array();
		if ($fields['themenarbeitsgruppe']){
			foreach ($fields['themenarbeitsgruppe'] as $key => $value ){
				$rows[$i]['Themenarbeitsgruppe'][] = $fields['themenarbeitsgruppe'][$key]['arbeitsgruppe'];
			}
		} else {
			$rows[$i]['Themenarbeitsgruppe'][] = '';
		}
		// Betreuer
		$rows[$i]['Betreuer'] = array();
		foreach ($fields['betreuer'] as $key => $value ){
			$rows[$i]['Betreuer'][] = $fields['betreuer'][$key]['mitarbeiter'];
		}
		// Aufzeichnungsanzahl
		$rows[$i]['Aufzeichnungsanzahl'] = ($fields['aufzeichnungsanzahl'] ? $fields['aufzeichnungsanzahl'] : '');
		// Klausurverlauf
		$rows[$i]['Klausurverlauf'] = array();
		if ($fields['klausurverlauf']){
			foreach ($fields['klausurverlauf'] as $key => $value ){
				$rows[$i]['Klausurverlauf'][] = $fields['klausurverlauf'][$key]['klausurart'];
				$rows[$i]['Klausurverlauf'][] = date("d.m.Y", strtotime($fields['klausurverlauf'][$key]['klausurtermin']));
				$rows[$i]['Klausurverlauf'][] = $fields['klausurverlauf'][$key]['klausurteilnehmer'];
				$rows[$i]['Klausurverlauf'][] = $fields['klausurverlauf'][$key]['klausurdurchgange'];
			}
		} else {
			$rows[$i]['Klausurverlauf'][] = '';
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
