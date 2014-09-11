<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<!-- Start Content -->
<div class="SC" role="main">

<!-- Side - List Post-->
<div class="SL">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
   <div class="post-head">
	 <div class="post-date"><?php the_time('F j, Y') ?></div>
	 <h1><?php the_title(); ?></h1>
   </div>
 <div class="post-share">
  <div class="post-share-com"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '[ 1 ] Comment', 'twentyten' ), __( '[ % ] Comments', 'twentyten' ) ); ?></div>  
  <div class="post-share-tweet"><script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
  <a href="http://twitter.com/share?url=<?php echo urlencode(get_permalink($post->ID)); ?>&count=horizontal" class="twitter-share-button">Tweet</a></div>
  <div class="post-share-like"><a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></div>  
 </div>
  <?php if ( has_post_thumbnail()) : ?><div class="post-img-large"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>    
  <div class="post-con">    
	<?php the_content(); ?>
   <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
  </div>
<!-- .post-con -->

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( esc_attr__( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>

 <ul class="post-det">
  <?php $tags_list = get_the_tag_list( '', ' | ' ); if ( $tags_list ): ?>
  <li class="post-tag"><?php printf( __( '<span class="%1$s"></span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?></li>
  <?php endif; ?>
  <?php edit_post_link( __( 'Edit', 'twentyten' ), '<li class="post-edit">', '</li>' ); ?>
  </ul>

</div>
<!-- #post-## -->

<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>
</div>
<!-- End - Side Left -->

<!-- Start - Side Right -->
<div class="SR">
 <?php get_sidebar(); ?>
</div>
<!-- End - Side Right -->

</div>
<!-- End - SC -->

<?php get_footer(); ?>
