<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="field" />
<input type="submit" id="searchsubmit" value="Go" class="button" />
</form>
