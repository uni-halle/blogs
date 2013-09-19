<div id="search">
	<form id="searchform" action="<?php bloginfo('url'); ?>" method="get">
		<input type="text" id="searchinput" name="s" class="searchinput" value="search" onfocus="if (this.value == 'search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search';}" />
		<input type="submit" id="searchsubmit" class="button" value="" />
	</form>
</div>