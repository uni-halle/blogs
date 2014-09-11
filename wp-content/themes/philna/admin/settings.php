<?php
class PhilNaOptions{

function getOptions() {
$options = get_option('philna_options');
if (!is_array($options)) {
$options=array(
'authormail'=>'',
'authorname'=>'',
'keywords'=>'',
'description'=>'',
'b_title_add'=>'',
'google_cse'=>'',
'feed'=>'',
'feed_url'=>'',
'feed_email'=>false,
'feed_url_email'=>'',
'rss_show_comment_state'=>true,
'rss_copyright_show'=>false,
'rss_copyright'=>'',
'show_welcome'=>true,
'notice'=>false,
'notice_content'=>'',
'showcase_registered'=>false,
'showcase_commentator'=>false,
'showcase_visitor'=>false,
'showcase_caption'=>false,
'showcase_content'=>'',
'show_online_counter'=>false,
'footer_content'=>'',
'xing_show'=>true,
'xing_limit_days'=>7,
'xing_limit_cm'=>5,
);
update_option('philna_options', $options);
}
return $options;
}

	function add(){
		if(isset($_POST['philna_save'])) {
			$options = philnaOptions::getOptions();
			
			
			//information
			$options['authormail'] = stripslashes($_POST['authormail']);
			$options['authorname'] = stripslashes($_POST['authorname']);

			// Main Meta
			$options['description'] = stripslashes($_POST['description']);
			$options['keywords'] = stripslashes($_POST['keywords']);
			$options['b_title_add'] = stripslashes($_POST['b_title_add']);

			// google custom search engine
			if ($_POST['google_cse']){
				$options['google_cse'] = (bool)true;
			} else {
				$options['google_cse'] = (bool)false;
			}
			$options['google_cse_cx'] = stripslashes($_POST['google_cse_cx']);

			// feed
			if ($_POST['feed']) {
				$options['feed'] = (bool)true;
			} else {
				$options['feed'] = (bool)false;
			}
			$options['feed_url'] = stripslashes($_POST['feed_url']);
			if ($_POST['feed_email']) {
				$options['feed_email'] = (bool)true;
			} else {
				$options['feed_email'] = (bool)false;
			}
			$options['feed_url_email'] = stripslashes($_POST['feed_url_email']);
			
			//rss
			if (!$_POST['rss_show_comment_state']) {
				$options['rss_show_comment_state'] = (bool)false;
			} else {
				$options['rss_show_comment_state'] = (bool)true;
			}
			if ($_POST['rss_copyright_show']) {
				$options['rss_copyright_show'] = (bool)true;
			} else {
				$options['rss_copyright_show'] = (bool)false;
			}
			$options['rss_copyright'] = stripslashes($_POST['rss_copyright']);
			
			
			if (!$_POST['show_welcome']) {
			$options['show_welcome'] = (bool)false;
			} else {
				$options['show_welcome'] = (bool)true;
			}
			// homepage notice
			if ($_POST['notice']) {
				$options['notice'] = (bool)true;
			} else {
				$options['notice'] = (bool)false;
			}
			$options['notice_content'] = stripslashes($_POST['notice_content']);

			// sidebar notice
			if (!$_POST['showcase_registered']) {
				$options['showcase_registered'] = (bool)false;
			} else {
				$options['showcase_registered'] = (bool)true;
			}
			if (!$_POST['showcase_commentator']) {
				$options['showcase_commentator'] = (bool)false;
			} else {
				$options['showcase_commentator'] = (bool)true;
			}
			if (!$_POST['showcase_visitor']) {
				$options['showcase_visitor'] = (bool)false;
			} else {
				$options['showcase_visitor'] = (bool)true;
			}
			if ($_POST['showcase_caption']) {
				$options['showcase_caption'] = (bool)true;
			} else {
				$options['showcase_caption'] = (bool)false;
			}
			$options['showcase_title'] = stripslashes($_POST['showcase_title']);
			$options['showcase_content'] = stripslashes($_POST['showcase_content']);
			
			if ($_POST['show_online_counter']) {
				$options['show_online_counter'] = (bool)true;
			} else {
				$options['show_online_counter'] = (bool)false;
			}
			
			$options['footer_content']=stripslashes($_POST['footer_content']);
			
			//xing Enthusiastic commentator
			if (!$_POST['xing_show']) {
				$options['xing_show'] = (bool)false;
			} else {
				$options['xing_show'] = (bool)true;
			}
			$options['xing_limit_days'] = (int)($_POST['xing_limit_days']);
			$options['xing_limit_cm'] = (int)($_POST['xing_limit_cm']);
			

			update_option('philna_options', $options);

		} else {
			philnaOptions::getOptions();
		}

		add_theme_page(__('PhilNa Theme Settings','philna'), __('PhilNa Settings','philna'), 'edit_themes', basename(__FILE__), array('philnaOptions', 'display'));
	}

