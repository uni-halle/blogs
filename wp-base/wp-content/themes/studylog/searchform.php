<?php
if(!isset($_REQUEST['nofl'])){
;?>

<form method="get" id="searchform" action="javascript:callStudyLogSortBySearch(document.forms.searchform.s.value)">
<label class="hidden" for="s"><?php _e('Search for:'); ?></label>
<div>
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="Suche" /></div>
</form>
<?php
} ?>

<?php
if(isset($_REQUEST['nofl'])){
;?>

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<label class="hidden" for="s"><?php _e('Search for:'); ?></label>
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="Suche" />
<input type="hidden" name="nofl" value="yes" />
</div>
</form>
<?php
} ?>