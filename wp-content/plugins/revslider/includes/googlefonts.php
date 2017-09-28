<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 * @since 	  5.1.0
 * @lastfetch 22.05.2017
 */
 
if( !defined( 'ABSPATH') ) exit();

/**
*** CREATED WITH SCRIPT SNIPPET AND DATA TAKEN FROM https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&fields=items(family%2Csubsets%2Cvariants)&key={YOUR_API_KEY}

$list = json_decode($list_raw, true);
$list = $list['items'];

echo '<pre>';
foreach($list as $l){
	echo "'".$l['family'] ."' => array("."\n";
	echo "'variants' => array(";
	foreach($l['variants'] as $k => $v){
		if($k > 0) echo ", ";
		if($v == 'regular') $v = '400';
		echo "'".$v."'";
	}
	echo "),\n";
	echo "'subsets' => array(";
	foreach($l['subsets'] as $k => $v){
		if($k > 0) echo ", ";
		echo "'".$v."'";
	}
	echo ")\n),\n";
}
echo '</pre>';
**/

$googlefonts = array(
'Roboto' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '900', '900italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Open Sans' => array(
'variants' => array('300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Lato' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Slabo 27px' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Roboto Condensed' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Oswald' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'cyrillic', 'latin')
),
'Source Sans Pro' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Montserrat' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Raleway' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'PT Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Roboto Slab' => array(
'variants' => array('100', '300', '400', '700'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Merriweather' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Open Sans Condensed' => array(
'variants' => array('300', '300italic', '700'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Lora' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Droid Sans' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Ubuntu' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Droid Serif' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Arimo' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Playfair Display' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Noto Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'devanagari', 'cyrillic', 'latin')
),
'PT Serif' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Titillium Web' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '900'),
'subsets' => array('latin-ext', 'latin')
),
'PT Sans Narrow' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Poppins' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Muli' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Indie Flower' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Dosis' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'latin')
),
'Bitter' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Hind' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Fjalla One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Oxygen' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Noto Serif' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Cabin' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Inconsolata' => array(
'variants' => array('400', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Libre Baskerville' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Arvo' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Lobster' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'cyrillic', 'latin')
),
'Baloo Bhaina' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'oriya', 'latin')
),
'Anton' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Bree Serif' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Nunito' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Crimson Text' => array(
'variants' => array('400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin')
),
'Roboto Mono' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Yanone Kaffeesatz' => array(
'variants' => array('200', '300', '400', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'cyrillic', 'latin')
),
'Catamaran' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('tamil', 'latin-ext', 'latin')
),
'Fira Sans' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Merriweather Sans' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '800', '800italic'),
'subsets' => array('latin-ext', 'latin')
),
'Josefin Sans' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Exo 2' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Asap' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Abel' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Pacifico' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Quicksand' => array(
'variants' => array('300', '400', '500', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Abril Fatface' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Gloria Hallelujah' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ubuntu Condensed' => array(
'variants' => array('400'),
'subsets' => array('greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Karla' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Work Sans' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Archivo Narrow' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Varela Round' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'vietnamese', 'latin-ext', 'latin')
),
'Rubik' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '900', '900italic'),
'subsets' => array('hebrew', 'latin-ext', 'cyrillic', 'latin')
),
'Signika' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Alegreya' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Play' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Shadows Into Light' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Dancing Script' => array(
'variants' => array('400', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Cuprum' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Questrial' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Francois One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Maven Pro' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Exo' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Libre Franklin' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'PT Sans Caption' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Acme' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'EB Garamond' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Pathway Gothic One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Amatic SC' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'vietnamese', 'latin-ext', 'latin')
),
'Archivo Black' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Architects Daughter' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Vollkorn' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Ropa Sans' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Crete Round' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Rokkitt' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Istok Web' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Source Serif Pro' => array(
'variants' => array('400', '600', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Khula' => array(
'variants' => array('300', '400', '600', '700', '800'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Hammersmith One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Source Code Pro' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Josefin Slab' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin')
),
'Lobster Two' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Orbitron' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('latin')
),
'Patua One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Alegreya Sans' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Comfortaa' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Passion One' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Russo One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Satisfy' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'News Cycle' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Kanit' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Cinzel' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin')
),
'ABeeZee' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Righteous' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Old Standard TT' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Playfair Display SC' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Quattrocento Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Rajdhani' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Noticia Text' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'BenchNine' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Monda' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Yellowtail' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Philosopher' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'cyrillic', 'latin')
),
'Gudea' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Poiret One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Chewy' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cookie' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Kaushan Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Quattrocento' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Volkhov' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Cormorant Garamond' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Courgette' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Arapey' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Yantramanav' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Pontano Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Armata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Domine' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Cabin Condensed' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Khand' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Permanent Marker' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Teko' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Sanchez' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Droid Sans Mono' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cantarell' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Alfa Slab One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Cardo' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('greek-ext', 'latin-ext', 'greek', 'latin')
),
'Ruda' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Tinos' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Economica' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Audiowide' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Handlee' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Kalam' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Shadows Into Light Two' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Concert One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Boogaloo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Kreon' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin')
),
'Ek Mukta' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Cairo' => array(
'variants' => array('200', '300', '400', '600', '700', '900'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Days One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Covered By Your Grace' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Vidaloka' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Amiri' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('arabic', 'latin')
),
'Bad Script' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin')
),
'Tangerine' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Hind Siliguri' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'bengali', 'latin')
),
'Great Vibes' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Antic Slab' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Damion' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nunito Sans' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Shrikhand' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Gentium Book Basic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Patrick Hand' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Coming Soon' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Didact Gothic' => array(
'variants' => array('400'),
'subsets' => array('greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Neuton' => array(
'variants' => array('200', '300', '400', 'italic', '700', '800'),
'subsets' => array('latin-ext', 'latin')
),
'Playball' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sigmar One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Fredoka One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Heebo' => array(
'variants' => array('100', '300', '400', '500', '700', '800', '900'),
'subsets' => array('hebrew', 'latin')
),
'Rock Salt' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Lusitana' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Sacramento' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Marck Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Paytone One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Clicker Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sintony' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Bevan' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Coda' => array(
'variants' => array('400', '800'),
'subsets' => array('latin-ext', 'latin')
),
'Neucha' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin')
),
'Alice' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'cyrillic', 'latin')
),
'Sorts Mill Goudy' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Amaranth' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Luckiest Guy' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Rambla' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Frank Ruhl Libre' => array(
'variants' => array('300', '400', '500', '700', '900'),
'subsets' => array('hebrew', 'latin-ext', 'latin')
),
'Glegoo' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Alegreya Sans SC' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Gentium Basic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Jura' => array(
'variants' => array('300', '400', '500', '600'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Julius Sans One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Actor' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sarala' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Bangers' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Martel' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Varela' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Caveat' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Special Elite' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Oleo Script' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Signika Negative' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Rochester' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'PT Mono' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Pragati Narrow' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Share' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Adamina' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Syncopate' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Homemade Apple' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Copse' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Prompt' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Love Ya Like A Sister' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nobile' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Nothing You Could Do' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Unica One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Fauna One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Kameron' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Hind Vadodara' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Calligraffitti' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ultra' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Montserrat Alternates' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Convergence' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Molengo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Alef' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'latin')
),
'Electrolize' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Arima Madurai' => array(
'variants' => array('100', '200', '300', '400', '500', '700', '800', '900'),
'subsets' => array('vietnamese', 'tamil', 'latin-ext', 'latin')
),
'Scada' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Cantata One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Prata' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'cyrillic', 'latin')
),
'Jaldi' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Rancho' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Just Another Hand' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Monoton' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fugaz One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Antic' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Advent Pro' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'greek', 'latin')
),
'Yesteryear' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Candal' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Pinyon Script' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ceviche One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Bubblegum Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'PT Serif Caption' => array(
'variants' => array('400', 'italic'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Carme' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Allerta' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fira Sans Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Niconne' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Aldrich' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Gochi Hand' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Squada One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Changa' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Ubuntu Mono' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Average' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Allura' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Alex Brush' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Quantico' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Enriqueta' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Basic' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Contrail One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Petit Formal Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Chivo' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Michroma' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cherry Cream Soda' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Freckle Face' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Average Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Tauri' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Forum' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Cabin Sketch' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Voltaire' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Allerta Stencil' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Rammetto One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Press Start 2P' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Nixie One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Overlock' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Assistant' => array(
'variants' => array('200', '300', '400', '600', '700', '800'),
'subsets' => array('hebrew', 'latin')
),
'Metamorphous' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Changa One' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Marmelad' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Reenie Beanie' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Marcellus SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Magra' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Parisienne' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Fanwood Text' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Marvel' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Ruslan Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Limelight' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Marcellus' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Viga' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Homenaje' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Rufina' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Cousine' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Hind Guntur' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'telugu', 'latin')
),
'Spinnaker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Jockey One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Arbutus Slab' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Oranienbaum' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Annie Use Your Telescope' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Merienda' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Slabo 13px' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Telex' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Grand Hotel' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Lustria' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Walter Turncoat' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Lalezar' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin')
),
'Leckerli One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fontdiner Swanky' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Graduate' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cutive Mono' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Cutive' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'VT323' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Eczar' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Carrois Gothic' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Crafty Girls' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Aclonica' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Halant' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Andika' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Metrophobic' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Carter One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Six Caps' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Doppio One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Goudy Bookletter 1911' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Racing Sans One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Italianno' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sue Ellen Francisco' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fredericka the Great' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Baloo' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'devanagari', 'latin')
),
'Lateef' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin')
),
'Alegreya SC' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Schoolbell' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ranga' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Radley' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'GFS Didot' => array(
'variants' => array('400'),
'subsets' => array('greek')
),
'Zeyada' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Hanuman' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer')
),
'Anonymous Pro' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'greek', 'cyrillic', 'latin')
),
'Arsenal' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Karma' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Puritan' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Black Ops One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Oregano' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Space Mono' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Itim' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Poller One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Caveat Brush' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Port Lligat Slab' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Martel Sans' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Cinzel Decorative' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin')
),
'Give You Glory' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ovo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Caudex' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'latin-ext', 'greek', 'latin')
),
'Rosario' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Berkshire Swash' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mr Dafoe' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Londrina Solid' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Lilita One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Waiting for the Sunrise' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cambay' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Trirong' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'The Girl Next Door' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Crushed' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nova Square' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Slackey' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Gilda Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Cambo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Gruppo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'IM Fell Double Pica' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Kelly Slab' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Coustard' => array(
'variants' => array('400', '900'),
'subsets' => array('latin')
),
'Anaheim' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Prosto One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Chelsea Market' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Allan' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Happy Monkey' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Inder' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Baumans' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Belleza' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Titan One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'IM Fell English' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Merienda One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Wire One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Orienta' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Quando' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Taviraj' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Tenor Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Pridi' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Pompiere' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Frijole' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Gravitas One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Palanquin' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Kranky' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Delius' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Mouse Memoirs' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Baloo Paaji' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'gurmukhi', 'latin')
),
'Mate' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Oleo Script Swash Caps' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Capriola' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mr De Haviland' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Yeseva One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Bungee Inline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Mako' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Duru Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mountains of Christmas' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Short Stack' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Lekton' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Tienne' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin')
),
'IM Fell English SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Prociono' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Chau Philomene One' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Reem Kufi' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin')
),
'Montserrat Subrayada' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Holtwood One SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Biryani' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Sofia' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Montez' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Trocchi' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rationale' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Abhaya Libre' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('sinhala', 'latin-ext', 'latin')
),
'Alike' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Vast Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Arizonia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Palanquin Dark' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Oxygen Mono' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Fenix' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Unkempt' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Herr Von Muellerhoff' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Timmana' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Andada' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Carrois Gothic SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Averia Sans Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Aladin' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Yrsa' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Judson' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Lily Script One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Bentham' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Averia Serif Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Strait' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Poly' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Fira Sans Extra Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Just Me Again Down Here' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Scheherazade' => array(
'variants' => array('400', '700'),
'subsets' => array('arabic', 'latin')
),
'Megrim' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Lemon' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'La Belle Aurore' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Share Tech Mono' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Norican' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Gabriela' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'cyrillic', 'latin')
),
'Brawler' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sniglet' => array(
'variants' => array('400', '800'),
'subsets' => array('latin-ext', 'latin')
),
'Patrick Hand SC' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Iceland' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Amethysta' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Bowlby One SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Knewave' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Corben' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Seaweed Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Cormorant' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Kristi' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Qwigley' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mallanna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Belgrano' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sumana' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Headland One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'UnifrakturMaguntia' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Bowlby One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Miriam Libre' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'latin-ext', 'latin')
),
'Unna' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Gafata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Expletus Sans' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin')
),
'Fondamento' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Skranji' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Euphoria Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'IM Fell DW Pica' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Federo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Imprima' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mitr' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Stardos Stencil' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Bilbo Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Engagement' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Raleway Dots' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Delius Unicase' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Life Savers' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Salsa' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Voces' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rouge Script' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Kotta One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Meddon' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Vesper Libre' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Faster One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'David Libre' => array(
'variants' => array('400', '500', '700'),
'subsets' => array('hebrew', 'vietnamese', 'latin-ext', 'latin')
),
'Cherry Swash' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Loved by the King' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Macondo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fira Mono' => array(
'variants' => array('400', '500', '700'),
'subsets' => array('greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'cyrillic', 'latin')
),
'Buenard' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Wendy One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Kurale' => array(
'variants' => array('400'),
'subsets' => array('cyrillic-ext', 'latin-ext', 'devanagari', 'cyrillic', 'latin')
),
'Finger Paint' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Chonburi' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Mada' => array(
'variants' => array('300', '400', '500', '900'),
'subsets' => array('arabic', 'latin')
),
'Pavanam' => array(
'variants' => array('400'),
'subsets' => array('tamil', 'latin-ext', 'latin')
),
'Dawning of a New Day' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Balthazar' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Suranna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Shojumaru' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Habibi' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Shanti' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Over the Rainbow' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Mystery Quest' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Podkova' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Italiana' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ledger' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Mukta Vaani' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Sansita' => array(
'variants' => array('400', 'italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Baloo Tamma' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'kannada')
),
'Delius Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Vampiro One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Stalemate' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Revalia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Aguafina Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Quintessential' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Englebert' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Khmer' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Nova Mono' => array(
'variants' => array('400'),
'subsets' => array('greek', 'latin')
),
'Maitree' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Cedarville Cursive' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Harmattan' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin')
),
'Medula One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ruluko' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Aref Ruqaa' => array(
'variants' => array('400', '700'),
'subsets' => array('arabic', 'latin')
),
'Amiko' => array(
'variants' => array('400', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Ravi Prakash' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Geo' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Kadwa' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin')
),
'Gurajada' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Rye' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Flamenco' => array(
'variants' => array('300', '400'),
'subsets' => array('latin')
),
'League Script' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Overpass' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Creepster' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Athiti' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Wallpoet' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Simonetta' => array(
'variants' => array('400', 'italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin')
),
'Donegal One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Barrio' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'New Rocker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mate SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'McLaren' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sail' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Codystar' => array(
'variants' => array('300', '400'),
'subsets' => array('latin-ext', 'latin')
),
'Ramabhadra' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Kite One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Chicle' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Coda Caption' => array(
'variants' => array('800'),
'subsets' => array('latin-ext', 'latin')
),
'Condiment' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Kumar One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Eagle Lake' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Bungee' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Denk One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Suez One' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'latin-ext', 'latin')
),
'Cantora One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Esteban' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'NTR' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Secular One' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'latin-ext', 'latin')
),
'Maiden Orange' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Amarante' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sunshiney' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Share Tech' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Amatica SC' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'latin-ext', 'latin')
),
'Bungee Shade' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'El Messiri' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('arabic', 'cyrillic', 'latin')
),
'Krona One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Text Me One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'IM Fell French Canon' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Bubbler One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rosarivo' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin')
),
'Dynalight' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Numans' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Milonga' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rubik Mono One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Dorsa' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Stoke' => array(
'variants' => array('300', '400'),
'subsets' => array('latin-ext', 'latin')
),
'Stint Ultra Expanded' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Fjord One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Mandali' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'IM Fell DW Pica SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Junge' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Miniver' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Artifika' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sonsie One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Almendra' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin')
),
'Mrs Saint Delafield' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Proza Libre' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('latin-ext', 'latin')
),
'Vibur' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Tulpen One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Elsie' => array(
'variants' => array('400', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Sarpanch' => array(
'variants' => array('400', '500', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Battambang' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer')
),
'IM Fell Great Primer SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'BioRhyme' => array(
'variants' => array('200', '300', '400', '700', '800'),
'subsets' => array('latin-ext', 'latin')
),
'Paprika' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Alike Angular' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Arya' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Rozha One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Inika' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Glass Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Ribeye' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'UnifrakturCook' => array(
'variants' => array('700'),
'subsets' => array('latin')
),
'Sancreek' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Averia Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin')
),
'Antic Didone' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Spirax' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'IM Fell Double Pica SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Overlock SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Stint Ultra Condensed' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Offside' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'IM Fell Great Primer' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Kavoon' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Asul' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Trade Winds' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cagliostro' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nova Round' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nosifer' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Croissant One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Buda' => array(
'variants' => array('300'),
'subsets' => array('latin')
),
'Sarina' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Petrona' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Pattaya' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'cyrillic', 'latin', 'thai')
),
'Dekko' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Hind Madurai' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('tamil', 'latin-ext', 'latin')
),
'IM Fell French Canon SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Cormorant Infant' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Monsieur La Doulaise' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Lemonada' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin')
),
'Baloo Chettan' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'malayalam')
),
'Wellfleet' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Emilys Candy' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sriracha' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'thai')
),
'Angkor' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Linden Hill' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin')
),
'Pirata One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Bigshot One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Monofett' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Della Respira' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Port Lligat Sans' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Henny Penny' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Laila' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Meie Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Trykker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Ruthie' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Swanky and Moo Moo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Irish Grover' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nova Slim' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Bilbo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Julee' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Diplomata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'GFS Neohellenic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek')
),
'Peralta' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Joti One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Redressed' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Inknut Antiqua' => array(
'variants' => array('300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Londrina Outline' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'MedievalSharp' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Nokora' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer')
),
'Lovers Quarrel' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Baloo Thambi' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'tamil', 'latin-ext', 'latin')
),
'Cormorant SC' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Modak' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Oldenburg' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Autour One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sura' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Fresca' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Montaga' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Scope One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Lancelot' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Jolly Lodger' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Nova Oval' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Spicy Rice' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Koulen' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Snippet' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nova Flat' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Griffy' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Iceberg' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Ranchers' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Galindo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Purple Purse' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Piedra' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Akronim' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rhodium Libre' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Pangolin' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Bokor' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Uncial Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Germania One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Dr Sugiyama' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mirza' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Trochut' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin')
),
'Passero One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Kenia' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sahitya' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin')
),
'Rakkas' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Plaster' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Snowburst One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Underdog' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Modern Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Keania One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Galdeano' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Original Surfer' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Chathura' => array(
'variants' => array('100', '300', '400', '700', '800'),
'subsets' => array('telugu', 'latin')
),
'Rum Raisin' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Amita' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Ramaraja' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Chango' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Molle' => array(
'variants' => array('italic'),
'subsets' => array('latin-ext', 'latin')
),
'Mrs Sheppards' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Averia Gruesa Libre' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Suwannaphum' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Odor Mean Chey' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Ewert' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Smythe' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Asset' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Content' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer')
),
'Astloch' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Caesar Dressing' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Miltonian Tattoo' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Diplomata SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Jim Nightshade' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Nova Script' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Jacques Francois Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Bayon' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Goblin One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Siemreap' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Atomic Age' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Baloo Bhai' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'gujarati', 'latin')
),
'Ribeye Marrow' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Yatra One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Farsan' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'gujarati', 'latin')
),
'Almendra SC' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Elsie Swash Caps' => array(
'variants' => array('400', '900'),
'subsets' => array('latin-ext', 'latin')
),
'Gorditas' => array(
'variants' => array('400', '700'),
'subsets' => array('latin')
),
'Cormorant Upright' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Seymour One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Fascinate' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Tillana' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Miss Fajardose' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Princess Sofia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Jacques Francois' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Margarine' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Romanesco' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Smokum' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Geostar Fill' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Miltonian' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Metal Mania' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Rasa' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Metal' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Arbutus' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Atma' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'bengali', 'latin')
),
'Londrina Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Warnes' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Moul' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Coiny' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'tamil', 'latin-ext', 'latin')
),
'Felipa' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Freehand' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Londrina Sketch' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Devonshire' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sirin Stencil' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Fascinate Inline' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Asar' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'devanagari', 'latin')
),
'Galada' => array(
'variants' => array('400'),
'subsets' => array('bengali', 'latin')
),
'Bonbon' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Combo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Mogra' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'Marko One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Macondo Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Nova Cut' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sevillana' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Butterfly Kids' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Geostar' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Sree Krushnadevaraya' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Ruge Boogie' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Flavors' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Butcherman' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Sofadi One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Unlock' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Chenla' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Preahvihear' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Stalinist One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'cyrillic', 'latin')
),
'Lakki Reddy' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Eater' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Chela One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Bigelow Rules' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Jomhuria' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Risque' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Taprom' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Erica One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Federant' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Aubrey' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Baloo Da' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'bengali', 'latin')
),
'Gidugu' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Almendra Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Dangrek' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Katibeh' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin')
),
'Kdam Thmor' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Supermercado One' => array(
'variants' => array('400'),
'subsets' => array('latin')
),
'Emblema One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Meera Inimai' => array(
'variants' => array('400'),
'subsets' => array('tamil', 'latin')
),
'Tenali Ramakrishna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Mr Bedfort' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Fruktur' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Padauk' => array(
'variants' => array('400', '700'),
'subsets' => array('myanmar', 'latin')
),
'Cormorant Unicase' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'cyrillic-ext', 'latin-ext', 'cyrillic', 'latin')
),
'Kantumruy' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('khmer')
),
'Fasthand' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Bungee Hairline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Hanalei Fill' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Hanalei' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Moulpali' => array(
'variants' => array('400'),
'subsets' => array('khmer')
),
'Bahiana' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin')
),
'Dhurjati' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Bungee Outline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin')
),
'Peddana' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Suravaram' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin')
),
'Overpass Mono' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin')
),
'Kavivanar' => array(
'variants' => array('400'),
'subsets' => array('tamil', 'latin-ext', 'latin')
),
'Kumar One Outline' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'gujarati', 'latin')
),
'BioRhyme Expanded' => array(
'variants' => array('200', '300', '400', '700', '800'),
'subsets' => array('latin-ext', 'latin')
)
);

ksort($googlefonts);
?>