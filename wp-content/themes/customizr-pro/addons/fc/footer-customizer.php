<?php
/**
 * Plugin Name: Footer Customizer
 * Plugin URI: http://www.themesandco.com/extension/footer-customizer
 * Description: Customize the footer credits of the Customizr WordPress theme.
 * Version: 1.0.1
 * Author: presscustomizr
 * Author URI: http://presscustomizr.com
 * License: GPL2+
 */

/**
* Fires the plugin
* @author Nicolas GUILLAUME
* @since 1.0
*/
if ( ! class_exists( 'TC_fc' ) ) :
class TC_fc {
      //Access any method or var of the class with classname::$instance -> var or method():
      static $instance;
      public $version;
      public $plug_name;
      public $plug_file;
      public $plug_version;
      public $plug_prefix;
      public $plug_lang;
      public static $theme_name;
      public $is_customizing;
      public $default_options;

      function __construct () {
            self::$instance =& $this;
            /* LICENSE AND UPDATES */
            // the name of your product. This should match the download name in EDD exactly
            $this -> plug_name    = 'Footer Customizer';
            $this -> plug_file    = __FILE__; //main plugin root file.
            $this -> plug_prefix  = 'footer_customizer';
            $this -> plug_version = '1.0.1';
            $this -> plug_lang    = did_action('plugins_loaded') ? 'customizr' : 'tc_font_customizer';

            //define the plug option key
            //$this -> plug_option_prefix     = did_action('plugins_loaded') ? 'tc_pro_fc' : 'tc_fc';
            $this -> plug_option_prefix   = 'tc_theme_options';

            //gets the theme name (or parent if child)
            $tc_theme                     = wp_get_theme();
            self::$theme_name             = $tc_theme -> parent() ? $tc_theme -> parent() -> Name : $tc_theme-> Name;
            self::$theme_name             = sanitize_file_name( strtolower(self::$theme_name) );

            //check if theme is customizr pro and plugin mode (did_action not triggered yet)
            if ( 'customizr-pro' == self::$theme_name && ! did_action('plugins_loaded') ) {
              add_action( 'admin_notices', array( $this , 'customizr_pro_admin_notice' ) );
              return;
            }

            //USEFUL CONSTANTS
            if ( ! defined( 'TC_FC_DIR_NAME' ) )      { define( 'TC_FC_DIR_NAME' , basename( dirname( __FILE__ ) ) ); }
            if ( ! defined( 'TC_BASE_URL' ) ) {
              //plugin context
              if ( ! defined( 'TC_FC_BASE_URL' ) ) { define( 'TC_FC_BASE_URL' , plugins_url( TC_FC_DIR_NAME ) ); }
            } else {
              //addon context
              if ( ! defined( 'TC_FC_BASE_URL' ) ) { define( 'TC_FC_BASE_URL' , sprintf('%s/%s' , TC_BASE_URL . 'addons' , basename( dirname( __FILE__ ) ) ) ); }
            }


            $_activation_classes = array(
                  'TC_activation_key'             => array('/back/classes/activation/class_activation_key.php', array(  $this -> plug_name , $this -> plug_prefix , $this -> plug_version )),
                  'TC_plug_updater'               => array('/back/classes/updates/class_plug_updater.php'),
                  'TC_check_updates'              => array('/back/classes/updates/class_check_updates.php', array(  $this -> plug_name , $this -> plug_prefix , $this -> plug_version, $this -> plug_file ))
            );

            $_plug_core_classes = array();//end of plug_classes array

            $plug_classes       =  did_action('plugins_loaded') ? $_plug_core_classes : array_merge($_activation_classes , $_plug_core_classes);

            //checks if is customizing : two context, admin and front (preview frame)
            $this -> is_customizing = $this -> tc_is_customizing();



            //loads and instanciates the plugin classes
            foreach ($plug_classes as $name => $params) {
                  //don't load admin classes if not admin && not customizing
                  if ( is_admin() && ! $this -> is_customizing ) {
                        if ( false != strpos($params[0], 'front') )
                              continue;
                  }
                  if ( ! is_admin() && ! $this -> is_customizing ) {
                        if ( false != strpos($params[0], 'back') )
                              continue;
                  }

                  if( !class_exists( $name ) )
                      require_once ( dirname( __FILE__ ) . $params[0] );

                  $args = isset( $params[1] ) ? $params[1] : null;
                  if ( $name !=  'TC_plug_updater' )
                      new $name( $args );
            }

            //adds plugin text domain
            add_action( 'plugins_loaded'                    , array( $this , 'fc_plugin_lang' ) );
            //setup the plugin hooks : setup hook is different in plugin / addon context
            $_setup_hook = did_action('plugins_loaded') ? 'after_setup_theme' : 'plugins_loaded';
            add_action( $_setup_hook                        , array( $this , 'fc_plugin_setup') );

            $theme_name = self::$theme_name;

            $this -> default_options = array(
              'fc_show_footer_credits'    => 1,
              'fc_copyright_text'         => sprintf( '&copy; %1$s', esc_attr( date( 'Y' ) ) ),
              'fc_site_name'              => esc_attr( get_bloginfo() ),
              'fc_site_link'              => esc_url( home_url() ),
              'fc_show_designer_credits'  => 1,
              'fc_credit_text'            => __( 'Designed by' , $this -> plug_lang ),
              'fc_designer_name'          => 'Themes &amp; Co',
              'fc_designer_link'          => 'http://themesandco.com'
            );
      }//end of construct



