<?php

// Functions related to mobile.
require_once 'mobile.php';
$shrsb_is_mobile = shrsb_is_mobile();
$shrsb_is_bot = shrsb_is_bot();

// Written in the footer if shareaholic-javascript is on
$shrsb_js_params = array();
$shrsb_tb_js_params = array();
$shrsb_rd_js_params = array();
$shrsb_cb_js_params = array();

$shrsb_bgimg_map = array(
  'shr' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-shr.png',
    'padding' => '28px 0 0 10px',
    'class' => 'shr-bookmarks-bg-shr',
  ),
  'caring' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-caring-hearts.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-caring',
  ),
  'care-old' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-caring.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-care-old',
  ),
  'love' => array(
    'url' => SHRSB_PLUGPATH.'images/share-love-hearts.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-love',
  ),
  'wealth' => array(
    'url' => SHRSB_PLUGPATH.'images/share-wealth.png',
    'padding' => '35px 0 0 20px',
    'class' => 'shr-bookmarks-bg-wealth',
  ),
  'enjoy' => array(
    'url' => SHRSB_PLUGPATH.'images/share-enjoy.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-enjoy',
  ),
  'german' => array(
    'url' => SHRSB_PLUGPATH.'images/share-german.png',
    'padding' => '35px 0 0 20px',
    'class' => 'shr-bookmarks-bg-german',
  ),
  'knowledge' => array(
    'url' => SHRSB_PLUGPATH.'images/share-knowledge.png',
    'padding' => '35px 0 0 10px',
    'class' => 'shr-bookmarks-bg-knowledge',
  ),
);

/**
 * Helper method that returns array for the current post of
 * array(
 *   'link' => ..,
 *   'title' => ..,
 *   'mail_subject' => ..
 * )
 */
