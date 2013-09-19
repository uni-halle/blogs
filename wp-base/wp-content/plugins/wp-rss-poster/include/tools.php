<?php
//get wrp data from database by jobid
function wrp_get_data($jobid){
  global $wpdb;
  $wrp_table = $wpdb->prefix.'wrp';
  $query = "SELECT * FROM $wrp_table WHERE id=".$jobid;
  $data = $wpdb->get_row($query, ARRAY_A);	
  return $data;
}

//DoJob
function wrp_do_job($jobid) {
	global $wrp_dojob_message;
	if (empty($jobid))
		return false;
	require_once(dirname(__FILE__).'/wrp_dojob.php');
	$wrp_dojob= new wrp_dojob($jobid);
	unset($wrp_dojob);
	return $wrp_dojob_message;
}

function wrp_connect($service){
    $wrp_opts = get_option(WRP_OPTIONS);
        
    switch($service){
	  case 'tw':   
	   if(!isset($_GET['resp'])){
	    $tw = new TwitterOAuth("i8aDsTg4etTCDHrvyxTjg", "kZfkPAwQJc0nj97D77Bd0qZtEr5nMUgepIWy6MtMMlY");
	    $twrt = $tw->getRequestToken(admin_url( 'admin.php?page=wrp-twitter&connect=tw&resp=1' ));
		$loginUrl = $tw->getAuthorizeURL($twrt);
		
		$wrp_opts['twrt'] = $twrt['oauth_token'];
		$wrp_opts['twrts'] = $twrt['oauth_token_secret'];;
		update_option(WRP_OPTIONS, $wrp_opts);	
		wp_redirect($loginUrl);
	   }else{
		    $tw = new TwitterOAuth("i8aDsTg4etTCDHrvyxTjg", "kZfkPAwQJc0nj97D77Bd0qZtEr5nMUgepIWy6MtMMlY", $wrp_opts['twrt'], $wrp_opts['twrts']);	        
	        $twrt = $tw->getAccessToken($_GET['oauth_verifier']);
			$_SESSION['access_token'] = $twrt; // saving access token to sessions

            $user = $tw->get('account/verify_credentials'); // get account details
			$wrp_opts['twat'] = $twrt['oauth_token'];
			$wrp_opts['twats'] = $twrt['oauth_token_secret'];
			$wrp_opts['tw_username'] = $user->screen_name;
			$wrp_opts['tw_profile'] = $user->profile_image_url;
			update_option(WRP_OPTIONS, $wrp_opts);			
			//session_unset();
			$redirect = admin_url( 'admin.php?page=wrp-twitter' ); 
            wp_redirect($redirect);   
	   }	
	  break;	
	  case 'fb':
	     
	     if(isset($_GET['resp'])){
			$session_array = explode(",", stripslashes($_GET['session']));
		    $fb_session = array();
		    foreach($session_array as $session_value){
			   	list($key, $value) = explode(":", $session_value);
			   	$key = trim($key, '"');
			   	$value = trim($value, '"');
			   	if($key == 'uid' || $key == 'access_token'){
				  $fb_session[$key] = $value;
				}			   	
			}
			
			//print_r($fb_session);
			//$wrp_opts = get_option(WRP_OPTIONS);
			//var_dump($wrp_opts);
		    $opts = array_merge($wrp_opts, $fb_session);		    
		    update_option(WRP_OPTIONS, $opts);    
		    $redirect = admin_url( 'admin.php?page=wrp-facebook' ); 
            wp_redirect($redirect);    	 
		 }
	     /*
	           # Creating the facebook object
               $facebook = new Facebook(array(
	                'appId'  => FB_APP_ID,
	                'secret' => FB_APP_SECRET,
	                'cookie' => true
               ));

               # Let's see if we have an active session
               $session = $facebook->getSession(); 
	           
	           if(!empty($session)) {
             	# Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
	            try{
	        	$uid = $facebook->getUser();
		        $user = $facebook->api('/me');
		
		        $wrp_opts['fb_userid'] = $user['id'];
		        $wrp_opts['fb_name'] = $user['name'];
		        $wrp_opts['fb_uid'] = $uid;
		        $wrp_opts['fb_access_token'] = $session['access_token'];
		         
		        $redirect = admin_url( 'admin.php?page=wrp-facebook' ); 
		        # req_perms is a comma separated list of the permissions needed
		        $url = $facebook->getLoginUrl(array(
			         'req_perms' => 'publish_stream',
                     'next' => $redirect,  
                     'cancel_url' => $redirect   
		         ));
		         header("Location: {$url} ");
	            } catch (Exception $e){}
               } else {
	              # There's no active session, let's generate one
	              $login_url = $facebook->getLoginUrl();
	              header("Location: ".$login_url);
               }
               */ 
	  break;
	  
	}
}

