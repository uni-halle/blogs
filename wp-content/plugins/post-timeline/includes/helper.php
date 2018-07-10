<?php
//if ( ! defined( 'OT_VERSION' ) ) exit( 'No direct script access allowed' );

/**
 * Icon Picker.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.3
 */
if ( ! function_exists( 'ot_type_icon_picker' ) ) {
  
  function ot_type_icon_picker( $args = array() ) {
    
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    $is_readonly = false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-date-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' p-tl-cont">';
    
    /* ICON picker JS */      
    echo '<script>jQuery(document).ready(function($) { $("#' . esc_attr( $field_id ) . '").iconpicker(); });</script>';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="input-group format-setting-inner">';
      
        /* build date picker */
        echo '<input type="text" data-placement="bottomRight" class="form-control icp icp-auto"  name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '"' . ( $is_readonly == true ? ' readonly' : '' ) . ' />';
        echo '<span class="input-group-addon"></span>';
      echo '</div>';
    
    echo '</div>';
    
  }
}


function ptl_newcolor($color, $opacity = false) {
 
  $default = 'rgb(0,0,0)';
 
  //Return default if no color provided
  if(empty($color))
          return $default; 
 
  //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
          $color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
          if(abs($opacity) > 1)
            $opacity = 1.0;
          $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
          $output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}

function ptl_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  /**
   * This first part of our function is a fallback
   * for custom pagination inside a regular loop that
   * uses the global $paged and global $wp_query variables.
   * 
   * It's good because we can now override default pagination
   * in our theme, and use this function in default quries
   * and custom queries.
   */
  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }

  /** 
   * We construct the pagination arguments to enter into our paginate_links
   * function. 
   */
  $pagination_args = array(
    'base'            => get_pagenum_link(1) . '%_%',
    'format'          => 'page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 5,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  $str_pagin = '';
  if ($paginate_links) {
    $str_pagin   .= "<nav class='custom-pagination'>";
      $str_pagin .= "<span class='page-numbers hide page-num'>Page " . $paged . " of " . $numpages . "</span> ";
      $str_pagin .= $paginate_links;
    $str_pagin   .= "</nav>";
  }

  return $str_pagin;
}

function ptl_get_image($_post, $is_admin) {

  $c_image = '';


  //if admin image
  if($is_admin) {

    return POST_TIMELINE_URL_PATH.'public/img/sample/'.$_post->p_img;
  }

  if(isset($_post->custom['_thumbnail_id'][0])) {

      $c_image = wp_get_attachment_image_src($_post->custom['_thumbnail_id'][0] , 'large' );
      $c_image = isset( $c_image['0'] ) ? $c_image['0'] : null;
  }
  else
      $c_image = POST_TIMELINE_URL_PATH.'public/img/dummy.jpg';

  return $c_image;
}