function shrsb_post_info($post) {
	global $shrsb_plugopts, $shrsb_bgimg_map;

  //Just so you don't forget, $r means "results"
  $r = array();

  // if (position == manual or (home and index)) and no post_title
  // then we are "Outside the loop".
  $ismanual = $shrsb_plugopts['position'] == 'manual';
  $ishome = is_home() &&
      false!==strpos($shrsb_plugopts['pageorpost'],"index");
  $isemptytitle = empty($post->post_title);
  if($ismanual || ($ishome && $isemptytitle) || !in_the_loop()) {
  
    if(!in_the_loop()) {
        $link= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    //Otherwise, it must be inside the loop
    else {
        if(($link = get_permalink($post->ID)) == false){
            $link = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
    }
    $link = trim($link);
    shrsb_log('Manual: Link Generation '.$link);
    $r['link'] = $link;
    $r['title'] = get_bloginfo('name') . wp_title('-', false);
    $r['mail_subject'] = urlencode(get_bloginfo('name') . wp_title('-', false));

	}
  //We are "in the loop"
  else {
        $r['link'] = trim(get_permalink($post->ID));
        shrsb_log("Loop mode Link Generation ".$r['link']);
		$r['title'] = $post->post_title;
		$r['mail_subject'] = urlencode($post->post_title);
	}

	// Determine how to handle post titles for Twitter
	if (strlen($r['title']) >= 80) {
		$r['short_title'] = urlencode(substr($r['title'], 0, 80)."[..]");
	}
	else {
		$r['short_title'] = urlencode($r['title']);
	}
  return $r;
}

/**
 * Returns array of values that should be used in recommendations js
 */
function shrsb_get_rd_config($post_id) {
  
  global $shrsb_recommendations;
  
  $r = shrsb_get_params($post_id);
  
  $params = array(
    'link' => $r['link'],
    'apikey' => $r['apikey'] ? $r['apikey'] : '8afa39428933be41f8afdb8ea21a495c',
		'style' =>	$shrsb_recommendations['style']
  );

  return array_filter($params);
}
/**
 * Returns array of values that should be used in classicbookmarks js
 */
function shrsb_get_cb_config($post_id) {
  
  global $shrsb_cb;
  
  $r = shrsb_get_params($post_id);
  
  $params = array(
    'link' => $r['link'],
    'apikey' => $r['apikey'] ? $r['apikey'] : '8afa39428933be41f8afdb8ea21a495c',
    'size' => $shrsb_cb['size']
  );

  return array_filter($params);
}

/**
 * Returns array of values that should be used in shareaholic-publishers.js
 */
function shrsb_get_publisher_config($post_id) {
  shrsb_log("get_publisher_config started");
  global $default_spritegen;
  $spritegen = $default_spritegen ? 'spritegen_default' : 'spritegen';
  $spritegen_basepath = shrsb_correct_protocol($default_spritegen ? SHRSB_PLUGPATH : SHRSB_UPLOADPATH);
    
  $r = shrsb_get_params($post_id);
  
  //set default values if not set
  if (!isset($r['spriteimg'])){$r['spriteimg'] = '';}
  if (!isset($r['bgimg-padding'])){$r['bgimg-padding'] = '';}
  
  $params = array(
    'link' => $r['link'],
    'short_link' => $r['short_link'],
    'shortener' => $r['shortener'],
    'shortener_key' => $r['shortener_key'],
    'title' => $r['title'],
    'notes' => $r['notes'],
    'service' => $r['service'],
    'apikey' => $r['apikey'] ? $r['apikey'] : '8afa39428933be41f8afdb8ea21a495c',
    // we need this because wordpress won't pass it at all if it's FALSE
    // and the default value for expand is true.  We convert it to boolean in javascript
    'expand' => $r['expand'] ? true : 'false',
    'src' => $spritegen_basepath.$spritegen,
    'localize' => true,
    'share_src' => shrsb_correct_protocol($r['shrbase']),
    'rel' => $r['reloption'],
    'target' => $r['targetopt'] == '_blank' ? '_blank' : '_top',
    'bgimg' => $r['bgimg-url'],
    'bgimg_padding' => $r['bgimg-padding'],
    'center' => $r['autocenter'] == 1,
    'spaced' => $r['autocenter'] == 2,
    'twitter_template' => $r['tweetconfig'],
    'mode' => 'inject',
    'spriteimg' => $r['spriteimg'],
    'designer_toolTips' => $r['designer_toolTips'],
    'tip_bg_color' => $r['tip_bg_color'],  // tooltip background color
    'tip_text_color' => $r['tip_text_color'], // tooltip text color
	  'dontShowShareCount' => $r['showShareCount'] == "0",
	  'shrlink'	=> $r['shrlink'],
  );

  shrsb_log("get_publisher_config completed");
  return array_filter($params);
}


function shrsb_get_shortener_settings(){
    global $shrsb_plugopts;
    $shorty = @$shrsb_plugopts['shorty'];
    $shortyapi = @$shrsb_plugopts['shortyapi'];
    $shortener_key = '';

    if (isset( $shorty ) ){
        switch( $shorty ) {
            case 'bitly':
            case 'jmp':
                $user = $shortyapi[$shorty]['user'];
                $api = $shortyapi[$shorty]['key'];
                $shortener_key  =  $user ? ($user.'%7c'.$api) : '';
                break;
            default:
        }
    }
    return $shortener_key;
}

/**
 * Returns array of all relevant information about the current post for sexy
 */
function shrsb_get_params($post_id) {
    
  if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] != '1') {
      return array();
  }
    
  shrsb_log("get_params start");
  global $shrsb_plugopts, $shrsb_bgimg_map;
  
  
  if($post_id >= 0){
    $post = get_post($post_id);  
  }else{
      //For handling the (manual mode && outside the loop)
      $post = "";
  }
    

  // response parameters
  $post_info = shrsb_post_info($post);
  $r = array_merge($shrsb_plugopts, $post_info);
  
  // Grab the short URL
  $r['short_link'] = shrsb_get_fetch_url();
  $r['shortener'] = $r['shorty'];
  $r['shortener_key'] = shrsb_get_shortener_settings();
    
    if($post_id >= 0){
        $r['post_summary'] = urlencode(strip_tags(
        strip_shortcodes($post->post_excerpt)));

        if ($r['post_summary'] == "") {
            $r['post_summary'] = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300));
        }

        $r['shrsb_content'] = urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
        if ($r['shrsb_content'] == "") {
            $r['shrsb_content'] = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300));
        }
        $r['shrsb_content']	= str_replace('+','%20',$r['shrsb_content']);
        $r['post_summary'] = stripslashes(str_replace('+','%20',$r['post_summary']));
    }
    
  
  $r['site_name'] = get_bloginfo('name');
  $r['mail_subject'] = str_replace("&#8217;","'",str_replace('+','%20',$r['mail_subject']));

  // Grab post tags for Twittley tags. If there aren't any, use default tags
  // set in plugin options page
  $getkeywords = get_the_tags();
  $r['d_tags'] = "";
  $tags = array();
	if (!empty($getkeywords) && !empty($d_tags)) {
    foreach($getkeywords as $tag) { 
      $tags[] = $tag->name; 
    }
    $r['d_tags'] = implode(',', $tags);
	}

 if($post_id >= 0){
	// Compatibility fix for NextGen Gallery Plugin...
	if( (strpos($r['post_summary'], '[') || strpos($r['post_summary'], ']')) ) {
		$r['post_summary'] = "";
	}
	if((strpos($r['shrsb_content'], '[') || strpos($r['shrsb_content'],']'))) {
		$r['shrsb_content'] = "";
	}
 }
  $r['bgimg-url'] = '';
	if(isset($shrsb_plugopts['bgimg-yes'])) {
    $r['bgimg-url'] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['url'];
    $r['bgimg-padding'] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['padding'];
  }
 
	// Select the background image
  $bclasses = array('shr-bookmarks');
	if (isset($shrsb_plugopts['bgimg-yes'])) {
    $bclasses[] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['class'];
	}
  if ($shrsb_plugopts['expand'] == 1) {
    $r['expand'] = true;
    $bclasses[] = 'shr-bookmarks-expand';
  }
	if ($shrsb_plugopts['autocenter'] == 1) {
		$bclasses[] = 'shr-bookmarks-center';
	} 
  elseif ($shrsb_plugopts['autocenter'] == 2) {
		$bclasses[] = 'shr-bookmarks-spaced';
	}
  $r['bookmarks-class'] = implode(' ', $bclasses);
  
  if($post_id >= 0){
        $r['notes'] = $r['post_summary'];
  }else{
        $r['notes'] = "";
  }

  shrsb_log("get_params completed");
	return $r;
}


