/*
Name: PRESS
Design Studio: Obox Design http://www.obox-design.com/
Version: 1.0
Author: Obox Design
Author URI: http://www.obox-design.com/
*/

/* clearTimeOut Keeps the Menu Open */
$clearTimeOut = 0;
$.submenu_open = 0;
// Keep the Menu Open if we're highlighting a sub-menu item
function keep_open()
	{$clearTimeOut = 0;}
// Begin the closing procedure with a countdown
function close_menu($id)
	{
		$clearTimeOut = 1;
		$temp_timeout = setTimeout("close_menu_final("+$id+");", 300);					
	}			
// Do the final menu clearing
function close_menu_final($id)
	{
		// Check whether or not we've scrolled over a menu item
		if($clearTimeOut == 1)
			{
				$use_id = "#sub-menu-"+$id;
				$($use_id).slideUp("10000");
				$.submenu_open = 0;
			}
	}
function switch_slides($current_id, $next_id)
	{	
		$old_post_id = $current_id.replace("image-", "post-");
		$new_post_id = $next_id.replace("image-", "post-");
		
		$($current_id).fadeOut("fast");
		$($next_id).addClass("floatleft");
		$($old_post_id).slideUp("slow");
		
		setTimeout(
			function()
				{
					$($next_id).fadeIn("slow");
					$($new_post_id).slideDown("slow").addClass("feature-post-content").addClass("clearfix");
					$.busy = 0;
				}
		,100);
	}

