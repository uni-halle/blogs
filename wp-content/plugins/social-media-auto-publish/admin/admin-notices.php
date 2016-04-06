<?php
function wp_smap_admin_notice()
{
	add_thickbox();
	$sharelink_text_array = array
						(
						"I use Social Media Auto Publish wordpress plugin from @xyzscripts and you should too.",
						"Social Media Auto Publish wordpress plugin from @xyzscripts is awesome",
						"Thanks @xyzscripts for developing such a wonderful social media auto publishing wordpress plugin",
						"I was looking for a social media publishing plugin and I found this. Thanks @xyzscripts",
						"Its very easy to use Social Media Auto Publish wordpress plugin from @xyzscripts",
						"I installed Social Media Auto Publish from @xyzscripts,it works flawlessly",
						"Social Media Auto Publish wordpress plugin that i use works terrific",
						"I am using Social Media Auto Publish wordpress plugin from @xyzscripts and I like it",
						"The Social Media Auto Publish plugin from @xyzscripts is simple and works fine",
						"I've been using this social media plugin for a while now and it is really good",
						"Social Media Auto Publish wordpress plugin is a fantastic plugin",
						"Social Media Auto Publish wordpress plugin is easy to use and works great. Thank you!",
						"Good and flexible  social media auto publish plugin especially for beginners",
						"The best social media auto publish wordpress plugin I have used ! THANKS @xyzscripts",
						);
$sharelink_text = array_rand($sharelink_text_array, 1);
$sharelink_text = $sharelink_text_array[$sharelink_text];

	
	echo '<div id="smap_notice_td" style="clear:both;width:98%;background: none repeat scroll 0pt 0pt #FBFCC5; border: 1px solid #EAEA09;padding:5px;">
	<p>It looks like you have been enjoying using <a href="https://wordpress.org/plugins/social-media-auto-publish/" target="_blank"> Social Media Auto Publish</a> plugin from Xyzscripts for atleast 30 days.  Would you consider supporting us with the continued development of the plugin using any of the below methods ?</p>
	<p>
	<a href="https://wordpress.org/support/view/plugin-reviews/social-media-auto-publish" class="button" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Rate it 5★\'s on wordpress</a>
	<a href="http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/purchase" class="button" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Purchase premium version</a>';
	if(get_option('xyz_credit_link')=="0")
		echo '<a class="button xyz_smap_backlink" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Enable backlink</a>';
	
	echo '<a href="#TB_inline?width=250&height=75&inlineId=show_share_icons_smap" class="button thickbox" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Share on</a>
	
	<a href="admin.php?page=social-media-auto-publish-settings&smap_notice=hide" class="button" style="color:black;text-decoration:none;margin-right:4px;">Don\'t Show This Again</a>
	</p>
	
	<div id="show_share_icons_smap" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">Facebook</a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/&text='.$sharelink_text.'" target="_blank">Twitter</a>
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">LinkedIn</a>
	<a class="button" style="background-color:#dd4b39;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="https://plus.google.com/share?&hl=en&url=http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">Google +</a>
	</div>
	</div>';
	
	
}
$smap_installed_date = get_option('smap_installed_date');
if ($smap_installed_date=="") {
	$smap_installed_date = time();
}
if($smap_installed_date < ( time() - (30*24*60*60) ))
{
	if (get_option('xyz_smap_dnt_shw_notice') != "hide")
	{
		add_action('admin_notices', 'wp_smap_admin_notice');
	}
}
?>