function shrsb_get_fetch_url() {
    shrsb_log("get_fetch_url start");
	global $post, $shrsb_plugopts, $wp_query; //globals
	
	//get link but first check if inside or outside loop and what page it's on
	$spost = $wp_query->post;

	if($shrsb_plugopts['position'] == 'manual') {
		//Check if outside the loop
		if(!in_the_loop()) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {
		//Check if outside the loop
		if(!in_the_loop()) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ;
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
	}
	$perms = trim($perms);
	shrsb_log("URL ".$perms);
	//if is post, and post is not published then return permalink and go back
	if($post && get_post_status($post->ID) != 'publish') {
		return $perms;
	}
	//if user chose not to use shortener, return nothing
	if($shrsb_plugopts['shorty'] == 'none') {
		// return no short_link
	}
	if ($shrsb_plugopts['shorty'] == 'tflp' && function_exists('permalink_to_twitter_link')) {
		$fetch_url = permalink_to_twitter_link($perms);
	} 
    elseif ($shrsb_plugopts['shorty'] == 'yourls' && function_exists('wp_ozh_yourls_raw_url')) {
		$fetch_url = wp_ozh_yourls_raw_url();
	}
	
	if (!empty($fetch_url)) {
	    return $fetch_url;
    }
    shrsb_log("get_fetch_url start completed");
}


// Create an auto-insertion function
function shrsb_position_menu($post_content) {
	global $post, $shrsb_plugopts, $shrsb_is_mobile, $shrsb_is_bot, $shrsb_js_params, $shrsb_rd_js_params, $shrsb_cb_js_params;
    
    if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] != '1') {
        return $post_content;
    }
    
    shrsb_log("Content Analysis started");
	// If user selected manual positioning, get out.
	if ($shrsb_plugopts['position']=='manual') {
        if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
            
            
              if(in_the_loop()){
                    $config = shrsb_get_publisher_config($post->ID);
                    $shrsb_js_params['shr-publisher-'.$post->ID] = $config;
              }else{
                  shrsb_log("Error: the function should not be called outside the loop");
              }
              
        }
        shrsb_log("Manual:Content Analysis returning ");
		return $post_content;
	}

	// If user selected hide from mobile and is mobile, get out.
	elseif ($shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_mobile || $shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_bot) {
		shrsb_log("Not Manual:Content Analysis returning");
        return $post_content;
	}
    
    $output = "";
    $likeButtonSetTop = "";
    $likeButtonSetBottom = "";
    
	// Decide whether or not to generate the bookmarks.
	if (    (is_single() && false!==strpos($shrsb_plugopts['pageorpost'],"post")) || 
            (is_page() && false!==strpos($shrsb_plugopts['pageorpost'],"page")) || 
            (is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) || 
            (is_category() && false!==strpos($shrsb_plugopts['pageorpost'],"category") ) || 
            (is_feed() && !empty($shrsb_plugopts['feed']))) { 
    
    // socials should be generated and added
        if( ($hide_sexy = get_post_meta($post->ID, 'Hide SexyBookmarks', true))  != 1 ){
            // Checking for new Mode
            if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
                $output = '<div class="shr-publisher-'.$post->ID.'"></div>';
                $likeButtonSetTop = get_shr_like_buttonset('Top', 1);
                $likeButtonSetBottom = get_shr_like_buttonset('Bottom', 1);
                $config = shrsb_get_publisher_config($post->ID);
                
                $shrsb_js_params['shr-publisher-'.$post->ID] = $config;
            }
            else {
                $output=get_sexy();
            }
            shrsb_log("Sexybookmark HTML here <!-- ".$output.$likeButtonSetTop.$likeButtonSetBottom."--> Inspect me check the html");
        }
    }
	// Place of bookmarks and return w/ post content.
  $r = $post_content;
	if (!empty($output)) {
        
        switch($shrsb_plugopts['position']) {
          case 'above':
            $r = $output.$post_content;
            break;
          case 'both':
            $r = $output.$post_content.$output;
            break;
          case 'below':
            $r = $post_content.$output;
            break;
          default:
            error_log(__('An unknown error occurred in SexyBookmarks','shrsb'));
        }

        $r = $likeButtonSetTop.$r.$likeButtonSetBottom;
  }
  
  shrsb_log("Content Analysis Completed");
  return $r;
} // End shrsb_position_menu...


