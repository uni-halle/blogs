<?php
// =============  Parse Cookies =================

function ParseHeaderCookies($response_header){
  $ResCookies = array();
  foreach ($response_header as $hdr) {
      if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
          parse_str($matches[1], $tmp);
          $ResCookies += $tmp;
      }
  }
   return $ResCookies;
}
// ==============================================


function loadPage($url){
   $str = file_get_contents($url);
   $header = $http_response_header;
   $res['cookies'] = ParseHeaderCookies($header);
   $res['html'] = str_get_html($str);
   return $res;
}

// ==============================================


function loadPagePost($url, $cookies = array(), $data = array() ){

    // == formating cookies ==========

    $cookiestr ="Cookie: ";
    foreach($cookies as $cookie=> $value)  $cookiestr = $cookiestr.$cookie."=".$value;
    $cookiestr = $cookiestr."\r\n";

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . $cookiestr,
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $header = $http_response_header;
    $res['cookies'] = ParseHeaderCookies($header);
    $res['html'] = str_get_html($result);
    return $res;
}


// ==============================================

?>