<?php
/**
 * @package Bizantine
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
    
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
              
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'Bizantine' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'Bizantine' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
    
	<footer class="entry-meta-bottom">
            
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ' ', 'Bizantine' ) );
				if ( $categories_list && Bizantine_categorized_blog() ) :
			?>
			<div class="entry-meta-bottom-item">
				<?php printf( __( 'Posted in %1$s', 'Bizantine' ), $categories_list ); ?>
			</div>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ' ', 'Bizantine' ) );
				if ( $tags_list ) :
			?>
			<div class="entry-meta-bottom-item">
				<?php printf( __( 'Tagged %1$s', 'Bizantine' ), $tags_list ); ?>
			</div>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>
        
		<div class="entry-meta-bottom-item">
			<?php Bizantine_posted_on(); ?>
		</div>
            
		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<div class="entry-meta-bottom-item">
			<?php comments_popup_link( __( 'Leave a comment', 'Bizantine' ), __( '1 Comment', 'Bizantine' ), __( '% Comments', 'Bizantine' ) ); ?>
        </div>
		<?php endif; ?>
        
        <?php edit_post_link( __( 'Edit', 'Bizantine' ), '<div class="entry-meta-bottom-item edit-link">', '</div>' ); ?>
        
	</footer><!-- .entry-meta -->      


</article><!-- #post-## -->
