<div class="postid" id="post-<?php the_ID(); ?>">
    		<div class="story">
<!-- Title ************************************************************* -->
                            <div class="story_date"><span class="dt"><?php the_time('j'); ?></span><?php the_time('S'); ?><br /><span><?php the_time('F'); ?></span></div>
                            <div class="storyTitle">
                            	<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                                <p class="storyAuthor">Posted by <span><?php the_author(); ?></span> at <?php the_time('g:i A'); ?>. Placed in <?php the_category(', '); ?> category <?php edit_post_link('Edit','&nbsp;| &nbsp;'); ?></p>
                        	</div>
                            <div style="clear:both;"></div>
<!-- Close Title ************************************************************* -->

<!-- Main Content Text ************************************************************* -->
                               <div class="storyContent">
                                    <?php the_content('Read More...'); ?>
                                    <div style="clear:both;"></div>
                                    <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
                               </div>
                               
                                <?php if($feedback =='feedback')
                                      {
                                		} else { ?> 
                                    <div class="comment_template"> <?php comments_template();?> </div> <?php }// Get wp-comments.php template ?>
                        
<!-- Close Main Content Text ************************************************************* -->
            </div>
  </div><!-- close postid--> 