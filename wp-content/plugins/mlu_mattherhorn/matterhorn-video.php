<?php
/**
 * PLugin Name: Matterhorn Video Plugin
 * Description: Emebed Matterhon videos
 * Author: Robert Jäckel
 * Author URI: mailto:robert.jaeckel@itz.uni-halle.de?subject=matterhorn-video-plugin
 */
defined('ABSPATH') or die("No script kiddies please!");

/*
http://openlecture.uni-halle.de:5000/episode/{id}

jQuery(function($){
    var base="http://engage.matterhorn.uni-halle.de:8080/engage/ui/";
    var watch="watch.html?id=";
    var embed="embed.html?id=";
    $('.matterhorn').each(function(){
        var $this=$(this),id=$this.html(),width=$this.parent().width(),height=Math.floor(width*.75);
        $this.replaceWith($("<span/>").append(
            $('<iframe/>').attr({src:base+embed+id,width:width,height:height,scrolling:"no"}),
            $('<a>Hier ansehen...</a>').attr({href:base+watch+id})
        ));
    });
});


*/
call_user_func(function(){
    //$baseUri=plugins_url("/",__FILE__);
    //wp_enqueue_style("matterhorn-video-css", $baseUri.'video.css');
    add_filter("the_content",function($text){
        $regex="/\[matterhorn(?:([a-z0-9\s]+))?\]([0-9a-z\-]+)\[\/matterhorn\]/i";
        $replace="<iframe class=\"matterhorn-video$1\" src=\"http://engage.matterhorn.uni-halle.de:8080/engage/ui/embed.html?id=$2\" scrolling=\"no\"></iframe>";
        return preg_replace($regex,$replace,$text);
        return $text."<hr>".var_dump(preg_replace($regex,$replace,$text))."<hr>";
    });
});
/*

add_action("wp_head", "addHeaderCode");

add_filter("the_content", "processContent" );


*/
