<?php
/**
 * Meta data for blog headings
 */
?>

<header class="blog_title">

    <?php if (has_post_thumbnail() ) { ?>
                         
        <div class="meta_wrapper meta_thumb"> 
        
            <h2>
            	<?php if ( !is_single() ) { ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                <?php } else { ?>
                	<?php the_title(); ?>
                <?php } ?>
            </h2>
    
            <div class="date_wrapper">                    
                <span class="month"><?php the_time('M'); ?></span>
                <span class="day"><?php the_time('d'); ?></span> 
                <span class="year"><?php the_time('Y'); ?></span>                                                    
            </div>
            
            <div class="author_wrapper">
                <?php the_author_posts_link(); ?> <br>
                <?php the_category(', '); ?> <br>
                <a href="<?php comments_link(); ?>">
                    <?php comments_number( __('No comments', 'maja'), __('1 comment', 'maja'), __('% comments', 'maja') ); ?>
                </a>
            </div>
        </div>
    
    <?php } else { ?>
    
        <div class="meta_wrapper"> 
        
            <h2>
                <?php if ( !is_single() ) { ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                <?php } else { ?>
                	<?php the_title(); ?>
                <?php } ?>
            </h2>
    
            <div class="date_wrapper">                    
                <span class="month"><?php the_time('M'); ?></span>
                <span class="day"><?php the_time('d'); ?></span> 
                <span class="year"><?php the_time('Y'); ?></span>                                                    
            </div>
            
            <div class="author_wrapper">
                <?php the_author_posts_link(); ?> <br>
                <?php the_category(', '); ?> <br>
                <a href="<?php comments_link(); ?>">
                    <?php comments_number( __('No comments', 'maja'), __('1 comment', 'maja'), __('% comments', 'maja') ); ?>
                </a>
            </div>
        </div> 
        
    <?php } ?>           
    
    <?php if (has_post_thumbnail() && !is_search() ) { ?>
   
		<?php if (!is_single()) { ?>
        
            <div class="feat_blog_img">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('blog-thumbnail'); ?>
                </a>
            </div> 
        
        <?php } else { ?>
        
            <div class="feat_blog_img">
                <?php the_post_thumbnail('blog-thumbnail'); ?>
            </div> 
         
        <?php } ?> 
     	
    <?php } ?> 
                                                                     
</header> <!--/blog_title-->