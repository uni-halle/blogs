<?php 
/* @package WordPress
 * @subpackage Desk
 */
?>
<?php
get_header();
?>


<div id="post">

<?php
	if ( have_posts() )
		the_post();
// If a user has filled out their description, show a bio on their entries.
if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( '', 72 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description" class="entry">
							<h2><?php printf( __( 'About %s', '' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info -->
					
					
<?php endif; ?>
<h2 class="page-title author clear"><?php printf( __( 'Posts by %s', '' ), "<span class='vcard'>" . get_the_author() . "</span>" ); ?></h2>
	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="posthead">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<div class="dater">
				<a href="<?php the_permalink(); ?>"><?php the_time('l, F j, Y'); ?></a></div>
		</div>
		<div class="entry">
			<?php the_post_thumbnail(array(120,120), array('class' => 'alignright')); ?>
			<?php the_content('Read on &raquo;'); ?></div>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'desk' ), 'after' => '</div>' ) ); ?>

			<div class="clear"></div>
	</div>
	
	<p class="postmetadata"><?php if ($dk_settings['desk_hidetags'] == '') { the_tags('<span class="tags">Tags: ', ', ', '</span><br />'); } ?>
	<span class="cats">Posted in <?php the_category(', ') ?></span> | <?php edit_post_link('Edit', '', ' | '); ?>
	<span class="comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
	</span></p>
	
	<?php endwhile; ?>
	<div class="pnavigation">
		<p class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?>
		</p>
		<p class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?>
		</p>
	</div>
	<?php else : ?>
	<h4 class="center">Not Found</h4>
	<p class="center">Sorry, but you are looking for something that isn&#39;t here.</p>
	<?php endif; ?></div>
<?php get_sidebar(); ?><?php get_footer(); ?>