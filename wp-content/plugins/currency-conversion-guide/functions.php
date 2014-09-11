<?php

function ccg_print_thecurrency_list($currency_code, $currency_list)
{


	$currency_name ="";
	foreach($currency_list as $k => $v)
	{
		$check_value = "";
		if ($currency_code == $k){
	   		$check_value = " SELECTED ";
			$country_name = $v['country_name'];
			$country_code = $v['country_code'];
			$currency_name = $v['currency_name'];
			$selected_values = $v;
		}

		$country_code = $v['country_code'];
		$country_name = $v['country_name'];
		$currency_name = $v['currency_name'];
		$output_string = '<option value="' . $k . '" ';
		$output_string .= $check_value . '>';
		$output_string .= $currency_name . '</option>';
		echo $output_string;
		echo "\n";

	}

}


// This function print for selector widget width

function ccg_print_thelength_list($size){
	 $size_list = array("short","medium");

	 echo "\n";
	foreach($size_list as $isize)
	{
		$check_value = "";
		if ($isize == $size)
	   		$check_value = ' SELECTED ';
		echo '<option value="'.$isize.'" '.$check_value .'>'.UCWords($isize).'</option>';
		echo "\n";
	}
}


// This function print for selector widget width

function ccg_print_thewidth_list($size){
	 $size_list = array("60","85","100","128","140","150","180","200","240","275","300","320","375","400","474","500","525","550","574","600","650","700");
	 echo "\n";
	foreach($size_list as $isize)
	{
		$check_value = "";
		if ($isize == $size)
	   		$check_value = ' SELECTED ';
		echo '<option value="'.$isize.'" '.$check_value .'>'.UCWords($isize).'</option>';
		echo "\n";
	}
}


// This function print for selector widget color list

function ccg_print_layout_list($type){

	 $type_list =array(
	       "horizontal" => "Horizontal",
	       "vertical" => "Vertical");

	 echo "\n";
	foreach($type_list as $key=>$ttype)
	{
		$check_value = "";
		if ($type == $key)
	   		$check_value = ' SELECTED ';

		echo '<option value="'.$key.'" style="background-color:'.$key .'" '.$check_value .'>'.$ttype.'</option>';
		echo "\n";
	}

}

// This function print for selector clock color list

function ccg_print_textcolor_list($text_color){

	 $color_list =array(
		   "#FF0000" => "Red",
		   "#CC033C" =>"Crimson",
		   "#FF6F00" =>"Orange",
		   "#FFCC00" =>"Gold",
		   "#009000" =>"Green",
		   "#00FF00" =>"Lime",
  		   "#0000FF" => "Blue",
		   "#000090" =>"Navy",
		   "#FE00FF" =>"Indigo",
		   "#F99CF9" =>"Pink",
		   "#900090" =>"Purple",
		   "#000000" =>"Black",
		   "#FFFFFF" =>"White",
		   "#DDDDDD" =>"Grey",
		   "#666666" =>"Gray"
         );

	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}


}


// This function print for selector clock color list

function ccg_print_bordercolor_list($text_color){

	 $color_list =array(
	      "#FF0000" => "Red",
	      "#CC033C" => "Crimson",
	      "#FF6F00" => "Orange",
	      "#FFCC00" => "Gold",
	      "#009000" => "Green",
	      "#a0c030" => "Dark Green",
	      "#00FF00" => "Lime",
	      "#963939" => "Brown",
	      "#C69633" => "Brass",
	      "#0000FF" => "Blue",
	      "#000090" => "Navy",
	      "#FE00FF" => "Indigo",
	      "#F99CF9" => "Pink",
	      "#900090" => "Purple",
	      "#000000" => "Black",
	      "#FFFFFF" => "White",
	      "#DDDDDD" => "Grey",
	      "#BBBBBB" => "Dark Grey",
	      "#666666" => "Gray",
	      "#F6F9F9;" => "Silver");


	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}



}


// This function print for selector clock color list

function ccg_print_backgroundcolor_list($text_color){

	 $color_list =array(
	       "#FF0000" => "Red",
	       "#CC033C" => "Crimson",
	       "#FF6F00" => "Orange",
	       "#F9F99F" => "Golden",
	       "#FFFCCC" => "Almond",
	       "#F6F6CC" => "Beige",
	       "#209020" => "Green",
	       "#209020" => "Green",
	       "#c3e44f" => "Light Green",
	       "#963939" => "Brown",
	       "#00FF00" => "Lime",
      	       "#99CCFF" => "Light Blue",
	       "#000090" => "Navy",
	       "#FE00FF" => "Indigo",
	       "#F99CF9" => "Pink",
	       "#993CF3" => "Violet",
	       "#000000" => "Black",
	       "#FFFFFF" => "White",
	       "#DDDDDD" => "Grey",
	       "#666666" => "Gray",
	       "#F6F9F9;" => "Silver");


	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}

}




?>