function wrp_disconnect($service){
   $wrp_opts = get_option(WRP_OPTIONS);
   switch($service){
	  case 'tw':	    
	    unset($wrp_opts['twrt']);
		unset($wrp_opts['twrts']);
	    unset($wrp_opts['twat']);
		unset($wrp_opts['twats']);
		unset($wrp_opts['tw_username']);
		unset($wrp_opts['tw_profile']);	    
	    update_option(WRP_OPTIONS, $wrp_opts);
		wp_redirect(admin_url( 'admin.php?page=wrp-twitter' ));
	  break;  
	  case 'fb':
	    unset($wrp_opts['uid']);
		unset($wrp_opts['access_token']);	    	    
	    update_option(WRP_OPTIONS, $wrp_opts);
		wp_redirect(admin_url( 'admin.php?page=wrp-facebook' ));
	  break; 
   }	
}

//file size
function wrp_formatBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);
	return round($bytes, $precision) . ' ' . $units[$pow];
}

function wrp_get_file($url){

	if(ini_get('allow_url_fopen') != 1) {
		@ini_set('allow_url_fopen', '1');
	}

	if(ini_get('allow_url_fopen') != 1) {
		
		$ch = curl_init();
 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
 
		$data = curl_exec($ch);
		curl_close($ch);
 
		return $data;

    
	} else {
		return @file_get_contents($url);
	}
	
	return false;

 
}

