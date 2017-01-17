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

			<table>	
			<tr><td>Fachbereich</td><td><?php the_field('fach'); ?></td></tr>
            <tr><td>Veranstaltung</td><td><?php the_field('veranstaltungstitel'); ?></td></tr>
			<tr><td>Studienprogramm/e</td><td><?php the_field('studienprogramm'); ?></td></tr>
			<tr><td>Teilnehmerzahl</td><td><?php the_field('anzahl'); ?></td></tr>
			 <tr><td colspan="2"><hr class="blue"></td></tr>			
			<tr><td>Ziel</td><td><?php the_field('ziel'); ?></td></tr>
            <tr><td>Zielgruppe</td><td><?php the_field('zielgruppe'); ?></td></tr>
            <tr><td>Werkzeug/e</td><td><?php the_field('werkzeug'); ?></td></tr>
            <tr><td>Sammlung</td><td><?php the_field('sammlung'); ?></td></tr>
            <tr><td>Zeitpunkt</td><td><?php the_field('zeitpunkt'); ?></td></tr>
            <tr><td>Kommunikation</td><td><?php the_field('kommunikation'); ?></td></tr>
            <tr><td>Leistung</td><td><?php the_field('leistung'); ?></td></tr>
            <tr><td>Fazit</td><td><?php the_field('fazit'); ?></td></tr>
            <tr><td colspan="2"><hr class="blue"></td></tr>		
			<tr><td width="30%">Hochschule</td><td><?php the_field('hochschule'); ?></td></tr>
			<tr><td>DozentIn</td><td><?php the_field('dozentin'); ?></td></tr>
            <tr><td>Kontakt</td><td><?php the_field('kontakt'); ?></td></tr>
			</table>
			
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
