<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package Aadya
 * @subpackage Aadya
 * @since Aadya 1.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
<header>
	<hgroup>		
		<h2><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'aadya' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	</hgroup>
</header>

<?php if ( has_post_thumbnail()) : ?>
	<div class="featured-img pull-left">
	<a href="<?php the_permalink(); ?>" class="th" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail('thumbnail', array('class' => "post-image")); ?></a>
	</div>
<?php endif; ?>					

<div class="entry-summary">
<?php the_excerpt(); ?>
</div><!-- .entry-summary -->
<div class="post-meta">			

<?php
	printf( __( '<span class="post-author no-margin"><i class="fa fa-user text-muted"></i> %2$s</span><span class="post-date text-muted"><i class="fa fa-calendar"></i> %1$s</span>', 'aadya' ),
	sprintf( '<a href="%1$s" title="%2$s" rel="bookmark" class="text-muted">%3$s</a>',
	get_permalink(),
	esc_attr( get_the_time() ),
	get_the_date()
	),
	sprintf( '<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>',
	get_author_posts_url( get_the_author_meta( 'ID' ) ),
	sprintf( esc_attr__( 'View all posts by %s', 'aadya' ), get_the_author() ),
	get_the_author()
	)
	);
?>  
				
<div class="pull-right">				
	<span class="post_comment text-muted"><i class="fa fa-comments"></i>
	<a href="<?php the_permalink() ?>#comments"><?php comments_number(__('No comments', 'aadya'),__('One comment','aadya'),__('% comments','aadya')); ?></a>
	</span>
</div>			

</div> 
<div class="clearfix"/>
</article>