//////////////////////////////////////////////
// Convert $html to UTF8
// (uses HTTP headers and HTML to find encoding)
// adapted from http://stackoverflow.com/questions/910793/php-detect-encoding-and-make-everything-utf-8
//////////////////////////////////////////////
function wrp_convert_to_utf8($html, $header=null)
{
	$accept = array(
		'type' => array('application/rss+xml', 'application/xml', 'application/rdf+xml', 'text/xml', 'text/html'),
		'charset' => array_diff(mb_list_encodings(), array('pass', 'auto', 'wchar', 'byte2be', 'byte2le', 'byte4be', 'byte4le', 'BASE64', 'UUENCODE', 'HTML-ENTITIES', 'Quoted-Printable', '7bit', '8bit'))
	);
	$encoding = null;
	if ($html || $header) {
		if (is_array($header)) $header = implode("\n", $header);
		if (!$header || !preg_match_all('/^Content-Type:\s+([^;]+)(?:;\s*charset=([^;"\'\n]*))?/im', $header, $match, PREG_SET_ORDER)) {
			// error parsing the response
		} else {
			$match = end($match); // get last matched element (in case of redirects)
			if (!in_array(strtolower($match[1]), $accept['type'])) {
				// type not accepted
				// TODO: avoid conversion
			}
			if (isset($match[2])) $encoding = trim($match[2], '"\'');
		}
		if (!$encoding) {
			if (preg_match('/^<\?xml\s+version=(?:"[^"]*"|\'[^\']*\')\s+encoding=("[^"]*"|\'[^\']*\')/s', $html, $match)) {
				$encoding = trim($match[1], '"\'');
			} elseif(preg_match('/<meta\s+http-equiv=["\']Content-Type["\'] content=["\'][^;]+;\s*charset=([^;"\'>]+)/i', $html, $match)) {
				if (isset($match[1])) $encoding = trim($match[1]);
			}
		}
		if (!$encoding) {
			$encoding = 'utf-8';
		} else {
			if (!in_array($encoding, array_map('strtolower', $accept['charset']))) {
				// encoding not accepted
				// TODO: avoid conversion
			}
			if (strtolower($encoding) != 'utf-8') {
				if (strtolower($encoding) == 'iso-8859-1') {
					// replace MS Word smart qutoes
					$trans = array();
					$trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
					$trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
					$trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
					$trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
					$trans[chr(134)] = '&dagger;';    // Dagger
					$trans[chr(135)] = '&Dagger;';    // Double Dagger
					$trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
					$trans[chr(137)] = '&permil;';    // Per Mille Sign
					$trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
					$trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
					$trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
					$trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
					$trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
					$trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
					$trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
					$trans[chr(149)] = '&bull;';    // Bullet
					$trans[chr(150)] = '&ndash;';    // En Dash
					$trans[chr(151)] = '&mdash;';    // Em Dash
					$trans[chr(152)] = '&tilde;';    // Small Tilde
					$trans[chr(153)] = '&trade;';    // Trade Mark Sign
					$trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
					$trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
					$trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
					$trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
					$html = strtr($html, $trans);
				}
				if(!class_exists('SimplePie_Misc'))
					require_once(RPFINC.'simplepie.class.php');

				$html = SimplePie_Misc::change_encoding($html, $encoding, 'utf-8');

				/*
				if (function_exists('iconv')) {
					// iconv appears to handle certain character encodings better than mb_convert_encoding
					$html = iconv($encoding, 'utf-8', $html);
				} else {
					$html = mb_convert_encoding($html, 'utf-8', $encoding);
				}
				*/
			}
		}
	}
	return $html;
}

function wrp_full_feed($permalink, &$campagin, &$item){

	require_once('readability.php');

	if ($permalink && $html = wrp_get_file($permalink)) {
		
		$html = wrp_convert_to_utf8($html);

		$content = grabArticleHtml($html);

		
	}else
		return false;

	if( false !== stripos($content,'readability was unable to parse this page for content') )
		return false;
	if( false !== stripos($content, 'return go_back();') )
		return false;
	
	
	$origin_array = explode(",", $$campagin['origin']);
	$rewrite_array = explode(",", $$campagin['rewrite']);
	
	foreach($origin_array as $key => $value){
	  	$content = str_ireplace(trim($value), stripslashes(trim($rewrite_array[$key])), $content);
	  	
	}	
	if($campagin['source_link'] == 'true'){
		$content .= '<br />Source Article from <a href="'.$permalink.'">'.$permalink.'</a>';
	}	
	
	/*
	if($campaign['cache_image'] == 'true') {
		$content=wrp_content_fix($content);
	    $content=wrp_parse_images($content,$item->get_base());	
	 }
	*/
	
	return $content;

}

function wrp_content_fix($text){
	preg_match_all('@(<a.+?href=\".+?\">)(.*?</a>)@',$text,$m);
	$urls = $m[1];
	if(count($urls)){
		foreach($urls as $pos => $link){
			if(false === stripos($link,'http://') && false === stripos($link,'https://')){
		
				$text=str_replace($link,'',$text);
		
				$text=str_replace($m[2][$pos],str_replace('</a>','',$m[2][$pos]),$text);
			}
		}
	}
	$text=preg_replace("/[&|#|&#]+[a-z0-9]+;/i","",$text);
	$text=preg_replace('@<[dtrx][^>]*>@','',$text);
	$text=preg_replace('@</[dtrx][^>]*>@','',$text);
	return $text;

}

