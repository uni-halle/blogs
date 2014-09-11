<?php
global $general_site_options, $social_media_options, $advert_options, $category_menu_options, $page_menu_options, $which_array;
$general_site_options =
	array(
		array("label" => "Custom Logo", "description" => "Full URL or folder path to your custom logo.", "name" => $input_prefix."custom_logo", "default" => "/images/layout/logo.gif", "id" => "", "input_type" => "file"),
		array("label" => "Color Options", "description" => "Select your desired colour option.", "name" => $input_prefix."theme_style", "default" => "blue", "id" => "", "input_type" => "select", "options" => array("Blue (Default)" => "blue", "Sea Blue" => "sea-blue", "White Rose" => "white-rose", "Blue &amp; Black" => "black-blue", "Black &amp; Green" => "black-green", "Black &amp; Red" => "black-red")),
		array("label" => "Home Page Posts", "description" => "Number of Posts to display on the Home Page.", "name" => $input_prefix."home_page_posts", "default" => "5", "id" => "", "input_type" => "select", "options" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "15" => "15", "20" => "20")),
		array("label" => "Home Page Categories", "description" => "Which Category will we display on the Home Page?", "name" => $input_prefix."home_page_categories", "default" => "", "id" => "", "input_type" => "select", "options" => "loop_categories"),
		array("label" => "Home Page Images", "description" => "This will automatically generate an image for your posts on the home page if you have not specified an image in the 'image url' field when adding/updating a post.", "name" => $input_prefix."auto_home_images", "default" => "yes", "id" => "", "input_type" => "select", "options" => array("Yes" => "yes", "No" => "no")),
		array("label" => "Site Analytics", "description" => "Enter in the Google Analytics Script here.","name" => $input_prefix."googleAnalytics", "default" => "", "id" => "","input_type" => "memo")
	);
	
$social_media_options =
	array(
		array(
			"main_section" => "RSS Details",
			"main_description" => "This will allow visitors to subscribe to your custom RSS feed, as well as your Feedburner Feed via e-mail.",
			"sub_elements" => 
				array(
					array("label" => "RSS URL", "description" => "", "name" => $input_prefix."rss_url", "default" => "", "id" => "", "input_type" => "text"),
					array("label" => "Feedburner ID", "description" => "", "name" => $input_prefix."feedburner_id", "default" => "", "id" => "", "input_type" => "text")
				)
		),
		array("label" => "Promote Post Options", "description" => "This will allow you to include Digg, Moo, Bump and Tweetmeme links in your posts.", "name" => $input_prefix."promote_posts", "default" => "yes", "id" => $input_prefix."promote_posts", "input_type" => "select", "options" => array("Yes" => "yes", "No" => "no"))
	);

$auto_advert_options =
	array(
		array(
			"main_section" => "3<sup>rd</sup> Party Adverts",
			"main_description" => "These settings allow you to manage your 3<sup>rd</sup> party (eg. BuySellAds, Afrigator etc.) adverts which display in the right side bar, and underneathe the Article in your home and post pages.",
			"sub_elements" => 
				array(
					array("label" => "Post Page Script", "description" => "", "name" => $input_prefix."main_ad_buysell_ads", "default" => "0", "id" => $input_prefix."main_ad_buysell_ads", "input_type" => "select", "options" => array("On" => "on", "Off" => "off")),
					array("label" => "Insert Page Script", "description" => "", "name" => $input_prefix."main_ad_buysell_id", "default" => "", "id" => $input_prefix."main_ad_buysell_id", "input_type" => "memo"),
					array("label" => "Right Sidebar Script", "description" => "", "name" => $input_prefix."small_buysell_ads", "default" => "0", "id" => $input_prefix."small_buysell_ads", "input_type" => "select", "options" => array("On" => "on", "Off" => "off")),
					array("label" => "Insert Sidebar Script", "description" => "", "name" => $input_prefix."small_buysell_id", "default" => "", "id" => $input_prefix."small_buysell_id", "input_type" => "memo")
				)
		)
	);
	
