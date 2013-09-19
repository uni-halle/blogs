<?php get_header(); ?>
 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post" id="post-<?php the_ID(); ?>">
<div class="p-head">
<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a>
<h2><strong><?php the_title(); ?></strong></h2>
</div>

<div class="p-con">
<p><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a>
<?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></p>
<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>


<div class="nav">
 <div class="left"><?php previous_image_link() ?></div>
 <div class="right"><?php next_image_link() ?></div>
</div>

<small><?php edit_post_link('Edit this entry.','',''); ?></small>

</div>
</div>

<?php comments_template(); ?>

<?php endwhile; else: ?>
<p>Sorry, no attachments matched your criteria.</p>
<?php endif; ?>

<?php get_footer(); ?>