function wrp_parse_images($content,$link){
	
	preg_match_all('/<img(.+?)src=\"(.+?)\"(.*?)>/', $content, $images);
	$urls = $images[2];
       
      	if(count($urls)){

		foreach($urls as $pos => $url){
			$oldurl=$url;
			$meta=parse_url($url);
			
			if(!isset($meta['host'])){

				$meta=parse_url($link);
				$url=$meta['scheme'].'://'.$meta['host'].'/'.$url;
				
   			}
			
          		$newurl = wrp_cache_image($url);
          		if($newurl)
            			$content = str_replace($oldurl, $newurl, $content);
			else
				$content = str_replace($images[0][$pos],'',$content);
        	} 
        }
	return $content;
   	


} 	

function wrp_cache_image($url){
	if( strpos($url, "icon_") !== FALSE)
	      return false;
	global $rpf_options;
	$contents = wrp_get_file($url);
	
	if( !$contents )
		return false;
	$basename = basename($url);
	$paresed_url = parse_url($basename);
	
	$filename = substr(md5(time()), 0, 5) . '_' . $paresed_url['path'];
    	
	
	$pluginpath = WRP_URL;
    	$real_cachepath=dirname(dirname(__FILE__)).'/cache';
	if(is_writable(	$real_cachepath ) ){
		
		if($contents){

			file_put_contents($real_cachepath . $filename, $contents);
			$i=@exif_imagetype($real_cachepath . $filename);
			if($i)
				return $pluginpath . $cachepath . rawurlencode($filename);
		}
	}else{
		
		echo " directory is not writable";
		
	}
    
	return false;

}

function wrp_log($message){
   
}

//*********************************************************************************************************
  /**
   * Parses a feed with SimplePie
   *
   * @param   boolean     $stupidly_fast    Set fast mode. Best for checks
   * @param   integer     $max              Limit of items to fetch
   * @return  SimplePie_Item    Feed object
   **/
  function fetchFeed($url, $stupidly_fast = false, $max = 0) {
    # SimplePie

	if (!class_exists('SimplePie')) {
		if (is_file(trailingslashit(ABSPATH).'wp-admin/includes/class-simplepie.php'))
			include_once( trailingslashit(ABSPATH).'wp-admin/includes/class-simplepie.php' );
		else
			//include_once('class-simplepie.php');
			include_once('simplepie.class.php');
	}		
    $feed = new SimplePie();
    $feed->enable_order_by_date(false);
    $feed->set_feed_url($url);
    $feed->set_item_limit($max);
    $feed->set_stupidly_fast(true);
    $feed->enable_cache(false);    
    $feed->init();
    $feed->handle_content_type(); 
    
    return $feed;
  }

function wrp_tweet($oauth_token, $oauth_token_secret, $message){	
	$tw = new TwitterOAuth(TW_KEY, TW_SECRET, $oauth_token, $oauth_token_secret);
  	$response = $tw->post('statuses/update', array('status' => $message));
}

//create bit.ly url
function wrp_bitly($url)
{
	//login information
	$login = 'darell';	//your bit.ly login
	$apikey = 'R_7edc48413e51301369ad7a52be587262'; //bit.ly apikey
	$format = 'json';	//choose between json or xml
	$version = '2.0.1';

	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;

	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);

	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

function wrp_fb_post($title, $link, $content){
    $wrp_opts = get_option(WRP_OPTIONS);
    $fb = new Facebook(array(
	'appId'  => FB_APP_ID,
	'secret' => FB_APP_SECRET	
    ));
    $args['access_token'] = $wrp_opts['access_token'];
	//$args['message'] = 'The Message';
	$args['name'] = $title;
	//$args['caption'] = 'The caption';
	$args['link'] = $link;
    $fbpost = $fb->api('/me/feed/', 'post', $args);
    	
}
?>
