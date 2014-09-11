/*
Theme Name: Arcade
Theme URI: http://www.obox-design.com/
Description: The first of six limited edition themes from the Obox Signature Series collection.
Version: 1.0
Author: Marc and David Perel
Author URI: http://www.obox-design.com/
*/
function check_nan(element, element_value, max_value)
	{
		var len = element_value.length;
		if(isNaN(element_value))
			{
				alert("Only number vlues are allow in this input.");
				element.value = element_value.substring(0, (len/1)-1);
			}
			
		if(max_value && ((element_value/1) > (max_value/1)))
			{
				alert("The maximum value allowed for this input is "+max_value);
				element.value = max_value;
			}
	}
function check_linked($this_id, $link_id)
	{
		$this_id = "#"+$this_id;
		$link_id = "#"+$link_id;
		if($($this_id).attr("value") !== "0")
			{
				$($link_id).attr("disabled", "true");
			}
		else
			{$($link_id).removeAttr("disabled");}
		
	}
$(document).ready(function()
	{
		/* Tab Swapping */
		$(".ocmx-tabs > ul > li > a").click(function()
			{
				/* Create the id to reference the content*/
				$id = this.id.toString().substr(9, 1);
				$tab_id = "#tab-" + $id;
				$top_submit_id = "#submit-top-" + $id;
				$bottom_submit_id = "#submit-bottom-" + $id;
				/* Set the form action so we know which tab to refresh onto */
				$current_action = $("#ocmx_form").attr("action")
				//Check if there's a tab already set and if so change it, otherwise, add it...
				$tab_start = $("#ocmx_form").attr("action").toString().indexOf("tab=");				
				if($tab_start !== -1)
					{$new_action = $current_action.substr(0, $tab_start)+"tab="+$id;}
				else
					{$new_action = $current_action+"&current_tab="+$id;}
				//Apply the new action to the form...
				$("#ocmx_form").attr("action", $new_action);
				// If we're clicking a new tab, fade it in.
				if($(".selected > a").attr("id") !== this.id)
					{
						//Fade the Old Form Out
				   		$(".selected_tab").slideUp("3000");
						//Fade the New Form out, Change it's Class, and Fade it in again
						$($tab_id).fadeOut("fast").attr("class", "selected_tab").slideDown("3000");
						if($($top_submit_id).html())
							{
								$(".submit").fadeOut("3000");
								$($top_submit_id).fadeOut("3000").attr("class", "submit").fadeIn("3000");
								$($bottom_submit_id).fadeOut("3000").attr("class", "submit").fadeIn("3000");
							}
						//Clear the Class of the Selected Tab
						$(".selected").attr("class", "")
						//Clear the Class of the New Tab						
						$(this).parent().attr("class", "selected")
					}
			});
		
		//Show all the Menu Items we've selected to display		
		$("input[id^='main_category_']:checked").parent().children("ul").fadeIn("fast");
		//Set the main category checkboxes selector		
		$category_input  = ".selection_sections > ul > li > input";		
		$("input[id^='main_category_']").click(function()
			{
				$id = $(this).attr("id").replace("main_category_", "");
				$sub_menu_id= "#sub_categories_"+$id;
				if(this.checked == true)
					{$($sub_menu_id).slideDown("fast");}
				else
					{$($sub_menu_id).slideUp("fast");}
			});
		
		
		// Disable 3rd party ads Script Input when entering the page (if Required)...
		if($("#ocmx_header_buysell_ads option:selected").attr("value") == "off"){$("#ocmx_header_buysell_id").attr("disabled", "true");}
		$("#ocmx_header_buysell_ads").change(function()
			{
				// Enable/Disable BuySell Ad Script
				if($(this).attr("value") == "on")
					{$("#ocmx_header_buysell_id").removeAttr("disabled");}
				else
					{$("#ocmx_header_buysell_id").attr("disabled", "true");}				
			});
		// Disable 3rd party ads Script Input when entering the page (if Required)...
		if($("#ocmx_main_buysell_ads option:selected").attr("value") == "off"){$("#ocmx_main_buysell_id").attr("disabled", "true");}
		$("#ocmx_main_buysell_ads").change(function()
			{
				// Enable/Disable BuySell Ad Script
				if($(this).attr("value") == "on")
					{$("#ocmx_main_buysell_id").removeAttr("disabled");}
				else
					{$("#ocmx_main_buysell_id").attr("disabled", "true");}				
			});
		// Disable 3rd party ads Script Input when entering the page (if Required)...
		if($("#ocmx_small_buysell_ads option:selected").attr("value") == "off"){$("#ocmx_small_buysell_id").attr("disabled", "true");}
		$("#ocmx_small_buysell_ads").change(function()
			{
				// Enable/Disable BuySell Ad Script
				if($(this).attr("value") == "on")
					{$("#ocmx_small_buysell_id").removeAttr("disabled");}
				else
					{$("#ocmx_small_buysell_id").attr("disabled", "true");}				
			});
		//AS we change the amount of small ads we're using, reload the #small-ads-div div
		$("#ocmx_small_ads").change(function()
			{
				$ad_number = $(this).attr("value");
				$("#small-ads-div").attr("class", "loading");
				
				$("#small-ads-div").fadeOut("slow", function(){$("#small-ads-div").load($("#template-directory").html()+"/ocmx/ads_refresh.php?small_ads="+$ad_number, function(){$("#small-ads-div").fadeIn("fast");});}).attr("class", "");				
				$("#ocmx_small_buysell_ads option").each(function () {
					//Since we're setting our own ads, disable Buy and Sell
					if($ad_number !== "0" && $(this).attr("value") == "0")
						{$(this).attr("selected", "true");}
					});
			});	

		$("input[id^='ocmx_small_ad_link_']").live("keyup", function()
			{
				//Set the Id of this Textarea
				$id = "#"+$(this).attr("id");
				//Set the length so we can find the ID integer
				$ad_id = $(this).attr("id").replace("ocmx_small_ad_link_", "");
				//Set the href Id
				$href_id = "#ocmx_small_ad_href_"+$ad_id;
				//Change the href value				
				$($href_id).attr("href", $($id).attr("value"));
				
			});
		$("input[id^='ocmx_small_ad_img_']").live("keyup", function()
			{
				//Set the Id of this Textarea
				$id = "#"+$(this).attr("id");
				//Set the length so we can find the ID integer
				$ad_id = $(this).attr("id").replace("ocmx_small_ad_img_", "");
				//Set the href Id
				$href_id = "#ocmx_small_ad_href_"+$ad_id;
				//Show a loading Bar, Change the href background
				$($href_id).removeAttr("style");
				$($href_id).attr("class", "loading");
				$($href_id).attr("style", "background: url('"+$($id).attr("value")+"') no-repeat center;");
				$($href_id).attr("class", "std_link");
				
			});
		$("#ocmx_comment_form").submit(function()
			{
				$send_form = confirm("Are you sure you would like to save these changes, which may include removing selected comments?");
				if(!$send_form)
					{return false;}
				else
					{return true;}
			});
		/*********************/
		/* COMMENT FUNCTIONS */
		$("[id^=view-comment-]").click(function(){												
			$id = $(this).attr("id").replace("view-comment-", "");
			$content_id = "#ocmx-comment-"+$id;
			if($($content_id).attr("class") == "no_display")
				{$($content_id).fadeIn("slow").removeClass("no_display");}
			else
				{$($content_id).fadeOut("slow").addClass("no_display");}
			return false;
		});
		
		
		/*********************/
		/* GALLERY FUNCTIONS */
		
		$("#width_1, #height_1, #percentage_1").keyup(function()
			{
				if($(this).attr("id") == "percentage_1")
					{
						check_nan(this, $(this).attr("value"), '100');
						if($(this).attr("value") == "")
							{$("#width_1, #height_1").removeAttr("disabled");}
						else
							{$("#width_1, #height_1").attr("disabled", "true");}
					}
				else
					{check_nan(this, $(this).attr("value"));}
				// switch_dimensions('1', this.value)
			});
		
		$("#width_2, #height_2, #percentage_2").keyup(function()
			{
				if($(this).attr("id") == "percentage_2")
					{
						check_nan(this, $(this).attr("value"), '100');
						if($(this).attr("value") == "")
							{$("#width_2, #height_2").removeAttr("disabled");}
						else
							{$("#width_2, #height_2").attr("disabled", "true");}
					}
				else
					{check_nan(this, $(this).attr("value"));}
				// switch_dimensions('1', this.value)
			});
		
		$("input[id^='ocmx-gallery-add']").click(function()
			{
				$url = $("#add_url").attr("value");
				window.location = ($url);				
			});

		$("input[id^='ocmx-gallery-save']").click(function()
			{
				$save_element = "<input type=\"hidden\" name=\"ocmx_gallery_save\" value=\"1\" />";
				$($save_element).appendTo("#ocmx_form");
				$("#ocmx_form").submit();
			});
		
		$("input[id^='ocmx-gallery-delete']").click(function()
			{
				$delete_gallery = confirm("Are you sure you want to delete the selected galleries?");
				if(!$delete_gallery)
					{}
				else
					{
						$delete_element = "<input type=\"hidden\" name=\"ocmx_gallery_delete\" value=\"1\" />";
						$($delete_element).appendTo("#ocmx_form");
						$("#ocmx_form").submit();
					}
			});
		/* Set Permalink */
		$("#ocmx_form").submit(function(){
			$("#linkTitle").removeAttr("disabled");
		})
		$("#item").blur(function(){
			$check_value = $("#item").attr("value");
			$use_value = "";
			$validchar = "1234567980abcdefghijklmnopqrstuvwxyz- ";
			$i_max = $("#item").attr("value").length;
			for($i = 0; $i < $i_max; $i++)
				{
					$this_char = $check_value.toLowerCase().charAt($i)
					if($validchar.indexOf($this_char) !== -1)
						{$use_value = $use_value + $this_char;}
				}
			$use_value = $use_value.replace(/ /g, "-");
			$("#linkTitle").attr("value", $use_value);
		});
	});