<?php
/**
 * @package Bizantine
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
    	
        <?php if(of_get_option('show_featured_image_single') == 'true' && has_post_thumbnail() ) : ?>
        <div class="entry-featured-image">
        	<?php the_post_thumbnail( 'Bizantinesingle' ); ?>
        </div>
        <?php endif; ?>
        
		<h1 class="entry-title"><?php the_title(); ?></h1>
		
        <?php if ( function_exists('the_ratings') && (!of_get_option('show_rat_on_single') || of_get_option('show_rat_on_single') == 'true')) : ?>
        <div class="entry-meta">
			<?php the_ratings(); ?>
		</div><!-- .entry-meta -->
        <?php endif; ?>
        
        <?php if (!of_get_option('show_pd_on_single') || of_get_option('show_pd_on_single') == 'true') : ?>
		<div class="entry-meta">
			<?php Bizantine_posted_on(); ?>
		</div><!-- .entry-meta -->
        <?php endif; ?>
        
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'Bizantine' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta-bottom">
		<?php if(!of_get_option('show_cats_on_single') || of_get_option('show_cats_on_single') == 'true') : ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ' ', 'Bizantine' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ' ', 'Bizantine' ) );

			if ( ! Bizantine_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = '<div class="entry-meta-bottom-item">'.__( 'Tags : %2$s', 'Bizantine' ).'</div><div class="entry-meta-bottom-item">'.__( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'Bizantine' ).'</div>';
				} else {
					$meta_text = '<div class="entry-meta-bottom-item">'.__( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'Bizantine' ).'</div>';
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = '<div class="entry-meta-bottom-item">'.__( 'Categories : %1$s', 'Bizantine' ).'</div><div class="entry-meta-bottom-item">'.__( 'Tags : %2$s', 'Bizantine' ).'</div><div class="entry-meta-bottom-item">'.__( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'Bizantine' ).'</div>';
				} else {
					$meta_text = '<div class="entry-meta-bottom-item">'.__( 'Categories : %1$s', 'Bizantine' ).'</div><div class="entry-meta-bottom-item">'.__( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'Bizantine' ).'</div>';
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>
		<?php endif; ?> 
        
		<?php edit_post_link( __( 'Edit', 'Bizantine' ), '<div class="entry-meta-bottom-item edit-link">', '</div>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
