<?php
/**
 * The default template for displaying content
 *
 * @package Esquire
 * @since Esquire 1.0
 */
?>

<div <?php post_class(); ?>>
	<?php if ( ! is_page() ) : ?>
	<div class="datebox">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<p class="day"><?php the_time( 'd' ); ?></p>
			<p class="month"><?php the_time( 'M' ); ?></p>
		</a>
	</div>
	<?php endif; ?>

	<div class="postbody link">
		<?php
			// Let's find the first url in the post content.
			$link_url = esquire_url_grabber();

			// Let's make the title a link if there's a link in this link post
			if ( ! empty( $link_url ) ) :
		?>
		<h1 class="entry-title"><a href="<?php echo esc_url( $link_url ); ?>"><?php the_title(); ?></a></h1>
		<?php else : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>

		<?php
		// Sometimes links need descriptions and sometimes they don't ...

		// Let's compare the length of the first url with the length of the post content.
		// If they're one and the same we don't really need to show the post content BECAUSE ...
		// that's just a url and we're already using that url as a href for the title link above BUT ...
		// if they're NOT the same I think we should show that content.
		if ( strlen( $link_url ) != strlen( get_the_content() ) ) :

			// Let's make any bare URL a clickable link, too.
			add_filter( 'the_content', 'make_clickable' );
		?>

		<div class="content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'esquire' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'esquire' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div>
		<?php endif; ?>

		<?php comments_template(); ?>

		<div class="meta bar">
			<p class="permalink">
				<?php if ( ! is_page() ) : ?>
				<a href="<?php the_permalink(); ?>"><span rel="<?php the_time( get_option( 'date_format' ) ); ?>"><?php printf( __( '%1$s ago', 'esquire' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span></a>
				<?php endif; ?>
				<a href="<?php echo wp_get_shortlink(); ?> " class="shorturl"><span><?php _e( 'Short URL', 'esquire' ); ?></span></a>
				<?php comments_popup_link( '<span>' . __( 'Comments', 'esquire' ) . '</span>', '<span>' . __( '1 Comment', 'esquire' ) . '</span>', '<span>' . __( '% Comments', 'esquire' ) . '</span>', 'comment-count', '' ); ?>
				<?php edit_post_link( __( 'Edit', 'esquire' ) ); ?>
			</p>

			<div class="tagbar">
				<?php if ( 1 != esquire_category_counter() ) : ?>
				<p class="tags cats"><?php the_category( '<span>/</span>' ); ?></p>
				<?php endif; ?>
				<?php the_tags( '<p class="tags">', '<span>/</span>', '</p>' ); ?>
			</div><!-- .tagbar -->
		</div><!-- .meta .bar -->
	</div><!-- .postbody .text -->
</div><!-- .post -->