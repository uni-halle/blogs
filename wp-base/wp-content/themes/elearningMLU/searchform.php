<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<div>
	<input type="text" class="txt" name="s" id="s" value="Suche nach..." onfocus="if (this.value == 'Suche nach...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Suche nach...';}" />
	<button type="submit" class="submit" value="">Los</button>
	</div>
	</form>
