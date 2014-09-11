<?php
/*
Plugin Name: Currency Conversion Guide
Description: Quicker than a Currency Calculator. Currency conversion guide is a cheat sheet to lookup significant values, e.g. 10, 20, 30, 50, 80, 100 of one currency in another.
Author: enclick
Version: 1.1
Author URI: http://fx-rate.net
Plugin URI: http://fx-rate.net/wordpress-currency-conversion-guide-plugin/
*/


require_once("functions.php");
static $currency_list;

/**
 * Add function to widgets_init that'll load our widget.
 */

add_action( 'widgets_init', 'load_currency_conversion_guide' );

/**
 * Register our widget.
 * 'currency_conversion_guide' is the widget class used below.
 *
 */
function load_currency_conversion_guide() {
        register_widget( 'currency_conversion_guide' );
}


/*******************************************************************************************
*
*       Currency Conversion Guide  class.
*       This class handles everything that needs to be handled with the widget:
*       the settings, form, display, and update.
*
*********************************************************************************************/
class currency_conversion_guide extends WP_Widget
{

  /*******************************************************************************************
      *
      *
      * Widget setup.
      *
      *
      ********************************************************************************************/
      function currency_conversion_guide() {
                #Widget settings

                $widget_ops = array( 'description' => __('Quicker than a Currency Calculator', 'currency_conveter') );

                #Widget control settings
                $control_ops = array( 'width' => 200, 'height' => 550, 'id_base' => 'currency_conversion_guide' );

                #Create the widget
                $this->WP_Widget( 'currency_conversion_guide', __('Currency Conversion Sheet', 'currency_conversion_guide'), $widget_ops, $control_ops );
        }


	/*******************************************************************************************
        *
        *
        * Update the widget settings.
        *
        *
        *******************************************************************************************/
        function update( $new_instance, $old_instance )
        {
		if(empty($currency_list)){
                        $file_location = dirname(__FILE__)."/currencies.ser";
                        if ($fd = fopen($file_location,'r')){
                           $currency_list_ser = fread($fd,filesize($file_location));
                           fclose($fd);
                        }
                        $currency_list = array();
                        $currency_list = unserialize($currency_list_ser);
                }

                $instance = $old_instance;

	      	$currency_code1 = strip_tags(stripslashes($new_instance['currency_code1']));
	      	$currency_code2 = strip_tags(stripslashes($new_instance['currency_code2']));
              	$instance['currency_code1'] = $currency_code1;
	      	$instance['currency_name1'] = strip_tags(stripslashes($currency_list[$currency_code1]['currency_name']));
	      	$instance['country_code1'] = strip_tags(stripslashes($currency_list[$currency_code1]['country_code']));
              	$instance['currency_code2'] = $currency_code2;
	      	$instance['currency_name2'] = strip_tags(stripslashes($currency_list[$currency_code2]['currency_name']));
	      	$instance['country_code2'] = strip_tags(stripslashes($currency_list[$currency_code2]['country_code']));
              	$instance['layout'] = strip_tags(stripslashes($new_instance['layout']));
              	$instance['title'] = $currency_name1 . " to ". $currency_name2 ;
              	$instance['width'] = strip_tags(stripslashes($new_instance['width']));
              	$instance['text_color'] = strip_tags(stripslashes($new_instance['text_color']));
              	$instance['border_color'] = strip_tags(stripslashes($new_instance['border_color']));
              	$instance['background_color'] = strip_tags(stripslashes($new_instance['background_color']));
              	$instance['transparent_flag'] = strip_tags(stripslashes($new_instance['transparent_flag']));

                return $instance;
        }


