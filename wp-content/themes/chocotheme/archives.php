<?php
/*
 *Template Name: Archives
 *
 * @package WordPress
 * @subpackage Choco
 */
?>
<?php get_header(); ?>
					<div class="post archives">
						<div class="entry">
							<?php get_search_form(); ?>
							<hr />
							<h3><?php _e( 'Archives by Month:', 'choco' ); ?></h3>
							<ul>
								<?php wp_get_archives( 'type=monthly' ); ?>
							</ul>
							<hr />
							<h3><?php _e( 'Archives by Subject:', 'choco' ); ?></h3>
							<ul>
								<?php wp_list_categories(); ?>
							</ul>
						</div>
					</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>