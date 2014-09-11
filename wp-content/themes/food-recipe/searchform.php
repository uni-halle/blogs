<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" id="searchformfield" value="<?php the_search_query(); ?>" name="s" id="s" /><br />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>
