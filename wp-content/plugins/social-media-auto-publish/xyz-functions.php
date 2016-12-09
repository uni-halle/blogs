<?php


if(!function_exists('xyz_trim_deep'))
{

	function xyz_trim_deep($value) {
		if ( is_array($value) ) {
			$value = array_map('xyz_trim_deep', $value);
		} elseif ( is_object($value) ) {
			$vars = get_object_vars( $value );
			foreach ($vars as $key=>$data) {
				$value->{$key} = xyz_trim_deep( $data );
			}
		} else {
			$value = trim($value);
		}

		return $value;
	}

}

if(!function_exists('esc_textarea'))
{
	function esc_textarea($text)
	{
		$safe_text = htmlspecialchars( $text, ENT_QUOTES );
		return $safe_text;
	}
}

if(!function_exists('xyz_smap_plugin_get_version'))
{
	function xyz_smap_plugin_get_version()
	{
		if ( ! function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin_folder = get_plugins( '/' . plugin_basename( dirname( XYZ_SMAP_PLUGIN_FILE ) ) );
		// 		print_r($plugin_folder);
		return $plugin_folder['social-media-auto-publish.php']['Version'];
	}
}


if(!function_exists('xyz_smap_links')){
	function xyz_smap_links($links, $file) {
		$base = plugin_basename(XYZ_SMAP_PLUGIN_FILE);
		if ($file == $base) {

			$links[] = '<a href="http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/"  title="FAQ">FAQ</a>';
			$links[] = '<a href="http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/"  title="Read Me">README</a>';
			$links[] = '<a href="http://xyzscripts.com/support/" class="xyz_support" title="Support"></a>';
			$links[] = '<a href="http://twitter.com/xyzscripts" class="xyz_twitt" title="Follow us on twitter"></a>';
			$links[] = '<a href="https://www.facebook.com/xyzscripts" class="xyz_fbook" title="Facebook"></a>';
			$links[] = '<a href="https://plus.google.com/+Xyzscripts" class="xyz_gplus" title="+1"></a>';
			$links[] = '<a href="http://www.linkedin.com/company/xyzscripts" class="xyz_linkdin" title="Follow us on linkedIn"></a>';
		}
		return $links;
	}
}


if(!function_exists('xyz_smap_string_limit')){
	
function xyz_smap_string_limit($string, $limit) {

	$space=" ";$appendstr=" ...";
	if(mb_strlen($string) <= $limit) return $string;
	if(mb_strlen($appendstr) >= $limit) return '';
	$string = mb_substr($string, 0, $limit-mb_strlen($appendstr));
	$rpos = mb_strripos($string, $space);
	if ($rpos===false)
		return $string.$appendstr;
	else
		return mb_substr($string, 0, $rpos).$appendstr;
}

}

if(!function_exists('xyz_smap_getimage')){
	
function xyz_smap_getimage($post_ID,$description_org)
{
	$attachmenturl="";
	$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
	if($post_thumbnail_id!="")
	{
		$attachmenturl=wp_get_attachment_url($post_thumbnail_id);
		$attachmentimage=wp_get_attachment_image_src( $post_thumbnail_id, full );

	}
	else {
		preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/is', $description_org, $matches);
		if(isset($matches[1][0]))
			$attachmenturl = $matches[1][0];
		else
		{
			apply_filters('the_content', $description_org);
			preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/is', $description_org, $matches);
			if(isset($matches[1][0]))
				$attachmenturl = $matches[1][0];
		}


	}
	return $attachmenturl;
}

}

if (!function_exists("xyzsmap_getpage")){
		function xyzsmap_getpage($url, $ref='', $ctOnly=false, $fields='', $advSettings='',$ch=false) {
			
		if(!$ch)
		$ch = curl_init($url);
		else 
			curl_setopt($ch, CURLOPT_URL, $url);
		
		$ccURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		static $curl_loops = 0; static $curl_max_loops = 20; global $xyzsmap_gCookiesArr; 
		
		$cookies = ''; 
		if (is_array($xyzsmap_gCookiesArr)) 
		foreach ($xyzsmap_gCookiesArr as $cName=>$cVal)
			$cookies .= $cName.'='.$cVal.'; ';
		
		
		if ($curl_loops++ >= $curl_max_loops){
			$curl_loops = 0; curl_close($ch);return false;
		}
		$headers = array(); 
		
		if ($fields!='')
			$field_type="POST";
		else	
		$field_type="GET";
		

		$headers[] = 'Cache-Control: max-age=0';
		$headers[] = 'Connection: Keep-Alive';
		$headers[]='Referer: '.$url; 
		$headers[]='User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.22 Safari/537.36';
		if($field_type=='POST') 
			$headers[]='Content-Type: application/x-www-form-urlencoded';
		
		if (isset($advSettings['liXMLHttpRequest'])) {
			$headers[] = 'X-Requested-With: XMLHttpRequest';
		}
		if (isset($advSettings['Origin'])) {
			$headers[] = 'Origin: '.$advSettings['Origin'];
		}
		if ($field_type=='GET')
			 $headers[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		else 
			$headers[]='Accept: */*';

		$headers[]='Accept-Encoding: deflate,sdch';
		$headers[] = 'Accept-Language: en-US,en;q=0.8';
		
		
		
		if(isset($advSettings['noSSLSec'])){
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		if(isset($advSettings['proxy']) && $advSettings['proxy']['host']!='' && $advSettings['proxy']['port']!==''){
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP ); curl_setopt( $ch, CURLOPT_PROXY, $advSettings['proxy']['host'] ); 
			curl_setopt( $ch, CURLOPT_PROXYPORT, $advSettings['proxy']['port'] );
			if ( isset($advSettings['proxy']['up']) && $advSettings['proxy']['up']!='' ) {
				curl_setopt( $ch, CURLOPT_PROXYAUTH, CURLAUTH_ANY ); curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $advSettings['proxy']['up'] );
			}
		}
		if(isset($advSettings['headers'])){
			$headers = array_merge($headers, $advSettings['headers']);
		}
		curl_setopt($ch, CURLOPT_HEADER, true);     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, $cookies); curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);  if (is_string($ref) && $ref!='') curl_setopt($ch, CURLOPT_REFERER, $ref);
		curl_setopt($ch, CURLOPT_USERAGENT, (( isset( $advSettings['UA']) && $advSettings['UA']!='')?$advSettings['UA']:"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.44 Safari/537.36"));
		
		if ($fields!=''){
			curl_setopt($ch, CURLOPT_POST, true);curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		} else { curl_setopt($ch, CURLOPT_POST, false); curl_setopt($ch, CURLOPT_POSTFIELDS, '');  curl_setopt($ch, CURLOPT_HTTPGET, true);
		}
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		$content = curl_exec($ch);

		$errmsg = curl_error($ch);  if (isset($errmsg) && stripos($errmsg, 'SSL')!==false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  $content = curl_exec($ch);
		}
		if (strpos($content, "\n\n")!=false && strpos($content, "\n\n")<100)  
			$content = substr_replace($content, "\n", strpos($content,"\n\n"), strlen("\n\n"));
		if (strpos($content, "\r\n\r\n")!=false && strpos($content, "\r\n\r\n")<100) 
			$content = substr_replace($content, "\r\n", strpos($content,"\r\n\r\n"), strlen("\r\n\r\n"));
		$ndel = strpos($content, "\n\n"); $rndel = strpos($content, "\r\n\r\n"); 
		if ($ndel==false) $ndel = 1000000; if ($rndel==false) $rndel = 1000000; $rrDel = $rndel<$ndel?"\r\n\r\n":"\n\n";
		list($header, $content) = explode($rrDel, $content, 2);
		if ($ctOnly!==true) {
			$fullresponse = curl_getinfo($ch); $err = curl_errno($ch); $errmsg = curl_error($ch); $fullresponse['errno'] = $err;  
			$fullresponse['errmsg'] = $errmsg;  $fullresponse['headers'] = $header; $fullresponse['content'] = $content;
		}
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); $headers = curl_getinfo($ch); 
		
		if (empty($headers['request_header'])) $headers['request_header'] = 'Host: None'."\n";
		
		$results = array(); preg_match_all('|Host: (.*)\n|U', $headers['request_header'], $results); 
		$ckDomain = str_replace('.', '_', $results[1][0]);  $ckDomain = str_replace("\r", "", $ckDomain); 
		$ckDomain = str_replace("\n", "", $ckDomain);
		

		$results = array(); $cookies = '';  preg_match_all('|Set-Cookie: (.*);|U', $header, $results); $carTmp = $results[1];
		preg_match_all('/Set-Cookie: (.*)\b/', $header, $xck); $xck = $xck[1]; 
		//$clCook = array();
		if (isset($advSettings['cdomain']) &&  $advSettings['cdomain']!=''){
			foreach ($carTmp as $iii=>$cTmp)
				 if (stripos($xck[$iii],'Domain=')===false || stripos($xck[$iii],'Domain=.'.$advSettings['cdomain'].';')!==false){
				$temp = explode('=',$cTmp,2); $xyzsmap_gCookiesArr[$temp[0]]=$temp[1];
			}
		}
		else { 
		 	foreach ($carTmp as $cTmp){
				$temp = explode('=',$cTmp,2);
				 $xyzsmap_gCookiesArr[$temp[0]]=$temp[1];
		    }
		}
		
		/*foreach ($carTmp as $cTmp){
			$temp = explode('=',$cTmp,2);
		}*/
		
		$rURL = '';

		if ($http_code == 200 && stripos($content, 'http-equiv="refresh" content="0; url=&#39;')!==false ) {
			$http_code=301; $rURL = xyzsmap_substring($content, 'http-equiv="refresh" content="0; url=&#39;','&#39;"');
			 $xyzsmap_gCookiesArr = array();
		}
		elseif ($http_code == 200 && stripos($content, 'location.replace')!==false ) {
			$http_code=301; $rURL = xyzsmap_substring($content, 'location.replace("','"');
		}
		if ($http_code == 301 || $http_code == 302 || $http_code == 303){
			if ($rURL!='') {
				$rURL = str_replace('\x3d','=',$rURL); $rURL = str_replace('\x26','&',$rURL);
				$url = @parse_url($rURL);
			} else { $matches = array(); preg_match('/Location:(.*?)\n/', $header, $matches); $url = @parse_url(trim(array_pop($matches)));
			} $rURL = '';
			if (!$url){
				$curl_loops = 0;curl_close($ch); return ($ctOnly===true)?$content:$fullresponse;
			}
			$last_urlX = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); $last_url = @parse_url($last_urlX);
			if (!$url['scheme']) $url['scheme'] = $last_url['scheme'];  if (!$url['host']) $url['host'] = $last_url['host']; 
			if (!$url['path']) $url['path'] = $last_url['path']; if (!isset($url['query'])) $url['query'] = '';
			$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:''); 
			
			 return xyzsmap_getpage($new_url, $last_urlX, $ctOnly, '', $advSettings, $ch);
		} else { $curl_loops=0;curl_close($ch); return ($ctOnly===true)?$content:$fullresponse;
		}
	}
}
/* Local time formating */
if(!function_exists('xyz_smap_local_date_time')){
	function xyz_smap_local_date_time($format,$timestamp){
		return date($format, $timestamp + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ));
	}
}

