<div id="sidebar">
	<ul>
		<li class="large-avatar"><?php slf_avatar(); ?></li>
		<?php slf_info(); ?>
		
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
			<li class="widget">
				<h2 class="widgettitle">Archives</h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>
		<?php endif; ?>
	</ul>
</div>

<?php if(get_option('slf_sidebarad') == 1) { ?>
<div id="sidebarright">
	<ul>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
			<li class="widget">
				<h2 class="widgettitle">Advertise</h2>
				<img src="<?php bloginfo('template_directory') ?>/screenshot.png" alt="Smells Like Facebook" width="110" /><br/><br/>
				Smells Like Facebook is created by <a href="http://nazieb.com">Ainun Nazieb</a>
			</li>
		<?php endif; ?>
	</ul>
</div>
<?php } ?>