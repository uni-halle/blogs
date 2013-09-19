<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	
	<div>
    	<label for="s" class="hide">Search</label><br />
    	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
	</div>
		
	<p class="clearfix submit">
	   [<input name="submit" type="submit" id="searchsubmit" value="Go" />]
    </p>
    
</form>
