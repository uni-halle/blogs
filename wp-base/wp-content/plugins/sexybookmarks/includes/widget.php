<?php

/*
 * Creating the widget for the WordPress Dashboard
 */

class ShareaholicWidget extends WP_Widget{
    
    function ShareaholicWidget(){
        //Actula Widget Code goes here
        parent::WP_Widget(false,$name = "Shareaholic");
    }
    
    function top_sharers($domain){
        echo '<script type="text/javascript" src="//shareaholic.com/media/js/topsharers.js?domain='.$domain.'"></script>';
    }
    
    function widget($args,$instance){
        //Output the Widget Contet
        extract($args);
        $this->top_sharers($this->get_domain());
    }
    
    function update($new_instance, $old_instance){
        //process and save the widget options
        return $new_instance;
    }
    
    function form($instance){
        //Output the Options for admin
    }
    
    function get_domain(){
        $site_url = get_option("siteurl");
        preg_match("/^(http?:\/\/)?([^\/]+)/i",$site_url , $matches);
        $host = $matches[2];
        $new_url = ereg_replace('www\.','',$host);
        $domain = parse_url($new_url);
        if(!empty($domain["host"])){
            return $domain["host"];
        }else{
            return $domain["path"];
        }        
        return $domain;
    }
}

function shrsb_register_widget() {
    register_widget('ShareaholicWidget');
}

add_action( 'widgets_init', 'shrsb_register_widget' );
?>