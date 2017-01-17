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
?>
