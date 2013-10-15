<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
#################################################################
/*
Author: Daniel Schurter
Email: DMSGuestbook@danielschurter.net
Url: http://DanielSchurter.net

DMSGuestbook is released under the GNU General Public License
http://www.gnu.org/licenses/gpl.html
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.
*/
#################################################################
/* collect some variables */
$var_step 				= $options["step"];
$var_multi_gb_id		= $multi_gb_id;
$var_supergb			= $options["supergb"];
$var_page_id 			= $page_id;
$var_messagetext_length	= $options["messagetext_length"];
$var_forwardchar			= html_entity_decode($options["forwardchar"], ENT_QUOTES);
$var_backwardchar		= html_entity_decode($options["backwardchar"], ENT_QUOTES);
$var_require_email		= $options["require_email"];
$var_require_url			= $options["require_url"];
$var_require_antispam	= $options["require_antispam"];
$var_show_ip				= $options["show_ip"];
$var_show_email			= $options["show_email"];
$var_show_url			= $options["show_url"];
$var_captcha_color 		= $options["captcha_color"];
$var_dateformat			= $options["dateformat"];
$var_setlocale			= $options["setlocale"];
$var_offset				= $options["offset"];
$var_formpos				= $options["formpos"];
$var_formposlink			= html_entity_decode($options["formposlink"], ENT_QUOTES);
$var_send_mail			= $options["send_mail"];
$var_mail_adress			= $options["mail_adress"];
$var_mail_method		= $options["mail_method"];
$var_smtp_host			= $options["smtp_host"];
$var_smtp_port			= $options["smtp_port"];
$var_smtp_username		= $options["smtp_username"];
$var_smtp_password		= $options["smtp_password"];
$var_smtp_auth			= $options["smtp_auth"];
$var_smtp_ssl			= $options["smtp_ssl"];
$var_sortitem			= $options["sortitem"];
$var_dbid				= $options["dbid"];
$var_language			= $multi_gb_language;
$var_email_image_path	= $options["email_image_path"];
$var_website_image_path	= $options["website_image_path"];
$var_admin_review		= $options["admin_review"];
$var_url_overruled		= $options["url_overruled"];
$var_gravatar			= $options["gravatar"];
$var_gravatar_rating	= $options["gravatar_rating"];
$var_gravatar_size		= $options["gravatar_size"];
$var_mandatory_char		= html_entity_decode($options["mandatory_char"], ENT_QUOTES);
$var_form_template		= $options["form_template"];
$var_post_template		= $options["post_template"];
$var_antispam_key		= $options["antispam_key"];
$var_akismet			= $options["akismet"];
$var_akismet_action		= $options["akismet_action"];
$var_nofollow			= $options["nofollow"];
$var_additional_option	= $options["additional_option"];
$var_additional_option_title	= $options["additional_option_title"];
$var_show_additional_option		= $options["show_additional_option"];
$var_recaptcha_publickey		= $options["recaptcha_publickey"];
$var_recaptcha_privatekey		= $options["recaptcha_privatekey"];

/* base64 */
if(BASE64 == 1){
$var_formposlink				= base64_decode(html_entity_decode($options["formposlink"], ENT_QUOTES));
$var_additional_option_title	= base64_decode($options["additional_option_title"]);
$var_mandatory_char				= base64_decode(html_entity_decode($options["mandatory_char"], ENT_QUOTES));
$var_forwardchar				= base64_decode(html_entity_decode($options["forwardchar"], ENT_QUOTES));
$var_backwardchar				= base64_decode(html_entity_decode($options["backwardchar"], ENT_QUOTES));
}

// global var
global $wpdb;
global $wpsmiliestrans, $wp_smiliessearch, $wp_smiliesreplace;
$table_option = $wpdb->prefix . "options";
$table_name = $wpdb->prefix . "dmsguestbook";
//collect datas for wp the_content

$DMSGuestbookContent = "";
$var_fontcolor1 = (isset($var_fontcolor1)) ? $var_fontcolor1 : '';
$error1 = (isset($error1)) ? $error1 : '';
$error2 = (isset($error2)) ? $error2 : '';
$error3 = (isset($error3)) ? $error3 : '';
$error4 = (isset($error4)) ? $error4 : '';
$error5 = (isset($error5)) ? $error5 : '';
$error6 = (isset($error6)) ? $error6 : '';
$success = (isset($success)) ? $success : '';
$submitid = (isset($submitid)) ? $submitid : '';
$var_width = (isset($var_width)) ? $var_width : '';
$backward = (isset($backward)) ? $backward : '';
$forward = (isset($forward)) ? $forward : '';
$namecheck = (isset($namecheck)) ? $namecheck : '';
$emailcheck = (isset($emailcheck)) ? $emailcheck : '';
$gravataremailcheck = (isset($gravataremailcheck)) ? $gravataremailcheck : '';
$urlcheck = (isset($urlcheck)) ? $urlcheck : '';
$messagecheck = (isset($messagecheck)) ? $messagecheck : '';
$antispamcheck = (isset($antispamcheck)) ? $antispamcheck : '';
$spam_detect = (isset($spam_detect)) ? $spam_detect : '';
$gravataremail = (isset($gravataremail)) ? $gravataremail : '';
$antispam_result = (isset($antispam_result)) ? $antispam_result : '';
$_REQUEST['success'] = (isset($_REQUEST['success'])) ? $_REQUEST['success'] : '';
$_REQUEST['newentry'] = (isset($_REQUEST['newentry'])) ? $_REQUEST['newentry'] : '';
$_REQUEST['select'] = (isset($_REQUEST['select'])) ? $_REQUEST['select'] : '';
$_REQUEST['from'] = (isset($_REQUEST['from'])) ? $_REQUEST['from'] : '';
$_REQUEST['widget_gb_step'] = (isset($_REQUEST['widget_gb_step'])) ? $_REQUEST['widget_gb_step'] : '';
$_REQUEST['widget'] = (isset($_REQUEST['widget'])) ? $_REQUEST['widget'] : '';
$_REQUEST['gbgravataremail'] = (isset($_REQUEST['gbgravataremail'])) ? $_REQUEST['gbgravataremail'] : '';
$_REQUEST['gbadditional'] = (isset($_REQUEST['gbadditional'])) ? $_REQUEST['gbadditional'] : '';

