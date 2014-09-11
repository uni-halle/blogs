<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Esquire
 */

get_header(); ?>

	<div id="header">
		<h2>
			<?php
				if ( is_category() ) :
					printf( __( 'Category Archives: %s', 'esquire' ), '<span>' . single_cat_title( '', false ) . '</span>' );

				elseif ( is_tag() ) :
					printf( __( 'Tag Archives: %s', 'esquire' ), '<span>' . single_tag_title( '', false ) . '</span>' );

				elseif ( is_author() ) :
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					 */
					the_post();
					printf( __( 'Author Archives: %s', 'esquire' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();

				elseif ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'esquire' ), '<span>' . get_the_date() . '</span>' );

				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'esquire' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'esquire' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
					_e( 'Asides', 'esquire' );

				elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
					_e( 'Images', 'esquire');

				elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
					_e( 'Videos', 'esquire' );

				elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
					_e( 'Quotes', 'esquire' );

				elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
					_e( 'Links', 'esquire' );

				else :
					_e( 'Archives', 'esquire' );

				endif;
			?>
		</h2>
	</div>

	<div id="posts">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

		<?php else : ?>

		<div class="hentry error404">
			<div class="postbody text">
				<h1><?php _e( 'Nothing Found', 'esquire' ); ?></h1>
				<div class="content">
					<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'esquire' ); ?></p>
				</div><!-- .content -->
			</div><!-- .postbody -->
		</div>

		<?php endif; ?>
	</div><!-- #posts -->

<?php get_footer();