      function fc_plugin_setup() {
        //update section map, since 3.2.0
        add_filter ( 'tc_add_section_map'                   , array( $this ,  'fc_update_section_map'), 20 );
        //update setting_control_map
        add_filter ( 'tc_add_setting_control_map'           , array( $this ,  'fc_update_setting_control_map'), 200 );
        add_filter ( 'tc_credits_display'                   , array( $this ,  'fc_custom_credits') );

        //js assets for the customizer
        add_action ( 'customize_controls_enqueue_scripts'   , array( $this , 'fc_customize_controls_js_css' ), 100);
        add_action ( 'customize_preview_init'               , array( $this , 'fc_customize_preview_js' ));
      }



      function fc_update_section_map( $sections ) {
        $_new_footer_section = array(
                        'footer_customizer_sec'          => array(
                                            'title'       =>  __( 'Footer credits' , 'customizr' ),
                                            'priority'    =>  20,
                                            'description' =>  __( 'Customize the footer credits' , 'customizr' ),
                                            'panel'       => 'tc-footer-panel'
                        ),
        );
        return array_merge($sections , $_new_footer_section);
      }



      function fc_custom_credits() {
        $_options = array();
        //get saved options
        foreach ( $this -> default_options as $_opt => $_default_value ) {
          $_options[$_opt] = $this->fc_get_option($_opt);
        }
        if ( 1 != $_options['fc_show_footer_credits'] )
          return '';

        ?>
        <div class="<?php echo apply_filters( 'tc_colophon_center_block_class', 'span4 credits' ) ?>">
          <p>
            <?php
            printf('&middot; <span class="fc-copyright-text">%1$s</span> <a class="fc-copyright-link" href="%2$s" title="%3$s" rel="bookmark">%3$s</a>',
              esc_attr( $_options['fc_copyright_text'] ),
              esc_url( $_options['fc_site_link'] ),
              esc_attr( $_options['fc_site_name'] )
            );
            if ( 1 == $_options['fc_show_designer_credits'] ) {
              printf( ' &middot; <span class="fc-credits-text">%1$s</span> <a class="fc-credits-link" href="%2$s" title="%3$s">%3$s</a> &middot;',
                esc_attr( $_options['fc_credit_text'] ),
                esc_url( $_options['fc_designer_link'] ),
                esc_attr( $_options['fc_designer_name'] )
              );
            } else {
              printf( ' &middot;');
            }
            ?>
          </p>
        </div>
        <?php
      }



