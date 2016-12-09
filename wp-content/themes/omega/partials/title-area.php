<?php
echo '<div class="' . omega_apply_atomic( 'title_area_class', 'title-area') .'">';

/* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */	
if ( $title = get_bloginfo( 'name' ) ) {		
	if ( $logo = get_theme_mod( 'custom_logo' ) ) {
		$title = sprintf( '<div itemscope itemtype="http://schema.org/Organization" class="site-title"><a itemprop="url" href="%1$s" title="%2$s" rel="home"><img itemprop="logo" alt="%3$s" src="%4$s"/></a></div>', home_url(), esc_attr( $title ), esc_attr( $title ), $logo );		
	} else {
		if (is_home()) {
			$title = sprintf( '<h1 class="site-title" itemprop="headline"><a href="%1$s" title="%2$s" rel="home">%3$s</a></h1>', home_url(), esc_attr( $title ), $title );		
		} else {
			$title = sprintf( '<h2 class="site-title" itemprop="headline"><a href="%1$s" title="%2$s" rel="home">%3$s</a></h2>', home_url(), esc_attr( $title ), $title );		
		}
	}
}

/* Display the site title and apply filters for developers to overwrite. */
echo omega_apply_atomic( 'site_title', $title );

/* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
if ( $desc = get_bloginfo( 'description' ) )
	$desc = sprintf( '<h3 class="site-description"><span>%1$s</span></h3>', $desc );

/* Display the site description and apply filters for developers to overwrite. */
echo omega_apply_atomic( 'site_description', $desc );

echo '</div>';
?>