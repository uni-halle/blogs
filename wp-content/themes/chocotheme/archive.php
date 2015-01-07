<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @package Choco
 */
get_header(); ?>

<?php if ( have_posts() ) : ?>

	<h1 class="pagetitle">
		<?php
			if ( is_category() ) {
				printf( __( 'Category Archives: %s', 'choco' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			} elseif ( is_tag() ) {
				printf( __( 'Tag Archives: %s', 'choco' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			} elseif ( is_author() ) {
				the_post();
				printf( __( 'Author Archives: %s', 'choco' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
				rewind_posts();
			} elseif ( is_day() ) {
				printf( __( 'Daily Archives: %s', 'choco' ), '<span>' . get_the_date() . '</span>' );
			} elseif ( is_month() ) {
				printf( __( 'Monthly Archives: %s', 'choco' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
			} elseif ( is_year() ) {
				printf( __( 'Yearly Archives: %s', 'choco' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
			} else {
				_e( 'Archives', 'choco' );
			}
		?>
	</h1>

	<div class="list-page">
		<?php
			if ( is_category() ) {
				// show an optional category description
				$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo apply_filters( 'category_archive_meta', '<div class="archive-meta">' . $category_description . '</div>' );

			} elseif ( is_tag() ) {
				// show an optional tag description
				$tag_description = tag_description();
				if ( ! empty( $tag_description ) )
					echo apply_filters( 'tag_archive_meta', '<div class="archive-meta">' . $tag_description . '</div>' );
			} elseif ( is_author() ) {
				// If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) { ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2 id="entry-author-info-heading"><?php printf( __( 'About %s', 'choco' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info --><?php
				}
			}
		?>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;
		?>

		<?php if ( $wp_query->max_num_pages > 1 ) : ?>
			<div class="post-navigation clear-fix">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'choco' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?></div>
			</div><!-- .post-navigation -->
		<?php endif; ?>
	</div><!-- #list-page -->

<?php else : ?>

	<div class="list-page">
		<h2 class="center"><?php _e( 'Not Found', 'choco' ); ?></h2>
		<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'choco' ); ?></p>
		<?php get_search_form(); ?>
	</div><!-- #list-page -->

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>