<div class="search-box">
	<form action="<?php bloginfo('siteurl');?>" method="get">
		<div>
			<input type="text" onblur="clickrecall(this,'Search.')" onclick="clickclear(this, 'Search.')" value="Search." name="s" id="search" class="search-input"/>
			<input type="submit" value="Go" class="search-button"/>
		</div>
	</form>
</div>