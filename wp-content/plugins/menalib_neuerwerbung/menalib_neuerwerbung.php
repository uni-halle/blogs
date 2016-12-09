<?php
/*
Plugin Name: MENALIB Neuerwerbungs-Liste
Plugin URI: http://menalib.de/
Description: listet Neuerwerbungen anhand von OPAC-Anfragen 
Version: 1.0
Author: ULB
Author URI: http://bibliothek.uni-halle.de/
Update Server: http://bibliothek.uni-halle.de/
Min WP Version: 1.5
Max WP Version: 4.0.0
*/


 // Die Standard-Zeitzone, die verwendet werden soll, setzen.  (Verfügbar seit PHP 5.1)
date_default_timezone_set('UTC');

//===============================================================================================

// ============================ Print hirachical  =======================================


$recursedepth=0;
function recurseHTMLList($list, $key, $title, $opacUrl, $opacQuery, $datestr ){
  //global $opacUrl, $opacQuery, $datestr;
  global $recursedepth;
  $recursedepth++;

  $onclick="onclick='toggle(this);'";


  $exp= "";
  foreach($list as $ekey => $entry){

  if (isset($entry[$key]))
  if (count($entry[$key])>0)   $exp= "expandable";
  if  ($recursedepth < 3)     $exp.=" expanded"; else $exp.=" notexpanded";

   echo "<li class='".$exp."'>"."<span  ".$onclick." >".$ekey." ".utf8_encode($entry[$title])."</span>";
   echo " <a href='".$opacUrl.$opacQuery.$entry["Query"]."' target='_blank'>".$datestr."</a>";
   echo "<a href='".plugins_url('feed.php',__FILE__ )."?sel=".$ekey."&lan=".$title."' ALT='rss' target='_blank'><span class='rss_neuerwerb'></span></a>";

    if (isset($entry[$key]))
    if (count($entry[$key])>0)
               {
                 echo "<ul id='menalib_neuerwerb'> \n";
                  recurseHTMLList($entry[$key], $key, $title,  $opacUrl, $opacQuery, $datestr);
                 echo "</ul> \n";
               }
   echo "</li> \n";
  }
 $recursedepth--;
}


//===============================================================================================


function neuerwerb_content($lang="de")
{

echo "
<script language='JavaScript'>
<!-- 
function toggle(element)
        {
                    element.parentNode.classList.toggle('expanded');
                    element.parentNode.classList.toggle('notexpanded');
                    /*
                    children=element.getElementsByTagName('li');
                    for (var i = 0; i < children.length; i++) {
                     children[i].classList.toggle('expanded');
                     children[i].classList.toggle('notexpanded');
                    }
                    */

        }

-->
</script>
";

// =========================== prepare Links and Constants ======================================
$opacUrl="https://opac.bibliothek.uni-halle.de/DB=1/CMD?";
$opacQuery="ACT=SRCHA&IKT=1016&SRT=YOP&TRM=";
$date=date("Y"); if (date("m") < 2) $date=date("Y")-1; // letzter monat soll angezeigt werden darum im januar nur bis zum 12. des letzten jahres
$date=$date."?"; if (isset($_GET["date"])) $date=$_GET["date"];  // FORMAT JJJJMM
if (strlen($date)== 4) $date.="?";
$JJ= substr($date, 0, 4);
$MM= "1";
$datestr= date("Y" , mktime(0, 0, 0, $MM, 1, $JJ));

if (strlen($date)== 6) {
    $MM= substr($date, 4, 2);
    $datestr= date("M. Y" , mktime(0, 0, 0, $MM, 1, $JJ));
   }

$datestr="(".$datestr.")";

$opacQuery.=urlencode("nel ".$date." and ");


// ============================= MENU ===========================================================
// TODO: archiv-submenu (menü in Funktion auslagern: parameter "von", "bis" -> aufruf: "<li> Archiv <ul> menu($von, $bis) </ul> </li>")

echo "<div id='menu'>";
echo "<ul>";
$maxyear=date("Y"); if (date("m") < 2) $maxyear=date("Y")-1; // letzter monat soll angezeigt werden darum im januar nur bis zum 12. des letzten jahres
for($year=2014; $year<=$maxyear; $year++){
    echo "<li>";
    echo '<a href="?date='.$year.'&lang='.$lang.'"> '.$year.'</a>';
    if ($year == $JJ)
    {
        echo "<ul>";
        $lastmonth=mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $maxmonth=date("m", $lastmonth); if ($year < date("Y")) $maxmonth=12;
        for($month=1; $month<=$maxmonth; $month++){
          $monstr=str_pad($month,2,"0", STR_PAD_LEFT);
          echo  '<li><a href="?date='.$year.$monstr.'&lang='.$lang.'"> '.$monstr.'</a></li>';
        }
        echo "</ul>";
    }
}
echo  '<li><a href="?old=1"> 1998-2013</a></li>';
echo "</ul>";
echo "</div>";

// ==============================================================================================
include_once('systematik.inc.php');  // erzeugt $content und $content2 -> Systematik tabelle

$content=$content2;
//echo "<pre>"; print_r($content); echo "</pre>";	

// ============================ Print hirachical  =======================================



echo "<ul>";
recurseHTMLList($content['Children'], "Children", $lang, $opacUrl, $opacQuery, $datestr);
echo "</ul>";


}


//===============================================================================================


//[menalib_neuerwerbungen]
function menalib_neuerwerb( $atts ){
	$lang="en";
	if (isset($atts["lang"]))
		if($atts["lang"]="de") 	$lang="de";
	    else   if($atts["lang"]="en") 	$lang="en";		
		
    neuerwerb_content($lang);
	
	
	$text=""; 
	//$text="Hallo Menalib Tabelle!";
	//$text.=print_r ($atts, true);
		
	return $text;
}


//===============================================================================================

function menalib_neuerwerbung_css() {
wp_register_style('menalib_neuerwerbung', plugins_url('menalib_neuerwerbung.css',__FILE__ ));
wp_enqueue_style('menalib_neuerwerbung');
//wp_register_script( 'your_css_and_js', plugins_url('your_script.js',__FILE__ ));
//wp_enqueue_script('your_css_and_js');
}
add_action( 'wp_enqueue_scripts','menalib_neuerwerbung_css');




add_shortcode( 'menalib_neuerwerbungen', 'menalib_neuerwerb' );

?>