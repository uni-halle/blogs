<?php
$headers = array();

$handle = opendir(getcwd());
while ($file=readdir($handle))
{
	if ($file != "." && $file != "..")
	{
		if ( get_ext($file) == 'jpg' && !preg_match('/thumbnail/',$file) )
		{
			$headers[] = $file;
		}
	}
}

function get_ext($file="")
{
	return substr($file,(strrpos($file,".")?strrpos($file,".")+1:strlen($file)),strlen($file)); 
}

header("Content-type: image/jpeg");

$total = count($headers);
$random = (mt_rand()%$total);

readfile($headers[$random]); 
?>