$(document).ready(function()
	{
		/***********************************************/
		/* All functions for the featured posts Widget */
		$.current_selected = $("#first_selected").html();
		$("[id^='ocmx-featured-href-']").click(function()
			{
				$use_id = $(this).attr("id").replace("ocmx-featured-href-", "");
				
				$old_header = "#feature-post-header-"+$.current_selected;
				$old_media = "#feature-post-media-"+$.current_selected;
				$header_id = "#feature-post-header-"+$use_id;
				$media_id = "#feature-post-media-"+$use_id;
				
				$("#feature-media-container").slideUp("slow");
				$($media_id+" > object").addClass("no_display");
				
				/* Clear old header */
				$($old_header).addClass("no_display");
				$($header_id).removeClass("no_display");
				
				/* Hide old Media*/
				$($old_media).slideUp("slow");
				$($old_media+" object").addClass("no_display");
				
				setTimeout(function()
					{
						$("#feature-media-container").slideDown("fast");				
						setTimeout(function()
							{	
								$($media_id).slideDown("slow");		
								$($media_id+" > object").removeClass("no_display");
							}
						,1000);
					}
				,150);
				$.current_selected = $use_id;
				return false;
			});
		$.current_month = 1;
		$("a[id^='archive-href-']").click(function()
			{
				$use_id = $(this).attr("id").replace("archive-href-", "");
				
				$old_detail = "#archive-detail-"+$.current_month;
				$new_detail = "#archive-detail-"+$use_id;
				
				/* Hide old Media*/
				$($old_detail).slideUp("slow");
				$($new_detail).slideDown("slow");
				
				$.current_month = $use_id;
				return false;
			});
		
		/*************/
		/* Main Menu */
		$.open_menu = 0;
		$("a[id^='main-menu-item-']").mouseover(function(){
			// Start the timeout to keep the menu open
			keep_open()
			// Create the id to ref the submenu
			$sub_menu_id = $(this).attr("id").replace("main-menu-item-", "");
			$id = "sub-menu-"+$sub_menu_id;
			$new_sub_menu = "#"+$id;
			if($.open_menu !== $new_sub_menu)
				{$(".sub-menu-container").slideUp("fast")}
		
			// fade in the submenu
			$($new_sub_menu).addClass("sub-menu-container").slideDown("2000");	
			$.open_menu = $new_sub_menu;
		});
		$("[id^='sub-menu-']").mouseover(function(){
			// Start the timeout to keep the menu open
			keep_open()														
		});
		$("[id^='sub-menu-']").mouseout(function(){
			// Create the id to ref the submenu
			$sub_menu_id = $(this).attr("id").replace("sub-menu-", "");
			// Start the cloding process
			close_menu($sub_menu_id);				
		});
		$("a[id^='main-menu-item-']").mouseout(function(){
			// Create the id to ref the submenu
			$sub_menu_id = $(this).attr("id").replace("main-menu-item-", "");
			// Start the cloding process
			close_menu($sub_menu_id);								
		});
			
		/********************/
		/* Ajax Comments */
		$("#commentform").submit(function(){return false;});
		
		$("#comments-link").click(function(){			
			$("html").animate({scrollTop: $("#comments").offset().top}, 1000);
			return false;
		});
		$("#comment_submit").live("click", function(){
			// Compile the request location
			$post_page = $("#template-directory").html()+"/functions/ocmx_comment_post.php";
			// Compile all the request details
			$author = $("#author").attr("value");
			$email = $("#email").attr("value");
			$url = $("#url").attr("value");
			$comment = $("#comment").attr("value");
			$twitter = $("#twitter").attr("value");
			$email_subscribe = $("#email_subscribe").attr("checked");
			$post_id = $("#comment_post_id").attr("value");
			$comment_parent_id = $("#comment_parent_id").attr("value");
	
			// Set which area the new comment will end up in
			if($comment_parent_id !== "0" && $comment_parent_id !== "")
				{$new_comments_id = "#new-reply-"+$comment_parent_id;}
			else
				{$new_comments_id = "#new_comments";}
			
			// Fade out the new comment div so that we can fade it in after posting our new comment
			//$($new_comments_id).fadeOut("fast");
			$("#commment-post-alert").fadeIn("slow");
			// Perform the "Magic" which is just a bit of Ajax
			$.post($post_page, { author: $author, email: $email, url: $url, twitter: $twitter, email_subscribe: $email_subscribe, comment: $comment, comment_post_id: $post_id, comment_parent: $comment_parent_id}, 
				function(data) {
					if($.browser.msie)
						{location.reload();}
					else
						{$($new_comments_id).html($($new_comments_id).html()+" "+data).fadeIn("slow");}
					$("#commment-post-alert").fadeOut("fast");
					$("#comment").attr("value", "");
			});
			return false;
		});
		
		$("a[id^='reply-']").live("click", function(){
			// Create the Comment Id and apply it to the comment form
			$comment_id = $(this).attr("id").replace("reply-", "");
			
			// Set which href we're dealing with

			if($.href_id)
				{
					$oldhref = $.href_id;
					$($oldhref).html("Reply");
				}
			$.href_id = "#reply-"+$comment_id;
			
			//Set where exactly the comment form will end up
			$new_location_id = "#form-placement-"+$comment_id;
			
			//Create the Id for the new placement of the comment Form and put it there
			if($($new_location_id).html().toString().indexOf("Leave") == -1)
				{
					$("#comment_form_container").remove().appendTo($new_location_id);
					$($new_location_id).fadeIn("slow");
					$("#comment_parent_id").attr("value", $comment_id);
					// Change href to Cancel
					$($.href_id).html("Cancel");
				}
			else
				{
					$($new_location_id).fadeOut("fast");
					$("#comment_form_container").remove().appendTo("#original_comment_location");
					$("#comment_parent_id").attr("value", "0");
					// Change href back to Reply
					$($.href_id).html("Reply");
				}
			setTimeout(function(){$("html").animate({scrollTop: $(".comment-form-content").offset().top}, 1000);}, 500);
			return false;
		});
		$("#contact_form").submit(function(){
			$err = "";	
			var theForm = document.getElementById("contact_form");
			var e_value = $("#contact_email").attr("value");
			
			if ($("#contact_name").attr("value") == "" || $("#contact_name").attr("value") == "Name")
				{$err = $err + "\n - Enter your name.";}
			if(e_value !== "Email Address" && e_value !== "" && e_value.indexOf("@") !== -1 && e_value.indexOf("@.") == -1 && e_value.indexOf("@@") == -1 && ( e_value.indexOf(",") == -1  && e_value.indexOf("/") == -1 && e_value.indexOf("'") == -1 && e_value.indexOf("&") == -1 && e_value.indexOf("%") == -1 ))
				{}
			else
				{$err = $err + "\n - Enter a valid e-mail address.";}		
			if ($("#contact_subject").attr("value") == "" || $("#contact_subject").attr("value") == "Subject")
				{$err = $err + "\n - Enter a subject title for your message.";}
			if ($("#contact_message").attr("value") == "" || $("#contact_message").attr("value") == "Your Message")
				{$err = $err + "\n - Enter a message.";}
	
			if($err !== "")
				{
					$err = "Please correct the following: \n" + $err;
					alert($err);
					return false
				}			
			else
				{return true;}		  	
		});
		/**********************/
		/* Search Form Clearer */
		$search_criteria_id = "search_criteria";
		$("#"+$search_criteria_id).focus(function(){
			if($("#"+$search_criteria_id).attr("value") == "Search...")
				{$("#"+$search_criteria_id).attr("value", "");}
		});
		
		$("#"+$search_criteria_id).blur(function(){
			if($("#"+$search_criteria_id).attr("value") == "")
				{$("#"+$search_criteria_id).attr("value", "Search...");}
		});
		
		/************************/
		/* Contact Form Clearer */
		$contact_name_id = "contact_name";
		$("#"+$contact_name_id).focus(function(){
			if($("#"+$contact_name_id).attr("value") == "Name")
				{$("#"+$contact_name_id).attr("value", "");}
		});
		
		$("#"+$contact_name_id).blur(function(){
			if($("#"+$contact_name_id).attr("value") == "")
				{$("#"+$contact_name_id).attr("value", "Name");}
		});
					
		$contact_email_id = "contact_email";
		$("#"+$contact_email_id).focus(function(){
			if($("#"+$contact_email_id).attr("value") == "Email Address")
				{$("#"+$contact_email_id).attr("value", "");}
		});
		
		$("#"+$contact_email_id).blur(function(){
			if($("#"+$contact_email_id).attr("value") == "")
				{$("#"+$contact_email_id).attr("value", "Email Address");}
		});
			
		$contact_subject_id = "contact_subject";
		$("#"+$contact_subject_id).focus(function(){
			if($("#"+$contact_subject_id).attr("value") == "Subject")
				{$("#"+$contact_subject_id).attr("value", "");}
		});
		
		$("#"+$contact_subject_id).blur(function(){
			if($("#"+$contact_subject_id).attr("value") == "")
				{$("#"+$contact_subject_id).attr("value", "Subject");}
		});
		$contact_message_id = "contact_message";
		$("#"+$contact_message_id).focus(function(){
			if($("#"+$contact_message_id).attr("value") == "Your Message")
				{$("#"+$contact_message_id).attr("value", "");}
		});
		
		$("#"+$contact_message_id).blur(function(){
			if($("#"+$contact_message_id).attr("value") == "")
				{$("#"+$contact_message_id).attr("value", "Your Message");}
		});
		
		/*************************/
		/* Comments Form Clearer */
		$search_id = "s";	
		$("#"+$search_id).focus(function(){
			if($("#"+$search_id).attr("value") == "Search...")
				{$("#"+$search_id).attr("value", "");}
		});
		
		$("#"+$search_id).blur(function(){
			if($("#"+$search_id).attr("value") == "")
				{$("#"+$search_id).attr("value", "Search...");}
		});
		
		/*************************/
		/* Comments Form Clearer */
		$author_id = "author";	
		$("#"+$author_id).focus(function(){
			if($("#"+$author_id).attr("value") == "Name")
				{$("#"+$author_id).attr("value", "");}
		});
		
		$("#"+$author_id).blur(function(){
			if($("#"+$author_id).attr("value") == "")
				{$("#"+$author_id).attr("value", "Name");}
		});
		
		$email_id = "email";	
		$("#"+$email_id).focus(function(){
			if($("#"+$email_id).attr("value") == "EMail Address")
				{$("#"+$email_id).attr("value", "");}
		});
		
		$("#"+$email_id).blur(function(){
			if($("#"+$email_id).attr("value") == "")
				{$("#"+$email_id).attr("value", "EMail Address");}
		});
		
		$url_id = "url";		
		$("#"+$url_id).focus(function(){
			if($("#"+$url_id).attr("value") == "Website URL")
				{$("#"+$url_id).attr("value", "");}
		});
		$("#"+$url_id).blur(function(){
			if($("#"+$url_id).attr("value") == "")
				{$("#"+$url_id).attr("value", "Website URL");}
		});
		
		$twitter_id = "twitter";		
		$("#"+$twitter_id).focus(function(){
			if($("#"+$twitter_id).attr("value") == "Twitter Name")
				{$("#"+$twitter_id).attr("value", "");}
		});
		$("#"+$twitter_id).live("blur", function(){
			if($("#"+$twitter_id).attr("value") == "")
				{$("#"+$twitter_id).attr("value", "Twitter Name");}
		});
	});