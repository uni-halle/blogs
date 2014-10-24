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
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
}

if(isset($dom)&&isset($q)) echo str_replace(['> <',@HEAD,@TITLE,@META,@HTML,@Xhtml],['><',@head,@title,@meta,@html,@XHTML],get_url("$prot://$dom.uni-halle.de/$q"));