      function fc_update_setting_control_map($_map) {
        $plug_option_prefix     = $this -> plug_option_prefix;
        $_defaults = $this -> default_options;
        $_new_settings = array(
          "fc_show_footer_credits" =>  array(
                    'default'       => isset( $_defaults['fc_show_footer_credits'] ) ? $_defaults['fc_show_footer_credits'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Enable the footer copyrights and credits" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'checkbox',
                    'priority'      => 1
          ),
          "fc_copyright_text" =>  array(
                    'default'       => isset( $_defaults['fc_copyright_text'] ) ? $_defaults['fc_copyright_text'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Copyright text" , "customizr" ),
                    'title'         => __( "Copyright"),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'text',
                    'priority'      => 5,
                    'transport'   =>  'postMessage',
          ),
          "fc_site_name" =>  array(
                    'default'       => isset( $_defaults['fc_site_name'] ) ? $_defaults['fc_site_name'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Site name" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'text',
                    'priority'      => 10,
                    'transport'   =>  'postMessage',
          ),
          "fc_site_link" =>  array(
                    'default'       => isset( $_defaults['fc_site_link'] ) ? $_defaults['fc_site_link'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Site link" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'url',
                    'priority'      => 20,
                    'transport'   =>  'postMessage',
          ),
          "fc_show_designer_credits" =>  array(
                    'default'       => isset( $_defaults['fc_show_designer_credits'] ) ? $_defaults['fc_show_designer_credits'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Display designer credits" , "customizr" ),
                    'title'         => __( "Credits"),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'checkbox',
                    'priority'      => 30
          ),
          "fc_credit_text" =>  array(
                    'default'       => isset( $_defaults['fc_credit_text'] ) ? $_defaults['fc_credit_text'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Credit text" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'text',
                    'priority'      => 40,
                    'transport'   =>  'postMessage',
          ),
          "fc_designer_name" =>  array(
                    'default'       => isset( $_defaults['fc_designer_name'] ) ? $_defaults['fc_designer_name'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Designer name" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'text',
                    'priority'      => 50,
                    'transport'   =>  'postMessage',
          ),
          "fc_designer_link" =>  array(
                    'default'       => isset( $_defaults['fc_designer_link'] ) ? $_defaults['fc_designer_link'] : false,
                    'control'       => 'TC_controls' ,
                    'label'         => __( "Designer link" , "customizr" ),
                    'section'       => 'footer_customizer_sec' ,
                    'type'          => 'url',
                    'priority'      => 60,
                    'transport'   =>  'postMessage',
          ),
        );
        return array_merge($_map , $_new_settings );
      }



      /**
      * Returns an option from the options array of the theme.
      *
      * @package FP
      * @since FP 1.0.0
      */
      function fc_get_option( $option_name , $option_group = null ) {
          //do we have to look in a specific group of option (plugin?)
          $option_group       = is_null($option_group) ? $this -> plug_option_prefix : $option_group;
          $all_saved          = (array) get_option( $option_group );
          $all_defaults       = $this -> default_options;
          $default            = isset($all_defaults[$option_name]) ? $all_defaults[$option_name] : false;
          return isset($all_saved[$option_name]) ? $all_saved[$option_name] : $default;
      }



      /**
      * Returns a boolean on the customizer's state
      *
      */
      function tc_is_customizing() {
            //checks if is customizing : two contexts, admin and front (preview frame)
            global $pagenow;
            $is_customizing = false;
            if ( is_admin() && isset( $pagenow ) && 'customize.php' == $pagenow ) {
            $is_customizing = true;
            } else if ( ! is_admin() && isset($_REQUEST['wp_customize']) ) {
            $is_customizing = true;
            }
            return $is_customizing;
      }



      //declares the plugin translation domain
      function fc_plugin_lang() {
        load_plugin_textdomain( $this -> plug_lang , false, basename( dirname( __FILE__ ) ) . '/lang' );
      }



      function fc_customize_preview_js() {
        wp_enqueue_script(
          'tc-fc-preview' ,
          sprintf('%1$s/back/assets/js/fc-customizer-preview.js' , TC_FC_BASE_URL ),
          array( 'customize-preview' ),
          $this -> plug_version ,
          true
        );
      }


      function fc_customize_controls_js_css() {
        wp_enqueue_script(
          'tc-fc-controls' ,
          sprintf('%1$s/back/assets/js/fc-customizer-control.js' , TC_FC_BASE_URL ),
          array( 'customize-controls' ),
          $this -> plug_version ,
          true
        );
      }




      function customizr_pro_admin_notice() {
          ?>
          <div class="error">
              <p>
                <?php
                printf( __( 'The <strong>%s</strong> plugin must be disabled since it is already included in this theme. Open the <a href="%s">plugins page</a> to desactivate it.' , $this -> plug_lang ),
                  $this -> plug_name,
                  admin_url('plugins.php')
                  );
                ?>
              </p>
          </div>
          <?php
      }

}//end of class

//Creates a new instance of front and admin
new TC_fc;

endif;