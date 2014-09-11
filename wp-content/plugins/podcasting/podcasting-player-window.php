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
            $player = do_shortcode($ss); 
        }
        else
        {
            $player = '<audio style="width:100%; height:100%;" src="' .
            $castfile .
            '" type="audio/mp3">' .
//            '<a href="' .
//            $castfile .
//            '">' .
//            $castfile .
//            '</a>' .
            '</audio>';
            
        }
 
        $content = $post->post_content;
        $content = preg_replace('/\[podcast[^\]]*\]([\s\S]*?)\[\/podcast[^\]]*\]/', '', $content);
        $content = apply_filters('the_content', $content);
        
        $summary = get_post_meta($id, 'itunes_summary', true);
        if($summary == '' )
        {
            $summary = $content;
        }
        
        $image = "";
        if($pod_itunes_image != "")
        {
          $image = '<img src="' . $pod_itunes_image . '">';
        }   
        

        $replace = array( 
            '{title}' => htmlspecialchars ( stripslashes($pod_title) ),
            '{player}' => $player,
            '{image}' => $image,
            '{post_title}' => $post->post_title,
            '{summary}' => $summary
            ); 
        
        
        
             
        echo  strReplaceAssoc( $replace, $template );        
        
        exit;
    }
    
    function strReplaceAssoc(array $replace, $subject) 
    { 
        return str_replace(array_keys($replace), array_values($replace), $subject);    
    } 


?>
