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
			$dachzeile = get_post_meta( get_the_ID(), 'unterkategorie', true );
			if( $dachzeile !== '' ) echo '<h2 class="dachzeile">'.$dachzeile.'</h2>';

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

//		$newspage_id = ICL_LANGUAGE_CODE === 'de' ? 821 : 826;  // development
		$newspage_id = ICL_LANGUAGE_CODE === 'de' ? 2258 : 2261;  // live
		$page_link = get_the_permalink( $newspage_id );

		?>

		<div class="single-post-footer">
			<p class="seitenanfang">
				<a class="scroll-top-link" href="javascript:void(0);"><?php _e( 'Seitenanfang', 'dct' ); ?></a>
				&nbsp;&nbsp;/&nbsp;&nbsp;
				<a href="<?php echo $page_link; ?>">
					<?php echo _e('News', 'dct'); ?>
				</a>
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
