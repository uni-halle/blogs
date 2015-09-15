<?php
	$width = ($columns * $size) + (($columns - 1) *  $padding);
	$height = ($rows * $size) + (($rows - 1) * $padding);
	$height = $height + 30;
?>

<!-- INK361 Widget -->
<script type="text/javascript">
	(function() { 
		var a = document.createElement('script'); 
		a.type='text/javascript'; 
		a.async=true; 
		a.src="https://e9bf41efcee6f23a660c-e3499556a2d3fd28f2809418521a162b.ssl.cf2.rackcdn.com/widget.js"; 
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(a,s); 
	})();
</script>
<div id="inkPreviewWidget" style="width: <?php echo $width ?>px; height: <?php echo $height ?>px; margin: 0px auto;">
	<?php
		$id = str_replace("#", "", $id);
		$idIg = str_replace("ig-", "", $id);
	?>

	<div class="inkWidget" 
		data-id="<?php 			echo $id 		?>" 
		data-type="<?php 		echo $type 		?>" 
		data-size="<?php 		echo $size 		?>" 
		data-background="<?php 		echo $background 	?>" 
		data-padding="<?php		echo $padding 		?>" 
		data-x="<?php			echo $columns 		?>" 
		data-y="<?php			echo $rows 		?>" 
		data-border="<?php		echo $border		?>">
		<?php
			if ($type === 'user') {
		?>
			<a href="https://ink361.com/app/users/ig-<?php echo $idIg ?>/unknown/photos" target="_blank" alt="View instagrams" title="View instagrams">
				view instagram gallery
			</a>
		<?php
			} else if ($type === 'tag') {
		?>
			<a href="https://ink361.com/app/tag/<?php echo $id ?>" target="_blank" alt="View #<?php echo $id ?> Instagrams" title="View #<?php echo $id ?> Instagrams">
				view #<?php echo $id ?> instagrams
			</a>
		<?php
			} else {
		?>
			<a href="https://ink361.com/app/popular" target="_blank" alt="View popular Instagrams" title="View popular Instagrams">
				popular instagrams
			</a>
		<?php
			}
		?>
	</div>
</div>
<!-- END INK361 Widget --> 