function get_shr_like_buttonset($pos = 'Bottom', $return_type = NULL, $settings = NULL) { // $pos = 'Bottom'/'Top' Case sensitive
        global $shrsb_plugopts, $post;
        
        if(!$settings) $settings = $shrsb_plugopts;
        
        $usage = "Manual";
        if($return_type) $usage = "Automatic";
        
        if(in_the_loop()){
            $href = get_permalink($post->ID);
            $title = urlencode($post->post_title);
        }else{
            $href = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $title = urlencode(wp_title("",false));
        }
        
        if(is_single()){
            $href = get_permalink($post->ID);
            $title = urlencode($post->post_title);
        }        

        if(empty($title)) {
            $title = get_bloginfo('name') . wp_title('-', false);
        }
        
        $output = "";
        $float = "none";

        if($settings['likeButtonSetAlignment'.$pos] == '1') {
            $float = "right";
        }

        if($settings['likeButtonSet'.$pos] &&
                ($settings['fbLikeButton'.$pos] == '1' || $settings['fbSendButton'.$pos] == '1' || $settings['googlePlusOneButton'.$pos] == '1' || $settings['tweetButton'.$pos] == '1')) {

            $spacer = '<div style="clear: both; min-height: 1px; height: 3px; width: 100%;"></div>';
            $like_layout = $settings['likeButtonSetSize'.$pos];
            $height = "";
            switch($like_layout) {
                case '2':
                    $height = "height:60px";
                    break;
                default:
                    $height = "height:30px";
                    break;
            }
            $output .= "<div class='shareaholic-like-buttonset' style='float:$float;$height;'>";
            $plusOneHTML = "";
            $fbLikeHTML = "";
            $fbSendHTML = "";
            $tweetButtonHTML = "";

            if($settings['googlePlusOneButton'.$pos] == '1') {
                $plusoneSize = $like_layout;
                switch($plusoneSize) {
                    case '1':
                        $plusoneSize = "medium";
                        break;
                    case '2':
                        $plusoneSize = "tall";
                        break;
                    default:
                        $plusoneSize = "standard";
                        break;
                }
                $plusoneCount = $settings['likeButtonSetCount'.$pos];
                $plusOneHTML = "<a class='shareaholic-googleplusone' data-shr_size='$plusoneSize' data-shr_count='$plusoneCount' data-shr_href='$href' data-shr_title='$title'></a>";
            }
            
            if($settings['tweetButton'.$pos] == '1'){
                $tweetButtonSize = $like_layout;
                $tweetButtonCount = $settings['likeButtonSetCount'.$pos];
                
                switch($tweetButtonSize) {
                    case '1':
                        $tweetButtonSize = "horizontal";
                        if(!$tweetButtonCount && $tweetButtonCount != "false") $tweetButtonSize = "horizontal";
                        break;
                    case '2':
                        $tweetButtonSize = "vertical";
                        break;
                    default:
                        $tweetButtonSize = "none";
                        if(!$tweetButtonCount && $tweetButtonCount != "false") $tweetButtonSize = "horizontal";
                        break;
                }
                
                if ($tweetButtonCount === "false") {
                  $tweetButtonSize = "none";
                }
                
                $tweetButtonHTML = "<a class='shareaholic-tweetbutton' data-shr_count='$tweetButtonSize' data-shr_href='$href' data-shr_title='$title'></a>";
            }
            
            if($settings['fbLikeButton'.$pos] == '1') {
                //$like_layout = $settings['likeButtonSetSize'.$pos];
                switch($like_layout) {
                    case '1':
                        $like_layout = "button_count";
                        break;
                    case '2':
                        $like_layout = "box_count";
                        break;
                    default:
                        $like_layout = "standard";
                        break;
                }
                $fbLikeHTML = "<a class='shareaholic-fblike' data-shr_layout='$like_layout' data-shr_showfaces='false' data-shr_href='$href' data-shr_title='$title'></a>";
            }

            if($settings['fbSendButton'.$pos] == '1') {
                $fbSendHTML = "<a class='shareaholic-fbsend' data-shr_href='$href'></a>";
            }
            
            foreach($settings['likeButtonOrder'.$pos] as $likeOption) {
                switch($likeOption) {
                    case "shr-fb-like":
                        $output .= $fbLikeHTML;
                        break;
                    case "shr-plus-one":
                        $output .= $plusOneHTML;
                        break;
                    case "shr-fb-send":
                        $output .= $fbSendHTML;
                        break;
                    case "shr-tw-button":
                        $output .= $tweetButtonHTML;
                        break;
                }
            }
            $output .= '</div>';
            $output = $spacer.$output.$spacer;
        }
        shrsb_log("<!-- $output -->");
        $output = "<!-- Start Shareaholic LikeButtonSet$pos $usage -->".$output."<!-- End Shareaholic LikeButtonSet$pos $usage -->";
        
        if ($return_type == 1){
            return $output;
        }else{
            echo $output;
        }
}

function selfserv_topbar(){
		shrsb_get_topbar("Manual");
}
/*
 * Sample Html
 *      <div class="shr-toolbox" shr_form_factor="shareaholic-top-bar">
 *           <div class="shareaholic-like-buttonset" >
 *              <a class="shareaholic-fblike" data-shr_layout="button_count" data-shr_showfaces="false" data-shr_href="http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fp%3D1" data-shr_title="Hello+world%21"></a>
 *              <a class="shareaholic-fbsend" data-shr_href="http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fp%3D1"></a>
 *              <a class="shareaholic-googleplusone" data-shr_size="medium" data-shr_count="true" data-shr_href="http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fp%3D1" data-shr_title="Hello+world%21"></a>
 *              <a class="shareaholic-tweetbutton" data-shr_count="horizontal" data-shr_href="http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fp%3D1" data-shr_title="Hello+world%21"></a>
 *          </div>
 *      </div>
 */
function shrsb_get_topbar($usage = NULL){
    
    if(empty($usage)) $usage = "Automatic";
    
    shrsb_log("get_topbar started");
    global $shrsb_plugopts,$shrsb_tb_plugopts;
    
    $output = "";
    $html = "";
    //Decide whether to display 
	
		if ($shrsb_plugopts['shareaholic-javascript'] == '1' && isset($shrsb_tb_plugopts['topbar']) && ($shrsb_tb_plugopts['topbar'] == '1') &&(is_single() && false!==strpos($shrsb_tb_plugopts['pageorpost'],"post")) || 
		(is_page() && false!==strpos($shrsb_tb_plugopts['pageorpost'],"page")) || 
		(is_home() && false!==strpos($shrsb_tb_plugopts['pageorpost'],"index")) || 
		(is_category() && false!==strpos($shrsb_tb_plugopts['pageorpost'],"category") )) { 
        $likeButtonSet = get_shr_like_buttonset('Top', 1, $shrsb_tb_plugopts);
        $html = <<<EOH
        <div class="shr-toolbox" shr_form_factor="shareaholic-top-bar">
            $likeButtonSet
        </div>
EOH;
		
	}
    shrsb_log("Topbar HTML here <!-- $html --> Inspect me check the html");
    $output = "<!-- Start Shareaholic TopSharingBar $usage -->$html<!-- End Shareaholic TopSharingBar $usage -->";
    shrsb_log("get_topbar completed");
    echo $output;
		
}

