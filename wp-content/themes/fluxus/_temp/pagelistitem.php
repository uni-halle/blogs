<article class="pagelist__item typo">
	<div class="pagelist__head">
		
		<?php 
			$thumbnail = "";
			if (has_post_thumbnail($page->ID)) {
				$thumbnail = get_the_post_thumbnail( $page->ID, 'full', array('class' => 'pagelist__imgbreakimg') );
			} else {
				//search for ancestors thumbnails
				$ancestors = get_ancestors($page->ID, 'page');
				$ancestors = array_reverse($ancestors);
				if($ancestors){
					foreach($ancestors as $ancestor){
						if (has_post_thumbnail($ancestor)) {
							$thumbnail = get_the_post_thumbnail( $ancestor, 'full', array('class' => 'pagelist__imgbreakimg') );
						}
					}
				}
			}
			echo $thumbnail;
		?>
		
		<h1 class="pagelist__title"><?php echo $page->post_title; ?></h1>
	</div>
	<div class="pagelist__content">
		<?php echo apply_filters('the_content', $page->post_content); ?>
	</div>
</article>