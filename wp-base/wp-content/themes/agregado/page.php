<?php get_header(); ?>

		<div id="main" class="clearfloat">

<?php include_once (TEMPLATEPATH . "/child_navigation.php"); ?>

<?php
	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">

				<div class="submenu" id="sidebar">

				<?php if ($children) { ?>  
				<div class="widget">
					<h3>In this section:</h3>
					
					<ul class="page-parents">
					<?php if ($section_overview) {echo $section_overview;} ?>
					<?php echo $children; ?>
					</ul>
				</div>
				<?php } ?>
					
					<div class="widget">
					<h3>Pages</h3>
					<ul>
					<?php wp_list_pages('title_li=&depth=1'); ?>
					</ul>
					</div>
					
					</div><!--END SUBMENU-->
  
  	<h2><?php if ($section_title) {echo $section_title;} else { the_title(); } ?></h2>
	<h3>
			<?php
			if ($section_title) {
				if ($parent->post_parent) {
					if ($grandparent->post_parent) {
						if($greatgrandparent->post_parent) {
							if($greatgreatgrandparent->post_parent) {
								echo $greatgreatgrandparent->post_title;
							}
							echo $greatgrandparent->post_title;
						}
						echo $grandparent->post_title;
					}
					echo $parent->post_title;
				}
				the_title(' / ');
			}
			else {
				if ($section_overview_title) {
					echo $section_overview_title;
				}
				else {
					echo 'Overview';
				}
			}?></h3>
			
		
		<div class="entry" id="content">
			<?php the_content(); ?>
			 <?php edit_post_link('Edit post', '<p>', '</p>'); ?>
			</div><!--END ENTRY-->
		
		</div><!--END POST-->
		
		
	<?php endwhile; endif; ?>
	

</div><!--END MAIN-->


</div><!--END WRAPPER-->
</div><!--END TOP-->
<?php get_footer(); ?>
