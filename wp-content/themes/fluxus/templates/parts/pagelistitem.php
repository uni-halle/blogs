<?php 
	// get last defined thumbnail
	$thumbnail = "";
	if (has_post_thumbnail($page->ID)) {
		$thumbnail = get_the_post_thumbnail_url( $page->ID, 'full', array('class' => 'pagelist__img') );
		$oldthumbnail = $thumbnail;
		$j = 1;
	} else {
		$thumbnail = $oldthumbnail;
		$j++;
	}
	// get last defined addimage
	$addimg = "";
	if (get_post_meta($page->ID, 'fluxusaddimage', true)) {
		$addimg = get_fluxus_addimage_url($page);
		$oldaddimg = $addimg;
	} else {
		$addimg = $oldaddimg;
	}
?>


<article id="pagelist__anchor--<?php echo $page->ID; ?>" class="pagelist__item typo section <?php if(get_page_template_slug( $page->ID )) { echo pathinfo(get_page_template_slug( $page->ID ))['filename']; } ?> " key="<?php echo $i; ?>" key="<?php echo $i; ?>" zoomcount="<?php echo $j; ?>" thumbnail="<?php echo $thumbnail; ?>" addimg="<?php echo $addimg; ?>">
	<div class="pagelist__content">
		<!--<span class="pagelist__title"><?php //echo $page->post_title; ?></span>-->
		<?php echo apply_filters('the_content', $page->post_content); ?>
	</div>
</article>