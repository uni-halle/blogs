<?php
/*
 * this file contains common functions being used in plugin
 */

/*
 * printing array elements in humane form
 */
function pa($arr){
	
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}


/*
 ** to fix url re-occuring, written by Naseer sb
*/

function fixRequestURI($vars){
	$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
	$parts = explode("?", $uri);

	$qsArr = array();
	if(isset($parts[1])){	////// query string present explode it
		$qsStr = explode("&", $parts[1]);
		foreach($qsStr as $qv){
			$p = explode("=",$qv);
			$qsArr[$p[0]] = $p[1];
		}
	}

	//////// updatig query string
	foreach($vars as $key=>$val){
		if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;
	}

	////// rejoin query string
	$qsStr="";
	foreach($qsArr as $key=>$val){
		$qsStr.=$key."=".$val."&";
	}
	if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);
	$uri = $parts[0];
	if($qsStr!="") $uri.="?".$qsStr;
	return $uri;
}


/*
 * time elapsed
*/

function time_difference($date)
{
	if(empty($date)) {
		return "No date provided";
	}

	$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths         = array("60","60","24","7","4.35","12","10");

	$now             = time();
	$unix_date         = strtotime($date);

	// check validity of date
	if(empty($unix_date)) {
		return "Bad date";
	}

	// is it future date or past date
	if($now > $unix_date) {
		$difference     = $now - $unix_date;
		$tense         = "ago";

	} else {
		$difference     = $unix_date - $now;
		$tense         = "from now";
	}

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		$periods[$j].= "s";
	}

	return "$difference $periods[$j] {$tense}";
}


function getFileSize($path, $files)
{
	$size = 0;
	$arr = explode(',', $files);
	foreach($arr as $f)
	{
		$size = $size + filesize($path . $f);
	}

	return $size;
}

function purifyFileMeta($metaData, $metaID){
		
	//add metaid for ref in admin
	$metaData['metaid'] = $metaID;
	return stripslashes(json_encode($metaData));
}

function sizeInKB($size)
{
	return round($size / 1024, 2) .' KB';
}


/*
 * this function is building Home link for DirTree
 */

function get_homelink($userid = false, $userlogin = false){
	
	if(!$userid){
		
		return get_permalink();
	}else{
		
		return get_admin_url('', 'admin.php?page=nm-repo-files&userid='.$userid.'&userlogin='.$userlogin);
	}
}