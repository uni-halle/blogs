<?php
/**
 * Plugin Name: Grid Customizr
 * Plugin URI: http://www.themesandco.com/extension/grid-customizer/
 * Description: Add beautiful effects to your blog post grid.
 * Version: 1.0.0
 * Author: ThemesandCo
 * Author URI: http://www.themesandco.com
 * License: GPLv2 or later
 */


/**
* Fires the plugin
* @package      GC
* @author Nicolas GUILLAUME - Rocco ALIBERTI
* @since 1.0
*/
if ( ! class_exists( 'TC_gc' ) ) :
class TC_gc {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    public $version;
    public $plug_name;
    public $plug_file;
    public $plug_version;
    public $plug_prefix;
    public $plug_lang;

    public static $theme_name;
    public $addon_opt_prefix;
    public $is_customizing;

    function __construct() {

        self::$instance =& $this;

        /* LICENSE AND UPDATES */
        // the name of your product. This should match the download name in EDD exactly
        $this -> plug_name    = 'Grid Customizer';
        $this -> plug_file    = __FILE__; //main plugin root file.
        $this -> plug_prefix  = 'gc';
        $this -> plug_version = '1.0.0';
        $this -> plug_lang    = 'tc_gc';

        //gets the theme name (or parent if child)
        $tc_theme                       = wp_get_theme();
        self::$theme_name               = $tc_theme -> parent() ? $tc_theme -> parent() -> Name : $tc_theme-> Name;
        self::$theme_name               = sanitize_file_name( strtolower(self::$theme_name) );

        //check if theme is customizr* and plugin mode (did_action not triggered yet)
        //stop execution if not customizr*
        if ( false === strpos( self::$theme_name, 'customizr' ) && ! did_action('plugins_loaded') ) {
          add_action( 'admin_notices', array( $this , 'gc_customizr_admin_notice' ) );
          return;
        }


        //USEFUL CONSTANTS
        if ( ! defined( 'TC_GC_DIR_NAME' ) ) { define( 'TC_GC_DIR_NAME' , basename( dirname( __FILE__ ) ) ); }
        if ( ! defined( 'TC_BASE_URL' ) ) {
          //plugin context
           if ( ! defined( 'TC_GC_BASE_URL' ) ) { define( 'TC_GC_BASE_URL' , plugins_url( TC_GC_DIR_NAME ) ); }
        } else {
          //addon context
          if ( ! defined( 'TC_GC_BASE_URL' ) ) { define( 'TC_GC_BASE_URL' , sprintf('%s/%s' , TC_BASE_URL . 'addons' , basename( dirname( __FILE__ ) ) ) ); }
        }


        //gets the version of the theme or parent if using a child theme
        $this -> version               = ( $tc_theme -> parent() ) ? $tc_theme -> parent() -> Version : $tc_theme -> Version;
        //define the plug option key
        $this -> addon_opt_prefix    = 'tc_theme_options';

        //plug-in version == theme version if as add-on
        $this -> plug_version          = did_action('plugins_loaded') ? $this -> version : $this -> plug_version;

        $plug_classes = array(
          'TC_utils_gc'              => array('/utils/classes/class_utils_gc.php'),
          'TC_back_gc'               => array('/back/classes/class_back_gc.php'),
          'TC_front_gc'              => array('/front/classes/class_front_gc.php')
        );//end of plug_classes array

        //checks if is customizing : two context, admin and front (preview frame)
        $this -> is_customizing = $this -> tc_is_customizing();

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
            if ( $name !=  'TC_plug_updater' )
                new $name( $args );
        }

        //add / register the following actions only in plugin context
        if ( ! did_action('plugins_loaded') ) {
          //writes versions
          register_activation_hook( __FILE__              , array( __CLASS__ , 'tc_write_versions' ) );
        } else {
          //adds setup when as addon in customizr-pro
          add_action( 'after_setup_theme'                 , array( $this , 'tc_setup' ), 20 );
        }

    }//end of construct



    function tc_setup() {
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
    public static function tc_write_versions(){
        //Gets options
        $plug_options = get_option(TC_gc::$instance -> addon_opt_prefix);
        //Adds Upgraded From Option
        if ( isset($plug_options['ver']) ) {
            $plug_options['tc_upgraded_from'] = $plug_options['ver'];
        }
        //Sets new version
        $plug_options['ver'] = TC_gc::$instance -> plug_version;
        //Updates
        update_option( TC_gc::$instance -> addon_opt_prefix , $plug_options );
    }



    function gc_customizr_admin_notice() {
        ?>
        <div class="error">
            <p>
              <?php
              printf( __( 'The <strong>%s</strong> plugin works only with the Customizr or Customizr Pro themes. Open the <a href="%s">plugins page</a> to desactivate it.' , $this -> plug_lang ),
                $this -> plug_name,
                admin_url('plugins.php')
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
    function tc_is_customizing() {
      //checks if is customizing : two contexts, admin and front (preview frame)
      global $pagenow;
      $bool = false;
      if ( is_admin() && isset( $pagenow ) && 'customize.php' == $pagenow )
        $bool = true;
      if ( ! is_admin() && isset($_REQUEST['wp_customize']) )
        $bool = true;
      if ( $this -> tc_doing_customizer_ajax() )
        $bool = true;
      return $bool;
    }

    /**
    * Returns a boolean
    * @since  3.3.2
    */
    function tc_doing_customizer_ajax() {
      return isset( $_POST['customized'] ) && ( defined( 'DOING_AJAX' ) && DOING_AJAX );
    }

} //end of class

//Creates a new instance of front and admin
new TC_gc;

endif;