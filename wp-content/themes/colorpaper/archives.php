<?php
/*
Template Name: Archives
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
				<h2>Archives by Month:</h2>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div>
			<div class="post">
				<h2>Archives by Subject:</h2>
				<ul>
					 <?php wp_list_categories(); ?>
				</ul>
			</div>
		</div>
	</div>
	<div id="right-col">
		<?php get_sidebar(); ?>
	</div>
</div></div>
<?php get_footer(); ?>