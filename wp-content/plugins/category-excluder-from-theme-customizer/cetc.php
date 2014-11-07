<?php
/*
Plugin Name: Category Excluder from Theme Customizer
Plugin URI: http://ixensa.com/plugins/category-excluder-from-theme-customizer/
Description: Exclude post category using theme customizer section ( theme live preview ).
Author: Abhishek Sinha
Version: 1.1
Author URI: abhisheksinhasblog.wordpress.com



=== RELEASE NOTES ===
2013-12-20 - v1.0 - first version
*/

// define plugin PATH
define('PLUGIN_PATH', dirname(__FILE__));

// Initialize the new theme_customizer class
new theme_customizer();

// Create the theme_customizer class
class theme_customizer
{
	
    // create public constructor and hook the CETC_manager to wordpress customizer register
	public function __construct()
    {
         add_action( 'customize_register', array(&$this, 'CETC_manager' ));
     
	}

 
    /**
     * Customizer CETC manager
     * @param  WP_Customizer_Manager $wp_manager
     * @return void
     */
    public function CETC_manager( $wp_manager )
    {
        
        $this->CETC_add_sections( $wp_manager );
    }

    /**
     * Adds a new section to use custom controls in the WordPress customiser
     *
     * @param  Obj $wp_manager - WP Manager
     *
     * @return Void
     */
    private function CETC_add_sections( $wp_manager )
    {
		// add new section
        $wp_manager->add_section( 'CETC_section', array(
            'title'          => 'Category Excluder',
            'priority'       => 36,
        ) );
		
		// include the html render function 
		require_once( PLUGIN_PATH . "/render-function.php");
		
		// add setting to CETC_section
        $wp_manager->add_setting( 'CETC_setting', array(
            'default'        => '',
        ) );
		
		// add control to CETC_section
        $wp_manager->add_control( new CETC_Control( $wp_manager, 'CETC_setting', array(
            'label'   => 'Select the Categories to be excluded from blog page',
			'transport'     =>  'postMessage',
            'section' => 'CETC_section',
            'settings'   => 'CETC_setting',
            'priority' => 3
        ) ) );

    }

}


// create a function to get the user selected option and modify the default wordpress query to exclude the categories to be show on blog page
function exclude_category($query) {
	$options =  get_theme_mod('CETC_setting');
	if ( $options != 0 ) {
		foreach ($options as $value) {
			$array = $array.' -'.$value; 
		}
 	
	if ( $query->is_home() ) {
		$query->set('cat', $array);
	}
	return $query;
	}
}

// add hook to default wordpress query 
add_filter('pre_get_posts', 'exclude_category');
