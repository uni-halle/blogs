<?php echo('<?xml version="1.0" encoding="UTF-8"?>');?>
<rss version='2.0' xmlns:media='http://search.yahoo.com/mrss/'>
	<channel>
		<generator><![CDATA[<?php esc_html_e($generator)?>]]></generator>
		<title><?php esc_html_e($feed_title) ?></title>
		<description><?php esc_html_e($feed_description) ?></description>
		<link><![CDATA[<?php echo nextgen_esc_url($feed_link)?>]]></link>
		<?php foreach($images as $image): ?>
		<?php
			$image_url  = $storage->get_image_url($image, 'full', TRUE);
			$thumb_url  = $storage->get_thumb_url($image, TRUE);
			$thumb_size = $storage->get_thumb_dimensions($image);
			$width		= $thumb_size['width'];
			$height		= $thumb_size['height'];
		?>
		<item>
			<title><![CDATA[<?php esc_html_e($image->alttext)?>]]></title>
			<description><![CDATA[<?php esc_html_e($image->description)?>]]></description>
			<link><![CDATA[<?php echo nextgen_esc_url($image_url)?>]]></link>
			<guid>image-id:<?php esc_html_e($image->id_field)?></guid>
			<media:content url="<?php echo nextgen_esc_url($image_url)?>" medium="image" />
			<media:title><![CDATA[<?php esc_html_e($image->alttext)?>]]></media:title>
			<?php if (isset($description)): ?>
			<media:description><![CDDATA[<?php esc_html_e($image->description)?>]]></media:description>
			<?php endif ?>
			<media:thumbnail width="<?php esc_attr_e($width)?>" height="<?php esc_attr_e($height)?>" url="<?php echo nextgen_esc_url($thumb_url) ?>"/>
			<?php if (isset($tagnames)): ?>
			<media:keywords><![CDATA[<?php esc_html_e($tagnames)?>]]></media:keywords>
			<?php endif ?>
			<media:copyright><![CDATA[<?php esc_html_e($copyright)?>]]></media:copyright>
		</item>
		<?php endforeach ?>
	</channel>
</rss>