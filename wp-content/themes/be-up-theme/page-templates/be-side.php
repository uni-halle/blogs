<?php
/*
Template Name: be-up Side
*/
get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
	<div class="main-grid">
	</div>
</div>
<main class="main-content-full-width be-background-3">
	<?php while ( have_posts() ) : the_post(); ?>



		<div class="be-h2-special be-h2-link">
			<div class="topspace be-gradient-2">
			</div>
			<div class="be-border-upper">
				<a href="#" onclick="event.preventDefault();window.history.go(-1);">
				<!-- <a href="#" onclick="console.log('back');"> -->
					<div class="grid-container">
						<div class="radius-bg-back h2-heading-link">
							Zurück
						</div>
					</div>
				</a>
			</div>
		</div>




		<?php get_template_part( 'template-parts/content', 'page' ); ?>
		<?php //get_template_part( 'template-parts/demo-content-standard', 'page' ); ?>
		<?php comments_template(); ?>


		<div class="be-h2-special be-h2-link" style="margin-bottom:0;">
			<div class="topspace">
			</div>
			<!-- <div class="be-border-upper"> -->
			<div class="">
				<a href="#" onclick="event.preventDefault();window.history.go(-1);">
					<div class="grid-container">
						<div class="radius-bg-back h2-heading-link">
							Zurück
						</div>
					</div>
				</a>
			</div>
		</div>


	<?php endwhile; ?>
</main>
<?php get_footer();