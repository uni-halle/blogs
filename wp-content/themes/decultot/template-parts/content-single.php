<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">

		<?php
			$post_cat = get_the_category();
			$allowed_cats = [ 5, 6, 9, 10 ];
			$is_allowed = false;

			foreach($post_cat as $cat) {
				if( in_array($cat->cat_ID, $allowed_cats)) $is_allowed = true;
			}

			if( $is_allowed ) {
				$dachzeile = get_post_meta( get_the_ID(), 'unterkategorie', true );
				if( $dachzeile !== '' ) echo '<h2 class="dachzeile">'.$dachzeile.'</h2>';
			}

			the_title( '<h1 class="entry-title">', '</h1>' );
		?>

	</header><!-- .entry-header -->

	<?php // twentysixteen_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();

		?>
			<?php
				$projectinfo = do_shortcode('[pods field="projekt_info"]');
				if (!empty($projectinfo)) :
					?>
					<div id="projektinfo" class="projektinfo">
						<?php echo $projectinfo; ?>
					</div>
					<?php
				endif;

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}

			$category = get_the_category();
			$cat_id = $category[0]->cat_ID;
			$cat_name = $category[0]->name;
			$cat_link = get_category_link( $cat_id );
			//					echo "<pre>" . print_r( $category, true ) . "</pre>";

			?>

			<div class="single-post-footer">
				<p class="seitenanfang">
					<a class="scroll-top-link" href="javascript:void(0);"><?php _e( 'Seitenanfang', 'dct' ); ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="<?php echo
					$cat_link;
					?>"><?php echo $cat_name; ?></a>
				</p>
				<p class="seitenanfang">
					<a href="javascript:void(0);" class="print-link"><?php _e( 'Seite drucken', 'dct' ); ?></a>
				</p>
			</div>

			<?php

		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php // twentysixteen_entry_meta(); ?>
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
