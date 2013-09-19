	<div id="search" class="widget">
	
		<h3>Search</h3>

    	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
        <div>
        <input type="text" class="field" name="s" id="s"  value="<?php _e('Enter keywords...',woothemes); ?>" onfocus="if (this.value == '<?php _e('Enter keywords...',woothemes); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Enter keywords...',woothemes); ?>';}" />
        <input type="submit" class="search_btn" value="<?php _e('SEARCH',woothemes); ?>" name="submit" />
        </div>
    	</form>
    	
    	<div class="fix"></div>
	
	</div><!--search-->