add_filter( 'plugin_row_meta','xyz_smap_links',10,2);

if (!function_exists("xyz_smap_is_session_started")) {
function xyz_smap_is_session_started()
{
         if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    
    return FALSE;
}
}
if (!function_exists("xyz_wp_smap_linkedin_attachment_metas")) {
	function xyz_wp_smap_linkedin_attachment_metas($contentln,$url)
	{
		$content_title='';$content_desc='';$utf="UTF-8";$content_img='';
		$aprv_me_data=wp_remote_get($url);
		if( is_array($aprv_me_data) ) {
			$aprv_me_data = $aprv_me_data['body']; // use the content
		}
		else {
			$aprv_me_data='';
		}

		$og_datas = new DOMDocument();
		@$og_datas->loadHTML($aprv_me_data);
		$xpath = new DOMXPath($og_datas);
		if(isset($contentln['content']['title']))
		{
			$ogmetaContentAttributeNodes_tit = $xpath->query("/html/head/meta[@property='og:title']/@content");
			foreach($ogmetaContentAttributeNodes_tit as $ogmetaContentAttributeNode_tit) {
				$content_title=$ogmetaContentAttributeNode_tit->nodeValue;
			}
			if(get_option('xyz_smap_utf_decode_enable')==1)
				$content_title=utf8_decode($content_title);
			// 			if(strcmp(get_option('blog_charset'),$utf)==0)
				// 				$content_title=utf8_decode($content_title);
			if($content_title!='')
				$contentln['content']['title']=$content_title;
		}
		if(isset($contentln['content']['description']))
		{
			$ogmetaContentAttributeNodes_desc = $xpath->query("/html/head/meta[@property='og:description']/@content");
			foreach($ogmetaContentAttributeNodes_desc as $ogmetaContentAttributeNode_desc) {
				$content_desc=$ogmetaContentAttributeNode_desc->nodeValue;
			}
			if(get_option('xyz_smap_utf_decode_enable')==1)
				$content_desc=utf8_decode($content_desc);
			// 			if(strcmp(get_option('blog_charset'),$utf)==0)
				// 				$content_desc=utf8_decode($content_desc);
			if($content_desc!='')
				$contentln['content']['description']=$content_desc;
		}
		/*if(isset($contentln['content']['submitted-image-url']))
		 {
		$ogmetaContentAttributeNodes_img = $xpath->query("/html/head/meta[@property='og:image']/@content");
		foreach($ogmetaContentAttributeNodes_img as $ogmetaContentAttributeNode_img) {
		$content_img=$ogmetaContentAttributeNode_img->nodeValue;
		}
		if($content_img!='')
			$contentln['content']['submitted-image-url']=$content_img;
		}*/
		if(isset($contentln['content']['submitted-url']))
			$contentln['content']['submitted-url']=$url;

		return $contentln;
	}
}
if (!function_exists("xyz_wp_fbap_attachment_metas")) {
	function xyz_wp_fbap_attachment_metas($attachment,$url)
	{
		$name='';$description_li='';$content_img='';$utf="UTF-8";
		$aprv_me_data=wp_remote_get($url);
		if( is_array($aprv_me_data) ) {
			$aprv_me_data = $aprv_me_data['body']; // use the content
		}
		else {
			$aprv_me_data='';
		}

		$og_datas = new DOMDocument();
		@$og_datas->loadHTML($aprv_me_data);
		$xpath = new DOMXPath($og_datas);
		if(isset($attachment['name']))
		{
			$ogmetaContentAttributeNodes_tit = $xpath->query("/html/head/meta[@property='og:title']/@content");

			foreach($ogmetaContentAttributeNodes_tit as $ogmetaContentAttributeNode_tit) {
				$name=$ogmetaContentAttributeNode_tit->nodeValue;

			}
			if(get_option('xyz_smap_utf_decode_enable')==1)
				$name=utf8_decode($name);
			// 			if(strcmp(get_option('blog_charset'),$utf)==0)
				// 				$content_title=utf8_decode($content_title);
			if($name!='')
				$attachment['name']=$name;
		}
		if(isset($attachment['actions']))
		{
			if(isset($attachment['actions']['name']))
			{
				$ogmetaContentAttributeNodes_tit = $xpath->query("/html/head/meta[@property='og:title']/@content");

				foreach($ogmetaContentAttributeNodes_tit as $ogmetaContentAttributeNode_tit) {
					$name=$ogmetaContentAttributeNode_tit->nodeValue;

				}
				if(get_option('xyz_smap_utf_decode_enable')==1)
					$name=utf8_decode($name);
				// 				if(strcmp(get_option('blog_charset'),$utf)==0)
					// 					$content_title=utf8_decode($content_title);
				if($name!='')
					$attachment['actions']['name']=$name;
			}
			if(isset($attachment['actions']['link']))
			{
				$attachment['actions']['link']=$url;
			}
		}
		if(isset($attachment['description']))
		{
			$ogmetaContentAttributeNodes_desc = $xpath->query("/html/head/meta[@property='og:description']/@content");
			foreach($ogmetaContentAttributeNodes_desc as $ogmetaContentAttributeNode_desc) {
				$description_li=$ogmetaContentAttributeNode_desc->nodeValue;
			}
			if(get_option('xyz_smap_utf_decode_enable')==1)
				$description_li=utf8_decode($description_li);
			// 			if(strcmp(get_option('blog_charset'),$utf)==0)
				// 				$content_desc=utf8_decode($content_desc);
			if($description_li!='')
				$attachment['description']=$description_li;
		}
		/*if(isset($attachment['picture']))
		 {
		$ogmetaContentAttributeNodes_img = $xpath->query("/html/head/meta[@property='og:image']/@content");
		foreach($ogmetaContentAttributeNodes_img as $ogmetaContentAttributeNode_img) {
		$content_img=$ogmetaContentAttributeNode_img->nodeValue;
		}
		if($content_img!='')
			$attachment['picture']=$content_img;
		}*/

		if(isset($attachment['link']))
			$attachment['link']=$url;

		return $attachment;
	}
}

