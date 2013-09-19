<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<!--<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Next Page |', 'atmosphere' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( '| Previous Page <span class="meta-nav">&raquo;</span>', 'atmosphere' ) ); ?></div>
	</div>
	-->
	<!-- #nav-above -->
<?php endif; ?>

<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'atmosphere' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'atmosphere' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php if ( in_category( _x('gallery', 'gallery category slug', 'atmosphere') ) ) : ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<div class="entry-meta">
				<?php atmosphere_posted_on(); ?>
			</div><!-- .entry-meta -->
			
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'atmosphere' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="entry-content">
<?php if ( post_password_required() ) : ?>
				<?php the_content(); ?>
<?php else : ?>			
				<?php 
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>
						<div class="gallery-thumb">
							<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</div><!-- .gallery-thumb -->
						<p><em><?php printf( __( 'This gallery contains <a %1$s>%2$s photos</a>.', 'atmosphere' ),
								'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'atmosphere' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
								$total_images
							); ?></em></p>
				<?php endif; ?>
						<?php the_excerpt(); ?>
<?php endif; ?>
			</div><!-- .entry-content -->

			<div class="entry-utility">
				<a href="<?php echo get_term_link( _x('gallery', 'gallery category slug', 'atmosphere'), 'category' ); ?>" title="<?php esc_attr_e( 'View posts in the Gallery category', 'atmosphere' ); ?>"><?php _e( 'More Galleries', 'atmosphere' ); ?></a>
				<span class="meta-sep">|</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Comment Now', 'atmosphere' ), __( '1 Comment', 'atmosphere' ), __( '% Comments', 'atmosphere' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'atmosphere' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

	<?php elseif ( in_category( _x('asides', 'asides category slug', 'atmosphere') ) ) : ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php if ( is_archive() || is_search() ) : // Display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'atmosphere' ) ); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

			<div class="entry-utility">
				<?php atmosphere_posted_on(); ?>
				<span class="meta-sep">|</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Comment Now', 'atmosphere' ), __( '1 Comment', 'atmosphere' ), __( '% Comments', 'atmosphere' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'atmosphere' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

	<?php else : ?>
		
		<div class="entry-meta">
			<?php atmosphere_posted_on(); ?>
		</div><!-- .entry-meta -->
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'atmosphere' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				
				<?php /* Add a conditional image and excertp output */ ?>
				<?php if(has_post_thumbnail()): // Start the condition ?>
				<div class="alignleft" style="margin: 0 0 10px 0">
					<?php the_post_thumbnail(); ?>
				</div>
					<?php the_excerpt(); ?>
				<?php else : // Offer an alternative ?> 
					<?php the_excerpt(); ?>
				<?php endif; // end the condition ?> 
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content">
				<?php if(has_post_thumbnail()): // Start the condition ?>
					<div class="alignleft" style="margin: 0 0 10px 0">
						<?php the_post_thumbnail(); ?>
					</div>
						<?php the_excerpt(); ?>			
				<?php else : // Offer an alternative ?> 
					<?php the_content( __( 'Read More <span class="meta-nav">&rarr;</span>', 'atmosphere' ) ); ?>
				<?php endif; ?>

				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'atmosphere' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

			<div class="entry-utility">
				<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Filed in</span> %2$s', 'atmosphere' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<?php
					$tags_list = ''; //get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'atmosphere' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Comment Now', 'atmosphere' ), __( '1 Comment', 'atmosphere' ), __( '% Comments', 'atmosphere' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'atmosphere' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

		<?php comments_template( '', true ); ?>

	<?php endif; ?>

<?php endwhile; // End the loop. Whew. ?>

<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Next Page |', 'atmosphere' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( '| Previous Page <span class="meta-nav">&raquo;</span>', 'atmosphere' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>