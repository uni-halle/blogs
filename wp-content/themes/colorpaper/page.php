<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div id="page-title">
				<div class="page-title-content">
					<h3 class="page-title"><?php the_title(); ?></h3>
					 <div class="post-meta-single">
						Written by <span class="orange weight-normal"><?php the_author_link(); ?></span> on <?php the_time('l, F jS, Y') ?>
							<br /><br />
						<div class="box">
							<?php if (('open' == $post-> comment_status)) : ?>
								Comments <a href="#respond">are open</a> on this page, you can <a href="#respond">leave a response</a>.
							<?php else: ?>
								Comments are <strong>closed.</strong>
							<?php endif; ?>
							<?php if(('open' == $post->ping_status)) : ?>
								You may <?php if(('open' == $post-> comment_status)): ?>also<?php endif; ?> leave a <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your site.
							<?php endif; ?>
							<?php wp_link_pages(array('before' => '<strong>Pages:</strong> ', 'after' => '', 'next_or_number' => 'number')); ?>
							<?php edit_post_link('Edit this entry.', '', ''); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="left-content">
			<div class="post">
				<div class="post-date"><span class="month block"><?php the_time('M'); ?> </span><span class="day"><?php the_time('d'); ?> </span></div>
				<?php the_content(''); ?>
			</div>
			<?php comments_template(); ?>
		</div>
	<?php endwhile; endif; ?>
	</div>
	<div id="right-col">
		<?php get_sidebar(); ?>
	</div>
</div></div>
<?php get_footer(); ?>