// URL
$url=get_bloginfo('wpurl');

// language
$language =	create_language($var_language);
$lang_name				=	html_entity_decode($language[0], ENT_QUOTES);
$lang_email				=	html_entity_decode($language[1], ENT_QUOTES);
$lang_url				=	html_entity_decode($language[2], ENT_QUOTES);
$lang_message			=	html_entity_decode($language[3], ENT_QUOTES);
$lang_antispam			=	html_entity_decode($language[4], ENT_QUOTES);
$lang_require			=	html_entity_decode($language[5], ENT_QUOTES);
$lang_submit			=	html_entity_decode($language[6], ENT_QUOTES);
$lang_name_error		=	html_entity_decode($language[7], ENT_QUOTES);
$lang_email_error		=	html_entity_decode($language[8], ENT_QUOTES);
$lang_url_error			=	html_entity_decode($language[9], ENT_QUOTES);
$lang_message_error		=	html_entity_decode($language[10], ENT_QUOTES);
$lang_antispam_error	=	html_entity_decode($language[11], ENT_QUOTES);
$lang_success			=	html_entity_decode($language[12], ENT_QUOTES);
$lang_admin_review		=	html_entity_decode($language[13], ENT_QUOTES);
$lang_spam_detect		=	html_entity_decode($language[14], ENT_QUOTES);

/* if using another recaptcha plugin check function "_recaptcha_qsencode" whether is already loaded */
if (function_exists('_recaptcha_qsencode')) {
	$included_files = get_included_files();
	foreach ($included_files as $filename) {
		if(substr_count($filename, 'recaptchalib.php') == 1) {
			define('RECAPTCHAPATH', $filename);
		}
	}
} else {
	define('RECAPTCHAPATH', ABSPATH . "wp-content/plugins/dmsguestbook/recaptcha/recaptchalib.php");
}

/* default english text */
if($lang_spam_detect == "") {
	$lang_spam_detect = __("This entry contains probably Spam!", "dmsguestbook") . "<br />" . __("This entry was not inscribed!", "dmsguestbook");
}

/* super guestbook */
if($var_supergb >= 1) {
$var_multi_gb_id = ($var_supergb -1);
}

############################################################################################
	
	/* guestbook container */
	$DMSGuestbookContent .= "<div class='css_guestbook_position'>";

