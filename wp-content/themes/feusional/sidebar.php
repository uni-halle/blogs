<?php global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<div id="sidebarwrap" class="fleft">
	<div id="sidebarone" class="fleft">
		<div class="intro">
			<img alt="intoduction" src="<?php if($feusional_intro_pic) echo $feusional_intro_pic; else echo bloginfo('template_url').'/images/intropic.gif'; ?>" />
			<p><?php echo $feusional_intro_desc; ?></p>
		</div>
		
		<ul>
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
		<!-- here is content(widgets) for sidebar 1 -->
		<?php endif; ?>
		</ul>
	</div> 
 
	<div id="sidebartwo" class="fright">
		<ul>
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
		<!-- here is content(widgets) for sidebar 2 -->
		<?php endif; ?>
		</ul>
	</div> 
</div><!--end of #sidebarwrap-->   