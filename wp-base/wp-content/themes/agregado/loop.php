<div class="post clearfloat" id="post-<?php the_ID(); ?>">
  <?php if (!is_single() ) { ?>
  <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
    <?php the_title(); ?>
    </a></h2>
  <?php } else { ?>
  
  <?php 
  // so we can exclude the post from related posts in the sidebar
  $GLOBALS['current_id'] = $post->ID; 
  ?>

  <h2>
    <?php the_title(); ?>
  </h2>
  
  <?php } ?>
  <p class="postmetadata">Posted <em>by</em> <?php the_author(); ?> <em>on</em> <?php the_time('M j, Y') ?> &bull; <span class="commentcount">(<a href="<?php the_permalink(); ?>#commentarea"><?php comments_number('0', '1', '%'); ?></a>)</span></p>
    
 
  <div class="entry clearfloat">
    <?php if (!is_single() && !is_home() ) { ?>
    <?php the_excerpt(); ?>
    <?php } else { ?>
    <?php the_content(); ?>
     
    <br />
	<?php wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink=page %'); ?>
	
	<span id="indexing"> <?php the_tags('Tagged as ',', ',' +'); ?> Categorized as <?php the_category(', ','single'); ?></span>
	
  <br />
  
   <?php edit_post_link('Edit post', '<p>', '</p>'); ?> 
	
    <?php } ?>
   
  </div>
  
  
   
</div>