# overall font color
if($var_fontcolor1!="none") {
$DMSGuestbookContent .= "<div class='css_guestbook_font_color'>"; }


		// -------- success text when db was written ---------
		// (reload block)
		if($_REQUEST['success']==1) {
		// success text
		$success = "$lang_success<br />";

		// if admin review (flag=1)
		if($var_admin_review==1) {$success.="$lang_admin_review<br />";}
		if($var_formpos=="bottom") {$DMSGuestbookContent .= "<div class='css_form_successmessage'>$success</div>"; }
		}


		// --------- save the guestbook entry --------
		if($_REQUEST['newentry']==1)
		{
			// --------------------- check the old HTTP_POST_VARS and new $_POST var -------------
			if(!empty($HTTP_POST_VARS)) {
			$POSTVARIABLE   = $HTTP_POST_VARS;
			}
			else {
		 		 $POSTVARIABLE = $_POST;
		 		 }

			// check the result of visual antispam
			if($var_require_antispam==1) {
				if(($_REQUEST['antispam_hash_key']) == sprintf("%s", strip_tags(md5($POSTVARIABLE["securecode"] . $var_antispam_key)))) {
					$antispam_result=1;
					$antispamcheck=1;
				}else { $antispam_result=0; $error5 =  "$lang_antispam_error";}
			}

			// check the result of mathematic antispam
			if($var_require_antispam==2) {
				if(($_REQUEST['antispam_hash_key']) == sprintf("%s", strip_tags(md5($POSTVARIABLE["securecode"] . $var_antispam_key)))) {
					$antispam_result=1;
					$antispamcheck=1;
				} else { $antispam_result=0; $error5 =  "$lang_antispam_error";}
			}

			// check the result of reCAPTCHA
			if($var_require_antispam==3) {
						require_once(RECAPTCHAPATH);
                        // Get a key from http://recaptcha.net/api/getkey
                        $privatekey = $var_recaptcha_privatekey;

                        # the response from reCAPTCHA
                        $resp = null;
                        # the error code from reCAPTCHA, if any
                        $error = null;

                        # was there a reCAPTCHA response?
                        if ($_POST["recaptcha_response_field"]) {
                          $resp = recaptcha_check_answer ($privatekey,
                                                        $_SERVER["REMOTE_ADDR"],
                                                        $_POST["recaptcha_challenge_field"],
                                                        $_POST["recaptcha_response_field"]);

                            if ($resp->is_valid) {
                                  $antispam_result=1;
								  $antispamcheck=1;
                            } else {
                                  # set the error code so that we can display it
                                  $error5 = $resp->error;
                                  $antispam_result=0;
                            }
                        }
			}

			if($var_require_antispam==0){
				$antispam_result=1;
				$antispamcheck=1;
			}



			// if antispam valid or off
			if($antispam_result==1 || $antispam_result==0) {


				/* remove all invalid chars from name field*/
				//$_REQUEST[gbname] = preg_replace("/[[:punct:]]+/i", "", $_REQUEST[gbname]);
				$_REQUEST['gbname'] = preg_replace("/[\\\\\"<=>\(\)\{\}\/]+/i", "", $_REQUEST['gbname']);

				// check name text lenght min. 1 char
				if(strlen($_REQUEST['gbname'])>=1) {
				$namecheck="1"; }
				else {$error1 = "$lang_name_error<br />";}


				/* remove all invalid chars from email field */
				$_REQUEST['gbemail'] = preg_replace("/[^a-z-0-9-_\.@]+/i", "", $_REQUEST['gbemail']);
				// check email email adress were is valid
				if(strlen($_REQUEST['gbemail'])>=1 || $var_require_email == 1)
				{
					if(preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $_REQUEST['gbemail']))
					{$emailcheck="1";}
					else {$error2 = "$lang_email_error<br />";}
				}
				else {$emailcheck=1;}

				/* remove all invalid chars from gravatar email field */
				$_REQUEST['gbgravataremail'] = strtolower(preg_replace("/[^a-z-0-9-_\.@]+/i", "", $_REQUEST['gbgravataremail']));
				// check email email adress were is valid
				if(strlen($_REQUEST['gbgravataremail'])>=1)
				{
					if(preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $_REQUEST['gbgravataremail']))
					{$gravataremailcheck="1";}
					else {$error6 = "$lang_email_error [Gravatar]<br />";}
				}
				else {$gravataremailcheck=1;}


				/* remove all invalid chars from url field */
				$_REQUEST['gburl'] = preg_replace("/[^a-z-0-9-_,.:?&%=\/]+/i", "", $_REQUEST['gburl']);
				// check url adress were is valid
				if(strlen($_REQUEST['gburl'])>=1 || $var_require_url == 1)
				{
					if(preg_match ("/^([^.-:\/][a-z0-9-.:\/]*)\.?+([a-z0-9-]+)*\.([a-z]{2,6})(\/)?([a-z0-9-_,.?&%=\/]*)$/i", $_REQUEST['gburl']))
					{$urlcheck="1";}
					else {$error3 = "$lang_url_error<br />";}
				}
				else {$urlcheck=1;}


				/* remove all html tags from message field */
				$_REQUEST['gbmsg'] = strip_tags($_REQUEST['gbmsg']);
				/* if user want to set admin [html] tags */
				$_REQUEST['gbmsg']=str_replace("[html]", "", $_REQUEST['gbmsg']);
				$_REQUEST['gbmsg']=str_replace("[/html]", "", $_REQUEST['gbmsg']);

				$_REQUEST['gbmsg']=str_replace("&", "&amp;", $_REQUEST['gbmsg']);

				// check message text lengt. min. 1 char
				if(strlen($_REQUEST['gbmsg'])>=1) {
				$messagecheck="1"; }
				else {$error4 = "$lang_message_error<br />";}


					if($namecheck=='1' && $emailcheck=='1' && $gravataremailcheck=='1' && $urlcheck=='1' && $messagecheck=='1' && $antispamcheck=='1')
					{
						//set the http:// string if is missing
						if(preg_match ("/^(http(s)?:\/\/)/i", $_REQUEST['gburl']))
						{$newurl = $_REQUEST['gburl'];} else {$newurl="http://" . $_REQUEST['gburl'];}

						$nname=addslashes($_REQUEST['gbname']);
						$mmu=addslashes($_REQUEST['gbmsg']);

						$date = mktime(date("H")+$var_offset, date("i"), date("s"), date("m"), date("d"), date("Y"));
						$ip = getenv('REMOTE_ADDR');

						if(strlen($_REQUEST['gbgravataremail']) > 0) {
						$gravataremail = md5($_REQUEST['gbgravataremail']);
						}

						/* akismet */
						if($var_akismet == 1) {
							$query_akismet = $wpdb->get_results("SELECT * FROM $table_option WHERE option_name = 'wordpress_api_key'");
							$num_rows_akismet = $wpdb->num_rows;

								foreach ($query_akismet as $result) {
								$var_akismet_key = $result->option_value;
								}

							$spam_detect = akismet($var_akismet_key, $nname, $_REQUEST['gbemail'], $newurl, $mmu, $lang_spam_detect);
								if($spam_detect==1 && $var_akismet_action==1) {
								$error1 .= $lang_spam_detect;
								}
							}
							else {
						         $spam_detect==0;
						         }

						/* cut the message text if $var_messagetext_length is not 0 */
						if($var_messagetext_length != 0) {
						$mmu = substr($mmu, 0, $var_messagetext_length); //
						}

						if($spam_detect==0 || $var_akismet_action!=1) {
							$sql=$wpdb->query("INSERT INTO $table_name (
							name, email, gravatar, url, date, ip, message, flag, guestbook, spam, additional
							)
							VALUES (
							'" . mysql_real_escape_string($nname) . "',
							'" . mysql_real_escape_string($_REQUEST['gbemail']) . "',
							'" . mysql_real_escape_string($gravataremail) . "',
							'" . mysql_real_escape_string($newurl) . "',
							'" . mysql_real_escape_string($date) . "',
							'" . mysql_real_escape_string($ip) . "',
							'" . mysql_real_escape_string($mmu) . "',
							'" . sprintf("%d", $var_admin_review) . "',
							'" . sprintf("%d", $var_multi_gb_id) . "',
							'" . sprintf("%d", $spam_detect) . "',
							'" . mysql_real_escape_string($_REQUEST['gbadditional']) . "')")
							or die (__("Database not available!", "dmsguestbook"));

							$abspath = str_replace("\\","/", ABSPATH);
							require_once($abspath . 'wp-admin/upgrade-functions.php');
      						dbDelta($sql);

							// send mail
							if($var_send_mail==1) {
								$DMSGuestbookContent .= send_email($var_mail_adress, $nname, $_REQUEST['gbemail'], $newurl, $ip, $mmu, $var_mail_method, $var_smtp_host, $var_smtp_port, $var_smtp_username, $var_smtp_password, $var_smtp_auth, $var_smtp_ssl, $_REQUEST['gbadditional']);
							}

							// unset variables
							unset($_REQUEST['gbname']);
							unset($_REQUEST['gbemail']);
							unset($_REQUEST['gbgravataremail']);
							unset($_REQUEST['gburl']);
							unset($_REQUEST['gbmsg']);
							unset($_REQUEST['gbadditional']);

							unset($antireload);
							$permalink = get_permalink($var_page_id);
							if(strstr($permalink, '?')) {
								if(headers_sent() == 1) {
								$antireload = $permalink . "&success=1";
								$DMSGuestbookContent .= "<meta http-equiv='refresh' content='0; URL=$antireload'>";
								}
								else 	{
										header('Refresh: 0; url=' . $permalink . '&success=1');
										}
							}
							else
								if(headers_sent() == 1) {
								$antireload = $permalink . "?success=1";
								$DMSGuestbookContent .= "<meta http-equiv='refresh' content='0; URL=$antireload'>";
								}
								else
									{
									header('Refresh: 0; url=' . $permalink . '?success=1');
									}
						} // akismet spam check
					}

	}				if($var_formpos=="bottom") {$DMSGuestbookContent .= "<a class=\"css_form_errormessage\" href=\"#guestbookform\">$error1 $error2 $error3 $error4 $error5 $error6</a><br /><br />";}


}

	// if guestbook form is on top the side
	if ($var_formpos =="top") {
	$DMSGuestbookContent .= input_form($error1, $error2, $error3, $error4, $error5, $error6, $success, $url, $var_page_id, $lang_name, $lang_email, $var_require_email, $lang_url, $var_require_url, $lang_message, $submitid, $lang_require, $var_require_antispam, $lang_antispam, $lang_submit, $var_url_overruled,$var_mandatory_char, $var_form_template, $var_antispam_key, $var_captcha_color, $var_gravatar, $var_additional_option, $var_additional_option_title, $var_recaptcha_publickey, $var_messagetext_length);
	}
	else {
	     $DMSGuestbookContent .= "<a class=\"css_form_link\" href=\"#guestbookform\">$var_formposlink</a>";
	     }

	# start init
	$select = sprintf("%d", $_REQUEST['select']);
	$from 	= sprintf("%d", $_REQUEST['from']);
	if($_REQUEST['from']=="") {$from=0; $select=1;}

	# count all guestbook entries
	# if flag = 1 the admin will review this post
	$query1 = $wpdb->get_results("SELECT id FROM $table_name WHERE flag != '1' && guestbook = '" . sprintf("%d", $var_multi_gb_id) . "' && spam = '0' ");
	$num_rows1 = $wpdb->num_rows;

	/* if widget <a href="" is activated */
	if($_REQUEST['widget_gb_step']==1) {$var_step=1; $num_rows1=1;}

	# read the guestbook
	# if flag = 1 the admin will review this post
	$query2 = $wpdb->get_results("SELECT * FROM $table_name
	WHERE flag != '1' && guestbook = '" . sprintf("%d", $var_multi_gb_id) ."' && spam = '0' ORDER BY id
	" . sprintf("%s", $var_sortitem) . " LIMIT " . $from .
	"," . sprintf("%d", $var_step) . ";");
	$num_rows2 = $wpdb->num_rows;

	$next=$from+$var_step;
	$back=$from-$var_step;


	$DMSGuestbookContent .= "<div class='css_navigation_totalcount'>($num_rows1)</div>";
	$DMSGuestbookContent .= "<div class='css_navigation_overview'>";

	$y=0;
	for($x=0; $x<$num_rows1; ($x=$x+$var_step))
	{
	$y++;
		if($select==$y) {
		$DMSGuestbookContent .= "<a class=\"css_navigation_select\" href=\"$url/index.php?page_id=$var_page_id&amp;from=$x&amp;select=$y\">$y </a> ";
		}
		else {
		     $DMSGuestbookContent .= "<a class=\"css_navigation_notselect\" href=\"$url/index.php?page_id=$var_page_id&amp;from=$x&amp;select=$y\">$y</a> ";
			 }
	}
	$DMSGuestbookContent .= "</div>";

	// navigation char forward construct
	if($next>=$num_rows1) {} else {
	$_REQUEST['select_forward']=$select+1;
	$forward ="<a class=\"css_navigation_char\" href=\"$url/index.php?page_id=$var_page_id&amp;from=$next&amp;select=$_REQUEST[select_forward]\">$var_forwardchar</a>";
	}

	// navigation char backward construct
	if($back<=-1) {} else {
	$_REQUEST['select_backward']=$select-1;
	$backward = "<a class=\"css_navigation_char\" href=\"$url/index.php?page_id=$var_page_id&amp;from=$back&amp;select=$_REQUEST[select_backward]\">$var_backwardchar</a>";
	}

	// show top navigation
	$DMSGuestbookContent .= navigation($num_rows1, $var_step, $var_width, $backward, $forward);

	// setlocale
	setlocale(LC_TIME, "$var_setlocale");


	// show DMSGuestbook entries
	foreach ($query2 as $dbresult) {

	if($var_sortitem=="ASC"){
		$itemnr=($from++)+1;
		}
	if($var_sortitem=="DESC"){
	$itemnr=($num_rows1 - $from++);
		}

####

$gravatar_url = "http://www.gravatar.com/avatar/".$dbresult->gravatar . "?r=" . $var_gravatar_rating . "&amp;s=" . $var_gravatar_size;

#####

		// DMSGuestbook post container
		$DMSGuestbookContent .= "<div class='css_post_embedded'>";

		// build the date / time variable
		$sec=date("s", "$dbresult->date");
		$min=date("i", "$dbresult->date");
		$hour=date("H", "$dbresult->date");
		$day=date("d", "$dbresult->date");
		$month=date("m", "$dbresult->date");
		$year=date("Y", "$dbresult->date");
		$displaydate = strftime ("$var_dateformat", mktime ($hour, $min, $sec, $month, $day, $year));
		$displaydate=htmlentities($displaydate, ENT_QUOTES);

		// remove quote /
		$message_name=stripslashes(htmlspecialchars($dbresult->name, ENT_QUOTES));
		$message_text=stripslashes($dbresult->message);
		$additional_text=stripslashes(htmlspecialchars($dbresult->additional, ENT_QUOTES));


		// add slash if ip is visible
		if($var_show_ip==1) {
			$slash="&nbsp;/&nbsp;";
			$part1=explode(".", $dbresult->ip);
			$part2=explode(".", $options["ip_mask"]);
				if($part2[0]=="*") {$part1[0]=str_repeat("*", strlen($part1[0]));}
				if($part2[1]=="*") {$part1[1]=str_repeat("*", strlen($part1[1]));;}
				if($part2[2]=="*") {$part1[2]=str_repeat("*", strlen($part1[2]));;}
				if($part2[3]=="*") {$part1[3]=str_repeat("*", strlen($part1[3]));;}
				$show_ip = $part1[0] . "." . $part1[1] . "." . $part1[2] . "." . $part1[3];
		} else {
			   $show_ip=""; $slash="";
			   }

		// show email icon
		if($var_show_email==1 && $dbresult->email != "") {
					# convert to ascii, better spam protection
					unset($ascii_email, $ascii_email_array);
					$ascii_email = (isset($ascii_email)) ? $ascii_email : '';
					$ascii_email_array = (isset($ascii_email_array)) ? $ascii_email_array : '';
					for($p=0; $p<strlen($dbresult->email); $p++) {
					$ascii_email_array[]=ord($dbresult->email[$p]);
					$ascii_email .= "&#" . $ascii_email_array[$p] . ";";
					}
			$show_email="<a href=\"mailto:$ascii_email\"><img class=\"css_post_email_image\" src=\"$var_email_image_path\" alt=\"email\" /></a>";
		} else {
			   $show_email="";
			   }

		// show url icon
		if($var_show_url==1 && ($dbresult->url != "http://" && $dbresult->url != "https://")) {
					# convert to ascii, better spam protection
					unset($ascii_url, $ascii_url_array);
					$ascii_url = (isset($ascii_url)) ? $ascii_url : '';
					$ascii_url_array = (isset($ascii_url_array)) ? $ascii_url_array : '';
					for($p=0; $p<strlen($dbresult->url); $p++) {
					$ascii_url_array[]=ord($dbresult->url[$p]);
					$ascii_url .= "&#" . $ascii_url_array[$p] . ";";
					}
						/* nofollow */
						if($var_nofollow==1) {
						$rel = "rel='nofollow'";
						} else {$rel="";}
			$show_url="<a href=\"$ascii_url\" $rel target=\"_blank\"><img class=\"css_post_url_image\" src=\"$var_website_image_path\" alt=\"url\" /></a>&nbsp;";
		} else {
			   $show_url="";
			   }


	// to decide database id or continuous number
	if($var_dbid==1) {
	$show_id = $dbresult->id;
	} else {
		   		if($_REQUEST['widget']==1) {
		   		$show_id = $_REQUEST['itemnr'];
		   		}
		   		else {$show_id = $itemnr;}
		   }

		/* message body */

		$search_tags=array("&","<",">");
		$replace_tags=array("&#38;","&#60;","&#62;");

		// parse ; correct
		$message_text=str_replace("&#38;#59", "&#59;", $message_text);

		/* replace all old admin [html] tags */
		$message_text=str_replace("[html]", "", $message_text);
		$message_text=str_replace("[/html]", "", $message_text);

		$message_text=str_replace("\r\n", " <br /> ", $message_text);
		$message_text=str_replace("\n", " <br /> ", $message_text);



		// smilies
		if(get_option('use_smilies')==1) {
			$siteurl = get_option( 'siteurl' );
			foreach ( (array) $wpsmiliestrans as $smiley => $img ) {
			$wp_smiliessearch = '/(\s|^)' . preg_quote( $smiley, '/' ) . '(\s|$)/';
			$smiley_masked = attribute_escape( trim( $smiley ) );
			$wp_smiliesreplace = " <img src='$siteurl/wp-includes/images/smilies/$img' alt='$smiley_masked' class='wp-smiley' /> ";
			$message_text=preg_replace($wp_smiliessearch, $wp_smiliesreplace, $message_text);
			}
		}

		$message_text = str_replace("\\","",$message_text);

		// if additional text is activated
		if($var_show_additional_option==0) {
		$additional_text="";
		}

		include("template/post/$var_post_template");
		$DMSGuestbookContent .= $GuestbookEntries1;

		if($dbresult->gravatar !="" && $var_gravatar==1) {
		$DMSGuestbookContent .= $GuestbookEntries2;
		}

		$DMSGuestbookContent .= $GuestbookEntries3;
		$DMSGuestbookContent .= "</div>";
		$DMSGuestbookContent .= $GuestbookEntries4;
	}

	// show bottom navigation
	$DMSGuestbookContent .= navigation($num_rows1, $var_step, $var_width, $backward, $forward);

	// if guestbook form is on bottom the side
	if ($var_formpos =="bottom") {
	$DMSGuestbookContent .= "<a name='guestbookform' class='css_form_link'></a>";
	$DMSGuestbookContent .= input_form($error1, $error2, $error3, $error4, $error5, $error6, $success, $url, $var_page_id, $lang_name, $lang_email, $var_require_email, $lang_url, $var_require_url, $lang_message, $submitid, $lang_require, $var_require_antispam, $lang_antispam, $lang_submit, $var_url_overruled,$var_mandatory_char, $var_form_template, $var_antispam_key, $var_captcha_color, $var_gravatar, $var_additional_option, $var_additional_option_title, $var_recaptcha_publickey, $var_messagetext_length);
	}

