<?php
/*
Plugin Name: List-All
Plugin URI: http://www.wpmudev.org/project/list-all
Description: Creates a list of all blogs on a WPMU site
Author: Andrew Billits
Author URI: http://wpmudev.org
Version: 0.0.5
*/

global $name_or_url;
global $begin_wrap;
global $end_wrap;
function echoArrayBlogList($arrayName) {
    global $wpdb;
    global $name_or_url;
    global $begin_wrap;
    global $end_wrap;
    
    $intArrayCount = 0;
    $bid = '';
    foreach ($arrayName as $arrayElement) {
        if (count($arrayElement) > 1) {
            echoArrayBlogList($arrayElement);
        } else {
            $intArrayCount = $intArrayCount + 1;
            if ($intArrayCount == 1) {
                $bid = $arrayElement;
                // get blog url depending on vhost or not-vhost installtion
                if( defined( "SUBDOMAIN_INSTALL" ) && constant( "SUBDOMAIN_INSTALL" ) == true )
                    $tmp_domain = $wpdb->get_var("SELECT domain FROM " . $wpdb->blogs . " WHERE  blog_id = '" . $bid . "'");
                else
                    $tmp_domain = get_blog_option( $bid, "siteurl");
                if ($name_or_url == "name") {
                $tmp_display = get_blog_option( $bid, "blogname");
                } else {
                $tmp_display = $tmp_domain;
                }
                $tmp_path = $wpdb->get_var("SELECT path FROM " . $wpdb->blogs . " WHERE  blog_id = '" . $bid . "'");
                // get blog url depending on vhost or not-vhost installtion
                if( defined( "SUBDOMAIN_INSTALL" ) && constant( "SUBDOMAIN_INSTALL" ) == true )
                    echo $begin_wrap . "<a href='http://" . $tmp_domain . $tmp_path . "'>" . $tmp_display . "</a>" . $end_wrap;
                else
                    echo $begin_wrap . "<a href='" . $tmp_domain . "'>" . $tmp_display . "</a>" . $end_wrap;
            }
        }
    }
}

function list_all_wpmu_blogs($tmp_limit, $tmp_name_or_url, $tmp_begin_wrap, $tmp_end_wrap, $tmp_order) {
    global $wpdb;
    global $name_or_url;
    global $begin_wrap;
    global $end_wrap;
    if ($tmp_limit == "") {
        //no limit
    } else {
        $limit = "LIMIT " . $tmp_limit;
    }
    if ($tmp_name_or_url == "") {
        $name_or_url = "name";
    } else {
        if ($tmp_name_or_url == "name") {
            $name_or_url = "name";
        } else {
            $name_or_url = "url";
        }
    }
    if (tmp_begin_wrap == "" || tmp_end_wrap == "" ) {
        $begin_wrap = "<p>";
        $end_wrap = "</p>";
    } else {
        $begin_wrap = $tmp_begin_wrap;
        $end_wrap = $tmp_end_wrap;
    }
    if ($tmp_order == "") {
        $order = "ORDER BY  last_updated DESC";
    } else {
        if ($tmp_order == "updated") {
            $order = "ORDER BY  last_updated DESC";
        }
        if ($tmp_order == "first_created") {
            $order = "ORDER BY  blog_id ASC";
        }
        if ($tmp_order == "last_created") {
            $order = "ORDER BY  blog_id DESC";
        }
    }
    $blog_list = $wpdb->get_results( "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted ='0' " . $order . " " . $limit . "", ARRAY_A );
    $check_blogs = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->blogs . "");
    if ($check_blogs == 0 || $check_blogs == 1 ){ // we don't want to display the admin blog so we return this even if there is one blog
        echo "<p>This are currently no active blogs</p>";
    } else {
        echoArrayBlogList($blog_list);
    }
}
?>