function shrsb_get_recommendations($post_content){
    
    if(empty($usage)) $usage = "Automatic";
    
    shrsb_log("get_recommendations started");
    global $shrsb_plugopts,$shrsb_recommendations,$post, $shrsb_rd_js_params;
    
    $output = "";
    $html = "";
    //Decide whether to display 
	
		if ($shrsb_plugopts['shareaholic-javascript'] == '1' && isset($shrsb_recommendations['recommendations']) && ($shrsb_recommendations['recommendations'] == '1') &&(is_single() && false!==strpos($shrsb_recommendations['pageorpost'],"post")) || 
		(is_page() && false!==strpos($shrsb_recommendations['pageorpost'],"page")) || 
		(is_home() && false!==strpos($shrsb_recommendations['pageorpost'],"index")) || 
		(is_category() && false!==strpos($shrsb_recommendations['pageorpost'],"category") )) {
      
      if(in_the_loop()){
          $shrsb_rd_js_params['shr_rd-'.$post->ID] = shrsb_get_rd_config($post->ID);
          $shrsb_cb_js_params['shr_cb-'.$post->ID] = shrsb_get_cb_config($post->ID);
          $html .= '<div class="shr_rd-'.$post->ID.'"></div>';
          shrsb_log("Loop:get_sexy new mode found,  returning ");
      }else{
          $shrsb_rd_js_params['shr_rd-'.$post->ID] = shrsb_get_rd_config($post->ID);
          $html .= '<div class="shr_rd"></div>';
          shrsb_log("Not Loop:get_sexy new mode found, returning ");
      }
      
    }
    shrsb_log("Recommendations HTML here <!-- $html --> Inspect me check the html");
    $output = "<!-- Start Shareaholic Recommendations $usage -->$html<!-- End Shareaholic Recommendations $usage -->";

    return $post_content.$output;
}
function shrsb_get_cb($post_content){
    
    if(empty($usage)) $usage = "Automatic";
    
    shrsb_log("get_cb started");
    global $shrsb_plugopts,$shrsb_cb,$post, $shrsb_cb_js_params;
    
    $output = "";
    $html = "";
    //Decide whether to display 
	
		if ($shrsb_plugopts['shareaholic-javascript'] == '1' && isset($shrsb_cb['cb']) && ($shrsb_cb['cb'] == '1') &&(is_single() && false!==strpos($shrsb_cb['pageorpost'],"post")) || 
		(is_page() && false!==strpos($shrsb_cb['pageorpost'],"page")) || 
		(is_home() && false!==strpos($shrsb_cb['pageorpost'],"index")) || 
		(is_category() && false!==strpos($shrsb_cb['pageorpost'],"category") )) {

      $html .= "<div style='clear:both'></div>" ;
      if(in_the_loop()){
          $shrsb_cb_js_params['shr_cb-'.$post->ID] = shrsb_get_cb_config($post->ID);
          $html .= '<div class="shr_cb-'.$post->ID.'"></div>';
          shrsb_log("Loop:get_cb new mode found,  returning ");
      }else{
          $shrsb_cb_js_params['shr_cb-'.$post->ID] = shrsb_get_cb_config($post->ID);
          $html .= '<div class="shr_cb"></div>';
          shrsb_log("Not Loop:get_cb new mode found, returning ");
      }
      $html .= "<div style='clear:both'></div>" ;

      
    }
    shrsb_log("Classic Bookmarks HTML here <!-- $html --> Inspect me check the html");
    $output = "<!-- Start Shareaholic ClassicBookmarks $usage -->$html<!-- End Shareaholic ClassicBookmarks $usage -->";

    return $post_content.$output;
}