	function display() {
		$options = philnaOptions::getOptions();
?>
<div class="wrap">
<h2>PhilNa Theme Settings</h2>


<?php if(isset($_POST['philna_save'])):?>
<div class="updated below-h2" style="margin: 15px 0pt;"> <p> <strong><?php _e('Settings saved.','philna')?></strong> </p> </div>
<?php endif;?>
<div id="poststuff" class="dlm">

<form name="form0" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php include("about.php");?>

<div class="postbox open">
<h3><?php _e('Information','philna'); ?></h3>

<div class="inside" style="<?php if($options['authorname']=='' || $options['authorname']=='') echo 'color:red;';?>">

<table class="form-table">

<tr>
<th>
<?php _e('Information about this blog','philna'); ?><br/><br/>
<em><?php _e('You should set this if you want to use the contact.template page ','philna'); ?></em>
</th>
<td>
<?php _e('Your E_mail','philna')?><small><?php _e('(The mail form will send mail to you)', 'philna'); ?></small><br/>
<input type="text" name="authormail" id="authormail" class="code" size="40" value="<?php echo($options['authormail']); ?>"/><br/>
<?php _e('Your name:', 'philna'); ?><small><?php _e('(Will display in the meta and the e_mail)', 'philna'); ?></small><br/>
<input type="text" name="authorname" id="authorname" class="code" size="40" value="<?php echo($options['authorname']); ?>"/>
</td>
</tr>

</table>

</div>
</div>


<div class="postbox open">
<h3><?php _e('Header','philna'); ?></h3>

<div class="inside">
<table class="form-table">

<tr>
<th>
<?php _e('Main Meta','philna')?>
</th>
<td>
<?php _e('Keywords','philna')?><small><?php _e('( Separate keywords with commas )', 'philna'); ?></small><br/>
<input type="text" name="keywords" id="keywords" class="code" style="width:95%;" value="<?php echo($options['keywords']); ?>"><br/><br/>
<?php _e('Description:', 'philna'); ?><small><?php _e('( Main decription for your blog )', 'philna'); ?></small><br/>
<input type="text" name="description" id="description" class="code" style="width:95%;" value="<?php echo($options['description']); ?>">
</td>
</tr>

<tr>
<th>
<?php _e('Add browser title','philna')?>
</th>
<td>
<?php _e('Add browser title:','philna')?>
<input type="text" name="b_title_add" id="b_title_add" class="code" size="20" value="<?php echo($options['b_title_add']); ?>">
</td>
</tr>


<tr>
<th>
<?php _e('Search Settings','philna')?>
</th>
<td>
<input id="google_cse" name="google_cse" type="checkbox" value="checkbox" <?php if($options['google_cse']) echo "checked='checked'"; ?> />
<label for="google_cse"><?php _e('Using google custom search engine.', 'philna'); ?></label><br/>
<?php _e('CX:', 'philna');?>
<input type="text" name="google_cse_cx" id="google_cse_cx" class="code" size="60" value="<?php echo($options['google_cse_cx']);?>"><br/>
<?php _e('How to find the CX code?','philna')?><br/>
<?php _e('Find <code>name="cx"</code> in the <strong>Search box code</strong> of <a href="http://www.google.com/coop/cse/">Google Custom Search Engine</a>, and type the <code>value</code> here.<br/>For example: <code>011275726292926788974:fezfvqcwgmo</code>', 'philna'); ?>
</td>
</tr>


<tr>
<th>
<?php _e('Feed Settings','philna')?>
</th>
<td>
<input id="feed" name="feed" type="checkbox" value="checkbox" <?php if($options['feed']) echo "checked='checked'"; ?> />
<label for="feed"><?php _e('Use custom feed.', 'philna'); ?></label><br/>
<?php _e('Custom feed URL:', 'philna'); ?>
<input type="text" name="feed_url" id="feed_url" class="code" style="width:80%;" value="<?php echo($options['feed_url']); ?>"><br/>

<input id="feed_email" name="feed_email" type="checkbox" value="checkbox" <?php if($options['feed_email']) echo "checked='checked'"; ?> />
<label for="feed_email"><?php _e('Use E_mail feed.', 'philna'); ?></label><br/>
<?php _e('E_mail feed URL:', 'philna'); ?>
<input type="text" name="feed_url_email" id="feed_url_email" class="code" style="width:80%;" value="<?php echo($options['feed_url_email']); ?>"><br/>
<input id="rss_show_comment_state" name="rss_show_comment_state" type="checkbox" value="checkbox" <?php if($options['rss_show_comment_state']) echo "checked='checked'"; ?> />
<label for="rss_show_comment_state"><?php _e('Show comment state in Rss.', 'philna'); ?></label><br/>

<input id="rss_copyright_show" name="rss_copyright_show" type="checkbox" value="checkbox" <?php if($options['rss_copyright_show']) echo "checked='checked'"; ?> />
<label for="rss_copyright_show"><?php _e('Add custom text in your Rss such as copyright.', 'philna'); ?></label><br/>
<label for="rss_copyright"><?php _e('Rss custom text goes here.', 'philna'); ?></label><br/>
<textarea name="rss_copyright" id="rss_copyright" cols="50" rows="5" style="width:98%;font-size:12px;" class="code"><?php echo($options['rss_copyright']); ?></textarea>


</td>
</tr>

<tr>
<th>
<?php _e('Show welcome','philna')?>
</th>
<td>
<input id="welcome" name="show_welcome" type="checkbox" value="checkbox" <?php if($options['show_welcome']) echo "checked='checked'"; ?> />
<label for="welcome"><?php _e('Show welcome in the header','philna')?></label>
</td>
</tr>


</table>

</div>
</div>


<div class="postbox open">
<h3><?php _e('Notice','philna'); ?></h3>

<div class="inside">
<table class="form-table">

<tr>
<th>
<?php _e('Homepage notice settings','philna'); ?><br/>
<small style="font-weight:normal;"><?php _e('HTML enabled', 'philna'); ?></small>
</th>
<td>
<input id="notice" name="notice" type="checkbox" value="checkbox" <?php if($options['notice']) echo "checked='checked'"; ?> />
<label for="notice"><?php _e('This notice bar will display at the top of posts on homepage.', 'philna'); ?></label><br/>
<textarea name="notice_content" id="notice_content" cols="50" rows="10" style="width:98%;font-size:12px;" class="code"><?php echo($options['notice_content']); ?></textarea>
</td>
</tr>


<tr>
<th>
<?php _e('Sidebar notice settings','philna'); ?><br/>
<small style="font-weight:normal;"><?php _e('HTML enabled', 'philna'); ?></small>
</th>
<td>
<?php _e('This showcase will display at the top of sidebar.', 'philna'); ?><br/>
<?php _e('Who can see?', 'philna'); ?></br>
<label style="margin-left:15px;">
<input name="showcase_registered" type="checkbox" value="checkbox" <?php if($options['showcase_registered']) echo "checked='checked'"; ?> />
<?php _e('Registered Users', 'philna'); ?>
</label>

<label style="margin-left:15px;">
<input name="showcase_commentator" type="checkbox" value="checkbox" <?php if($options['showcase_commentator']) echo "checked='checked'"; ?> />
<?php _e('Commentator', 'philna'); ?>
</label>

<label style="margin-left:15px;">
<input name="showcase_visitor" type="checkbox" value="checkbox" <?php if($options['showcase_visitor']) echo "checked='checked'"; ?> />
<?php _e('Visitors', 'philna'); ?>
</label><br/>
<label>
<input name="showcase_caption" type="checkbox" value="checkbox" <?php if($options['showcase_caption']) echo "checked='checked'"; ?> />
<?php _e('Title:', 'philna'); ?></label>
<input type="text" name="showcase_title" id="showcase_title" class="code" size="30" value="<?php echo($options['showcase_title']); ?>">
<br/>
<textarea name="showcase_content" id="showcase_content" cols="50" rows="10" style="width:98%;font-size:12px;" class="code"><?php echo($options['showcase_content']); ?></textarea>
</td>
</tr>




</table>

</div>
</div>

<div class="postbox open">
<h3><?php _e('Comments','philna'); ?></h3>

<div class="inside">
<table class="form-table">
<tr>
<th>
<?php _e('Show Enthusiastic commentator','philna'); ?>
</th>
<td>
<input id="xing_show" name="xing_show" type="checkbox" value="checkbox" <?php if($options['xing_show']) echo "checked='checked'"; ?> />
<label for="xing_show"><?php _e('Show Enthusiastic commentator','philna'); ?></label><br/>
<label for="xing_limit_days"><?php _e('Limit days:','philna')?></label><select id="xing_limit_days" name="xing_limit_days">
<option value="7" <?php if($options['xing_limit_days']==5) echo 'selected="selected"';?>><?php _e('7 days','philna')?></option>
<option value="10" <?php if($options['xing_limit_days']==10) echo 'selected="selected"';?>><?php _e('10 days','philna')?></option>
<option value="15" <?php if($options['xing_limit_days']==15) echo 'selected="selected"';?>><?php _e('15 days','philna')?></option>
<option value="30" <?php if($options['xing_limit_days']==30) echo 'selected="selected"';?>><?php _e('30 days','philna')?></option>
</select>
<label for="xing_limit_cm"><?php _e('Limit commtent times:','philna')?></label><select id="xing_limit_cm" name="xing_limit_cm">
<option value="5" <?php if($options['xing_limit_cm']==5) echo 'selected="selected"';?>><?php _e('5 times','philna')?></option>
<option value="10" <?php if($options['xing_limit_cm']==10) echo 'selected="selected"';?>><?php _e('10 times','philna')?></option>
<option value="15" <?php if($options['xing_limit_cm']==15) echo 'selected="selected"';?>><?php _e('15 times','philna')?></option>
<option value="20" <?php if($options['xing_limit_cm']==20) echo 'selected="selected"';?>><?php _e('20 times','philna')?></option>
</select>
</td>
</tr>
</table>
</div>
</div>

<div class="postbox open">
<h3><?php _e('Footer','philna'); ?></h3>

<div class="inside">

<table class="form-table">

<tr>
<th>
<?php _e('Show the online counter','philna'); ?>
</th>
<td>
<input id="Show_the_online_counter" name="show_online_counter" type="checkbox" value="checkbox" <?php if($options['show_online_counter']) echo "checked='checked'"; ?> />
<label for="Show_the_online_counter"><?php _e('Show (Make sure the websit directory you can write )','philna'); ?></label>
</td>
</tr>


<tr>
<th>
<?php _e('Footer Insert:','philna'); ?><br/>
<em>
<br/><?php _e('Such as google Analytics code, </br>Tongji Beian hao and so on ','philna')?></em><br/>
<br/><small style="font-weight:normal;"><?php _e('HTML enabled', 'philna'); ?></small>
</th>
<td>
<textarea name="footer_content" id="footer_content" cols="50" rows="5" style="width:98%;font-size:12px;" class="code"><?php echo($options['footer_content']); ?></textarea>
</td>
</tr>

</table>

</div>
</div>



<p class="submit">
<input class="button-primary" type="submit" name="philna_save" value="<?php _e('Save Changes', 'philna'); ?>" />
</p>

</form>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="alignright">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHLwYJKoZIhvcNAQcEoIIHIDCCBxwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAtUHpkjsaq+1cfP+VBgx6nFljuqIb808eZu0HC5q7QLYXCqfnuXzcj2vtiqNgcAdDWHr6eq+IJHHQP8GBPKEmoQ8orI6FU+JXSOhrRtYYlmqDsJHN98Y/vqj2z38jPIaSRu1/u4crMPNjUxcjYcKsZqpO1EH354zw5QQPAJS+AGjELMAkGBSsOAwIaBQAwgawGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQI2ktGFex6NTSAgYijgG3Jy6lXqpsOOokJIQQH6rjnewzfprnFy/ilgOaFKLQ+OOHHltdAWjwn3QIK82ObQO0eT8Vw/TjYuCGpV7oROu6zmuOHMJhncReM3APL2q19V4bZU99HIIeLLHOYLVH25HpPNYUVuPW4cPzz1HdvimJZlqxsjWeMBQWugVD/ZQTFvvP83Nq7oIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMDkwMzA0MTI0NDM3WjAjBgkqhkiG9w0BCQQxFgQUHRVhu9WJy2rXpuAX+MdMPIn4cfcwDQYJKoZIhvcNAQEBBQAEgYBEvbyu8lszUdNbCZvz5Bpd/Khz1QylMKh2lXIHCwUbqgtO7nKhjLHmF43VbNdJ5bAvofPYo7I/4NNdSibCLU3Cp1riY8023F7baCWOdFU/i7NjkWvTK4Q7R7eFPV9MAJ0um9W5KsqdkJJPLHvq5A8CdCxRch6QsaAaDxPHnhoHHg==-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/zh_XC/i/scr/pixel.gif" width="1" height="1">
</form>
<div><?php _e('If you find my work useful and you want to encourage the development of more free resources, you can do it by donating...','philna')?></div>


</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
<script type="text/javascript"> $(document).ready(function(){ $("#toggleabout").click(function(){ $("#aboutphilna").slideToggle()})}); </script>
</div>
<?php
	}
}
?>