<?php
/**
 *
 * Template Name: Portfolio Page
 *
 * @package bueno
 */

get_header(); ?>
	<div id="primary" class="span12">
		<div id="content" class="site-content" role="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
		<div class="page_wrap">		
			<h1 class="post-title"><?php the_title(); ?></h1>
			<div class="post_content">
				<?php the_content(); ?>
			</div>
		</div>
			
		<?php endwhile; else: ?>
		
			<?php get_template_part( 'templates/no-results' ); ?>

		<?php endif; ?>
		<div class="portfolio-page">
			<div class="row">
				<?php bueno_portfolio_show(); ?>
			</div>
		</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php do_action( 'bueno_portfolio_after_content' ); ?>

<?php get_footer(); ?>