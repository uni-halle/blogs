<?php

// Als Parameter übergebene Filter
$GLOBALS['filterParameter'] = array(
	'kategorie' => 'kategorie',
	'facharbeitsgruppe_0_arbeitsgruppe' => 'facharbeitsgruppe',
	'status' => 'status',
	'veroffentlichung' => 'veroffentlichung'
);

// Beiträge in Projekte umbenennen
function renamePostLabels() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Projekte';
    $labels->singular_name = 'Projekt';
		$labels->menu_name = 'Projekte';
    $labels->name_admin_bar = 'Projekt';
    $labels->add_new = 'Erstellen';
    $labels->add_new_item = 'Neues Projekt erstellen';
    $labels->edit_item = 'Projekt bearbeiten';
    $labels->new_item = 'Neues Projekt';
    $labels->view_item = 'Projekt anzeigen';
    $labels->search_items = 'Projekte durchsuchen';
    $labels->not_found = 'Keine Projekte gefunden.';
    $labels->not_found_in_trash = 'Keine Projekte im Papierkorb gefunden.';
    $labels->all_items = 'Alle Projekte';

}
add_action( 'init', 'renamePostLabels' );

// Aktionen aus Artikelliste entfernen
function removeListActions( $actions ) {
	unset($actions['inline hide-if-no-js']);
	unset($actions['edit_as_new_draft']);
	return $actions;
}
add_filter( 'post_row_actions' , 'removeListActions' , 10, 1);

// Custom Fields durchsuchen
/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
/* function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' ); */
/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
/* function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' ); */
/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
/* function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' ); */

// Semesterliste generieren
function listSemesterInRange($startDate, $endDate) {
	$semesterList = array();
	$loopDate = $startDate;
	$loopYear = substr($loopDate, 0, 4);
	$loopMonth = substr($loopDate, 4, 2);
	$i = 0;
	while ($loopDate <= $endDate) {
		if ($loopMonth <= '03') {
			// Wintersemester
			$semesterList[$i]['start'] = ($loopYear - 1) . '10' . '01';
			$semesterList[$i]['end'] = $loopYear . '03' . '31';
			$loopMonth = '03';
		} elseif ($loopMonth <= '09') {
			// Sommersemester
			$semesterList[$i]['start'] = $loopYear . '04' . '01';
			$semesterList[$i]['end'] = $loopYear . '09' . '30';
			$loopMonth = '09';
		} else {
			// Wintersemester
			$semesterList[$i]['start'] = $loopYear . '10' . '01';
			$semesterList[$i]['end'] = ($loopYear + 1) . '03' . '31';
			$loopYear++;
			$loopMonth = '03';
		}
		$loopMonth++;
		$loopDate = $loopYear . $loopMonth . '01';
		$i++;
	}
	return $semesterList;
}

// Datum in Semester umwandeln
function convertDateToSemester($date, $mode = NULL) {
	$semYear = $date[2].$date[3];
	$semMonth = $date[4].$date[5];

	if ($semMonth >= '04' AND $semMonth <= '09') {
		$semSeason = 's';
	} elseif ($semMonth >= '10' OR $semMonth <= '03') {
		$semSeason = 'w';
	} else {
		$semSeason = 'x';
	}

	if ($semMonth >= '01' AND $semMonth <= '03') {
		$semYear--;
	}

	switch ($mode) {
		case 0:
			return array('d' => $semSeason.$semYear, 's' => $semSeason, 'y' => intval($semYear));
			break;

		case 1:
			return $semSeason.$semYear;
			break;

		case 2:
			return $semSeason;
			break;

		case 3:
			return intval($semYear);
			break;

		case 4:
			if ($semSeason == 'w') {
				$string = 'Wintersemester ' . $semYear . '/' . ($semYear + 1);
			} elseif ($semSeason == 's') {
				$string = 'Sommersemester ' . $semYear;
			}
			return $string;
			break;

		default:
			return array('d' => $semSeason.$semYear, 's' => $semSeason, 'y' => intval($semYear));
			break;
	}
}

// Semester ausschreiben
function makeSemesterKeysReadable($return) {
	foreach ($return as $key => $value) {
		if ($key[0] == 'w') {
			$year = $key[1].$key[2];
			$string = 'Wintersemester '.$year.'/'.($year + 1);
			unset($return[$key]);
			$return[$string] = $value;
		} elseif ($key[0] == 's') {
			$string = 'Sommersemester '.$key[1].$key[2];
			unset($return[$key]);
			$return[$string] = $value;
		}
	}
	return $return;
}

// Kummulierte Semesterdaten
function cumulateSemester($arr) {
	$j = 1;
  foreach ($arr as $semester => $count) {
  	if ($j > 1) {
    	$arr[$semester] += $prevSemesterCount;
    }
    $prevSemesterCount = $arr[$semester];
    $j++;
  }
  return $arr;
}

// Zeilenumbrüche aus Text entfernen
function removeReturn ($text) {
	return str_replace(array("\r\n", "\n", chr(13)), ' ', $text);
}

// Rekursive Suche nach einem Schlüssel in einem multidimensionalem Array
function getArrayKey($search, $array) {
    foreach($array as $key => $values) {
        if(in_array($search, $values)) {
            return $key;
        }
    }
    return false;
}

// Überflüssige Tabellenspalten im WP-Backend entfernen
function removeDefaultWPColumns($defaults) {
    unset($defaults['categories']);
    unset($defaults['comments']);
    unset($defaults['tags']);
    unset($defaults['date']);

    return $defaults;
}

add_filter('manage_post_posts_columns', 'removedefaultWPColumns');

// Neue Spalten anlegen
function projectNewColumnHeads($defaults) {
    $defaults['projectCategory'] = 'Kategorie';
    $defaults['projectWorkgroup'] = 'Facharbeitsgruppe';

    return $defaults;
}

add_filter('manage_posts_columns', 'projectNewColumnHeads');

// Die neuen Spalten auch mit Inhalt füllen
function projectNewColumnContent($column, $postID) {
    if ($column == 'projectCategory') {
        $cat = get_post_meta($postID, 'kategorie', 1);
        if ($cat) {
            echo $cat;
        }
    }
    if ($column == 'projectWorkgroup') {
        $workgroup = get_post_meta($postID, 'facharbeitsgruppe_0_arbeitsgruppe', 1);
        if ($workgroup) {
            echo $workgroup;
        }
    }
}
add_action('manage_posts_custom_column', 'projectNewColumnContent', 10, 2);

// Einige der neuen Spalten als sortierbar kennzeichnen
function makeProjectColumnsSortable($sortable_columns) {
    $sortable_columns['projectCategory'] = 'projectCategory';

    return $sortable_columns;
}

add_filter('manage_edit-post_sortable_columns', 'makeProjectColumnsSortable');

// Die als "sortierbar" gekennzeichneten Spalten sortierbar machen
function sortProjectColumns($query) {
    if ($query->is_main_query() && ($orderby = $query->get('orderby'))) {
        switch($orderby) {
            case 'projectCategory':
                $query->set( 'meta_key', 'kategorie' );
                $query->set( 'orderby', 'meta_value' );
                break;
        }

    }
}
add_action('pre_get_posts', 'sortProjectColumns', 1);

?>
