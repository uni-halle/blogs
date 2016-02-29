<?php



function powerpress_admin_import_feed()
{
	?>
<h2><?php echo __('Import podcast feed to WordPress', 'powerpress'); ?></h2>
<p><?php echo __('The following tool will import your podcast episodes to this website.', 'powerpress'); ?></p>

<ul><li>
	<h3><?php echo __('Import from specific service', 'powerpress'); ?></h3>
	<ul>
		<li><strong><a href="<?php echo admin_url("admin.php?import=powerpress-soundcloud-rss-podcast"); ?>"><?php echo __('Podcast from SoundCloud', 'powerpress'); ?></a></strong></li>
		<li><strong><a href="<?php echo admin_url("admin.php?import=powerpress-libsyn-rss-podcast"); ?>"><?php echo __('Podcast from LibSyn', 'powerpress'); ?></a></strong></li>
		<li><strong><a href="<?php echo admin_url("admin.php?import=powerpress-podbean-rss-podcast"); ?>"><?php echo __('Podcast from PodBean', 'powerpress'); ?></a></strong></li>
		<li><strong><a href="<?php echo admin_url("admin.php?import=powerpress-squarespace-rss-podcast"); ?>"><?php echo __('Podcast from Squarespace', 'powerpress'); ?></a></strong></li>
	</ul>
	<h3><?php echo __('Import from anywhere else', 'powerpress'); ?></h3>
	<ul>
		<li><strong><a href="<?php echo admin_url("admin.php?import=powerpress-rss-podcast"); ?>"><?php echo __('Podcast RSS Feed', 'powerpress'); ?></a></strong></li>
	</ul>
</li></ul>
<p><?php echo sprintf(__('Importing your feed does not migrate your media files. Please use the %s tool to migrate your media once your feed is imported.', 'powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>'); ?></p>

<br /><br />
<?php
}

// eof