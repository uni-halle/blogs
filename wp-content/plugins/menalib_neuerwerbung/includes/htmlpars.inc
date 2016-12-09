<?php
require_once 'simple_html_dom.php';

function microtime_float() 
{
  return array_sum(explode(' ', microtime()));
}

// opac-----------------------------------------------------------------------------
function parseOpac($nach, $query)
{
	echo "<h3>OPAC:</h3>";
	$query = str_replace(' ', '+', $query);

	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://opac.bibliothek.uni-halle.de/DB=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query");

	foreach($content as $zeile)
	{
		// echo $zeile;
		// base-adresse (mit cooky)
		if(strpos($zeile, "<BASE HREF=\"") !== false) {
			$ref = substr($zeile, strpos($zeile, "<BASE HREF=\"") + 12, strlen($zeile) - (strpos($zeile, "<BASE HREF=\"") + 16));
		}
		// anzahl results
		if(strpos($zeile, "&nbsp;von&nbsp;") !== false && strlen($anzHits) === 0) {
			for($i = strpos($zeile, "&nbsp;von&nbsp;") + 15; $i < strlen($zeile); $i++)
			{
				if(is_numeric(substr($zeile, $i, 1)))
					$anzHits .= substr($zeile, $i, 1);
				else	
					break;
			}
		}
		// 1. result - flag setzen
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">1.&nbsp;</TD>") !== false)
			$anfang = true;
		
		// 6. result - schluss
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">6.&nbsp;</TD>") !== false)
			break;
		
		// sonderbehandlung 1. result
		if($naechste)
		{
			$result[0] .= $zeile;
			$naechste = false;
		}
		
		// die results
		if($anfang)
		{
			if(strpos($zeile, "href=\"SHW?FRST=") !== false)
			{
				// flag fuer sonderbehandlung
				if(count($result) == 0)
					$naechste = true;
				else
					$naechste = false;
				$result[] = "<a target=\"_blank\" href=\"".$ref."/".substr($zeile, strpos($zeile, "SHW?FRST="));
			}
		}	
		
		// spezialfall einzelresult
		if(strpos($zeile, "<strong>Titel:&#xA0;</strong>") !== false) {
			$startpos = strpos($zeile, "<strong>Titel:&#xA0;</strong>") + 72;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;

			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		else {
		if(strpos($zeile, "<strong>Zeitschrift:&#xA0;</strong>") !== false) {
			$startpos = strpos($zeile, "<strong>Zeitschrift:&#xA0;</strong>") + 78;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<strong>Aufsatz:&#xA0;</strong>") !== false) {
			$startpos = strpos($zeile, "<strong>Aufsatz:&#xA0;</strong>") + 22;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<strong>Schriftenreihe:&#xA0;</strong>") !== false) {
			$startpos = strpos($zeile, "<strong>Schriftenreihe:&#xA0;</strong>") + 29;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		}
	}
	
	// ausgabe
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{	
			echo "<li>".iconv("ISO-8859-1","UTF-8",$zeile)."</li>\n";
		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}

// sdfosihfpo ---------------------
function parseFivIblk($nach, $query)
{
	echo "<h3>FIV-IBLK:</h3>";

	$query = str_replace(" ", "+", $query);

	$html = file_get_html ("http://swb.bsz-bw.de/DB=2.362/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query");

	$i = 0;
	echo "<ol start=\"1\">";
	$base = $html->find('base');
	$baseHref = $base[0]->getAttribute("href");
	//	$baseHref = "";
	foreach($html->find('.hit') as $element) {
		if($element->getAttribute("align") == "left" && $i < 5) {
			$link = $element->find('a');
			$link[0]->setAttribute("target","_blank");
			$link[0]->setAttribute("href", $baseHref . $link[0]->getAttribute("href"));
			echo '<li>'. $element->innertext.'<br/>';
			$i++;
		}
	}
	echo "</ol>";
	if($i > 0) {
		if($hits = $html->find('.pages')) {
			echo "<a target=\"_blank\" href=\"http://swb.bsz-bw.de/DB=2.362/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			echo "insg. " . substr($hits[0]->innertext, strripos($hits[0]->innertext, ";") + 1);
			echo " Hits</a><br>";
		}
	} else
		echo "no result<br>\n";


}

// ssg-olc--------------------------------------------------------------------------
function parseSsgolc($nach, $query) 
{ 
	echo "<h3>SSG-OLC:</h3>\n";
	$query = str_replace(' ', '+', $query);
	
	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://gso.gbv.de/DB=2.84/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query");

	$resCounter = 0;	
	$next = false;

	foreach($content as $zeilennummer => $zeile)
	{
		// base-adresse (mit cooky)
		if(strpos($zeile, "<base href=\"") !== false) {
			$ref = substr($zeile, 12, strlen($zeile) - 15);
			echo "<br>ref: " . $ref . "</br>";
		}
			
		// anzahl results
		if(strpos($zeile, "Treffer</span>") !== false)
			$anzHits = substr($zeile, 42, strlen($zeile) - 57);
		
		// result 2. zeile
		if($next)
		{	
			$result[$resCounter] .= substr($zeile, 5, strlen($zeile) - 12);
			$next = false;
			$resCounter ++;
		}
		// result 1. zeile
		if($resCounter < 5 && strpos($zeile, " href=\"SHW?FRST=") !== false)
		{
			$next = true;
			$t_number = $resCounter + 1;
			$result[$resCounter] = "<a target=\"_blank\" href=\"" . $ref . "SHW?FRST=$t_number".substr($zeile,29,strlen($zeile)-36);
		}
		
		// spezialfall einzelresult
		if($anzHits == 1 && strpos($zeile, '<span>Aufsatztitel: </span>') !== false) {
			for($i = $zeilennummer + 1; $i++; $i < count($content)) {
				if(strpos($content[$i], '</td>') !== false) {
					break;
				} else {
					$resultHtml .= $content[$i];
				}
			}
			$result[0] = "<a target=\"_blank\" href=\"http://gso.gbv.de/DB=2.84/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query\">";
			$result[0] .= strip_tags($resultHtml) . '</a>';	
		}
	
	}

	// ausgabe  iconv("ISO-8859-1", "Windows-1256",
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{	
	
			echo "<li>".$zeile."</li>\n";
		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
	
}
	
	
// menacontents--------------------------------------------------------------------------
function parseMena($nach, $query)
{
//	$query = htmlentities($query);
	
	if($nach == "4") {
		$conNach = "title";
	} else if($nach == "1004") {
		$conNach = "author";	
	} else {
		echo "<br>";
		return;
	}
	echo "<h3>MENA-Contents:</h3>";
	$query = str_replace(' ', '+', $query);
	
		$anzHits = "";
		$counter = 0;
		$content = file("http://www.menalib.de/index.php?id=58&ajaxTarget=alif&alif[find]=$query&L=0");
		
	
		echo "<b>Journale:</b><br>\n";
		
		foreach($content as $zeilennummer => $zeile)
		{
	//		echo $zeile;
			// anzahl results
			if(strpos($zeile, "You are browsing through the results") !== false)
			{
				$anzHits .= substr($zeile, strpos($zeile, ' from ') + 6, -16);
		
			}
			// results	 
			if($counter < 3)
			{
				//zw. "<td><a" und "</td>"
				if(strpos($zeile, '<li class="journal">') !== false)
				{
					$result[$counter] = '<a target="_blank" href="http://www.menalib.de/' . substr($content[$zeilennummer + 2], strpos($content[$zeilennummer + 2], href) + 6, -10);
					$counter++;
				}
			}
			else break;
			
		}
		// ausgabe iconv("UTF-8", "ISO-8859-1",)
		if(count($result) > 0)
		{
			echo "<ol start=\"1\">";
			foreach($result as $t=>$zeile)
			{	
				echo "<li>".$zeile."</li>\n";
	
			}
			echo "</ol><br> (<a href=\"http://www.menalib.de/index.php?id=58&ajaxTarget=alif&alif[find]=$query\" target=\"_blank\">insg. $anzHits Journale</a>)<br>";
		}
		else
			echo "no result<br>";
			
	$anzHits = "";
		$counter = 0;
		$content = file("http://www.menalib.de/index.php?id=59&ajaxTarget=alif&alif[find]=$query&L=0");
		
	
		echo "<b>Artikel:</b><br>\n";
		
		foreach($content as $zeilennummer => $zeile)
		{
	//		echo $zeile;
			// anzahl results
			if(strpos($zeile, "You are browsing through the results") !== false)
			{
				$anzHits .= substr($zeile, strpos($zeile, ' from ') + 6, -16);
		
			}
			// results	 
			if($counter < 3)
			{
				//zw. "<td><a" und "</td>"
				if(strpos($zeile, '<li class="article">') !== false)
				{
					$result[$counter] = '<a target="_blank" href="http://www.menalib.de/' . substr($content[$zeilennummer + 3], strpos($content[$zeilennummer + 3], 'href') + 6, -10);
					$counter++;
				}
			}
			else break;
			
		}
		// ausgabe iconv("UTF-8", "ISO-8859-1",)
		if(count($result) > 0)
		{
			echo "<ol start=\"1\">";
			foreach($result as $t=>$zeile)
			{	
				echo "<li>".$zeile."</li>\n";
	
			}
			echo "</ol><br> (<a href=\"http://www.menalib.de/index.php?id=59&ajaxTarget=alif&alif[find]=$query\" target=\"_blank\">insg. $anzHits Artikel</a>)<br>";
		}
		else
			echo "no result<br>";
		
	
}
// almisbah--------------------------------------------------------------------------
function parseAlmi($nach, $query)
{
	echo "<h3>Almisbah:</h3>\n";
	$query = str_replace(' ', '+', $query);
	
	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://gso.gbv.de/DB=8.7/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query");

	$resCounter = 0;	
	$next = false;

	foreach($content as $zeilennummer => $zeile)
	{
		// base-adresse (mit cooky)
		if(strpos($zeile, "<base href=\"") !== false) {
			$ref = substr($zeile, 12, -3);
			$refZeile = $zeile;
		}
		// anzahl results
		if(strpos($zeile, "Treffer</span>") !== false) {
			$anzHits = substr($zeile, 42, strlen($zeile) - 57);
		}
	
		// result
		if($resCounter < 5 && strpos($zeile, " \" href=\"SHW?FRST=") !== false)
		{
			$next = true;
			$result[] = "<a target=\"_blank\" href=\"$ref".substr($zeile,19,-7);
			$resCounter ++;
		}
		
		// spezialfall einzelresult
		if($anzHits == 1 && strpos($zeile, '<span>Titel: </span>') !== false) {
			for($i = $zeilennummer + 1; $i++; $i < count($content)) {
				if(strpos($content[$i], '</td>') !== false) {
					break;
				} else {
					$resultHtml .= $content[$i];
				}
			}
			$result[0] = "<a target=\"_blank\" href=\"http://gso.gbv.de/DB=8.7/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query\">";
			$result[0] .= strip_tags($resultHtml) . '</a>';	
		}
	
	}

	// ausgabe  iconv("ISO-8859-1", "Windows-1256",
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{	
	
			echo "<li>".$zeile."</li>\n";
		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}

// oibeirut--------------------------------------------------------------------------
function parseOIBeirut($nach, $query) 
{ 
	echo "<h3>OI Beirut:</h3>\n";
	$query = str_replace(' ', '+', $query);
	
	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://vzlbs2.gbv.de/DB=49/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query");

	foreach($content as $zeile)
	{
		//echo $zeile;
		// base-adresse (mit cooky)
		if(strpos($zeile, "<BASE HREF=\"") !== false) {
			$ref = substr($zeile, strpos($zeile, "<BASE HREF=\"") + 12, strlen($zeile) - (strpos($zeile, "<BASE HREF=\"") + 16));
		}
		// anzahl results
		if(strpos($zeile, "&nbsp;von&nbsp;") !== false && strlen($anzHits) === 0) {
			for($i = strpos($zeile, "&nbsp;von&nbsp;") + 15; $i < strlen($zeile); $i++)
			{
				if(is_numeric(substr($zeile, $i, 1)))
					$anzHits .= substr($zeile, $i, 1);
				else	
					break;
			}
		}
		// 1. result - flag setzen
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">1.&nbsp;</TD>") !== false)
			$anfang = true;
		
		// 6. result - schluss
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">6.&nbsp;</TD>") !== false)
			break;
		
		// sonderbehandlung 1. result
		if($naechste)
		{
			$result[0] .= $zeile;
			$naechste = false;
		}
		
		// die results
		if($anfang)
		{
			if(strpos($zeile, "href=\"SHW?FRST=") !== false)
			{
				// flag fuer sonderbehandlung
				if(count($result) == 0)
					$naechste = true;
				else
					$naechste = false;
				$result[] = "<a target=\"_blank\" href=\"".$ref."/".substr($zeile, strpos($zeile, "SHW?FRST="));
			}
		}	
		
		// spezialfall einzelresult
		if(strpos($zeile, "<div>Titel:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Titel:&#xA0;</div>") + 75;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;
	
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=49/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		else {
		if(strpos($zeile, "<div>Zeitschrift:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Zeitschrift:&#xA0;</div>") + 81;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=49/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<div>Aufsatz:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Aufsatz:&#xA0;</div>") + 22;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=49/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<div>Schriftenreihe:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Schriftenreihe:&#xA0;</div>") + 84;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=49/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		}
	}
	

	// ausgabe
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{
			echo "<li>".$zeile."</li>\n";
		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}

// ub tuebingen-----------------------------------------------------------------------------
function parseTueb($nach, $query)
{
	echo "<h3>UB Tuebingen:</h3>";
	$query = str_replace(" ", "+", $query);

	$anfang = false;
	$naechste = false;
	$anzHits = "";
	
	if($nach == "4")
		$conNach = "ti";
	else if($nach == "1004")
		$conNach = "ar";
	else
		$conNach = "ct";
	$query = str_replace(array('Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü'), array('Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue'), $query);

	$content = file("http://swb2.bsz-bw.de/DB=2.312/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query");

	foreach($content as $pos => $zeile)
	{
		// echo $zeile;
		// base-adresse (mit cooky)
		if(strpos($zeile, "<BASE HREF=\"") !== false) {
			$ref = substr($zeile, strpos($zeile, "<BASE HREF=\"") + 12, strlen($zeile) - (strpos($zeile, "<BASE HREF=\"") + 16));
		}
		// anzahl results
		if(strpos($zeile, "&nbsp;von&nbsp;") !== false && strlen($anzHits) === 0) {
			for($i = strpos($zeile, "&nbsp;von&nbsp;") + 15; $i < strlen($zeile); $i++)
			{
			if(is_numeric(substr($zeile, $i, 1)))
				$anzHits .= substr($zeile, $i, 1);
				else
					break;
			}
			}
			// 1. result - flag setzen
			if(strpos($zeile, "<TD class=\"hit\" id=\"hitNumId1\" align=\"right\" valign=\"top\"> 	1.&nbsp;</TD>") !== false)
			$anfang = true;
	
			// 6. result - schluss
			if(strpos($zeile, "<TD class=\"hit\" id=\"hitNumId6\" align=\"right\" valign=\"top\"> 	6.&nbsp;</TD>") !== false)
			break;
	
			// sonderbehandlung 1. result
			if($naechste)
			{
			$result[0] .= $zeile;
			$naechste = false;
			}

			// die results
			if($anfang)
			{
			if(strpos($zeile, "href=\"SHW?FRST=") !== false)
			{
				// flag fuer sonderbehandlung
				if(count($result) == 0)
					$naechste = true;
					else
						$naechste = false;
					$link     = "<a target=\"_blank\" href=\"".$ref."/".substr($zeile, strpos($zeile, "SHW?FRST="));
					$resta = $content[$pos + 1];
					if ( strlen($resta) < 6 )
						$resta = $content[$pos + 2];
					$posimg   = strpos($resta, ' <img src=') - 1;
					$resta    = substr($resta, 1, $posimg);
					$result[] = $link . $resta . "</a>";
			}
			}
	
			// spezialfall einzelresult
			$titstr = "<strong><div>Titel:&#xA0;</div></strong>";
		if(strpos($zeile, $titstr) !== false) {
			$startpos = strpos($zeile, $titstr) + 88;
			$startpos = strpos($zeile, 'onmouseout="UnTip()">', $startpos) + 21;
			$endpos = strpos($zeile, '</div></td></tr>', $startpos);
			$result[0] = "<a target=\"_blank\" href=\"http://swb2.bsz-bw.de/DB=2.312/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= substr($zeile, $startpos, $endpos - $startpos);
			}
			else {
			$titstr = "<strong><div>Zeitschrift:&#xA0;</div></strong>";
		if(strpos($zeile, $titstr) !== false) {
			$startpos = strpos($zeile, $titstr) + 88;
			$startpos = strpos($zeile, 'onmouseout="UnTip()">', $startpos) + 21;
			$endpos = strpos($zeile, '</div></td></tr>', $startpos);
			$result[0] = "<a target=\"_blank\" href=\"http://swb2.bsz-bw.de/DB=2.312/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= substr($zeile, $startpos, $endpos - $startpos);
			}
			$titstr = "<strong><div>Aufsatz:&#xA0;</div></strong>";
		if(strpos($zeile, $titstr) !== false) {
			$startpos = strpos($zeile, $titstr) + 88;
			$startpos = strpos($zeile, 'onmouseout="UnTip()">', $startpos) + 21;
			$endpos = strpos($zeile, '</div></td></tr>', $startpos);
			$result[0] = "<a target=\"_blank\" href=\"http://swb2.bsz-bw.de/DB=2.312/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= substr($zeile, $startpos, $endpos - $startpos);
			}
			$titstr = "<strong><div>Schriftenreihe:&#xA0;</div></strong>";
		if(strpos($zeile, $titstr) !== false) {
			$startpos = strpos($zeile, $titstr) + 88;
			$startpos = strpos($zeile, 'onmouseout="UnTip()">', $startpos) + 21;
			$endpos = strpos($zeile, '</div></td></tr>', $startpos);
			$result[0] = "<a target=\"_blank\" href=\"http://swb2.bsz-bw.de/DB=2.312/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= substr($zeile, $startpos, $endpos - $startpos);
			}
			}
			}
	
			// ausgabe
			if(count($result) > 0)
			{
			echo "<ol start=\"1\">";
			foreach($result as $t=>$zeile)
			{
			echo "<li>".$zeile."</li>\n";
			}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
			}
			else
		echo "no result<br>\n";
	}
	
// iblk----------------------------------------------------------------------------------
function parseIblk($nach, $query)
{
	echo "<h3>IBLK Datenbasis:</h3>";
	
	$query = str_replace(" ", "+", $query);

	$anzHits = "";
	
	if($nach == "4")
		$conNach = "TI";
	else if($nach == "1004")
		$conNach = "AR";
	else
		$conNach = "CT";

	$content = file("http://www.ubka.uni-karlsruhe.de/hylib-bin/suche.cgi?opacdb=UEBERSEE&field1=$conNach&name1=$query");

	foreach($content as $zeilennummer => $zeile)
	{
		// anzahl results
		if(strpos($zeile, "<STRONG>Trefferanzahl</STRONG>: ") !== false)
			for($i = 36; $i < strlen($zeile); $i++)
			{
				if(is_numeric(substr($zeile, $i, 1)))
					$anzHits .= substr($zeile, $i, 1);
				else	
					break;
			}
		// die results
		if($counter < $ende)
		{
			if(strpos($zeile, "A HREF=") !== false)
				$result .= "<A TARGET=\"_blank\" HREF=\"http://www.ubka.uni-karlsruhe.de".substr(iconv("ISO-8859-1","UTF-8",$zeile),8);
			else
				$result .= iconv("ISO-8859-1","UTF-8",$zeile);
			$counter++;
		}
		// 1. result - flag setzen
		if(strpos($zeile, "<UL") !== false)
		{
			$result = "<OL start=\"1\">";
			$counter = 0;
			$ende = min(15, $anzHits * 3);
		}
		
		// spezialfall Einzelergebnis
		if(strpos($zeile, "td valign=\"top\"") !== false && strlen($anzHits) == 0) {
			$anzHits = 1;
			$result = "<ol><li><a target=\"_blank\" href=\"http://www.ubka.uni-karlsruhe.de/hylib-bin/suche.cgi?opacdb=UEBERSEE&field1=$conNach&name1=$query\">";
			$result .= iconv("ISO-8859-1","UTF-8",substr($content[$zeilennummer + 2], 0, -5)) . '</a></li>';
		}
			
		
	}
	$result .= "</ol>";
	
	// ausgabe
	if($anzHits > 0)
	{
		echo $result;
		echo "<br> (<a href=\"http://www.ubka.uni-karlsruhe.de/hylib-bin/suche.cgi?opacdb=UEBERSEE&field1=$conNach&name1=$query\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}
// fes----------------------------------------------------------------------------------
function parseFes($nach, $query)
{
	echo "<h3>Friedrich Ebert Stiftung:</h3>";
	
	$temp=explode(' ', $query);

	$anzHits = "";
	
	if($nach == "4")
		$conNach = "TIT";
	else if($nach == "1004")
		$conNach = "PER";
	else
		$conNach = "STW";
	
	$phrase = urldecode("pull_1=$conNach&text_1=$query&pull_2=$conNach&text_2=&pull_3=$conNach&text_3=$temp[2]");
	$content = file("http://library.fes.de/cgi-bin/populo/ssgvor.pl?db=ssgvor&$phrase&erj=&t_allegro=suchen&modus=%3F&logik=AND");
	$counter = 0;
	$ende = -1;
	foreach($content as $zeilennummer => $zeile)
	{
		// die results
		if($counter < $ende && strpos($zeile, "<a class='nosign' href=")!== false)
		{
			$pos1 = strpos($zeile, " href=");
			$pos2 = strpos($zeile, "/cgi-bin/");
			$pos3 = strpos($zeile, "<b>");
			$pos4 = strpos($zeile, "</b>");
			if(strpos($zeile, "<br><font color='#cc3333'>")) {
				$pos5 = strpos($zeile, "<br><font color='#cc3333'>");
			} else {
				$pos5 = strlen($zeile) - 11; 
			}
	
			$result .= "<li><a target=\"_blank\"".substr($zeile, $pos1, $pos2 - ($pos1 + 1));
			$result .= "'http://library.fes.de".substr($zeile, $pos2, $pos3 - $pos2);
			$result .= substr($zeile, $pos3 + 3, $pos4 - ($pos3 + 3)).'<br/>';
			$result .= substr($zeile, $pos4 + 4, $pos5 - ($pos4 + 4))."</li>\n";
			$counter++;
		}
		// anzahl results
		if(strpos($zeile, "Treffer zu Ihrer Suchanfrage") !== false)
		{
			$pos = strpos($zeile, " Treffer zu Ihrer Suchanfrage");
			$anzHits = substr($zeile, 0, $pos);
			$ende = min(5, $anzHits);			
			$result = "<ol>\n";
		}	

		// spezialfall Einzelergebnis
		if(strpos($zeile, '!-- pre -->') > 0 && $anzHits < 26) {
		//	$anzHits = 1;
			$zeilennummer2 = $zeilennummer + 2;
			while(!strpos($content[$zeilennummer2], "!-- /pre --") && $zeilennummer2 < count($content) && $counter < 5) {
				if(strpos($content[$zeilennummer2], "<b>")) {
					$result .= '<li>' . strip_tags($content[$zeilennummer2]) . '</li>';
					$counter++;
				}
				$zeilennummer2 ++;
			}
		}
		
	}
	
	
	$result .= "</ol>";
	
	// ausgabe
	if($anzHits > 0)
	{
		echo iconv("ISO-8859-1","UTF-8",$result);
		echo "<br> (<a href=\"http://library.fes.de/cgi-bin/populo/ssgvor.pl?db=ssgvor&db=ssgvor&$phrase&erj=&t_allegro=suchen&modus=%3F&logik=AND&f_THE=\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}
// zmo----------------------------------------------------------------------------------
function parseZmo($nach, $query)
{
	echo "<h3>Zentrum Moderner Orient Berlin:</h3>";
	$query = str_replace(" ", "+", $query);

	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://vzlbs2.gbv.de/DB=58/LNG=DU/SID=71ec2575-3/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query");
//	echo "http://haweb1.bibliothek.uni-halle.de:8080/DB=1/SET=1/TTL=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query";	


	foreach($content as $zeile)
	{
		
		// base-adresse (mit cooky)
		if(strpos($zeile, "<BASE HREF=\"") !== false) {
			$ref = substr($zeile, strpos($zeile, "<BASE HREF=\"") + 12, strlen($zeile) - (strpos($zeile, "<BASE HREF=\"") + 16));
		}
		// anzahl results
		if(strpos($zeile, "&nbsp;von&nbsp;") !== false && strlen($anzHits) === 0) {
			for($i = strpos($zeile, "&nbsp;von&nbsp;") + 15; $i < strlen($zeile); $i++)
			{
				if(is_numeric(substr($zeile, $i, 1)))
					$anzHits .= substr($zeile, $i, 1);
				else	
					break;
			}
		}
		// 1. result - flag setzen
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">1.&nbsp;</TD>") !== false)
			$anfang = true;
		
		// 6. result - schluss
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">6.&nbsp;</TD>") !== false)
			break;
		
		// sonderbehandlung 1. result
		if($naechste)
		{
			$result[0] .= $zeile;
			$naechste = false;
		}
		
		// die results
		if($anfang)
		{
			if(strpos($zeile, "href=\"SHW?FRST=") !== false)
			{
				// flag fuer sonderbehandlung
				if(count($result) == 0)
					$naechste = true;
				else
					$naechste = false;
				$result[] = "<a target=\"_blank\" href=\"".$ref."/".substr($zeile, strpos($zeile, "SHW?FRST="));
			}
			
		}	
		// spezialfall einzelresult
		if(strpos($zeile, "<strong><div>Titel:&#xA0;</div></strong>") !== false) {
			$startpos = strpos($zeile, "<strong><div>Titel:&#xA0;</div></strong>") + 31;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=58/LNG=DU/SID=71ec2575-3/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<strong><div>Zeitschrift:&#xA0;</div></strong>") !== false) {
			$startpos = strpos($zeile, "<strong><div>Zeitschrift:&#xA0;</div></strong>") + 37;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/SET=1/TTL=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<strong><div>Aufsatz:&#xA0;</div></strong>") !== false) {
			$startpos = strpos($zeile, "<strong><div>Aufsatz:&#xA0;</div></strong>") + 33;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/SET=1/TTL=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<strong><div>Schriftenreihe:&#xA0;</div></strong>") !== false) {
			$startpos = strpos($zeile, "<strong><div>Schriftenreihe:&#xA0;</div></strong>") + 40;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://opac.bibliothek.uni-halle.de/DB=1/SET=1/TTL=1/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
	}
	
	// ausgabe
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{	
			//echo "<li>".iconv("ISO-8859-1","UTF-8",$zeile)."<br>";
			echo "<li>".$zeile."</li>\n";
		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}

// oiistanbul--------------------------------------------------------------------------
function parseOIIstanbul($nach, $query) 
{ 
	echo "<h3>OI Istanbul:</h3>\n";
	$query = str_replace(" ", "+", $query);

//	$query = urlencode(str_replace(" ", "+", $query));
	
	$anfang = false;	
	$naechste = false;
	$anzHits = "";

	$content = file("http://vzlbs2.gbv.de/DB=47/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&LNG=DU&TRM=$query");

	foreach($content as $zeile)
	{
		//echo $zeile;
		// base-adresse (mit cooky)
		if(strpos($zeile, "<BASE HREF=\"") !== false) {
			$ref = substr($zeile, strpos($zeile, "<BASE HREF=\"") + 12, strlen($zeile) - (strpos($zeile, "<BASE HREF=\"") + 16));
		}
		// anzahl results
		if(strpos($zeile, "&nbsp;von&nbsp;") !== false && strlen($anzHits) === 0) {
			for($i = strpos($zeile, "&nbsp;von&nbsp;") + 15; $i < strlen($zeile); $i++)
			{
				if(is_numeric(substr($zeile, $i, 1)))
					$anzHits .= substr($zeile, $i, 1);
				else	
					break;
			}
		}
		// 1. result - flag setzen
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">1.&nbsp;</TD>") !== false)
			$anfang = true;
		
		// 6. result - schluss
		if(strpos($zeile, "<TD class=\"hit\" align=\"right\" valign=\"top\">6.&nbsp;</TD>") !== false)
			break;
		
		// sonderbehandlung 1. result
		if($naechste)
		{
			$result[0] .= $zeile;
			$naechste = false;
		}
		
		// die results
		if($anfang)
		{
			if(strpos($zeile, "href=\"SHW?FRST=") !== false)
			{
				// flag fuer sonderbehandlung
				if(count($result) == 0)
					$naechste = true;
				else
					$naechste = false;
				$result[] = "<a target=\"_blank\" href=\"".$ref."/".substr($zeile, strpos($zeile, "SHW?FRST="));
			}
		}	
		
		// spezialfall einzelresult
		if(strpos($zeile, "<div>Titel:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Titel:&#xA0;</div>") + 75;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;
	
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=47/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		else {
		if(strpos($zeile, "<div>Zeitschrift:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Zeitschrift:&#xA0;</div>") + 81;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=47/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<div>Aufsatz:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Aufsatz:&#xA0;</div>") + 22;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=47/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		if(strpos($zeile, "<div>Schriftenreihe:&#xA0;</div>") !== false) {
			$startpos = strpos($zeile, "<div>Schriftenreihe:&#xA0;</div>") + 84;
			$endpos = strpos($zeile, "</td></tr>") - $startpos;	
		
			$result[0] = "<a target=\"_blank\" href=\"http://vzlbs2.gbv.de/DB=47/CMD?ACT=SRCHA&IKT=$nach&SRT=YOP&TRM=$query\">";
			$result[0] .= strip_tags(substr($zeile, $startpos, $endpos)) . '</a>';
		}
		}
	}
	

	// ausgabe
	if(count($result) > 0)
	{
		echo "<ol start=\"1\">";
		foreach($result as $t=>$zeile)
		{
			echo "<li>".$zeile."</li>\n";

		}
		echo "</ol><br> (<a href=\"".$ref."/NXT?FRST=1\" target=\"_blank\">insg. ".$anzHits." Hits</a>)<br>\n";
	}
	else
		echo "no result<br>\n";
}

		

?>

