<?php get_header(); ?>

<div id="post-container">
	<div id="posts">

<?php if(have_posts()) : ?>

<h2 class="archive">Search result for '<?php echo strip_tags($_GET['s']) ?>'</h2>

	<div class="post-list">
<?php while(have_posts()) : the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">
		<div class="post-gravatar">
			<a href="<?php echo get_author_posts_url($authordata->ID); ?>"><?php echo get_avatar(get_the_author_email(), '50') ?></a>
		</div>
		<div class="post-text">
			<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			<div class="meta">
				<?php the_time('j F Y'); echo ' at '; the_time('H:i'); ?> - 
				<a href="<?php the_permalink(); ?>#respond" class="respondlink notajax" id="respondlink-<?php echo $post->ID; ?>">Comments</a>
				<?php edit_post_link('Edit', ' - ', '') ?>
			</div>
			<div class="commentcontainer">
			<?php
				$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' AND comment_type = '' ORDER BY comment_date", $post->ID));
				$count = count($comments);
				if($count >= 3) { ?>
					<div class="index-comment morecomments"><a class="notajax" href="<?php the_permalink(); ?>#comments">View all <?php echo $count; ?> comments</a></div>
		<?php	}
				if($count != 0) {
					$shown = array($count - 2, $count - 1);
					$i = 0;
					foreach($shown as $id) {
						if(isset($comments[$id])) {
							$i++;
							global $comment;
							$comment = $comments[$id];	?>
							<div class="index-comment">
								<div class="ic-avatar"><?php echo get_avatar(get_comment_author_email(), '37'); ?></div>
								<div class="ic-text">
									<div class="ic-meta ic-author"><?php echo get_comment_author_link(); ?></div>
									<div class="ic-content"><?php echo get_comment_excerpt() ?></div>
									<div class="ic-meta ic-date"><?php echo get_comment_date('j F y'); ?> at <?php echo get_comment_date('H:i'); ?></div>
								</div>
							</div>
			<?php		}
					}
				} ?>
			</div>
			
			<?php if($post->comment_status == 'open' && get_option('slf_ajax') == 1 && $count != 0) { ?>
					<div class="index-comment"><textarea class="respondtext">Write a comment..</textarea></div><?php 
				} ?>
			
			<div id="commentform-<?php echo $post->ID; ?>" class="index-comment comment-form" style="display:none;">
			<?php if($post->comment_status != 'open') { ?>
				Comment form currently closed..
			<?php } else { ?>
				<form action="<?php bloginfo('wpurl') ?>/wp-comments-post.php" method="post">
				<?php if($user_ID) { ?>
					<div class="form_login ic-avatar">
							<?php echo get_avatar($user_email, '37'); ?>
					</div>
				<?php } else { ?>
					<div class="form_input">
						<input class="author focus" name="author" type="text" />
						<label for="author">Name</label>
					</div>
					<div class="form_input">
						<input class="email" name="email" type="text" />
						<label for="email">Email</label>
					</div>
					<div class="form_input">
						<input class="url" name="url" type="text" />
						<label for="url">Website</label>
					</div>
				<?php } ?>
					<div class="form_comment">
						<textarea name="comment" class="focus <?php if($user_ID) { echo "commenttextright"; } ?>"></textarea>
					</div>
					<div class="form_submit">
						<input type="submit" name="submit" value="Comment" class="submit" />
					</div>
					<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
				</form>
			<?php } ?>
			</div>
			
		</div>
	</div>
<?php endwhile; ?>
		<?php if(get_next_posts_link() != "" && is_paged()) { ?><input type="hidden" id="nextpage" value="<?php next_posts(); ?>" /><?php } ?>
	</div>
	
	<div class="navigation">
		<div class="next"><?php next_posts_link('Show Older Posts..') ?></div>
		<div class="prev"><?php previous_posts_link('Show Newer Posts..') ?></div>
	</div>
	
<?php else : ?>
	There are no results for <?php echo strip_tags($_GET['s']) ?>. Make sure all words are spelt correctly.
<?php endif; ?>
		<input type="hidden" name="title" value="<?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" />
	</div>
</div>
<?php get_footer(); ?>