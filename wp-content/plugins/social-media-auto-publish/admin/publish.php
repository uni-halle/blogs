<?php 

add_action('publish_post', 'xyz_link_publish');
add_action('publish_page', 'xyz_link_publish');

$xyz_smap_future_to_publish=get_option('xyz_smap_std_future_to_publish');

if($xyz_smap_future_to_publish==1)
	add_action('future_to_publish', 'xyz_link_smap_future_to_publish');

function xyz_link_smap_future_to_publish($post){
	$postid =$post->ID;
	xyz_link_publish($postid);
}


$xyz_smap_include_customposttypes=get_option('xyz_smap_include_customposttypes');
$carr=explode(',', $xyz_smap_include_customposttypes);
foreach ($carr  as $cstyps ) {
	add_action('publish_'.$cstyps, 'xyz_link_publish');

}

function xyz_link_publish($post_ID) {
	
	$_POST_CPY=$_POST;
	$_POST=stripslashes_deep($_POST);
	
	$post_permissin=get_option('xyz_smap_post_permission');
	if(isset($_POST['xyz_smap_post_permission']))
		$post_permissin=$_POST['xyz_smap_post_permission'];
	
	$post_twitter_permission=get_option('xyz_smap_twpost_permission');
	if(isset($_POST['xyz_smap_twpost_permission']))
		$post_twitter_permission=$_POST['xyz_smap_twpost_permission'];
	
	$lnpost_permission=get_option('xyz_smap_lnpost_permission');
	if(isset($_POST['xyz_smap_lnpost_permission']))
		$lnpost_permission=$_POST['xyz_smap_lnpost_permission'];
	
	if (($post_permissin != 1)&&($post_twitter_permission != 1)&&($lnpost_permission != 1)) {
		$_POST=$_POST_CPY;
		return ;
	
	} else if (isset($_POST['_inline_edit']) AND (get_option('xyz_smap_default_selection_edit') == 0) ) {
		$_POST=$_POST_CPY;
		return;
	}
	
	
	
	$get_post_meta=get_post_meta($post_ID,"xyz_smap",true);
	if($get_post_meta!=1)
		add_post_meta($post_ID, "xyz_smap", "1");

	global $current_user;
	get_currentuserinfo();
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
		$post_twitter_image_permission=$_POST['xyz_smap_twpost_image_permission'];
		////////////////////////

	////////////fb///////////
	$appsecret=get_option('xyz_smap_application_secret');
	$useracces_token=get_option('xyz_smap_fb_token');


	$message=get_option('xyz_smap_message');
	if(isset($_POST['xyz_smap_message']))
		$message=$_POST['xyz_smap_message'];
	$fbid=get_option('xyz_smap_fb_id');


	
	$posting_method=get_option('xyz_smap_po_method');
	if(isset($_POST['xyz_smap_po_method']))
		$posting_method=$_POST['xyz_smap_po_method'];
		//////////////////////////////
		
	////////////linkedin////////////
	
	$lnappikey=get_option('xyz_smap_lnapikey');
	$lnapisecret=get_option('xyz_smap_lnapisecret');
	$lmessagetopost=get_option('xyz_smap_lnmessage');
	if(isset($_POST['xyz_smap_lnmessage']))
		$lmessagetopost=$_POST['xyz_smap_lnmessage'];
	
  $xyz_smap_ln_shareprivate=get_option('xyz_smap_ln_shareprivate'); 
  if(isset($_POST['xyz_smap_ln_shareprivate']))
  $xyz_smap_ln_shareprivate=$_POST['xyz_smap_ln_shareprivate'];
 
  $xyz_smap_ln_sharingmethod=get_option('xyz_smap_ln_sharingmethod');
  if(isset($_POST['xyz_smap_ln_sharingmethod']))
  $xyz_smap_ln_sharingmethod=$_POST['xyz_smap_ln_sharingmethod'];
  

  $post_ln_image_permission=get_option('xyz_smap_lnpost_image_permission');
  if(isset($_POST['xyz_smap_lnpost_image_permission']))
  	$post_ln_image_permission=$_POST['xyz_smap_lnpost_image_permission'];

    $lnaf=get_option('xyz_smap_lnaf');
	
	$postpp= get_post($post_ID);global $wpdb;
	$entries0 = $wpdb->get_results( 'SELECT user_nicename FROM '.$wpdb->prefix.'users WHERE ID='.$postpp->post_author);
	foreach( $entries0 as $entry ) {			
		$user_nicename=$entry->user_nicename;}
	
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
			
		if($posttype=="post")
		{
			$xyz_smap_include_posts=get_option('xyz_smap_include_posts');
			if($xyz_smap_include_posts==0)
			{
				$_POST=$_POST_CPY;return;
			}
			
			$xyz_smap_include_categories=get_option('xyz_smap_include_categories');
			if($xyz_smap_include_categories!="All")
			{
				$carr1=explode(',', $xyz_smap_include_categories);
					
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

		include_once ABSPATH.'wp-admin/includes/plugin.php';
		$pluginName = 'bitly/bitly.php';
		
		if (is_plugin_active($pluginName)) {
			remove_all_filters('post_link');
		}
		$link = get_permalink($postpp->ID);



		$xyz_smap_apply_filters=get_option('xyz_smap_std_apply_filters');
		$ar2=explode(",",$xyz_smap_apply_filters);
		$con_flag=$exc_flag=$tit_flag=0;
		if(isset($ar2[0]))
			if($ar2[0]==1) $con_flag=1;
		if(isset($ar2[1]))
			if($ar2[1]==2) $exc_flag=1;
		if(isset($ar2[2]))
			if($ar2[2]==3) $tit_flag=1;
		
		
		$content = $postpp->post_content;
		if($con_flag==1)
			$content = apply_filters('the_content', $content);
		$excerpt = $postpp->post_excerpt;
		if($exc_flag==1)
			$excerpt = apply_filters('the_excerpt', $excerpt);
		
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
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
		$caption = html_entity_decode(get_bloginfo('title'), ENT_QUOTES, get_bloginfo('charset'));
		if($tit_flag==1)
			$name = apply_filters('the_title', $name);

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

				$fb=new SMAPFacebook(array(
						'appId'  => $acces_token,
						'secret' => $appsecret,
						'cookie' => true
				));
				$message1=str_replace('{POST_TITLE}', $name, $message);
				$message2=str_replace('{BLOG_TITLE}', $caption,$message1);
				$message3=str_replace('{PERMALINK}', $link, $message2);
				$message4=str_replace('{POST_EXCERPT}', $excerpt, $message3);
				$message5=str_replace('{POST_CONTENT}', $description, $message4);
				$message5=str_replace('{USER_NICENAME}', $user_nicename, $message5);
				
				$message5=str_replace("&nbsp;","",$message5);

               $disp_type="feed";
				if($posting_method==1) //attach
				{
					$attachment = array('message' => $message5,
							'access_token' => $acces_token,
							'link' => $link,
							'name' => $name,
							'caption' => $caption,
							'description' => $descriptionfb_li,
							'actions' => array(array('name' => $name,
							'link' => $link)),
							'picture' => $attachmenturl

					);
				}
				else if($posting_method==2)  //share link
				{
					$attachment = array('message' => $message5,
							'access_token' => $acces_token,
							'link' => $link,
							'name' => $name,
							'caption' => $caption,
							'description' => $descriptionfb_li,
							'picture' => $attachmenturl


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
							$albums = $fb->api("/$page_id/albums", "get", array('access_token'  => $acces_token));
							}
							catch(Exception $e)
							{
								$fb_publish_status[$page_id."/albums"]=$e->getMessage();
							}
							foreach ($albums["data"] as $album) {
								if ($album["type"] == "wall") {
									$timeline_album = $album; break;
								}
							}
							if (isset($timeline_album) && isset($timeline_album["id"])) $page_id = $timeline_album["id"];
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
				try{
				$result = $fb->api('/'.$page_id.'/'.$disp_type.'/', 'post', $attachment);}
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
					$img = wp_remote_get($attachmenturl);
					
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
			
			preg_match_all("/{(.+?)}/i",$messagetopost,$matches);
			$matches1=$matches[1];$substring="";$islink=0;$issubstr=0;
			$len=118;
			if($image_found==1)
				$len=$len-24;

			foreach ($matches1 as $key=>$val)
			{
				$val="{".$val."}";
				if($val=="{POST_TITLE}")
				{$replace=$name;}
				if($val=="{POST_CONTENT}")
				{$replace=$description;}
				if($val=="{PERMALINK}")
				{
					$replace="{PERMALINK}";$islink=1;
				}
				if($val=="{POST_EXCERPT}")
				{$replace=$excerpt;}
				if($val=="{BLOG_TITLE}")
					$replace=$caption;

				if($val=="{USER_NICENAME}")
					$replace=$user_nicename;



				$append=mb_substr($messagetopost, 0,mb_strpos($messagetopost, $val));

				if(mb_strlen($append)<($len-mb_strlen($substring)))
				{
					$substring.=$append;
				}
				else if($issubstr==0)
				{
					$avl=$len-mb_strlen($substring)-4;
					if($avl>0)
						$substring.=mb_substr($append, 0,$avl)."...";
						
					$issubstr=1;

				}



				if($replace=="{PERMALINK}")
				{
					$chkstr=mb_substr($substring,0,-1);
					if($chkstr!=" ")
					{$substring.=" ".$replace;$len=$len+12;}
					else
					{$substring.=$replace;$len=$len+11;}
				}
				else
				{
						
					if(mb_strlen($replace)<($len-mb_strlen($substring)))
					{
						$substring.=$replace;
					}
					else if($issubstr==0)
					{
							
						$avl=$len-mb_strlen($substring)-4;
						if($avl>0)
							$substring.=mb_substr($replace, 0,$avl)."...";
							
						$issubstr=1;

					}


				}
				$messagetopost=mb_substr($messagetopost, mb_strpos($messagetopost, $val)+strlen($val));
					
			}

			if($islink==1)
				$substring=str_replace('{PERMALINK}', $link, $substring);
				
			$twobj = new SMAPTwitterOAuth(array( 'consumer_key' => $tappid, 'consumer_secret' => $tappsecret, 'user_token' => $taccess_token, 'user_secret' => $taccess_token_secret,'curl_ssl_verifypeer'   => false));
				
			if($image_found==1 && $post_twitter_image_permission==1)
			{
				 $resultfrtw = $twobj -> request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $substring), true, true);
				
				if($resultfrtw!=200){
					if($twobj->response['response']!="")
						$tw_publish_status["statuses/update_with_media"]=print_r($twobj->response['response'], true);
					else
						$tw_publish_status["statuses/update_with_media"]=$resultfrtw;
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
			
			$message5=str_replace("&nbsp;","",$message5);
						
				$contentln['comment'] =$message5;
				$contentln['content']['title'] = $name_li;
				$contentln['content']['submitted-url'] = $link;
				if($attachmenturl!="" && $post_ln_image_permission==1)
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