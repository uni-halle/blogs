<?php 
if( !defined('ABSPATH') ){ exit();}
/*add_action('publish_post', 'xyz_link_publish');
add_action('publish_page', 'xyz_link_publish');

$xyz_smap_future_to_publish=get_option('xyz_smap_std_future_to_publish');

if($xyz_smap_future_to_publish==1)
	add_action('future_to_publish', 'xyz_link_smap_future_to_publish');

function xyz_link_smap_future_to_publish($post){
	$postid =$post->ID;
	xyz_link_publish($postid);
}*/
add_action(  'transition_post_status',  'xyz_link_smap_future_to_publish', 10, 3 );

function xyz_link_smap_future_to_publish($new_status, $old_status, $post){
	
	if(!isset($GLOBALS['smap_dup_publish']))
		$GLOBALS['smap_dup_publish']=array();
	$postid =$post->ID;
	$get_post_meta=get_post_meta($postid,"xyz_smap",true);                           //	prevent duplicate publishing
	$post_permissin=get_option('xyz_smap_post_permission');
	$post_twitter_permission=get_option('xyz_smap_twpost_permission');
	$lnpost_permission=get_option('xyz_smap_lnpost_permission');
	
	if(isset($_POST['xyz_smap_post_permission']))
		$post_permissin=intval($_POST['xyz_smap_post_permission']);
	if(isset($_POST['xyz_smap_twpost_permission']))
		$post_twitter_permission=intval($_POST['xyz_smap_twpost_permission']);
	if(isset($_POST['xyz_smap_lnpost_permission']))
		$lnpost_permission=intval($_POST['xyz_smap_lnpost_permission']);
	if(!(isset($_POST['xyz_smap_post_permission']) || isset($_POST['xyz_smap_twpost_permission']) || isset($_POST['xyz_smap_lnpost_permission']))) 
	{
	
		if($post_permissin == 1 || $post_twitter_permission == 1 || $lnpost_permission == 1 ) {
			
			if($new_status == 'publish')
			{
				if ($get_post_meta == 1 ) {
					return;
				}
			}
			else return;
		}
	}
	if($post_permissin == 1 || $post_twitter_permission == 1 || $lnpost_permission == 1 )
	{
		if($new_status == 'publish')
		{
			if(!in_array($postid,$GLOBALS['smap_dup_publish'])) {
				$GLOBALS['smap_dup_publish'][]=$postid;
				xyz_link_publish($postid);
			}
		}
			
	}
	else return;		

}

/*$xyz_smap_include_customposttypes=get_option('xyz_smap_include_customposttypes');
$carr=explode(',', $xyz_smap_include_customposttypes);
foreach ($carr  as $cstyps ) {
	add_action('publish_'.$cstyps, 'xyz_link_publish');

}*/

