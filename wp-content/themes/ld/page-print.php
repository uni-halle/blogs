<?php
global $wpdb;

if (!empty($_GET['range'])) {
	// Unnötige Zeichen ersetzen
	$range = preg_replace('/[^\d,-]/', '', $_GET['range']);
	// Werte trennen
	$range = explode(',', $range);
	$include = array();
	// Werte durchlaufen
	for ($i = 0; $i < count($range); $i++) {
		// Wenn Bereich
		if (strpos($range[$i], '-')) {
			// Von und Bis
			$range[$i] = explode('-', $range[$i]);
			// Kleinster zuerst
			sort($range[$i]);
			// Nummer validieren
			if (!isValidArticleNumber($range[$i][0])) {
				for ($k = $range[$i][0]; ; $k++) {
					if (isValidArticleNumber($k)) {
						// Nummer aufstocken
						$range[$i][0] = $k;
						break;
					}
				}
			}
			// Nummer validieren
			if (!isValidArticleNumber($range[$i][1])) {
				for ($k = $range[$i][1]; ; $k--) {
					if (isValidArticleNumber($k)) {
						// Nummer reduzieren
						$range[$i][1] = $k;
						break;
					}
				}
			}
			// Posts im Bereich
			$result = $wpdb->get_results(
			"
				SELECT id
				FROM $wpdb->posts
				WHERE post_type = 'post'
				AND post_status = 'publish'
				AND id >= '" . articleNumberToPostIdentifier($range[$i][0]) . "'
				AND id <= '" . articleNumberToPostIdentifier($range[$i][1]) . "'
			"
			);
			// In Array schreiben
			foreach ($result as $row) {
				$include[] = $row->id;
			}
		// Wenn Einzelwert
		} else {
			// Nur validierte Nummern
			if (isValidArticleNumber($range[$i]) && (get_post_type(articleNumberToPostIdentifier($range[$i])) == 'post')) {
				$include[] = articleNumberToPostIdentifier($range[$i]);
			}
		}
	}
	// Doppelte Werte entfernen
	$include = array_unique($include);
	// Nach Wert sortieren
	sort($include);
	$include = implode(',', $include);
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ausgabe</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
		<style>
		body {
			font-family: 'Roboto', sans-serif;
			font-size: 0;
			margin: 0 0;
			padding: 0;
			width: 21cm;
	    height: 29.7cm;
		}
		.label {
			display: inline-block;
			overflow: hidden;
			margin: 0;
			padding: 0.25cm 0.6cm;
			width: 5.8cm;
			height: 3cm;
			font-size: 6pt;
			font-weight: 300;
		}
		#artikelnummer {
			font-weight: 300;
			font-size: 8pt;
		}
		#titel {
			font-size: 9pt;
			font-weight: 700;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		#zusatzinformationen {
			margin-top: 1.5pt;
		  line-height: 1.2em;
		  max-height: 2.2em;
			overflow: hidden;
		}
		#hinweis {
			display: inline-block;
			width: 2cm;
		}
		.qr {
			display: inline-block;
		}
		.qr img {
			display: block;
			margin-top: 5pt;
			margin-bottom: 2pt;
			margin-right: 5pt;
			width: 1.5cm;
			height: 1.5cm;
		}
		.attribut {
			display: inline;
			margin-left: 2.5pt;
			margin-right: 1.5pt;
		}
		.attribut:first-child {
			margin-left: 0;
		}
		.wert {
			display: inline;
			font-weight: 500;
		}
		@page {
			margin: 0.85cm 0;
			size: portrait;
			size: A4;
		}
		</style>
  </head>
  <body>
<?php
if (!empty($_GET['offset'])) {
	$offset = preg_replace('/[^\d]/', '', $_GET['offset']);
	for ($m = 0; $m < $offset; $m++) {
		echo '<div class="label"></div>';
	}
}
$args = array (
	'posts_per_page' => -1,
	'include' => $include
);
$posts = get_posts( $args );
foreach ( $posts as $post ) {
	setup_postdata ( $post );
	$fields = get_fields();
?>
<div class="label">
		<div id="titel"><span id="artikelnummer">#<?php echo $fields['artikelnummer']; ?></span> <?php echo ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']); ?></div>
		<div id="zusatzinformationen"><?php
		if ($fields['kategorie'] == 'Literatur') {
			// Autoren
			$autoren = array();
			foreach ($fields['autoren'] as $key => $value) { $autoren[] = $fields['autoren'][$key]['name']; }
			echo '<span class="attribut">' . (count($autoren) > 1 ? 'Autoren' : 'Autor') . '</span><span class="wert">' . implode(', ', $autoren) . '</span>';
			// Untertitel
			if ($fields['untertitel']) {
			echo '<span class="attribut">Untertitel</span><span class="wert">' . $fields['untertitel'] . '</span>';
      }
			// Erscheinungsjahr
			if ($fields['erscheinungsjahr']) {
				echo '<span class="attribut">Jahr</span><span class="wert">' . $fields['erscheinungsjahr'] . '</span>';
			}
			// Zusatzinformationen
			foreach ($fields['zusatzinformationen'] as $key => $value) {
				echo '<span class="attribut">' . $fields['zusatzinformationen'][$key]['attribut'] . '</span><span class="wert">' . $fields['zusatzinformationen'][$key]['wert'] . '</span>';
			};
		} else {
			// Zusatzinformationen
			foreach ($fields['zusatzinformationen'] as $key => $value) {
				echo '<span class="attribut">' . $fields['zusatzinformationen'][$key]['attribut'] . '</span><span class="wert">' . $fields['zusatzinformationen'][$key]['wert'] . '</span>';
			};
		}
?>
		</div>
		<div class="qr">
			<img src="http://chart.apis.google.com/chart?chs=500x500&cht=qr&chld=L|0&chl=https://stuff.llz.uni-halle.de/checkout?i=<?php echo $fields['artikelnummer']; ?>">
			Ausleihe
		</div>
		<div class="qr">
			<img src="http://chart.apis.google.com/chart?chs=500x500&cht=qr&chld=L|0&chl=https://stuff.llz.uni-halle.de/checkin?i=<?php echo $fields['artikelnummer']; ?>">
			Rücknahme
		</div>
		<div id="hinweis">Artikel scannen oder mit Artikelnummer <span class="wert"><?php echo $fields['artikelnummer']; ?></span> auf Webseite stuff.llz.uni-halle.de ausleihen.<br><br></div>
</div>
<?php
}
wp_reset_postdata();
?>
	</body>
</html>