function get_sexy() {
    shrsb_log("get_sexy started");
	global $shrsb_plugopts, $wp_query, $post;
    
    $output = "";
    
    // If sexybookmark is disabled then return empty html
    if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] != '1') {
        return $output;
    }
    
	$spost = $wp_query->post;

    
    if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
        
            if(in_the_loop()){
                $output .= '<div class="shr-publisher-'.$post->ID.'"></div>';
                shrsb_log("Loop:get_sexy new mode found,  returning ");
                return $output;
            }else{
                $output .= '<div class="shr_class shareaholic-show-on-load"></div>';
                shrsb_log("Not Loop:get_sexy new mode found, returning ");
                return $output;
            }
    }
   
    //For Old Mode Only
	if($shrsb_plugopts['position'] == 'manual') {

		//Check if outside the loop
		if(!in_the_loop()) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ;
            //$perms = "";
            shrsb_log("Manual:Not in Loop: ".$perms);
			$title = urlencode(get_bloginfo('name') . wp_title('-', false));
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
            shrsb_log("Manual:In Loop: ".$perms);
			$title = urlencode($post->post_title);
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
    
	}//manual mode

	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {

		//Check if outside the loop
		if(!in_the_loop()) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            //$perms= "";
            shrsb_log("NotManualqqqq:Not in Loop: ".$perms);
			$title = get_bloginfo('name') . wp_title('-', false);
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
            shrsb_log("NotManual:In Loop: ".$perms);
			$title = $post->post_title;
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
        shrsb_log("Dont know in loop or not: ".$perms);
		$title = $post->post_title;
		$feedperms = strtolower($perms);
		$mail_subject = urlencode($post->post_title);
	}

  // Grab the short URL
  $fetch_url = shrsb_get_fetch_url();


	//Determine how to handle post titles for Twitter
	if (strlen($title) >= 80) {
		$short_title = urlencode(substr($title, 0, 80)."[..]");
	}
	else {
		$short_title = urlencode($title);
	}

	$title=urlencode($title);

	$shrsb_content	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));

	if ($shrsb_content == "") {	$shrsb_content = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300)); }

	$shrsb_content	= str_replace('+','%20',$shrsb_content);
	$post_summary = stripslashes($shrsb_content);
	$site_name = get_bloginfo('name');
	$mail_subject = str_replace('+','%20',$mail_subject);
	$mail_subject = str_replace("&#8217;","'",$mail_subject);



	// Grab post tags for Twittley tags. If there aren't any, use default tags set in plugin options page
	$getkeywords = get_the_tags(); 
  if($getkeywords) { 
    foreach($getkeywords as $tag) { 
      $keywords[]=$tag->name; 
    }
    $keywords = implode(',', $keywords);
	  if(!empty($getkeywords) && !empty($d_tags)) {
		  $d_tags=substr($d_tags, 0, count($d_tags)-2);
    }
	}


	// Check permalink setup for proper feed link
	if (false !== strpos($feedperms,'?') || false !== strpos($feedperms,'.php', max(0,strlen($feedperms) - 4))) {
		$feedstructure = '&feed=comments-rss2';
	} 
  else {
		if ('/' == $feedperms[strlen($feedperms) - 1]) {
			$feedstructure = 'feed';
		}
		else {
			$feedstructure = '/feed';
		}
	}


	// Compatibility fix for NextGen Gallery Plugin...
	if( (strpos($post_summary, '[') || strpos($post_summary, ']')) ) {
		$post_summary = "";
	}
	if( (strpos($shrsb_content, '[') || strpos($shrsb_content,']')) ) {
		$shrsb_content = "";
	}


	// Select the background image
	if(!isset($shrsb_plugopts['bgimg-yes'])) {
		$bgchosen = '';
	}
  else {
    $bgchosen = ' shr-bookmarks-bg-';

    switch($shrsb_plugopts['bgimg']) {
      case 'care-old':
        $bgchosen .= 'caring-old';
        break;
      default:
        $bgchosen .= $shrsb_plugopts['bgimg'];
    }
  }


	$expand=$shrsb_plugopts['expand']?' shr-bookmarks-expand':'';
  switch($shrsb_plugopts['autocenter']) {
    case 1:
      $autocenter = ' shr-bookmarks-center';
      break;
    case 2:
      $autocenter = ' shr-bookmarks-spaced';
      break;
    default:
      $autocenter = '';
  }


	//Write the sexybookmarks menu
	$socials = "\n\n";
	$socials .= '<div class="shr-bookmarks'.$expand.$autocenter.$bgchosen.'">'."\n".'<ul class="socials">'."\n";
	foreach ($shrsb_plugopts['bookmark'] as $name) {
		switch ($name) {
			case 'shr-twitter':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-identica':
				$socials.=bookmark_list_item($name, array(
					'short_title'=>$short_title,
					'fetch_url'=>$fetch_url,
				));
				break;
			case 'shr-mail':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-tomuse':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-diigo':
				$socials.=bookmark_list_item($name, array(
					'sexy_teaser'=>$shrsb_content,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-linkedin':
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'site_name'=>$site_name,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-tumblr':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urlencode($perms),
					'title'=>$title,
				));
				break;
			default:
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
		}
	}
	$socials.='</ul>';
	if ($shrsb_plugopts['shrlink'] == 1) {
		$socials.= '<div style="clear: both;"></div>';
		$socials.= '<div class="shr-getshr" style="visibility:hidden;font-size:10px !important"><a target="_blank" href="https://shareaholic.com/?src=pub">Get Shareaholic</a></div>';
	}
	$socials.= '<div style="clear: both;"></div></div>';
	$socials.="\n\n";
    shrsb_log("get_sexy completed");
	return $socials;
}

// This function is what allows people to insert the menu wherever they please rather than above/below a post... (depreciated)
function selfserv_sexy() {
	global $post;
	if(($hide_sexy = get_post_meta($post->ID, 'Hide SexyBookmarks', true)) != 1 ){
        echo "<!-- Start Shareaholic Sexybookmarks Manual -->";
        echo get_sexy();
        echo "<!-- End Shareaholic Sexybookmarks Manual -->";
    }
		
        
}

//Same as above function, just a diff name
function selfserv_shareaholic() {
	global $post;
    
    if(in_the_loop()){
        if(($hide_sexy = get_post_meta($post->ID, 'Hide SexyBookmarks', true)) != 1 )
		echo get_sexy();
    }else{
        echo get_sexy();
    }
		
}