function xyz_link_publish($post_ID) {
	
	$_POST_CPY=$_POST;
	$_POST=stripslashes_deep($_POST);
	
	$post_permissin=get_option('xyz_smap_post_permission');
	if(isset($_POST['xyz_smap_post_permission']))
		$post_permissin=intval($_POST['xyz_smap_post_permission']);
	
	$post_twitter_permission=get_option('xyz_smap_twpost_permission');
	if(isset($_POST['xyz_smap_twpost_permission']))
		$post_twitter_permission=intval($_POST['xyz_smap_twpost_permission']);
	
	$lnpost_permission=get_option('xyz_smap_lnpost_permission');
	if(isset($_POST['xyz_smap_lnpost_permission']))
		$lnpost_permission=intval($_POST['xyz_smap_lnpost_permission']);
	
	if (($post_permissin != 1)&&($post_twitter_permission != 1)&&($lnpost_permission != 1)) {
		$_POST=$_POST_CPY;
		return ;
	
	} else if(( (isset($_POST['_inline_edit'])) || (isset($_REQUEST['bulk_edit'])) ) && (get_option('xyz_smap_default_selection_edit') == 0) ) {
		
		$_POST=$_POST_CPY;
		return;
	}
	
	
	
	$get_post_meta=get_post_meta($post_ID,"xyz_smap",true);
	if($get_post_meta!=1)
		add_post_meta($post_ID, "xyz_smap", "1");

	global $current_user;
	wp_get_current_user();
	$af=get_option('xyz_smap_af');
	
	
/////////////twitter//////////
	$tappid=get_option('xyz_smap_twconsumer_id');
	$tappsecret=get_option('xyz_smap_twconsumer_secret');
	$twid=get_option('xyz_smap_tw_id');
	$taccess_token=get_option('xyz_smap_current_twappln_token');
	$taccess_token_secret=get_option('xyz_smap_twaccestok_secret');
	$messagetopost=get_option('xyz_smap_twmessage');
	if(isset($_POST['xyz_smap_twmessage']))
		$messagetopost=$_POST['xyz_smap_twmessage'];
	$appid=get_option('xyz_smap_application_id');
	
	
	$post_twitter_image_permission=get_option('xyz_smap_twpost_image_permission');
	if(isset($_POST['xyz_smap_twpost_image_permission']))
		$post_twitter_image_permission=intval($_POST['xyz_smap_twpost_image_permission']);
		////////////////////////

	////////////fb///////////
	$app_name=get_option('xyz_smap_application_name');
	$appsecret=get_option('xyz_smap_application_secret');
	$useracces_token=get_option('xyz_smap_fb_token');


	$message=get_option('xyz_smap_message');
	if(isset($_POST['xyz_smap_message']))
		$message=$_POST['xyz_smap_message'];
	//$fbid=get_option('xyz_smap_fb_id');


	
	$posting_method=get_option('xyz_smap_po_method');
	if(isset($_POST['xyz_smap_po_method']))
		$posting_method=intval($_POST['xyz_smap_po_method']);
		//////////////////////////////
		
	////////////linkedin////////////
	
	$lnappikey=get_option('xyz_smap_lnapikey');
	$lnapisecret=get_option('xyz_smap_lnapisecret');
	$lmessagetopost=get_option('xyz_smap_lnmessage');
	if(isset($_POST['xyz_smap_lnmessage']))
		$lmessagetopost=$_POST['xyz_smap_lnmessage'];
	
  $xyz_smap_ln_shareprivate=get_option('xyz_smap_ln_shareprivate'); 
  if(isset($_POST['xyz_smap_ln_shareprivate']))
  $xyz_smap_ln_shareprivate=intval($_POST['xyz_smap_ln_shareprivate']);
 
  $xyz_smap_ln_sharingmethod=get_option('xyz_smap_ln_sharingmethod');
  if(isset($_POST['xyz_smap_ln_sharingmethod']))
  $xyz_smap_ln_sharingmethod=intval($_POST['xyz_smap_ln_sharingmethod']);

    $lnaf=get_option('xyz_smap_lnaf');
	
	$postpp= get_post($post_ID);global $wpdb;
	$reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$entries0 = $wpdb->get_results($wpdb->prepare( 'SELECT user_nicename,display_name FROM '.$wpdb->prefix.'users WHERE ID =%d',$postpp->post_author));
	foreach( $entries0 as $entry ) {			
		$user_nicename=$entry->user_nicename;
		$display_name=$entry->display_name;
	}
	
	if ($postpp->post_status == 'publish')
	{
		$posttype=$postpp->post_type;
		$fb_publish_status=array();
		$ln_publish_status=array();
		$tw_publish_status=array();
		if ($posttype=="page")
		{

			$xyz_smap_include_pages=get_option('xyz_smap_include_pages');
			if($xyz_smap_include_pages==0)
			{$_POST=$_POST_CPY;return;}
		}
			
		else if($posttype=="post")
		{
			$xyz_smap_include_posts=get_option('xyz_smap_include_posts');
			if($xyz_smap_include_posts==0)
			{
				$_POST=$_POST_CPY;return;
			}
			
			$xyz_smap_include_categories=get_option('xyz_smap_include_categories');
			if($xyz_smap_include_categories!="All")
			{
				$carr1=explode(',',$xyz_smap_include_categories);
					
				$defaults = array('fields' => 'ids');
				$carr2=wp_get_post_categories( $post_ID, $defaults );
				$retflag=1;
				foreach ($carr2 as $key=>$catg_ids)
				{
					if(in_array($catg_ids, $carr1))
						$retflag=0;
				}
					
					
				if($retflag==1)
				{$_POST=$_POST_CPY;return;}
			}
		}
		
		else
		{
		
			$xyz_smap_include_customposttypes=get_option('xyz_smap_include_customposttypes');
			if($xyz_smap_include_customposttypes!='')
			{
				$carr=explode(',', $xyz_smap_include_customposttypes);
				
				if(!in_array($posttype, $carr))
				{
					$_POST=$_POST_CPY;return;
				}
			}
			else
			{
				$_POST=$_POST_CPY;return;
			}
		
		}

		include_once ABSPATH.'wp-admin/includes/plugin.php';
		$pluginName = 'bitly/bitly.php';
		
		if (is_plugin_active($pluginName)) {
			remove_all_filters('post_link');
		}
		$link = get_permalink($postpp->ID);



		$xyz_smap_apply_filters=get_option('xyz_smap_std_apply_filters');
		$ar2=explode(",",$xyz_smap_apply_filters);
		$con_flag=$exc_flag=$tit_flag=0;
		if(isset($ar2))
		{
			if(in_array(1, $ar2)) $con_flag=1;
			if(in_array(2, $ar2)) $exc_flag=1;
			if(in_array(3, $ar2)) $tit_flag=1;
		}
		
		$content = $postpp->post_content;
		if($con_flag==1)
			$content = apply_filters('the_content', $content);
		$content = html_entity_decode($content, ENT_QUOTES, get_bloginfo('charset'));
		$excerpt = $postpp->post_excerpt;
		if($exc_flag==1)
			$excerpt = apply_filters('the_excerpt', $excerpt);
		$excerpt = html_entity_decode($excerpt, ENT_QUOTES, get_bloginfo('charset'));
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
		$content=  preg_replace("/\\[caption.*?\\].*?\\[.caption\\]/is", "", $content);
		$content = preg_replace('/\[.+?\]/', '', $content);
		$excerpt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $excerpt);
		
		if($excerpt=="")
		{
			if($content!="")
			{
				$content1=$content;
				$content1=strip_tags($content1);
				$content1=strip_shortcodes($content1);
				
				$excerpt=implode(' ', array_slice(explode(' ', $content1), 0, 50));
			}
		}
		else
		{
			$excerpt=strip_tags($excerpt);
			$excerpt=strip_shortcodes($excerpt);
		}
		$description = $content;
		
		$description_org=$description;
		$attachmenturl=xyz_smap_getimage($post_ID, $postpp->post_content);
		if($attachmenturl!="")
			$image_found=1;
		else
			$image_found=0;
		
		$name = $postpp->post_title;
		$caption=get_bloginfo('title');
		$caption = html_entity_decode($caption, ENT_QUOTES, get_bloginfo('charset'));
		
		if($tit_flag==1)
			$name = apply_filters('the_title', $name);
		$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
		$name=strip_tags($name);
		$name=strip_shortcodes($name);
		
		$description=strip_tags($description);		
		$description=strip_shortcodes($description);
	
	 	$description=str_replace("&nbsp;","",$description);
		
		$excerpt=str_replace("&nbsp;","",$excerpt);
		if($useracces_token!="" && $appsecret!="" && $appid!="" && $post_permissin==1)
		{
			$descriptionfb_li=xyz_smap_string_limit($description, 10000);
			
			$user_page_id=get_option('xyz_smap_fb_numericid');

			$xyz_smap_pages_ids=get_option('xyz_smap_pages_ids');
			if($xyz_smap_pages_ids=="")
				$xyz_smap_pages_ids=-1;

			$xyz_smap_pages_ids1=explode(",",$xyz_smap_pages_ids);


			foreach ($xyz_smap_pages_ids1 as $key=>$value)
			{
				if($value!=-1)
				{
					$value1=explode("-",$value);
					$acces_token=$value1[1];$page_id=$value1[0];
				}
				else
				{
					$acces_token=$useracces_token;$page_id=$user_page_id;
				}

				$fb=new Facebook\Facebook(array(
						'app_id'  => $appid,
						'app_secret' => $appsecret,
						'cookie' => true
				));
				$message1=str_replace('{POST_TITLE}', $name, $message);
				$message2=str_replace('{BLOG_TITLE}', $caption,$message1);
				$message3=str_replace('{PERMALINK}', $link, $message2);
				$message4=str_replace('{POST_EXCERPT}', $excerpt, $message3);
				$message5=str_replace('{POST_CONTENT}', $description, $message4);
				$message5=str_replace('{USER_NICENAME}', $user_nicename, $message5);
				$message5=str_replace('{POST_ID}', $post_ID, $message5);
				$publish_time=get_the_time(get_option('date_format'),$post_ID );
				$message5=str_replace('{POST_PUBLISH_DATE}', $publish_time, $message5);
				$message5=str_replace('{USER_DISPLAY_NAME}', $display_name, $message5);
				$message5=str_replace("&nbsp;","",$message5);
               $disp_type="feed";
				if($posting_method==1) //attach
				{
					$attachment = array('message' => $message5,
							'access_token' => $acces_token,
							'link' => $link,
							'actions' => json_encode(array('name' => $name,
							'link' => $link))

					);
				}
				else if($posting_method==2)  //share link
				{
					$attachment = array('message' => $message5,
							'access_token' => $acces_token,
							'link' => $link,

					);
				}
				else if($posting_method==3) //simple text message
				{
						
					$attachment = array('message' => $message5,
							'access_token' => $acces_token				
					
					);
					
				}
				else if($posting_method==4 || $posting_method==5) //text message with image 4 - app album, 5-timeline
				{
					if($attachmenturl!="")
					{
						

						if($posting_method==5)
						{
							try{
								$album_fount=0;
								
								$albums = $fb->get("/$page_id/albums", $acces_token);
								$arrayResults = $albums->getGraphEdge()->asArray();
								
														
							}
							catch (Exception $e)
							{
								$fb_publish_status[$page_id."/albums"]=$e->getMessage();
									}
							if(isset($arrayResults))
							{
								foreach ($arrayResults as $album) {
									if (isset($album["name"]) && $album["name"] == "Timeline Photos") {
										$album_fount=1;$timeline_album = $album; break;
									}
								}
							}
							if (isset($timeline_album) && isset($timeline_album["id"])) $page_id = $timeline_album["id"];
							if($album_fount==0)
							{
								$attachment = array('name' => "Timeline Photos",
										'access_token' => $acces_token,
								);
								try{
									$album_create=$fb->post('/'.$page_id.'/albums', $attachment);
									$album_node=$album_create->getGraphNode();
									if (isset($album_node) && isset($album_node["id"]))
										$page_id = $album_node["id"];
								}
								catch (Exception $e)
								{
									$fb_publish_status[$page_id."/albums"]=$e->getMessage();
										
								}
									
							}
						}
						else
						{
							try{
								$album_fount=0;
								
								$albums = $fb->get("/$page_id/albums", $acces_token);
								$arrayResults = $albums->getGraphEdge()->asArray();
								
							}
							catch (Exception $e)
							{
								$fb_publish_status[$page_id."/albums"]=$e->getMessage();					
							}
							if(isset($arrayResults))
							{
								foreach ($arrayResults as $album)
								{
									if (isset($album["name"]) && $album["name"] == $app_name) {
										$album_fount=1;
										$app_album = $album; break;
									}
								}
						
							}
							if (isset($app_album) && isset($app_album["id"])) $page_id = $app_album["id"];
							if($album_fount==0)
							{
								$attachment = array('name' => $app_name,
										'access_token' => $acces_token,
								);
								try{
									$album_create=$fb->post('/'.$page_id.'/albums', $attachment);
									$album_node=$album_create->getGraphNode();
									if (isset($album_node) && isset($album_node["id"]))
										$page_id = $album_node["id"];
								}
								catch (Exception $e)
								{
									$fb_publish_status[$page_id."/albums"]=$e->getMessage();
								}
									
							}
						}
						
						
						$disp_type="photos";
						$attachment = array('message' => $message5,
								'access_token' => $acces_token,
								'url' => $attachmenturl	
						
						);
					}
					else
					{
						$attachment = array('message' => $message5,
								'access_token' => $acces_token
						
						);
					}
					
				}
				
				if($posting_method==1 || $posting_method==2)
				{
				
					//$attachment=xyz_wp_fbap_attachment_metas($attachment,$link);
					update_post_meta($post_ID, "xyz_smap_insert_og", "1");
				}
				try{
				$result = $fb->post('/'.$page_id.'/'.$disp_type.'/', $attachment);}
							catch(Exception $e)
							{
								$fb_publish_status[$page_id."/".$disp_type]=$e->getMessage();
							}

			}

			if(count($fb_publish_status)>0)
			  $fb_publish_status_insert=serialize($fb_publish_status);
			else
				$fb_publish_status_insert=1;
			
			$time=time();
			$post_fb_options=array(
					'postid'	=>	$post_ID,
					'acc_type'	=>	"Facebook",
					'publishtime'	=>	$time,
					'status'	=>	$fb_publish_status_insert
			);
			
			$smap_fb_update_opt_array=array();
			
			$smap_fb_arr_retrive=(get_option('xyz_smap_fbap_post_logs'));
			
			$smap_fb_update_opt_array[0]=isset($smap_fb_arr_retrive[0]) ? $smap_fb_arr_retrive[0] : '';
			$smap_fb_update_opt_array[1]=isset($smap_fb_arr_retrive[1]) ? $smap_fb_arr_retrive[1] : '';
			$smap_fb_update_opt_array[2]=isset($smap_fb_arr_retrive[2]) ? $smap_fb_arr_retrive[2] : '';
			$smap_fb_update_opt_array[3]=isset($smap_fb_arr_retrive[3]) ? $smap_fb_arr_retrive[3] : '';
			$smap_fb_update_opt_array[4]=isset($smap_fb_arr_retrive[4]) ? $smap_fb_arr_retrive[4] : '';
			
			array_shift($smap_fb_update_opt_array);
			array_push($smap_fb_update_opt_array,$post_fb_options);
			update_option('xyz_smap_fbap_post_logs', $smap_fb_update_opt_array);
			
			
			
			
		}       


		if($taccess_token!="" && $taccess_token_secret!="" && $tappid!="" && $tappsecret!="" && $post_twitter_permission==1)
		{
			
			////image up start///
         
			$img_status="";
			if($post_twitter_image_permission==1)
			{
				
				$img=array();
				if($attachmenturl!="")
					$img = wp_remote_get($attachmenturl,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
					
				if(is_array($img))
				{
					if (isset($img['body'])&& trim($img['body'])!='')
					{
						$image_found = 1;
							if (($img['headers']['content-length']) && trim($img['headers']['content-length'])!='')
							{
								$img_size=$img['headers']['content-length']/(1024*1024);
								if($img_size>3){$image_found=0;$img_status="Image skipped(greater than 3MB)";}
							}
							
						$img = $img['body'];
					}
					else
						$image_found = 0;
				}
					
			}
			///Twitter upload image end/////
			$messagetopost=str_replace("&nbsp;","",$messagetopost);
			
			$substring="";$islink=0;$issubstr=0;
			
			$substring=xyz_smap_split_replace('{POST_TITLE}', $name, $messagetopost);
			$substring=str_replace('{BLOG_TITLE}', $caption,$substring);
			$substring=str_replace('{PERMALINK}', $link, $substring);
			$substring=xyz_smap_split_replace('{POST_EXCERPT}', $excerpt, $substring);
			$substring=xyz_smap_split_replace('{POST_CONTENT}', $description, $substring);
			$substring=str_replace('{USER_NICENAME}', $user_nicename, $substring);
			$substring=str_replace('{POST_ID}', $post_ID, $substring);
			$publish_time=get_the_time(get_option('date_format'),$post_ID );
			$substring=str_replace('{POST_PUBLISH_DATE}', $publish_time, $substring);
			$substring=str_replace('{USER_DISPLAY_NAME}', $display_name,$substring );
			preg_match_all($reg_exUrl,$substring,$matches); // @ is same as /
			
			if(is_array($matches) && isset($matches[0]))
			{
				$matches=$matches[0];
				$final_str='';
				$len=0;
			    $tw_max_len=get_option('xyz_smap_twtr_char_limit');
				
// 				if($image_found==1)
// 					$tw_max_len=$tw_max_len-24;
			
			if (function_exists('mb_strlen')) {
				foreach ($matches as $key=>$val)
				{
			
						$url_max_len=23;//23 for https and 22 for http
			
					$messagepart=mb_substr($substring, 0, mb_strpos($substring, $val));
			
					if(mb_strlen($messagepart)>($tw_max_len-$len))
					{
						$final_str.=mb_substr($messagepart,0,$tw_max_len-$len-3)."...";
						$len+=($tw_max_len-$len);
						break;
					}
					else
					{
						$final_str.=$messagepart;
						$len+=mb_strlen($messagepart);
					}
			
					$cur_url_len=mb_strlen($val);
					if(mb_strlen($val)>$url_max_len)
						$cur_url_len=$url_max_len;
			
					$substring=mb_substr($substring, mb_strpos($substring, $val)+strlen($val));
					if($cur_url_len>($tw_max_len-$len))
					{
						$final_str.="...";
						$len+=3;
						break;
					}
					else
					{
						$final_str.=$val;
						$len+=$cur_url_len;
					}
			
				}
			
				if(mb_strlen($substring)>0 && $tw_max_len>$len)
				{
			
					if(mb_strlen($substring)>($tw_max_len-$len))
					{
						$final_str.=mb_substr($substring,0,$tw_max_len-$len-3)."...";
					}
					else
					{
						$final_str.=$substring;
					}
				}
			}
			else {
				foreach ($matches as $key=>$val)
				{
					//	if(substr($val,0,5)=="https")
					$url_max_len=23;//23 for https and 22 for http
					// 					else
						// 						$url_max_len=22;//23 for https and 22 for http
						$messagepart=substr($substring, 0, strpos($substring, $val));
						if(strlen($messagepart)>($tw_max_len-$len))
						{
							$final_str.=substr($messagepart,0,$tw_max_len-$len-3)."...";
							$len+=($tw_max_len-$len);
							break;
						}
						else
						{
							$final_str.=$messagepart;
							$len+=strlen($messagepart);
						}
						$cur_url_len=strlen($val);
						if(strlen($val)>$url_max_len)
							$cur_url_len=$url_max_len;
							$substring=substr($substring, strpos($substring, $val)+strlen($val));
							if($cur_url_len>($tw_max_len-$len))
							{
								$final_str.="...";
								$len+=3;
								break;
							}
							else
							{
								$final_str.=$val;
								$len+=$cur_url_len;
							}
				}
				if(strlen($substring)>0 && $tw_max_len>$len)
				{
					if(strlen($substring)>($tw_max_len-$len))
					{
						$final_str.=substr($substring,0,$tw_max_len-$len-3)."...";
					}
					else
					{
						$final_str.=$substring;
					}
					}
				}
			
				$substring=$final_str;
			}
			
				
			$twobj = new SMAPTwitterOAuth(array( 'consumer_key' => $tappid, 'consumer_secret' => $tappsecret, 'user_token' => $taccess_token, 'user_secret' => $taccess_token_secret,'curl_ssl_verifypeer'   => false));
				
			if($image_found==1 && $post_twitter_image_permission==1)
			{

				$url = 'https://upload.twitter.com/1.1/media/upload.json';
				$img_response = wp_remote_get($attachmenturl,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false) );
				if ( is_array( $img_response ) ) {
					$img_body = $img_response['body'];
					$params=array('media_data' =>base64_encode($img_body));
					$code = $twobj->request('POST', $url, $params, true,true);
					if ($code == 200)
					{
						$response = json_decode($twobj->response['response']);
						$media_ids_str = $response->media_id_string;
						$resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array( 'media_ids' => $media_ids_str, 'status' => $substring));
						if($resultfrtw==200)
						{
							if ( $media_ids_str !='')
								$tw_publish_status["statuses/update_with_media"]="<span style=\"color:green\">statuses/update_with_media : Success.</span>";
								else
									$tw_publish_status["statuses/update"]="<span style=\"color:green\">statuses/update : Success.</span>";
						}
						else
							$tw_publish_status["statuses/update_with_media"]="<span style=\"color:red\">statuses/update : ".$twobj->response['response']."</span>";
								
					}
					else
					{
						$tw_publish_status["statuses/update_with_media"]="<span style=\"color:red\">statuses/update : ".$twobj->response['response']."</span>";
					}
				}
				
			}
			else
			{
				$resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array('status' =>$substring));
				
				if($resultfrtw!=200){
					if($twobj->response['response']!="")
						$tw_publish_status["statuses/update"]=print_r($twobj->response['response'], true);
					else
						$tw_publish_status["statuses/update"]=$resultfrtw;
				}
				else if($img_status!="")
					$tw_publish_status["statuses/update_with_media"]=$img_status;
				
				
			}
			
			if(count($tw_publish_status)>0)
				$tw_publish_status_insert=serialize($tw_publish_status);
			else
				$tw_publish_status_insert=1;
			
			$time=time();
			$post_tw_options=array(
					'postid'	=>	$post_ID,
					'acc_type'	=>	"Twitter",
					'publishtime'	=>	$time,
					'status'	=>	$tw_publish_status_insert
			);
			
			$smap_tw_update_opt_array=array();
			
			$smap_tw_arr_retrive=(get_option('xyz_smap_twap_post_logs'));
			
			$smap_tw_update_opt_array[0]=isset($smap_tw_arr_retrive[0]) ? $smap_tw_arr_retrive[0] : '';
			$smap_tw_update_opt_array[1]=isset($smap_tw_arr_retrive[1]) ? $smap_tw_arr_retrive[1] : '';
			$smap_tw_update_opt_array[2]=isset($smap_tw_arr_retrive[2]) ? $smap_tw_arr_retrive[2] : '';
			$smap_tw_update_opt_array[3]=isset($smap_tw_arr_retrive[3]) ? $smap_tw_arr_retrive[3] : '';
			$smap_tw_update_opt_array[4]=isset($smap_tw_arr_retrive[4]) ? $smap_tw_arr_retrive[4] : '';
			
			array_shift($smap_tw_update_opt_array);
			array_push($smap_tw_update_opt_array,$post_tw_options);
			update_option('xyz_smap_twap_post_logs', $smap_tw_update_opt_array);
			
		}
	   
		if($lnappikey!="" && $lnapisecret!=""  && $lnpost_permission==1 && $lnaf==0)
		{	
			$contentln=array();
			
			$description_li=xyz_smap_string_limit($description, 362);
			$caption_li=xyz_smap_string_limit($caption, 200);
			$name_li=xyz_smap_string_limit($name, 200);
				
			$message1=str_replace('{POST_TITLE}', $name, $lmessagetopost);
			$message2=str_replace('{BLOG_TITLE}', $caption,$message1);
			$message3=str_replace('{PERMALINK}', $link, $message2);
			$message4=str_replace('{POST_EXCERPT}', $excerpt, $message3);
			$message5=str_replace('{POST_CONTENT}', $description, $message4);
			$message5=str_replace('{USER_NICENAME}', $user_nicename, $message5);
			
			$publish_time=get_the_time(get_option('date_format'),$post_ID );
			$message5=str_replace('{POST_PUBLISH_DATE}', $publish_time, $message5);
			$message5=str_replace('{POST_ID}', $post_ID, $message5);
			$message5=str_replace('{USER_DISPLAY_NAME}', $display_name, $message5);
			$message5=str_replace("&nbsp;","",$message5);
			$message5=xyz_smap_string_limit($message5, 700);
		
				$contentln['comment'] =$message5;
				$contentln['content']['title'] = $name_li;
				$contentln['content']['submitted-url'] = $link;
				if($attachmenturl!="")
				$contentln['content']['submitted-image-url'] = $attachmenturl;
				$contentln['content']['description'] = $description_li;
		
		
		
			if($xyz_smap_ln_shareprivate==1)
			{
				$contentln['visibility']['code']='connections-only';
			}
			else
			{
			$contentln['visibility']['code']='anyone';
			}
		
		$xyz_smap_application_lnarray=get_option('xyz_smap_application_lnarray');
	
		
		$ln_acc_tok_arr=json_decode($xyz_smap_application_lnarray);
		$xyz_smap_application_lnarray=$ln_acc_tok_arr->access_token;
		$ln_publish_status=array();

		$ObjLinkedin = new SMAPLinkedInOAuth2($xyz_smap_application_lnarray);
		//$contentln=xyz_wp_smap_linkedin_attachment_metas($contentln,$link);
		if($xyz_smap_ln_sharingmethod==0)
		{
				try{
				$arrResponse = $ObjLinkedin->shareStatus($contentln);
						if ( isset($arrResponse['errorCode']) && isset($arrResponse['message']) && ($arrResponse['message']!='') ) {//as per old api ; need to confirm which is correct
							$ln_publish_status["new"]=$arrResponse['message'];
						}
						if(isset($response2['error']) && $response2['error']!="")//as per new api ; need to confirm which is correct
							$ln_publish_status["new"]=$response2['error'];

				}
			catch(Exception $e)
			{
				$ln_publish_status["new"]=$e->getMessage();
			}
			
		}
		else
		{ 
		$description_liu=xyz_smap_string_limit($description, 950);
		try{
		     $response2=$OBJ_linkedin->updateNetwork($description_liu);
		   }
			catch(Exception $e)
			{
				$ln_publish_status["updateNetwork"]=$e->getMessage();
			}
			
			if(isset($response2['error']) && $response2['error']!="")
				$ln_publish_status["updateNetwork"]=$response2['error'];
		}
		
		if(count($ln_publish_status)>0)
			$ln_publish_status_insert=serialize($ln_publish_status);
		else
			$ln_publish_status_insert=1;
		
		$time=time();
		$post_ln_options=array(
				'postid'	=>	$post_ID,
				'acc_type'	=>	"Linkedin",
				'publishtime'	=>	$time,
				'status'	=>	$ln_publish_status_insert
		);
		
		$smap_ln_update_opt_array=array();
		
		$smap_ln_arr_retrive=(get_option('xyz_smap_lnap_post_logs'));
		
		$smap_ln_update_opt_array[0]=isset($smap_ln_arr_retrive[0]) ? $smap_ln_arr_retrive[0] : '';
		$smap_ln_update_opt_array[1]=isset($smap_ln_arr_retrive[1]) ? $smap_ln_arr_retrive[1] : '';
		$smap_ln_update_opt_array[2]=isset($smap_ln_arr_retrive[2]) ? $smap_ln_arr_retrive[2] : '';
		$smap_ln_update_opt_array[3]=isset($smap_ln_arr_retrive[3]) ? $smap_ln_arr_retrive[3] : '';
		$smap_ln_update_opt_array[4]=isset($smap_ln_arr_retrive[4]) ? $smap_ln_arr_retrive[4] : '';
		
		array_shift($smap_ln_update_opt_array);
		array_push($smap_ln_update_opt_array,$post_ln_options);
		update_option('xyz_smap_lnap_post_logs', $smap_ln_update_opt_array);
		
		}
	}
	
	$_POST=$_POST_CPY;
}

?>
