<?php
/**
 * Shortcode für OPAC-Katalog Suche de / en
 *
 * @package ulb_menalib 
 */


function de_menalib_kit_search() 
{
return <<<FORMULAR


<form action="https://kvk.bibliothek.kit.edu/hylib-bin/kvk/nph-kvk2.cgi" method="GET" name="KVK_Suchmaske"><input name="maske" type="hidden" value="SSG623" />

<input type="hidden" name="input-charset" value="utf-8">
<input type="hidden" name="header" value="http://kvk.bibliothek.kit.edu/asset/html/header.html">
<input type="hidden" name="footer" value="http://kvk.bibliothek.kit.edu/asset/html/footer.html">
<input type="hidden" name="lang" value="de">

<table>
<tbody>
<tr>
<td>
	<script type="text/javascript">
	<!--	    
			document.write("Auswahl")
			document.write("</td>")
			document.write("<td nowrap>")
			document.write("<input class=\"button\" type=\"button\" value=\"  Alle  \" onClick=\"check_all(this.form, true)\" title=\"Alle Kataloge ausw�hlen\">\n")
			document.write("<input class=\"button\" type=\"button\" value=\"Einzelne\" onClick=\"check_all(this.form, false)\" title=\"Einzelne Kataloge ausw�hlen\">")

	//-->
	</script>
</tr>
</tr>
<tr>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_TUE" /><a href="http://www.ub.uni-tuebingen.de/home.html">Universitätsbibliothek Tübingen</a>
</td>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_HALLE" /> <a href="http://lhhal.gbv.de/DB=1/LNG=DU/">Universitäts- und Landesbibliothek Sachsen-Anhalt</a>
</td>
</tr>
<tr>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="IBLK_OPAC" /> <a href="http://www.ubka.uni-karlsruhe.de/hylib/iblk/">Datenbasis IBLK</a>
</td>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="VKNL_FES" /> <a href="http://library.fes.de/cgi-bin/populo/ssgvor.pl?db=ssgvor&amp;t_maske">Friedrich Ebert Stiftung</a>
</td>
</tr>
<tr>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="SSG_ZMO_BERLIN" /> <a href="http://195.37.93.199/biblio/main.htm">Zentrum Moderner Orient, Berlin</a></td>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_BEIRUT" /> <a href="http://vzlbs2.gbv.de/DB=49/">Orient-Institut Beirut</a></td>
</tr>
<tr>
<td>
	<input checked="checked" name="kataloge" type="checkbox" value="SSG_VO_ISTANBUL" /> <a href="http://vzlbs2.gbv.de/DB=47/">Orient-Institut Istanbul</a></td>
</tr>
</tbody>
</table>

<h2>Suchbegriffe eingeben</h2>
<table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#F2F2F2">
<!-- Tabelle : Suchbegriffe: Beginn -->
<tr align="left" valign="top">
<td>Freitext</td>    
<td>
	<input size=18 type="text" name="ALL" id="menainput">
</td>
</tr>
<tr align="left" valign="top">
<td>Titel</td>
<td>
	<input size=18 type="text" name="TI" id="menainput">
</td>
<td>
	Jahr
</td>
<td>
	<input size=18 type="text" name="PY" id="menainput">
</td>
</tr>
<tr align="left" valign="top">
<td>
	Autor
</td>
<td>
	<input size=18 type="text" name="AU" id="menainput">
</td>
<td>
	ISBN
</td>
<td>
	<input size=18 type="text" name="SB" id="menainput">
</td>
</tr>
<tr align="left" valign="top">
<td>
	K&ouml;rperschaft
</td>
<td>
	<input size=18 type="text" name="CI" id="menainput">
</td>
<td>
	ISSN 
</td>
<td>
	<input size=18 type="text" name="SS" id="menainput">
</td>
</tr>
<tr align="left" valign="top">
<td>
	Schlagwort
</td>
<td>
	<input size=18 type="text" name="ST" id="menainput">
</td>
<td>
	Verlag
</td>
<td>
	<input size=18 type="text" name="PU" id="menainput">
</td>
</tr>
</table>

<!-- Tabelle : Suchbegriffe: Ende -->

<table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#F2F2F2">
<tbody>
<tr align="left" valign="middle">
<td>
	Suche
<input class="button" title="Suche starten" type="submit" value=" Starten " />
<input class="button" title="Löschen" type="reset" value=" Löschen " /></td>
</tr>
</tbody>
</table>
</form>
<!-- Ende : Suchformular -->
<h2>Tipps zur Suche</h2>

<ul>
	<li>Rechtstrunkierung mit '?', im Feld Autor geschieht dies automatisch</li>
	<li>Die Felder werden mit UND verknüpft</li>
</ul>

<br/>
<h2>Weitere Informationen</h2>
Die im <a href="http://wikis.sub.uni-hamburg.de/webis/index.php/Vorderer_Orient_einschl._Nordafrika_%286.23%29" target="_blank">Sondersammelgebiet 6.23 Vorderer Orient/ Nordafrika</a> bis 1997 in Tübingen und 1998-2015 in Halle gesammelten Bestände werden seit 2016 im neu geschaffenen <a href="http://gepris.dfg.de/gepris/projekt/284366805" target="_blank">Fachinformationsdienst Nahost-, Nordafrika- und Islamstudien</a> fortgeführt. Den Suchergebnissen können Sie entnehmen, welche der Bibliotheken über den gewünschten Titel verfügt.

<h2>Kontakt</h2>
Bei technischen Fragen: <a href="mailto:Uwe.Dierolf@kit.edu">Uwe Dierolf</a>
Bei fachlichen Fragen: <a href="mailto:volker.adam@bibliothek.uni-halle.de">Dr. Volker Adam</a>

FORMULAR;
}
add_shortcode('de_kit', 'de_menalib_kit_search');























