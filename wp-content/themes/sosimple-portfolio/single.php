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
		
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta">
						<?php sosimple_posted_on(); ?>
					</div><!-- .entry-meta -->
			</header><!-- .entry-header -->
			<table>	 
			 
			<?php if( $users = get_field('user') ) :?>
				<tr>
					<td width="25%">Personen</td>
					<td><?=implode(', ',array_map(function($usr){ return $usr['display_name'];},$users)) ?></td>
				</tr>
			<?php endif ?> 						
            <?php if($kurztext = get_field('kurztext')):?>
            	<tr><td width="25%">Kurzbeschreibung</td><td><?=$kurztext?></td></tr>
            <?php endif ?>  			
            <?php $datum = get_field( 'datum' );
				if( $datum ) {?><tr><td width="25%">Datum</td><td><?php echo $datum;?></td></tr><?php	}  ?> 
			  
			<?php $aufgabe = get_field( 'aufgabe' );
				if( $aufgabe ) {?><tr><td width="25%">Aufgabe</td><td><?php echo $aufgabe;?></td></tr><?php	}  ?>  
			
			<?php $status = get_field( 'status' );
                    if( $status ) {?><tr><td width="25%">Status</td><td><?php foreach($status as $status)
                                   {
                                        echo  $status ;
                                   } ?></td></tr><?php     }  ?>
                                   <tr><td colspan="2"><hr class="blue"></td></tr>	
			<?php $smart = get_field( 'smart' );
				if( $smart ) {?><tr><td width="25%">Smart-Kriterien	</td><td><?php foreach($smart as $smart)
							{
								echo  $smart ;?><br /><?php
							} ?></td></tr><?php	}  ?> 
			<?php $lernziele = get_field( 'lernziele' );
				if( $lernziele ) {?><tr><td colspan="2"><h2>Lernziele</h2><?php echo $lernziele;?></td></tr><?php	}  ?>
							
			<?php $langtext = get_field( 'langtext' );
				if( $langtext ) {?><tr><td colspan="2"><h2>Portfoliobeitrag</h2><?php echo $langtext;?></td></tr><?php	}  ?>
			<?php $reflexion = get_field( 'reflexion' );
				if( $reflexion ) {?><tr><td colspan="2"><h2>Reflexion</h2><?php echo $reflexion;?></td></tr><?php	}  ?>
            </table>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-thumbnail">
					<?php the_post_thumbnail( 'sosimple-featured' ); ?>
				</div>
			<?php endif; ?>
			
			<div class="entry-content">
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sosimple' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
			
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
