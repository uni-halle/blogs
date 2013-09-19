



<form method="get" id="search-box" action="<?php echo home_url(); ?>" >
	<div class="search-wrapper">
		<input type="text" value="<?php _e('Search...', 'magazinetheme'); ?>" class="textfield" name="s" id="search-text" onblur="if(this.value=='') this.value='<?php _e('Search...', 'magazinetheme'); ?>';" onfocus="if(this.value=='<?php _e('Search...', 'magazinetheme'); ?>') this.value='';" />
	</div>
</form>