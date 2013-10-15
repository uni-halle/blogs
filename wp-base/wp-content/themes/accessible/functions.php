<?php
/*
 * Accessible Twenty Ten -  Accessible child for the Wordpress 2010 default theme Twenty Ten by sprungmarker (http://sprungmarker.de)
 * The functions file is used to initialize everything in the child theme. It controls TinyMCE, skip link and some other features.
 * Only for themes who are directly derived from Twenty Ten or for those who are using Twenty Ten.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Accessible
 * @subpackage Functions
 * @version 2.0
 * @author Sylvia Egger <developer@sprungmarker.de>
 * @copyright Copyright (c) 2011, Sylvia Egger
 * @link http://accessible.sprungmarker.de/2011/01/accessible-1-0/
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


 /* I18n for child-theme */
if ( ! function_exists( 'accessible_theme_setup' ) ):
    function accessible_theme_setup() {
        /* Load translation files. */
        load_child_theme_textdomain( 'accessible', get_stylesheet_directory() . '/languages' );
    }
    add_action( 'after_setup_theme', 'accessible_theme_setup' );
endif;


/**
 * TinyMCE customization
 *
 */

 /* TinyMCE: Styles Plugin - show only a lang class - to get the language integration running */
if ( ! function_exists( 'accessible_tiny_mce_before_init' ) ):
    function accessible_tiny_mce_before_init( $init_array ) {
    $init_array['theme_advanced_styles'] = "lang=lang";
    return $init_array;
    }
    add_filter( 'tiny_mce_before_init', 'accessible_tiny_mce_before_init' );
endif;

/* Control buttons in TinyMCE */
if ( ! function_exists( 'accessible_mce_btns1' ) ):
    function accessible_mce_btns1($orig) {
    return array('bold','italic','strikethrough','|', 'bullist','numlist','blockquote','|','link','unlink','wp_more','|','spellchecker','fullscreen','wp_adv','|','attribs','cite','abbr','acronym');
    }
    add_filter( 'mce_buttons_1', 'accessible_mce_btns1', 999 );
endif;

if ( ! function_exists( 'accessible_mce_btns2' ) ):
    function accessible_mce_btns2($orig) {
    return array('formatselect','sub', 'sup', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'undo', 'redo', 'wp_help', 'charmap','code','tablecontrols');
    }
    add_filter( 'mce_buttons_2', 'accessible_mce_btns2', 999 );
endif;

/* Reduce buttons and format options in TinyMCE */
if ( ! function_exists( 'accessible_change_mce_buttons' ) ):
    function accessible_change_mce_buttons( $initArray ) {
        //@see http://wiki.moxiecode.com/index.php/TinyMCE:Control_reference
        $initArray['theme_advanced_blockformats'] = 'p,address,h2,h3,h4,h5,h6';
        $initArray['theme_advanced_disable'] = 'forecolor,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent';
        return $initArray;
    }
    add_filter('tiny_mce_before_init', 'accessible_change_mce_buttons');
endif;

/**
 * Accessible Skip Links
 *
 */

 /* Insert skip-link after content */
if ( ! function_exists( 'accessible_skip_content' ) ):
    function accessible_skip_content() {
            echo '<a href="#wrapper" class="skip-content screen-reader-text">';
            echo _e( 'Skip to top', 'accessible' );
            echo '</a>';
    }
    add_filter("comments_template", "accessible_skip_content");
endif;

 /* Insert skip-link in footer area  */
if ( ! function_exists( 'accessible_skip_top' ) ):
    function accessible_skip_top() {
      echo '<a href="#wrapper" class="skip-bottom screen-reader-text">';
      echo _e( 'Skip to top', 'accessible' );
      echo '</a>';
    }
    
    add_filter('twentyten_credits', 'accessible_skip_top');
endif;

/**
 * Accessible Hidden Headlines
 *
 */

 /* Insert hidden headline for accessibility issues */
if ( ! function_exists( 'accessible_headline_sidebar' ) ):
    function accessible_headline_sidebar() {
      echo '<h2 class="screen-reader-text">';
      echo _e( 'More informations', 'accessible' );
      echo '</h2>';
    }
    
    add_filter("get_sidebar", "accessible_headline_sidebar");
endif;

 /* Insert hidden headline comments for accessibility issues */
if ( ! function_exists( 'accessible_headline_comments' ) ):
    function accessible_headline_comments() {
        if ( comments_open() ){
                echo '<h2 class="screen-reader-text">';
                echo _e( 'Comments', 'twentyten' );
                echo '</h2>';
        }
    }
    
    add_filter('comments_template', "accessible_headline_comments");
endif;

 /* menue: home translated */
if ( ! function_exists( 'accessible_home_label' ) ):
    function accessible_home_label($args) {
        if ( ! empty($args['show_home']) )
            $args['show_home'] = __( 'Home', 'accessible' );
        return $args;
     }
    add_filter('wp_page_menu_args', 'accessible_home_label', 20);
endif;

?>