if (!function_exists("xyz_smap_split_replace"))
{
	function xyz_smap_split_replace($search, $replace, $subject)//case insensitive
	{
		if(!stristr($subject,$search))
		{
			$search_tmp=str_replace("}", "", $search);
			preg_match_all("@(".preg_quote($search_tmp)."\:)(l|w)\-(\d+)}@i",$subject,$matches); // @ is same as /
			if(is_array($matches) && isset($matches[0]))
			{
				foreach ($matches[0] as $k=>$v)
				{
					$limit=$matches[3][$k];
					if(strcasecmp($matches[2][$k],"l")==0)//lines
					{
						$replace_arr = preg_split( "/(\.|;|\!)/", $replace ,0,PREG_SPLIT_DELIM_CAPTURE );
						if(is_array($replace_arr) && count($replace_arr)>0)
						{
							$replace_new=implode(array_slice($replace_arr,0,(2*$limit)));
							$subject=str_replace($matches[0][$k], $replace_new, $subject);
						}
					}
					if(strcasecmp($matches[2][$k],"w")==0)//words
					{
						$replace_arr=explode(" ",$replace);
						if(is_array($replace_arr) && count($replace_arr)>0)
						{
							$replace_new=implode(" ",array_slice($replace_arr,0,$limit));
							$subject=str_replace($matches[0][$k], $replace_new, $subject);
						}
					}
				}
			}
		}
		else
			$subject=str_replace($search, $replace, $subject);
		return $subject;
	}
}

?>