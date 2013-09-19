<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/" class="box">
    <fieldset>
      <label for="s">Search</label>
      <input type="text" name="s" id="s" value="<?php the_search_query(); ?>" class="input-txt" /><input id="searchsubmit" value="Search" type="submit" class="submit" />
    </fieldset>
</form>
