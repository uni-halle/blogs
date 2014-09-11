<?php $searchText = __('search', 'elegant-grunge'); ?>
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<div>
		<input type="text" value="<?php echo htmlspecialchars(get_search_query() ? get_search_query() : $searchText ); ?>" onfocus="if (this.value == '<?php echo htmlspecialchars($searchText) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo htmlspecialchars($searchText) ?>';}"  name="s" id="s" />
		<input type="submit" id="searchsubmit" value="<?php _e('Go', 'elegant-grunge') ?>" />
	</div>
</form>
