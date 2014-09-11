<?php if (!current_theme_supports('wdgpo_activities-style')) { ?>
<style type="text/css">
.wdgpo-activity-meta {
	float: left;
	width: 100px;
	background-color: #eee;
	border: 1px solid #ccc;
	padding: 5px;
	text-align: right;
	font-size: 0.9em;
}
.wgdpo-activities-activity_content {
	float: left;
	width: 80%;
	padding-left: 5px;
}
.wdgpo-activity {
	clear: both;
	margin-bottom: 2em;
}
.wgdpo-activities-author_info a {
	text-decoration: none;
}
.wdgpo-activity-meta-author_info-display_name {
	display: block;
}
</style>
<?php } ?>

<?php
	global $post;
	$oldpost = $post;
	$datetime_format = get_option('date_format') . ' ' . get_option('time_format');
?>

<div class="wdgpo-activities">
<?php foreach ($activities as $activity) { ?>
	<?php $post = $activity; ?>
	<?php $author = get_post_meta($activity->ID, 'wdgpo_gplus_author', true); ?>
	<div class="wdgpo-activity">
		<h3>
			<a href="<?php echo (('wdgpo_imported_post' == $activity->post_type) ? get_post_meta($activity->ID, 'wdgpo_gplus_item_id', true) : get_permalink($activity->ID));?>">
				<?php echo $activity->post_title;?>
			</a>
		</h3>
		<div class="wdgpo-activity-meta">
			<div class="wgdpo-activities-author_info">
				<a href="<?php echo @$author['url'];?>">
					<?php if ($args['show_faces'] && $author) { ?>
						<img src="<?php echo @$author['image']['url'];?>" />
					<?php } ?>
					<span class="wdgpo-activity-meta-author_info-display_name"><?php echo @$author['displayName'];?></span>
				</a>
			</div>
			<div class="wgdpo-activities-activity_meta">
				<div><?php echo mysql2date($datetime_format, $activity->post_date);?></div>
				<div>
				<?php
					$tags = array();
					foreach (wp_get_post_tags($activity->ID) as $tag) {
						$link = ('wdgpo_imported_post' == $activity->post_type)
							? get_tag_link($tag->term_id)
							: strstr($tag->name, '#')
								? 'https://plus.google.com/b/' . get_post_meta($activity->ID, 'wdgpo_gplus_feed_id', true) . '/s/' . urlencode($tag->name)
								: get_tag_link($tag->term_id)
						;
						$tags[] = '<a href="' . $link . '">' . $tag->name . '</a>';
					}
					if ($tags) echo join(', ', $tags);
				?>
				</div>
			</div>
		</div>
		<div class="wgdpo-activities-activity_content">
			<?php echo apply_filters('the_content', $activity->post_content);?>
		</div>
		<div style="clear:both"></div>
	</div> <!-- .activity -->
<?php } ?>
</div>

<?php
	$post = $oldpost;
?>