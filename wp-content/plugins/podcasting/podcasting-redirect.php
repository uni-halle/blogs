<?php

/** ====================================================================================================================================================
	* Redirect to the true page
	* 
	* @return void
	*/
	
	function redirect_404() {
		global $post;
        
        $pod_player_audiovars   = get_option('pod_player_audiovars');
        $pod_itunes_image       = get_option('pod_itunes_image');
        
        
		if(is_404()) 
        {
            $requestUrl = $_SERVER['REQUEST_URI'];
             // strip GET variables from URL
            if(($pos = strpos($requestUrl, '?')) !== false) {
                $requestUrl =  substr($requestUrl, 0, $pos);
            }
            $parsed_url = parse_url( $requestUrl );
			$params = explode("/", $parsed_url['path'] ) ; 
            $key = $params[count($params)-1];
            

            if($key == "castplayer")
            {
     
                display_player_window($_GET);
                
                echo '<img src="' . $pod_itunes_image . '" alt="some_text">';
     
                $castfile = $_GET['castfile'];
     

            }
            
			if (preg_match("/^([a-zA-Z0-9_.-])*$/",$key,$matches)==1) 
            {
// This seems to harsh
//                wp_redirect( home_url() ); exit;
			} 
          
		}
	}
    
    add_action('template_redirect','redirect_404', 1);
    
    
?>
