<?php 
	global $comment_id, $i, $oddevan;
	$oddeven = "";
	if ($comments) :
?>       
	<h2 class="post-section-title"><?php  echo $post->comment_count; ?> Comments <a name="comments"></a></h2>
	<?php
        foreach ($comments as $comment) :
            if ($comment->comment_parent == 0) :
                $comment_table = $wpdb->prefix . "ocmx_comment_meta";
                $comment_meta_sql = "SELECT * FROM $comment_table WHERE commentId = ".$comment->comment_ID." LIMIT 1";
                $comment_meta = $wpdb->get_row($comment_meta_sql);
		        $comment_type = get_comment_type();
        ?>
		<div class="comment clearfix">
            <?php if($comment_type == "comment") : ?>
                <div class="user">
                    <?php echo get_avatar( $comment, 40 ); ?>
                    <a href="#" id="reply-<?php echo $comment->comment_ID ?>" class="reply-link">Reply</a>
                    
                </div>
            <?php endif; ?>
			<div class="comment-post">
				<div class="container-header-light-normal"><span></span></div>
				<div class="comment-content clearfix">
					<h3><a href="<?php comment_author_url(); ?>" class="commentor_url" name="comment-<?php echo $comment->comment_ID; ?>" rel="nofollow"><?php comment_author(); ?></a><span class="comment-date"><?php comment_date() ?> <?php comment_time() ?></span></h3>
				  	<?php if ($comment->comment_approved == '0') : ?>
                        <p>Comment is awaiting moderation.</p>
                    <?php else :
                        comment_text();
                    endif; ?>  
				</div>
				<div class="container-footer-light-normal"><span></span></div>
			</div>
			<?php
                $comment_id = $comment->comment_ID;
                if($comment_type == "comment") :
                    fetch_comments($comment_id, $i);
                endif;
            ?>
			<div style="display: none;" id="new-reply-<?php echo $comment->comment_ID; ?>"></div>
        </div>
        <div class="dynamic-footer"><div class="left"></div><div class="right"></div></div>
        <div style="display: none;" id="form-placement-<?php echo $comment->comment_ID ?>"></div>
	<?php
            endif;
        endforeach;
    endif;
?>
<div id="new_comments"></div>
<div id="original_comment_location">
    <?php    
        if ('open' == $post->comment_status) : ?>
            <div id="comment_form_container">        
                <?php if ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in ?>
                    <p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>" class="std_link">logged in</a> to post a comment.</p>      
                <?php else : ?>
                    <div class="comment-form-content">
                       	<h2 class="post-section-title">Leave A Comment</h2>
						<p id="commment-post-alert" class="no_display">Posting your comment...</p>
                        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="comment_form" id="commentform"> 
                            <?php if ( $user_ID ) : ?>
                               <div class="checkbox">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php" class="std_link"><?php echo $user_identity; ?></a>.
                               <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Logout &raquo;</a></div>
                            <?php else : ?>
                                <?php do_action('fbc_display_login_button') ?>  
                                <label>Name</label>
                                <div class="comment-input"><input type="text" name="author" id="author" value="<?php if($comment_author != ""){echo $comment_author;}else{echo 'Name';} ?>" size="32" tabindex="1" /></div>
                                <label>Email</label>
                                <div class="comment-input"><input type="text" name="email" id="email" value="<?php if($comment_author_email != ""){echo $comment_author_email;}else{echo 'EMail Address';} ?>" size="32" tabindex="2" /></div>
                                <label>URL</label>
                                <div class="comment-input"><input type="text" name="url" id="url" value="<?php if($comment_author_url != ""){echo $comment_author_url;}else{echo 'Website URL';} ?>" size="32" tabindex="3" /></div>
                            <?php endif; ?>
                            <label>Twitter</label>
                            <div class="comment-input"><input type="text" name="twitter" id="twitter" value="<?php if($comment_author_url != ""){echo $comment_author_twitter;}else{echo 'Twitter Name';} ?>" size="32" tabindex="3" /></div>
                            <label>Message</label>
                            <div class="comment-texarea"><textarea name="comment" id="comment" cols="40" rows="10" tabindex="4" class="comment"></textarea></div>
                            <div class="checkbox">
                                <input type="checkbox" id="email_subscribe" name="email_subscribe" />
                                Subscribe to these comment via email
                            </div>                            
                            
                            <input type="image" src="<?php bloginfo('template_directory'); ?>/images/layout/submit-comment.png" class="submit_button" id="comment_submit" value="Submit Comment" name="cmdSubmit" />
                            <input type="hidden" id="comment_post_id" name="comment_post_ID" value="<?php echo $id; ?>" />
                            <input type="hidden" id="comment_parent_id" name="comment_parent_id" value="0" />                         
                            <?php do_action('comment_form', $post->ID); ?>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
    <?php
        endif; // if you delete this the sky will fall on your head
    ?>
</div>

 <?php 
		/*****************************/
		/* Threaded Replies Function */
		function fetch_comments($comment_id, $i)
			{		
				global $wpdb;
				require('wp-load.php');
				$sql = "SELECT * FROM $wpdb->comments WHERE comment_parent = ".$comment_id;
				$child_comments =  $wpdb->get_results($sql);				
				$thread_count = 0;
				if(count($child_comments) !== 0) :
					$thread_count++ 
	?>
					<div class="threaded-comments">
						<?php
							foreach($child_comments as $sub_comment) :
								$this_comment = get_comment($sub_comment->comment_ID);
								$comment_table = $wpdb->prefix . "ocmx_comment_meta";
								$sub_comment_meta_sql = "SELECT * FROM $comment_table WHERE commentId = ".$sub_comment->comment_ID." LIMIT 1";
								$sub_comment_meta = $wpdb->get_row($sub_comment_meta_sql);
						?>
                            <div class="thread-comment">
                                <div class="user">
									<?php echo get_avatar( $this_comment, 40); ?>
                                </div>
                                <div class="comment-post">
                                    <div class="container-header-light-normal"><span></span></div>
                                    <div class="comment-content clearfix">
                                        <h3>
                                        	<?php if($sub_comment->comment_author_url !== "http://" && $sub_comment->comment_author_url !== "") : ?>
                                               <a href="<?php echo $sub_comment->comment_author_url; ?>" class="commentor_url" name="comment-<?php echo $sub_comment->comment_ID; ?>" rel="nofollow"> <?php echo $sub_comment->comment_author; ?></a>
                                            <?php else : ?>
                                                 <?php echo $sub_comment->comment_author; ?>
                                            <?php endif; ?>
                                            <?php if($sub_comment_meta->twitter !== "") : ?><span class="twitter-link"><a href="http://twitter.com/<?php echo $sub_comment_meta->twitter; ?>" class="commentor_url" rel="nofollow">@<?php echo $sub_comment_meta->twitter; ?></a></span><?php endif; ?>
                                        	<span class="comment-date"><?php echo date('F d Y', strtotime($sub_comment->comment_date)); ?> <?php echo date("H\:i a", strtotime($sub_comment->comment_date)); ?></span>
										</h3>
                                        <?php if ($sub_comment->comment_approved == '0') : ?>
                                            <p>Comment is awaiting moderation.</p>
                                        <?php else :
                                            $use_comment = apply_filters('wp_texturize', $this_comment->comment_content);
                                            $use_comment = str_replace("\n", "<br>", $use_comment);
                                            echo "<p>".$use_comment."</p>";
                                        endif; ?>
                                    </div>
                                    <div class="container-footer-light-normal"><span></span></div>
                                </div>
                            </div>
						<?php
							endforeach;
						?>
					</div>
	<?php
				endif;
			}
	?>