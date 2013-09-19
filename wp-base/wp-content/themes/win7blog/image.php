<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'win7blog' ), wp_specialchars( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>

			<div id="post-<?php the_ID() ?>" class="post">
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
					<div class="entry-attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></div>
					<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt() ?></div>
					<?php the_content() ?>
				</div>
			</div><!-- .post -->

			<div id="nav-images" class="navigation">
				<div class="nav-previous"><?php previous_image_link() ?></div>
				<div class="nav-next"><?php next_image_link() ?></div>
			</div>
			
<?php comments_template() ?>

		</div><!-- #content -->
		<?php get_sidebar() ?>
	</div><!-- #container -->

<?php get_footer() ?>