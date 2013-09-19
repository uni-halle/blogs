<?php get_header();?>
<div id="content">
<div id="content-main">
		<?php if ($posts) {
				if (get_settings('mistylook_asideid') != "")
					$AsideId = get_settings('mistylook_asideid');
				function ml_hack($str)
				{
					return preg_replace('|</ul>\s*<ul class="asides">|', '', $str);
				}
				ob_start('ml_hack');
				foreach($posts as $post)
				{
					the_post();
				?>
				<?php if ( in_category($AsideId) && !is_single() ) : ?>
					<ul class="asides">
						<li id="p<?php the_ID(); ?>">
							<?php echo wptexturize($post->post_content); ?>
							<br/>
							<p class="postmetadata"><?php comments_popup_link('(0)', '(1)','(%)')?>  | <a href="<?php the_permalink(); ?>" title="<?php _e('Permalink:','ml');?> <?php echo wptexturize(strip_tags(stripslashes($post->post_title), '')); ?>" rel="bookmark">#</a> <?php edit_post_link(__('(edit)','ml')); ?></p>
						</li>
					</ul>
				<?php else: // If it's a regular post or a permalink page ?>
				<div id="post-<?php the_ID(); ?>" <?php if (function_exists('post_class')){ post_class(); } else { echo 'class="post"' ;} ?>>
        <div class="posttitle">
          <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info">            
            <?php the_time(__('M jS, Y','ml')) ?> <?php _e('by','ml');?> <?php the_author_posts_link() ?> <?php edit_post_link(__('Edit','ml'), '', ' | '); ?> </p>          
				</div>

				<div class="entry">
					<?php the_content(__('Continue Reading &raquo;','ml')); ?>
					<?php wp_link_pages(); ?>
					<p class="postmetadata"><?php if (function_exists('the_tags')) the_tags(__('Tags: ','ml'), ', ', '<br/>'); ?> </p>
				</div>

				<p class="postmetadata"><?php _e('Posted in','ml');?> <?php the_category(', ') ?> | <?php comments_popup_link(__('No Comments &#187;','ml'), __('1 Comment &#187;','ml'), __('% Comments &#187;','ml'),'',__('Comments Off','ml')); ?></p>
				<?php comments_template(); ?>
			</div>
			<?php endif; // end if in category ?>
			<?php
				}
				 ob_end_flush();
			}
			else include_once(TEMPLATEPATH.'/notfound.php');
		?>
		<p align="center"><?php posts_nav_link(' - ',__('&#171; Newer Posts','ml'),__('Older Posts &#187;','ml')) ?></p>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>
