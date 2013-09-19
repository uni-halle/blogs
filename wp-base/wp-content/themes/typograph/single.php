<?php get_header() ?>

			<div class="singlepost">
<?php the_post(); ?>

				<div id="post-<?php the_ID(); ?>">
				<div class="cat-links"><?php printf(__('%s'), get_the_category_list(', ')) ?></div>
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="author">Posted by <?php the_author_posts_link(); ?> on <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>.</div>
				<div class="entry-content">
				<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>').''); ?>

					<?php wp_link_pages('before=<div class="page-link">' .__('Pages:') . '&after=</div>') ?>
				</div>

				
	<div class="postInfo">
		<ul>
		  	<!-- Inserts Trackback URI if pings are enabled -->
			<?php if ( pings_open() ) { ?>
			<li class="trackback"><a title="Trackback-URL for &#39;<?php the_title() ?>&#39;" href="<?php trackback_url() ?>" rel="nofollow">Trackback URL</a></li>
			<?php } ?>

			<!-- Inserts link to RSS Comments feed if comments are enabled -->
			<?php if ( comments_open() ) { ?>
			<li class="feed"><span title="Subscribe to comments feed"><?php comments_rss_link('Comments feed for this post') ?></span></li>
			<?php } ?>

			<!-- Inserts Tag links if tags are defined for the post -->		  
			<?php if ( has_tag() ) { ?> 
			<li class="tags"><span title="tags"><?php the_tags('Tags: ', ', ', ''); ?> </span></li>
			<?php } ?>
		</ul>
	</div><!-- END postInfo -->
		
		<!-- Get wp-comments.php template -->
		<?php comments_template(); ?>

</div><!-- END post-number -->
				
				
			</div><!-- END .post -->
		</div><!-- END #content -->

	<?php get_sidebar() ?>
</div><!-- END #container -->

<?php get_footer() ?>
