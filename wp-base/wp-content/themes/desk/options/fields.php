<?php

function sa_options_table(){
global $dk_settings;

	$shortname = "desk"; //important to prevent conflict between themes

// Create theme options
global $options;
$options = array (

	array( "type" => "open"),

array( "name" => "Title Font Size",
 "desc" => "Set the size of the blog title font in pixels. E.g. to set the size as 55 pixels just put 55 in the box. Can be useful if you have a long title and want it to fit on one line.",
 "id" => $shortname."_headerfontsize",
 "type" => "text",
 "std" => "",
 "class" => "small-text"),
 
 array( "name" => "Title Banner Image",
 "desc" => "Enter the URL of your image banner to be displayed in the header. This will replace the blog title and description.",
 "id" => $shortname."_bannerimage",
 "type" => "text",
 "std" => ""),
 
 array( "name" => "Custom Feed URL",
 "desc" => "Input your custom feed URL which will set the URL for the icon in the menu bar. If left blank it will use the default wordpress RSS URL. Remember to include the http://",
 "id" => $shortname."_feed_url",
 "type" => "text",
 "std" => ""),
 
 array( "name" => "Hide Featured Image in Single Post",
 "desc" => "Hide the featured image of a post when in the single post view",
 "id" => $shortname."_hide_postimage",
 "type" => "checkbox",
 "std" => ""),

array( "name" => "Disable Comments in Page",
 "desc" => "Disable the comments feature in pages leaving comments only in blog posts",
 "id" => $shortname."_pagecommentdisable",
 "type" => "checkbox",
 "std" => ""),
 
array( "name" => "Hide tags for posts",
 "desc" => "Hide the tags in the footers of posts leaving only catagories and comments (and edit post link if you are logged in as admin)",
 "id" => $shortname."_hidetags",
 "type" => "checkbox",
 "std" => ""),


  array( "name" => "Disable RSS icon",
 "desc" => "Hide the RSS feed icon in the menu bar",
 "id" => $shortname."_hiderss",
 "type" => "checkbox",
 "std" => ""),
 
array( "name" => "Extra Styling Code",
 "desc" => "Put extra CSS styling code in here",
 "id" => $shortname."_custom_css",
 "type" => "textarea",
 "std" => ""),
 


array( "name" => "Extra Header Code",
 "desc" => "Put extra stuff for the header in here e.g. extra scripts etc",
 "id" => $shortname."_header_code",
 "type" => "textarea",
 "std" => ""),

array( "name" => "Analytics/Tracking Code",
 "desc" => "You can paste your Google Analytics or other website tracking code in this box. This will be automatically added to the footer.",
 "id" => $shortname."_analytics_code",
 "type" => "textarea",
 "std" => ""),

array( "type" => "close")

); 

$dk_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db

	foreach ($options as $value) {
	switch ( $value['type'] ) {


	case 'text':
	?>

		<tr valign="top" class="options_input options_text">
			
			<th><span class="labels"><label for="sa_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label></th>
			<td><input type="<?php echo $value['type']; ?>" name="sa_options[<?php echo $value['id']; ?>]" id="sa_options[<?php echo $value['id']; ?>]" class="<?php echo $value['class']; ?>" value="<?php if ( $dk_settings[ $value['id'] ]  != "") { echo stripslashes( $dk_settings[ $value['id'] ] ); } else { echo $value[ 'std' ]; } ?>" /></span></td>
			<td><span class="description"><?php echo $value['desc']; ?></span></td>
		</tr>

	<?php
	break;


	case 'textarea':
	?>
		<tr valign="top" class="options_input options_textarea">
			<th><label for="sa_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label></th>
			<td colspan=2><p><span class="description"><?php echo $value['desc']; ?></span></p>
			<textarea name="sa_options[<?php echo $value['id']; ?>]" type="<?php echo $value['type']; ?>" class="large-text code" cols="50" rows="10"><?php if ( $dk_settings[ $value['id'] ]  != "") { echo stripslashes( $dk_settings[ $value['id'] ] ); } else { echo $value[ 'std' ]; } ?></textarea></td>
			
		</tr>

	<?php
	break;


	case 'select':
	?>
		<tr class="options_input options_select">
			
			<th><span class="labels"><label for="sa_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label></span></th>
			<td><select name="sa_options[<?php echo $value['id']; ?>]" id="sa_options[<?php echo $value['id']; ?>]">
			<?php foreach ($value['options'] as $option) { ?>
					<option <?php if ( $dk_settings[ $value['id'] ] == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
			</select></td>
			<td><span class="description"><?php echo $value['desc']; ?></span></td>
		</tr>

	<?php
	break;


	case "radio":
	?>
		<tr class="options_input options_select">
			
			<td><span class="labels"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span></td>
			  <?php foreach ($value['options'] as $key=>$option) {
				$radio_setting = get_option($value['id']);
				if($radio_setting != ''){
					if ($key == get_option($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
				<td><input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br /></td>
				<?php } ?>
				<td><span class="description"><?php echo $value['desc']; ?></span></td>
		</tr>

	<?php
	break;


	case "checkbox":
	?>
		<tr class="options_input options_checkbox">
			
			<?php if($dk_settings[ $value['id'] ]){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
			<td></td>
			<td><input type="checkbox" name="sa_options[<?php echo $value['id']; ?>]" id="sa_options[<?php echo $value['id']; ?>]" value="true" <?php echo $checked; ?> /> <label for="sa_options[<?php echo $value['id']; ?>]"><?php echo $value['name']; ?></label></td>
			<td><span class="description"><?php echo $value['desc']; ?></span></td>
		 </tr>

	<?php
	break;


	case "close":
	$i++;
	?>


	<?php
	}
	}
}



?>