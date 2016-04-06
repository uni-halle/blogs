<?php if(get_option('xyz_smap_premium_version_ads')==1){?>
<div id="xyz-wp-smap-premium">

	<div style="float: left; padding: 0 5px">
		<h2 style="vertical-align: middle;">
			<a target="_blank"
				href="http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features">Fully
				Featured XYZ WP SMAP Premium Plugin</a> - Just 29 USD
		</h2>
	</div>
	<div style="float: left; margin-top: 3px">
		<a target="_blank"
			href="http://xyzscripts.com/members/product/purchase/XYZWPSMPPRE"><img class="hoverImages"
			src="<?php  echo plugins_url("social-media-auto-publish/admin/images/orange_buynow.png"); ?>">
		</a>
	</div>
	<div style="float: left; padding: 0 5px">
	<h4 style="vertical-align: middle;text-shadow: 1px 1px 1px #686868">
			( <a 	href="<?php echo admin_url('admin.php?page=social-media-auto-publish-about');?>">Compare Features</a> ) 
			<span style="color:red;font-weight:bold; font-size:14px;">* Pinterest and Google+ added *</span>
	</h4>		
	</div>
</div>
<?php }?>

<?php 
if($_POST && isset($_POST['xyz_credit_link']))
{
	
	$xyz_credit_link=$_POST['xyz_credit_link'];
	
	update_option('xyz_credit_link', $xyz_credit_link);
	?>
<div class="system_notice_area_style1" id="system_notice_area">
	Settings updated successfully. &nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
	<?php 
}?>


<?php 

if(get_option('xyz_credit_link')=="0"){
	?>
<div style="float:left;background-color: #FFECB3;border-radius:5px;padding: 0px 5px;margin-top: 10px;border: 1px solid #E0AB1B" id="xyz_backlink_div">

	Please do a favour by enabling backlink to our site. <a class="xyz_smap_backlink" style="cursor: pointer;" >Okay, Enable</a>.
<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('.xyz_smap_backlink').click(function() {

		var dataString = { 
				action: 'xyz_smap_ajax_backlink', 
				enable: 1 
			};

		jQuery.post(ajaxurl, dataString, function(response) {
			jQuery('.xyz_smap_backlink').hide();
			jQuery("#xyz_backlink_div").html('Thank you for enabling backlink !');
			jQuery("#xyz_backlink_div").css('background-color', '#D8E8DA');
			jQuery("#xyz_backlink_div").css('border', '1px solid #0F801C');
		});

});
});
</script>
</div>
	<?php 
}



?>


 
<div style="margin-top: 10px">
<table style="float:right; ">
<tr>
<td  style="float:right;">
	<a title="Please help us to keep this plugin free forever by donating a dollar"   class="xyz_smap_link" style="margin-right:12px;"  target="_blank" href="http://xyzscripts.com/donate/1">Donate</a>
</td>
<td style="float:right;">
	<a class="xyz_smap_link"  target="_blank" href="http://kb.xyzscripts.com/wordpress-plugins/social-media-auto-publish/">FAQ</a> | 
</td>
<td style="float:right;">
	<a class="xyz_smap_link"  target="_blank" href="http://docs.xyzscripts.com/wordpress-plugins/social-media-auto-publish/">Readme</a> | 
</td>
<td style="float:right;">
	<a class="xyz_smap_link"  target="_blank" href="http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/details">About</a> | 
</td>
<td style="float:right;">
	<a class="xyz_smap_link"  target="_blank" href="http://xyzscripts.com">XYZScripts</a> |
</td>

</tr>
</table>
</div>


<div style="clear: both"></div>