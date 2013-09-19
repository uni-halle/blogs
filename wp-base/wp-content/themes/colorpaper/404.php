<?php get_header(); ?>
			<div id="page-title">
				<div class="page-title-content">
					<h3 class="page-title">404 - Page Not Found</h3>
					 <div class="post-meta-single">
						<div class="box">
							<p><?php include (TEMPLATEPATH . '/searchform.php'); ?></p>
							<p class="light">Search <?php bloginfo('name'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="left-content">
			<div class="post">
				<h3>404 - Page Not Found</h3>
				<h5 class="light medium">Something has gone horribly wrong. Please go back, or try some of the links below:</h5>
				<h2>Archives by Date:</h2>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
				<h2>Archives by Subject:</h2>
				<ul>
					 <?php wp_list_categories('title_li='); ?>
				</ul>
			</div>
		</div>
	</div>
	<div id="right-col">
		<?php get_sidebar(); ?>
	</div>
</div></div>
<?php get_footer(); ?>