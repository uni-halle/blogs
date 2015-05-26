<?php
/**
* FRONT END CLASS
* @package  GC
* @author Nicolas GUILLAUME, Rocco ALIBERTI
* @since 1.0
*/
class TC_front_gc {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $plug_lang;
    public $current_effect;

    function __construct () {
        self::$instance     =& $this;
        $this -> plug_lang  = TC_gc::$instance -> plug_lang;

        add_action( '__post_list_grid'         , array( $this , 'tc_set_gc_hooks'), 10 );
        add_action( 'wp_enqueue_scripts'       , array( $this , 'tc_enqueue_plug_resources'));
        add_filter( 'tc_user_options_style'    , array( $this , 'tc_gc_write_inline_css'), 100 );

        //EFFECT
        //Filter effect
        add_filter( 'tc_gc_current_effect'     , array( $this, 'tc_maybe_set_global_rand_effect') );
        //Set current effect property
        add_action( 'wp'                        , array( $this , 'tc_gc_set_current_effect' ) );

    }//end of construct


    /***************************************
    * HOOKS SETTINGS ***********************
    ****************************************/
    /**
    * hook : __post_list_grid
    */
    function tc_set_gc_hooks() {
      if ( ! $this -> tc_is_grid_customizer_enabled() )
        return;

      //disables the fade effect for excerpt
      add_filter( 'tc_grid_fade_excerpt'      , '__return_false' );
      //replaces class expanded by gc-expanded
      add_filter( 'tc_post_list_selectors'    , array( $this, 'tc_override_expanded_class' ), 50 );
      //disable edit link (it's added afterwards)
      add_filter( 'tc_edit_in_title'          , '__return_false' );

      // pre loop hooks
      add_action( '__before_article_container', array( $this, 'tc_set_gc_before_loop_hooks'), 20 );
      // loop hooks
      add_action( '__before_loop'             , array( $this, 'tc_set_gc_loop_hooks'), 20 );
      add_action( '__after_loop'              , array( $this, 'tc_set_gc_after_loop_hooks') );

      return;
    }



    /** PRE LOOP HOOKS
    * hook : __before_article_container
    * before loop
    */
    function tc_set_gc_before_loop_hooks() {
      //Removes the default grid behaviour for sticky posts
      remove_filter( 'tc_grid_display_figcaption_content' , array( TC_post_list_grid::$instance, 'tc_grid_set_expanded_post_title') );

      //TITLES
      if ( 'over' == esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_title_location') ) ) {
        //Removes the headings view content normally displayed below the single post grid
        //But still allows the metas to be displayed by user
        add_filter( 'tc_headings_content_html'  , '__return_false' , 20);
        //Incorporates the title inside
        add_filter( 'tc_grid_display_figcaption_content'    , array( $this, 'tc_gc_render_title_inside_caption') );
      } else {
        add_filter( 'tc_render_grid_headings_view' , '__return_true' );
      }

      //GRID CONTAINER CSS CLASSES
      add_filter( 'tc_article_container_class'            , array( $this, 'tc_gc_set_container_classes' ) );

      //EACH GRID ITEM CSS CLASSES
      add_filter( 'tc_gc_effect_class'                    , array( $this, 'tc_maybe_randomize_gc_effect' ) );
    }


    /**
    * hook : __before_loop
    * actions and filters inside loop
    * @return  void
    */
    function tc_set_gc_loop_hooks() {
      //adds the effect class + may be gc-no-excerpt to the figure html tag
      add_filter( 'tc_single_grid_post_wrapper_class' , array( $this, 'tc_gc_set_post_wrapper_class' ) );
      // trim and wrap the excerpt
      add_filter( 'excerpt_length'                    , array( $this, 'tc_gc_set_excerpt_length' ), 999);
      add_filter( 'get_the_excerpt'                   , array( $this, 'tc_gc_wrap_the_excerpt' ), 21);

      //add_filter( 'tc_the_title'                          , array( $this, 'tc_gc_wrap_title'), -1);
      /* Remove the update notice by default, it's ugly in small thumbs, handle this with CSS?*/
      remove_filter( 'tc_the_title'                   , array( TC_headings::$instance, 'tc_add_update_notice_in_title' ), 20);

      //display the edit link
      if ( TC_headings::$instance -> tc_is_edit_enabled() )
        add_action( '__grid_single_post_content'        , array( $this, 'tc_grid_render_edit_link' ), 50 );
    }

    function tc_grid_render_edit_link() {
      TC_headings::$instance -> tc_render_edit_link_view();
    }

