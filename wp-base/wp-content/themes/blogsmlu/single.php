<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
	
		<div <?php post_class() ?>>
			
			<p class="date"><?php the_time('j.'); ?>
				<span><?php the_time('M'); ?></span>
				<?php the_time('Y'); ?>
			</p>
	       	<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
	       <p class="author">
	       <?php _e('Written by ','blogsmlu'); ?> <?php the_author_posts_link(); ?>
	       </p>
	
	           <div class="entrytext">
		           <?php the_content(__('[ Read On ... ]', 'blogsmlu')); ?>
		           <?php link_pages(__('Pages: ', 'blogsmlu'), '', 'number'); ?>
			
					<p class="postmetadata">
						<?php the_category(', ') ?> | <a href="<?php comments_link(); ?>" title=""><?php comments_number(__( 'No Comments', 'blogsmlu'), __('1 Comment', 'blogsmlu'), __('% Comments', 'blogsmlu'));?></a> <?php edit_post_link(__('Edit ', 'blogsmlu'), ' | ', ''); ?>
					</p>
					<p class="postmetadata">
					<?php the_tags(__('Tags: ', 'blogsmlu'), ' | ', ''); ?>
					</p>
					
					<ul class="weitersagen">
						<li class="twitter"><a href="http://twitter.com/home?status=<?php the_title(); ?>:<?php the_permalink();?>" title="Weitertwittern">Twitter</a></li>
						<li class="delicious"><a href="http://del.icio.us/post?url=<?php the_permalink();?>&amp;title=<?php the_title(); ?>" title="Sichern bei Delicious">delicious</a></li>
						<li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>?t=<?php the_title(); ?>" title="Weitersagen auf Facebook">Facebook</a></li>
						<li class="studivz"><a href="http://www.studivz.net/Link/ExternLink/Url/?u=<?php the_permalink();?>&desc=<?php the_title(); ?>&prov=blogs.urz.uni-halle.de" title="Weitersagen auf StudiVZ">StudiVZ</a></li>
						<li class="mrwong"><a href="http://www.mister-wong.com/index.php?action=addurl&amp;bm_url=<?php the_permalink();?>&amp;bm_description=<?php the_title(); ?>" title="Mr Wong">Mr. Wong</a></li>
					</ul>
										
					<?php authorbox(); ?>

				</div>
			
		</div>
	
	<?php comments_template('', true); ?>
	
	<div class="pagenavigation2">
		<div class="alignright"><?php next_post_link(); ?></div>
		<div class="alignleft"><?php previous_post_link(); ?></div>
	</div>
	
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<p><?php _e('Oh snap, no entries found.', 'blogsmlu'); ?></p>
	
	<?php endif; ?>
	
</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>


