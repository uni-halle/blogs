<?php
/*---------------------------------------------------------------------------
Be free to change what you want... @ your own risk :-)
Would you like to use your own template?
1.) Copy this code in a other file and save it with the .tpl prefix (e.g. myfile.tpl)
2.) Select your template on DMSGuestbook admin page for use

Guestbook entries

CSS variables:
css_post_header1 				= id, name
css_post_header2				= url
css_post_header3				= email
css_post_header4				= date, ip
css_post_separator				= separator
css_post_message				= message text
Edit these CSS settings on DMSGuestbook admin panel (CSS section)

Function variables:
$show_id						= id
$message_name					= name
$show_url						= url
$show_email						= email
$gravatar_url					= gravatar url (hash)
$show_ip						= ip
$displaydate 					= date and time
$slash							= separator if ip is visible
$message_text					= guestbook message text
$additional_text				= user defined additional text
Edit these variables on DMSGuestbook admin panel
---------------------------------------------------------------------------*/

	$GuestbookEntries1 = "
		<div>
			<table style='margin:0px 0px; padding:0px 0px; border:1px; width:100%;' cellspacing='0' cellpadding='0' border='0'>
				<tr>";
	$GuestbookEntries2 = "
					<!-- gravatar -->
					<td style='width:40px;'><img style='padding:0px 5px 3px 0px;' src='$gravatar_url' alt='Gravatar' /></td>";
	$GuestbookEntries3 = "
					<!-- id, name, url & email -->
					<td class='css_post_header1'>($show_id) $message_name<br />
					<!-- date & ip -->
					<div class='css_post_header4'>$displaydate $slash $show_ip</div></td>
					<td style='width:1px;'></td>
					<!-- url & email -->
					<td class='css_post_header2'>$show_url</td>
					<td class='css_post_header3'>$show_email</td>
				</tr>
			</table>
			<!-- separator -->
			<hr class='css_post_separator' />
		</div>
		<!-- message -->

		<!-- $additional_text can be place where ever you want. Using html and css tags to format the appearance of this. -->
		<div class='css_post_message'>$message_text<br /><br /><i>$additional_text</i></div>
		";

	$GuestbookEntries4 = "
		<!-- space between each guestbook post -->
		<table style='margin:0px 0px 20px 0px; padding:0px 0px; border:1px; width:100%;' cellspacing='0' cellpadding='0' border='0'>
			<tr>
			<td></td>
			</tr>
		</table>
		";
?>
