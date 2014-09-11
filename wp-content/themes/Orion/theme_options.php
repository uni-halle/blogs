<?php

$themename = "Orion";
$shortname = "orio";
$zm_categories_obj = get_categories('hide_empty=0');
$zm_categories = array();
foreach ($zm_categories_obj as $zm_cat) {
$zm_categories[$zm_cat->cat_ID] = $zm_cat->category_nicename;
}
$categories_tmp = array_unshift($zm_categories, "Select a category:");	

$options = array (


    array(  "name" => "Homepage post count ",
            "type" => "title",
			"desc" => "This section customizes the homepage post count.",
       		),
	   
	array(    "type" => "open"),
	
	array( 	"name" => "Number of posts on homepage",
			"desc" => "Enter number of posts on homepage.( Use multiples of 4 )",
			"id" => $shortname."_hpostcount",
			"std" => "",
            "type" => "text"), 		
						
		
	array(    "type" => "close"),	
	

    array(  "name" => "Slider settings",
            "type" => "title",
			"desc" => "This section customizes the slider.",
       		),
			
	array(    "type" => "open"),	
	
	array( 	"name" => "Slider post Category",
			"desc" => "Select the category to be featured on slider.",
			"id" => $shortname."_slide_cat",
			"std" => "Select a category:",
			"type" => "select",
			"options" => $zm_categories),	

	array( 	"name" => "Number of posts on slider",
			"desc" => "Set the number of posts on slider.",
			"id" => $shortname."_slide_count",
			"std" => "",
            "type" => "text"), 				
	
	array(    "type" => "close"),	
	
	array(  "name" => "125 x 125 banner Settings",
            "type" => "title",
			"desc" => "You can setup four 125 x 125 banners on your sidebar from here",
       ), 
	 
	array("type" => "open"),
	
	
	array("name" => "Banner-1 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner1",
        	"std" => "http://web2feel.com/images/webhostinghub.png",
            "type" => "text"), 
			
	array("name" => "Banner-1 Image alt tag",
			"desc" => "Enter your banner alt tag.",
            "id" => $shortname."_alt1",
        	"std" => "Cheap reliable web hosting from WebHostingHub.com.",
            "type" => "text"),    
	  
	array("name" => "Banner-1 Url",
			"desc" => "Enter the banner-1 url here.",
            "id" => $shortname."_url1",
            "std" => "http://www.webhostinghub.com/",
            "type" => "text"),    
			
	array("name" => "Banner-1 link title",
			"desc" => "Enter the banner-1 title here.",
            "id" => $shortname."_lab1",
            "std" => "Web Hosting Hub - Cheap reliable web hosting.",
            "type" => "text"),   
	
	array("type" => "break"),
	
	array("name" => "Banner-2 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner2",
            "std" => "http://web2feel.com/images/pcnames.png",
            "type" => "text"),    
	
	array("name" => "Banner-2 Image alt tag",
			"desc" => "Enter your banner alt tag.",
            "id" => $shortname."_alt2",
        	"std" => "Domain name search and availability check by PCNames.com.",
            "type" => "text"),    	   
	   
	   
	array("name" => "Banner-2 Url",
			"desc" => "Enter the banner-2 url here.",
            "id" => $shortname."_url2",
            "std" => "http://www.pcnames.com/",
            "type" => "text"), 

	array("name" => "Banner-2 link title",
			"desc" => "Enter the banner-2 title here.",
            "id" => $shortname."_lab2",
            "std" => "PC Names - Domain name search and availability check",
            "type" => "text"),   

	array("type" => "break"),		
			
	array("name" => "Banner-3 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner3",
            "std" => "http://web2feel.com/images/designcontest.png",
            "type" => "text"),  

	array("name" => "Banner-3 Image alt tag",
			"desc" => "Enter your banner alt tag.",
            "id" => $shortname."_alt3",
        	"std" => "Website and logo design contests at DesignContest.com.",
            "type" => "text"),    			
	   
	array("name" => "Banner-3 Url",
			"desc" => "Enter the banner-3 url here.",
            "id" => $shortname."_url3",
            "std" => "http://www.designcontest.com/",
            "type" => "text"),

	array("name" => "Banner-3 link title",
			"desc" => "Enter the banner-3 title here.",
            "id" => $shortname."_lab3",
            "std" => "Design Contest - Logo and website design contests",
            "type" => "text"), 		

	array("type" => "break"),
			
	array(  "name" => "Banner-4 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner4",
            "std" => "http://web2feel.com/images/webhostingrating.png",
            "type" => "text"),    

	array(  "name" => "Banner-4 Image alt tag",
			"desc" => "Enter your banner alt tag.",
            "id" => $shortname."_alt4",
        	"std" => "Reviews of the best cheap web hosting providers at WebHostingRating.com.",
            "type" => "text"),    
			
	array(  "name" => "Banner-4 Url",
			"desc" => "Enter the banner-4 url here.",
            "id" => $shortname."_url4",
            "std" => "http://webhostingrating.com",
            "type" => "text"),
	
	array(  "name" => "Banner-4 link title",
			"desc" => "Enter the banner-4 title here.",
            "id" => $shortname."_lab4",
            "std" => "Web Hosting Rating - Customer reviews of the best web hosts",
            "type" =>"text"), 	
		
	array("type" => "close"),	
				

	
);

 
function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=theme_options.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: themes.php?page=theme_options.php&reset=true");
            die;

        }
    }

      add_theme_page($themename." Options", "$themename Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}



function mytheme_admin() {

    global $themename, $shortname, $options;


    
    
?>
<div class="wrap">
<div class="opwrap" style="background:#fff; margin:20px auto; width:80%; padding:30px; border:1px solid #ddd;" >

<h2 class="wraphead" style="margin:10px 0px; padding:15px 10px; font-family:arial black; font-style:normal; background:#B3D5EF;"><b><?php echo $themename; ?> theme options</b></h2>

<?php
   if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
   if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
?>
<form method="post">

<?php foreach ($options as $value) {


switch ( $value['type'] ) {

case "image":
?>


<tr>
<td width="30%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
<td width="70%"><img src="<?php echo $value['id']; ?>" /></td>
</tr>
<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case "open":
?>
<table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">

<?php break;

case "close":
?>

</table><br />
<?php break;

case "break":
?>
<tr><td colspan="2" style="border-top:1px solid #C2DCEF;">&nbsp;</td></tr>

<?php break;

case "title":
?>

<table width="100%" border="0" style="background-color:#dceefc; padding:5px 10px;">

<tr>
    <td colspan="2"><h3 style="font-size:18px;font-family:Georgia,'Times New Roman',Times,serif;"><?php echo $value['name']; ?></h3></td>
</tr>

<?php break;

case 'text':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'textarea':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes (get_settings( $value['id'] )); } else { echo $value['std']; } ?></textarea></td>

</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'select':
?>
<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr>
<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case "checkbox":
?>
    <tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
        <td width="80%"><? if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                </td>
    </tr>

    <tr>
        <td><small><?php echo $value['desc']; ?></small></td>
   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php         break;

}
}
?>

<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<p style="text-align:right;"> <small> WordPress themes from <a href="http://www.web2feel.com/">web2feel.com</a> </small>
</div>
<?php
}
add_action('admin_menu', 'mytheme_add_admin'); ?>