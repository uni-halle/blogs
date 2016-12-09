<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SoSimple
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<table>	
						<?php if( $users = get_field('user') ) :?>
				<tr>
					<td width="25%">TeilnehmerInnen</td>
					<td><?=implode(', ',array_map(function($usr){ return $usr['display_name'];},$users)) ?></td>
				</tr>
			<?php endif ?> 						
            <?php if($thema = get_field('thema')):?>
            	<tr><td width="25%">Thema</td><td><?=$thema?></td></tr>
            <?php endif ?> 			
            <?php $datum = get_field( 'datum' );
				if( $datum ) {?><tr><td width="25%">Datum</td><td><?php echo $datum;?></td></tr><?php	}  ?> 
			<?php $aufgabe = get_field( 'aufgabe' );
				if( $aufgabe ) {?><tr><td width="25%">Aufgabe</td><td><?php echo $aufgabe;?></td></tr><?php	}  ?>  
			<?php $status = get_field( 'status' );
				if( $status ) {?><tr><td width="25%">Status</td><td><?php foreach($status as $status)
							{
								echo  $status ;
							} ?></td></tr><?php	}  ?> 
            </table>
<hr class="blue">

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
