<?php
/**
* FRONT END CLASS
* @package  FPU
* @author Nicolas GUILLAUME
* @since 1.0
*/
class TC_front_fpu {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $plug_lang;

    function __construct () {
        self::$instance     =& $this;
        $this -> plug_lang  = TC_fpu::$instance -> plug_lang;
        add_action( 'template_redirect'         , array( $this , 'tc_set_fp_hook'), 10 );
        add_action( 'wp_head'                   , array( $this , 'tc_set_colors'), 10 );
        add_action( 'wp_enqueue_scripts'        , array( $this , 'tc_enqueue_plug_resources') );
    }//end of construct




    function tc_set_fp_hook() {
        $hook               = apply_filters( 'tc_fp_location' , esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_position' ) ) );
        switch ( $hook ) {
          case 'wp_nav_menu':
              add_filter ('wp_nav_menu'         , array($this , 'tc_fp_after_menu') , 100 , 2 );
              add_filter ('wp_page_menu'        , array($this , 'tc_fp_after_menu') , 100 , 2 );
          break;

          default:
              if ('custom_hook' == $hook ) {
                $custom_hook = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_custom_position' ) );
                $hook = !empty($custom_hook) ? $custom_hook : $hook;
              }
              add_action ( $hook           , array($this , 'tc_fp_block_display'), 10 , 1 );
          break;
        }//end switch

        //Set thumb shape with customizer options (since V1.17)
        add_filter ( 'fpc_row_classes'     , array( $this , 'tc_set_thumb_shape'), 10 , 2);
    }




    /*
    * @hook fpc_row_classes
    * @since v1.17
    */
    function tc_set_thumb_shape( $classes ) {
      $_shape = esc_attr( tc__f( '__get_fpc_option' , 'tc_thumb_shape') );
      if ( false == $_shape )
        return $classes;
      return array_merge( $classes , array($_shape) );
    }




    function tc_set_colors() {
        $bg_color               = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_background' ) );
        $text_color             = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_text_color' ) );

        printf('<style id="fpc-colors" type="text/css">%1$s%2$s%3$s%4$s</style>',
            "\n\n",

            ( isset( $bg_color) && ! empty( $bg_color) ) ? sprintf( '.fpc-widget-front .round-div {%1$s : %2$s%3$s!important}%3$s',
                                        "\nborder-color",
                                        $bg_color,
                                        "\n"
                                    ) : '',

            ( isset( $bg_color) && ! empty( $bg_color) ) ? sprintf( '.fpc-container {%1$s : %2$s%3$s!important}%3$s',
                                        "\nbackground-color",
                                        $bg_color,
                                        "\n"
                                    ) : '',

            ( isset( $text_color) && ! empty( $text_color) ) ? sprintf( '.fpc-marketing .fpc-widget-front h2, .fpc-widget-front > p {%1$s : %2$s%3$s!important}%3$s',
                                        "\ncolor",
                                        $text_color,
                                        "\n"
                                    ) : ''
        );//end of printf
    }



    function tc_fp_after_menu( $nav_menu , $args ) {
        //enable the filter only if menu location is primary (for natives wordpress themes, can filtered for other themes )
        $args     = (array)$args;
        $location = '';
        if ( isset($args['theme_location']) ) {
          $location = $args['theme_location'];
        }
        if ( TC_utils_fpu::$instance -> tc_get_theme_config('menu') == $location )
          return $nav_menu.$this->tc_fp_block_display( true );
        else
          return $nav_menu;
    }



    function tc_get_layout( $what = null) {
      $fp_per_row                     = apply_filters( 'fpc_per_line', 3 );
      //defines the span class
      $span_array = array(
        1 => 12,
        2 => 6,
        3 => 4,
        4 => 3,
        5 => 2,
        6 => 2,
        7 => 2
      );
      //default 4
      $span_value = ( $fp_per_row > 7) ? 1 : 4;
      $span_value = isset( $span_array[$fp_per_row] ) ? $span_array[$fp_per_row] :  $span_value;
      return ('span' == $what ) ? $span_value : array( $span_value, $fp_per_row );
    }


    /**
    * The template displaying the front page featured page block.
    *
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_fp_block_display() {
        $hook               = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_position' ) );

        //if the hook is loop start, we don't want to display fp in all queries.
        if ( 'loop_start' == $hook && (! is_main_query() || ! in_the_loop() ) )
            return;

        //gets display options
        $tc_show_featured_pages         = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp' ) );

        if ( !apply_filters( 'tc_show_fp', 0 != $tc_show_featured_pages && tc__f('__is_home') ) )
          return;

        //gets the featured pages array and sets the fp layout
        $fp_ids                         = apply_filters( 'fpc_featured_pages_ids' , TC_fpu::$instance -> fpc_ids);
        $fp_nb                          = count($fp_ids);
        $_fp_row_classes                = implode(" " , apply_filters('fpc_row_classes' , array('fpc-row-fluid' ,'fpc-widget-area') ) );
        list($span_value, $fp_per_row)  = $this -> tc_get_layout();

        //save $args for filter
        $args                           = array($fp_ids, $fp_nb, $fp_per_row, $span_value);
        ?>

        <?php ob_start(); ?>

          <div class="fpc-container fpc-marketing">
            <?php
              do_action ('__before_fp') ;

              $j = 1;
              for ($i = 1; $i <= $fp_nb ; $i++ ) {
                    printf('%1$s<div class="fpc-span%2$s fp-%3$s">%4$s</div>%5$s',
                        ( 1 == $j ) ? "<div class='{$_fp_row_classes}' role='complementary'>" : "",
                        $span_value,
                        $fp_ids[$i - 1],
                        $this -> tc_fp_single_display( $fp_ids[$i - 1]),
                        ( $j == $fp_per_row || $i == $fp_nb ) ? '</div>' : ''
                    );
              //set $j back to start value if reach $fp_per_row
              $j++;
              $j = ($j == ($fp_per_row + 1)) ? 1 : $j;
              }

              do_action ('__after_fp') ;

              //display edit link for logged in users with edit posts capabilities
              if ( apply_filters('tc_show_fp_edit_link' , is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) && ! TC_utils_fpu::$instance -> is_customizing ) {
                printf('<a class="fpc-edit-link fpc-btn fpc-btn-inverse" href="%1$s" title="%2$s" target="_blank">%2$s</a>',
                  admin_url().'customize.php',
                  __( 'Edit Featured Pages' , $this -> plug_lang )
                );
              }//end edit attachment condition
            ?>
          </div><!-- .fpc-container -->

        <?php  echo !tc__f( '__is_home_empty') ? apply_filters( 'fpc_after_fp_separator', '<hr class="featurette-divider '.current_filter().'">' ) : ''; ?>

       <?php
        $html = ob_get_contents();
        if ($html) ob_end_clean();

        //Return or echo
        $hook               = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_position' ) );
        if ( 'wp_nav_menu' != $hook )
          echo apply_filters( 'fpc_block_display' , $html, $args );
        else
          return apply_filters( 'fpc_block_display' , $html, $args );
      }



      /**
      * The template displaying one single featured page
      *
      * @package FPU
      * @since FPU 1.4
      * @param area are defined in featured-pages templates,show_img is a customizer option
      * @todo better area definition : dynamic
      */
      function tc_fp_single_display( $fp_single_id ) {
        //holder declaration
        $fp_holder_img          = apply_filters ('fp_holder_img' , '<img data-src="holder.js/270x250" alt="Holder Thumbnail" />' );
        $fp_img                 = $fp_holder_img;

        //gets boolean and general options
        //$prefix                 = TC_fpu::$instance -> plug_option_prefix;
        $tc_random_colors             = esc_attr( tc__f( '__get_fpc_option' , 'tc_random_colors') );
        $tc_show_fp_img               = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_img') );
        $tc_show_fp_img_override      = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_img_override') );
        $tc_show_fp_button            = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_button') );
        $tc_show_fp_title             = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_title') );
        $tc_show_fp_text              = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_text') );
        $tc_fp_text_limit             = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_text_limit') );
        $tc_fp_text_color_override    = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_text_color_override') );
        $tc_fp_button_color           = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_button_color') );
        $tc_button_text_color         = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_button_text_color') );
        $tc_fp_button_color_override  = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_button_color_override') );
        $tc_button_text               = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_button_text') );

        //random colors
        $rand_color_key         = '';
        if ( false != $tc_random_colors ) {
            $colors             = apply_filters( 'fpc_random_color_list' , array("#510300" , "#4D2A33", "#2B3F38", "#03A678" ,"#7A5945" , "#807D77" ,"#073233", "#B3858A","#F57B3D", "#449BB5", "#043D5D", "#EB5055", "#68C39F", "#1A4A72", "#4B77BE", "#5C97BF", "#F5AE30", "#EDA737", "#C8C8C8", "#13181C", "#248F79", "#D95448", "#26B89A" , "#EC6766", "#E74C3C") );
            $rand_color_key     = array_rand($colors, 1);
            $fp_img             = '<div style="background:' . $colors[$rand_color_key] . ';height: 100%;opacity: 0.7"></div>';
        }

        //if fps are not set
        if ( ! $this -> tc_fp_is_eligible($fp_single_id) ) {
            //admin link if user logged in
            $featured_page_link             = is_user_logged_in() ? apply_filters( 'fpc_link_url', admin_url().'customize.php' , $fp_single_id ) : '';
            $admin_link                     = is_user_logged_in() ? '<a href="'.admin_url().'customize.php" title="'.__( 'Customizer screen' , $this -> plug_lang ).'">'.__( 'here' , $this -> plug_lang ).'</a>' : '';

            //rendering
            $featured_page_id               =  null;
            $featured_page_title            =  apply_filters( 'fpc_title', __( 'Featured page' , $this -> plug_lang ) );
            $text                           =  apply_filters(
                                                'fpc_text',
                                                sprintf( __( 'Featured page description text : use the page or post excerpt or set your own custom text in the WordPress customizer screen %s.' , $this -> plug_lang ),
                                                  $admin_link
                                                ),
                                                $fp_single_id,
                                                $featured_page_id
                                              );
            $fp_img                         =  apply_filters ('fpc_img_src' , $fp_img );
        }

        else {
            $featured_page_id               = apply_filters( 'fpc_id', esc_attr( tc__f( '__get_fpc_option' , 'tc_featured_page_'.$fp_single_id) ), $fp_single_id );

            //get the page/post object
            $_post                          = get_post( $featured_page_id );

            $featured_page_link             = apply_filters( 'fpc_link_url', get_permalink( $featured_page_id ), $fp_single_id );
            $featured_page_title            = apply_filters( 'fpc_title', isset( $_post->post_title ) ? $_post->post_title : '', $fp_single_id, $featured_page_id );
            //when are we displaying the edit link?
            $edit_enabled                   = ( (is_user_logged_in()) && current_user_can('edit_pages') && is_page( $featured_page_id ) ) ? true : false;
            $edit_enabled                   = ( (is_user_logged_in()) && current_user_can('edit_post' , $featured_page_id ) && ! is_page( $featured_page_id ) ) ? true : $edit_enabled;
            $edit_enabled                   = apply_filters( 'tc_edit_in_fp_title', $edit_enabled );

            $featured_text                  = apply_filters( 'fpc_text', tc__f( '__get_fpc_option' , 'tc_featured_text_'.$fp_single_id ), $fp_single_id, $featured_page_id );
            $featured_text                  = apply_filters( 'fpc_text_sanitize', html_entity_decode( $featured_text ) , $fp_single_id, $featured_page_id );

            //set page/post excerpt as default text if no $featured_text
            $text                           = ( empty($featured_text) && !post_password_required($featured_page_id) ) ? strip_tags(apply_filters( 'the_excerpt' , $_post->post_excerpt )) : $featured_text ;
            $text                           = ( empty($text) && !post_password_required($featured_page_id) ) ? strip_tags(apply_filters( 'the_content' , $_post->post_content )) : $text ;

            //limit text to 200 car
            $default_fp_text_length         = $tc_fp_text_limit ? apply_filters( 'fpc_text_length', 200 ) : 9999;
            $tc_fp_text_length = strlen($text);
            if ( $tc_fp_text_length > $default_fp_text_length ){
                /* strpos returns FALSE if the needle was not found this coudl mess up substr*/
                $end_substr = strpos( $text, ' ' , $default_fp_text_length);
                $end_substr = ( $end_substr !== FALSE ) ? $end_substr : $tc_fp_text_length;
                $text       = substr( $text , 0 , $end_substr );
                $text       = ( $end_substr == $tc_fp_text_length ) ? $text : $text . ' ...';
            }

            //set the image : uses thumbnail if any then >> the first attached image then >> a holder script
            $fp_img_size                    = apply_filters( 'fpc_img_size' , 'fpc-size' );
            $fp_img_id                      = apply_filters( 'fpc_img_id', false , $fp_single_id , $featured_page_id );

            //When do we look for images?
            //1) when random colors not enabled
            //2) when random colors enabled AND overidde boolean is true
            if ( ! $tc_random_colors || $tc_show_fp_img_override ) {
              //try to get "tc_thumb" , "tc_thumb_height" , "tc_thumb_width"
              //tc_get_thumbnail_model( $requested_size = null, $_post_id = null , $_thumb_id = null )
              $_fp_img_model = TC_utils_thumb::$instance -> tc_get_thumbnail_model( $fp_img_size, $featured_page_id, $fp_img_id );

              //finally we define a default holder if no thumbnail found or page is protected
              if ( isset( $_fp_img_model["tc_thumb"]) && ! empty( $_fp_img_model["tc_thumb"] ) && ! post_password_required( $featured_page_id ) )
                $fp_img = $_fp_img_model["tc_thumb"];
              else
                $fp_img = $fp_holder_img;
            }

            //finally we define a default holder if no thumbnail found or page is protected
            $fp_img                 = apply_filters ('fp_img_src' , $fp_img , $fp_single_id , $featured_page_id );
          }//end if

          $random_color_enabled_class = $tc_random_colors ? 'tc-random-colors-enabled' : '';
          //Let's render this
          ob_start();
          ?>

          <div class="fpc-widget-front <?php echo $random_color_enabled_class ?>">
            <?php
              $tc_fp_img_block = sprintf('<div class="thumb-wrapper %1$s %2$s">%3$s%4$s</div>',
                   ( $fp_img == $fp_holder_img ) ? 'tc-holder' : '',
                   $tc_show_fp_img ? '' : 'fpc-hide',
                   apply_filters('fpc_round_div' , sprintf('<a class="round-div" href="%1$s" title="%2$s"></a>',
                                                    $featured_page_link,
                                                    $featured_page_title
                                                  ) ,
                                $fp_single_id ),
                   $fp_img
              );
              echo apply_filters( 'fpc_img_block' , $tc_fp_img_block , $fp_single_id );


              //When do we show the random colors?
              //1) if random is set
              //2) no override
              $apply_random_to_text = isset($colors[$rand_color_key]) ? true : false;
              $apply_random_to_text = $tc_fp_text_color_override ? false : $apply_random_to_text;

              //title block
              $tc_fp_title_block  = sprintf('<%1$s %2$s class="fp-title %3$s %4$s">%5$s %6$s</%1$s>',
                                    apply_filters( 'fpc_title_tag' , 'h2' ),
                                    $apply_random_to_text ? 'style="color:'.$colors[$rand_color_key].'!important"' : '',
                                    $tc_show_fp_title ? '' : 'fpc-hide',
                                    $tc_fp_text_color_override ? 'text-random-override' : '',
                                    $featured_page_title,
                                    ( isset($edit_enabled) && $edit_enabled )? sprintf('<span class="edit-link fpc-btn fpc-btn-inverse btn btn-inverse btn-mini"><a class="post-edit-link" href="%1$s" title="%2$s" target="_blank">%2$s</a></span>',
                                              get_edit_post_link( $featured_page_id ),
                                              __( 'Edit' , 'customizr' )
                                              ) : ''
              );
              echo apply_filters( 'fpc_title_block' , $tc_fp_title_block , $featured_page_title );

              //text block
              $tc_fp_text_block   = sprintf('<p class="fp-excerpt fp-text-%1$s %2$s %3$s" %4$s>%5$s</p>',
                                    $fp_single_id,
                                    $tc_show_fp_text ? '' : 'fpc-hide',
                                    $tc_fp_text_color_override ? 'text-random-override' : '',
                                    $apply_random_to_text ? 'style="color:'.$colors[$rand_color_key].'!important"' : '',
                                    $text
              );
              echo apply_filters( 'fpc_text_block' , $tc_fp_text_block , $fp_single_id , $text);

              //BUTTON BLOCK
              //When do we show the random colors?
              //1) if random is set and no button style
              //2) random + button style + no override
              $apply_random_to_btn = isset($colors[$rand_color_key]) ? true : false;
              //$apply_random_to_btn = ('none' == $tc_fp_button_color ) ? $apply_random_to_btn : false;
              $apply_random_to_btn = ('none' != $tc_fp_button_color && $tc_fp_button_color_override ) ? false : $apply_random_to_btn;

              if ($apply_random_to_btn) {
                  $btn_style = sprintf('style="background-color:%1$s;border-color:%2$s;color:%3$s"',
                          $colors[$rand_color_key],
                          $colors[$rand_color_key],
                          $tc_button_text_color
                  );
              } else {
                  $btn_style = ('none' != $tc_fp_button_color && 'original' != $tc_fp_button_color ) ? sprintf('style="color:%1$s!important"',
                          $tc_button_text_color
                  ) : '';
              }

              $fpc_button_text = apply_filters( 'fpc_button_text' , $tc_button_text , $fp_single_id );
              $tc_show_fp_button = $tc_show_fp_button && $fpc_button_text ? true : false;
              $tc_fp_button_block = sprintf('<a class="%1$s %2$s" href="%3$s" title="%4$s" data-color="%5$s" %6$s>%7$s</a>',

                                    apply_filters( 'fpc_button_class' ,
                                                sprintf('fpc-btn fpc-btn-primary fp-button %1$s %2$s %3$s',
                                                    ! $apply_random_to_btn ? $tc_fp_button_color : '',
                                                    isset($colors[$rand_color_key]) ? 'btn-random-colors' : '',
                                                    $tc_fp_button_color_override ? 'btn-random-override' : ''
                                                ),
                                                $fp_single_id
                                    ),//end filter

                                    $tc_show_fp_button ? '' : 'fpc-hide',
                                    $featured_page_link,
                                    $featured_page_title,
                                    $tc_fp_button_color,
                                    $btn_style,
                                    $fpc_button_text
              );
              echo apply_filters( 'fpc_button_block' , $tc_fp_button_block , $featured_page_link , $featured_page_title , $fp_single_id );

            ?>

          </div><!-- /.fpc-widget-front -->

          <?php
          $html = ob_get_contents();
          if ($html) ob_end_clean();
          return apply_filters( 'fpc_single_display' , $html, $fp_single_id, $fp_img, $featured_page_link, $featured_page_title, $text );
    }//end of function


    /**
    * Helper : check if the fp id exists in option AND is an existing post in db
    * handles the case when the post has been deleted
    *
    * @param $fp_single_id
    * @return  boolean
    */
    private function tc_fp_is_eligible($fp_single_id) {
      if ( false === (bool) tc__f( '__get_fpc_option' , 'tc_featured_page_'.$fp_single_id ) )
        return;

      //check if the post exists
      return null != get_post( tc__f( '__get_fpc_option' , 'tc_featured_page_'.$fp_single_id ) );
    }


    function tc_enqueue_plug_resources() {
        wp_enqueue_style(
          'fpu-front-style' ,
          sprintf('%1$s/front/assets/css/fpu-front%2$s.css' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
          array(),
          TC_fpu::$instance -> plug_version,
          $media = 'all'
        );

        //register and enqueue jQuery if necessary
        if ( ! wp_script_is( 'jquery', $list = 'registered') ) {
            wp_register_script('jquery', '//code.jquery.com/jquery-latest.min.js', array(), false, false );
        }
        if ( ! wp_script_is( 'jquery', $list = 'enqueued') ) {
          wp_enqueue_script( 'jquery');
        }

        //FPU Front end scripts
        wp_enqueue_script(
          'fpu-front-js',
          sprintf('%1$s/front/assets/js/fpu-front%2$s.js' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
          array('jquery'),
          TC_fpu::$instance -> plug_version,
          false
        );

        //enqueue imageCenter.js only if
        //1) not customizr
        //2) customizr v < 3.3.5
        //3) customizr pro v < 1.0.13
        $_imgcenter_bool = false;
        if ( false === strpos(TC_fpu::$theme_name, 'customizr') )
          $_imgcenter_bool = true;
        else {
          if ( 'customizr' == TC_fpu::$theme_name && version_compare( TC_fpu::$theme_version , '3.3.5' , '<' ) )
            $_imgcenter_bool = true;
          if ( 'customizr-pro' == TC_fpu::$theme_name && version_compare( TC_fpu::$theme_version , '1.0.13' , '<' ) )
            $_imgcenter_bool = true;
        }
        $_imgcenter_bool = $_imgcenter_bool && 1 == esc_attr( tc__f( '__get_fpc_option' , 'tc_center_fp_img' ) );

        if ( apply_filters('fpu_enqueue_centerimagejs' , $_imgcenter_bool ) ) {
          wp_enqueue_script(
            'tc-center-images',
            sprintf('%1$s/front/assets/js/jqueryCenterImages%2$s.js' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
            array('jquery', 'fpu-front-js'),
            TC_fpu::$instance -> plug_version,
            false
          );
        }

        //localizes
        wp_localize_script(
          'fpu-front-js',
          'FPUFront',
          apply_filters('tc_fpc_js_front_params' ,
            array(
              'Spanvalue'               => $this -> tc_get_layout('span'),
              'ThemeName'               => str_replace( ' ' , '-', TC_fpu::$theme_name),
              'DisableReorderingFour'   => esc_attr( tc__f( '__get_fpc_option' , 'tc_disable_reordering_768' ) ),
              'imageCentered'           => esc_attr( tc__f( '__get_fpc_option' , 'tc_center_fp_img' ) )
            )
          )
        );

        $tc_show_featured_pages_img     = esc_attr( tc__f( '__get_fpc_option' , 'tc_show_fp_img' ) );
        if ( 0 != $tc_show_featured_pages_img ) {
          //holder image
          wp_enqueue_script(
            'holder' ,
            sprintf( '%s/front/assets/js/holder.js' , TC_FPU_BASE_URL ),
            array(),
            TC_fpu::$instance -> plug_version,
            $in_footer = false
          );
        }
    }
} //end of class