$DMSGuestbookContent .= "</div>";


function input_form($error1, $error2, $error3, $error4, $error5, $error6, $success, $url, $var_page_id, $lang_name, $lang_email, $var_require_email, $lang_url, $var_require_url, $lang_message, $submitid, $lang_require, $var_require_antispam, $lang_antispam, $lang_submit, $var_url_overruled, $var_mandatory_char, $var_form_template, $var_antispam_key, $var_captcha_color, $var_gravatar, $var_additional_option, $var_additional_option_title, $var_recaptcha_publickey, $var_messagetext_length) {

	$gbadditional_selectbox = (isset($gbadditional_selectbox)) ? $gbadditional_selectbox : '';
	$captcha2 = (isset($captcha2)) ? $captcha2 : '';
	$recaptcha = (isset($recaptcha)) ? $recaptcha : '';
	$DMSGuestbookContent = (isset($DMSGuestbookContent)) ? $DMSGuestbookContent : '';
	$_REQUEST['gbname'] = (isset($_REQUEST['gbname'])) ? $_REQUEST['gbname'] : '';
	$_REQUEST['gbemail'] = (isset($_REQUEST['gbemail'])) ? $_REQUEST['gbemail'] : '';
	$_REQUEST['gburl'] = (isset($_REQUEST['gburl'])) ? $_REQUEST['gburl'] : '';
	$_REQUEST['gbgravataremail'] = (isset($_REQUEST['gbgravataremail'])) ? $_REQUEST['gbgravataremail'] : '';
	$_REQUEST['gbmsg'] = (isset($_REQUEST['gbmsg'])) ? $_REQUEST['gbmsg'] : '';
	$_REQUEST['gbadditional'] = (isset($_REQUEST['gbadditional'])) ? $_REQUEST['gbadditional'] : '';
	$captcha1 = (isset($captcha1)) ? $captcha1 : '';
	$antispam_hash_key = (isset($antispam_hash_key)) ? $antispam_hash_key : '';
	
	$abspath = str_replace("\\","/", ABSPATH);

	###########
	/* captcha antispam image */
	if($var_require_antispam==1) {
	$seed = date("U");
		srand($seed);
      // all figures were captcha can use
      $possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
      unset($str);
	  $str = (isset($str)) ? $str : '';
	  while(strlen($str)<5) {
        $str.=substr($possible,(rand()%(strlen($possible))),1);
      	}
	$captcha1 = $url . "/wp-content/plugins/dmsguestbook/captcha/captcha.php?seed=$seed&amp;var_captcha_color=$var_captcha_color";
	$antispam_hash_key = md5($str . $var_antispam_key);
	}
	###########


	###########
	/* captcha antispam mathematic */
	if($var_require_antispam==2) {
		srand();
		$rand1 = rand(1, 9);
		$rand2 = rand(1, 9);
		$captcha2 = $rand1 . " + " . $rand2 . "=";
		$antispam_hash_key = md5(($rand1+$rand2) . $var_antispam_key);
	}
	###########

	###########
	/* reCAPTCHA */
	if($var_require_antispam==3) {
		require_once(RECAPTCHAPATH);
		// Get a key from http://recaptcha.net/api/getkey
		$publickey = $var_recaptcha_publickey;
		$recaptcha = recaptcha_get_html($publickey, $error5);
	}
	###########

	$gbname 	= $_REQUEST['gbname'];
	$gbemail 	= $_REQUEST['gbemail'];
	$gbgravataremail 	= $_REQUEST['gbgravataremail'];
	$gburl 		= $_REQUEST['gburl'];
	$gbmsg 		= str_replace("\\","",$_REQUEST['gbmsg']);
	$gbadditional_raw = $_REQUEST['gbadditional'];


	if($var_require_email==1){$var_mandatory_email=$var_mandatory_char; } else {$var_mandatory_email=""; }
	if($var_require_url==1) 	{$var_mandatory_url=$var_mandatory_char; }   else {$var_mandatory_url=""; }

		// if additional option is selected
		if($var_additional_option != "none") {
		$buffer = "";
		$gbadditional_selectbox = "";

				if(is_file($abspath . "wp-content/plugins/dmsguestbook/module/" . $var_additional_option)) {
				$handle = fopen ($url . "/wp-content/plugins/dmsguestbook/module/" . $var_additional_option, "r");
					while (!feof($handle)) {
    				$buffer .= fgets($handle, 4096);
					}
				fclose($handle);
				}
			$split = explode(";", $buffer);

				if($gbadditional_raw !="") {
				$gbadditional_selectbox .= "<option>" . $gbadditional_raw . "</option>";
				}
			for($s=0; $s<count($split)-1; $s++) {
				if(trim($split[$s]) != trim($gbadditional_raw)) {
				$gbadditional_selectbox .= "<option>" . trim($split[$s]) . "</option>";
				}
			}
		}


	/* Check whether message text chars count is activated */
	if($var_messagetext_length != 0) {
	$countchars[0] = "onkeyup=\"gbmsgLen()\"";
	$countchars[1] = "<br />";
	$countchars[2] = $var_messagetext_length;
	$countchars[3] = "<input style='border:1px;' type='text' name='counter' value='$var_messagetext_length' size='5' readonly='readonly' />";
	} else {
		   unset($countchars);
		   }

	$countchars = (isset($countchars)) ? $countchars : '';
	$countchars[0] = (isset($countchars[0])) ? $countchars[0] : '';
	$countchars[1] = (isset($countchars[1])) ? $countchars[1] : '';
	$countchars[2] = (isset($countchars[2])) ? $countchars[2] : '';
	$countchars[3] = (isset($countchars[3])) ? $countchars[3] : '';
	include("template/form/$var_form_template");


	$DMSGuestbookContent .= "<div class='css_form_embedded'>";
	$DMSGuestbookContent .= $var_form1;

	#Form

	if(strlen($var_url_overruled)>4) {
	$DMSGuestbookContent .= "<form action=\"$var_url_overruled\" method=\"post\">";
	}
	else {
	     $DMSGuestbookContent .= "<form action=" . "\"" . get_permalink($var_page_id) . "\"" . " method=\"post\">";
	     }

	$DMSGuestbookContent .= $var_form2;

	if($var_gravatar==1) {
	$DMSGuestbookContent .= $var_form3;
	}

	$DMSGuestbookContent .= $var_form4;

	if($var_additional_option != "none") {
	$DMSGuestbookContent .= $var_form5;
	}

	$DMSGuestbookContent .= $var_form6;

	if($var_require_antispam==1) {
	$DMSGuestbookContent .= $var_form7;
	}

	if($var_require_antispam==2) {
	$DMSGuestbookContent .= $var_form8;
	}

	if($var_require_antispam==3) {
	$DMSGuestbookContent .= $var_form8_1;
	}

	if($var_require_antispam==0) {
	}

	$DMSGuestbookContent .= $var_form9 . "<input type='hidden' name='newentry' value='1' />
	<input type='hidden' name='Itemid' value='$submitid' />
	<input type='hidden' name='antispam_hash_key' value='$antispam_hash_key' />
	</form>";


	$DMSGuestbookContent .= $var_form10;
	$DMSGuestbookContent .= "</div>";
	$DMSGuestbookContent .= $var_form11;

	return $DMSGuestbookContent;
}







	# #	# # # # # - FUNCTIONS - # # # # # # #

	/* language */
	function create_language($var_language)
	{
		$abspath = str_replace("\\","/", ABSPATH);
		$handle = fopen ($abspath . "wp-content/plugins/dmsguestbook/language/" . $var_language, "r");
		unset($stringtext);
		$stringtext = (isset($stringtext)) ? $stringtext : '';
			if($handle) {
				while (!feof($handle)) {
    			$buffer = fgets($handle, 4096);
				$stringtext=$stringtext . $buffer;
				}
			}
		fclose($handle);

		$string_flag=array(
		"name",
		"email",
		"url",
		"message",
		"antispam",
		"mandatory",
		"submit",
		"name_error",
		"email_error",
		"url_error",
		"message_error",
		"antispam_error",
		"success",
		"admin_review",
		"spam_detect"
		);

		unset($language);
		for($c=0; $c<count($string_flag); $c++) {
		$part1 = explode("<" . $string_flag[$c] . ">", $stringtext);
		$part2 = explode("</" . $string_flag[$c] . ">", $part1[1]);
		$language[$c]=htmlentities($part2[0], ENT_QUOTES);
		$language[$c]=str_replace("&lt;", "<", $language[$c]);
		$language[$c]=str_replace("&gt;", ">", $language[$c]);
		}
		//$DMSGuestbookContent .= $language;
		//return $DMSGuestbookContent;
		return $language;
	}


	/* create navigation */
	function navigation($num_rows1, $var_step, $var_width, $backward, $forward) {
		$DMSGuestbookContent = (isset($DMSGuestbookContent)) ? $DMSGuestbookContent : '';
		if($num_rows1 > $var_step) {
		$DMSGuestbookContent .= "<div class='css_navigation_char_position'>";
		$DMSGuestbookContent .= $backward . " " .$forward;
		$DMSGuestbookContent .= "</div>";
	 	}
	return $DMSGuestbookContent;
	}




	/* Email function with phpmailer */
	function send_email($var_mail_adress, $nname, $gbemail, $newurl, $ip, $mmu, $var_mail_method, $var_smtp_host, $var_smtp_port, $var_smtp_username, $var_smtp_password, $var_smtp_auth, $var_smtp_ssl, $gbadditional) {
		$phpvers = explode(".", phpversion());

		$date=date("d.m.Y, h:i:s");
		$host = str_replace("www.", "", "$_SERVER[HTTP_HOST]");

		if(!class_exists('PHPMailer')) {
			if($phpvers[0] == 4) {
			include_once('phpmailer/php4/class.phpmailer.php');
			}

			if($phpvers[0] >= 5) {
			include_once('phpmailer/php5-6/class.phpmailer.php');
			}
		}
			$mail = new PHPMailer();

			if($var_mail_method == "Mail") {
			$mail->isMail();
			}

			if($var_mail_method == "SMTP") {
			$mail->isSMTP();
			$mail->Host     = $var_smtp_host;
			$mail->Port		= $var_smtp_port;
			}

			if($var_smtp_auth == 1) {
			$mail->SMTPAuth = true;
			$mail->Username	= $var_smtp_username;
			$mail->Password = $var_smtp_password;
			}

			if($var_smtp_ssl == 1) {
			$mail->SMTPSecure = true;
			}

		/* split multiple email adresses */
		$split_adress = explode(";", $var_mail_adress);

		for($x=0; $x<count($split_adress); $x++) {
			$body             = __("From", "dmsguestbook") . ": $nname\n" . __("E-mail", "dmsguestbook") . ": $gbemail\n" . __("Website", "dmsguestbook") . ": $newurl\n" . __("Additional text", "dmsguestbook") . ": $gbadditional\n\n" . __("Message", "dmsguestbook") . ":\n$mmu\n\n" . __("IP", "dmsguestbook") . ": $ip\n" . __("Date", "dmsguestbook") . ": $date";
			$mail->From       = "DMSGuestbook@".$host;
			$mail->FromName   = "DMSGuestbook";
			$mail->Subject    = __("You have a new guestbook post!", "dmsguestbook");
			$mail->AddAddress($split_adress[$x], $host);
			$mail->Body		  = $body;
			$mail->CharSet 	  = 'utf-8';
			@$mail->Send();
			$mail->ClearAddresses();
		}

		// debuging
		//if(!$mail->Send()) {
  		//	echo "Mailer Error: " . $mail->ErrorInfo;
		//} else {
  		//		echo "Message sent!";
		//	   }
		//print_r($mail);
		//exit;

	}



