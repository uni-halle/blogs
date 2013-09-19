<?php
// Options panel stylesheet
function woothemes_admin_head() { 
    echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin-style.css" media="screen" />';
}

// Load different stylesheet
function woothemes_wp_head() { 
    //Styles
     $style = $_REQUEST[style];
     if ($style != '') {
          echo '<link href="'. get_bloginfo('template_directory') .'/styles/'. $style . '.css" rel="stylesheet" type="text/css" />'."\n"; 
     } else { 
          $stylesheet = get_option('woo_alt_stylesheet');
          if($stylesheet != '')
               echo '<link href="'. get_bloginfo('template_directory') .'/styles/'. $stylesheet .'" rel="stylesheet" type="text/css" />'."\n";         
          else
               echo '<link href="'. get_bloginfo('template_directory') .'/styles/default.css" rel="stylesheet" type="text/css" />'."\n";         		  
     } 
     
      // Custom.css insert
      echo '<link href="'. get_bloginfo('template_directory') .'/custom.css" rel="stylesheet" type="text/css" />'."\n";   
      
     // Favicon
    if(get_option('woo_custom_favicon') != ''){
        echo '<link rel="shortcut icon" href="'.  get_option('woo_custom_favicon')  .'"/>'."\n";
     }    
     
     // Custom CSS block in Backend
    $custom_css = get_option('woo_custom_css');
    if($custom_css != '')
        {
            $output = '<style type="text/css">'."\n";
            $output .= $custom_css . "\n";
            $output .= '</style>'."\n";
            echo $output;
        }
        
    //Decode
     $decode = $_REQUEST['decode'];
     if ($decode == 'true') 
		woo_option_output(); 

	// Localization
	load_theme_textdomain(woothemes);	
	
	// Date format
	$GLOBALS['woodate'] = get_option('woo_date');	
	if ( $GLOBALS['woodate'] == "" )
		$GLOBALS['woodate'] = "d. M, Y";	

}

// Add Encrypted setting field to footer for debug purposes
function woo_option_output(){

    $data = get_option('woo_settings_encode');
    echo '<meta name="generator" content="' . $data . '" />';

}


// Use legacy comments on versions before WP 2.7
add_filter('comments_template', 'legacy_comments');
function legacy_comments($file) {
    if(!function_exists('wp_list_comments')) : // WP 2.7-only check
        $file = TEMPLATEPATH . '/comments-legacy.php';
    endif;
    return $file;
}



?>