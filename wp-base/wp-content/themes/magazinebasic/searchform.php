<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div>
<input type="text" class="search_input" value="Suchwort + [Enter]" name="s" id="s" onfocus="if (this.value == 'Suchwort + [Enter]') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Suchwort + [Enter]';}" />
<input type="hidden" id="searchsubmit" value="suchen" />
</div>
</form>
