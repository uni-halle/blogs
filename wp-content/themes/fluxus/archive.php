<?php get_header(); ?>
<?php get_template_part('sidebar', 'top'); ?>

<div class="main__content <?php if ( !is_active_sidebar( 'sidebar-top' ) && !is_active_sidebar( 'sidebar-bottom' ) ) { echo "main__content--full"; } ?> content">

	<article <?php post_class("content__article article typo"); ?>>
		<h1 class="article__title"><?php single_term_title(); ?></h1>
		<?php the_archive_description( '<p>', '</p>' ); ?>
	</article>

	<?php if (have_posts()): while(have_posts()): the_post(); ?> 

		<article <?php post_class("postlinklist__item typo"); ?>>
			<a href="<?php the_permalink(); ?>">
				<div class="postlinklist__head">
					<?php the_post_thumbnail('thumbnail', array("class" => "postlinklist__postthumbnail", "size" => "size-thumbnail")); ?>
					<div class="postlinklist__headinfo">
						<?php the_date("", "<p class='postlinklist__date'>", "</p>"); ?>
						<?php the_title("<h4 class='postlinklist__title'>", "</h4>"); ?>
					</div>
				</div>
				<div class="postlinklist__content">
					<?php the_excerpt(); ?>
				</div>
			</a>
		</article>

	<?php endwhile; endif; ?> 

</div>

<?php get_template_part('sidebar', 'bottom'); ?>
<?php get_footer(); ?>