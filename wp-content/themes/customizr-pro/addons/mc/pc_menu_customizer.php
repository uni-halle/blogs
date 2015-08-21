<?php
/**
 * Plugin Name: Menu Customizer
 * Plugin URI: http://www.themesandco.com/extension/menu-customizer/
 * Description: Add beautiful side menu to the Customizr theme. 
 * Version: 1.0.0
 * Author: PressCustomizr
 * Author URI: http://www.themesandco.com
 * License: GPLv2 or later
 */


/**
* Fires the plugin
* @package      MC
* @author Nicolas GUILLAUME - Rocco ALIBERTI
* @since 1.0
*/
if ( ! class_exists( 'PC_mc' ) ) :
class PC_mc {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    public $version;
    public $plug_name;
    public $plug_file;
    public $plug_version;
    public $plug_prefix;
    public $plug_lang;

    public static $theme;
    public static $theme_name;
    public $addon_opt_prefix;
    public $is_customizing;
    private $mc_fire_plugin_active_notice;

    function __construct() {

        self::$instance =& $this;

        /* LICENSE AND UPDATES */
        // the name of your product. This should match the download name in EDD exactly
        $this -> plug_name    = 'Menu Customizer';
        $this -> plug_file    = __FILE__; //main plugin root file.
        $this -> plug_prefix  = 'mc';
        $this -> plug_version = '1.0.0';
        $this -> plug_lang    = 'pc_mc';
        
        //checks if is customizing : two context, admin and front (preview frame)
        $this -> is_customizing = $this -> pc_is_customizing();
        
        self::$theme          = $this -> pc_get_theme();
        self::$theme_name     = $this -> pc_get_theme_name();
      
        // check if plugin active
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $mc_plugin_active = is_plugin_active('menu-customizer/pc_menu_customizer.php');
        //check if theme is customizr pro , plugin included
        /* REMOVE THIS IF NOT INCLUDED IN PRO YET */
        if ( 'customizr-pro' == self::$theme_name && $mc_plugin_active ) {
            $this -> mc_fire_plugin_active_notice = true;
            add_action( 'admin_notices', array( $this , 'pc_mc_admin_notice' ) );
            return;
        }   

        //check if theme is customizr* and plugin mode (did_action not triggered yet)
        //stop execution if not customizr*
        if ( false === strpos( self::$theme_name, 'customizr' ) && ! did_action('plugins_loaded') ) {
          add_action( 'admin_notices', array( $this , 'pc_mc_admin_notice' ) );
          return;
        }   


        /* die if addon mode and previewing a different theme */
        if ('customizr-pro' != self::$theme_name && did_action('plugin_loaded') ) {
          return;
        }

        //USEFUL CONSTANTS
        if ( ! defined( 'PC_MC_DIR_NAME' ) ) { define( 'PC_MC_DIR_NAME' , basename( dirname( __FILE__ ) ) ); }
        if ( ! defined( 'TC_BASE_URL' ) ) {
          //plugin context
           if ( ! defined( 'PC_MC_BASE_URL' ) ) { define( 'PC_MC_BASE_URL' , plugins_url( PC_MC_DIR_NAME ) ); }
        } else {
          //addon context
          if ( ! defined( 'PC_MC_BASE_URL' ) ) { define( 'PC_MC_BASE_URL' , sprintf('%s/%s' , TC_BASE_URL . 'addons' , basename( dirname( __FILE__ ) ) ) ); }
        }


        //gets the version of the theme or parent if using a child theme
        $this -> version               = ( self::$theme -> parent() ) ? self::$theme -> parent() -> Version : self::$theme -> Version;
        //define the plug option key
        $this -> addon_opt_prefix      = 'tc_theme_options';

        //plug-in version == theme version if as add-on
        $this -> plug_version          = did_action('plugins_loaded') ? $this -> version : $this -> plug_version;

        $plug_classes = array(
          'PC_utils_mc'              => array('/utils/classes/class_utils_mc.php'),
          'PC_back_mc'               => array('/back/classes/class_back_mc.php'),
          'PC_front_mc'              => array('/front/classes/class_front_mc.php')
        );//end of plug_classes array

        //loads and instanciates the plugin classes
        foreach ( $plug_classes as $name => $params ) {
            //don't load admin classes if not admin && not customizing
            if ( is_admin() && ! $this -> is_customizing ) {
                if ( false != strpos($params[0], 'front') )
                    continue;
            }

            if ( ! is_admin() && ! $this -> is_customizing ) {
                if ( false != strpos($params[0], 'back') )
                    continue;
            }

            if( ! class_exists( $name ) )
                require_once ( dirname( __FILE__ ) . $params[0] );

            $args = isset( $params[1] ) ? $params[1] : null;
            if ( $name !=  'PC_plug_updater' )
                new $name( $args );
        }
 
        //add / register the following actions only in plugin context
        if ( ! did_action('plugins_loaded') ) {
          //writes versions
          register_activation_hook( __FILE__              , array( __CLASS__ , 'pc_write_versions' ) );
        } else {
          //adds setup when as addon in customizr-pro
          add_action( 'after_setup_theme'                 , array( $this , 'pc_setup' ), 20 );
        }

    }//end of construct


