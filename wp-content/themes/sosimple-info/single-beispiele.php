	<?php
/**
 * The template for displaying all single posts.
 *
 * @package SoSimple
 */
/* Attach the Table CSS and Javascript */



get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single-beispiele' ); ?>

			<div class="table">	
			<div class="tr"><div class="td25">Fachbereich</div><div class="td70"><?php the_field('fach'); ?></div></div>
            <div class="tr"><div class="td">Veranstaltung</div><div class="td"><?php the_field('veranstaltungstitel'); ?></div></div>
			<div class="tr"><div class="td">Studienprogramm/e</div><div class="td"><?php the_field('studienprogramm'); ?></div></div>
			<div class="tr"><div class="td">Teilnehmerzahl</div><div class="td"><?php the_field('anzahl'); ?></div></div>
			</div>
			<hr class="blue">	
			<div class="table">			
			<div class="tr"><div class="td25">Ziel</div><div class="td70"><?php the_field('ziel'); ?></div></div>
            <div class="tr"><div class="td">Zielgruppe</div><div class="td"><?php the_field('zielgruppe'); ?></div></div>
            <div class="tr"><div class="td">Werkzeug/e</div><div class="td"><?php the_field('werkzeug'); ?></div></div>
            <div class="tr"><div class="td">Sammlung</div><div class="td"><?php the_field('sammlung'); ?></div></div>
            <div class="tr"><div class="td">Zeitpunkt</div><div class="td"><?php the_field('zeitpunkt'); ?></div></div>
            <div class="tr"><div class="td">Kommunikation</div><div class="td"><?php the_field('kommunikation'); ?></div></div>
            <div class="tr"><div class="td">Leistung</div><div class="td"><?php the_field('leistung'); ?></div></div>
            <div class="tr"><div class="td">Fazit</div><div class="td"><?php the_field('fazit'); ?></div></div>
			</div>
            <hr class="blue">	
            <div class="table">		
			<div class="tr"><div class="td25">Hochschule</div><div class="td70"><?php the_field('hochschule'); ?></div></div>
			<div class="tr"><div class="td">DozentIn</div><div class="td"><?php the_field('dozentin'); ?></div></div>
            <div class="tr"><div class="td">Kontakt</div><div class="td"><?php the_field('kontakt'); ?></div></div>
			</div>
			
			<?php the_post_navigation(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
