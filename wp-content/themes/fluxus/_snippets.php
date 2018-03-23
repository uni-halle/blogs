<?php





	echo get_post_meta( $post->ID, '_subtitle', true );



	get_template_part('/templates/parts/addgallery-slideshow', ''); 
	get_template_part('/templates/parts/addgallery-masonry', ''); 



	// to solve the pagination problem with posts_pagination on static-frontpage with postlist
	// set home-page via wp-backend to blockpost-list
	// use this redirect-function in index.php as the only content
	wp_redirect(get_permalink(get_page_by_title("YOUR FRONTPAGE TITLE")));





?>





<a class="backlink" href='<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>'>zurück</a>
