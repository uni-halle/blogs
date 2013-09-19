<?php
/**
 * @package Duotone
 */
?>
<?php get_header(); rewind_posts(); ?>
<div class="archive">

<ul id="filters">
	<li><?php _e( 'Filter by:', 'duotone' ); ?></li>
	<li>
		<select name="archive-dropdown" id="archive-dropdown">
			<option value=""><?php _e( 'Select Month', 'duotone' ); ?></option>
			<?php wp_get_archives( 'type=monthly&format=option&show_post_count=1' ); ?>
		</select>
	</li>
	<li>
		<?php wp_dropdown_categories( array( 'show_option_none' => __( 'Select Category', 'duotone' ) ) ); ?>
	</li>
</ul>

	<?php if ( have_posts() ) : ?>

		<?php /* If this is a category archive */ if ( is_category() ) { ?>
			<h2><?php printf( __( 'Archive for the &#8216;%s&#8217; Category', 'duotone' ), single_cat_title( '', false ) ); ?></h2>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
			<h2><?php printf( __( 'Posts tagged &#8216;%s&#8217;', 'duotone' ), single_tag_title( '', false ) ); ?></h2>
		<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
			<h2><?php _e( 'Archive for', 'duotone' ); ?> <?php the_time( __( 'F jS, Y', 'duotone' ) ); ?></h2>
		<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
			<h2><?php _e( 'Archive for', 'duotone' ); ?> <?php the_time( __( 'F, Y', 'duotone' ) ); ?></h2>
		<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
			<h2><?php _e( 'Archive for', 'duotone' ); ?> <?php the_time( __( 'Y', 'duotone' ) ); ?></h2>
		<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
			<h2><?php _e( 'Author Archive', 'duotone' ); ?></h2>
		<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged']) ) { ?>
			<h2><?php _e( 'Blog Archives', 'duotone' ); ?></h2>
		<?php } ?>

		<div class="nav">
			<div class="prev"><?php next_posts_link( __( '&larr; Older Entries', 'duotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'duotone' ) ); ?></div>
		</div>

		<ul class="thumbnails">
		<?php while ( have_posts() ) : the_post(); ?>
			<li id="post-<?php the_ID(); ?>">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo Duotone::get_archive_image(); ?></a>
			</li>
		<?php endwhile; ?>
		</ul>

		<div class="nav">
			<div class="prev"><?php next_posts_link( __( '&larr; Older Entries', 'duotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'duotone' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'duotone' ); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>

<?php get_footer(); ?>