// Write the <head> code only on pages that the menu is set to display
function shrsb_publicStyles() {
	global $shrsb_plugopts, $post, $shrsb_custom_sprite;

	// If custom field is set, do not display sexybookmarks
	if ($post && ($hide_sexy = get_post_meta($post->ID, 'Hide SexyBookmarks', true)) == 1) {
		echo "\n\n".'<!-- '.__('SexyBookmarks has been disabled on this page', 'shrsb').' -->'."\n\n";
	} 
  elseif ($shrsb_plugopts['shareaholic-javascript'] != '1') {
		//custom mods rule over custom css
    if ($shrsb_plugopts['custom-mods'] != 'yes') {
      if($shrsb_custom_sprite != '') {
        $surl = $shrsb_custom_sprite;
      } 
      else {
        $surl = SHRSB_PLUGPATH.'css/style.css';
      }
    } 
    elseif ($shrsb_plugopts['custom-mods'] == 'yes') {
      $surl = WP_CONTENT_URL.'/sexy-mods/css/style.css';
    }
		wp_enqueue_style('sexy-bookmarks', $surl, false, SHRSB_vNum, 'all');
  }
}
function shrsb_publicScripts() {
	global $shrsb_plugopts, $post, $default_spritegen, $shrsb_debug,$shrsb_tb_plugopts, $shrsb_analytics, $shrsb_recommendations, $shrsb_cb;

    $spritegen = $default_spritegen ? 'spritegen_default' : 'spritegen';
    $spritegen_basepath = $default_spritegen ? SHRSB_PLUGPATH : SHRSB_UPLOADPATH;
    
     // Always Enqueue global settings as it can be used by all Apps        
    $shr_global_params["src"] = shrsb_correct_protocol($spritegen_basepath.$spritegen);
    $shr_global_params["perfoption"] = $shrsb_plugopts['perfoption'];
    $shr_global_params["twitter_template"] = $shrsb_plugopts['tweetconfig'];
    $shr_global_params["locale"] = $shrsb_plugopts['locale'];
    $shr_global_params["shortener"] = $shrsb_plugopts['shorty'];
    $shr_global_params["shortener_key"] = shrsb_get_shortener_settings();
    $shr_global_params["pubGaSocial"] = $shrsb_analytics['pubGaSocial'];
    $shr_global_params["pubGaKey"] = $shrsb_analytics['pubGaKey'];

    $js = 'var SHRSB_Globals = '.json_encode($shr_global_params);
    echo '<script type="text/javascript">/* <![CDATA[ */ ' . $js . '; /* ]]> */</script>';
    
    // Beta script
    if ($shrsb_plugopts['shareaholic-javascript'] == '1' && !is_admin()){// && !get_post_meta($post->ID, 'Hide SexyBookmarks')) {
        $infooter = ($shrsb_plugopts['scriptInFooter'] == '1')?true:false;
        
        // Enqueue SexyBookmarks JavaScript only if enabled
        if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] == '1' && shrsb_check_activation() == true){
            wp_enqueue_script('shareaholic-sb-js', (empty($shrsb_debug['sb_script'])) ? shrsb_correct_protocol('http://dtym7iokkjlif.cloudfront.net/media/js/jquery.shareaholic-publishers-sb.min.js') : $shrsb_debug['sb_script'], null, SHRSB_vNum, $infooter);
            $localize_to = 'shareaholic-sb-js';
        }
        
        // Enqueue Tob Bar JavaScript only if enabled
        if(isset($shrsb_tb_plugopts) && isset($shrsb_tb_plugopts['topbar']) && $shrsb_tb_plugopts['topbar'] == '1' && shrsb_check_activation() == true){
            wp_enqueue_script('shareaholic-topbar-js',(empty($shrsb_debug['tb_script'])) ? shrsb_correct_protocol('http://dtym7iokkjlif.cloudfront.net/media/js/jquery.shareaholic-share-buttons.min.js'): $shrsb_debug['tb_script'], null, SHRSB_vNum, $infooter);    
            $localize_to = 'shareaholic-topbar-js';
        }
        
        // Enqueue Recommendations JavaScript only if enabled
        if(isset($shrsb_recommendations) && isset($shrsb_recommendations['recommendations']) && $shrsb_recommendations['recommendations'] == '1' && shrsb_check_activation() == true){
            wp_enqueue_script('shareaholic-recommendations-js',(empty($shrsb_debug['rd_script'])) ? shrsb_correct_protocol("http://dtym7iokkjlif.cloudfront.net/media/js/jquery.shareaholic-publishers-rd.min.js"): $shrsb_debug['rd_script'], null, SHRSB_vNum, $infooter);    
            $localize_to = 'shareaholic-recommendations-js';
        }
        
        // Enqueue Classic Bookmarks JavaScript only if enabled
        if(isset($shrsb_cb) && isset($shrsb_cb['cb']) && $shrsb_cb['cb'] == '1' && shrsb_check_activation() == true){
            wp_enqueue_script('shareaholic-cb-js',(empty($shrsb_debug['cb_script'])) ? shrsb_correct_protocol("http://dtym7iokkjlif.cloudfront.net/media/js/jquery.shareaholic-publishers-cb.min.js"): $shrsb_debug['cb_script'], null, SHRSB_vNum, $infooter);
            $localize_to = 'shareaholic-cb-js';
        }       
    } else {
    // If any javascript dependent options are selected, load the scripts
    if (($shrsb_plugopts['expand'] || $shrsb_plugopts['autocenter'] || $shrsb_plugopts['targetopt']=='_blank') && $post && (($hide_sexy = get_post_meta($post->ID, 'Hide SexyBookmarks', true)) != 1 )) {
      // If custom mods is selected, pull files from new location
      if($shrsb_plugopts['custom-mods'] == 'yes') {
        $surl = WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js';
     } else {
        $surl = SHRSB_PLUGPATH.'js/sexy-bookmarks-public.min.js';
     }
      // If jQuery compatibility fix is not selected, go ahead and load jQuery
      $jquery = ($shrsb_plugopts['doNotIncludeJQuery'] != '1') ? array('jquery') : array();
      $infooter = ($shrsb_plugopts['scriptInFooter'] == '1')?true:false;
      wp_enqueue_script('sexy-bookmarks-public-js', $surl, $jquery, SHRSB_vNum, $infooter);
    }
  }
  
  // Perf tracking for old mode only.For New mode tracking is moved to javascript
  if ( ($shrsb_plugopts['perfoption'] == '1' || $shrsb_plugopts['perfoption'] == '') && (!is_admin() && $shrsb_plugopts['shareaholic-javascript'] !== '1' )) {
      wp_enqueue_script('shareaholic-perf', SHRSB_PLUGPATH.'js/shareaholic-perf.min.js', null, SHRSB_vNum, false);
      wp_enqueue_script("shr_dough_recipe", shrsb_correct_protocol("http://dtym7iokkjlif.cloudfront.net/dough/1.0/shareaholic-analytics.js"), null, null);
  }
}

