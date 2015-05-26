<?php
/**
* @package      FPU
* @subpackage   classes
* @since        2.0.5
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    Copyright (c) 2013-15, Nicolas GUILLAUME
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
if ( ! class_exists( 'TC_utils_thumb' ) ) :
class TC_utils_thumb {
    static $instance;
    function __construct () {
      self::$instance =& $this;
    }

    /**********************
    * THUMBNAIL MODELS
    **********************/
    /**
    * Gets the thumbnail or the first images attached to the post if any
    * inside loop
    * @return array( $tc_thumb(image object), $tc_thumb_width(string), $tc_thumb_height(string) )
    *
    * @package Customizr
    * @since Customizr 1.0
    */
    function tc_get_thumbnail_model( $requested_size = null, $_post_id = null , $_custom_thumb_id = null ) {
      if ( ! $this -> tc_has_thumb( $_post_id, $_custom_thumb_id ) )
        return array();

      $tc_thumb_size              = is_null($requested_size) ? apply_filters( 'fpc_img_size' , 'fpc-size' ) : $requested_size;
      $_post_id                   = is_null($_post_id) ? get_the_ID() : $_post_id;
      $_model                     = array();
      $_img_attr                  = array();
      $tc_thumb_height            = '';
      $tc_thumb_width             = '';

      //try to extract $_thumb_id and $_thumb_type
      extract( $this -> tc_get_thumb_info( $_post_id, $_custom_thumb_id ) );
      if ( ! isset($_thumb_id) || ! $_thumb_id || is_null($_thumb_id) )
        return array();

      //Try to get the image
      $image                      = wp_get_attachment_image_src( $_thumb_id, $tc_thumb_size);
      if ( empty( $image[0] ) )
        return array();

      //check also if this array value isset. (=> JetPack photon bug)
      if ( isset($image[3]) && false == $image[3] )
        $tc_thumb_size          = 'medium';

      $_img_attr['class']     = sprintf( 'attachment-%1$s tc-thumb-type-%2$s wp-post-image' , $tc_thumb_size , $_thumb_type );
      //Add the style value
      $_img_attr['style']     = apply_filters( 'fpu_thumb_inline_style' , '', $image );
      $_img_attr              = apply_filters( 'fpu_thumbnail_img_attributes' , $_img_attr );

      //get the thumb html
      if ( is_null($_custom_thumb_id) && has_post_thumbnail( $_post_id ) )
        //get_the_post_thumbnail( $post_id, $size, $attr )
        $tc_thumb = get_the_post_thumbnail( $_post_id , $tc_thumb_size , $_img_attr);
      else
        //wp_get_attachment_image( $attachment_id, $size, $icon, $attr )
        $tc_thumb = wp_get_attachment_image( $_thumb_id, $tc_thumb_size, false, $_img_attr );

      //get height and width if not empty
      if ( ! empty($image[1]) && ! empty($image[2]) ) {
        $tc_thumb_height        = empty($image[2]) ? 250 : $image[2];
        $tc_thumb_width         = empty($image[1]) ? 270 : $image[1];
      }
      //used for smart load when enabled
      $tc_thumb = apply_filters( 'fpu_thumb_html', $tc_thumb, $requested_size, $_post_id, $_custom_thumb_id );

      return apply_filters( 'fpu_get_thumbnail_model',
        isset($tc_thumb) && ! empty($tc_thumb) && false != $tc_thumb ? compact( "tc_thumb" , "tc_thumb_height" , "tc_thumb_width" ) : array(),
        $_post_id,
        $_thumb_id
      );
    }



    /**
    * inside loop
    * @return array( "_thumb_id" , "_thumb_type" )
    */
    private function tc_get_thumb_info( $_post_id = null, $_thumb_id = null ) {
      $_post_id     = is_null($_post_id) ? get_the_ID() : $_post_id;
      $_meta_thumb  = get_post_meta( $_post_id , 'fpu-thumb-fld', true );
      //get_post_meta( $post_id, $key, $single );
      //always refresh the thumb meta if user logged in and current_user_can('upload_files')
      //When do we refresh ?
      //1) empty( $_meta_thumb )
      //2) is_user_logged_in() && current_user_can('upload_files')
      $_refresh_bool = empty( $_meta_thumb ) || ! $_meta_thumb;
      $_refresh_bool = ! isset($_meta_thumb["_thumb_id"]) || ! isset($_meta_thumb["_thumb_type"]);
      $_refresh_bool = ( is_user_logged_in() && current_user_can('upload_files') ) ? true : $_refresh_bool;
      //if a custom $_thumb_id is requested => always refresh
      $_refresh_bool = ! is_null( $_thumb_id ) ? true : $_refresh_bool;

      if ( ! $_refresh_bool )
        return $_meta_thumb;
      return $this -> tc_set_thumb_info( $_post_id , $_thumb_id, true );
    }

    /**************************
    * EXPOSED HELPERS / SETTERS
    **************************/
    public function tc_has_thumb( $_post_id = null , $_thumb_id = null ) {
      $_post_id  = is_null($_post_id) ? get_the_ID() : $_post_id;
      //try to extract (OVERWRITE) $_thumb_id and $_thumb_type
      extract( $this -> tc_get_thumb_info( $_post_id, $_thumb_id ) );
      return isset($_thumb_id) && false != $_thumb_id && ! empty($_thumb_id);
    }


    /**
    * update the thumb meta and maybe return the info
    * public because also fired from admin on save_post
    * @param post_id and (bool) return
    * @return void or array( "_thumb_id" , "_thumb_type" )
    */
    public function tc_set_thumb_info( $post_id = null , $_thumb_id = null, $_return = false ) {
      $post_id      = is_null($post_id) ? get_the_ID() : $post_id;
      $_thumb_type  = 'none';

      //IF a custom thumb id is requested
      if ( ! is_null( $_thumb_id ) && false !== $_thumb_id ) {
        $_thumb_type  = false !== $_thumb_id ? 'custom' : $_thumb_type;
      }
      //IF no custom thumb id :
      //1) check if has thumbnail
      //2) check attachements
      else {
        if ( has_post_thumbnail( $post_id ) ) {
          $_thumb_id    = get_post_thumbnail_id( $post_id );
          $_thumb_type  = false !== $_thumb_id ? 'thumb' : $_thumb_type;
        } else {
          $_thumb_id    = $this -> tc_get_id_from_attachment( $post_id );
          $_thumb_type  = false !== $_thumb_id ? 'attachment' : $_thumb_type;
        }
      }
      //update_post_meta($post_id, $meta_key, $meta_value, $prev_value);
      update_post_meta( $post_id , 'fpu-thumb-fld', compact( "_thumb_id" , "_thumb_type" ) );
      if ( $_return )
        return apply_filters( 'fpu_set_thumb_info' , compact( "_thumb_id" , "_thumb_type" ), $post_id );
    }


    private function tc_get_id_from_attachment( $post_id ) {
      //define a filtrable boolean to set if attached images can be used as thumbnails
      if ( ! apply_filters( 'fpu_use_attachement_as_thumb' , true ) )
        return;

      //Case if we display a post or a page
      if ( 'attachment' != get_post_type( $post_id ) ) {
        //look for the last attached image in a post or page
        $tc_args = apply_filters('tc_attachment_as_thumb_query_args' , array(
            'numberposts'             =>  1,
            'post_type'               =>  'attachment',
            'post_status'             =>  null,
            'post_parent'             =>  $post_id,
            'post_mime_type'          =>  array( 'image/jpeg' , 'image/gif' , 'image/jpg' , 'image/png' ),
            'orderby'                 => 'post_date',
            'order'                   => 'DESC'
          )
        );
        $attachments              = get_posts( $tc_args );
      }

      //case were we display an attachment (in search results for example)
      elseif ( 'attachment' == get_post_type( $post_id ) && wp_attachment_is_image( $post_id ) ) {
        $attachments = array( get_post( $post_id ) );
      }

      if ( ! isset($attachments) || empty($attachments ) )
        return;
      return isset( $attachments[0] ) && isset( $attachments[0] -> ID ) ? $attachments[0] -> ID : false;
    }//end of fn

}//end of class
endif;