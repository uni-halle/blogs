<?php

    function display_player_window($params)
    {
    
        $pod_itunes_image       = get_option('pod_itunes_image');
        $pod_title              = get_option('pod_title');
        
        $castfile   = $params['castfile'];
        $id         = $params['id'];
        
        $format = '';
        if(isset($params['format']))
        {
            $format     = $params['format'];
        }
        
        $post = get_post($id);
        
        $template = file_get_contents(dirname(__file__) . "/templates/player.tmpl");
        
        if($format == 1)
        {
            $ss = '[video src="' . $castfile . '"]';
        }
        else
        {
            $ss = '[audio src="' . $castfile . '"]';
        }
        
        $player = do_shortcode($ss);    
        
        $content = $post->post_content;
        $content = preg_replace('/\[podcast[^\]]*\]([\s\S]*?)\[\/podcast[^\]]*\]/', '', $content);
        $content = apply_filters('the_content', $content);
        $replace = array( 
            '{title}' => htmlspecialchars ( stripslashes($pod_title) ),
            '{player}' => $player,
            '{image}' => $pod_itunes_image,
            '{post_title}' => $post->post_title,
            '{summary}' => apply_filters('the_content', $content),
            ); 
        echo  strReplaceAssoc( $replace, $template );        
        
        exit;
    }
    
    function strReplaceAssoc(array $replace, $subject) 
    { 
        return str_replace(array_keys($replace), array_values($replace), $subject);    
    } 


?>
