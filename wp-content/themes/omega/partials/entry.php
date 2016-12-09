<?php
if ( is_home() || is_archive() || is_search() ) {
?>	
	<div <?php omega_attr( 'entry-summary' ); ?>>
<?php 		
	if( get_theme_mod( 'post_thumbnail', 1 ) && has_post_thumbnail()) {

		if ( ! class_exists( 'Get_The_Image' ) ) {
			apply_filters ( 'omega_featured_image' , printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_post_thumbnail(get_the_ID(), get_theme_mod( 'image_size' ), array('class' => get_theme_mod( 'image_size' )) ) ));
		} else {
			get_the_image( array( 'size' => get_theme_mod( 'image_size' ) ) );		
		}	
	}

	if ( 'excerpts' === get_theme_mod( 'post_excerpt', 'excerpts' ) ) {
		if ( get_theme_mod( 'excerpt_chars_limit', 0 ) ) {
			the_content_limit( (int) get_theme_mod( 'excerpt_chars_limit' ), get_theme_mod( 'more_text', '[Read more...]' ) );
		} else {
			the_excerpt();
		}
	}
	else {
		the_content( get_theme_mod( 'more_text' ) );
	}
?>	
	</div>
<?php 	
} else {
?>	
	<div <?php omega_attr( 'entry-content' ); ?>>
<?php 	
	the_content();
	wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'omega' ) . '</span>', 'after' => '</p>' ) );
?>	
	</div>
<?php 	
}
?>