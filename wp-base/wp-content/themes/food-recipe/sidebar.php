<div class="sidebar">

    <div class='sidebargallery'>
    <h3>Recommendations</h3>
	<?php $my_query = new WP_Query('category_name=featured&showposts=8'); $count=1; ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		<div class="sidebargalleryimage"<?php if ($count%4 == 0) { echo ' style="margin-right:0px"'; } ?>>
        	<div class="sidebargalleryimage_inner">
			<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
            <?php if (get_post_meta($post->ID, "featuredimage", true) != '') { //for previous users who have use the postmeta to set their featured images ?> 
	            <img src="<?php echo get_post_meta($post->ID, "featuredimage", true) ?>" alt="<?php the_title() ?>" />
            <?php } else { 
				the_post_thumbnail();
				}
			?>
            </a>
            </div>
        </div>
	<?php $count++; endwhile; ?>
	<div style="clear:both"></div>
	</div>

    <div>       
        <div class="sidebar_left">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_left') ) : ?>
            <div class="sidebarbox">
                <h3>Categories</h3>
                <ul>
                    <?php wp_list_categories('title_li='); ?>
                </ul>
            </div>

			<div class="sidebarbox">
			<?php
            if (function_exists('wp_list_bookmarks')) {
                wp_list_bookmarks(array('title_before' => '<h3>', 'title_after' => '</h3>',	'category_before' => '<div class="sidebarbox">', 'category_after' => '</div>'));
            }
            ?>
			</div>
		<?php endif; ?>
        </div>
                        
        <div class="sidebar_right">

        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_right') ) : ?>
                <h3>Recent Comments</h3>
	            <ul>
                <?php
                global $wpdb, $comments, $comment;
                $comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type = '' ORDER BY comment_date_gmt DESC LIMIT 8");
                if ( $comments ) : foreach ($comments as $comment) :
                echo  '<li class="recentcomments">' . sprintf(__('%1$s on %2$s'), get_comment_author_link(), '<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
                endforeach; endif;
                ?>
            </ul>
        <?php endif; ?>
        </div>
	</div>

</div>