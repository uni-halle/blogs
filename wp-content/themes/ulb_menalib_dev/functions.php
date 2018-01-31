<?php


// parent-style import; current best practice
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', PHP_INT_MAX);

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
    wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
add_filter('body_class', 'blacklist_body_class', 20, 2);
function blacklist_body_class($wp_classes, $extra_classes) {
if( is_single() || is_page() ) :
// List of the classes to remove from the WP generated classes
$blacklist = array('singular');
// Filter the body classes
  foreach( $blacklist as $val ) {
    if (!in_array($val, $wp_classes)) : continue;
    else:
      foreach($wp_classes as $key => $value) {
      if ($value == $val) unset($wp_classes[$key]);
      }
    endif;
  }
endif;   // Add the extra classes back untouched
return array_merge($wp_classes, (array) $extra_classes);
}

add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
	register_sidebar( array(
			'id' => 'ulb_green',
			'name' => __( 'ULB gruen' ),
			'description' => __( 'The right sidebar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);

	/*
		2018_01_29 HenryG.

		PageTopWidget
		
	 */

	register_sidebar( array(
		'id' => 'top_widget',
		'name' => __( 'PageTopWidget' ),
		'description' => __( 'Widget on top of the page area' ),
		'before_widget' => '<div id="page-top-widget" class="widget widget-area page-top-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
		)
	);



}
function de_menalib_kit_search() {
return <<<FORMULAR
<form action="https://kvk.bibliothek.kit.edu/hylib-bin/kvk/nph-kvk2.cgi" method="GET" name="KVK_Suchmaske"><input name="maske" type="HIDDEN" value="SSG623" /><INPUT TYPE="HIDDEN" NAME="input-charset" VALUE="utf-8"><INPUT TYPE="HIDDEN" NAME="header" VALUE="http://kvk.bibliothek.kit.edu/asset/html/header.html">
<INPUT TYPE="HIDDEN" NAME="footer" VALUE="http://kvk.bibliothek.kit.edu/asset/html/footer.html">
<input TYPE="HIDDEN" NAME="lang" VALUE="de">
<h2>Kataloge auswählen</h2>
<table>
<tbody>
<tr>
<td><script type="text/javascript"><!--
        document.write("<font face=\"arial,helvetica\" size=\"2\" color=\"#000000\">")
		document.write("Auswahl")
		document.write("</font></td>")
		document.write("<td nowrap>")
		document.write("<font face=\"arial,helvetica\" size=\"2\">")
		document.write("<input class=\"button\" type=\"button\" value=\"  Alle  \" onClick=\"check_all(this.form, true)\" title=\"Alle Kataloge ausw�hlen\">\n")
		document.write("<input class=\"button\" type=\"button\" value=\"Einzelne\" onClick=\"check_all(this.form, false)\" title=\"Einzelne Kataloge ausw�hlen\">")
		document.write("</font>")
		//--></script>
</tr>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_TUE" /><a href="http://www.ub.uni-tuebingen.de/home.html">Universitätsbibliothek Tübingen</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_HALLE" /> <a href="http://lhhal.gbv.de/DB=1/LNG=DU/">Universitäts- und Landesbibliothek Sachsen-Anhalt</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="IBLK_OPAC" /> <a href="http://www.ubka.uni-karlsruhe.de/hylib/iblk/">Datenbasis IBLK</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="VKNL_FES" /> <a href="http://library.fes.de/cgi-bin/populo/ssgvor.pl?db=ssgvor&amp;t_maske">Friedrich Ebert Stiftung</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_ZMO_BERLIN" /> <a href="http://195.37.93.199/biblio/main.htm">Zentrum Moderner Orient, Berlin</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_BEIRUT" /> <a href="http://vzlbs2.gbv.de/DB=49/">Orient-Institut Beirut</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_ISTANBUL" /> <a href="http://vzlbs2.gbv.de/DB=47/">Orient-Institut Istanbul</a></td>
</tr>
</tbody>
</table>
<h2>Suchbegriffe eingeben</h2>
<table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#F2F2F2"><!-- Tabelle : Suchbegriffe: Beginn -->
<tr align="left" valign="top">
<td>Freitext</td>    
<td><INPUT SIZE=18 TYPE="text" NAME="ALL" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Titel</td>
<td><INPUT SIZE=18 TYPE="text" NAME="TI" id="menainput"></td>
<td>Jahr</td>
<td><INPUT SIZE=18 TYPE="text" NAME="PY" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Autor</td>
<td><INPUT SIZE=18 TYPE="text" NAME="AU" id="menainput"></td>
<td>ISBN</td>
<td><INPUT SIZE=18 TYPE="text" NAME="SB" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>K&ouml;rperschaft</td>
<td><INPUT SIZE=18 TYPE="text" NAME="CI" id="menainput"></td>
<td>ISSN </td>
<td><INPUT SIZE=18 TYPE="text" NAME="SS" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Schlagwort</td>
<td><INPUT SIZE=18 TYPE="text" NAME="ST" id="menainput"></td>
<td>Verlag</td>
<td><INPUT SIZE=18 TYPE="text" NAME="PU" id="menainput">
</td>
</tr>
</table>

<!-- Tabelle : Suchbegriffe: Ende -->

<table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#F2F2F2">
<tbody>
<tr align="left" valign="middle">
<td>Suche
<input class="button" title="Suche starten" type="submit" value=" Starten " /><input class="button" title="Löschen" type="reset" value=" Löschen " /></td>
</tr>
</tbody>
</table></form>
<!-- Ende : Suchformular -->
<h2>Tipps zur Suche</h2>
<ul><li>Rechtstrunkierung mit '?', im Feld Autor geschieht dies automatisch</li>
<li>Die Felder werden mit UND verknüpft</li></ul>
<br/>
<h2>weitere Informationen</h2>
Die im <a href="http://wikis.sub.uni-hamburg.de/webis/index.php/Vorderer_Orient_einschl._Nordafrika_%286.23%29" target="_blank">Sondersammelgebiet 6.23 Vorderer Orient/ Nordafrika</a> bis 1997 in Tübingen und 1998-2015 in Halle gesammelten Bestände werden seit 2016 im neu geschaffenen <a href="http://gepris.dfg.de/gepris/projekt/284366805" target="_blank">Fachinformationsdienst Nahost-, Nordafrika- und Islamstudien</a> fortgeführt. Den Suchergebnissen können Sie entnehmen, welche der Bibliotheken über den gewünschten Titel verfügt.
<h2>Kontakt</h2>
Bei technischen Fragen: <a href="mailto:Uwe.Dierolf@kit.edu">Uwe Dierolf</a>
Bei fachlichen Fragen: <a href="mailto:volker.adam@bibliothek.uni-halle.de">Dr. Volker Adam</a>

FORMULAR;
}
add_shortcode('de_kit', 'de_menalib_kit_search');

function en_menalib_kit_search() {
return <<<FORMULAR

<form action="http://kvk.ubka.uni-karlsruhe.de/hylib-bin/kvk/nph-kvk2.cgi" method="GET" name="KVK_Suchmaske"><input name="maske" type="HIDDEN" value="SSG623" />
<h2>Choose Catalogues</h2>
<table>
<tbody>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_TUE" /><a href="http://www.ub.uni-tuebingen.de/home.html">University Library Tübingen</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_HALLE" /> <a href="http://lhhal.gbv.de/DB=1/LNG=DU/">University and State Library Saxony-Anhalt</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="IBLK_OPAC" /> <a href="http://www.ubka.uni-karlsruhe.de/hylib/iblk/">Database IBLK</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="VKNL_FES" /> <a href="http://library.fes.de/cgi-bin/populo/ssgvor.pl?db=ssgvor&amp;t_maske">Friedrich Ebert Foundation</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_ZMO_BERLIN" /> <a href="http://195.37.93.199/biblio/main.htm">Zentrum Moderner Orient, Berlin</a></td>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_BEIRUT" /> <a href="http://vzlbs2.gbv.de/DB=49/">Orient-Institute Beirut</a></td>
</tr>
<tr>
<td><input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_ISTANBUL" /> <a href="http://vzlbs2.gbv.de/DB=47/">Orient-Institute Istanbul</a></td>
</tr>
</tbody>
</table>
<h2>Enter Search Terms</h2>
<table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#F2F2F2"><!-- Tabelle : Suchbegriffe: Beginn -->
<tr align="left" valign="top">
<td>Freetext</td>    
<td><INPUT SIZE=18 TYPE="text" NAME="ALL" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Title</td>
<td><INPUT SIZE=18 TYPE="text" NAME="TI" id="menainput"></td>
<td>Year</td>
<td><INPUT SIZE=18 TYPE="text" NAME="PY" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Author</td>
<td><INPUT SIZE=18 TYPE="text" NAME="AU" id="menainput"></td>
<td>ISBN</td>
<td><INPUT SIZE=18 TYPE="text" NAME="SB" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Institution</td>
<td><INPUT SIZE=18 TYPE="text" NAME="CI" id="menainput"></td>
<td>ISSN </td>
<td><INPUT SIZE=18 TYPE="text" NAME="SS" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Keyword</td>
<td><INPUT SIZE=18 TYPE="text" NAME="ST" id="menainput"></td>
<td>Publisher</td>
<td><INPUT SIZE=18 TYPE="text" NAME="PU" id="menainput">
</td>
</tr>
</table>

<!-- Tabelle : Suchbegriffe: Ende -->

<table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#F2F2F2">
<tbody>
<tr align="left" valign="middle">
<td>Search
<input class="button" title="Start" type="submit" value=" Submit " /><input class="button" title="Delete" type="reset" value=" Reset " /></td>
<td align="right">
<table border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>Results</td>
<td><select class="css" title="Style of Results" name="css">
<option selected="selected" value="http://www.ubka.uni-karlsruhe.de/kvk/vk_ssg_vo/vk_ssg_vo_neu.css">New</option>
<option value="http://www.ubka.uni-karlsruhe.de/kvk/vk_ssg_vo/vk_ssg_vo_klassisch.css">Classic</option>
</select></td>
</tr>
<tr>
<td><!--<a href="http://www.ubka.uni-karlsruhe.de/hylib/kvk_help.html#sort">-->Results<!--</a>--></td>
<td><select name="sortiert">
<option selected="selected" value="nein">Unsorted</option>
<option value="ja">Sorted</option>
</select></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table></form>
<!-- Ende : Suchformular -->
<h2>Search tips</h2>
<ul><li>Right truncation with '?', automatically done in the author field</li>
<li>fields are linked with AND</li></ul>
<br/>
<h2>further Information</h2>
The library holdings of the Special Interest Collection Near East/ North Africa, collected until 1997 in Tübingen and 1998-2015 in Halle are continued since 2016 in the Special Information Service Near East, North Africa and Islamic Studies. You can see which library holds the wanted title in the results.

<h2>Contact</h2>
For technical questions: <a href="mailto:Uwe.Dierolf@kit.edu">Uwe Dierolf</a>
For professional questions: <a href="mailto:volker.adam@bibliothek.uni-halle.de">Dr. Volker Adam</a>
FORMULAR;
}
add_shortcode('en_kit', 'en_menalib_kit_search');
function de_kalenderrechner() {
return <<<FORMULAR

<script language="JavaScript" type="text/javascript">       <!--
       var h,g,i;
       
       function gregor (h)
               {
               i = h - (h/33) + 622;
               g = Math.round(i);
               return g;
               }
               
       function islam (g)
               {
               i = g - 622 + (g-622)/32;
               h = Math.round(i);
               return h;
               }
      //-->
      </script><p>Bitte tragen Sie links das Jahr (Hiǧra oder Gregorianisch) ein und klicken Sie auf den entsprechenden Button.</p><p>&nbsp;</p><p><form name="formular1"><table border="0"><tr><td>Eingabe:<br /><input name="jahrEin" size="4" maxlength="4" />&nbsp;&nbsp;&nbsp;</td><td><p>&nbsp;<input type="button" value=" Greg. Jahr&nbsp; --&gt; &nbsp;Hiǧri Jahr&nbsp;" onclick="formular1.jahrAus.value=islam(Number(formular1.jahrEin.value))" /></p>       &nbsp;<input type="Button" value="&nbsp;Hiǧri Jahr&nbsp; --&gt; &nbsp;Greg. Jahr " onclick="formular1.jahrAus.value=gregor(Number(formular1.jahrEin.value))" /></td><td>&nbsp;&nbsp;&nbsp;Ausgabe:<br />&nbsp;&nbsp;&nbsp;<input name="jahrAus" size="4" maxlength="4" /></td></tr></table><p>&nbsp;</p><p>Das Ergebnis ist ein N&auml;herungswert. Jedes Hiǧra-Jahr entspricht
einem Gregorianischen Jahr und umgekehrt. Die Konvertierung basiert auf
dieser Formel:  <b>G=H-(H/33)+622</b> and <b>H=G-622+(G-622)/32</b> (Carl Brockelmann: Arabische
Grammatik, 12. neubearbeitete Auflage, Leipzig: Harrassowitz, 1948, p. 209).
Wenn Sie genaue Daten mit Monat und Jahr konvertieren wollen, gehen Sie bitte auf diese Website: <a href="http://mela.us/hegira.html" target="new">Conversion of Islamic
and Christian dates von J. Thomann</a> oder auf
<a href="http://www.nabkal.de/kalrech.html" target="new">N.A.B. Rechner</a> von
Nikolaus A. B&auml;r.</p></form></p>
FORMULAR;
}
add_shortcode('de_kalender', 'de_kalenderrechner');
function en_kalenderrechner() {
return <<<FORMULAR

<script language="JavaScript" type="text/javascript">
	<!--
	var h,g,i;
	
	function gregor (h)
		{
		i = h - (h/33) + 622;
		g = Math.round(i);
		return g;
		}
		
	function islam (g)
		{
		i = g - 622 + (g-622)/32;
		h = Math.round(i);
		return h;
		}
	//-->
	</script>
<p>Enter the year you want to convert (Gregorian or Hijra) into the [i]Input[/i]-field and click the button for the conversion.</p>
<p>&nbsp;</p>
<p><form name="formular1">
<table border="0"><tr>
<td>Input:<br /><input name="jahrEin" size="4" maxlength="4" />&nbsp;&nbsp;&nbsp;</td>
<td><p>&nbsp;<input type="button" value=" Greg. year&nbsp; --&gt; &nbsp;Hijri year&nbsp;" onclick="formular1.jahrAus.value=islam(Number(formular1.jahrEin.value))" /></p>
       &nbsp;<input type="Button" value="&nbsp;Hijri year&nbsp; --&gt; &nbsp;Greg. year " onclick="formular1.jahrAus.value=gregor(Number(formular1.jahrEin.value))" /></td>
<td>&nbsp;&nbsp;&nbsp;Output:<br />&nbsp;&nbsp;&nbsp;<input name="jahrAus" size="4" maxlength="4" /></td>
</tr></table>
<p>&nbsp;</p>
<p>The result is an approximate date. Every Hijri year corresponds to one Gregorian year and vice versa. The conversion is based on the follwing formulae: G=H-(H/33)+622 and H=G-622+(G-622)/32 (Carl Brockelmann: Arabische Grammatik, 12. neubearbeitete Auflage, Leipzig: Harrassowitz, 1948, p. 209).

If you want to convert exact dates including month and day please go to the converter offered by the website <a href="http://mela.us/hegira.html" target="_blank">Conversion of Islamic and Christian dates</a> by J. Thomann or to the <a href="http://www.nabkal.de/kalrech.html" target="new">N.A.B. Rechner</a> developed by Nikolaus A. B&auml;r.</p>
</form></p>
FORMULAR;
}
add_shortcode('en_kalender', 'en_kalenderrechner');
function add_button_tile( $atts, $content = null) {
return <<<FORMULAR

     <div class="lebouton">$content</div>
FORMULAR;
}
add_shortcode('lebouton', 'add_button_tile');
/*function add_anfahrt() {
return <<<FORMULAR

     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2484.057875232845!2d11.961391515841033!3d51.49380541951797!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a67caa8db31243%3A0xa3c48bf1957328a2!2sStadtbezirk+Nord%2C+M%C3%BChlweg+15%2C+06114+Halle+(Saale)!5e0!3m2!1sde!2sde!4v1467013128528" width="400" height="400" frameborder="0" style="border:0"></iframe>
FORMULAR;	
}
add_shortcode('anfahrt', 'add_anfahrt');*/





/*
	2017_12_18 HenryG.
	Enable categories and tags for pages

	http://spicemailer.com/wordpress/add-categories-tags-pages-wordpress/ 
*/
function add_taxonomies_to_pages() {
 register_taxonomy_for_object_type( 'post_tag', 'page' );
 register_taxonomy_for_object_type( 'category', 'page' );
 }
add_action( 'init', 'add_taxonomies_to_pages' );

/*
	***
*/


/*
	2018_01_03 HenryG.
	SVG Support

	https://www.sitepoint.com/wordpress-svg/ 


function add_file_types_to_uploads($file_types){

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );

    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');


*/


/*
	***
*/



/*
	2018_01_03 HenryG.
	SVG Support in costum header
	(This enables to skip the crop image function)

	https://wordpress.stackexchange.com/questions/207442/how-to-use-a-svg-as-custom-header
	function hikeitbaby_custom_header_setup()

*/

function menalib_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'menalib_custom_header_args', array(
        'default-image'          => '',
        'default-text-color'     => 'FFFFFF',
        'width'                  => 886,
        'height'                 => 120,
        'flex-width'             => true,
        'flex-height'            => true,
        'wp-head-callback'       => 'menalib_header_style',
        'admin-head-callback'    => 'menalib_admin_header_style',
        'admin-preview-callback' => 'menalib_admin_header_image',
    ) ) );
}


add_action( 'after_setup_theme', 'menalib_custom_header_setup' );
/*
	***
*/


/*
	2018_01_24 HenryG.
	Display pages as posts
	https://premium.wpmudev.org/blog/wordpress-pages-like-posts/
 */

add_filter( 'pre_get_posts', 'my_get_posts' );
 function my_get_posts( $query ) 
 {
	 if ( is_home() && false == $query->query_vars['suppress_filters'] )
	 $query->set( 'post_type', array( 'post', 'page') );
	 return $query;
 }

/*
	***
*/


/*



*/