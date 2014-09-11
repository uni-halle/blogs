<?php
/**
 * @package WordPress
 * @subpackage MLUchemie Theme
*/


/*-----------------------------------------------------------------------------------*/
/*	Javascsript
/*-----------------------------------------------------------------------------------*/


wp_enqueue_script(
     'my-theme-script'
    ,get_stylesheet_directory_uri().'/linkhelper.js'
    ,array( 'jquery' )
);


//cookie for formular
/*

function iww_cookie($email) {
    setcookie( 'iwwForm', $email, 0, '/', 'studieninfo.mathematik.uni-halle.de'); 
}
add_action( 'iwwc', 'iww_cookie' );
*/
//cookie end

//add_action( 'admin_menu', 'my_remove_menus', 999 );


function my_remove_menus() {

	
    remove_menu_page('index.php'); // Dashboard tab
    remove_menu_page( 'link-manager.php' ); // Links
	remove_menu_page('themes.php');	
	remove_menu_page('edit-comments.php');	
    remove_menu_page('edit.php?post_type=page'); // Pages
}


?>