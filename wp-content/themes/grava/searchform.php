<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
  <div class="searchformdiv">
    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="&raquo;&raquo;&raquo;" title="<?php bloginfo('name'); ?> durchsuchen" />
  </div>
</form>

