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
					<td width="25%">TeilnehmerInnen</td>
					<td><?=implode(', ',array_map(function($usr){ return $usr['display_name'];},$users)) ?></td>
				</tr>
			<?php endif ?> 								
            <?php $datum = get_field( 'datum' );
				if( $datum ) {?><tr><td width="25%">Datum</td><td><?php echo $datum;?></td></tr><?php	}  ?> 
			<?php $status = get_field( 'status' );
				if( $status ) {?><tr><td width="25%">Status</td><td><?php foreach($status as $status)
							{
								echo  $status ;
							} ?></td></tr><?php	}  ?> 
			<?php if($kurztext = get_field('kurztext')):?>
            	<tr><td width="25%">&nbsp;</td><td><?=$kurztext?></td></tr>
            <?php endif ?> 
            <?php if($langtext = get_field('langtext')):?>
            	<tr><td width="25%">&nbsp;</td><td><?=$langtext?></td></tr>
            <?php endif ?>		
			
			<?php $link = get_field( 'link' );
				if( $link ) {?><tr><td width="25%">Link</td><td><a href="<?php echo $link;?>" target="–blank"><?php echo $link;?></a></td></tr><?php	}  ?>  
			<?php $datei = get_field( 'datei' );
				if( $datei ) {?><tr><td width="25%">Datei</td><td><?php echo $datei;?></td></tr><?php	}  ?> 
            </table>

			<hr class="blue">
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