    /**
    * hook : __after_loop
    * remove actions and filters after loop
    * @return  void
    */
    function tc_set_gc_after_loop_hooks() {
      //remove trim and wrap the excerpt filters => for fps after the content
      remove_filter( 'excerpt_length'               , array( $this, 'tc_gc_set_excerpt_length'), 999);
      remove_filter( 'get_the_excerpt'              , array( $this, 'tc_gc_wrap_the_excerpt'), 21);

      //remove_filter( 'tc_the_title'                 , array( $this, 'tc_gc_wrap_title'), -1);
      add_filter( 'tc_the_title'                    , array( TC_headings::$instance, 'tc_add_update_notice_in_title'), 20);
    }


    /******************************************
    * RENDER VIEWS
    ******************************************/
    /**
    * @return  html string
    * hook : tc_grid_display_figcaption_content
    */
    function tc_gc_render_title_inside_caption( $_html ) {
      return apply_filters(
        'tc_gc_title',
        sprintf( '%1$s%2$s',
          TC_headings::$instance -> tc_post_page_title_callback(),
          $_html
        )
      );
    }



    /******************************************
    * SETTERS / GETTTERS / CALLBACKS
    ******************************************/
    //Set effect property
    function tc_gc_set_current_effect() {
      $this -> _current_effect = $this -> tc_gc_get_current_effect();
    }

    /**
    * hook : tc_post_list_selectors
    * replaces 'expanded' by 'gc-expanded'
    * @return  string of classes
    */
    function tc_override_expanded_class($selectors) {
      return str_replace( 'expanded', 'gc-expanded', $selectors );
    }

