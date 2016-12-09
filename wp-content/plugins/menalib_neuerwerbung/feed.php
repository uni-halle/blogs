<?php
require_once("includes/simple_html_dom.php");
require_once("includes/http_io.php");
require_once("includes/rss.php");

// chache durch ablage der erzeugten feeds als datei  -> prüfen ob datumsstempel älter als 1Tag, dann neu erzeugen sonst datei abrufen

// Die Standard-Zeitzone, die verwendet werden soll, setzen.  (Verfügbar seit PHP 5.1)
date_default_timezone_set('UTC');


// ============================ "SSG Systematik"  table =========================================

include_once('systematik.inc.php');  // erzeugt $content und $content2 -> Systematik tabelle





$select="1."; if(isset($_GET['sel'])) if (isset($content[$_GET['sel']])) $select=$_GET['sel'];
$lang="en"; if(isset($_GET['lan']))  $lang=$_GET['lan'];


//if file is to old or not found generate new
$filename="feed".$select."_".$lang.".xml";
$notallowed=array("/","\\",":","!","?","<",">","[", "]","^","\"","&","´", "´","'", "#");
$filename="cache/".str_replace($notallowed,"_",$filename);

$updatefeed=true;
if(file_exists($filename)){
  $lastmodify = filemtime($filename);
  if (date("U", strtotime("now")- $lastmodify) < 60*60*24) $updatefeed=false;
}

if(!$updatefeed) { echo file_get_contents($filename);  die();}

// =========================== prepare Links and Constants ======================================

$lastmonth=mktime(0, 0, 0, date("m")-1, 1,   date("Y"));
$datestr=date("Ym", $lastmonth);
$pubdate=date("D, d M Y h:i:s +0100", $lastmonth);

$entriesperpage=10;

$opac="https://lhhal.gbv.de";
$query="nel ".$datestr. " and ". urldecode($content[$select]["Query"]); // Suchanfrage
///if (isset($_GET['q']))$query=$_GET['q'];  // TODO
$DB="/DB=1";   //Datenbank
$SET="/SET=1";
$TTL="/TTL=1";
$cmd="CMD";      // Inperpreter Script: "CMD" +ACT=SRCHA  -> suche, "SHW" + FRST=n -> show/anzeige des n. ergebnisses
$ACT="ACT=SRCHA"; //
$IKT="IKT=1016"; // Abgefragte Felder (wenn nicht explizit im suchstring angegeben)
$SRT="SRT=YOP";  //Sortierung
$TRM="TRM=".urlencode($query);


$url=$opac.$DB.$SET.$TTL."/";

// ====================Parse Result Count =======================================================

$website =loadPagePost($opac.$DB.$SET.$TTL."/".$cmd."?".$ACT."&".$IKT."&".$SRT."&".$TRM);
$html=$website['html'];
$cookies=$website['cookies'];
$entries=0;
//echo $html;
if ($html->find("strong[class=pages]",0)!=null){
   $entries = $html->find("strong[class=pages]",0)->plaintext;
   if(isset($entries)) {$entries_split = explode(" ", str_replace("&nbsp;", " ", $entries)); $entries= $entries_split[4];}
  }
// ==============================================================================================

 $rss_items=array();

for($pages=0;$pages<=round($entries/$entriesperpage);$pages++){
     //$url=$opac.$DB.$SET.$TTL."/".$cmd."?".$ACT."&".$IKT."&".$SRT."&".$TRM;
     //$url=$opac."/DB=1/FKT=1016/FRM=nel%2B201409%2Band%2B%28sys%2Bssg2.2%2Bor%2Bsys%2Bssg6.2.%29/IMPLAND=Y/LNG=DU/LRSET=1/SET=1/SID=ea58ede9-1/SRT=YOP/TTL=1/NXT?FRST=11";
     $url= $opac."/DB=1/FKT=1016/FRM=".urlencode(urlencode($query))."/IMPLAND=Y/LNG=DU/LRSET=1/SET=1/SRT=YOP/TTL=1/NXT?FRST=".($pages*$entriesperpage);
     $website =loadPagePost($url, $cookies);
     $html=$website['html'];
     //echo $url;
     //echo $html;
     //die();

     if ($html->find('table[summary="hitlist"]',0))
     foreach($html->find('table[summary="hitlist"]',0)->find('tr[valign="top"]') as $row){
        $atext=$row->find('a',0)->plaintext;
        $adesc=$row->find('td',2)->plaintext;
        $href=$row->find('a',0)->href;
        $href= "https://lhhal.gbv.de/DB=1/FKT=1016/FRM=".urlencode(urlencode($query))."/IMPLAND=Y/LNG=DU/LRSET=1/SET=1/SRT=YOP/TTL=1/".$href;
        array_push($rss_items, array("title"=> $atext, "link"=> $href , "description" => $adesc, "date"=> $pubdate /*"Tue, 21 Oct 2014 12:25:14 +0200"*/));
     }
}




$rss_title=array("de" => "Neuerwerbungen: ".$content[$select]["de"], "en" => "New Acquisitions: ".$content[$select]["en"]) ;
$rss_url=$_SERVER['PHP_SELF'];
$rss_description=array("de" => "Neuerwerbungen des Sondersammelgebietes Vorderer Orient einschl. Nordafrika", "en" =>"New Acquisitions: Special Subject Collection Middle East");
$rss_language=array("de"=> "German","en"=>"English");
$rss_logo_title="Menalib";
$rss_logo_path= "./mena_title.png";
$rss_logo_width= 100;
$rss_logo_height= 100;


 $rsscontent=  rss_feed(utf8_encode($rss_title[$lang]) ."(".$entries.")" , $rss_url, utf8_encode($rss_description[$lang]), $rss_language[$lang],
                        $rss_logo_title, $rss_logo_path, $rss_logo_width, $rss_logo_height,
                        $rss_items);


   file_put_contents($filename, $rsscontent,  LOCK_EX);

 echo file_get_contents($filename);

?>