    /**
    * @uses  wp_get_theme() the optional stylesheet parameter value takes into account the possible preview of a theme different than the one activated
    *
    * @return  the (parent) theme object
    */
    function pc_get_theme(){
      // Return the already set theme
      if ( self::$theme )
        return self::$theme;
      // $_REQUEST['theme'] is set both in live preview and when we're customizing a non active theme
      $stylesheet = $this -> is_customizing && isset($_REQUEST['theme']) ? $_REQUEST['theme'] : '';

      //gets the theme (or parent if child)
      $pc_theme               = wp_get_theme($stylesheet);

      return $pc_theme -> parent() ? $pc_theme -> parent() : $pc_theme;

    }
 
    /**
    *
    * @return  the theme name
    *
    */
    function pc_get_theme_name(){
      $pc_theme = $this -> pc_get_theme();
        
      return sanitize_file_name( strtolower( $pc_theme -> Name ) );
    }



    function pc_setup() {
        //declares the plugin translation domain
        if ( current_filter() == 'plugins_loaded' )
           load_plugin_textdomain( $this -> plug_lang , false, basename( dirname( __FILE__ ) ) . '/lang' );
        else { // load textdomain as addon
            $locale = apply_filters( 'theme_locale', get_locale(), $this -> plug_lang );
            $mofile = $this -> plug_lang . '-' . $locale . '.mo';
            load_textdomain( $this -> plug_lang , dirname( __FILE__ ) . '/lang/' . $mofile );
        }
    }



    //write current and previous version => used for system infos
    public static function pc_write_versions(){
        //Gets options
        $plug_options = get_option(PC_mc::$instance -> addon_opt_prefix);
        //Adds Upgraded From Option
        if ( isset($plug_options['ver']) ) {
            $plug_options['pc_upgraded_from'] = $plug_options['ver'];
        }
        //Sets new version
        $plug_options['ver'] = PC_mc::$instance -> plug_version;
        //Updates
        update_option( PC_mc::$instance -> addon_opt_prefix , $plug_options );
    }



    function pc_mc_admin_notice() {
        $what = $this -> mc_fire_plugin_active_notice ?
            __( 'must be disabled since it is included in this theme' , $this -> plug_lang ) :
            __( 'works only with the Customizr or Customizr Pro themes', $this -> plug_lang );

        $where = '';
        global $pagenow;
        if ( ! ( is_admin() && isset( $pagenow ) && 'plugins.php' == $pagenow ) && $this -> mc_fire_plugin_active_notice )
            $where = sprintf(__(' Open the <a href="%s">plugins page</a> to deactivate it.', $this -> plug_lang),
                         admin_url('plugins.php')
                     );

       ?>
        <div class="error">
            <p>
              <?php
              printf( __( 'The <strong>%1$s</strong> plugin %2$s.%3$s' , $this -> plug_lang ),
                $this -> plug_name,
                $what,
                $where
              );
              ?>
            </p>
        </div>
        <?php
    }


    /**
    * Returns a boolean on the customizer's state
    * @since  3.2.9
    */
    function pc_is_customizing() {
      //checks if is customizing : two contexts, admin and front (preview frame)
      global $pagenow;
      $bool = false;
      if ( is_admin() && isset( $pagenow ) && 'customize.php' == $pagenow )
        $bool = true;
      if ( ! is_admin() && isset($_REQUEST['wp_customize']) )
        $bool = true;
      if ( $this -> pc_doing_customizer_ajax() )
        $bool = true;
      return $bool;
    }

    /**
    * Returns a boolean
    * @since  3.3.2
    */
    function pc_doing_customizer_ajax() {
      return isset( $_POST['customized'] ) && ( defined( 'DOING_AJAX' ) && DOING_AJAX );
    }

} //end of class

//Creates a new instance of front and admin
new PC_mc;

endif;
