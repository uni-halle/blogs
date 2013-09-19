	<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
		<input type="text" value="Search..." name="s" id="s" onblur="if (this.value == '') {this.value = 'Search...';}" onfocus="if (this.value == 'Search...') {this.value = '';}" />
		<input type="hidden" id="searchsubmit" />
	</form>