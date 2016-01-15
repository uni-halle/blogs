<?php

extract($_GET);

if(!in_array(@$prot,[@http,@https])) $prot=@http;

if(!function_exists('get_url')) {
  function get_url($url) {
    $ch = curl_init();

    if($ch === false) die('Failed to create curl object');

    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    list($header, $body) = explode("\r\n\r\n", $data, 2);
    $headers=explode("\n",$header);
    $headersToSend=['Content-Type','Content-Length','Last-Modified','HTTP'];
    array_walk($headers,function($h)use($headersToSend){
      foreach($headersToSend as $send) {
        if(stripos($h,$send)===0) return header($h);
      }
    });
    curl_close($ch);
    return $body;
  }
}
#header("x-uri: ".$_SERVER['QUERY_STRING']);

$qry=explode('?',$_SERVER['REQUEST_URI'],2)[1];
$postprocess=in_array(@$utf,['en','de'])?"utf8_${utf}code":false;
header('x-PP: ' . $postprocess);
header("x-req: ".$_SERVER['REQUEST_URI']);
header('x-qry:' .$qry);
header('x-q: '.$q);
if(isset($dom)&&in_array($dom,['www','www2.usz','minerva.kunstgesch'])&&isset($q)) $raw=str_replace(['> <',@HEAD,@TITLE,@META,@HTML,@Xhtml],['><',@head,@title,@meta,@html,@XHTML],get_url("$prot://$dom.uni-halle.de/$q".($qry?"?$qry":'')));

echo ($postprocess && strpos($raw,"<html>")!==false)?$postprocess($raw):$raw;


