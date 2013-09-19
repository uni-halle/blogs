<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>
			<div id="page-title">
				<div class="page-title-content">
					<h3 class="page-title"><?php the_title(); ?></h3>
					 <div class="post-meta-single">
						<div class="box">
							<?php include (TEMPLATEPATH . '/searchform.php'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="left-content">
			<div class="post">
				<h2>Links:</h2>
					<ul>
					<?php wp_list_bookmarks(); ?>
					</ul>
			</div>
		</div>
	</div>
	<div id="right-col">
		<?php get_sidebar(); ?>
	</div>
</div></div>
<?php get_footer(); ?>