   	/*****************************************************************************************
	 *
         *      Displays the widget settings controls on the widget panel.
         *      Make use of the get_field_id() and get_field_name() function
         *      when creating your form elements. This handles the confusing stuff.
         *
         *
         ********************************************************************************************/
        function form( $instance )
        {

                #
                #       Set up some default widget settings
                #

      		if(empty($currency_list)){
			$file_location = dirname(__FILE__)."/currencies.ser"; 
			if ($fd = fopen($file_location,'r')){
	   	   	   $currency_list_ser = fread($fd,filesize($file_location));
	   	   	   fclose($fd);
			}
			$currency_list = array();
			$currency_list = unserialize($currency_list_ser);
        	}


      	   	$default = array(
	   		 'currency_code1'=>'EUR',
	   		 'currency_name1'=>'Euro',
	   		 'currency_code2'=>'USD',
	   		 'currency_name2'=>'American Dollar',
	   		 'title'=>'Euro to American Dollar',
           		 'country_code1' => 'EU',
           		 'country_code2' => 'US',
   			 'layout' => 'vertical',
           		 'width' => '150',
           		 'text_color' => '#000000',
           		 'border_color' => '#BBBBBB',
           		 'background_color' => '#FFFFFF',
           		 'transparent_flag'=>'0'
	  	);

      	   	if(empty($instance['currency_code1'])){
			$instance = $default;
		}


		// Extract value from vars
      		$currency_code1 = htmlspecialchars($instance['currency_code1'], ENT_QUOTES);
		$currency_name1 = htmlspecialchars($instance['currency_name1'], ENT_QUOTES);
      		$currency_code2 = htmlspecialchars($instance['currency_code2'], ENT_QUOTES);
		$currency_name2 = htmlspecialchars($instance['currency_name2'], ENT_QUOTES);
		$title = $currency_name1  . " to " . $currency_name2;
      		$country_code1 = htmlspecialchars($instance['country_code1'], ENT_QUOTES);
      		$country_code2 = htmlspecialchars($instance['country_code2'], ENT_QUOTES);
      		$layout = htmlspecialchars($instance['layout'], ENT_QUOTES);
      		$width = htmlspecialchars($instance['width'], ENT_QUOTES);
      		$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
      		$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
      		$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);
      		$transparent_flag = htmlspecialchars($instance['transparent_flag'], ENT_QUOTES);

       		#
                #
                #       START FORM OUTPUT
                #
                #


      		echo ' <div style="align:center;text-align:center;margin-bottom:10px">Currency Conversion Guide<br> by <a href="http://fx-rate.net">fx-rate.net</a></div>';

       		// Get currency, length and label type 

       	echo '<p><label for="' .$this->get_field_id( 'currency_code1' ). '">From Currency:'.
               '<select id="' .$this->get_field_id( 'currency_code1' ). '" name="' .$this->get_field_name( 'currency_code1' ). '" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	ccg_print_thecurrency_list($currency_code1, $currency_list);
      	echo '</select></label></p>';


       	echo '<p><label for="' .$this->get_field_id( 'currency_code2' ). '">To Currency:'.
               '<select id="' .$this->get_field_id( 'currency_code2' ). '" name="' .$this->get_field_name( 'currency_code2' ). '" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	ccg_print_thecurrency_list($currency_code2, $currency_list);
      	echo '</select></label></p>';

      	// Set layout type
      	echo '<p><label for="' .$this->get_field_id( 'layout' ). '">'.'Layout:&nbsp;&nbsp;';
       	echo '<select id="' .$this->get_field_id( 'layout' ). '" name="' .$this->get_field_name( 'layout' ). '" style="width:120px" >';
      	ccg_print_layout_list($layout);
      	echo '</select></label>';
      	echo '</p>';


      	// Set Width
	echo "\n";
      	echo '<p><label for="' .$this->get_field_id( 'width' ). '">'.'Width: &nbsp;&nbsp;&nbsp;'.
         '<select id="' .$this->get_field_id( 'width' ). '" name="' .$this->get_field_name( 'width' ). '" style="width:75px">';
      	ccg_print_thewidth_list($width);
      	echo '</select></label></p>';

      	// Set Text Widget color
      	echo '<p><label for="' .$this->get_field_id( 'text_color' ). '">'.'Text Color: &nbsp;&nbsp;&nbsp;&nbsp;';
       	echo '<select id="' .$this->get_field_id( 'text_color' ). '" name="' .$this->get_field_name( 'text_color' ). '" style="width:95px" >';
      	ccg_print_textcolor_list($text_color);
      	echo '</select></label>';
      	echo '</p>';

      	// Set Border Widget color
      	echo '<p><label for="' .$this->get_field_id( 'border_color' ). '">'.'Header Color:&nbsp;';
       	echo '<select id="' .$this->get_field_id( 'border_color' ). '" name="' .$this->get_field_name( 'border_color' ). '" style="width:95px" >';
      	ccg_print_bordercolor_list($border_color);
      	echo '</select></label>';
      	echo '</p>';

