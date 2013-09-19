<?php
function ocmx_comment_options (){
	if($_POST["startrow"]) :
		$startrow = $_POST["startrow"];
		$endrow = ($_POST["startrow"] + 15);
	elseif($_GET["startrow"]) :
		$startrow = $_GET["startrow"];
		$endrow = ($_GET["startrow"] + 15);
	else :
		$startrow = "0";
		$endrow = 15;
	endif;
	global $options, $themename, $manualurl, $wpdb;
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";	
		$count_subscribers = $wpdb->get_results("SELECT * FROM $comment_table");		
		$fetch_subscribers = $wpdb->get_results(
			"SELECT $wpdb->comments.*,  $comment_table.*
			FROM $wpdb->comments INNER JOIN $comment_table
			ON $wpdb->comments.comment_ID = $comment_table.commentId
			ORDER BY $wpdb->comments.comment_post_ID ASC, $wpdb->comments.comment_author ASC, $wpdb->comments.comment_author_email ASC, $comment_table.twitter ASC, $comment_table.email_subscribe DESC
			LIMIT $startrow, $endrow");
		
		if(isset($_POST["comment_changes"])) :
			$users_deleted = 0;
			$users_blocked = 0;
			$users_subs = 0;
			wp_cache_flush(); 
			foreach($fetch_subscribers as $subscriber_details) : 
				$delete_key = "delete_comment-".$subscriber_details->commentId;
				$block_key = "block_comment-".$subscriber_details->commentId;
				$subs_key = "mail_comment-".$subscriber_details->commentId;
				if(isset($_POST[$delete_key])) :
					$main_sql = "DELETE FROM $wpdb->comments WHERE comment_ID = ".$subscriber_details->commentId;
					$meta_sql = "DELETE FROM $comment_table WHERE commentId = '".$subscriber_details->commentId."'";
					$check_post = get_post($subscriber_details->comment_post_ID);
					if($wpdb->query($main_sql) == "1"  && $wpdb->query($meta_sql) == "1") :
						if($check_post->comment_count !== 0):
							$update_posts = "UPDATE $wpdb->posts SET comment_count = ".($check_post->comment_count - 1)." WHERE `ID` = ".$subscriber_details->comment_post_ID;
							$wpdb->query($update_posts);
						endif;			
						$users_deleted += 1;
					endif;
				else :
					if(isset($_POST[$block_key])): $block_user = 1; else: $block_user = 0; endif;
					if(isset($_POST[$subs_key])): $subs_user = 1; else: $subs_user = 0; endif;
					$update_sql = "UPDATE $comment_table
						SET  block_user = ".$block_user.",
						email_subscribe = ".$subs_user."
						WHERE commentId = ".$subscriber_details->commentId;					
					$wpdb->query($update_sql);
					
					if(isset($_POST[$block_key])) :
						$users_blocked += 1;
					endif;
					if(isset($_POST[$subs_key])) :
						$users_subs += 1;
					endif;
				endif;
			endforeach;
			$fetch_subscribers = $wpdb->get_results(
				"SELECT $wpdb->comments.*,  $comment_table.*
				FROM $wpdb->comments INNER JOIN $comment_table
				ON $wpdb->comments.comment_ID = $comment_table.commentId
				ORDER BY $wpdb->comments.comment_post_ID ASC, $wpdb->comments.comment_author ASC, $wpdb->comments.comment_author_email ASC, $comment_table.twitter ASC, $comment_table.email_subscribe DESC
				LIMIT $startrow, $endrow");
		endif;	
		
		ocmx_header();
		?>

	<h2>OCMX Comments</h2>        
    <?php if(isset($_POST["comment_changes"])) :
        if($users_deleted !== 0 || $users_blocked !== 0 || $users_subs !== 0) :
            echo "<p class=\"success\">Your changes were successful. $users_deleted Deleted, $users_blocked Blocked, $users_subs Subscription Changes</p>";
        else :
            echo "<p class=\"error\">Your changes were not successful.</p>";				
        endif;
    endif; ?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>&startrow=<?php echo $startrow; ?>" id="ocmx_comment_form" method="post" enctype="multipart/form-data">
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>		
		<input type="hidden" name="comment_changes" value="1" />	
        <input type="hidden" name="startrow" value="<?php echo $startrow; ?>" />
        <?php
			$pages = (count($count_subscribers)/15);
			if($pages > 1) :
				echo "<p>Pages: ";
				for ($i = 1; $i < ($pages + 1); $i++)
					{
						if($startrow == ($i*15-15)) :
							echo "<a href=\"".$_SERVER['REQUEST_URI']."&startrow=".($i*15-15)."\"><strong>".$i."</strong></a>";
						else :
							echo "<a href=\"".$_SERVER['REQUEST_URI']."&startrow=".($i*15-15)."\">".$i."</a>";
						endif;
						if($i !== round($pages)) :
							echo " &middot; ";
						endif;
					}
				echo "</p>";
			endif;
		?>
        <div id="container">
			<div class="content_box">
                <div class="ocmx-tabs">                	
                    <ul class="tabs clearfix">
                    	<li class="selected"><a href="#">Manage</a></li>
                    </ul>
				</div>
               	<div class="ocmx-content">
                    <div class="header"><div></div></div>
                    <div class="content clearfix">
                        <!-- <div class="tablenav clearfix"> -->
                            <p></p>
                            <table class="widefat post fixed">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>E-Mail</th>
                                        <th>Twitter ID</th>
                                        <th width="8%">View</th>
                                        <th width="8%">Mail</th>
                                        <th width="8%">Block</th>
                                        <th width="8%">Delete</th>
                                    </tr>
                                </thead>                                
                                <?php 
									$last_post_id = "alternate";
									foreach($fetch_subscribers as $subscriber_details) : 
										$cId = $subscriber_details->commentId;										
									    if($last_post_id !== $subscriber_details->comment_post_ID) :
                                            if($use_class == "alternate") :
                                                $use_class = "";
                                            else:
                                                $use_class = "alternate";
                                            endif;
                                    ?>
                                        <tr class="<?php echo $use_class; ?>">
                                            <td colspan="7">
                                            	<strong>Post Title: </strong>
                                                <a href="<?php echo get_permalink($subscriber_details->comment_post_ID); ?>" class="std_link" target="_blank">
                                                    <?php echo get_the_title($subscriber_details->comment_post_ID); ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        endif;								
                                    ?>
                                    <tr class="<?php echo $use_class; ?>">
                                        <td><?php echo $subscriber_details->comment_author; ?></td>
                                        <td><?php echo $subscriber_details->comment_author_email; ?></td>
                                        <td>
											<input type="hidden" name="post_id-<?php echo $cId; ?>" value="1" />	
    	                                    <a href="http://twitter.com/<?php echo $subscriber_details->twitter; ?>" class="std_link" target="_blank">
												<?php echo $subscriber_details->twitter; ?>
                                            </a>
										</td>
										<td class="t_center">
                                        	<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&amp;delete_comment=1&amp;comment_id=<?php echo $cId; ?>" class="std_link" id="view-comment-<?php echo $cId; ?>" title="View Comment">
	                                        	<img src="<?php bloginfo('template_directory'); ?>/ocmx/images/comment.gif" alt="view" />
                                            </a>
										</td>
                                        <td class="t_center">
											<?php if($subscriber_details->email_subscribe == 1): ?>
                                            	<input type="checkbox" checked="checked" name="mail_comment-<?php echo $cId; ?>"  id="mail_comment-<?php echo $cId; ?>"/>
                                            <?php else: ?>
	                                        	<input type="checkbox" name="mail_comment-<?php echo $cId; ?>"  id="mail_comment-<?php echo $cId; ?>"/>
											<?php endif?>
										</td>
										<td class="t_center">
											<?php if($subscriber_details->block_user == 1): ?>
                                        		<input type="checkbox" checked="checked" name="block_comment-<?php echo $cId; ?>"  id="block_comment-<?php echo $cId; ?>"/>
											<?php else : ?>
                                        		<input type="checkbox" name="block_comment-<?php echo $cId; ?>"  id="block_comment-<?php echo $cId; ?>"/>
                                            <?php endif?>
										</td>
                                        <td class="t_center">
                                        	<input type="checkbox" name="delete_comment-<?php echo $cId; ?>"  id="delete_comment-<?php echo $cId; ?>"/>
                                        </td>
                                    </tr>
                                    <tr class="no_display" id="ocmx-comment-<?php echo $cId; ?>">
                                    	<td colspan="7">
                                        	<p>
                                            	<?php
													$use_comment = apply_filters('wp_texturize', $subscriber_details->comment_content);
													$use_comment = str_replace("\n", "<br>", $use_comment);
													echo $use_comment;
												?>
											</p>
                                        </td>
                                    </tr>
                                <?php 
									$last_post_id = $subscriber_details->comment_post_ID;
								endforeach; ?>
                            </table>
                        <!-- </div> -->
                    </div>	
                    <div class="footer"><div></div></div>
					
                </div>                
			</div>  
		</div>
         <?php
			if($pages > 1) :
				echo "<p>Pages: ";
				for ($i = 1; $i < ($pages + 1); $i++)
					{
						if($startrow == ($i*15-15)) :
							echo "<a href=\"".$_SERVER['REQUEST_URI']."&startrow=".($i*15-15)."\"><strong>".$i."</strong></a>";
						else :
							echo "<a href=\"".$_SERVER['REQUEST_URI']."&startrow=".($i*15-15)."\">".$i."</a>";
						endif;
						if($i !== round($pages)) :
							echo " &middot; ";
						endif;
					}
				echo "</p>";
			endif;
		?>
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>							
		
		<div class="clearfix"></div>
	</form>

<div class="clearfix"></div>
 
 <?php

};
?>