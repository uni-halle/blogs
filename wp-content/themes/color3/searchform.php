<form action="<?php bloginfo('home'); ?>/" method="post" >
	
	<div id="search-box">	<input type="text" value="<?php _e('Search...', 'default'); ?>" class="textfield" name="s" id="search-text"  
onblur="if(this.value=='') this.value='<?php _e('Search...', 'default'); ?>';" onfocus="if(this.value=='<?php _e('Search...', 'default'); ?>') this.value='';"/>
</div>
</form>