      	// Set Background Widget color
      	echo '<p><label for="' .$this->get_field_id( 'background_color' ). '">'.'Background Color:&nbsp;';
       	echo '<select id="' .$this->get_field_id( 'background_color' ). '" name="' .$this->get_field_name( 'background_color' ). '" style="width:95px" >';
      	ccg_print_backgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</p>';


	//   Transparent option

	$transparent_checked = "";
	if ($transparent_flag =="1")
	   $transparent_checked = "CHECKED";
	echo "\n";
        echo '<p><label for="' .$this->get_field_id( 'transparent_flag' ). '"> Transparent: 
	<input type="checkbox" id="' .$this->get_field_id( 'transparent_flag' ). '" name="' .$this->get_field_name( 'transparent_flag' ). '" value=1 '.$transparent_checked.' /> 
	</label></p>';

	// hidden title init

        echo '<label for="' .$this->get_field_id( 'title' ). '">';
	echo ' <input type="hidden" id="' .$this->get_field_id( 'title' ). '" name="' .$this->get_field_name( 'title' ). '" value="'.$title.'" /> </label>';



    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT TABLE WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function widget($args, $instance) 
     {


	// Get values 
      	extract($args);

      	// Extract value from vars
      	$currency_code1 = htmlspecialchars($instance['currency_code1'], ENT_QUOTES);
	$currency_name1 = htmlspecialchars($instance['currency_name1'], ENT_QUOTES);
      	$currency_code2 = htmlspecialchars($instance['currency_code2'], ENT_QUOTES);
	$currency_name2 = htmlspecialchars($instance['currency_name2'], ENT_QUOTES);
	$title = $currency_name1 . " to " . $currency_name2 . " Conversion";
	$title0 = $currency_name1 . " to " . $currency_name2 ;
      	$country_code1 = htmlspecialchars($instance['country_code1'], ENT_QUOTES);
      	$country_code2 = htmlspecialchars($instance['country_code2'], ENT_QUOTES);
      	$layout = htmlspecialchars($instance['layout'], ENT_QUOTES);
      	$width = htmlspecialchars($instance['width'], ENT_QUOTES);
      	$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);
      	$transparent_flag = htmlspecialchars($instance['transparent_flag'], ENT_QUOTES);


	if($transparent_flag == "1"){
  	     $background_color ="";
  	     $border_color ="";
	}

	if($currency_code)
		$length = "medium";

	$text_color = str_replace("#","",$text_color);

	echo $before_widget; 


	// Output title
	echo $before_title . $title0 . $after_title; 
	

	// Output calculator

	$widget_call_string = 'http://fx-rate.net/wp_conversion.php?';
	if($currency_code1) $widget_call_string .= 'currency='.$currency_code1 ."&";
	$widget_call_string .="tcolor=". $text_color ."&";
	if($currency_code2) $widget_call_string .= 'currency_pair='.$currency_code2 ;

	$widget_call_string .="&layout=". $layout;



	$target_url= "http://fx-rate.net/$currency_code1/$currency_code2/";
	
	$tsize=12;
#	if($layout == "vertical" && $length =="short") $tsize = 10;

#
#
#


	echo '<!-Currency Conversion Guide widget - HTML code - fx-rates.net -->';
	echo'<div  style="width:'.$width.'px; background-color:'.$background_color.';border:2px solid #888;text-align:center;margin:auto; padding: 0px 0px;margin-top:15px!important">';

	echo '<div style="margin: 0px; padding: 0px;text-align:center;align:center;background-color:'.$border_color. ';border-bottom:1px solid #888;width:100%">
 	     <a style="font-size:'.$tsize.'px!important;line-height:16px!important;font-family:arial;text-weight:bold;margin-bottom:6px;text-decoration:none;color:#'.$text_color.'" href="'.$target_url.'">';

	
	echo "<b>" . $title . '</b></a></div>';
     	echo'<script type="text/javascript" src="'.$widget_call_string.'"></script></div><!-end of code-->';
	echo $after_widget;


    }

}



?>