<div class="sidebar">
<ul>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>
<li><h3>Inside</h3></li>


			<li>
				<h3><?php _e('Pages', 'sandbox') ?></h3>
				<ul>
<?php wp_list_pages('title_li=&sort_column=post_title' ) ?>
				</ul>
			</li>

			<li>
				<h3><?php _e('Categories', 'sandbox'); ?></h3>
				<ul>
<?php wp_list_cats('sort_column=name&hierarchical=1') ?>

				</ul>
			</li>

			<li>
				<h3><?php _e('Archives', 'sandbox') ?></h3>
				<ul>
<?php wp_get_archives('type=monthly') ?>

				</ul>
			</li>
<?php endif; // end sidebar widgets  ?>
		</ul>
</div><!-- #sidebar -->
