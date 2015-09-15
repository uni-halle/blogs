<script type="text/javascript">
	(function() { 
		var a = document.createElement('script'); 
		a.type='text/javascript'; 
		a.async=true; 
		a.src="https://e9bf41efcee6f23a660c-e3499556a2d3fd28f2809418521a162b.ssl.cf2.rackcdn.com/embed.js"; 
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(a,s); 
	})();
</script>
<div class="inkEmbed" 
	data-id="<?php echo $id ?>">
	<?php
		$parts = explode('_', $id);
		$id = str_replace("ig-", "", $id);
	?>

	<a href="https://ink361.com/app/users/ig-<?php echo $parts[1] ?>/unknown/photos/ig-<?php echo $id ?>" target="_blank">View original instagram</a> or visit <a href="https://ink361.com" target="_blank">INK361</a>
</div>
