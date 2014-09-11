<?php while (have_posts()) : the_post(); ?>  
<?php if(has_post_thumbnail() ) { ?>
<div class="tile loop-entry thumb <?php foreach( get_the_category() as $cat ) { echo $cat->slug . '  '; } ?>
post<?php the_ID();?>
">
<a href="<?php the_permalink(' ') ?>" title="<?php the_title(); ?>">
        <div class="loop-entry-thumbnail">
            <?php the_post_thumbnail('thumbnail'); ?>
        </div>
  	<div class="column-text">
    	<h2 class="text-header"><?php the_title(); ?></h2>
    	<p><?php  echo excerpt('15'); ?></p>
    	
    </div>
</a>
<div class="box"></div>
</div><!-- END entry -->

<?php } else { ?>
<div class="tile loop-entry <?php foreach( get_the_category() as $cat ) { echo $cat->slug . '  '; } ?>
post<?php the_ID();?>
">
<a href="<?php the_permalink(' ') ?>" title="<?php the_title(); ?>">
    	<h2 class="text-header"><?php the_title(); ?></h2>
    	<p class="text3"><?php  echo excerpt('15'); ?></p>
    <!-- END loop-entry-details -->  
</a>
<div class="box"></div>
</div><!-- END entry -->
<?php }?>

<?php endwhile; ?>