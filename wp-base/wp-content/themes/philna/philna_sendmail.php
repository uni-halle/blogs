<?php
$options = get_option('philna_options');
if($options['authorname']=='' || $options['authorname']==''):
_e('<div class="mailinfo" style="color:red;"><p><strong>You should set your name and E_mail if you want to use the contact template page</strong></p></div>','philna');
else:
?>
<?php
$options = get_option('philna_options');
function yinheli_sendmail($to,$subject,$message){
$blogname = get_option('blogname');
$charset = get_option('blog_charset');
$yinheli_sendmail_headers  = "From: $blogname \n" ;
$yinheli_sendmail_headers .= "MIME-Version: 1.0\n";
$yinheli_sendmail_headers .= "Content-Type: text/html;charset=\"$charset\"\n";
return @wp_mail($to, $subject, $message, $yinheli_sendmail_headers);
}
$m='';$s='';$msg='';
if (isset($_POST["sendmail"])){
	if(!is_email($_POST["mailfrom"])){
	_e('<div class="mailinfo" style="color: #008800;"><p>Please enter a valid email address!</p></div>','philna');
	$s=$_POST["subject"];
	$msg=$_POST["message"];
	
	
	}elseif($_POST["subject"]==''){
	_e('<div class="mailinfo" style="color: #008800;"><p>Please enter the e-mail Subject</p></div>','philna');
	$s=$_POST["subject"];
	$msg=$_POST["message"];
	
	
	}elseif($_SESSION['VCODE']!==$_POST["yzm"]){
	_e('<div class="mailinfo" style="color: #008800;"><p>Verification Code error</p></div>','philna');
	$m=$_POST["mailfrom"];
	$s=$_POST["subject"];
	$msg=$_POST["message"];
	
	
	}elseif($_POST["message"]==''){
	_e('<div class="mailinfo" style="color: #008800;"><p>please type the content</p></div>','philna');
	$s=$_POST["subject"];
	
	}else{
	//格式化输出
	$tome=$options['authormail'];
	$authorname=$options['authorname'];
	$tocc=$_POST["mailfrom"];
	$tome_subject=$_POST["subject"].'---from-'.$_POST["mailfrom"];
	$tocc_subject='CC: '.$_POST["subject"].'-----You sended a mail to '.$authorname;
	$tome_message='Someone sended me a mail,his(her) E_mail is :'.$_POST["mailfrom"].' The following are the contents of<hr>'.$_POST["message"];
	
	$tocc_message='Thank you directly send a mail through my blog.I will reply as soon as possible The following are the contents of<hr>'.$_POST["message"];
	//发送邮件
	yinheli_sendmail($tome,$tome_subject,$tome_message);
	yinheli_sendmail($tocc,$tocc_subject,$tocc_message);
	_e('<div class="mailinfo"><p><strong>Your message has been sent</strong></p></div>','philna');	
	}
}else{
_e('<p><strong>Give me a direct email</strong></p>','philna');
}
?>
<div id="mailform" >
<form method="post" action="" >
<div class="row"><input id="mailfrom" class="sendtext" name="mailfrom" type="text" value="<?echo $m;?>" tabindex="1" />
<label for="mailfrom" class="small"><?php _e('E-Mail (required)', 'philna');?></label></div>

<div class="row"><input id="subject" class="sendtext" name="subject" type="text" value="<?echo $s?>" tabindex="2" />
<label for="subject" class="small"><?php _e('E-mail Subject (required)', 'philna');?></label></div>

<div class="row"><input id="yzm" class="sendtext" name="yzm" type="text" value="" tabindex="3" />

<label for="yzm" class="small"><?php _e('Verification Code', 'philna');?>(*):<img class="yzimg" src="<?php bloginfo('template_url'); ?>/yz_img.php"  alt="Click the picture to refresh" onclick="this.src=this.src+'?'" style="cursor:pointer;" /><small style="color:#ABABAB;">Click the picture to refresh</small></label></div>


<div class="row"><textarea id="sendmsg" class="sendmsg" style="width:630px;" name="message" cols="50" rows="8" tabindex="4"><?echo $msg?></textarea></div>
<p class="alignright"><input class="sendsub"  type="submit" name="sendmail" value="<?php _e('Send e-mail','philna')?>" tabindex="5"/></p>
<p>E-mail form author:<a href="http://philna.com">yinheli</a></p>
</form>
</div>
<?php endif;?>