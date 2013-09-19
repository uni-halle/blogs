			<!--For Theme Options-->
				<?php
					global $options;
					foreach ($options as $value) {
					if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
					}
				?>
			<!--End of For Theme Options-->

			<div id="sidebar">
			<?php if ($mb_sidebar_tabs == "true") {?>
				<div id="tab-container">
					<ul id="tabnav">
						<li><a href="#first" id="tab_first" class="tab" style="margin-right:20px;">Popular</a></li>
						<li><a href="#second" id="tab_second" class="tab" style="margin-right:20px;">Recent</a></li>
						<li><a href="#third" id="tab_third" class="tab">Categories</a></li>
					</ul>
					<div id="panel_first" class="panel">
						<ul>
							<?php most_popular_posts(); ?>
						</ul>
					</div>
					<div id="panel_second" class="panel">
						<ul>
							<?php query_posts('showposts=5'); ?>
								<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
								<li>
									<a href="<?php the_permalink() ?>">
										<?php the_title() ?>
									</a>
								</li>
							<?php endwhile; endif; ?>
						</ul>
					</div>
					<div id="panel_third" class="panel">
						<ul>
							<?php wp_list_categories('orderby=name&order=ASC&depth=-1&title_li=');?>
						</ul>
					</div> 
				</div>
				<?php }?>
				<?php if ($mb_sidebar_twitter == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-twitter">Icon</span>My Tweets</h2>
					<div class="clear"></div>
					<ul id="twitter_update_list">
						<li style="display:none;"></li>
					</ul>
					<a href="http://twitter.com/<?php echo $mb_twitter_name;?>" class="twitter-link">Follow me on <span class="italic">Twitter</span>.</a>
				</div>
				<?php }?>
				<?php if ($mb_sidebar_popular_posts == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-popular-posts">Icon</span>Popular Posts</h2>
					<div class="clear"></div>
					<ul>
						<?php most_popular_posts(); ?>
					</ul>
				</div>
				<?php }?>
				<?php if ($mb_sidebar_recent_posts == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-recent-posts">Icon</span>Recent Posts</h2>
					<div class="clear"></div>
					<ul>
						<?php query_posts('showposts=5'); ?>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<li>
								<a href="<?php the_permalink() ?>">
									<?php the_title() ?>
								</a>
							</li>
						<?php endwhile; endif; ?>
					</ul>
				</div>
				<?php }?>
				<?php if ($mb_sidebar_recent_comments == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-recent-comments">Icon</span>Recent Comments</h2>
					<div class="clear"></div>
					<?php
						$number=5; // number of recent comments desired
						$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $number");
					?>
					<ul>
						<?php
							if ( $comments ) : foreach ( (array) $comments as $comment) :
							echo  '<li>' . sprintf(__('%1$s on %2$s'), get_comment_author_link(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
							endforeach; endif;
						?>
					</ul>
				</div>
				<?php }?>
				<?php if ($mb_sidebar_categories == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-categories">Icon</span>Categories</h2>
					<div class="clear"></div>
					<ul>
						<?php wp_list_categories('orderby=name&order=ASC&depth=-1&title_li=');?>
					</ul>
				</div>
				<?php }?>
				<?php if ($mb_sidebar_search == "true") {?>
				<div class="sidebar-box">
					<h2 class="with-icon"><span id="sidebar-search">Icon</span>Search the Site</h2>
					<div class="clear"></div>
					<div class="search-box">
						<form action="<?php bloginfo('siteurl');?>" method="get">
							<div>
								<input type="text" onblur="clickrecall(this,'Search.')" onclick="clickclear(this, 'Search.')" value="Search." name="s" id="search" class="search-input"/>
								<input type="submit" value="Go" class="search-button"/>
							</div>
						</form>
					</div>
				</div>
				<?php }?>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("sidebar") ) : ?>
				<?php endif; ?>
			</div>			
<!--End of Sidebar.php-->