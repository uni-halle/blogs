<?php global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<div id="topbox">
	<div class="welcome_m">
		<h2 class="topbox_t">
		<?php if($feusional_welcome_title)  echo $feusional_welcome_title;  else  echo "Hello and welcome to my website!";  ?>
	 	</h2>
		<p class="topbox_desc"><?php if($feusional_welcome_desc)  echo $feusional_welcome_desc;  else  echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip."; ?></p> 
	</div><!--end of .welcome_m -->

	<div id="twitter_div">
 		<div class="twitter_text">
		<?php if($feusional_notwitter == "false") { ?>
			<h3 class="follow"><a title="Follow me on Twitter!" href="http://www.twitter.com/<?php echo $feusional_twitter;?>">Twitter/<?php echo $feusional_twitter;?></a></h3>
			<ul id="twitter_update_list"></ul>
			<br />
		<?php } else echo "";?>
		</div>
	</div><!--end of #twitter_div -->
</div><!--end of #topbox-->