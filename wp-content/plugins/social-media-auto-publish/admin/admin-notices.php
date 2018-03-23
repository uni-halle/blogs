<?php
if( !defined('ABSPATH') ){ exit();}
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
$xyz_smap_link = admin_url('admin.php?page=social-media-auto-publish-settings&smap_blink=en');
$xyz_smap_link = wp_nonce_url($xyz_smap_link,'smap-blk');
$xyz_smap_notice = admin_url('admin.php?page=social-media-auto-publish-settings&smap_notice=hide');
$xyz_smap_notice = wp_nonce_url($xyz_smap_notice,'smap-shw');
	echo '
	<script type="text/javascript">
			function xyz_smap_shareon_tckbox(){
			tb_show("Share on","#TB_inline?width=500&amp;height=75&amp;inlineId=show_share_icons_smap&class=thickbox");
		}
	</script>
	<div id="smap_notice_td" class="error" style="color: #666666;margin-left: 2px; padding: 5px;line-height:16px;">
	<p>Thank you for using <a href="https://wordpress.org/plugins/social-media-auto-publish/" target="_blank"> Social Media Auto Publish </a> plugin from <a href="https://xyzscripts.com/" target="_blank">xyzscripts.com</a>. Would you consider supporting us with the continued development of the plugin using any of the below methods?</p>
	<p>
	<a href="https://wordpress.org/support/plugin/social-media-auto-publish/reviews" class="button xyz_rate_btn" target="_blank">Rate it 5★\'s on wordpress</a>';
	if(get_option('xyz_credit_link')=="0")
		echo '<a href="'.$xyz_smap_link.'" class="button xyz_backlink_btn xyz_blink">Enable Backlink</a>';
	
	echo '<a class="button xyz_share_btn" onclick=xyz_smap_shareon_tckbox();>Share on</a>
		<a href="https://xyzscripts.com/donate/5" class="button xyz_donate_btn" target="_blank">Donate</a>
	
	<a href="'.$xyz_smap_notice.'" class="button xyz_show_btn">Don\'t Show This Again</a>
	</p>
	
	<div id="show_share_icons_smap" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">Facebook</a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/&text='.$sharelink_text.'" target="_blank">Twitter</a>
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">LinkedIn</a>
	<a class="button" style="background-color:#dd4b39;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="https://plus.google.com/share?&hl=en&url=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank">Google +</a>
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