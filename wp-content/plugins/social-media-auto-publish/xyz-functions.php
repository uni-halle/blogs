<?php
if( !defined('ABSPATH') ){ exit();}
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

			$links[] = '<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/"  title="FAQ">FAQ</a>';
			$links[] = '<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/"  title="Read Me">README</a>';
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
		$aprv_me_data=wp_remote_get($url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
		if( is_array($aprv_me_data) ) {
			$aprv_me_data = $aprv_me_data['body']; // use the content
		}
		else {
			$aprv_me_data='';
		}

		$og_datas = new DOMDocument();
		@$og_datas->loadHTML('<?xml encoding="UTF-8">'.$aprv_me_data);
		
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
		$aprv_me_data=wp_remote_get($url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
		if( is_array($aprv_me_data) ) {
			$aprv_me_data = $aprv_me_data['body']; // use the content
		}
		else {
			$aprv_me_data='';
		}

		$og_datas = new DOMDocument();
		@$og_datas->loadHTML('<?xml encoding="UTF-8">'.$aprv_me_data);
		$xpath = new DOMXPath($og_datas);
		/* if(isset($attachment['name']))
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
		} */
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
	/* 	if(isset($attachment['description']))
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
		} */
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