    /**
    * hook : tc_article_container_class
    * inside loop
    * add custom classes to the grid .article-container element
    */
    function tc_gc_set_container_classes( $_classes ) {
      array_push( $_classes, 'tc-gc' );

      /* SOME OPTIONS */
      if ( esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_title_caps') ) )
        array_push( $_classes, 'gc-title-caps' );

      //TITLE / POST BACKGROUND CLASS
      $_bg_class = esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_transp_bg' ) );
      if ( ! $_bg_class || empty( $_bg_class ) )
        $_bg_class = 'gc-title-dark-bg';
      else
        $_bg_class = sprintf('gc-%s' , $_bg_class );

      array_push( $_classes, $_bg_class );

      //TITLE COLOR CLASS
      if ( apply_filters( 'tc_gc_white_title_hover' , true ) )
        array_push( $_classes, 'gc-white-title-hover' );

      $_color_class = esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_title_color' ) );
      if ( ! $_color_class || empty( $_color_class ) )
        $_color_class = 'gc-white-title';
      else
        $_color_class = sprintf('gc-%s-title' , $_color_class );

      array_push( $_classes, $_color_class );
      //array_push( $_classes, 'gc-title-bg', 'gc-title-shadow' );
      return $_classes;
    }

    /**
    * hook : tc_single_grid_post_wrapper_class
    * adds css classes to the figure html tag
    * inside loop
    * @return  array of classes
    */
    function tc_gc_set_post_wrapper_class( $_classes ) {
      array_push( $_classes, apply_filters( 'tc_gc_effect_class' , $this -> _current_effect ) );
      if ( ! $this -> tc_gc_wrap_the_excerpt( get_the_excerpt() ) )
        array_push( $_classes, 'gc-no-excerpt' );

      return $_classes;
    }


    /**
    * hook : tc_the_excerpt
    * inside loop
    * wraps the title into convenients span tags
    */
    function tc_gc_wrap_title( $_title ) {
      if ( ! $_title  )
        return;

      $_title_words = $this -> tc_split_text_chunks( $_title, 2);

      if ( sizeof($_title_words) > 1 ){
        $_title_span = array_map( array( $this, 'tc_wrap_string_tag'), $_title_words, array_fill( 0, sizeof ($_title_words),'span'), array( 'class="part1"', 'class="part2"') ) ;
        $_title = implode(' ', $_title_span);
      }
      return $this -> tc_wrap_string_tag( $_title, 'span' );
    }



    /**
    * hook : get_the_excerpt
    * inside loop
    * wraps the excerpt into convenients p tags
    */
    function tc_gc_wrap_the_excerpt( $_excerpt ) {
      //removes potential spaces at the beginning
      $_excerpt = trim( str_replace( '&nbsp;', ' ', $_excerpt ) );
      if ( ! $_excerpt || 0 == strlen( $_excerpt ) )
          return;

      if ( 'effect-4' != $this -> _current_effect ) {
          $_excerpt = '<p>' . $_excerpt . '</p>';
      } else {
          $_excerpt_words = $this -> tc_split_text_chunks( $_excerpt, 3 );
          if ( empty($_excerpt_words) )
            return;

          $_excerpt_p = array_map( array( $this, 'tc_wrap_string_tag'), $_excerpt_words, array_fill( 0, sizeof ($_excerpt_words),'p') ) ;
          $_excerpt = implode('', $_excerpt_p);
      }
      return $_excerpt;
    }


    /**
    * Set excerpt length in number of words
    * hook : excerpt_length
    * @return int
    */
    function tc_gc_set_excerpt_length( $_length ) {
      if ( ! TC_utils::$inst->tc_opt( 'tc_gc_limit_excerpt_length' ) )
        return $_length;

      $_user_length = esc_attr( TC_utils::$inst->tc_opt( 'tc_post_list_excerpt_length' ) );
      $_length = 'effect-4' != $this -> _current_effect ? 18 : 15;
      return apply_filters( 'tc_gc_excerpt_length', $_user_length > $_length ? $_length : $_user_length, $this -> _current_effect );
    }


    /**
    * @return css string
    * hook : tc_user_options_style
    */
    function tc_gc_write_inline_css( $_css ) {
      if ( ! $this -> tc_is_grid_customizer_enabled() )
        return $_css;
      //EFFECT #2 SPECIFICS
      if ( 'effect-2' == $this -> _current_effect ) {
        $_color = TC_utils::$instance -> tc_get_skin_color();
        $_css = sprintf("%s\n%s",
          $_css,
          "
          .hover .has-thumb.effect-2 {
            background: {$_color};
          }\n"
        );
      }

      //TITLE SKIN COLOR
      $_user_color = esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_title_color' ) );
      if ( 'white' != $_user_color ) {
        $_color = 'custom' == $_user_color ? esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_title_custom_color' ) ) : TC_utils::$instance -> tc_get_skin_color();
        $_css = sprintf("%s\n%s",
          $_css,
          "
          .gc-custom-title figure .entry-title a {
            color:{$_color};
          }\n"
        );
      }
      return $_css;
    }


    /**
    * Return a random effect from the list
    * @return string effect name
    * hook : tc_gc_current_effect
    */
    function tc_maybe_set_global_rand_effect( $_effect ) {
      if ( 'rand-global' != esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_random' ) ) )
        return $_effect;
      return $this -> tc_get_random_effect();

    }



    /**
    * Applies randoms effect to the grid
    * @return string effect name
    * hook : tc_gc_effect_class
    */
    function tc_maybe_randomize_gc_effect( $_effect  ) {
      if ( 'rand-each' != esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_random' ) ) )
        return $_effect;
      return $this -> tc_get_random_effect();
    }



    /**
    * Return a random effect from the list
    * @return string effect name
    */
    private function tc_get_random_effect() {
      return array_rand( TC_utils_gc::$instance -> tc_get_effects_list() , 1);
    }

    /******************************
    HELPERS
    *******************************/
    /**
    * @return string
    */
    private function tc_gc_get_current_effect() {
      return apply_filters( 'tc_gc_current_effect', TC_utils::$inst->tc_opt( 'tc_gc_effect') );
    }


    /**
    * @return bool
    */
    private function tc_is_grid_customizer_enabled() {
      $post_list_grid_enabled = ( did_action('__post_list_grid') ) ? true : TC_post_list_grid::$instance -> tc_is_grid_enabled();
      return apply_filters( 'tc_is_grid_customizer_enabled', $post_list_grid_enabled && esc_attr( TC_utils::$inst->tc_opt( 'tc_gc_enabled') ) );
    }


    /**
    * Return string wrapped into the passed tag
    * @return string
    */
    private function tc_wrap_string_tag( $_string, $_tag, $_attr = '' ) {
      return sprintf('<%1$s %2$s>%3$s</%1$s>',
        $_tag,
        $_attr,
        $_string
      );
    }


    /**
     * Return array of strings, with the $_chunks size
     * @return array
     */
    private function tc_split_text_chunks( $_string, $_chunks ) {
      $_string = trim( str_replace('&nbsp;', ' ',  $_string ) );
      if ( 0 == strlen(str_replace(' ', '',$_string) ) )
        return array();

      $_string_words = explode( ' ', $_string );
      $_chunk_size = ceil( sizeof($_string_words) / $_chunks );
      $_return = array();

      $_string_chunks = array_chunk( $_string_words, $_chunk_size, true);

      foreach ( $_string_chunks as $key => $value )
        array_push($_return, implode( ' ', $value) );
      return $_return;
    }



    /******************************
    * ASSETS
    *******************************/
    /* Enqueue Plugin resources */
    function tc_enqueue_plug_resources() {
      if ( ! $this -> tc_is_grid_customizer_enabled() )
        return;

      wp_enqueue_style(
        'gc-front-style' ,
        sprintf('%1$s/front/assets/css/gc-front%2$s.css' , TC_GC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        null,
        TC_gc::$instance -> plug_version,
        $media = 'all'
      );
    }

} //end of class