function en_menalib_kit_search() {
return <<<FORMULAR
<form action="https://kvk.bibliothek.kit.edu/hylib-bin/kvk/nph-kvk2.cgi" method="GET" name="KVK_Suchmaske"><input name="maske" type="hidden" value="SSG623" />

<input type="hidden" name="input-charset" value="utf-8">
<input type="hidden" name="header" value="http://kvk.bibliothek.kit.edu/asset/html/header.html">
<input type="hidden" name="footer" value="http://kvk.bibliothek.kit.edu/asset/html/footer.html">
<input type="hidden" name="lang" value="en">

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
<td><input size=18 type="text" name="ALL" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Title</td>
<td><input size=18 type="text" name="TI" id="menainput"></td>
<td>Year</td>
<td><input size=18 type="text" name="PY" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Author</td>
<td><input size=18 type="text" name="AU" id="menainput"></td>
<td>ISBN</td>
<td><input size=18 type="text" name="SB" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Institution</td>
<td><input size=18 type="text" name="CI" id="menainput"></td>
<td>ISSN </td>
<td><input size=18 type="text" name="SS" id="menainput"></td>
</tr>
<tr align="left" valign="top">
<td>Keyword</td>
<td><input size=18 type="text" name="ST" id="menainput"></td>
<td>Publisher</td>
<td><input size=18 type="text" name="PU" id="menainput">
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
<h2>Search Tips</h2>
<ul><li>Right truncation with '?', automatically done in the author field</li>
<li>fields are linked with AND</li></ul>
<br/>
<h2>Further Information</h2>
The library holdings of the Special Interest Collection Near East/ North Africa, collected until 1997 in Tübingen and 1998-2015 in Halle are continued since 2016 in the Special Information Service Near East, North Africa and Islamic Studies. You can see which library holds the wanted title in the results.

<h2>Contact</h2>
For technical questions: <a href="mailto:Uwe.Dierolf@kit.edu">Uwe Dierolf</a>
For professional questions: <a href="mailto:volker.adam@bibliothek.uni-halle.de">Dr. Volker Adam</a>
FORMULAR;
}
add_shortcode('en_kit', 'en_menalib_kit_search');