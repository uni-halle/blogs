<?php get_header(); ?>

<div id="blog">
		<?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        
        <div class="post" id="post-<?php the_ID(); ?>">
        
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <div class="date">{ Posted on <?php the_time('M d Y'); ?> by <?php the_author(); ?> }</div>
        
        
        <div class="meta">
      <?php the_tags('Tags : ', ', ', '<br />'); ?> Categories : <?php the_category(', ') ?> <?php edit_post_link('Edit', ' | ', ' | '); ?>
        </div><!--meta-->
        
        <!-- gallerycode -->
        <p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
        <div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>
        <!-- gallerycode -->
        
        <div class="content"><?php the_content('Read More &raquo;'); ?></div>
        
        <!-- gallerynavigation -->
        <div class="imgnav">
        <div class="imgleft"><?php previous_image_link() ?></div>
        <div class="imgright"><?php next_image_link() ?></div>
        </div>
        <br clear="all" />
        <!-- gallerynavigation -->
        	
            <div class="postmetadata">
					<small>
						This entry was posted
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
						and is filed under <?php the_category(', ') ?>.
						You can follow any responses to this entry through the <?php comments_rss_link('RSS 2.0'); ?> feed.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							Both comments and pings are currently closed.

						<?php } edit_post_link('Edit this entry.','',''); ?>

					</small>
				</div>
        <?php comments_template(); ?>
        </div><!--post-->
        
                <?php endwhile; ?>
        
                <div class="navigation">
                    <div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
                    <div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
                </div>
        
            <?php else : ?>
                <div class="post">
                <h2 class="center search">Search could not find anything!</h2>
                <p class="center">Sorry, but you are looking for something that isn't here.</p>
                </div>
            <?php endif; ?>
</div><!--blog-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>