$custom_advert_options =
	array(
		array(
			"main_section" => "Post Page Ads",
			"main_description" => "These settings allow you to manage your custom adverts which display in the post pages of your site. (Recommended size for the post ad is 560px by 60px.)",
			"sub_elements" => 
				array(
					array("label" => "Advert Title", "description" => "", "name" => $input_prefix."post_ad_title", "default" => "", "id" =>  $input_prefix."post_ad_title", "input_type" => "text"),
					array("label" => "Advert Link", "description" => "", "name" => $input_prefix."post_ad_link", "default" => "", "id" =>  $input_prefix."post_ad_link", "input_type" => "text"),
					array("label" => "Image URL", "description" => "", "name" => $input_prefix."post_ad_image", "default" => "", "id" =>  $input_prefix."post_ad_image", "input_type" => "text")
				)
		),
		array(
			"main_section" => "Sidebar Ads",
			"main_description" => "These settings allow you to manage your custom adverts which display in the right side bar. Use the \"Media\" section to upload you ad image, and paste the image url into the \"Image url\" text field. (Recommended width is 125px by 125px.)",
			"sub_elements" => 
				array(
					array("label" => "Number of Small Ads", "description" => "", "name" => $input_prefix."small_ads", "default" => "0", "id" =>  $input_prefix."small_ads", "input_type" => "select", "options" => array("None" => "0", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10"))
				)
		)
	);
$page_menu_options =
	array(
		array(
			"main_section" => "Pages",
			"main_description" => "Order your pages by the following.",
			"sub_elements" => 
				array(
					array("label" => "Order By", "description" => "", "name" => $input_prefix."page_order", "default" => "", "id" =>  $input_prefix."page_order", "input_type" => "select", "options" => array("Title" => "post_title", "Page Order" => "menu_order", "Creation Date/Time" => "post_date", "Modified Date/Time" => "post_modified", "Page ID" => "ID", "Post Slug" => "post_name")),
					array("label" => "Direction", "description" => "", "name" => $input_prefix."page_updown", "default" => "", "id" =>  $input_prefix."page_updown", "input_type" => "select", "options" => array("Ascending" => "ASC", "Descending" => "DESC")),
				)
		)
	);
$category_menu_options =
	array(
		array(
			"main_section" => "Categories",
			"main_description" => "Order your categories by the following.",
			"sub_elements" => 
				array(
					array("label" => "Order By", "description" => "", "name" => $input_prefix."category_order", "default" => "ID", "id" =>  $input_prefix."category_order", "input_type" => "select", "options" => array("Category ID" => "ID", "Name" => "name")),
					array("label" => "Direction", "description" => "", "name" => $input_prefix."category_updown", "default" => "", "id" =>  $input_prefix."category_updown", "input_type" => "select", "options" => array("Ascending" => "ASC", "Descending" => "DESC")),
				)
		)
	);
// Set the global var to see which array we're dealing with

/*********************************************************************************/
/* Below we create the form containers, according to our arrays in functions.php */
function fetch_options($which_array)
	{		
	
		global $input, $counter, $label_class;
		if($which_array) :
			foreach($which_array as $input)
				{
					// This option Has Sub Options, so loop them and give them slightly different classes
					if($input['description'] !== "") :
?>
						<p class="section_notice"><?php echo $input["description"];?></p>
<?php
					endif;
?>
					<div class="form_item clearfix">
<?php
					// If this option has sub-options
					if($input["main_section"]) :
						$i = 0;
?>
						<h3><?php echo $input["main_section"] ?></h3>
                          
<?php 
						if($input["main_description"] && $input["main_description"] !== "") :
?>
							 <p class="section_notice"><?php echo $input["main_description"]; ?></p>
<?php
						endif;
						foreach($input["sub_elements"] as $sub_input)
							{
								$i++;
?>
							 <div class="form_label">
								<div class="input_label"><?php echo $sub_input["label"]; ?></div>
<?php 
								/* Set the class for the div */
								if($sub_input["options"] && $sub_input["input_type"] == "checkbox")
									{$label_class = "catagories";}
								else
									{$label_class = "input_form";}
									
								/* Output the form items using the create_form function */
								create_form($sub_input, count($input), $label_class);
								
								/* Special case, a bit of a hard-code we know - if this is the "Sidebar Ad Number" option, bring into play the small_ad_form function
								Which can be found below */
								if($sub_input["name"] == "ocmx_small_ads") :
									if(get_option("ocmx_small_buysell_ads") == "1") :
										$no_display = "no_display";
									else :
										$no_display = ""; 
									endif;
?>
                                    <br />
                                    <div class="clearfix <?php echo $no_display; ?>" id="small-ads-div">
<?php 
                                    for($i = 1; $i <= get_option("ocmx_small_ads"); $i++)
                                        {small_ad_form($i);}
?>
                                    </div>
<?php 
                                endif;
?>
							</div> 
<?php
							}
					//This Option just sits on its own...
					else :			
?>
                        <div class="form_label">
							<div class="input_label"><?php echo $input["label"]; ?></div>
<?php 						
							if($input["options"] && $input["input_type"] == "checkbox")
								{$label_class = "catagories";}
							else
								{$label_class = "input_form";}
								
							/* Output the form items using the create_form function */
							create_form($input, count($input), $label_class);
?>
						</div>
<?php 
					endif;					
?>					
					</div>
<?php
				}
		endif;		
	}
	
/************************************************************************/
/* Below we check which kind of form item we're creating, and output it */
function create_form($input, $counter, $label_class)
	{
?>
		<div class="<?php echo $label_class; ?>">
<?php 
		// Set the input value to default or get_option()
		if(!get_option($input["name"])) :			
			$input_value = $input["default"];			
		else :
			$input_value = get_option($input["name"]);
		endif;	
		
		// This denotes that we're using the wp-categories instead of set options
		if($input["options"] == "loop_categories") :
			$option_loop = get_categories();
		elseif($input["options"] == "loop_pages") :
			$option_loop = get_pages();
		else :
			//$values =  array_values($input["options"]);	
			$option_loop = $input["options"];
		endif;
		
		//Switch through the input_type
		switch($input["input_type"]) 
			{
				case 'select';
				if($input['linked']) :
?>
	    			<select size="1" name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>" onchange="javacript: check_linked('<?php echo $input['id'];?>', '<?php echo $input['linked'];?>')">
<?php
				else :
?>
	    			<select size="1" name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>">
<?php
				endif;
					// Tiny little hack.. if we've set the options to loop through the categories, we must have an "All" option
					if ($input["options"] == "loop_categories") :
?>
						<option <?php if($input_value == 0){echo "selected=\"selected\"";} ?> value="0">All</option>
<?php 
					elseif($input["options"] == "loop_pages") :
?>
						<option <?php if($input_value == 0){echo "selected=\"selected\"";} ?> value="0">Use a Custom Description</option>
<?php
					endif;
                    foreach($option_loop as $option_label => $value)
                        { 	
							// Set the $value and $label for the options
							if($input["options"] == "loop_categories") : 
								$use_value =  $value->slug;
								$label =  $value->cat_name;
							elseif($input["options"] == "loop_pages") : 
								$use_value =  $value->ID;
								$label =  $value->post_title;
							else :		
								$use_value  =  $value;
								$label =  $option_label;
							endif;
							//If this option == the value we set above, select it
                            if($use_value == $input_value)
                                {$selected = " selected='selected' ";}
                            else
                                {$selected = " ";}
?>
                            <option <?php echo $selected; ?> value="<?php echo $use_value; ?>"><?php echo $label; ?></option>
<?php 
                        }
?>
                </select>
<?php 
				break;
				case 'checkbox' :								
?>			
                <div class="selection_catagory clearfix">
                    <ul class="cat_option clearfix">
<?php 
					 foreach($option_loop as $option_label => $value) :
						// Set the $value and $label for the options
						if($input["options"] == "loop_categories") : 
							$use_value =  $value->slug;
							$label =  $value->cat_name;
						elseif($input["options"] == "loop_pages") : 
							$use_value =  $value->ID;
							$label =  $value->post_title;
						else :		
							$use_value  =  $value;
							$label =  $option_label;
						endif;
						if($use_value == $input_value)
							{$selected = " checked='checkeds' ";}
						else
							{$selected = " ";}
?>
						<li><input type="checkbox" name="<?php echo $input["name"]."_".$counter; ?>" value="<?php echo $use_value; ?>" /> <?php echo $label; ?></li>
<?php 
					endforeach;
?>
                    </ul>
                </div>
<?php 
				break;
				case 'radio' :
?>
                <div class="selection_catagory clearfix">
                    <ul class="cat_option">
<?php                         
					 foreach($option_loop as $option_label => $value) :
					 	// Check whether we must loop through the categories for the options
						if($input["options"] == "loop_categories") : 
							$use_value =  $value->slug;
							$label =  $value->cat_name;
						else :		
							$use_value  =  $value;
							$label =  $option_label;
						endif;
						if($use_value == $input_value) {$selected = " selected='selected' ";}
						else
							{$selected = " ";}
?>
						<li><input type="radio" name="<?php echo $input["name"]; ?>" value="<?php echo $use_value; ?>" />&nbsp;<?php echo $option_label; ?> </li>
<?php 
					endforeach;
?>
                    </ul>
                </div>
<?php                    
				break;
				case 'memo':
					if($input['linked']) :
						if((get_option($input['linked'])) && get_option($input['linked']) !== "0") :
							$disabled_element = "disabled=\"disabled\"";
						else :
							$disabled_element = "";
						endif;
					else :
							$disabled_element = "";
					endif;
?>
					<textarea name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>" <?php echo $disabled_element; ?> class="mceNoEditor"><?php echo stripslashes($input_value); ?></textarea>
<?php 					
				break;
				case 'file':
?>
                    <input type="file" style="width: 300px;" name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>" value="<?php echo $input_value; ?>" />
                    <br /> <br />
                    <em>Current Image</em>
                    <div class="form-thumbnail">
                    	<a id="<?php echo $ad_href_id; ?>" style="background: url('<?php bloginfo('wpurl');?><?php echo "/wp-content/uploads/".$input_value; ?>')  no-repeat center;" href="<?php bloginfo('wpurl');?><?php echo "/wp-content/uploads/".$input_value; ?>" class="std_link" rel="lightbox" target="_blank">
	                    </a>
                    </div>
					
<?php 					
				break;
				case 'zip_file':
?>
                    <input type="file" style="width: 300px;" name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>" value="<?php echo $input_value; ?>" />
<?php 					
				break;
				default :
?>
					<input type="text" name="<?php echo $input["name"]; ?>" id="<?php echo $input["id"]; ?>" value="<?php echo $input_value; ?>" />
<?php
				break;
			}		
?>
		</div>
<?php 
	}
function menu_options($defaults) {
		global $input_prefix, $parent_count, $advert_options, $page_menu_options, $category_menu_options;
		$page_args = array("sort_column" => get_option("ocmx_page_order"), "sort_order" => get_option("ocmx_page_updown"), "depth" => "1");
		$fetch_pages= get_pages($page_args);
?>
        <?php fetch_options($page_menu_options); ?>
		<div class="selection_sections clearfix">
            <ul class="section_option">
<?php
				//Set the $i var to count through as we loop through the pages
				$i = 1;
				foreach ($fetch_pages as $this_page) : 
					$use_id = $input_prefix."menu_page_".$this_page->ID;					
					// Check/Uncheck Categories
					if(get_option($use_id)) : $main_checked = "checked='checked'"; else : $main_checked = ""; endif;
					// If we're halfway then place create a new <ul> to seperate the categories
					if ($i == number_format(count($fetch_pages)/2, 0)) :
?>
                        </ul>     
                        <ul class="section_option clearfix">
<?php
					endif;
?>
	                	<li><input type="checkbox" <?php echo $main_checked; ?> name="<?php echo $use_id ?>" id="<?php echo $use_id; ?>" /> <?php echo $this_page->post_title; ?></li>                                
<?php
					$i++;	
				endforeach;
?>
			</ul>
		</div>
        <?php fetch_options($category_menu_options); ?>
        <div class="selection_sections clearfix">
            <ul class="section_option">
<?php
			//Set the $i var to count through as we loop through the categories
			$i = 0;
			$parent_count = 0;
			$cat_args = array("orderby" => get_option("ocmx_category_order"), "order" => get_option("ocmx_category_updown"), "hide_empty" => false);
			$parent_categories = get_categories($cat_args);
			// Count the Parent Categories (That is Categories without Parents themselves (To be used in the loop, explained below)
			foreach ($parent_categories as $this_category) :
				if($this_category->category_parent == 0) :
					$parent_count++;
				endif;
			endforeach;
			//Loop through the categories				
			foreach ($parent_categories as $this_category) :
				//Create the checkbox Name/Id
				$use_id = $input_prefix."maincategory_".$this_category->cat_ID;
				
				// Check/Uncheck Categories
				if(get_option($use_id)) : $main_checked = "checked='checked'"; else : $main_checked = ""; endif;
				
				// If we're halfway then place create a new <ul> to seperate the categories
				if ($i == number_format($parent_count/2, 0)) :
?>
					</ul>     
					<ul class="section_option clearfix">
<?php
				endif;
				// Now List the Subcategories
				$sub_category_defaults = array('type' => 'post', 'child_of' => $this_category->cat_ID, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false);
				$sub_categories = get_categories($sub_category_defaults);
	
				if($this_category->category_parent == 0) :
?>
	
					<li class="clearfix">
						<input type="checkbox" <?php echo $main_checked; ?> name="<?php echo $use_id; ?>" id="main_category_<?php echo $this_category->cat_ID; ?>" /> <?php echo $this_category->cat_name; ?>
						<ul class="section_option clearfix" id="sub_categories_<?php echo $this_category->cat_ID; ?>" style="display: none;">
<?php
						foreach ($sub_categories as $sub_category) :
							//Create the checkbox name
							$sub_category->cat_name;
							$use_id = $input_prefix."subcategory_".$sub_category->cat_ID;
							
							// Check/Uncheck Categories
							if(get_option($use_id)) : $sub_checked = "checked='checked'"; else : $sub_checked = ""; endif;
?>
							<li><input type="checkbox" <?php echo $sub_checked; ?> name="<?php echo $use_id ?>" id="<?php echo $use_id; ?>" /> <?php echo $sub_category->cat_name; ?></li>
<?php
						endforeach;
?>                
						</ul>
					</li>
<?php 
					$i++;	
				endif;
			endforeach;	
?>
            </ul>
            <br class="clearboth "/>
        </div>
<?php 
	}	
function small_ad_form($id) {
		global $input_prefix;
		$ad_title_id = $input_prefix."small_ad_title_".$id;
		$ad_link_id = $input_prefix."small_ad_link_".$id;
		$ad_img_id = $input_prefix."small_ad_img_".$id;
		$ad_href_id = $input_prefix."small_ad_href_".$id;
?>
		<div class="form_label">
			<div class="label_tag">
				<div class="input_label">Advert Title</div>
				<div class="input_form"><input type="text" name="<?php echo $ad_title_id ?>" id="<?php echo $ad_title_id ?>" value="<?php echo get_option($ad_title_id); ?>" /></div>
			</div>
		</div>
		<div class="form_label">
			<div class="label_tag">
				<div class="input_label">Advert Link</div>
				<div class="input_form"><input type="text" name="<?php echo $ad_link_id ?>" id="<?php echo $ad_link_id ?>" value="<?php echo get_option($ad_link_id); ?>" /></div>
			</div>
		</div>
		<div class="form_label">
			<div class="label_tag">
				<div class="input_label">Image URL</div>
				<div class="input_form"><input type="text" name="<?php echo $ad_img_id ?>" id="<?php echo $ad_img_id ?>" value="<?php echo get_option($ad_img_id); ?>" /></div>
			</div>
		</div>
		<div class="form_label">
			<div class="label_tag">
				<div class="input_label">&nbsp;</div>
				<div class="thumbnail"><a id="<?php echo $ad_href_id; ?>" style="background: url('<?php echo get_option($ad_img_id); ?>')  no-repeat center;" href="<?php echo get_option($ad_link_id); ?>" class="std_link" rel="lightbox" target="_blank">&nbsp;</a></div>
			</div>
		</div>

<?php
	}
?>