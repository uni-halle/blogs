<?php
/*---------------------------------------------------------------------------
Be free to change what you want... @ your own risk :-)
Would you like to use your own template?
1.) Copy this code in a other file and save it with the .tpl prefix (e.g. myfile.tpl)
3.) Customize your template and save it
2.) Select your template on DMSGuestbook admin page for use

Guestbook input form

CSS variables:
css_form_text						= define property of titel (name, email, url, message)
css_form_textfieldspace				= define space between each text fields
css_form_errormessage				= wrong input text error message
css_form_successmessage				= success input text message
css_form_namefield					= name field
css_form_emailfield					= email field
css_form_urlfield					= url field
css_form_additional_option			= additional selectbox
css_form_messagefield				= message field
css_form_antispamtext				= antispam text
css_form_antispamcontent				= antispam image or mathematic figures
css_form_antispamcontent_position	= antispam image or mathematic figures position
css_form_antispam_inputfield			= antispam input field
css_form_submit_position				= submit button position
Edit these CSS settings on DMSGuestbook admin panel (CSS section)

Function variables:
$error1							= name error
$error2							= email error
$error3							= url error
$error4							= message error
$error5							= antispam error
$error6							= gravatar error
$success						= success message
$gbname							= name
$gbemail						= email
$gburl							= url
$gbmsg							= message
$gbadditional_selectbox			= additional selectbox (user defined)
$captcha1						= visual captcha
$captcha2						= mathematical captcha
$var_mandatory_char				= mandatory char
$var_mandatory_email			= display $var_mandatory_char if it set in the admin page
$var_mandatory_url				= display $var_mandatory_char if it set in the admin page
$var_additional_option_title	= display additional selectbox title
$countchars[0]					= javasript gbmsgLen() function for textarea (allowed message text length)
$countchars[1]					= line break if chars count left is displayed
$countchars[2]					= number of chars left
$countchars[3]					= field where number of chars is displayed
Edit these variables on DMSGuestbook admin panel

Language text variables:
$lang_name						= name title
$lang_email						= email title
$lang_message					= message title
$lang_require					= require title
$lang_antispam					= antispam description text
$lang_submit					= submit button title
Edit these variables on DMSGuestbook admin panel
---------------------------------------------------------------------------*/

		$var_form1 = "
				<!-- define the space on top of the form -->
				<div class='css_form_textfieldspace'></div>

				<!-- error & succes messages -->
				<div class='css_form_errormessage'>$error1</div>
				<div class='css_form_errormessage'>$error2</div>
				<div class='css_form_errormessage'>$error3</div>
				<div class='css_form_errormessage'>$error4</div>
				<div class='css_form_errormessage'>$error5</div>
				<div class='css_form_errormessage'>$error6</div>
				<div class='css_form_successmessage'>$success</div>
				<br />
				";


		$var_form2 = "
				<!-- name field -->
				<div class='css_form_textfieldspace'>
				<input class='css_form_namefield' type='text' name='gbname' value='$gbname' maxlength='50' />
				<b class='css_form_text'>&nbsp;$lang_name $var_mandatory_char</b></div>

				<!-- email field -->
				<div class='css_form_textfieldspace'>
				<input class='css_form_emailfield' type='text' name='gbemail' value='$gbemail' />
				<b class='css_form_text'>&nbsp;$lang_email $var_mandatory_email</b></div>
				";


		$var_form3 = "
				<!-- gravatar email field -->
				<div class='css_form_textfieldspace'>
				<input class='css_form_emailfield' type='text' name='gbgravataremail' value='$gbgravataremail' />
				<b class='css_form_text'>&nbsp;Gravatar $lang_email <a href='http://en.gravatar.com/' target='_blank'>[?]</a></b></div>
				";


		$var_form4 = "
				<!-- url field -->
				<div class='css_form_textfieldspace'>
				<input class='css_form_urlfield' type='text' name='gburl' value='$gburl' />
				<b class='css_form_text'>&nbsp;$lang_url $var_mandatory_url</b></div>
				";

		$var_form5 = "
				<!-- additional selectbox  if selected -->
				<div class='css_form_textfieldspace'>
				<select class='css_form_additional_option' name='gbadditional'>
				$gbadditional_selectbox
				</select>
				<b class='css_form_text'>&nbsp;$var_additional_option_title</b></div>
				";

		$var_form6 = "
				<!-- message field -->
				<div class='css_form_textfieldspace'>
				$countchars[3]
				$countchars[1]
				<textarea class='css_form_messagefield' name='gbmsg' rows='0' cols='0' $countchars[0]>$gbmsg</textarea>
				<b class='css_form_text'>&nbsp;$lang_message $var_mandatory_char</b></div>

				<!-- require text and the requirement char -->
				<div style='text-align:left;'><b class='css_form_text'>$var_mandatory_char $lang_require</b></div>
				<br />
				";


		$var_form7 = "
				<!-- visual captcha if selected -->
				<div class='css_form_antispamtext'>$lang_antispam</div>
				<div class='css_form_antispamcontent_position'>
				<img class='css_form_antispamcontent' src='$captcha1' alt='captcha' /></div>
				<div class='css_form_antispamcontent_position'>
				<input class='css_form_antispam_inputfield' type='text' name='securecode' /></div>
				";


		$var_form8 = "
				<!-- mathematical chaptcha if selected -->
				<div class='css_form_antispamtext'>$lang_antispam</div>
				$captcha2 <input class='css_form_antispam_inputfield' type='text' name='securecode' />
				";

		$var_form8_1 = "
				<!-- reCHAPTCHA if selected -->
				<div class='css_form_antispamcontent_position'>$recaptcha</div>
				";

		$var_form9 = "
				<!-- submit button -->
				<div class='css_form_submit_position'><input class='css_form_submit' type='submit' value='$lang_submit' /></div>
				";


		$var_form10 = "
				<!-- define space after the submit button -->
				<p style='padding:10px 0px 0px 0px;'></p>
				";


		$var_form11 = "
				<!-- define space between form and navigation -->
				<div style='padding:30px 0px 0px 0px;'></div>
				";



$DMSGuestbookContent .= "<!-- Message text chars left counter -->
<script type=\"text/javascript\">
<!--
function gbmsgLen()
{
maxLen=\"$countchars[2]\";
var text=document.forms[0].gbmsg.value;
if(text.length>maxLen)
    {
      document.forms[0].gbmsg.value = text.substring(0,maxLen);
      document.forms[0].counter.value = \"0\";
    }
else
    {
    var length = maxLen-text.length;
    document.forms[0].counter.value = length;
    }
}
-->
</script>";

?>



