<?php
/**
 * BLOG COMMENTS
 */

function mytheme_comment($comment, $args, $depth) { 	
	
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
   
       <article id="comment-<?php comment_ID(); ?>">
       
          <footer>	
                  
              <div class="comment-author vcard">
                   <?php echo get_avatar($comment,$size='60',$default='<path_to_url>' ); ?>
                   <?php printf('<h3>%s</h3>', get_comment_author_link()) ?>
              </div>
                    
              <?php if ($comment->comment_approved == '0') : ?>
                   <em><?php _e('Your comment is awaiting moderation.', 'maja') ?></em>
                   <br>
              <?php endif; ?>
              
              <div class="comment-meta commentmetadata">
                  <?php printf(__('%1$s at %2$s', 'maja'), get_comment_time('M d Y'),  get_comment_time()) ?>
                  <?php edit_comment_link(__('(Edit)', 'maja'),'  ','') ?>
              </div>
              
          </footer>            
          
          <div class="comment_text">
              <?php comment_text() ?>
          </div>
          
          <div class="reply">
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
          </div>
        
       </article>
     
<?php } ?>