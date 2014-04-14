<?php
if ( is_home() || is_archive() || is_search() ) {
?>
	<h1 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<?php		
} else {
?>
	<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
<?php
}
?>