/*
 * @desc Populate javascript settings in the footer for Sexybookmarks
 */
function shrsb_write_js_params() {
  global $shrsb_plugopts, $shrsb_js_params;
  if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] == '1' && $shrsb_plugopts['shareaholic-javascript'] == '1') {
        //For manual mode, when no config is defined
        if($shrsb_plugopts['position'] == 'manual' && !in_the_loop()){
        $shrsb_js_params['shr_class'] = shrsb_get_publisher_config(-1);
        }

        echo '<script type="text/javascript">var SHRSB_Settings = ';
        echo json_encode($shrsb_js_params);
        echo ';</script>';
  }
}

/*
 * @desc Populate javascript settings in the footer for topbar
 */
function shrsb_tb_write_js_params() {
    global $shrsb_plugopts, $shrsb_tb_js_params,$shrsb_tb_plugopts;
    if ($shrsb_plugopts['shareaholic-javascript'] == '1' && $shrsb_tb_plugopts['topbar'] == '1') {
        
        $js = "";    
        //if ($shrsb_tb_plugopts['useSbSettings'] != '1'){
            $shrsb_tb_js_params["topBarBgColor"] = $shrsb_tb_plugopts["tb_bg_color"];
            $shrsb_tb_js_params["topBarBorderColor"] = $shrsb_tb_plugopts["tb_border_color"];
            $shrsb_tb_js_params["showAddv"] = $shrsb_tb_plugopts["addv"];
            $shrsb_tb_js_params["apiKey"] = "e3c665c2eb6785741cea4515633f1d86b";
            if ( ( is_home() || is_front_page() ) && have_posts() )
              $shrsb_tb_js_params["isIndexPage"] = "indexPage"; 
            $js = 'var SHRTB_Settings = '.json_encode($shrsb_tb_js_params);
        //}
  
        echo '<script type="text/javascript">';
        echo $js;
        echo ';</script>';
  }
}

/*
 * @desc Populate javascript settings in the footer for classicbookmarks
 */
function shrsb_cb_write_js_params() {
    global $shrsb_plugopts, $shrsb_cb_js_params,$shrsb_cb;
    
    
    if ($shrsb_plugopts['shareaholic-javascript'] == '1' && $shrsb_cb['cb'] == '1') {

        $js = 'var SHRCB_Settings = '.json_encode($shrsb_cb_js_params);
        
        echo '<script type="text/javascript">';
        echo $js;
        echo ';</script>';
  }
}

/*
 * @desc Populate javascript settings in the footer for recommendations
 */
function shrsb_rd_write_js_params() {
    global $shrsb_plugopts, $shrsb_rd_js_params,$shrsb_recommendations;
    
    
    if ($shrsb_plugopts['shareaholic-javascript'] == '1' && $shrsb_recommendations['recommendations'] == '1') {

        $js = 'var SHRRD_Settings = '.json_encode($shrsb_rd_js_params);

        echo '<script type="text/javascript">';
        echo $js;
        echo ';</script>';
  }
}

// Add the scripts and Global setting on the page
add_action('wp_print_scripts', 'shrsb_publicScripts');

// Add the hook only when sexybokmark is enabled
if(isset($shrsb_plugopts['sexybookmark']) && $shrsb_plugopts['sexybookmark'] == '1') {
    add_filter('the_content', 'shrsb_position_menu');
    add_action('wp_print_styles', 'shrsb_publicStyles');
    add_action('wp_footer', 'shrsb_write_js_params');
}

// Add the hook only when topbar is enabled
if(isset($shrsb_tb_plugopts['topbar']) && ($shrsb_tb_plugopts['topbar'] == '1')){
    add_action('wp_footer', 'shrsb_get_topbar');
    add_action('wp_footer', 'shrsb_tb_write_js_params');
}

// Add the hook only when recommendations is enabled
if(isset($shrsb_recommendations['recommendations']) && ($shrsb_recommendations['recommendations'] == '1')){
    add_filter('the_content', 'shrsb_get_recommendations');
    add_action('wp_footer', 'shrsb_rd_write_js_params');
}

if(isset($shrsb_cb['cb']) && ($shrsb_cb['cb'] == '1')){
    add_filter('the_content', 'shrsb_get_cb');
    add_action('wp_footer', 'shrsb_cb_write_js_params');
}