/* Akismet */
	function akismet($var_akismet_key, $nname, $gbemail, $newurl, $mmu, $errormsg) {
		$url=get_bloginfo('wpurl');
		$phpvers = explode(".", phpversion());

		if($phpvers[0] == 4) {
		include_once('microakismet/func.microakismet.inc.php');
		}

		if($phpvers[0] >= 5) {
		include_once "microakismet/class.microakismet.inc.php";
		}

		// The array of data we need
		$vars    = array();
		$vars["user_ip"]              = $_SERVER["REMOTE_ADDR"];
   		$vars["user_agent"]           = $_SERVER["HTTP_USER_AGENT"];
   		$vars["reerrer"]			  = $_SERVER["HTTP_REFERER"];

   		$vars["comment_content"]      = $mmu;
   		$vars["comment_author"]       = $nname;
   		$vars["comment_author_url"]	  = $newurl;
   		$vars["comment_author_email"] = $gbemail;
   		$vars["permalink"]			  = get_permalink($var_page_id);
   		$vars["comment_type"]		  = "comment";

		/* php 4 */
		if($phpvers[0] == 4) {
			if ( akismet_check( $vars ) ) {
				//echo "Spam detected!";
				//echo $errormsg;
				return(1);
			}
			else {
		    	return(0);
		    	}
			}

		/* php 5 & 6 */
		if($phpvers[0] >= 5) {
		$akismet	= new MicroAkismet("$var_akismet_key", $vars["permalink"], "$url/1.0");

			if ( $akismet->check( $vars ) ) {
				//echo "Spam detected!";
				//echo $errormsg;
				return(1);
			}
			else {
		    	return(0);
		    	}
			}
}
/* end guestbook container */
$DMSGuestbookContent .= "</div>";
?>